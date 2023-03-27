<?php
declare(strict_types=1);

namespace Test\Hobby\Block\Adminhtml\Form\Field;

use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;

/**
 * Hobby renderer in system configuration.
 */
class Hobby extends AbstractFieldArray
{
    /**
     * Prepare rendering the hobby field by adding all the needed columns
     *
     * @return void
     */
    protected function _prepareToRender(): void
    {
        $this->addColumn('value', ['label' => __('Value'), 'class' => 'required-entry']);
        $this->addColumn('label', ['label' => __('Label'), 'class' => 'required-entry']);
        $this->addColumn('order', ['label' => __('Sort order'), 'class' => 'required-entry']);
        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add');
    }
}
