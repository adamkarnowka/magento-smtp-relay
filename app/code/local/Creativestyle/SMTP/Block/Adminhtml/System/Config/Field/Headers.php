<?php

class Creativestyle_SMTP_Block_Adminhtml_System_Config_Field_Headers extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $magentoAttributes;

    public function __construct()
    {
        $this->addColumn('header_name', array(
                'label' => Mage::helper('adminhtml')->__('Header name'),
                'size'  => 3,
            ));
        $this->addColumn('header_value', array(
                'label' => Mage::helper('adminhtml')->__('Header value'),
                'size'  => 3,
            ));

        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add new custom header');

        parent::__construct();
        $this->setTemplate('creativestyle_smtp/campaign_dropdown.phtml');
    }

    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }

        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';
        $rendered = '<input name="'.$inputName.'" type="input" />';

        return $rendered;
    }
}
?>