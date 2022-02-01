<?php
/**
 * @copyright Copyright © 2021 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Plugin\Customer;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Schatzmann\CartSharing\Api\CartSharingInterface;
use Schatzmann\CartSharing\Helper\Config\CartSharingHelper;

/**
 * Class AddSharingKeyBeforeSave
 * @package CartSharing
 */
class AddSharingKeyBeforeSave
{
    /**
     * @var CartSharingInterface
     */
    protected $cartSharing;

    /**
     * @var CartSharingHelper
     */
    protected $cartSharingHelper;

    /**
     * @param CartSharingInterface $cartSharing
     * @param CartSharingHelper $cartSharingHelper
     */
    public function __construct(
        CartSharingInterface $cartSharing,
        CartSharingHelper $cartSharingHelper
    ) {
        $this->cartSharing = $cartSharing;
        $this->cartSharingHelper = $cartSharingHelper;
    }

    /**
     * @param CustomerRepositoryInterface $subject
     * @param CustomerInterface $customerData
     * @return array
     */
    public function beforeSave(CustomerRepositoryInterface $subject, CustomerInterface $customerData): array
    {
        if (!$this->cartSharingHelper->isModuleEnabled()) {
            return [$customerData];
        }

        $customerData->setCustomAttribute('cart_sharing_key', $this->cartSharing->createSharingKey($customerData));

        return [$customerData];
    }
}
