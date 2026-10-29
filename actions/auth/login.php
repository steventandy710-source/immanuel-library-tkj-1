<?php
require_once __DIR__ . "/../../config/database.php";
require_once __DIR__ . "/../../middleware/auth.php";

sessionStart();

if (isset($_POST['login']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];

    // Validate input
    $errors = [];
    if ($email == '') {
        $errors['email'] = 'Email wajib diisi';
    }
    if ($password == '') {
        $errors['password'] = 'Kata sandi wajib diisi';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old'] = $_POST;
        header('Location: ' . LOGIN_URL);
        exit;
    }

    // Check if email exists
    $query = "select id, name, email, password, role from users where email = :email";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        unset($_SESSION['errors'], $_SESSION['old']);
        header('Location: ../../pages/books/index.php');
        exit;
    } else {
        $_SESSION['errors'] = [
            'Login' => 'Email atau kata sandi salah'
        ];
        header('Location: ' . LOGIN_URL);
        exit;
    }
} else {
    header('Location: ' . LOGIN_URL);
    exit;
}