<?php
declare(strict_types=1);

namespace Test\Hobby\Controller\Hobby;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Controller\AccountInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\State\InputMismatchException;
use Magento\Framework\Message\ManagerInterface;
use Test\Hobby\Setup\Patch\Data\AddHobbyCustomerAttribute;

/**
 * Save hobby for customer.
 */
class EditPost implements HttpPostActionInterface, AccountInterface
{
    /**
     * @param Session $customerSession
     * @param RequestInterface $request
     * @param CustomerRepositoryInterface $customerRepository
     * @param ManagerInterface $manager
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        private readonly Session $customerSession,
        private readonly RequestInterface $request,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly ManagerInterface $manager,
        private readonly RedirectFactory $redirectFactory
    ) {
    }

    /**
     * Save selected hobby for current customer.
     *
     * @return Redirect
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function execute(): Redirect
    {
        $resultRedirect = $this->redirectFactory->create();
        $customer       = $this->customerSession->getCustomer();
        $customerData   = $this->customerSession->getCustomerData();
        try {
            $hobby = $this->request->getParam('hobby');
            $customerData->setCustomAttribute(AddHobbyCustomerAttribute::ATTRIBUTE_CODE, $hobby);
            $customer->updateData($customerData);
            $customer->setHobby($hobby);
            $this->customerRepository->save($customerData);
        } catch (InputException|InputMismatchException $e) {
        } catch (LocalizedException $e) {
            $this->manager->addErrorMessage('We can\'t update hobby');
        }

        return $resultRedirect->setPath('*/*/edit');
    }
}
