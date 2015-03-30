<?php
/**
 * Created by PhpStorm.
 * User: Adam
 * Date: 24.03.15
 * Time: 16:09
 */ 
class Creativestyle_SMTP_Model_Core_Email_Template extends Mage_Core_Model_Email_Template {
    /**
     * Overriden method which uses SMTP server if configured.
     * @param array|string $email
     * @param null         $name
     * @param array        $variables
     *
     * @return bool
     */

    public function send($email, $name = null, array $variables = array())
    {
        if(!Mage::getStoreConfig('creativestyle_smtp/settings/enabled')){
            return parent::send($email,  $name, $variables);
        }

        if (!$this->isValidForSend()) {
            Mage::logException(new Exception('This letter cannot be sent.')); // translation is intentionally omitted
            return false;
        }

        $emails = array_values((array)$email);
        $names = is_array($name) ? $name : (array)$name;
        $names = array_values($names);
        foreach ($emails as $key => $email) {
            if (!isset($names[$key])) {
                $names[$key] = substr($email, 0, strpos($email, '@'));
            }
        }

        $variables['email'] = reset($emails);
        $variables['name'] = reset($names);

        $this->setUseAbsoluteLinks(true);
        $text = $this->getProcessedTemplate($variables, true);
        $subject = $this->getProcessedTemplateSubject($variables);

        $setReturnPath = Mage::getStoreConfig(self::XML_PATH_SENDING_SET_RETURN_PATH);
        switch ($setReturnPath) {
            case 1:
                $returnPathEmail = $this->getSenderEmail();
                break;
            case 2:
                $returnPathEmail = Mage::getStoreConfig(self::XML_PATH_SENDING_RETURN_PATH_EMAIL);
                break;
            default:
                $returnPathEmail = null;
                break;
        }

        if ($this->hasQueue() && $this->getQueue() instanceof Mage_Core_Model_Email_Queue) {
            /** @var $emailQueue Mage_Core_Model_Email_Queue */
            $emailQueue = $this->getQueue();
            $emailQueue->setMessageBody($text);
            $emailQueue->setMessageParameters(array(
                    'subject'           => $subject,
                    'return_path_email' => $returnPathEmail,
                    'is_plain'          => $this->isPlain(),
                    'from_email'        => $this->getSenderEmail(),
                    'from_name'         => $this->getSenderName(),
                    'reply_to'          => $this->getMail()->getReplyTo(),
                    'return_to'         => $this->getMail()->getReturnPath(),
                ))
                ->addRecipients($emails, $names, Mage_Core_Model_Email_Queue::EMAIL_TYPE_TO)
                ->addRecipients($this->_bccEmails, array(), Mage_Core_Model_Email_Queue::EMAIL_TYPE_BCC);
            $emailQueue->addMessageToQueue();

            return true;
        }

        ini_set('SMTP', Mage::getStoreConfig('system/smtp/host'));
        ini_set('smtp_port', Mage::getStoreConfig('system/smtp/port'));

        $mail = $this->getMail();

        if ($returnPathEmail !== null) {
            $mailTransport = new Zend_Mail_Transport_Sendmail("-f".$returnPathEmail);
            Zend_Mail::setDefaultTransport($mailTransport);
        }

        foreach ($emails as $key => $email) {
            $mail->addTo($email, '=?utf-8?B?' . base64_encode($names[$key]) . '?=');
        }

        if ($this->isPlain()) {
            $mail->setBodyText($text);
        } else {
            $mail->setBodyHTML($text);

            if(Mage::getStoreConfig('creativestyle_smtp/settings/generate_txt_version') && file_exists(getcwd().'/lib/Creativestyle/SMTP/Html2Text.php')){
                require(getcwd().'/lib/Creativestyle/SMTP/Html2Text.php');
                $txtVersion = Html2Text\Html2Text::convert($text);
                $mail->setBodyText($txtVersion);
            }
        }

        $mail->setSubject('=?utf-8?B?' . base64_encode($subject) . '?=');
        $mail->setFrom($this->getSenderEmail(), $this->getSenderName());

        try {
            if(Mage::getStoreConfig('creativestyle_smtp/settings/enabled') && Mage::helper('creativestyle_smtp')->validateSettings()){
                $config = array();
                $config['auth'] = Mage::getStoreConfig('creativestyle_smtp/settings/smtp_auth');
                $config['username'] = Mage::getStoreConfig('creativestyle_smtp/settings/smtp_user');
                $config['password'] = Mage::getStoreConfig('creativestyle_smtp/settings/smtp_password');
                $config['port'] = Mage::getStoreConfig('creativestyle_smtp/settings/smtp_port');
                $config['ssl'] = Mage::getStoreConfig('creativestyle_smtp/settings/smtp_ssl');

                if($headers = unserialize(Mage::getStoreConfig('creativestyle_smtp/settings/headers'))){
                    foreach($headers as $id => $header){
                        $mail->addHeader($header['header_name'], $header['header_value']);
                    }
                }

                $emailTransport = new Zend_Mail_Transport_Smtp(Mage::getStoreConfig('creativestyle_smtp/settings/smtp_host'), $config);
                $mail->send($emailTransport);
            } else {
                $mail->send();
            }

            $this->_mail = null;
        }
        catch (Exception $e) {
            $this->_mail = null;
            Mage::logException($e);
            return false;
        }

        return true;
    }
}