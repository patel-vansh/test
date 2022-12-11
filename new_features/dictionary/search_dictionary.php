<?php
require "connection.php";

if ($conn) {
    $search_string = $_POST["search_string"];
    $response_word_id = $conn->query("SELECT word_id FROM widget_dictionary WHERE word='$search_string'");
    
    if ($response_word_id->num_rows > 0) {
        $word_id = $response_word_id->fetch_assoc()["word_id"];
        
        $response_for_all_words = $conn->query("SELECT * FROM widget_dictionary WHERE word_id='$word_id'");
        
        if ($response_for_all_words->num_rows > 0) {
            $res = array();
            while ($row = $response_for_all_words->fetch_assoc()) {
                $res[] = $row;
            }
            echo json_encode(array("status"=>"Success", "message"=>$res));
        } else {
            echo json_encode(array("status"=>"Failure", "message"=>"No Words Matching Word ID " . $word_id . " Found"));
        }
        
    } else {
        echo json_encode(array("status"=>"Failure", "message"=>"No Word Id Found"));
    }
} else {
    echo json_encode(array("status"=>"Failure", "message"=>"" . $conn->connec_error));
}
?>