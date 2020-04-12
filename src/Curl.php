<?php

namespace ZTE;

class Curl
{

  private $_type;
  private $_data;
  private $_verb;
  private $_url_prefix = "http://";
  private $_referer = "/index.html";
  private $_url_set = "/goform/goform_set_cmd_process";
  private $_url_get = "/goform/goform_get_cmd_process";
  private $_modem_ip = "";

  public function __construct($modem_ip, $type, $data, $verb = false)
  {
    $this->_modem_ip = $modem_ip;
    $this->_type = $type;
    $this->_data = $data;
    $this->_verb = $verb;
  }

  public function get_post()
  {
    if ( ($this->_type != "GET") && ($this->_type != "POST") ) {
      return "Error_-_Invalid_Curl_Type";
    }

    $ch = curl_init();

    if ($this->_type == "GET") {
      $url = $this->_url_prefix.$this->_modem_ip.$this->_url_get.$this->_data;
    }

    if ($this->_type == "POST") {
      $url = $this->_url_prefix.$this->_modem_ip.$this->_url_set;
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, $this->_verb);

    if ($this->_type == "GET") {
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->_type);
      curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');
    }

    if ($this->_type == "POST") {
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $this->_data);
    }

    $header = array();
    $header[] = 'Host: '.$this->_modem_ip;
    $header[] = 'Referer: '.$this->_url_prefix.$this->_modem_ip.$this->_referer;

    if ($this->_type == "POST") {
      $header[] = 'Content-Type: application/x-www-form-urlencoded';
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

    $result = curl_exec($ch);

    if (curl_errno($ch)) {
      return 'Error: ' . curl_error($ch);
    }

    curl_close($ch);

    return $result;
  }

}
