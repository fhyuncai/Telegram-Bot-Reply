<?php
/**
 * Telegram Bot Reply - Webhook
 * Author: FHYunCai
 * Version: 1.0
 */

define('TGBOT_REPLY_WEBHOOK',false);
if(TGBOT_REPLY_WEBHOOK == false) exit;
require(dirname(dirname(dirname(__DIR__))).'/wp-load.php');

$request_data = json_decode(file_get_contents('php://input'),true);
if(get_option('tgbot_reply_newcomment_reply') == 'checked' && $request_data['message']['from']['id'] == get_option('tgbot_reply_tguser_id')){
    if($request_data['message']['reply_to_message']['from']['is_bot'] == 'true' && substr($request_data['message']['reply_to_message']['text'],-1) == ']'){
        $parent_id = substr($request_data['message']['reply_to_message']['text'],strrpos($request_data['message']['reply_to_message']['text'],"[")-strlen($request_data['message']['reply_to_message']['text'])+1,-1);
        $parent_comment = get_comment($parent_id);
        $user_info = get_user_by('id',get_option('tgbot_reply_wpuser_id'));
        wp_new_comment([
            'comment_post_ID'=>$parent_comment->comment_post_ID,
            'comment_author'=>$user_info->display_name,
            'comment_author_email'=>$user_info->user_email,
            'comment_author_url'=>$user_info->user_url,
            'comment_content'=>$request_data['message']['text']."\n——此评论通过 Telegram Bot 回复",
            'comment_type'=>'',
            'comment_parent'=>$parent_id,
            'user_id'=>get_option('tgbot_reply_wpuser_id')
        ]);
        $message = '回复成功';
        tgbot_reply_function_tgsend($message);
    }
}
