<?php

namespace Commercers\DeepTracking\Model\Adminhtml\Config\Source\Order;


class Status extends \Magento\Sales\Model\Config\Source\Order\Status
{
    protected $_stateStatuses = null;

    public function toOptionArray()
    {
        $array = parent::toOptionArray();
        foreach ($array as &$value) {
            $value['label'] = __($value['label']);
        }
        return $array;
    }
}
