<?php

namespace Commercers\DeepTracking\Service\Api\Dpd;

use Soapclient;
use SOAPHeader;
use Commercers\DeepTracking\Helper\Config;
use Commercers\DeepTracking\Logger\Logger;
use Commercers\DeepTracking\Model\Config\Source\Dpd\Status;

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
	 * @var Auth
	 */
	protected $_auth;

	protected $_parcelStatusWsdl;

	protected $_soapHeaderUrl;

	protected $_status;

	public function __construct(
        Config $config,
		Logger $logger,
		Auth $dpdAuth,
        Status $status
	) {
		$this->_config = $config;
		$this->_logger = $logger;
		$this->_auth = $dpdAuth->auth();
		$this->_parcelStatusWsdl = $this->_config->getDpdParcelLifeCycle();
		$this->_soapHeaderUrl = $this->_config->getDpdSoapHeaderUrl();
		$this->_status = $status;
	}

	public function getStatus($parcelLabelNumber){
		$result = array();
		try {
			$client = new Soapclient($this->_parcelStatusWsdl);
			$header = new SOAPHeader($this->_soapHeaderUrl, 'authentication', $this->_auth);

			$client->__setSoapHeaders($header);
			$response = $client->getTrackingData(['parcelLabelNumber' => $parcelLabelNumber]);

			$check = (array)$response->trackingresult;
			if (empty($check)) {
				$this->_logger->err('Parcel '.$parcelLabelNumber.' not found');
			} else {
				foreach($response->trackingresult->statusInfo as $statusInfo){
					if ($statusInfo->isCurrentStatus){
					    $status = $this->_status->getStatusMapping($statusInfo->status);

						$result = array(
							'statusCode' => $status,
							'statusLabel' => $statusInfo->label->content,
							'statusDescription' => $statusInfo->description->content->content,
						);

					}
				}
			}
		}
		catch (\Exception $e){
			$this->_logger->err($e->getMessage());
		}

		return $result;
	}

}
