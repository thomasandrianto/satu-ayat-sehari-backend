<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendDailyVerseNotificationJob;

class SendDailyVerseNotification extends Command
{
    protected $signature='notification:daily-verse';

    protected $description='Dispatch daily verse notification';

    public function handle(): int
    {
        SendDailyVerseNotificationJob::dispatch();

        $this->info(
            'Daily verse notification queued'
        );

        return self::SUCCESS;
    }
}