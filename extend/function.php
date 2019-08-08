<?php

// 调试函数
function p($data, $isStop = false){
    $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest' || isset($_GET['_isAjax']) || isset($_POST['_isAjax']);

    $trace = (new \Exception())->getTrace()[0];
    if($isAjax){
        header('Content-type:application/json;charset=utf-8');
        exit(json_encode(array(
            'file' => $trace['file'],
            'line' => $trace['line'],
            'dataStr' => var_export($data, true),
            'data' => $data,
        )));
    }else{
        echo '<pre style="color:green">';
        print_r($data);
        echo '</pre>';
    }
    $isStop && exit;
}