<?php
/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Api;

use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Math\Random as MathRandom;

/**
 * Interface CartSharingInterface
 * @package CartSharing
 */
interface CartSharingInterface
{
    /**
     * Base Url path for shared carts.
     */
    const CART_SHARING_URL = 'shared/carts/index?cart=';

    /**
     * Characters that will be used to create Cart Sharing Key
     */
    const CART_SHARING_KEY_CHARACTERS = MathRandom::CHARS_UPPERS . MathRandom::CHARS_DIGITS;

    /**
     * Number of characters that the cart sharing hash will have
     */
    const CART_SHARING_HASH_LENGTH = 12;

    /**
     * @param string $sharingHash
     * @return bool
     */
    public function getSharedCart(string $sharingHash): bool;

    /**
     * @param CustomerInterface $customer
     * @return null|string
     */
    public function createSharingKey(CustomerInterface $customer): ?string;

    /**
     * @param string|null $cartSharingKey
     * @return string|null
     */
    public function createSharingUrl(string $cartSharingKey = null): ?string;
}
