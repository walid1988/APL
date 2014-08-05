<?php


class ScriptController extends Controller
{
 

 public function accessRules()
 {
  return array(
   array('allow',  // allow all users to perform 'index' and 'view' actions
    'actions'=>array('outbound'),
    'users'=>array('*'),
   ),
   
  );
 }

 public function actionOutbound()
 {
     $presses = Press::model()->findAll();
     foreach ($presses as $press){
         $date=date("Y-m-d H:i:s");
         if(($press->press_status=='Q')&&($press->press_date_started <= $date)){
         $list= $press->list;
         $contacts =$list->contacts;
         
         foreach ($contacts as $contact){
            
             $email = Yii::app()->mandrillwrap; 
             //$email->html = $press->press_content;
//             $email->subject = $press->press_subject;
//             $email->fromName = $press->press_sender_name;
//             $email->fromEmail = $press->press_sender_email;
//             $email->replyEmail=$press->press_replyto_email;
//             $email->toName = $contact->contact_name_last .' '.$contact->contact_name_first;
//             $email->toEmail = $contact->contact_email;
    
         $result=$email->sendEmail();
         }
     }}
     
      $this->render('outbound');
      
      
 }



}
