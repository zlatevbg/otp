<?php

namespace App;

class Session
{
    public const SESSION_STARTED = true;
    public const SESSION_NOT_STARTED = false;

    private bool $sessionState = self::SESSION_NOT_STARTED;

    private static object $instance;

    public static function getInstance(): object
    {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        self::$instance->startSession();

        return self::$instance;
    }

    public function startSession(): bool
    {
        if ($this->sessionState == self::SESSION_NOT_STARTED) {
            $this->sessionState = session_start();
        }

        return $this->sessionState;
    }

    public function __set(string $name, mixed $value): void
    {
        $_SESSION[$name] = $value;
    }

    public function &__get($name): mixed
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }

        $s = null;

        return $s;
    }

    public function __isset($name): bool
    {
        return isset($_SESSION[$name]);
    }


    public function __unset($name): void
    {
        unset($_SESSION[$name]);
    }

    public function destroy(): bool
    {
        if ($this->sessionState == self::SESSION_STARTED) {
            $this->sessionState = !session_destroy();
            unset($_SESSION);

            return !$this->sessionState;
        }

        return false;
    }
}
