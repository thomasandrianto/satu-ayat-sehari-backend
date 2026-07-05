<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\Device;
use App\Models\Devotion;
use App\Services\FirebaseNotificationService;
use Carbon\Carbon;

class SendDailyVerseNotificationJob implements ShouldQueue
{
    use Queueable;

    public function handle(FirebaseNotificationService $firebase): void
    {
        $today=Carbon::today('Asia/Jakarta');

        $devotion=Devotion::whereDate(
            'devotion_date',
            $today
        )->first();

        if(!$devotion){
            return;
        }

        $devices=Device::query()
            ->where('platform','android')
            ->whereNotNull('push_token')
            ->get();

        if($devices->isEmpty()){
            return;
        }

        $tokens=$devices
            ->pluck('push_token')
            ->toArray();

        $result=$firebase->sendToMultipleTokens(
            $tokens,
            'Ayat Hari Ini',
            $devotion->devotion_title
        );

        if(!empty($result['failed_tokens'])){
            Device::whereIn(
                'push_token',
                $result['failed_tokens']
            )->update([
                'push_token'=>null
            ]);
        }
    }
}