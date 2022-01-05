<?php

namespace Commercers\DeepTracking\Service\Api\Ups;

use Commercers\DeepTracking\Helper\Config;
use Commercers\DeepTracking\Logger\Logger;
use Commercers\DeepTracking\Model\Config\Source\Ups\Status;
use Ups\Tracking;

class ParcelTrack {

	/**
	 * @var Config
	 */
	protected $_config;

	/**
	 * @var Logger
	 */
	private $_logger;

	/**
	 * @var Status
	 */
	private $_status;

	/**
	 * @var Tracking
	 */
	protected $_tracking;

	public function __construct(
		Config $config,
		Logger $logger,
		Status $status
	) {
		$this->_config = $config;
		$this->_logger = $logger;
		$this->_status = $status;
		$userId = $this->_config->getUpsUserId();
		$password = $this->_config->getUpsPassword();
		$accessKey = $this->_config->getUpsAccessKey();
		$isSandBox = $this->_config->getUpsIsSandbox();
		$this->_tracking = new Tracking($accessKey, $userId, $password, $isSandBox);
	}

	public function getStatus($parcelLabelNumber){
		$result = array();
		try {
			$shipment = $this->_tracking->track($parcelLabelNumber);
			if($shipment) {
				$statusInfo = [];
				foreach($shipment->Package->Activity as $activity) {
					$statusInfo = $activity;
					break;
				}

				if($statusInfo) {
					$status = $this->_status->getStatusMapping($statusInfo->Status->StatusType->Code);
					$result = array(
						'statusCode' => $status,
						'statusLabel' => $statusInfo->Status->StatusType->Description,
						'statusDescription' => $statusInfo->Status->StatusType->Description,
					);
				}
			}
		}
		catch (\Exception $e){
			$this->_logger->err('Parcel number: '.$parcelLabelNumber.' - Message: '.$e->getMessage());
		}

		return $result;
	}

}
