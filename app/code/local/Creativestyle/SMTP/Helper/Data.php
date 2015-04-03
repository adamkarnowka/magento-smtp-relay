<?php
/**
 * User: Adam KARNOWKA
 * Date: 24.03.15
 * Time: 16:08
 */ 
class Creativestyle_SMTP_Helper_Data extends Mage_Core_Helper_Abstract {

    /** Checks if SMTP is enabled and if setting is correct
     *  @return bool
     */
    public function validateSettings(){
        if(Mage::getStoreConfig('creativestyle_smtp/settings/enabled')){
            $validator = new Zend_Validate_Hostname();
            if($validator->isValid(Mage::getStoreConfig('creativestyle_smtp/settings/enabled'))){
                return true;
            } else {
                Mage::log($validator->getMessages(),Zend_Log::ERR,'creativestyle_smtp.log');
            }
        } else {
            Mage::log(Mage::helper('creativestyle_smtp')->__('Cannot use SMTP because config value is not set.'), Zend_Log::ERR,'creativestyle_smtp.log');
        }

        return false;
    }
}