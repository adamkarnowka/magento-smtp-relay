<?php
class Creativestyle_SMTP_Model_System_Config_Source_Smtp_Auth
{
    public function toOptionArray()
    {
        return array(
            "none"   => Mage::helper('creativestyle_smtp')->__('None'),
            "login"   => Mage::helper('creativestyle_smtp')->__('Login'),
            "plain"   => Mage::helper('creativestyle_smtp')->__('Plain')
        );
    }
}