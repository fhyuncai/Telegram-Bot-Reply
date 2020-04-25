# Telegram Bot Reply

> 通过 Telegram bot 通知和回复博客评论。

## 当前版本

Version 1.0

## 作者

[FHYunCai](https://yuncaioo.com)

## 安装教程

下载源代码并将 tgbot-reply 文件夹解压到 WordPress 插件目录 (/wp-content/plugins/)，前往 WordPress 后台启用并配置相关信息。

如需使用回复功能，需在**保存配置后**点击 “更新 Telegram Bot Webhook” 按钮更新 Telegram bot webhook 配置。

## 使用方法

插件配置完成后，如果需要回复评论，只需回复 Bot 发送的消息即可。

## 注意事项

### 代理服务

使用本插件需连接 Telegram 服务器，但在中国大陆的服务器无法直连 Telegram 服务器，为了方便使用故提供了代理服务。

如果需要使用代理服务，仅需将 proxy-tool 内的 proxy.php 以及 WordPress 本插件目录内的 SecertKey.php (需要启用插件才会生成) 上传至可直连 Telegram 的 Web 服务器 (PHP 版本 >= 5.3)，并在插件设置页面配置访问 proxy.php 的地址 (如：https://example.com/proxy.php) 即可。

## 更新日志

* 1.0

    Telegram Bot Reply 的第一个版本
