<?php 

    $hostname = "localhost";
    $username = "root";
    $password = "root";
    $databaseName = "prestamos";

    $connect = mysqli_connect($hostname, $username, $password, $databaseName);
    $query = "SELECT * FROM `fabricantes`";

    $result1 = mysqli_query($connect, $query);
?> 