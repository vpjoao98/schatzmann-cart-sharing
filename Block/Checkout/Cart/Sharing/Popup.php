<?php
/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Block\Checkout\Cart\Sharing;

use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Schatzmann\CartSharing\Helper\Config\CartSharingHelper;

/**
 * Class Popup
 * @package CartSharing
 */
class Popup extends Template
{
    /**
     * @var Json
     */
    protected $json;

    /**
     * @var CartSharingHelper
     */
    protected $cartSharingHelper;

    /**
     * @param Json $json
     * @param Context $context
     * @param CartSharingHelper $cartSharingHelper
     * @param array $data
     */
    public function __construct(
        Json $json,
        Context $context,
        CartSharingHelper $cartSharingHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->json = $json;
        $this->cartSharingHelper = $cartSharingHelper;
    }

    /**
     * @return string
     */
    public function getJsLayout(): string
    {
        $jsLayout = $this->getData('jsLayout');
        $jsLayout['components']['cartSharingPopup']['children']['cartSharingForm']['cartSharingKeyLength'] = $this->cartSharingHelper->getCartSharingKeyLength();
        $jsLayout['components']['cartSharingPopup']['children']['cartSharingForm']['authenticateCartSharing'] = $this->cartSharingHelper->isAuthenticationEnabled();

        return $this->json->serialize($jsLayout);
    }
}
