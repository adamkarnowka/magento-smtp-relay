<?php

class Creativestyle_SMTP_Model_System_Config_Source_Smtp_Ssl {
    public function toOptionArray()
    {

        return array(
            "none"   => Mage::helper('creativestyle_smtp')->__('No SSL'),
            "ssl"   => Mage::helper('creativestyle_smtp')->__('SSL'),
            "tls"   => Mage::helper('creativestyle_smtp')->__('SSL TLS'),
        );
    }
}