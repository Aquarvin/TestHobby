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
use Psr\Log\LoggerInterface;
use Test\Hobby\Model\Source\Hobby;
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
     * @param Hobby $hobbySource
     * @param LoggerInterface $logger
     */
    public function __construct(
        private readonly Session $customerSession,
        private readonly RequestInterface $request,
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly ManagerInterface $manager,
        private readonly RedirectFactory $redirectFactory,
        private readonly Hobby $hobbySource,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Save selected hobby for current customer.
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        $resultRedirect = $this->redirectFactory->create();
        $hobby          = $this->request->getParam('hobby');
        $options        = $this->hobbySource->getAllOptions();

        if (!in_array($hobby, array_column($options, 'value'), true)) {
            $this->manager->addErrorMessage(__('The specified hobby is not valid.'));
            return $resultRedirect->setPath('*/*/edit');
        }

        try {
            $customerData = $this->customerSession->getCustomerData();
            $customerData->setCustomAttribute(AddHobbyCustomerAttribute::ATTRIBUTE_CODE, $hobby);
            $this->customerRepository->save($customerData);
            $this->manager->addSuccessMessage(__('Your hobby has been updated.'));
        } catch (InputException|InputMismatchException|NoSuchEntityException $e) {
            $this->logger->error($e->getMessage());
            $this->manager->addErrorMessage(__('We can\'t update hobby'));
        } catch (LocalizedException $e) {
            $this->manager->addErrorMessage(__('We can\'t update hobby'));
        }

        return $resultRedirect->setPath('*/*/edit');
    }
}
