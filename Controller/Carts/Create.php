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
use Magento\Framework\Controller\Result\Raw;
use Schatzmann\CartSharing\Api\CartSharingInterface;

/**
 * Class Create
 * @package CartSharing
 */
class Create implements ActionInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var Raw
     */
    protected $rawResult;

    /**
     * @var CartSharingInterface
     */
    protected $cartSharing;

    /**
     * @param RequestInterface $request
     * @param Raw $rawResult
     * @param CartSharingInterface $cartSharing
     */
    public function __construct(
        RequestInterface $request,
        Raw $rawResult,
        CartSharingInterface $cartSharing
    ) {
        $this->request = $request;
        $this->rawResult = $rawResult;
        $this->cartSharing = $cartSharing;
    }

    /**
     * @return Raw
     */
    public function execute(): Raw
    {
        $cartSharingKey = $this->request->getParam('cart_sharing_key');
        $cartSharingUrl = $this->cartSharing->createSharingUrl($cartSharingKey);

        return $this->rawResult->setContents($cartSharingUrl);
    }
}
