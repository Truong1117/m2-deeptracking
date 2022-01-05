<?php

namespace Commercers\DeepTracking\Service\Api\Hellmann;

use GuzzleHttp\Client;
use Commercers\DeepTracking\Helper\Config;
use Commercers\DeepTracking\Logger\Logger;
use Commercers\DeepTracking\Model\Config\Source\Hellmann\Status;

class ParcelTrack {

	/**
	 * @var Config
	 */
	protected $_config;

	/**
	 * @var Logger
	 */
	private $_logger;

	private $_status;

	public function __construct(
        Config $config,
		Logger $logger,
        Status $status
	) {
		$this->_config = $config;
		$this->_logger = $logger;
        $this->_status = $status;
	}

	public function getStatus($parcelLabelNumber = null){
		$result = [];
		try {
            $client = new Client();
            $baseUri = $this->_config->getHellmannApiUrl();
            $response = $client->request('GET', $baseUri ,[
                'query' => [
                    //'reference' => '00340327471376116090' // FOR testing only
                    'reference' => $parcelLabelNumber,
                    'transportmode' => 'ROAD'
                ]
            ]);

            $trackingData = json_decode($response->getBody(),true);

            if($trackingData['paging']['totalFound']) {
                foreach ($trackingData['shipmentList'] as $data) {
                    if(isset($data['latestStatusColor']) && $data['latestStatusColor']) {
                        $latestStatusCode = $this->_status->getStatusMapping($data['latestStatusColor']);
                        $latestStatusDescription = $data['latestStatusScreenName'];

                        $result = [
                            'statusCode' => $latestStatusCode,
                            'statusDescription' => $latestStatusDescription
                        ];

                        break;
                    }
                }

            }

            return $result;
		}
		catch (\Exception $e){
			$this->_logger->err($e->getMessage());
		}

		return $result;
	}

}
