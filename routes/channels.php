<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin-dashboard', function ($user) {
    // Izinkan semua user yang sudah login untuk mendengarkan.
    // Anda bisa menambahkan pengecekan role di sini jika perlu.
    return $user != null;
});
