<?php
/* @package Creativestyle_EmailPreview */

class Creativestyle_SMTP_Block_Adminhtml_System_Config_Field_Infoblock  extends  Mage_Adminhtml_Block_Abstract  implements  Varien_Data_Form_Element_Renderer_Interface
{
    public function render(Varien_Data_Form_Element_Abstract $element){
        return Mage::app()->getLayout()->createBlock('Mage_Core_Block_Template', 'SMTP_Block', array('template' => 'creativestyle_smtp/infoblock.phtml'))->toHtml();
    }
}