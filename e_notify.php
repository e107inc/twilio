<?php
/*
 * e107 website system
 *
 * Copyright (C) 2008-2013 e107 Inc (e107.org)
 * Released under the terms and conditions of the
 * GNU General Public License (http://www.gnu.org/licenses/gpl.txt)
 *
 *
*/



if (!defined('e107_INIT')) { exit; }

// e107::lan('_blank','notify',true);

// v2.x Standard 
class twilio_notify extends notify
{		
	function router()
	{
		$ret = [];
	
		$ret['sms'] = array(
			'label'			=> "SMS",
			'field'		    => "phone",
			'category'		=> ''
		);	

		return $ret;
	}


	function phone($name, $curVal)
	{
		return e107::getForm()->text($name, $curVal, 80, ['size'=>'large','placeholder'=>'+1-555-444-3333']);
	}


	function sms($data=array())
	{
		if(!empty($data['recipient']))
		{
			$to = $data['recipient'];
		}

		if(!empty($data['to']))
		{
			$to = $data['to'];
		}

		$ev = [
			'to'        => $to,
			'message'   => strip_tags(str_replace('<br />',"\n", $data['message']))
		];

		return e107::getEvent()->trigger('system_send_sms', $ev);
	}

	
}


