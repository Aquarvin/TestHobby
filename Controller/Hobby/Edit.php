<?php
declare(strict_types=1);

namespace Test\Hobby\Controller\Hobby;

use Magento\Customer\Controller\AccountInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

/**
 * Hobby form page controller action.
 */
class Edit implements HttpGetActionInterface, AccountInterface
{

    /**
     * @param PageFactory $resultPageFactory
     */
    public function __construct(private readonly PageFactory $resultPageFactory)
    {
    }

    /**
     * Execute hobby edit form action
     *
     * @return ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Hobby'));
        return $resultPage;
    }
}
