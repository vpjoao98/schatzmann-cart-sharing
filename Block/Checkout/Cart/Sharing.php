<?php
/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Block\Checkout\Cart;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Schatzmann\CartSharing\Helper\Config\CartSharingHelper;

/**
 * Class Sharing
 * @package CartSharing
 */
class Sharing extends Template
{
    /**
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var Json
     */
    protected $json;

    /**
     * @var CartSharingHelper
     */
    protected $cartSharingHelper;

    /**
     * @param CheckoutSession $checkoutSession
     * @param Json $json
     * @param Context $context
     * @param CartSharingHelper $cartSharingHelper
     * @param array $data
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        Json $json,
        Context $context,
        CartSharingHelper $cartSharingHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->checkoutSession = $checkoutSession;
        $this->json = $json;
        $this->cartSharingHelper = $cartSharingHelper;
    }

    /**
     * @return bool
     * @throws LocalizedException
     */
    public function isEnabledForCustomer(): bool
    {
        if (!$this->cartSharingHelper->isModuleEnabled()) {
            return false;
        }

        $quote = $this->checkoutSession->getQuote();

        if ($quote->getCustomerIsGuest()) {
            return $this->cartSharingHelper->isAvailableForGroup();
        }

        $customer = $quote->getCustomer();

        return $customer->getCustomAttribute('can_share_cart')->getValue() && $this->cartSharingHelper->isAvailableForGroup($customer->getGroupId());
    }

    /**
     * @return bool
     */
    public function isPopupEnabled(): bool
    {
        return $this->cartSharingHelper->isPopupEnabled();
    }

    /**
     * @return string
     */
    public function getJsLayout(): string
    {
        $jsLayout = $this->getData('jsLayout');
        $jsLayout['components']['cartSharingForm']['cartSharingKeyLength'] = $this->cartSharingHelper->getCartSharingKeyLength();
        $jsLayout['components']['cartSharingForm']['authenticateCartSharing'] = $this->cartSharingHelper->isAuthenticationEnabled();

        return $this->json->serialize($jsLayout);
    }
}
