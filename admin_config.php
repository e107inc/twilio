<?php

// Generated e107 Plugin Admin Area 

require_once('../../class2.php');
if (!getperms('P')) 
{
	e107::redirect('admin');
	exit;
}

// e107::lan('twilio',true);


class twilio_adminArea extends e_admin_dispatcher
{

	protected $modes = array(	
	
		'main'	=> array(
			'controller' 	=> 'twilio_ui',
			'path' 			=> null,
			'ui' 			=> 'twilio_form_ui',
			'uipath' 		=> null
		),
		

	);	
	
	
	protected $adminMenu = array(
			
		'main/prefs' 		=> array('caption'=> LAN_PREFS, 'perm' => 'P'),	

		// 'main/div0'      => array('divider'=> true),
		'main/test'		=> array('caption'=> 'Test', 'perm' => 'P'),
		
	);

	protected $adminMenuAliases = array(
		'main/edit'	=> 'main/list'				
	);	
	
	protected $menuTitle = 'Twilio';
}




				
class twilio_ui extends e_admin_ui
{
			
		protected $pluginTitle		= 'Twilio';
		protected $pluginName		= 'twilio';
	//	protected $eventName		= 'twilio-'; // remove comment to enable event triggers in admin. 		
		protected $table			= '';
		protected $pid				= '';
		protected $perPage			= 10; 
		protected $batchDelete		= true;
		protected $batchExport     = true;
		protected $batchCopy		= true;

	//	protected $sortField		= 'somefield_order';
	//	protected $sortParent      = 'somefield_parent';
	//	protected $treePrefix      = 'somefield_title';

	//	protected $tabs				= array('tab1'=>'Tab 1', 'tab2'=>'Tab 2'); // Use 'tab'=>'tab1'  OR 'tab'=>'tab2' in the $fields below to enable. 
		
	//	protected $listQry      	= "SELECT * FROM `#tableName` WHERE field != '' "; // Example Custom Query. LEFT JOINS allowed. Should be without any Order or Limit.
	
		protected $listOrder		= ' DESC';
	
		protected $fields 		= array (
		);		
		
		protected $fieldpref = array();
		

	//	protected $preftabs        = array('General', 'Other' );
		protected $prefs = array(
			'active'	=> array('title'=> 'Active', 'tab'=>0, 'type'=>'boolean', 'data' => 'str', 'help'=>'', 'writeParms' => []),
			'phone'		=> array('title'=> 'Phone', 'tab'=>0, 'type'=>'text', 'data' => 'str', 'help'=>'Your SMS compatible Twilio phone number', 'writeParms' => ['placeholder'=>'+1-222-333-4444']),
			'sid'		=> array('title'=> 'Apikey', 'tab'=>0, 'type'=>'text', 'data' => 'str', 'help'=>'', 'writeParms' => ['size'=>'xxlarge']),
			'secret'	=> array('title'=> 'Token', 'tab'=>0, 'type'=>'method', 'data' => 'str', 'help'=>'', 'writeParms' => ['size'=>'xlarge']),
		); 

	
		public function init()
		{
			// This code may be removed once plugin development is complete. 
			if(!e107::isInstalled('twilio'))
			{
				e107::getMessage()->addWarning("This plugin is not yet installed. Saving and loading of preference or table data will fail.");
			}
			
			// Set drop-down values (if any). 
	
		}

		public function testPage()
		{
			if(!empty($_POST['testTwilio']) && !empty($_POST['to']) && !empty($_POST['message']))
			{
				$event = [];
				if($result = e107::getEvent()->trigger('system_send_sms', $_POST))
				{
					e107::getMessage()->addInfo('SENT: '. print_a($result, true));
				}
				else
				{
					e107::getMessage()->addError('There was a problem. Check the number and make sure it begins with <b>+</b>.');
				}

			}

			$frm = $this->getUI();

			$text = $frm->open('testPage');
			$text .= "<table class='table table-bordered' style='width:auto'>";
			$text .= "<tr><td>";
			$text .= $frm->text('to',varset($_POST['to']), 80, ['placeholder' => '+1-555-333-2222']);
			$text .= "</td><tr><td>";
			$text .= $frm->textarea('message', 'Hello from e107!', 3);
			$text .= "</td></tr>";
			$text .= "</table>";
			$text .= $frm->submit('testTwilio','Send Test SMS');
			$text .= $frm->close();

			return $text;
		}

		
		// ------- Customize Create --------
		
		public function beforeCreate($new_data,$old_data)
		{
			return $new_data;
		}
	
		public function afterCreate($new_data, $old_data, $id)
		{
			// do something
		}

		public function onCreateError($new_data, $old_data)
		{
			// do something		
		}		
		
		
		// ------- Customize Update --------
		
		public function beforeUpdate($new_data, $old_data, $id)
		{
			return $new_data;
		}

		public function afterUpdate($new_data, $old_data, $id)
		{
			// do something	
		}
		
		public function onUpdateError($new_data, $old_data, $id)
		{
			// do something		
		}		
		
		// left-panel help menu area. (replaces e_help.php used in old plugins)
		public function renderHelp()
		{
			$caption = LAN_HELP;
			$text = "<p>This plugins adds <a href='https://www.twilio.com/' target='_blank' title='Visit website'>Twilio</a> SMS capabilities to e107.</p>
				<p>After saving your API details, as found on your Twilio dashboard, use the <b>Test</b> page to send yourself an SMS to check that is it functioning correctly.</p>
				<p>You can also choose to have <a href='".e_ADMIN."notify.php'>system notifications</a> sent to you by SMS if you wish. </p>
				<p>Plugin developers may send an SMS message using the following code example:</p>
			<pre>e107::getEvent()->trigger(<br />'system_send_sms',<br />[<br />&nbsp;&nbsp;&nbsp;'to' => '+1-222-333-4444',<br />&nbsp;&nbsp;&nbsp;'message' => 'Your message'<br />]);</pre>
";
			return array('caption'=>$caption,'text'=> $text);

		}
			
	/*	
		// optional - a custom page.  
		public function customPage()
		{
			$text = 'Hello World!';
			$otherField  = $this->getController()->getFieldVar('other_field_name');
			return $text;
			
		}
		
	
		
		
	*/
			
}
				


class twilio_form_ui extends e_admin_form_ui
{

	
	// Custom Method/Function (pref)
	function apikey($curVal,$mode)
	{


		// Edit Page
		if($mode == 'write')
		{
			return $this->text('apikey', $curVal, 255, 'size=large');
		}
		
		return null;
	}

	
	// Custom Method/Function (pref)
	function secret($curVal,$mode)
	{
		// Edit Page
		if($mode == 'write')
		{
			return $this->password('secret', $curVal, 255, 'size=xxlarge');
		}
		
		return null;
	}

}		
		
		
new twilio_adminArea();

require_once(e_ADMIN."auth.php");
e107::getAdminUI()->runPage();

require_once(e_ADMIN."footer.php");
exit;

