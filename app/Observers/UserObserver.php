<?php

namespace App\Observers;
use App\Models\User;

class UserObserver
{
    public function created(User $user){
        if($user->role === 'user' || empty($user->role)){
            $user->customer()->create([
                'email' => $user->email,
                'name' => $user->name,
            ]);
        }
    }
}
