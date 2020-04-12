<?php

namespace ZTE;

class Sms
{

  private $_tz = "+3";
  private $_phone = "";
  private $_message = "";
  private $_id = -1;
  private $_modem_ip = "";

  public function __construct($modem_ip)
  {
    $this->_modem_ip = $modem_ip;
  }

  public function setPhone( $phone ) {
    $this->_phone = $phone;
  }

  public function setMessage( $message ) {
  $this->_message = $message;
  }

  public function setId( $id ) {
    $this->_id = $id;
  }

  /*
  * Get Messages List
  * return @array
  */
  public function list()
  {
    $data = '?isTest=false';
    $data .= '&cmd=sms_data_total';
    $data .= '&page=0';
    $data .= '&data_per_page=500';
    $data .= '&mem_store=1';
    $data .= '&tags=10';
    $data .= '&order_by=order+by+id+desc';

    $curl = new Curl($this->_modem_ip, 'GET', $data);
    $result = $curl->get_post();

    $result = preg_replace (
      ['/\x00/', '/\x01/', '/\x02/', '/\x03/', '/\x04/',
      '/\x05/', '/\x06/', '/\x07/', '/\x08/', '/\x09/',
      '/\x0A/', '/\x0B/', '/\x0C/', '/\x0D/', '/\x0E/',
      '/\x0F/', '/\x10/', '/\x11/', '/\x12/','/\x13/',
      '/\x14/', '/\x15/', '/\x16/', '/\x17/', '/\x18/',
      '/\x19/', '/\x1A/', '/\x1B/', '/\x1C/', '/\x1D/',
      '/\x1E/', '/\x1F/'
      ],
      ["\u0000", "\u0001", "\u0002", "\u0003", "\u0004",
      "\u0005", "\u0006", "\u0007", "\u0008", "\u0009",
      "\u000A", "\u000B", "\u000C", "\u000D", "\u000E",
      "\u000F", "\u0010", "\u0011", "\u0012", "\u0013",
      "\u0014", "\u0015", "\u0016", "\u0017", "\u0018",
      "\u0019", "\u001A", "\u001B", "\u001C", "\u001D",
      "\u001E", "\u001F"
      ],
      $result
    );

    $json = new Json('DEC', $result);
    $decode = $json->decode_encode();

    $ret['data'] = $data;
    $ret['result'] = $result;
    $ret['decode'] = $decode;

    return $ret;
  }

  /*
  * Send Mesage
  * return @array on success
  * return @string on fail
  */
  public function read_sms()
  {
    $all_messages = SELF::list();

    $messages = $all_messages['decode'];

    if (!is_array($messages)) {

			if (is_null($messages)) {
        return "Read Messages_Var=Null";
			}

			if (strlen($messages)==0) {
        return "Read Messages_Var=Empty";
			}

		}

    if (count($messages)==0) {
      return "Read Messages_Count=0";
    }

    foreach ($messages as $messages1) {

			foreach ($messages1 as $message) {

				$id = $message['id'];
				$content = $message['content'];
				$number = $message['number'];
        $date = $message['date'];
        $decodehex = new Hex('DEC',$content);
        $translated = $decodehex->decode_encode();

        $date_trans = explode(',',$date);

				$new_message[$id]= "@".$number."*".$date_trans[2].'/'.$date_trans[1].'/'
        .$date_trans[0].' '.$date_trans[3].':'.$date_trans[4].':'.$date_trans[5]
        ."#".trim($translated)."#";

			}
		}

    if (!isset($new_message)) {
      return "No new Messages";
    }

		return $new_message;
  }

