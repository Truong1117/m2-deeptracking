<?php

namespace Commercers\DeepTracking\Model\ResourceModel\DhlLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'commercers_deeptracking_dhl_log_collection';
	protected $_eventObject = 'dhl_log_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Commercers\DeepTracking\Model\DhlLog', 'Commercers\DeepTracking\Model\ResourceModel\DhlLog');
	}

}
