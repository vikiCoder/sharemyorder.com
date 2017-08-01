<?php

function sendResponce($status, $status_message, $data){
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;

    echo json_encode($response);
}

function getArrayFromNumberString($str){
    $arr = explode(";", $str);
    return arr;
}

?>