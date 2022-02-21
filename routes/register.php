<?php

use App\Helper;

$stmt = $db->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([
    $session->email,
]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

if ($user) {
    $session->error = 'Whoops! User already exists.';
    header("Location: $config->url/");
    Helper::abort();
} else {
    try {
        $code = Helper::randomNumber();
        $stmt = $db->prepare('INSERT INTO users (email, phone, password, code, created_at) VALUES (?, ?, ?, ?, ?)')->execute([
            $session->email,
            $session->phone,
            Helper::hash($session->password),
            $code,
            date('Y-m-d H:i:s'),
        ]);

        $user = $db->lastInsertId();

        unset($session->email);
        unset($session->password);

        $session->user = $user;
        $session->success = 'User account created successfully. SMS code: ' . $code;

        header("Location: $config->url/verify");
        Helper::abort();
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int) $e->getCode());
    }
}
