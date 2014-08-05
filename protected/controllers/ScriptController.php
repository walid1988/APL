<?php

class ScriptController extends Controller {

    public function accessRules() {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('outbound'),
                'users' => array('*'),
            ),
        );
    }

    public function actionOutbound() {
        $presses = Press::model()->findAll();
        foreach ($presses as $press) {
            $date = date("Y-m-d H:i:s");
            if (($press->press_status == 'Q') && ($press->press_date_started <= $date)) {
                $list = $press->list;
                $contacts = $list->contacts;
                $content = '';
                foreach ($contacts as $contact) {
                    $email = Yii::app()->mandrillwrap;
                    $content = $press->press_content;
                //    $content= htmlentities($content);
                    echo $content;
                      echo '*********';
                    //                  $content = str_replace('"', '', $htmontent);
                 // $content = substr($content,0,30);
                // $content='<html><div > aaaa <img src="some_ad.png"/> <h3>&quot;Hot&quot; </h3>Items</div></html>';
                 $content= str_replace("\r\n", '', $content);
                 echo $content;
                 //$email->html ="<div class=\"ad_box\"><img class=\"banner\" src=\"some_ad.png\"\/><h3>&quot;Hot&quot; Items<ul \/><\/div>";
                  $email->html = $content;//"<html><div > aaaa <img src='some_ad.png'/> <h3>&quot;Hot&quot; </h3>Items</div></html>";
                    $email->subject = $press->press_subject;
                    $email->fromName = $press->press_sender_name;
                    $email->fromEmail = $press->press_sender_email;
                    $email->replyEmail = $press->press_replyto_email;
                    
                    $email->toName = $contact->contact_name_last . ' ' . $contact->contact_name_first;
                    $email->toEmail = $contact->contact_email;
                    //print_r( $content);
                    $result = $email->sendEmail();
                }
            }
        }

        $this->render('outbound');
    }

}
