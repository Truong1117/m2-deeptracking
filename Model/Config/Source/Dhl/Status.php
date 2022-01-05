<?php

namespace Commercers\DeepTracking\Model\Config\Source\Dhl;

class Status implements \Magento\Framework\Option\ArrayInterface
{
    const DHL_DELIVERY_TO_PACKSTATION = 'DHL_DELIVERY_TO_PACKSTATION';
    const DHL_START_PARCEL_CENTER = 'DHL_START_PARCEL_CENTER';
    const DHL_COLLECTION_SUCCESSFUL = 'DHL_COLLECTION_SUCCESSFUL';
    const DHL_DESTINATION_PARCEL_CENTER = 'DHL_DESTINATION_PARCEL_CENTER';
    const DHL_IN_DELIVERY = 'DHL_IN_DELIVERY';
    const DHL_DELIVERED = 'DHL_DELIVERED';

    public $_statusMapping = [
        'ZU' => self::DHL_DELIVERED,
        'PO' => self::DHL_IN_DELIVERY,
        'EE' => self::DHL_DESTINATION_PARCEL_CENTER,
        'AE' => self::DHL_COLLECTION_SUCCESSFUL,
        'AA' => self::DHL_START_PARCEL_CENTER,
        'ES' => self::DHL_DELIVERY_TO_PACKSTATION
    ];

    public function toOptionArray()
    {
        return [
            ['value' => self::DHL_DELIVERED, 'label' => __('Zustellung erfolgreich')],
            ['value' => self::DHL_IN_DELIVERY, 'label' => __('In Zustellung')],
            ['value' => self::DHL_DESTINATION_PARCEL_CENTER, 'label' => __('Ziel-Paketzentrum')],
            ['value' => self::DHL_COLLECTION_SUCCESSFUL, 'label' => __('Abholung erfolgreich')],
            ['value' => self::DHL_START_PARCEL_CENTER, 'label' => __('Transport zum Start-Paketzentrum')],
            ['value' => self::DHL_DELIVERY_TO_PACKSTATION, 'label' => __('Einlieferung in PACKSTATION')]
        ];
    }

    public function getStatusMapping($statusCode){
        return $this->_statusMapping[$statusCode];
    }
}
