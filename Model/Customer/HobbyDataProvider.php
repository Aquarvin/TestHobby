<?php
declare(strict_types=1);

namespace Test\Hobby\Model\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Test\Hobby\Model\Source\Hobby as HobbyOptions;
use Test\Hobby\Setup\Patch\Data\AddHobbyCustomerAttribute;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;

/**
 * Hobby Data Provider for updating hobby customer attribute via GraphQl
 */
class HobbyDataProvider
{
    /**
     * @param HobbyOptions $hobbyOptions
     * @param CustomerRepositoryInterface $customerRepository
     */
    public function __construct(
        private readonly HobbyOptions $hobbyOptions,
        private readonly CustomerRepositoryInterface $customerRepository
    ) {
    }

    /**
     * @param CustomerInterface $customer
     * @param array $data
     * @return CustomerInterface
     * @throws GraphQlInputException
     */
    public function execute(CustomerInterface $customer, array $data): CustomerInterface
    {
        $this->validateData($data);
        try {
            $customer->setCustomAttribute(AddHobbyCustomerAttribute::ATTRIBUTE_CODE, $data['value']);
            $this->customerRepository->save($customer);
        } catch (\Exception $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }

        return $customer;
    }

    /**
     * @param $data
     * @return void
     * @throws GraphQlInputException
     */
    private function validateData($data): void
    {
        if (empty($data['value'])) {
            throw new GraphQlInputException(
                __('value of the attribute should be specified.')
            );
        }
        $options    = $this->hobbyOptions->getAllOptions();
        $hobbyValue = $data['value'];
        $hobbyId    = array_search($hobbyValue, array_column($options, 'value'));
        if ($hobbyId === false) {
            throw new GraphQlInputException(
                __('There is no such hobby value in system configuration.')
            );
        }
    }
}
