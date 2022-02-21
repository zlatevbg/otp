<?php

namespace App;

class Helper
{
    public static function randomNumber(int $length = 6): string
    {
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= mt_rand(0, 9);
        }

        return $result;
    }

    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function dd(mixed $obj): void
    {
        printf('<pre>%s</pre>', print_r($obj, true));
        self::abort();
    }

    public static function abort(int|null $code = null, string|null $message = null): void
    {
        if ($code) {
            if ($code == 422 && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
                $code = 302;
            }

            http_response_code($code);
        }

        exit($message);
    }

    public static function log(mixed $message): void
    {
        if (is_array($message) || is_object($message)) {
            $message = json_encode($message);
        }

        file_put_contents('../log-' . date('d.m.Y') . '.log', $message . "\n", FILE_APPEND);
    }
}
