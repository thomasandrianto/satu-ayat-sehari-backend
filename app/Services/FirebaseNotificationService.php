<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseNotificationService
{
    protected $messaging;

    public function __construct()
    {
        $credentials=config('firebase.projects.app.file');

        $this->messaging=(new Factory())
            ->withServiceAccount($credentials)
            ->createMessaging();
    }

    public function sendToToken(string $token,string $title,string $body): void
    {
        $message=CloudMessage::withTarget('token',$token)
            ->withNotification(
                Notification::create($title,$body)
            );

        $this->messaging->send($message);
    }

    public function sendToMultipleTokens(array $tokens,string $title,string $body): array
    {
        $message=CloudMessage::new()
            ->withNotification(
                Notification::create($title,$body)
            );

        $report=$this->messaging->sendMulticast(
            $message,
            $tokens
        );

        $failedTokens=[];

        foreach($report->failures() as $failure){
            $failedTokens[]=$failure->target()->value();
        }

        return [
            'success'=>$report->successes()->count(),
            'failure'=>$report->failures()->count(),
            'failed_tokens'=>$failedTokens,
        ];
    }
}