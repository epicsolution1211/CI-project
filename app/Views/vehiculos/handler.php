<?php 

    $hostname = "localhost";
    $username = "root";
    $password = "root";
    $databaseName = "prestamos";

    $connect = mysqli_connect($hostname, $username, $password, $databaseName);
    if (!$connect) {
        exit("Error en Conexión...");
    }

    /* Load Manufactures */
    $query = "SELECT * FROM `fabricantes`";
    $queryM = "SELECT * FROM `modelos`";

    $result1 = mysqli_query($connect, $query);
    $result2 = mysqli_query($connect, $queryM);

    

    /* Filter Model */
    
    // $val_M = mysqli_real_escape_string($connect, $val);
    // $selectedM = $_GET['manufacture'];
   

    // $sql = "SELECT `id_fabricante`, `nombre_modelo`  FROM `modelos` WHERE `id_fabricante` = '$selectedM'";
    $sql = "SELECT `id_fabricante`, `nombre_modelo`  FROM `modelos`";

  

    $result = mysqli_query($connect, $sql);

    // if (mysqli_num_rows($result) > 0) {
    //     echo "<select>";
    //     while ($rows = mysqli_fetch_assoc($result)) {
    //         echo "<option>".$rows["nombre_modelo"]."</option>";
    //     }

    //     echo "</select>";
    // }

?>