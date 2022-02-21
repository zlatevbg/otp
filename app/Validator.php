<?php

namespace App;

use App\Helper;

class Validator
{
    public Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function validateParameters($parameters): void
    {
        $this->session->errors = [];

        foreach ($parameters as $param) {
            $this->{'validate' . ucwords($param)}();
        }

        if ($this->session->errors) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            Helper::abort(422);
        }
    }

    public function validateEmail(): void
    {
        if (!isset($_POST['email']) || empty($_POST['email'])) {
            $this->session->errors['email'] = 'Email is required';
            return;
        }

        $this->session->email = $_POST['email'];

        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $this->session->errors['email'] = 'Invalid email';
            return;
        }
    }

    public function validatePhone(): void
    {
        if (!isset($_POST['phone']) || empty($_POST['phone'])) {
            $this->session->errors['phone'] = 'Phone is required';
            return;
        }

        $this->session->phone = $_POST['phone'];

        $this->session->phone = ltrim(preg_replace('/^(00359|359)/', '', preg_replace('/[^0-9]/', '', $this->session->phone)), 0);

        if (mb_strlen($this->session->phone) < 9) {
            $this->session->errors['phone'] = 'Invalid phone number';
            return;
        }

        $this->session->phone = '359' . $this->session->phone;
    }

    public function validatePassword(): void
    {
        if (!isset($_POST['password']) || empty($_POST['password'])) {
            $this->session->errors['password'] = 'Password is required';
            return;
        }

        $this->session->password = $_POST['password'];

        if (mb_strlen($this->session->password) < 6) {
            $this->session->errors['password'] = 'Password must be at least 6 characters';
            return;
        }
    }

    public function validateCode(): void
    {
        if (!isset($_POST['code']) || empty($_POST['code'])) {
            $this->session->errors['code'] = 'SMS code is required';
            return;
        }

        $this->session->code = $_POST['code'];

        if (mb_strlen($this->session->code) != 6) {
            $this->session->errors['code'] = 'Invalid SMS code';
            return;
        }
    }
}
