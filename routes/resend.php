<?php

use App\Helper;

$stmt = $db->prepare('SELECT * FROM users WHERE id = ? AND phone = ?');
$stmt->execute([
    $session->user,
    $session->phone,
]);
$user = $stmt->fetch(PDO::FETCH_OBJ);

if (!$user) {
    header("Location: $config->url/");
    Helper::abort(404);
} else {
    try {
        $code = Helper::randomNumber();
        $result = $db->prepare('UPDATE users SET code = ? WHERE id = ?')->execute([
            $code,
            $user->id,
        ]);

        if ($result) {
            $session->success = 'New SMS code sent successfully. SMS code: ' . $code;

            header("Location: $config->url/verify");
            Helper::abort();
        } else {
            $session->error = 'Your account is already verified.';

            header("Location: $config->url/");
            Helper::abort();
        }
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int) $e->getCode());
    }
}
