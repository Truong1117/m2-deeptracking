<?php

namespace Commercers\DeepTracking\Model\ResourceModel;

class HellmannLog extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {
	public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}

	protected function _construct()
	{
		$this->_init('commercers_deeptracking_hellmann_log', 'id');
	}
}
