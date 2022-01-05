<?php

namespace Commercers\DeepTracking\Model\Config\Source\Ups;

class Status implements \Magento\Framework\Option\ArrayInterface
{
	const UPS_IN_TRANSIT = 'UPS_IN_TRANSIT';
	const UPS_DELIVERED = 'UPS_DELIVERED';
	const UPS_EXCEPTION = 'UPS_EXCEPTION';
	const UPS_PICKUP = 'UPS_PICKUP';
	const UPS_MANIFEST_PICKUP = 'UPS_MANIFEST_PICKUP';

	public $_statusMapping = [
		'I' => self::UPS_IN_TRANSIT,
		'D' => self::UPS_DELIVERED,
		'X' => self::UPS_EXCEPTION,
		'P' => self::UPS_PICKUP,
		'M' => self::UPS_MANIFEST_PICKUP
	];

	public function toOptionArray()
	{
		return [
			['value' => self::UPS_IN_TRANSIT, 'label' => __('In Transit')],
			['value' => self::UPS_DELIVERED, 'label' => __('Delivered')],
			['value' => self::UPS_EXCEPTION, 'label' => __('Exception')],
			['value' => self::UPS_PICKUP, 'label' => __('Pickup')],
			['value' => self::UPS_MANIFEST_PICKUP, 'label' => __('Manifest Pickup')]
		];
	}

	public function getStatusMapping($statusCode){
		return $this->_statusMapping[$statusCode];
	}
}
