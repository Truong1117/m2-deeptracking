<?php

namespace Commercers\DeepTracking\Service\Api\Dhl;

use Commercers\DeepTracking\Helper\Config;

class Credentials
{
    /**
     * API endpoint sandbox
     */
    const ENDPOINT_SANDBOX = 'https://cig.dhl.de/services/sandbox/rest/sendungsverfolgung';

    /**
     * API endpoint production
     */
    const ENDPOINT_PRODUCTION = 'https://cig.dhl.de/services/production/rest/sendungsverfolgung';

    public $_config;

    public function __construct(
        Config $config
    )
    {
        $this->_config = $config;
    }

    public function getCigUser() {
        return $this->_config->getDhlCigUser();
    }
    public function getCigPassword() {
        return $this->_config->getDhlCigPassword();
    }
    public function getCigEndpoint() {
        if($this->_config->getDhlCredentials() == 'ENDPOINT_PRODUCTION') {
            return self::ENDPOINT_PRODUCTION;
        }

        return self::ENDPOINT_SANDBOX;
    }
    public function getTntUser() {
        return $this->_config->getDhlTntUser();
    }
    public function getTntPassword() {
        return $this->_config->getDhlTntPassword();
    }

}
