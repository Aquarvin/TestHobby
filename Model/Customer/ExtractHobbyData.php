<?php
declare(strict_types=1);

namespace Test\Hobby\Model\Customer;

use Magento\Customer\Api\Data\CustomerInterface;
use Test\Hobby\Model\Source\Hobby as HobbyOptions;
use Test\Hobby\Setup\Patch\Data\AddHobbyCustomerAttribute;

/**
 * Hobby data extractor
 */
class ExtractHobbyData
{
    /**
     * @param HobbyOptions $hobbyOptions
     */
    public function __construct(private readonly HobbyOptions $hobbyOptions)
    {
    }

    /**
     * @param CustomerInterface $customer
     * @return array|null
     */
    public function execute(CustomerInterface $customer): ?array
    {
        if (!$customer->getCustomAttribute(AddHobbyCustomerAttribute::ATTRIBUTE_CODE)) {
            return null;
        }
        $hobbyValue = $customer->getCustomAttribute(AddHobbyCustomerAttribute::ATTRIBUTE_CODE)->getValue();
        $options    = $this->hobbyOptions->getAllOptions();
        $hobbyId    = array_search($hobbyValue, array_column($options, 'value'));

        return [
            'value' => $options[$hobbyId]['value'],
            'label' => $options[$hobbyId]['label'],
        ];
    }
}
