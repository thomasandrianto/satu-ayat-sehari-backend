<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Devotion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class DevotionController extends Controller
{
    /**
     * GET /api/devotion/today
     */
    public function today(): JsonResponse
    {
        $date = today()->toDateString();

        $devotion = Cache::remember(
            "devotion_today_{$date}",
            now()->endOfDay(),
            function () use ($date) {
                return Devotion::query()
                    ->whereDate('devotion_date', $date)
                    ->where('is_published', true)
                    ->first();
            }
        );

        if (! $devotion) {
            return $this->notFound($date);
        }

        return $this->success($devotion);
    }

    /**
     * GET /api/devotion/{date}
     */
    public function show(string $date): JsonResponse
    {
        $validator = Validator::make(
            [
                'date' => $date
            ],
            [
                'date' => [
                    'required',
                    'date_format:Y-m-d'
                ]
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid date format',
                'date' => $date
            ], 422);
        }

        if ($date > today()->toDateString()) {
            return response()->json([
                'success' => false,
                'message' => 'Devotion not available yet',
                'date' => $date
            ], 404);
        }

        $devotion = Cache::remember(
            "devotion_{$date}",
            now()->endOfDay(),
            function () use ($date) {
                return Devotion::query()
                    ->whereDate('devotion_date', $date)
                    ->where('is_published', true)
                    ->first();
            }
        );

        if (! $devotion) {
            return $this->notFound($date);
        }

        return $this->success($devotion);
    }

    /**
     * GET /api/devotions
     */
    public function index(Request $request): JsonResponse
    {
        $page = $request->integer('page', 1);

        $perPage = min(
            $request->integer('per_page', 30),
            100
        );

        $today = today()->toDateString();

        $cacheKey = "devotions_{$today}_{$page}_{$perPage}";

        $devotions = Cache::remember(
            $cacheKey,
            now()->addHours(6),
            function () use ($perPage, $today) {
                return Devotion::query()
                    ->where('is_published', true)
                    ->whereDate(
                        'devotion_date',
                        '<=',
                        $today
                    )
                    ->orderByDesc('devotion_date')
                    ->paginate($perPage);
            }
        );

        return response()->json([
            'success' => true,
            'message' => 'Devotions retrieved successfully',
            'data' => collect($devotions->items())
                ->map(
                    fn ($devotion) =>
                    $this->transformDevotion($devotion)
                )
                ->values(),
            'pagination' => [
                'current_page' => $devotions->currentPage(),
                'last_page' => $devotions->lastPage(),
                'per_page' => $devotions->perPage(),
                'total' => $devotions->total(),
            ],
        ]);
    }

    /**
     * Success response
     */
    private function success(Devotion $devotion): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Devotion found',
            'data' => $this->transformDevotion($devotion),
        ]);
    }

    /**
     * Not found response
     */
    private function notFound(string $date): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'Devotion not found',
            'date' => $date,
        ], 404);
    }

    /**
     * Transform model
     */
    private function transformDevotion(Devotion $devotion): array
    {
        return [
            'id' => $devotion->id,
            'devotion_date' => $devotion->devotion_date,
            'book' => $devotion->book,
            'chapter' => $devotion->chapter,
            'verse_start' => $devotion->verse_start,
            'verse_end' => $devotion->verse_end,
            'verse_text_en' => $devotion->verse_text_en,
            'verse_text_id' => $devotion->verse_text_id,
            'devotion_title' => $devotion->devotion_title,
            'devotion_text' => $devotion->devotion_text,
            'theme' => $devotion->theme,
        ];
    }
}