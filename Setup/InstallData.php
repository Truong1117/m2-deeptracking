<?php

namespace Commercers\DeepTracking\Setup;

use Commercers\DeepTracking\Model\Config\Source\Dhl\Status as DhlStatus;
use Commercers\DeepTracking\Model\Config\Source\Dpd\Status as DpdStatus;
use Commercers\DeepTracking\Model\Config\Source\Hellmann\Status as HellmannStatus;
use Commercers\DeepTracking\Model\Config\Source\Ups\Status as UpsStatus;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface {

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context) {

        $setup->startSetup();

        $data = [];
        $data[] = ['status' => DpdStatus::DPD_ACCEPTED, 'label' => 'COMPLETE - Parcel handed to DPD'];
        $data[] = ['status' => DpdStatus::DPD_AT_SENDING_DEPOT, 'label' => 'COMPLETE - Arrived at DPD'];
        $data[] = ['status' => DpdStatus::DPD_ON_THE_ROAD, 'label' => 'COMPLETE - On the way to Destination'];
        $data[] = ['status' => DpdStatus::DPD_AT_DELIVERY_DEPOT, 'label' => 'COMPLETE - Arrived at Delivery Depot'];
        $data[] = ['status' => DpdStatus::DPD_DELIVERED, 'label' => 'COMPLETE - Delivered'];

        $setup->getConnection()->insertArray($setup->getTable('sales_order_status'), ['status', 'label'], $data);

        $setup->getConnection()->insertArray(
            $setup->getTable('sales_order_status_state'),
            ['status', 'state', 'is_default','visible_on_front'],
            [
                [DpdStatus::DPD_ACCEPTED,'complete', '0', '1'],
                [DpdStatus::DPD_AT_SENDING_DEPOT, 'complete', '0', '1'],
                [DpdStatus::DPD_ON_THE_ROAD, 'complete', '0', '1'],
                [DpdStatus::DPD_AT_DELIVERY_DEPOT, 'complete', '0', '1'],
                [DpdStatus::DPD_DELIVERED, 'complete', '0', '1']
            ]
        );

        $data = [];
        $data[] = ['status' => DhlStatus::DHL_DELIVERED, 'label' => 'COMPLETE - Delivered'];
        $data[] = ['status' => DhlStatus::DHL_IN_DELIVERY, 'label' => 'COMPLETE - In Delivery'];
        $data[] = ['status' => DhlStatus::DHL_DESTINATION_PARCEL_CENTER, 'label' => 'COMPLETE - Destination parcel center'];
        $data[] = ['status' => DhlStatus::DHL_COLLECTION_SUCCESSFUL, 'label' => 'COMPLETE - Collection successful'];
        $data[] = ['status' => DhlStatus::DHL_START_PARCEL_CENTER, 'label' => 'COMPLETE - Transport to the start parcel center'];
        $data[] = ['status' => DhlStatus::DHL_DELIVERY_TO_PACKSTATION, 'label' => 'COMPLETE - Delivery to PACKSTATION'];

        $setup->getConnection()->insertArray($setup->getTable('sales_order_status'), ['status', 'label'], $data);

        $setup->getConnection()->insertArray(
            $setup->getTable('sales_order_status_state'),
            ['status', 'state', 'is_default','visible_on_front'],
            [
                [DhlStatus::DHL_DELIVERED,'complete', '0', '1'],
                [DhlStatus::DHL_IN_DELIVERY, 'complete', '0', '1'],
                [DhlStatus::DHL_DESTINATION_PARCEL_CENTER, 'complete', '0', '1'],
                [DhlStatus::DHL_COLLECTION_SUCCESSFUL, 'complete', '0', '1'],
                [DhlStatus::DHL_START_PARCEL_CENTER, 'complete', '0', '1'],
                [DhlStatus::DHL_DELIVERY_TO_PACKSTATION, 'complete', '0', '1']
            ]
        );

        $data = [];
        $data[] = ['status' => HellmannStatus::HELLMANN_IN_TRANSFER, 'label' => 'COMPLETE - In transfer / On road to consignee'];
        $data[] = ['status' => HellmannStatus::HELLMANN_ACTION_NECESSARY, 'label' => 'COMPLETE - Action necessary'];
        $data[] = ['status' => HellmannStatus::HELLMANN_DELIVERED_WITH_DAMAGES, 'label' => 'COMPLETE - Delivered with damages / shortages'];
        $data[] = ['status' => HellmannStatus::HELLMANN_DELIVERED_SUCCESSFULLY, 'label' => 'COMPLETE - Delivered successfully'];

        $setup->getConnection()->insertArray($setup->getTable('sales_order_status'), ['status', 'label'], $data);

        $setup->getConnection()->insertArray(
            $setup->getTable('sales_order_status_state'),
            ['status', 'state', 'is_default','visible_on_front'],
            [
                [HellmannStatus::HELLMANN_IN_TRANSFER,'complete', '0', '1'],
                [HellmannStatus::HELLMANN_ACTION_NECESSARY, 'complete', '0', '1'],
                [HellmannStatus::HELLMANN_DELIVERED_WITH_DAMAGES, 'complete', '0', '1'],
                [HellmannStatus::HELLMANN_DELIVERED_SUCCESSFULLY, 'complete', '0', '1'],
            ]
        );

        $data = [];
        $data[] = ['status' => UpsStatus::UPS_DELIVERED, 'label' => 'COMPLETE - UPS Delivery'];
        $data[] = ['status' => UpsStatus::UPS_EXCEPTION, 'label' => 'COMPLETE - UPS Exception'];
        $data[] = ['status' => UpsStatus::UPS_IN_TRANSIT, 'label' => 'COMPLETE - UPS In transit'];
        $data[] = ['status' => UpsStatus::UPS_MANIFEST_PICKUP, 'label' => 'COMPLETE - UPS Manifest pickup'];
        $data[] = ['status' => UpsStatus::UPS_PICKUP, 'label' => 'COMPLETE - UPS Pickup'];

        $setup->getConnection()->insertArray($setup->getTable('sales_order_status'), ['status', 'label'], $data);

        $setup->getConnection()->insertArray(
            $setup->getTable('sales_order_status_state'),
            ['status', 'state', 'is_default','visible_on_front'],
            [
                [UpsStatus::UPS_DELIVERED,'complete', '0', '1'],
                [UpsStatus::UPS_EXCEPTION, 'complete', '0', '1'],
                [UpsStatus::UPS_IN_TRANSIT, 'complete', '0', '1'],
                [UpsStatus::UPS_MANIFEST_PICKUP, 'complete', '0', '1'],
                [UpsStatus::UPS_PICKUP, 'complete', '0', '1']
            ]
        );

        $setup->endSetup();
    }
}
