<?php
/**
 * @copyright Copyright © 2021 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Helper\Config;

use Magento\Customer\Api\Data\GroupInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Schatzmann\Base\Helper\SchatzmannBaseHelper;

/**
 * Class CartSharingHelper
 * @package CartSharing
 */
class CartSharingHelper extends AbstractHelper
{
    /**
     * Configuration path for cart_sharing_enabled field.
     */
    const CART_SHARING_ENABLED = 'cart_sharing/cart_sharing_options/cart_sharing_enabled';

    /**
     * Configuration path for cart_sharing_authentication field.
     */
    const CART_SHARING_AUTHENTICATION = 'cart_sharing/cart_sharing_options/cart_sharing_authentication';

    /**
     * Configuration path for cart_sharing_modal field.
     */
    const CART_SHARING_MODAL = 'cart_sharing/cart_sharing_options/cart_sharing_modal';

    /**
     * Configuration path for cart_sharing_key_length field.
     */
    const CART_SHARING_KEY_LENGTH = 'cart_sharing/cart_sharing_options/cart_sharing_key_length';

    /**
     * Configuration path for cart_sharing_groups field.
     */
    const CART_SHARING_GROUPS = 'cart_sharing/cart_sharing_options/cart_sharing_groups';

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var SchatzmannBaseHelper
     */
    protected $schatzmannBaseHelper;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param SchatzmannBaseHelper $schatzmannBaseHelper
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        SchatzmannBaseHelper $schatzmannBaseHelper
    ) {
        parent::__construct($context);
        $this->scopeConfig = $scopeConfig;
        $this->schatzmannBaseHelper = $schatzmannBaseHelper;
    }

    /**
     * @return bool
     */
    public function isModuleEnabled(): bool
    {
        return $this->getValue(self::CART_SHARING_ENABLED) && $this->schatzmannBaseHelper->isExtensionsEnabled();
    }

    /**
     * @return bool
     */
    public function isAuthenticationEnabled(): bool
    {
        return (bool) $this->getValue(self::CART_SHARING_AUTHENTICATION);
    }

    /**
     * @return bool
     */
    public function isModalEnabled(): bool
    {
        return (bool) $this->getValue(self::CART_SHARING_MODAL);
    }

    /**
     * @param int|string $groupId
     * @return bool
     */
    public function isAvailableForGroup($groupId = GroupInterface::NOT_LOGGED_IN_ID): bool
    {
        return in_array($groupId, $this->getCustomerGroups());
    }

    /**
     * @return array
     */
    public function getCustomerGroups(): array
    {
        if (!$this->getValue(self::CART_SHARING_GROUPS)) {
            return [];
        }

        return explode(',', $this->getValue(self::CART_SHARING_GROUPS));
    }

    /**
     * @return int
     */
    public function getCartSharingKeyLength(): int
    {
        return (int) $this->getValue(self::CART_SHARING_KEY_LENGTH);
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function getValue(string $path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }
}
