<?php

namespace App;

use PDO;
use PDOException;

class DB extends PDO
{
    public Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;

        $dns = $this->config->db['driver'] . ':host=' . $this->config->db['host'] . ';port=' . $this->config->db['port'] . ';charset=' . $this->config->db['charset'];

        try {
            $conn = new PDO($dns, $this->config->db['username'], $this->config->db['password']);
            $conn->exec("CREATE DATABASE IF NOT EXISTS " . $this->config->db['database']);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }

        $dns = $this->config->db['driver'] . ':host=' . $this->config->db['host'] . ';port=' . $this->config->db['port'] . ';dbname=' . $this->config->db['database'];

        parent::__construct($dns, $this->config->db['username'], $this->config->db['password']);
    }

    public function createSchema(): void
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `users` (
            `id` BIGINT(20) AUTO_INCREMENT NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `phone` CHAR(12) NOT NULL,
            `password` VARCHAR(255) NOT NULL,
            `code` CHAR(6) NULL,
            `created_at` TIMESTAMP NULL,
            `verified_at` TIMESTAMP NULL,
            PRIMARY KEY (`id`))
            ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci';

        try {
            $this->exec($sql);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int) $e->getCode());
        }
    }
}
