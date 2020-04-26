<?php
/**
 * Telegram Bot Reply - Proxy - PHP
 * Author: FHYunCai
 * Version: 1.01
 * Update time: 2020/04/26
 */

define('TGBOT_REPLY_PATH',true);

if(is_file('SecertKey.php')){
    require('SecertKey.php');
}else{
    exit('Cannot find SK file');
}

if(base64_decode($_GET['sk']) == $SecertKey && $_GET['d'] != '' && $_GET['r'] != ''){
    $json = base64_decode($_GET['r']);
    if($json == '') exit;
    $json_arr = json_decode($json);
    switch($_GET['d']){
        case 'send':
            $bot_token = $json_arr[0];
            $user_id = $json_arr[1];
            $message = $json_arr[2];
            file_get_contents('https://api.telegram.org/bot'.$bot_token.'/sendMessage?chat_id='.$user_id.'&text='.urlencode($message));
            echo 'Succeed';
        break;
        case 'update':
            switch($json_arr[0]){
                case 'webhook':
                    $bot_token = $json_arr[1];
                    $webhook = $json_arr[2];
                    file_get_contents('https://api.telegram.org/bot'.$bot_token.'/setWebhook?url='.urlencode($webhook));
                    echo 'Succeed';
                break;
                default:
                    echo 'Request Error';
            }
        break;
        default:
            echo 'Request Error';
    }
}else{
    echo 'Request Error';
}
