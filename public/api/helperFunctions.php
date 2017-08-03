<?php

function sendResponce($status, $status_message, $data){
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;

    echo json_encode($response);
}

function getArrayFromString($str, $seperator){
    $arr = explode($seperator, $str);
    if($arr[0] == "")
        array_shift($arr);

    return $arr;
}

?>