<?php

function sendResponce($status, $status_message, $data){
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;

    echo json_encode($response);
}

?>