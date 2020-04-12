<?php

namespace ZTE;

class Wifi
{

  private $_modem_ip = "";
  private $_type = "";

  public function __construct($modem_ip, $type)
  {
    $this->_modem_ip = $modem_ip;
    $this->_type = $type;
  }

  /*
  * Enable or Disable Wifi
  * return @array or @string
  */
  public function disable_enable()
  {
    if ( ($this->_type!= "DIS") && ($this->_type != "ENA") ) {
      return "Error_-_Invalid_Wifi_Type";
    }

    $data = "goformId=SET_WIFI_INFO";
    $data .="&isTest=false";
    $data .="&m_ssid_enable=0";
    $data .="&wifiEnabled=%type%";

    if ($this->_type == "DIS") {
      $data = str_replace('%type%', 0, $data);
    }

    if ($this->_type == "ENA") {
      $data = str_replace('%type%', 1, $data);
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
