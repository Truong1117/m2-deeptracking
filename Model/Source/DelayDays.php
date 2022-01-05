<?php

namespace Commercers\DeepTracking\Model\Source;

class DelayDays extends \Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend
{
    protected $_options = null;

    public function toOptionArray()
    {
        $arr = [];
        for($i = 1; $i<=30; $i++) {
            $arr[] = [
                'value' => $i,
                'label' => __('%1 days',$i)
            ];
        }
        return $arr;
    }

    public function getAllOptions()
    {
        return $this->toOptionArray();
    }
}
