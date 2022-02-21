<?php

namespace App;

use App\Helper;

class Router
{
    public Config $config;
    public array $routes;
    public string $route;
    public string $method;

    public function __construct(Config $config)
    {
        $this->config = $config;

        $this->routes = $this->config->routes;
        $this->route = $this->getRoute();
        $this->method = $this->getMethod();
    }

    public function getRoute(): string
    {
        return str_replace($this->config->url, '', $_SERVER['REQUEST_URI']);
    }

    public function getMethod(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function getMethods(): array
    {
        return $this->routes[$this->route]['methods'];
    }

    public function getParameters(): array
    {
        return $this->routes[$this->route]['parameters'][$this->method];
    }

    public function hasParameters(): bool
    {
        return isset($this->routes[$this->route]['parameters'][$this->method]);
    }

    public function validateRoute()
    {
        if ($this->route) {
            if (array_key_exists($this->route, $this->routes)) {
                if (!in_array($this->method, $this->routes[$this->route]['methods'])) {
                    Helper::abort(405, "405 Method Not Allowed: ($this->method). Allowed methods: " . implode(', ', $this->routes[$this->route]['methods']));
                }
            } else {
                Helper::abort(404, "404 Not Found: ($this->route) does not exists.");
            }
        }
    }
}
