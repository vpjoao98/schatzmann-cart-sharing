<?php
/**
 * @copyright Copyright © 2021 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Api;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Quote\Api\Data\CartInterface;

/**
 * Interface CartSharingInterface
 * @package CartSharing
 */
interface CartSharingInterface
{
    /**
     * @param CartInterface $cart
     * @throws LocalizedException
     */
    public function setSharedCartData(CartInterface $cart);

    /**
     * @param string $sharingHash
     * @return CartInterface
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getSharedCart(string $sharingHash): CartInterface;

    /**
     * @param CustomerInterface $customer
     * @return null|string
     */
    public function createSharingKey(CustomerInterface $customer): ?string;

    /**
     * @param string|null $cartSharingKey
     * @return string
     * @throws LocalizedException
     */
    public function createSharingUrl(string $cartSharingKey = null): string;
}
