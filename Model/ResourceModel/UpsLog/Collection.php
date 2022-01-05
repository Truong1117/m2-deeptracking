<?php
/**
 * Created by PhpStorm.
 * User: niken
 * Date: 9/24/18
 * Time: 16:40
 */
namespace Commercers\DeepTracking\Model\ResourceModel\UpsLog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'commercers_deeptracking_ups_log_collection';
	protected $_eventObject = 'ups_log_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Commercers\DeepTracking\Model\UpsLog', 'Commercers\DeepTracking\Model\ResourceModel\UpsLog');
	}

}
