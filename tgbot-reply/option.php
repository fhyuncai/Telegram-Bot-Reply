<?php
defined('TGBOT_REPLY_PATH') or exit;
if(is_file(TGBOT_REPLY_DIR.'/SecertKey.php')){
    require(TGBOT_REPLY_DIR.'/SecertKey.php');
}else{
    exit;
}
if($_POST['update_pluginoptions'] == 'true'){
    $message = tgbot_reply_update_pluginoptions();
    if($message == true){
        echo '<div id="message" class="updated"><h4>设置已保存</h4></div>';
    }else{
        echo '<div id="message" class="error"><h4>设置保存失败：'.$message.'</h4></div>';
    }
}elseif($_POST['update_tgbotwebhook'] == 'true'){
    tgbot_reply_update_webhook();
    exit('Success');
}

if(is_file(TGBOT_REPLY_DIR.'/webhook_'.$SecertKey.'.php')){
    $webhook_ver_str = file_get_contents(TGBOT_REPLY_DIR.'/webhook_'.$SecertKey.'.php');
    $webhook_ver_a = strpos($webhook_ver_str,'* Version: ')+11;
    $webhook_ver_b = strpos($webhook_ver_str,"\n",$webhook_ver_a);
    $webhook_ver = substr($webhook_ver_str,$webhook_ver_a,$webhook_ver_b-$webhook_ver_a);
    if(TGBOT_REPLY_WEBHOOK_VERSION != $webhook_ver){
        echo '<div id="message" class="error"><h4>Webhook 文件版本不正确，请在保存设置后点击 “更新 Telegram Bot Webhook” 以更新文件</h4></div>';
    }
}else{
    echo '<div id="message" class="error"><h4>首次使用请在保存设置后点击 “更新 Telegram Bot Webhook” 以更新 Webhook</h4></div>';
}
?>
<div class="wrap">
<h2>Telegram Bot Reply 设置</h2>
<form method="POST" action="" style="margin-top:30px;">
    <input type="hidden" name="update_pluginoptions" value="true"/>
    <table class="form-table">
        <tr><th>Telegram Bot 新评论通知</th>
        <td><label>
            <input type="checkbox" name="newcomment_notify" id="newcomment_notify" <?php echo get_option('tgbot_reply_newcomment_notify') ?> /><span> 启用</span>
        </label></td></tr>
        <tr><th>Telegram Bot 回复评论</th>
        <td><label>
            <input type="checkbox" name="newcomment_reply" id="newcomment_reply" <?php echo get_option('tgbot_reply_newcomment_reply') ?> /><span> 启用</span>
        </label></td></tr>
        <tr><th>Telegram Bot Token</th>
        <td><label>
            <input type="text" style="width:300px;" value="<?php echo get_option('tgbot_reply_bot_token')?get_option('tgbot_reply_bot_token'):'';?>" size="3" name="bot_token" id="bot_token"/>
        </label><p>向 @BotFather 发送消息以创建机器人</p></td></tr>
        <tr><th>Telegram User ID</th>
        <td><label>
            <input type="number" style="width:300px;" value="<?php echo get_option('tgbot_reply_tguser_id')?get_option('tgbot_reply_tguser_id'):'';?>" size="3" name="tguser_id" id="tguser_id"/>
        </label><p>向 @getmyid_bot 发送消息以获取 ID</p></td></tr>
        <tr><th>WordPress 用户 ID</th>
        <td><label>
            <input type="number" value="<?php echo get_option('tgbot_reply_wpuser_id')?get_option('tgbot_reply_wpuser_id'):'1';?>" size="3" name="wpuser_id" id="wpuser_id" required="required"/>
        </label><p>若为个人博客则无需修改默认值“1”</p></td></tr>
        <tr><th>代理接口</th>
        <td><label>
            <input type="text" style="width:300px;" value="<?php echo get_option('tgbot_reply_proxy_address')?get_option('tgbot_reply_proxy_address'):'';?>" size="3" name="proxy_address" id="proxy_address"/>
        </label><p>若服务器可以直连 TG 服务器则无需填写</p></td></tr>
    </table>
    <p class="submit">
        <input name="submit" type="submit" class="button button-primary" value="保存设置" />
        <input type="hidden" name="action" value="update" />
        <input id="update_webhook" type="button" class="button button-primary" value="更新 Telegram Bot Webhook" />
        <input type="hidden" name="panel" value="0" id="active_panel_name" />
    </p>
    Telegram Bot Reply 版本：<?php echo TGBOT_REPLY_VERSION ?> &nbsp; 作者：<a href="https://yuncaioo.com">FHYunCai</a> &nbsp; 版本发布：<a href="https://github.com/fhyuncai/Telegram-Bot-Reply/releases">Github Release</a> &nbsp; <!--<a href="https://yuncaioo.com">帮助</a>-->
