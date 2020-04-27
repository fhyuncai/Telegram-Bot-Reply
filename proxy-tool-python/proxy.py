# -*- coding: UTF-8 -*-

# Telegram Bot Reply - Proxy - Python
# Author: FHYunCai
# Version: 1.0
# Update time: 2020/04/26

from flask import Flask,request
from gevent.pywsgi import WSGIServer
from urllib import parse
import base64,json,requests,time

SecertKey = '' # API Secert Key

requests.packages.urllib3.disable_warnings()
app = Flask(__name__)

@app.route('/proxy',methods=['GET'])
def proxy():
    try:
        sk = base64.b64decode(request.args.get('sk')).decode()
        do = request.args.get('d')
        jsonp = json.loads(base64.b64decode(request.args.get('r')).decode())
        if request.method == 'GET' and sk == SecertKey and do and json:
            if do == 'send':
                bot_token = jsonp[0]
                user_id = jsonp[1]
                message = jsonp[2]
                requests.get('https://api.telegram.org/bot'+bot_token+'/sendMessage?chat_id='+user_id+'&text='+parse.quote_plus(message),verify=False)
                return 'Succeed'
            elif do == 'update':
                if jsonp[0] == 'webhook':
                    bot_token = jsonp[1]
                    webhook = jsonp[2]
                    requests.get('https://api.telegram.org/bot'+bot_token+'/setWebhook?url='+parse.quote_plus(webhook),verify=False)
                    return 'Succeed'
                else:
                    raise ValueError
            else:
                raise ValueError
        else:
            raise ValueError
    except ValueError:
        return 'Request Error',403
    except Exception as e:
        with open('errorlog_'+time.strftime('%Y%m%d_%H%M%S',time.localtime())+'.txt','w',encoding='utf-8') as f:
            f.write(str(e))
            f.close()
        return 'Something was wrong',500

@app.errorhandler(404)
def page_not_found(e):
    return 'Not Found',404

if __name__ == '__main__':
    #app.run(host='0.0.0.0',port=64321,debug=True) # Debug
    http_server = WSGIServer(('0.0.0.0',64321),app)
    http_server.serve_forever()
