<?php

namespace Commercers\DeepTracking\Model;

class DpdLog extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface {

	const CACHE_TAG = 'commercers_deeptracking_dpd_log';

	protected $_cacheTag = 'commercers_deeptracking_dpd_log';

	protected $_eventPrefix = 'commercers_deeptracking_dpd_log';

	protected function _construct()
	{
		$this->_init('Commercers\DeepTracking\Model\ResourceModel\DpdLog');
	}

	public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}
}
