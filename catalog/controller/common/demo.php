<?php 
class ControllerCommonDemo extends Controller {

    public function abc() {

       echo "SELECT a.key, a.data, a.date_added FROM ((SELECT CONCAT('customer_', ca.key) AS `key`, ca.data, ca.date_added FROM `" . DB_PREFIX . "customer_activity` ca) UNION (SELECT CONCAT('affiliate_', aa.key) AS `key`, aa.data, aa.date_added FROM `" . DB_PREFIX . "affiliate_activity` aa)) a ORDER BY a.date_added DESC LIMIT 0,5";
    }

    public function index() {
        $salt = token(9);

    

        // // $settingsApps = array(
        // //        '1234_abc'   => array(
        // //            'path'   => 'mfblog/index_page/title',
        // //            'scope'  => 'stores'
        // //        ),
        // //        '12345_abc'   => array(
        // //            'path'   => 'klaviyo_reclaim_general/general/private_api_key', 
        // //            'scope'  => 'stores'
        // //        )
        // // );
        // // foreach($settingsApps as $key =>  $value ) {
       
        // // }
        // $paramPutArray['storeId']                   =  0;
        //     $paramPutArray['settingsApps']              =  array(
        //         mt_rand().'_'.'0'  =>  array(
        //             'path'              => 'payment/stripecreditcards/active',
        //             'scope'             => 'default'
        //         ),
        //         mt_rand().'_'.'0'  =>  array(
        //             'path'              => 'payment/stripeinstantcheckout/active',
        //             'scope'             => 'default'
        //         )
        // );

      
    }
    public function smtp() {
         //    $mail = new Mail('SMTP');
         // //   $mail->parameter = 'pro01.emailserver.vn';
         //    $mail->smtp_hostname = 'ssl://pro01.emailserver.vn';
         //    $mail->smtp_username = 'smtp1@fastwinner.com.vn';
         //    $mail->smtp_password = html_entity_decode('TLW3#$eXT#LX', ENT_QUOTES, 'UTF-8');
         //    $mail->smtp_port = '465';
         //    $mail->smtp_timeout = '20';
         //    $mail->setTo('votuananh8127@gmail.com');
         //    $mail->setFrom('smtp1@fastwinner.com.vn');
         //   //./ $mail->setReplyTo('linhthongtin132@gmail.com');
         //    $mail->setSender(html_entity_decode('dfsdf', ENT_QUOTES, 'UTF-8'));
         //    $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), 'tuânnh'), ENT_QUOTES, 'UTF-8'));
         //    $mail->setText('sdfsdf');
         //    $mail->send();

           //  $mail = new Mail('SMTP');
           //  $mail->parameter = 'smtp2@thepvietduc.com.vn';
           //  $mail->smtp_hostname = 'ssl://pro01.emailserver.vn';
           //  $mail->smtp_username = 'smtp2@thepvietduc.com.vn';
           //  $mail->smtp_password = html_entity_decode('8rFgPtWkw;4b', ENT_QUOTES, 'UTF-8');
           //  $mail->smtp_port = '465';
           //  $mail->smtp_timeout = '20';
           //  $mail->setTo('smtp2@thepvietduc.com.vn');
           //  $mail->setFrom('smtp2@thepvietduc.com.vn');
           // // $mail->setReplyTo('linhthongtin132@gmail.com');
           //  $mail->setSender(html_entity_decode('dfsdf', ENT_QUOTES, 'UTF-8'));
           //  $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), 'sf dfsdf ád'), ENT_QUOTES, 'UTF-8'));
           //  $mail->setText('sdfsdfd');
           //  $mail->send();

        // $mail = new Mail('SMTP');
        //     $mail->parameter = 'mail.vinmeli.com';
        //     $mail->smtp_hostname = 'ssl://mail.vinmeli.com';
        //     $mail->smtp_username = 'info@vinmeli.com';
        //     $mail->smtp_password = html_entity_decode('tlmfBaEN!kHW', ENT_QUOTES, 'UTF-8');
        //     $mail->smtp_port = '465';
        //     $mail->smtp_timeout = '20';
        //     $mail->setTo('votuananh8127@gmail.com');
        //     $mail->setFrom('info@vinmeli.com');
        //    ./ $mail->setReplyTo('linhthongtin132@gmail.com');
        //     $mail->setSender(html_entity_decode('dfsdf', ENT_QUOTES, 'UTF-8'));
        //     $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), 'tuânnh'), ENT_QUOTES, 'UTF-8'));
        //     $mail->setText('sdfsdf');
        //     $mail->send();

            $mail = new Mail('SMTP');
            $mail->parameter = 'hotro@fastwinner.com.vn';
            $mail->smtp_hostname = 'tls://smtp.office365.com';
            $mail->smtp_username = 'hotro@fastwinner.com.vn';
            $mail->smtp_password = html_entity_decode('mgbkrnlvtfztfccp', ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = '587';
            $mail->smtp_timeout = '20';
            $mail->setTo('votuananh8127@gmail.com');
            $mail->setFrom('hotro@fastwinner.com.vn');
           // $mail->setReplyTo('linhthongtin132@gmail.com');
            $mail->setSender(html_entity_decode('dfsdf', ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode(sprintf($this->language->get('email_subject'), 'sf dfsdf ád'), ENT_QUOTES, 'UTF-8'));
            $mail->setText('sdfsdfd');
            $mail->send();
    }
}
// outlook.office365.com Kimcuong2015