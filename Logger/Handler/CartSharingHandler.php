<?php
/**
 * @copyright Copyright © 2021 - Schatzmann. All rights reserved.
 * @author João Victor Pereira <vpjoao98@gmail.com>
 * @package CartSharing
 */

declare(strict_types=1);

namespace Schatzmann\CartSharing\Logger\Handler;

use Magento\Framework\Logger\Handler\Base as BaseHandler;
use Monolog\Logger as MonologLogger;

/**
 * Class CartSharingHandler
 * @package CartSharing
 */
class CartSharingHandler extends BaseHandler
{
    /**
     * @var int
     */
    protected $loggerType = MonologLogger::ERROR;

    /**
     * @var string
     */
    protected $fileName = '/var/log/cart-sharing.log';
}
