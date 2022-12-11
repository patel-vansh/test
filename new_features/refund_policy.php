<?php
require "connection.php";

if ($conn) {
    $response = $conn->query("SELECT * FROM frontend_settings WHERE id='25'");
    
    if ($response->num_rows > 0) {
        while ($row = $response->fetch_assoc()) {
            echo json_encode(array("status"=>"Success", "message"=>$row["value"]));
        }
    } else {
        echo json_encode(array("status"=>"Failure", "message"=>"No Refund Policy Found"));
    }
} else {
    echo json_encode(array("status"=>"Failure", "message"=>"" . $conn->connec_error));
}

?>