<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;

final class RegisterUserAction
{
    /**
     * Crée un nouvel utilisateur et déclenche l'événement d'inscription.
     *
     * @param  array{name: string, email: string, password: string}  $data
     */
    public function execute(array $data): User
    {
        $baseHandle = \Illuminate\Support\Str::slug($data['name'], '');
        if (empty($baseHandle)) {
            $baseHandle = 'user' . rand(1000, 9999);
        }

        $handle = $baseHandle;
        $counter = 1;
        while (User::where('handle', $handle)->exists()) {
            $handle = $baseHandle . $counter++;
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'handle' => strtolower($handle),
            'password' => Hash::make($data['password']),
        ]);

        event(new Registered($user));

        return $user;
    }
}
