<?php

use App\Models\Establecimiento;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function (User $user, int $id): bool {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('establecimiento.{establecimientoId}', function (User $user, int $establecimientoId): bool {
    if ($user->isAdmin()) {
        return true;
    }

    $establecimiento = Establecimiento::find($establecimientoId);

    return $establecimiento !== null && (int) $establecimiento->propietario_id === (int) $user->id;
});
