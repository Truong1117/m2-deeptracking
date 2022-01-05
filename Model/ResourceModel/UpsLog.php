<?php

namespace Commercers\DeepTracking\Model\ResourceModel;

class UpsLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('commercers_deeptracking_ups_log', 'id');
	}
}
