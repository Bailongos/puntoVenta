<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'punto_de_venta';

$conn = new mysqli($host, $user, $password);

if ($conn->connect_error) {
    die('Error de conexión: ' . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS `$dbname` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db($dbname);
$conn->set_charset('utf8mb4');
date_default_timezone_set('America/Mexico_City');
