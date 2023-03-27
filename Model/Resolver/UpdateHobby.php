<?php
declare(strict_types=1);

namespace Test\Hobby\Model\Resolver;

use Magento\CustomerGraphQl\Model\Customer\GetCustomer;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use Test\Hobby\Model\Customer\ExtractHobbyData;
use Test\Hobby\Model\Customer\HobbyDataProvider;

/**
 * Resolver class for updating hobby attribute.
 */
class UpdateHobby implements ResolverInterface
{
    /**
     * @param GetCustomer $getCustomer
     * @param HobbyDataProvider $hobbyDataProvider
     * @param ExtractHobbyData $extractHobbyData
     */
    public function __construct(
        private readonly GetCustomer $getCustomer,
        private readonly HobbyDataProvider $hobbyDataProvider,
        private readonly ExtractHobbyData $extractHobbyData
    ) {
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (false === $context->getExtensionAttributes()->getIsCustomer()) {
            throw new GraphQlAuthorizationException(__('The current customer isn\'t authorized.'));
        }
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(
                __('"Input" value should be specified and in the form of array.')
            );
        }
        $customer = $this->getCustomer->execute($context);
        $customer = $this->hobbyDataProvider->execute($customer, $args['input']);
        return $this->extractHobbyData->execute($customer);
    }
}
