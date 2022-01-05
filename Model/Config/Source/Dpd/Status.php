<?php

namespace Commercers\DeepTracking\Model\Config\Source\Dpd;

class Status
{
    const DPD_ACCEPTED = 'DPD_ACCEPTED';
    const DPD_AT_SENDING_DEPOT = 'DPD_AT_SENDING_DEPOT';
    const DPD_ON_THE_ROAD = 'DPD_ON_THE_ROAD';
    const DPD_AT_DELIVERY_DEPOT = 'DPD_AT_DELIVERY_DEPOT';
    const DPD_DELIVERED = 'DPD_DELIVERED';

    public $_statusMapping = [
        'ACCEPTED' => self::DPD_ACCEPTED,
        'AT_SENDING_DEPOT' => self::DPD_AT_SENDING_DEPOT,
        'ON_THE_ROAD' => self::DPD_ON_THE_ROAD,
        'AT_DELIVERY_DEPOT' => self::DPD_AT_DELIVERY_DEPOT,
        'DELIVERED' => self::DPD_DELIVERED
    ];

    public function getStatusMapping($statusCode){
        return $this->_statusMapping[$statusCode];
    }
}
