<?php

namespace Commercers\DeepTracking\Model\Config\Source\Hellmann;

class Status
{
    const HELLMANN_IN_TRANSFER = 'HELLMANN_IN_TRANSFER';
    const HELLMANN_ACTION_NECESSARY = 'HELLMANN_ACTION_NECESSARY';
    const HELLMANN_DELIVERED_WITH_DAMAGES = 'HELLMANN_DELIVERED_WITH_DAMAGES';
    const HELLMANN_DELIVERED_SUCCESSFULLY = 'HELLMANN_DELIVERED_SUCCESSFULLY';

    public $_statusMapping = [
        'GREEN' => self::HELLMANN_IN_TRANSFER,
        'ORANGE' => self::HELLMANN_ACTION_NECESSARY,
        'RED' => self::HELLMANN_DELIVERED_WITH_DAMAGES,
        'WHITE' => self::HELLMANN_DELIVERED_SUCCESSFULLY
    ];

    public function getStatusMapping($statusCode){
        return $this->_statusMapping[$statusCode];
    }
}
