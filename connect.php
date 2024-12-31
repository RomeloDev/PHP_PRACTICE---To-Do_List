<?php
    define('host', 'localhost');
    define('user', 'root');
    define('password', '');
    define('database', 'todolist');

    $conn = new mysqli(host, user, password, database);

    if($conn->connect_error){
        die("Connection Failed: {$conn->connect_error}");
    }
?>