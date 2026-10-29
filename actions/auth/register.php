<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../middleware/auth.php";
require_once __DIR__ . "/../../validations/auth-validation.php";

if (isset($_POST['register']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = validateRegister($_POST);

    if (!empty($errors)) {
        sessionStart();
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
        header('Location: ' . REGISTER_URL);
        exit;
    }

    $request = getRegisterRequest($_POST);

    try {
        $stmt = $pdo->prepare("insert into users (name, email, password, role) values (:name, :email, :password, :role)");
        $stmt->execute([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => password_hash($request['password'], PASSWORD_DEFAULT),
            'role' => 'member'
        ]);

        sessionStart();
        $_SESSION['success'] = 'Registrasi berhasil. Silakan login.';
        header('Location: ' . LOGIN_URL);
        exit;
    } catch (PDOException $e) {
        sessionStart();
        $_SESSION['errors'] = ['Register' => 'Email sudah terdaftar'];
        header('Location: ' . REGISTER_URL);
        exit;
    }
} else {
    header('Location: ' . REGISTER_URL);
    exit;
}