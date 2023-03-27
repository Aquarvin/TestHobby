<?php
declare(strict_types=1);

namespace Test\Hobby\CustomerData;

use Magento\Customer\CustomerData\SectionSourceInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Test\Hobby\Model\Source\Hobby;
use Test\Hobby\Setup\Patch\Data\AddHobbyCustomerAttribute;

/**
 * Hobby section data class.
 */
class HobbySection implements SectionSourceInterface
{
    /**
     * @param CustomerSession $customerSession
     * @param Hobby $hobbySource
     */
    public function __construct(private readonly CustomerSession $customerSession, private readonly Hobby $hobbySource)
    {
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSectionData(): array
    {
        $customerData = $this->customerSession->getCustomerData();
        $hobbyValue   = $customerData->getCustomAttribute(AddHobbyCustomerAttribute::ATTRIBUTE_CODE)->getValue();
        if ($hobbyValue) {
            $options = $this->hobbySource->getAllOptions();
            $hobbyId = array_search($hobbyValue, array_column($options, 'value'));
            return [
                'hobby' => $options[$hobbyId]['label'],
            ];
        }
        return [];
    }
}
