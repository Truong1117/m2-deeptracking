<?php

namespace Commercers\DeepTracking\Model\Config\Source\Dhl;

class Options implements \Magento\Framework\Option\ArrayInterface
{
     public function toOptionArray()
     {
          return [
               ['value' => 'ENDPOINT_SANDBOX', 'label' => __('Sandbox')],
               ['value' => 'ENDPOINT_PRODUCTION', 'label' => __('Production')]
          ];
     }
}
