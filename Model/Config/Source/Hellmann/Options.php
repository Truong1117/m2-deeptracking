<?php

namespace Commercers\DeepTracking\Model\Config\Source\Hellmann;

class Options implements \Magento\Framework\Option\ArrayInterface
{
     public function toOptionArray()
     {
          return [
               ['value' => Status::HELLMANN_IN_TRANSFER, 'label' => __('In transfer / On road to consignee')],
               ['value' => Status::HELLMANN_ACTION_NECESSARY, 'label' => __('Action necessary')],
               ['value' => Status::HELLMANN_DELIVERED_WITH_DAMAGES, 'label' => __('Delivered with damages / shortages')],
               ['value' => Status::HELLMANN_DELIVERED_SUCCESSFULLY, 'label' => __('Delivered successfully')]
          ];
     }
}
