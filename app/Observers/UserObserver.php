<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ActivityLogger;

class UserObserver
{

    protected ActivityLogger $logger;


    public function __construct(ActivityLogger $logger)
    {
        $this->logger = $logger;
    }


    public function updated(User $user): void
    {

        if ($user->isDirty('role')) {

            $oldRole = $user->getOriginal('role');
            $newRole = $user->role;


            $this->logger->log(

                'ROLE_UPDATED',

                'users',

                $user->id,

                "Role berubah dari {$oldRole} menjadi {$newRole}"

            );

        }

    }

}