  /*
  * Send Mesage
  * return @string
  */
  public function send_sms()
  {

    if (is_null($this->_phone)) {
      return "Destination SMS Number Null";
    }

    if (strlen($this->_phone)==0) {
      return "Destination SMS Number Empty";
    }

    if (is_null($this->_message)) {
      return "SMS Message Null";
    }

    if (strlen($this->_message)==0) {
      return "SMS Message Empty";
    }

		$data='isTest=false&';
		$data.= 'goformId=SEND_SMS&';
		$data.= 'notCallback=true&';
		$data.= 'Number='.urlencode($this->_phone).'&';
		$date = gmdate('y;m;d;H;i;s;'.$this->_tz,time()+($this->_tz*3600));
		$data.= 'sms_time='.urlencode($date).'&';
    $encodehex = new Hex('ENC',$this->_message);
    $untranlated = $encodehex->decode_encode();
		$data.= 'MessageBody='.$untranlated.'&';
		$data.= 'ID=-1&';
		$data.= 'encode_type=UNICODE';

    $curl = new Curl($this->_modem_ip, 'POST', $data);
    $result = $curl->get_post();
    $json = new Json('DEC', $result);
    $decode = $json->decode_encode();

    $ret['data'] = $data;
    $ret['result'] = $result;
    $ret['decode'] = $decode;

    if ($ret['decode']['result'] == 'success') {
      $ret['txt'] = 'Message Send to: '.$this->_phone;
    } else {
      $ret['txt'] = 'Message could not be send.';
    }

    return $ret;
  }

  /*
  * Delete Mesage(s)
  * return @string
  */
  public function delete_message()
  {

    if (is_null($this->_id)) {
      return "Message ID is Null";
    }

    if (strlen($this->_id)<1) {
      return "Message ID is Empty";
    }

    if ( ($this->_id != "*") && (intval($this->_id)<0) ) {
      return "Invalid ID, ID is >0";
    }

    if ($this->_id == "*") {

      $all_messages = SELF::list();

      if (!array_key_exists('decode', $all_messages)) {
        return "No Messages to Delete";
      }

      $messages = $all_messages['decode'];

      if (!is_array($messages)) {

        if (count($messages)==0) {
          return "Delete Messages_Count=0";
        }

        if (is_null($messages)) {
          return "Delete Messages_Var=Null";
        }

        if (strlen($messages)==0) {
          return "Delete Messages_Var=Empty";
        }

      }

      if (count($messages)==0) {
        return "Delete Messages_Count=0";
      }

      foreach ($messages as $messages1) {

        foreach ($messages1 as $message) {

          $id = $message['id'];

          $ids[]=$id;

        }

      }

      $ret['txt'] = '';

      if (!isset($ids)) {
        return "No more messages to Delete!";
      }

      if (!is_array($ids)) {
        return "No more messages to Delete!";
      }

      $x=0;
      foreach ($ids as $id_message) {

        $data ="isTest=false";
        $data .="&goformId=DELETE_SMS";
        $data .="&msg_id=".$id_message;
        $data .="&notCallback=true";

        $curl = new Curl($this->_modem_ip, 'POST', $data);
        $result = $curl->get_post();
        $json = new Json('DEC', $result);
        $decode = $json->decode_encode();

        $ret[]['data'] = $data;
        $ret[]['result'] = $result;
        $ret[]['decode'] = $decode;

        if (!array_key_exists('decode', $ret[$x])) {
          continue;
        }

        if ($ret[$x]['decode']['result'] == 'success') {
            $ret['txt'] .= 'Message '.$id.' Deleted ! #';
          } else {
            $ret['txt'] .= 'Message could not be deleted. #';
        }

        $x++;

      }

      return $ret;
    }

    if ( !is_string(intval($this->_id)) ) {

      $data ="isTest=false";
      $data .="&goformId=DELETE_SMS";
      $data .="&msg_id=".$this->_id;
      $data .="&notCallback=true";

      $curl = new Curl($this->_modem_ip, 'POST', $data);
      $result = $curl->get_post();
      $json = new Json('DEC', $result);
      $decode = $json->decode_encode();

      $ret['data'] = $data;
      $ret['result'] = $result;
      $ret['decode'] = $decode;

      if ($ret['decode']['result'] == 'success') {
        $ret['txt'] = 'Message '.$this->_id.' Deleted !';
      } else {
          $ret['txt'] = 'Message could not be deleted.';
      }

      return $ret;
    }

  }

}
