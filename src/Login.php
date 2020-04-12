<?php

namespace ZTE;

class Login
{

  private $_modem_ip = "";
  private $_passwd = "";

  public function __construct($modem_ip, $passwd)
  {
    $this->_modem_ip = $modem_ip;
    $this->_passwd = base64_encode($passwd);
  }

  /*
  * Do Login on Modem
  * Return @array
  */
  public function login()
  {
    $data = 'isTest=false';
    $data .= '&goformId=LOGIN';
    $data .= '&password='.$this->_passwd;

    $curl = new Curl($this->_modem_ip, 'POST', $data);
    $result = $curl->get_post();
    $json = new Json('DEC', $result);
    $decode = $json->decode_encode();

    $ret['data'] = $data;
    $ret['result'] = $result;
    $ret['decode'] = $decode;

    return $ret;
  }

}
