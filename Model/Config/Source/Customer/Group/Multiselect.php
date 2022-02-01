<?php
/**
 * @copyright Copyright © 2021 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Model\Config\Source\Customer\Group;

use Magento\Customer\Model\ResourceModel\Group\Collection as CustomerGroupCollection;
use Magento\Framework\Data\OptionSourceInterface;

class Multiselect implements OptionSourceInterface
{
    /**
     * @var CustomerGroupCollection
     */
    protected $groupCollection;

    /**
     * @param CustomerGroupCollection $groupCollection
     */
    public function __construct(
        CustomerGroupCollection $groupCollection
    ){
        $this->groupCollection = $groupCollection;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return $this->groupCollection->toOptionArray();
    }
}
