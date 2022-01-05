<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 9/26/18
 * Time: 00:06
 */
namespace Commercers\DeepTracking\Logger;

use Monolog\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
	/**
	 * Logging level
	 * @var int
	 */
	protected $loggerType = Logger::ERROR;

	/**
	 * File name
	 * @var string
	 */
	protected $fileName = '/var/log/commercers_deeptracking.log';
}
