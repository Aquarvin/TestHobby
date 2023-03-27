<?php
declare(strict_types=1);

namespace Test\Hobby\Model\Source;

use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Serialize\SerializerInterface;

/**
 * Customer hobby attribute Source data class
 */
class Hobby extends AbstractSource
{
    private const HOBBY_ATTRIBUTE_CONFIG_XML = 'customer/customer_attributes/hobby';

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param SerializerInterface $serializer
     */
    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly SerializerInterface $serializer
    ) {
    }

    /**
     * Retrieve All options
     *
     * @return array
     */
    public function getAllOptions(): array
    {
        if (!$this->_options) {
            $optionArray = [];
            $hobbies     = $this->scopeConfig->getValue(self::HOBBY_ATTRIBUTE_CONFIG_XML);
            if (!$hobbies) {
                return $optionArray;
            }
            if (is_string($hobbies)) {
                $hobbies = $this->serializer->unserialize($hobbies);
            }
            usort($hobbies, fn($a, $b) => $a['order'] <=> $b['order']);
            foreach ($hobbies as $hobby) {
                $optionArray[] = [
                    'value' => $hobby['value'],
                    'label' => $hobby['label']
                ];
            }
            $this->_options = $optionArray;
        }
        return $this->_options;
    }
}
