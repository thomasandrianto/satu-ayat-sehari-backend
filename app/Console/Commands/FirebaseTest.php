<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Kreait\Firebase\Factory;

class FirebaseTest extends Command
{
    protected $signature = 'firebase:test';

    protected $description = 'Test Firebase connection';

    public function handle(): int
    {
        try {

            $credentials = config('firebase.projects.app.file');

            $factory = (new Factory())
                ->withServiceAccount($credentials);

            $messaging = $factory->createMessaging();

            $this->info('Firebase Messaging initialized successfully.');

            return self::SUCCESS;

        } catch (\Throwable $e) {

            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}