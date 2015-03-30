<?php

class Creativestyle_SMTP_Block_Adminhtml_System_Config_Field_Password extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    private $_element;


    public function _prepareLayout(){
        $this->setTemplate('creativestyle_smtp/password.phtml');
    }

    public function render(Varien_Data_Form_Element_Abstract $element){
        $this->_element = $element;
        return $this->_toHtml();
    }

    public function getElement(){
        return $this->_element;
    }
}