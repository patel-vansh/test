<?php
require "connection.php";

if ($conn) {
    $search_string = $_GET["search_string"];
    $response_word_id = $conn->query("SELECT * FROM widget_dictionary WHERE word LIKE '$search_string%'");
    
    if ($response_word_id->num_rows > 0) {
        $word_ids = $response_word_id->fetch_all();
        $response = array();
        foreach($word_ids as $word_id) {
            $response[] = array('id'=>$word_id[0], 'word_id'=>$word_id[1], 'language'=>$word_id[2], 'word'=>$word_id[3]);
        }
        if (empty($response)) {
            echo json_encode(array("status"=>"Failure", "message"=>"No Words Matching Word ID " . $word_id . " Found"));
        } else {
            echo json_encode(array("status"=>"Success", "message"=>$response));
        }
        
    } else {
        echo json_encode(array("status"=>"Failure", "message"=>"No Word Id Found"));
    }
} else {
    echo json_encode(array("status"=>"Failure", "message"=>"" . $conn->connec_error));
}
?>