<?php

function isEmailTaken($email)
{
    global $pdo;
    $stmt = $pdo->prepare("select count(*) from users where email = :email");
    $stmt->execute(['email' => $email]);
    return $stmt->fetchColumn() > 0;
}

function getRegisterRequest($request)
{
    return [
        'name'                  => trim(htmlspecialchars($request['name'])),
        'email'                 => trim(htmlspecialchars($request['email'])),
        'password'              => $request['password'],
        'password_confirmation' => $request['password_confirmation'],
    ];
}

function validateRegister($request)
{
    $errors = [];

    // Validate name
    if ($request['name'] == '') {
        $errors['name'] = "Nama lengkap wajib diisi";
    } elseif (mb_strlen($request['name']) > 150) {
        $errors['name'] = "Nama lengkap tidak boleh melebihi dari 150 karakter";
    }

    // Validate email
    if ($request['email'] == '') {
        $errors['email'] = "Email wajib diisi";
    } elseif (mb_strlen($request['email']) > 150) {
        $errors['email'] = "Email tidak boleh melebihi dari 150 karakter";
    } elseif (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Email yang dikirimkan tidak valid";
    } else {
        if (isEmailTaken($request['email'])) {
            $errors['email'] = "Email sudah didaftarkan sebelumnya";
        }
    }

    return $errors;
}