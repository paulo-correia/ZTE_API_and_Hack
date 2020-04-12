<?php
namespace ZTE;

class Wan
{

  private $_modem_ip = "";
  private $_type = "";

  public function __construct($modem_ip, $type)
  {
    $this->_modem_ip = $modem_ip;
    $this->_type = $type;
  }

  /*
  * Connect or Disconnect WAN
  * return @array or @string
  */
  function connect_disconnect()
  {

    if ( ($this->_type!="CON") && ($this->_type!="DIS") ) {
      return "Wan: Invalid Type (CON) OR (DIS)";
    }

    $data = 'isTest=false';
    $data .= '&notCallback=true';

    if ($this->_type=="CON") {
      $data .= '&goformId=CONNECT_NETWORK';

    }

    if ($this->_type=="DIS") {
      $data .= '&goformId=DISCONNECT_NETWORK';
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
