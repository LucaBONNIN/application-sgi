<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;
use OwenIt\Auditing\Models\Audit;

class UserAuthSubscriber
{
    public function handleUserLogin(Login $event): void
    {
        $this->auditEvent($event->user, 'login');
    }

    public function handleUserLogout(Logout $event): void
    {
        if ($event->user) {
            $this->auditEvent($event->user, 'logout');
        }
    }

    protected function auditEvent($user, string $type): void
    {
        $data = [
            'auditable_id' => $user->id,
            'auditable_type' => get_class($user),
            'event' => $type,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'tags' => 'authentication', // Utile pour filtrer plus tard
            'created_at' => now(),
            'updated_at' => now(),

            // Les champs user_type/id sont pour savoir "QUI" a fait l'action.
            // Dans un login, c'est l'utilisateur lui-même.
            'user_id' => $user->id,
            'user_type' => get_class($user),

            // Champs requis par la BDD mais vides ici
            'old_values' => [],
            'new_values' => [],
        ];

        Audit::create($data);
    }

    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [self::class, 'handleUserLogin']
        );

        $events->listen(
            Logout::class,
            [self::class, 'handleUserLogout']
        );
    }
}
