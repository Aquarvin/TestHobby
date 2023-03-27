<?php
declare(strict_types=1);

namespace Test\Hobby\ViewModel;

use Magento\Customer\Model\Session;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Test\Hobby\Model\Source\Hobby;
use Test\Hobby\Setup\Patch\Data\AddHobbyCustomerAttribute;

class HobbyViewModel implements ArgumentInterface
{
    /**
     * @param Hobby $hobbySource
     * @param Session $customerSession
     */
    public function __construct(
        private readonly Hobby $hobbySource,
        private readonly Session $customerSession
    ) {
    }

    /**
     * Return list of hobbies.
     *
     * @return array
     */
    public function getHobbies(): array
    {
        return $this->hobbySource->getAllOptions();
    }

    /**
     * Return current hobby value.
     *
     * @return string
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getCurrentHobby(): string
    {
        $value        = '';
        $customerData = $this->customerSession->getCustomerData();
        if ($customerData && $customerData->getCustomAttribute(AddHobbyCustomerAttribute::ATTRIBUTE_CODE)) {
            $value = $customerData->getCustomAttribute(AddHobbyCustomerAttribute::ATTRIBUTE_CODE)->getValue();
        }
        return $value;
    }
}
