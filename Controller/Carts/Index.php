<?php
/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Controller\Carts;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Schatzmann\CartSharing\Api\CartSharingInterface;

/**
 * Class Index
 * @package CartSharing
 */
class Index implements ActionInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var CartSharingInterface
     */
    protected $cartSharing;

    /**
     * @param RequestInterface $request
     * @param ResultFactory $resultFactory
     * @param ManagerInterface $messageManager
     * @param CartSharingInterface $cartSharing
     */
    public function __construct(
        RequestInterface $request,
        ResultFactory $resultFactory,
        ManagerInterface $messageManager,
        CartSharingInterface $cartSharing
    ) {
        $this->request = $request;
        $this->resultFactory = $resultFactory;
        $this->messageManager = $messageManager;
        $this->cartSharing = $cartSharing;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $sharingHash = $this->request->getParam('cart');

        if ($this->cartSharing->getSharedCart($sharingHash)) {
            return $redirect->setUrl('/checkout/cart');
        }

        $this->messageManager->addErrorMessage(__('This cart is no longer available or the cart sharing URL is invalid.'));

        return $redirect->setUrl('/');
    }
}
