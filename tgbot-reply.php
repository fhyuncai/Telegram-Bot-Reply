<?php
/*
Plugin Name: Telegram Bot Reply
Plugin URI: https://yuncaioo.com
Description: 通过 Telegram bot 通知和回复博客评论。
Author: FHYunCai
Author URI: https://yuncaioo.com
Version: 1.2
*/

if (!defined('ABSPATH')) exit;
define('TGBOT_REPLY_PATH', __DIR__);
define('TGBOT_REPLY_VERSION', 1.2);

if (is_file(TGBOT_REPLY_PATH . '/SecertKey.php')) {
    require(TGBOT_REPLY_PATH . '/SecertKey.php');
    $webhook_url = get_option('tgbot_reply_webhook_url');
    $webhook_url2 = plugins_url('webhook_' . $SecertKey . '.php', __FILE__);
    if ($webhook_url != $webhook_url2) {
        update_option('tgbot_reply_webhook_url', $webhook_url2);
        $webhook_url = $webhook_url2;
    }
} else {
    $SecertKey = bin2hex(random_bytes(20));
    file_put_contents(TGBOT_REPLY_PATH . '/SecertKey.php', '<?php defined(\'TGBOT_REPLY_PATH\') or exit; $SecertKey=\'' . $SecertKey . '\'; ?>');
    $webhook_str = file_get_contents(TGBOT_REPLY_PATH . '/webhook.php');
    $webhook_str = str_replace('define(\'TGBOT_REPLY_WEBHOOK\',false);', 'define(\'TGBOT_REPLY_WEBHOOK\',true);', $webhook_str);
    file_put_contents(TGBOT_REPLY_PATH . '/webhook_' . $SecertKey . '.php', $webhook_str);
    $webhook_url = plugins_url('webhook_' . $SecertKey . '.php', __FILE__);
    update_option('tgbot_reply_webhook_url', $webhook_url);
}

register_activation_hook(__FILE__, 'tgbot_reply_plugin_activate');
add_action('admin_init', 'tgbot_reply_plugin_redirect');

function tgbot_reply_plugin_redirect()
{
    if (get_option('do_activation_redirect', false)) {
        delete_option('do_activation_redirect');
        wp_redirect(admin_url('options-general.php?page=tgbot_reply'));
    }
}

function tgbot_reply_pluginoptions_page()
{
    require_once('option.php');
}

function tgbot_reply_menu()
{
    add_options_page('Telegram Bot Reply', 'Telegram Bot Reply', 'administrator', 'tgbot_reply', 'tgbot_reply_pluginoptions_page');
}

if (is_admin()) {
    add_action('admin_menu', 'tgbot_reply_menu');
}

function tgbot_reply_register_plugin_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=tgbot_reply">设置</a>';
    array_unshift($links, $settings_link);
    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_{$plugin}", 'tgbot_reply_register_plugin_settings_link');


if (get_option('tgbot_reply_newcomment_notify') == 'checked') {
    add_action('comment_post', 'tgbot_reply_function_comment_notify');
}


/* Functions */
function tgbot_reply_function_tgsend($message)
{
    global $SecertKey;
    $proxyUrl = get_option('tgbot_reply_proxy_address');
    $botToken = get_option('tgbot_reply_bot_token');
    $telegramUid = get_option('tgbot_reply_tguser_id');
    $telegramApiUrl = $proxyUrl ? $proxyUrl : 'https://api.telegram.org';
    file_get_contents($telegramApiUrl . '/bot' . $botToken . '/sendMessage?chat_id=' . $telegramUid . '&text=' . urlencode($message));
}

function tgbot_reply_function_comment_notify($comment_id)
{
    if (!empty(get_option('tgbot_reply_bot_token')) && !empty(get_option('tgbot_reply_tguser_id'))) {
        $comment = get_comment($comment_id);
        if ($comment->user_id != get_option('tgbot_reply_wpuser_id')) {
            $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
            $s_blogname = get_option('blogname');
            $s_postname = get_the_title($comment->comment_post_ID);
            $s_author = trim($comment->comment_author);
            $s_email = trim($comment->comment_author_email);
            $s_website = trim($comment->comment_author_url) ? trim($comment->comment_author_url) : '无';
            $s_content = trim($comment->comment_content);
            $s_commentlink = get_comment_link($comment_id);
            $message = "{$s_blogname} 收到一条新评论\n文章标题: {$s_postname}\n昵称: {$s_author}\n邮箱: {$s_email}\n网站: {$s_website}\n评论地址: {$s_commentlink}\n内容: \n{$s_content}\n\n[{$comment_id}]";
            tgbot_reply_function_tgsend($message);
        }
    }
}
