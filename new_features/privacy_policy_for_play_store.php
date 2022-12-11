<?php
require "connection.php";

if ($conn) {
    $response = $conn->query("SELECT * FROM frontend_settings WHERE id='11'");
    
    if ($response->num_rows > 0) {
        while ($row = $response->fetch_assoc()) {
            echo $row["value"];
        }
    } else {
        echo "No Privacy Policy Found";
    }
} else {
    echo "Connection Error :(";
}

?>