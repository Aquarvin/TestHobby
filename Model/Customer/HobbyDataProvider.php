<?php
declare(strict_types=1);

namespace Test\Hobby\Model\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Psr\Log\LoggerInterface;
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
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly HobbyOptions $hobbyOptions,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly LoggerInterface $logger
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
            $this->logger->error($e->getMessage());
            throw new GraphQlInputException(__('We can\'t update hobby.'));
        }

        return $customer;
    }

    /**
     * @param array $data
     * @return void
     * @throws GraphQlInputException
     */
    private function validateData(array $data): void
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
