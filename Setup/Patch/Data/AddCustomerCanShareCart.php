<?php
/**
 * @copyright Copyright © 2022 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Setup\Patch\Data;

use Exception;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Model\ResourceModel\Attribute as AttributeResource;
use Magento\Customer\Setup\CustomerSetup;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Schatzmann\CartSharing\Logger\CartSharingLogger;

/**
 * Class AddCustomerCanShareCart
 * @package CartSharing
 */
class AddCustomerCanShareCart implements DataPatchInterface, PatchRevertableInterface
{
    /**
     * @var AttributeResource
     */
    private $attributeResource;

    /**
     * @var CustomerSetup
     */
    private $customerSetup;

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var CartSharingLogger
     */
    private $logger;

    /**
     * @param AttributeResource $attributeResource
     * @param CustomerSetupFactory $customerSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CartSharingLogger $logger
     */
    public function __construct(
        AttributeResource $attributeResource,
        CustomerSetupFactory $customerSetupFactory,
        ModuleDataSetupInterface $moduleDataSetup,
        CartSharingLogger $logger
    ) {
        $this->attributeResource = $attributeResource;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetup = $customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        try {
            $this->customerSetup->addAttribute(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                'can_share_cart',
                [
                    'type' => 'int',
                    'label' => 'Can Share Cart',
                    'input' => 'boolean',
                    'default' => true,
                    'required' => false,
                    'visible' => true,
                    'system' => false,
                    'user_defined' => false,
                    'sort_order' => 310
                ]
            );

            $this->customerSetup->addAttributeToSet(
                CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                CustomerMetadataInterface::ATTRIBUTE_SET_ID_CUSTOMER,
                null,
                'can_share_cart'
            );

            $attribute = $this->customerSetup
                ->getEavConfig()
                ->getAttribute(
                    CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER,
                    'can_share_cart'
                );

            $attribute->setData(
                'used_in_forms', [
                    'adminhtml_customer'
                ]
            );

            $this->attributeResource->save($attribute);
        } catch (Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        try {
            $this->customerSetup->removeAttribute(CustomerMetadataInterface::ENTITY_TYPE_CUSTOMER, 'can_share_cart');
        } catch (Exception $exception) {
            $this->logger->critical($exception->getMessage());
        }

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
