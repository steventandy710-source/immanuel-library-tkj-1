<?php

require_once __DIR__ . "/../config/database.php";

function getUsers()
{
    global $pdo;
    $stmt = $pdo->prepare("select id, name, email, role from users");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getUser()
{
    global $pdo;
    // Ambil user pertama (atau sesi user jika ada)
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
        return $user;
    }
    // Fallback: ambil user pertama dari database
    $stmt = $pdo->prepare("select id, name, email, role from users limit 1");
    $stmt->execute();
    return $stmt->fetch();
}

function getProfile()
{
    global $pdo;
    // Ambil profile berdasarkan user terakhir login
    if (isset($_SESSION['user'])) {
        $userId = $_SESSION['user']['id'];
        $stmt = $pdo->prepare("select phone, address, bio from profiles where user_id = :user_id");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetch();
    }
    // Fallback: profile default
    return [
        'phone' => '0812-3456-7890',
        'address' => 'Jl. Merdeka No. 21, Pontianak, Kalimantan Barat',
        'bio' => 'Murid kelas XI TKJ yang gemar membaca novel fiksi dan buku pengembangan diri.',
    ];
}