<?php

namespace Commercers\DeepTracking\Service\Api\Dpd;

use Soapclient;
use Commercers\DeepTracking\Helper\Config;
use Commercers\DeepTracking\Logger\Logger;

class Auth {

	/**
	 * @var Config
	 */
	protected $_config;

	/**
	 * @var Logger
	 */
	private $_logger;

	protected $_loginWsdl;

	protected $_options;

	public function __construct(
        Config $config,
        Logger $logger
	) {
		$this->_config = $config;
		$this->_logger = $logger;

		$this->_loginWsdl = $this->_config->getDpdLogin();
		$this->_options = array(
			'delisId' => $this->_config->getDpdDelisid(),
			'password' => $this->_config->getDpdPassword(),
			'messageLanguage' => 'de_DE',
		);
	}

	public function auth(){
		$auth = null;
		try {
			$client = new Soapclient($this->_loginWsdl);
			$auth = $client->getAuth($this->_options);
			$auth->return->messageLanguage = $this->_options['messageLanguage'];
		}
		catch (\Exception $e){
			$this->_logger->err($e->getMessage());
		}

		return $auth->return;
	}

}
