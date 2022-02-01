<?php
/**
 * @copyright Copyright © 2021 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Model;

use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Math\Random as MathRandom;
use Magento\Quote\Api\Data\CartInterface;
use Schatzmann\CartSharing\Api\CartSharingInterface;
use Schatzmann\CartSharing\Helper\Config\CartSharingHelper;
use Schatzmann\CartSharing\Logger\CartSharingLogger;

/**
 * Class CartSharing
 * @package CartSharing
 */
class CartSharing implements CartSharingInterface
{
    /**
     * Base Url path for shared carts.
     */
    const CART_SHARING_URL = 'shared/carts/?cart=';

    /**
     * Characters that will be used to create Cart Sharing Key
     */
    const CART_SHARING_KEY_CHARACTERS = MathRandom::CHARS_UPPERS . MathRandom::CHARS_DIGITS;

    /**
     * Number of characters that the cart sharing hash will have
     */
    const CART_SHARING_HASH_LENGTH = 12;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var FilterBuilder
     */
    protected $filterBuilder;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var MathRandom
     */
    protected $mathRandom;

    /**
     * @var CartSharingHelper
     */
    protected $cartSharingHelper;

    /**
     * @var CartSharingLogger
     */
    protected $logger;

    public function __construct(
        CustomerRepositoryInterface $customerRepository,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        MathRandom $mathRandom,
        CartSharingHelper $cartSharingHelper,
        CartSharingLogger $logger
    ) {
        $this->customerRepository = $customerRepository;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->mathRandom = $mathRandom;
        $this->cartSharingHelper = $cartSharingHelper;
        $this->logger = $logger;
    }

    public function setSharedCartData(CartInterface $cart)
    {
        // TODO: Implement setSharedCartData() method.
    }

    public function getSharedCart(string $sharingHash): CartInterface
    {
        // TODO: Implement getSharedCart() method.
    }

    /**
     * @param CustomerInterface $customer
     * @return null|string
     */
    public function createSharingKey(CustomerInterface $customer): ?string
    {
        if ($customerId = $customer->getId()) {
            try {
                $customer = $this->customerRepository->getById($customerId);
            } catch (LocalizedException $e) {
                $this->logger->error(__('Could not get customer with id %1.', $customer->getId()));
            }
        }

        if ($attribute = $customer->getCustomAttribute('cart_sharing_key')) {
            if (strlen($attribute->getValue()) == $this->cartSharingHelper->getCartSharingKeyLength()) {
                return $attribute->getValue();
            }
        }

        $sharingKey = null;

        try {
            while (!$sharingKey) {
                $temporaryKey = $this->mathRandom->getRandomString($this->cartSharingHelper->getCartSharingKeyLength(), self::CART_SHARING_KEY_CHARACTERS);
                if (!$this->filterEntityRepository('cart_sharing_key', '=', $temporaryKey, $this->customerRepository)) {
                    $sharingKey = $temporaryKey;
                }
            }
        } catch (LocalizedException $e) {
            $this->logger->error(__('Could not generate Cart Sharing Key.' . $e->getMessage()));
        }

        return $sharingKey;
    }

    public function createSharingUrl(string $cartSharingKey = null): string
    {
        // TODO: Implement createSharingUrl() method.
    }

    /**
     * Generic method to add single filter to entity repository's search criteria
     *
     * @param string $field
     * @param string $condition
     * @param string $value
     * @param $entityRepository
     * @return array
     */
    public function filterEntityRepository(string $field, string $condition, string $value, $entityRepository): array
    {
        $this->searchCriteriaBuilder->addFilter($field, $value, $condition);
        $searchCriteria = $this->searchCriteriaBuilder->create();

        return $entityRepository->getList($searchCriteria)->getItems();
    }
}
