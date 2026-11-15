<?php

function get_connection()
{
    $host = "localhost";
    $user = "root";
    $password = "";
    $database = "online_quiz_db";

    try
    {
        $conn = new mysqli($host, $user, $password, $database);

        $conn->set_charset("utf8mb4");

        return $conn;
    }
    catch (mysqli_sql_exception $e)
    {
        die("Database connection failed: " . $e->getMessage());
    }
}

?>
