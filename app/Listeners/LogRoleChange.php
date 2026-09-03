<?php

namespace App\Listeners;

use App\Services\ActivityLogger;

class LogRoleChange
{

    protected ActivityLogger $logger;


    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }


    public function handle($event): void
    {

        $user = $event->user;


        $this->logger->log(

            'ROLE_UPDATED',

            'users',

            $user->id,

            'Role user berhasil diperbarui'

        );

    }

}
