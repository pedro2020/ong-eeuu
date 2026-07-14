<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "ainbite_eeuu", "Theverve2020%", "ainbite_eeuu");

if ($conn->connect_error) {
    die("FALLA: " . $conn->connect_error);
}

echo "OK CONECTADO";
?>