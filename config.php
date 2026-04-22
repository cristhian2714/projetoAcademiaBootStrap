<?php
$host = '127.0.0.1';
$db   = 'academia';
$user = 'root';
$pass = ''; // Senha padrão do XAMPP

$mysqli = new mysqli($host, $user, $pass, $db);

if ($mysqli->connect_error) {
    die(json_encode(["error" => "Database connection failed", "message" => $mysqli->connect_error]));
}

$mysqli->set_charset("utf8mb4");
