<?php
// Use absolute path to autoload.php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();

$host = $_ENV['DB_HOST'];
$username = $_ENV['DB_NAME'];
$password = $_ENV['DB_PASSWORD'];
$database = $_ENV['DB_DATABASE'];

// Create a new mysqli object
$conn = pg_connect("host=$host dbname=$database user=$username password=$password");

// Check if connection was successful
if (!$conn) {
    die("Connection failed: " . pg_last_error());
}
?>