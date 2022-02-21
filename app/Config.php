<?php

namespace App;

class Config
{
    public string $url;
    public array $routes;
    public array $db;

    public function __construct()
    {
        $this->url = ''; // /otp/public

        $this->routes = [
            '/' => [
                'methods' => [
                    'get',
                ],
            ],
            '/register' => [
                'methods' => [
                    'post',
                ],
                'parameters' => [
                   'post' => [
                        'email',
                        'phone',
                        'password',
                   ],
                ],
            ],
            '/verify' => [
                'methods' => [
                    'get',
                    'post',
                ],
                'parameters' => [
                    'post' => [
                        'code',
                    ],
                ],
            ],
            '/resend' => [
                'methods' => [
                    'post',
                ],
            ],
        ];

        $this->db = [
            'driver' => 'mysql',
            'host' => 'mysql', // localhost
            'port' => '3306',
            'database' => 'otp',
            'username' => 'root',
            'password' => 'password',
            'charset' => 'utf8mb4',
        ];
    }
}
