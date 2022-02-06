<?php
/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Block\Customer\Account;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Api\Data\CartInterface;
use Magento\Store\Model\ScopeInterface;
use Schatzmann\CartSharing\Api\CartSharingInterface;
use Schatzmann\CartSharing\Helper\Config\CartSharingHelper;

/**
 * Class SharedCarts
 * @package CartSharing
 */
class SharedCarts extends Template
{
    /**
     * @var CustomerSession
     */
    protected $customerSession;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceFormat;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var CartSharingInterface
     */
    protected $cartSharing;

    /**
     * @var CartSharingHelper
     */
    protected $cartSharingHelper;

    /**
     * @param CustomerSession $customerSession
     * @param PriceCurrencyInterface $priceFormat
     * @param Context $context
     * @param CartRepositoryInterface $cartRepository
     * @param CartSharingInterface $cartSharing
     * @param CartSharingHelper $cartSharingHelper
     * @param array $data
     */
    public function __construct(
        CustomerSession $customerSession,
        PriceCurrencyInterface $priceFormat,
        Context $context,
        CartRepositoryInterface $cartRepository,
        CartSharingInterface $cartSharing,
        CartSharingHelper $cartSharingHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->customerSession = $customerSession;
        $this->priceFormat = $priceFormat;
        $this->cartRepository = $cartRepository;
        $this->cartSharing = $cartSharing;
        $this->cartSharingHelper = $cartSharingHelper;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->cartSharingHelper->isModuleEnabled();
    }

    /**
     * @return CartInterface[]
     */
    public function getSharedCarts(): array
    {
        $customerEmail = $this->customerSession->getCustomer()->getEmail();
        $carts = $this->cartSharing->filterEntityRepository('shared_by', '=', $customerEmail, $this->cartRepository);
        $sharedCarts = [];

        foreach ($carts as $cart) {
            $sharedCarts[] = [
                'id' => $cart->getId(),
                'date' => $this->formatDate($cart->getUpdatedAt()),
                'total' => $cart->getSubtotal(),
                'items' => $cart->getItemsCount(),
                'sharing_url' => $this->getBaseUrl() . CartSharingInterface::CART_SHARING_URL . $cart->getSharingHash()
            ];
        }

        if ($this->getSharedCount()) {
            return array_slice($sharedCarts, 0, $this->getSharedCount());
        }

        return $sharedCarts;
    }

    /**
     * @param float $price
     * @return string
     */
    public function getFormattedPrice(float $price): string
    {
        return $this->priceFormat->convertAndFormat($price, true, PriceCurrencyInterface::DEFAULT_PRECISION, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function getSharedCartsHistoryUrl(): string
    {
        return $this->getBaseUrl() . 'shared/customer/carts';
    }

    /**
     * @return string
     */
    public function getCustomerSharingKey(): string
    {
        return $this->customerSession->getCustomer()->getData('cart_sharing_key');
    }
}
