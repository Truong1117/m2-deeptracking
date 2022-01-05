<?php

namespace Commercers\DeepTracking\Model\ResourceModel\HellmannLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'commercers_deeptracking_hellmann_log_collection';
	protected $_eventObject = 'hellmann_log_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Commercers\DeepTracking\Model\HellmannLog', 'Commercers\DeepTracking\Model\ResourceModel\HellmannLog');
	}

}
