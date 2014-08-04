<?php

class mandrillwrap extends CApplicationComponent {
	
	public $mandrillKey;
	public $fromEmail;
	public $fromName;
	public $toEmail;
	public $toName;
	public $text;
	public $subject;
	
	public function init()
	{
		Yii::import('application.vendors.Mandrill');
		define('MANDRILL_API_KEY','-47REdJ11GUIcpHCv60c0w');
        }
		public function sendEmail() {		
		
$request_json = '{
"type":"messages",
"call":"send",
"message":{
"html": "' . $this->html . '",
 "text": "' . $this->text . '", 
 "subject":"' . $this->subject . '",
 "from_email": "' . $this->fromEmail . '", 
 "from_name": "' . $this->fromName . '",,
 "to":[{
 "email": "' . $this->toEmail . '",
 "name": "' . $this->toName . '"
 }],
  "headers":{"Reply-To": "' . $this->replyEmail . '"},
"track_opens":false,
"track_clicks":false
}}';
$ret = Mandrill::call((array) json_decode($request_json));
//$ret1 =Mandrill::call(array('type'=>'users', 'call'=>'ping'));

    }
}
