<?php
declare(strict_types=1);

namespace Test\Hobby\Model\Resolver;

use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Test\Hobby\Model\Customer\ExtractHobbyData;

/**
 * Customer hobby attribute resolver
 */
class Hobby implements ResolverInterface
{
    /**
     * @param GetCustomer $getCustomer
     * @param ExtractHobbyData $extractHobbyData
     */
    public function __construct(
        private readonly GetCustomer $getCustomer,
        private readonly ExtractHobbyData $extractHobbyData
    ) {
    }

    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }
        $customer = $this->getCustomer->execute($context);
        return $this->extractHobbyData->execute($customer);
    }
}
