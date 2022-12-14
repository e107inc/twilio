<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2022 e107.org
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
*/

if (!defined('e107_INIT')) { exit; }


class twilio_event
{

	function config()
	{
		$event = array();

		if(e107::pref('twilio', 'active', false))
		{
			$event[] = array('name' => "system_send_sms", 'function' => "sendSMS");
		}

		return $event;
	}

	/**
	 * @param array $data
	 * @param str $event
	 * @return array
	 */
	function sendSMS($data, $event=null)
	{

		$pref       = e107::pref('twilio');

		$sid        = $pref['sid']; // Your Account SID from www.twilio.com/console
		$secret     = $pref['secret']; // Your Auth Token from www.twilio.com/console
		$from       = $pref['phone']; // Your Twilio phone number.

		if(empty($sid))
		{
			e107::getMessage()->addError("SID is empty");
			return false;
		}

		if(empty($secret))
		{
			e107::getMessage()->addError("Secret is empty");
			return false;
		}

		$srch = ['-',' '];
		$to = str_replace($srch, '', $data['to']);

		if(!empty($data['from']))
		{
			$from = str_replace($srch,'',$data['from']);
		}

		if(empty($from))
		{
			e107::getMessage()->addError("From is empty");
			return false;
		}

		if(empty($to))
		{
			e107::getMessage()->addError("To number is empty");
			return false;
		}

		$result = $this->twilioCreateSMS($to, $from, $data['message'], $sid, $secret);

		$type = empty($result['error_code']) ? E_LOG_INFORMATIVE : E_LOG_WARNING;

	    e107::getLog()->addArray($result)->save('TWILIO_01', $type);

		return $result;
	}


	/**
	 * @param str $to
	 * @param str $from
	 * @param str $body
	 * @param str $sid
	 * @param str $token
	 * @return array
	 */
	private function twilioCreateSMS($to, $from, $body, $sid, $token)
	{

	    $url = 'https://api.twilio.com/2010-04-01/Accounts/'.$sid.'/Messages.json';

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_FOLLOWLOCATION,true);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);

	    curl_setopt($ch, CURLOPT_HTTPAUTH,CURLAUTH_BASIC);
	    curl_setopt($ch, CURLOPT_USERPWD, $sid . ':' . $token);

		curl_setopt($ch, CURLOPT_POST, true);
	    curl_setopt($ch, CURLOPT_POSTFIELDS,
	        'To=' . rawurlencode($to) .
	     //   '&MessagingServiceSid=' . $service .
	        '&From=' . rawurlencode($from) .
	        '&Body=' . rawurlencode($body));

	    $resp = curl_exec($ch);
	    curl_close($ch);

	    return json_decode($resp,true);

	}


}