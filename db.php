<?php

require_once('dotenv.php');
use DevCoder\DotEnv;

(new DotEnv(__DIR__ . '/dev.env'))->load();


class DB{
    public static function connect() {

        $host = getenv('DATABASE_DNS');
        $db = getenv('DATABASE_NAME');
        $user = getenv('DATABASE_USER');
        $pass = getenv('DATABASE_PASSWORD');
        $charset = 'utf8mb4';


        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // vychozi nastaveni
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new PDO($dsn, $user, $pass, $options);

        return $pdo;
    }
}