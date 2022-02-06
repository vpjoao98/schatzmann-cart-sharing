<?php
/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Controller\Customer;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Carts implements ActionInterface
{
    /**
     * @var PageFactory
     */
    protected $pageFactory;

    /**
     * @param PageFactory $pageFactory
     */
    public function __construct(
        PageFactory $pageFactory
    ){
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return ResultInterface
     */
    public function execute()
    {
        $resultPage = $this->pageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('My Shared Carts'));

        return $resultPage;
    }
}
