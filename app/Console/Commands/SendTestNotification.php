<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Device;
use App\Services\FirebaseNotificationService;

class SendTestNotification extends Command
{
    protected $signature = 'notification:test';

    protected $description = 'Send test push notification to all devices';

    public function handle(
        FirebaseNotificationService $firebase
    ): int {

        $devices = Device::query()
            ->where('platform', 'android')
            ->pluck('push_token')
            ->toArray();


        if (empty($devices)) {

            $this->error('No devices found');

            return self::FAILURE;

        }


        $firebase->sendToMultipleTokens(
            $devices,
            'Test Notifikasi',
            'Kalau ini muncul berarti push sudah jalan'
        );


        $this->info('Notification sent successfully');

        return self::SUCCESS;
    }
}