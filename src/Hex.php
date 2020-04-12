<?php

namespace ZTE;

class Hex
{

  private $_type = "";
  private $_data = "";

  public function __construct($type, $data)
  {
    $this->_type = $type;
    $this->_data = $data;
    mb_internal_encoding("UTF-8");
  }

  /*
  * Decode or Encode
  * return @string
  */
  public function decode_encode()
  {
    if ( ($this->_type!= "DEC") && ($this->_type != "ENC") ) {
      return "Error_-_Invalid_Hex_Type";
    }

    if ($this->_type == "DEC") {
      $l=mb_strlen($this->_data)/4;
		  $res='';
		  for ($i=0;$i<$l;$i++) {
         $res.=html_entity_decode('&#'.hexdec(mb_substr($this->_data,$i*4,4)).';',ENT_NOQUOTES,'UTF-8');
       }
    }

    if ($this->_type == "ENC") {
      $l=mb_strlen($this->_data);
      $res='';
      for ($i=0;$i<$l;$i++)
      {
        $s = mb_substr($this->_data,$i,1);
        $s = mb_convert_encoding($s, 'UCS-2LE', 'UTF-8');
          $s = dechex(ord(substr($s, 1, 1))*256+ord(substr($s, 0, 1)));
          if (mb_strlen($s)<4) $s = str_repeat("0",(4-mb_strlen($s))).$s;
          $res.=$s;
        }
    }

    return $res;
  }

}
