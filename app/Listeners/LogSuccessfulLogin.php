<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use App\Services\ActivityLogger;

class LogSuccessfulLogin
{

    protected ActivityLogger $logger;


    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }


    public function handle(Login $event): void
    {

        $this->logger->log(

            'LOGIN_SUCCESS',

            'users',

            $event->user->id,

            'User berhasil login ke sistem'

        );

    }

}
