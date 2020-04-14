<?php

namespace ZTE;

class Login
{

  private $_modem_ip = "";
  private $_passwd = "";
  private $_type = "";

  public function __construct($modem_ip, $type, $passwd = '' )
  {
    $this->_modem_ip = $modem_ip;
    $this->_passwd = base64_encode($passwd);
    $this->_type = $type;
  }

  /*
  * Do Login or Logoff on Modem
  * Return @array or @string
  */
  public function  login_logout()
  {
    $data = 'isTest=false';

    if ( ($this->_type!= "IN") && ($this->_type != "OUT") ) {
      return "Error: Invalid Login Type";
    }

    if ($this->_type == "IN") {

      if (is_null($this->_passwd)) {
        return "Login: Password is Null";
      }

      if (strlen($this->_passwd)<1) {
        return "Login: Password is Empty";
      }

      $data .= '&goformId=LOGIN';
      $data .= '&password='.$this->_passwd;
    }

    if ($this->_type == "OUT") {
      $data .= '&goformId=LOGOUT';
    }

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
