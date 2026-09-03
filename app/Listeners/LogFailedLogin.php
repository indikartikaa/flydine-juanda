<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use App\Services\ActivityLogger;

class LogFailedLogin
{

    protected ActivityLogger $logger;


    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }


    public function handle(Failed $event): void
    {

        $email = $event->credentials['email'] ?? 'unknown';


        $this->logger->log(

            'LOGIN_FAILED',

            'users',

            null,

            'Percobaan login gagal untuk email: '.$email

        );

    }

}
