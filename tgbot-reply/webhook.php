<?php
/**
 * Telegram Bot Reply - Webhook
 * Author: FHYunCai
 * Version: 1.1
 */

define('TGBOT_REPLY_WEBHOOK',false);
define('TGBOT_REPLY_WEBHOOK_NOWVERSION',1.1);
if(TGBOT_REPLY_WEBHOOK == false) exit;
require(dirname(dirname(dirname(__DIR__))).'/wp-load.php');

$request_data = json_decode(file_get_contents('php://input'),true);
if(get_option('tgbot_reply_newcomment_reply') == 'checked' && $request_data['message']['from']['id'] == get_option('tgbot_reply_tguser_id')){
    if($request_data['message']['reply_to_message']['from']['is_bot'] == 'true' && substr($request_data['message']['reply_to_message']['text'],-1) == ']'){
        $parent_id = substr($request_data['message']['reply_to_message']['text'],strrpos($request_data['message']['reply_to_message']['text'],"[")-strlen($request_data['message']['reply_to_message']['text'])+1,-1);
        if($request_data['message']['text'] == '[Del]'){
            $status_del = wp_delete_comment($parent_id,false);
            $message = $status_del?'删除成功':'删除失败';
        }else{
            $parent_comment = get_comment($parent_id);
            $user_info = get_user_by('id',get_option('tgbot_reply_wpuser_id'));
            $status_add = wp_new_comment([
                'comment_post_ID'=>$parent_comment->comment_post_ID,
                'comment_author'=>$user_info->display_name,
                'comment_author_email'=>$user_info->user_email,
                'comment_author_url'=>$user_info->user_url,
                'comment_content'=>$request_data['message']['text']."\n——此评论通过 Telegram Bot 回复",
                'comment_type'=>'',
                'comment_parent'=>$parent_id,
                'user_id'=>get_option('tgbot_reply_wpuser_id')
            ]);
            $message = $status_add?'回复成功':'回复失败';
        }
        tgbot_reply_function_tgsend($message);
    }
}
