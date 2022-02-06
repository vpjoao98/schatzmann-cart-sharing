<?php
/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Model;

use Exception;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Api\Data\CustomerInterface;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Math\Random as MathRandom;
use Magento\Framework\UrlInterface;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Store\Model\StoreManagerInterface;
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
     * @var CheckoutSession
     */
    protected $checkoutSession;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $customerRepository;

    /**
     * @var CustomerSession
     */
    protected $customerSession;

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
     * @var CartManagementInterface
     */
    protected $cartManagement;

    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var CartSharingHelper
     */
    protected $cartSharingHelper;

    /**
     * @var CartSharingLogger
     */
    protected $logger;

    /**
     * @param CheckoutSession $checkoutSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerSession $customerSession
     * @param FilterBuilder $filterBuilder
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param MathRandom $mathRandom
     * @param CartManagementInterface $cartManagement
     * @param CartRepositoryInterface $cartRepository
     * @param StoreManagerInterface $storeManager
     * @param CartSharingHelper $cartSharingHelper
     * @param CartSharingLogger $logger
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CustomerRepositoryInterface $customerRepository,
        CustomerSession $customerSession,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        MathRandom $mathRandom,
        CartManagementInterface $cartManagement,
        CartRepositoryInterface $cartRepository,
        StoreManagerInterface $storeManager,
        CartSharingHelper $cartSharingHelper,
        CartSharingLogger $logger
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->customerRepository = $customerRepository;
        $this->customerSession = $customerSession;
        $this->filterBuilder = $filterBuilder;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->mathRandom = $mathRandom;
        $this->cartManagement = $cartManagement;
        $this->cartRepository = $cartRepository;
        $this->storeManager = $storeManager;
        $this->cartSharingHelper = $cartSharingHelper;
        $this->logger = $logger;
    }

    /**
     * @param string $sharingHash
     * @return bool
     */
    public function getSharedCart(string $sharingHash): bool
    {
        if (!$sharedCart = current($this->filterEntityRepository('sharing_hash', '=', $sharingHash, $this->cartRepository))) {
            return false;
        }

        try {
            if ($this->checkoutSession->getQuote()->isEmpty()) {
                if ($customerId = $this->customerSession->getCustomerId()) {
                    $newQuote = $this->cartManagement->createEmptyCartForCustomer($customerId);
                } else {
                    $newQuote =  $this->cartManagement->createEmptyCart();
                }

                $this->checkoutSession->setQuoteId($newQuote);
            }

            $this->checkoutSession->getQuote()->merge($sharedCart);
            $this->cartRepository->save($this->checkoutSession->getQuote());
            $this->cartRepository->delete($sharedCart);

            return true;
        } catch (Exception $e) {
            $this->logger->error(__('Could not get the shared cart %1.', $sharingHash));
        }

        return false;
    }

    /**
     * @param CustomerInterface $customer
     * @return string|null
     */
    public function createSharingKey(CustomerInterface $customer): ?string
    {
        if ($customerId = $customer->getId()) {
            try {
                $customer = $this->customerRepository->getById($customerId);
            } catch (Exception $e) {
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
        } catch (Exception $e) {
            $this->logger->error(__('Could not generate Cart Sharing Key.' . $e->getMessage()));
        }

        return $sharingKey;
    }

    /**
     * @param string|null $cartSharingKey
     * @return string|null
     */
    public function createSharingUrl(string $cartSharingKey = null): ?string
    {
        try {
            $quote = $this->checkoutSession->getQuote();
        } catch (Exception $e) {
            $this->logger->error(__('Could not get quote from session.' . $e->getMessage()));
            return null;
        }

        /**
         * Look for customers that have the cartSharingKey assigned
         */
        if ($cartSharingKey && $this->cartSharingHelper->isAuthenticationEnabled()) {
            $filteredCustomers = $this->filterEntityRepository('cart_sharing_key', '=', $cartSharingKey, $this->customerRepository);

            if (count($filteredCustomers) !== 1) {
                return null;
            }

            $quote->setData('shared_by', current($filteredCustomers)->getEmail());
        }

        try {
            $sharingHash = $this->mathRandom->getRandomString(self::CART_SHARING_HASH_LENGTH);
            $quote->setData('sharing_hash', $sharingHash);
            $quote->setIsActive(false);
            $this->cartRepository->save($quote);
            $this->checkoutSession->clearStorage();

            return $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_DIRECT_LINK) . self::CART_SHARING_URL . $sharingHash;
        } catch (Exception $e) {
            $this->logger->error(__('Could not generate sharing url.' . $e->getMessage()));
        }

        return null;
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
