<?php

namespace ZTE;

class Json
{

  private $_type = "";
  private $_data = "";
  private $_assoc = "";

  public function __construct($type, $data, $assoc = "")
  {
    $this->_type = $type;
    $this->_data = $data;

    if ( (strlen($assoc)<1) && (!$assoc) ) {
        $this->_assoc = true;
      } else {
        $this->_assoc = false;
      }
  }

  /*
  * Decode or Encode
  * return @array, @object or @string
  */
  public function decode_encode()
  {
    if ( ($this->_type!= "DEC") && ($this_type != "ENC") ) {
      return "Error_-_Invalid_Json_Type";
    }

      if ($this->_type == "DEC") {
        $ret = json_decode($this->_data, $this->_assoc);
      }

      if ($this->_type== "ENC") {
        $ret = json_encode($this->_data);
      }

    return $ret;
  }

}
