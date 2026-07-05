<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * POST /api/device/register
     */
    public function register(
        Request $request
    ): JsonResponse {

        $validated = $request->validate([

            'device_id' => [
                'required',
                'string',
                'max:255',
            ],

            'push_token' => [
                'required',
                'string',
            ],

            'platform' => [
                'nullable',
                'string',
                'in:android,ios',
            ],

        ]);


        $device = Device::updateOrCreate(

            [
                'device_id' =>
                    $validated['device_id'],
            ],

            [

                'push_token' =>
                    $validated['push_token'],

                'platform' =>
                    $validated['platform']
                    ?? 'android',

                'last_seen_at' =>
                    now(),

            ]

        );


        return response()->json([

            'success' => true,

            'message' =>
                'Device registered successfully',

            'data' => [

                'id' =>
                    $device->id,

                'device_id' =>
                    $device->device_id,

            ],

        ]);

    }


    /**
     * POST /api/device/ping
     */
    public function ping(
        Request $request
    ): JsonResponse {


        $validated = $request->validate([

            'device_id' => [
                'required',
                'string',
            ],

        ]);


        $device = Device::where(
            'device_id',
            $validated['device_id']
        )
        ->first();


        if (! $device) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Device not found',

            ], 404);

        }


        $device->update([

            'last_seen_at' =>
                now(),

        ]);


        return response()->json([

            'success' => true,

            'message' =>
                'Device ping updated',

        ]);

    }
}