</form>

<style>
.panel {
    margin: 0 20px;
}
.panel h3 {
    margin: 0;
}
.panel th {
    font-weight: normal;
}

.wrap.searching .nav-tab-wrapper a,
.wrap.searching .panel tr,
body.show-filters .wrap form {
    display: none
}
.wrap.searching .panel {
    display: block!important;
}

.filter-drawer {
    padding-top: 0;
    padding-bottom: 0;
}
.filter-drawer ul {
    list-style: disc inside;
}
</style>
<script>
/* global wp */
jQuery(function($){
    var $body = $("body");
    var $themeOptionsFilter = $("#theme-options-filter");
    var $wpFilterSearchInput = $("#wp-filter-search-input");
    var mediaUploader,$mediaInput;

    $(".filter-links a").click(function () {
        $(this).addClass("current").parent().siblings().children(".current").removeClass("current");
        $(".panel").hide();
        $($(this).attr("href")).show();
        $("#active_panel_name").val($(this).data("panel"));
        $body.removeClass("show-filters");
        return false;
    });

    $(".wrap form").submit(function(){
        $(".submit .button").prop("disabled",true);
        $(this).find(".submit .button").val("正在提交…");
    });

    $("#update_webhook").click(function(){
        $(this).attr("disabled","true");
        $(this).val("正在更新…");
        $.post("",{update_tgbotwebhook:'true'},function(result){
            $("#update_webhook").val("更新成功");
        });
    });
});
</script>


<?php
function tgbot_reply_update_pluginoptions(){
    if($_POST['newcomment_notify'] == 'on'){
        $display = 'checked';
    }else{
        $display = '';
    }
    update_option('tgbot_reply_newcomment_notify',$display);
    if($_POST['newcomment_reply'] == 'on'){
        $display = 'checked';
    }else{
        $display = '';
    }
    update_option('tgbot_reply_newcomment_reply',$display);
    update_option('tgbot_reply_bot_token',$_POST['bot_token']);
    update_option('tgbot_reply_tguser_id',$_POST['tguser_id']);
    update_option('tgbot_reply_wpuser_id',$_POST['wpuser_id']);
    update_option('tgbot_reply_proxy_address',$_POST['proxy_address']);
    return true;
}

function tgbot_reply_update_webhook(){
    if(get_option('tgbot_reply_bot_token') != ''){
        global $SecertKey;
        $webhook_str = file_get_contents(TGBOT_REPLY_DIR.'/webhook.php');
        $webhook_str = str_replace('define(\'TGBOT_REPLY_WEBHOOK\',false);','define(\'TGBOT_REPLY_WEBHOOK\',true);',$webhook_str);
        file_put_contents(TGBOT_REPLY_DIR.'/webhook_'.$SecertKey.'.php',$webhook_str);

        $webhook_url = plugins_url('webhook_'.$SecertKey.'.php',__FILE__);
        $bot_token = get_option('tgbot_reply_bot_token');
        $proxy_address = get_option('tgbot_reply_proxy_address');
        if($proxy_address != ''){
            $request_address = $proxy_address.'?sk='.base64_encode($SecertKey).'&d=update&r='.base64_encode(json_encode(['webhook',$bot_token,$webhook_url]));
        }else{
            $request_address = 'https://api.telegram.org/bot'.$bot_token.'/setWebhook?url='.urlencode($webhook_url);
        }
        file_get_contents($request_address);
        return true;
    }else{
        return false;
    }
}
?>
