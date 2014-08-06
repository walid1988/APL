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
                $contacts = $press->list->contacts;                
               // $user_press = $press->pressUser->porfile_name_last;
                //echo $user_press;
                $credit = 100;
                if ($credit >= count($contacts)) {                
                    $content = '';
                    $press_contacts_maild = 0;
                    $press_contacts_failed = 0;
                    foreach ($contacts as $contact) {
                        $email = Yii::app()->mandrillwrap;
                        $content = $press->press_content;
                        $content = str_replace("\r\n", '', $content);
                        $email->html = $content;
                        $email->subject = $press->press_subject;
                        $email->fromName = $press->press_sender_name;
                        $email->fromEmail = $press->press_sender_email;
                        $email->replyEmail = $press->press_replyto_email;
                        $email->toName = $contact->contact_name_last . ' ' . $contact->contact_name_first;
                        $email->toEmail = $contact->contact_email;
                        $email->img_content = file_get_contents(Yii::app()->basePath . '/uploads/' . $press->press_file_1);
                        $email->img_content = base64_encode($email->img_content);
                        $extension = pathinfo($press->press_file_1, PATHINFO_EXTENSION);
                        switch ($extension) {
                            case 'jpg':
                            case 'jpeg':
                                $email->ext = "image/jpeg";
                                $email->img_name = basename($press->press_file_1, ".jpg");
                                break;
                            case 'gif':
                                $email->ext = "image/gif";
                                $email->img_name = basename($press->press_file_1, ".gif");

                                break;
                            case 'png':
                                $email->ext = "image/png";
                                $email->img_name = basename($press->press_file_1, ".png");

                                break;
                        }
                         $ret = $email->sendEmail();
                        if ($ret['status'] == "sent")
                            $press_contacts_maild = $press_contacts_maild + 1;
                        else
                            $press_contacts_failed = $press_contacts_failed + 1;
                      
                    }
                }
            }
        }

        $this->render('outbound');
    }

}
