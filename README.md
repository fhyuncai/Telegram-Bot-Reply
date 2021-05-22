# Telegram Bot Reply

![GitHub release (latest by date)](https://img.shields.io/github/v/release/fhyuncai/Telegram-Bot-Reply?style=flat-square)
![GitHub](https://img.shields.io/github/license/fhyuncai/Telegram-Bot-Reply?style=flat-square)

> 通过 Telegram bot 通知和回复博客评论

## 当前版本

V1.2

## 作者

[FHYunCai](https://yuncaioo.com)

## 安装教程

前往 [Release](https://github.com/fhyuncai/Telegram-Bot-Reply/releases/latest) 下载源代码并将压缩包解压到 WordPress 插件目录 `/wp-content/plugins/` ，前往 WordPress 后台启用并配置相关信息

如需使用回复功能，需在**保存配置后**点击 `更新 Telegram Bot Webhook` 按钮更新 Telegram bot webhook 配置

## 使用方法

插件配置完成后，如果需要回复评论，只需回复 Bot 发送的消息即可

如需删除新评论，回复需要删除的评论 `[Del]` 即可

## 代理服务

使用本插件需连接 Telegram API ，但在中国大陆的服务器无法直连 Telegram API ，为了方便使用故允许使用代理请求

在插件设置页面配置代理地址即可，如 `https://api.telegram.org`

## 更新日志

* 1.2 `2021/05/22`

    **此版本需更新 Webhook**

    新增 消息发送测试

    修改 代理模式

    优化 配置页面文案

    优化 通过 Telegram Bot 回复评论时会自动批准原评论

* 1.1 `2020/05/10`

    **此版本需更新 Webhook**

    修改 评论者若没有填写网址则返回 “无”

    新增 Webhook 首次使用及版本不一致提示

    新增 删除评论功能

* 1.0 `2020/04/25`

    Telegram Bot Reply 的第一个版本
