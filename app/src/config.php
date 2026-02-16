<?php
namespace App;

class Config {
    public const DB_SERVER_NAME = 'mysql';
    public const DB_USERNAME = 'root';
    public const DB_PASSWORD = 'secret123';
    public const DB_NAME = 'developmentdb';

    public function generateKey() : string {
        return bin2hex(openssl_random_pseudo_bytes(32));
    }
}