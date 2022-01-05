<?php

namespace Commercers\DeepTracking\Cron\Dhl;

use Magento\Framework\App\ResourceConnection;
use Magento\Sales\Model\OrderFactory;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;
use Commercers\DeepTracking\Helper\Config;
use Commercers\DeepTracking\Model\DhlLogFactory;
use Commercers\DeepTracking\Service\Api\Dhl\ShipmentTracking;
use Commercers\DeepTracking\Service\Api\Dhl\RequestBuilder;
use Commercers\DeepTracking\Model\Config\Source\Dhl\Status as DhlStatus;
use Commercers\DeepTracking\Logger\Logger;

class Track
{
    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderFactory;

    protected $_log;

    protected $_config;

    protected $_resource;

    protected $_orderCollectionFactory;

    protected $_shipmentTracking;

    protected $_dhlStatus;

    /**
     * @var Logger
     */
    private $_logger;

    public function __construct(
        Config $config,
        ResourceConnection $resource,
        OrderFactory $orderFactory,
        CollectionFactory  $orderCollectionFactory,
        DhlLogFactory $log,
        ShipmentTracking $shipmentTracking,
        DhlStatus $dhlStatus,
        Logger $logger
    ) {
        $this->_config = $config;
        $this->_resource = $resource;
        $this->_orderFactory = $orderFactory;
        $this->_log = $log;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_shipmentTracking = $shipmentTracking;
        $this->_dhlStatus = $dhlStatus;
    }

    public function execute(){
        if(!$this->_config->isDhlEnabled()){
            return;
        }

        $log = $this->_log->create()->getCollection();
        $log->getSelect()->order(array('updated_at DESC', 'order_id DESC'));
        $lastOrderRun = $log->getFirstItem()->getOrderId();

        $daysBack = $this->_config->getDhlDayBack();
        $orderPerRun = $this->_config->getDhlCronLimit();
        $carrierTitle = $this->_config->getDhlCarrierTitle();

        $filterStatuses = explode(',',$this->_config->getDhlOrderStatuses());

        if($daysBack <= 1){
            $daysBackTime = strtotime('-'.$daysBack." day");
        } else {
            $daysBackTime = strtotime('-'.$daysBack." days");
        }
        $date = date('Y-m-d h:i:s', $daysBackTime);

        $collection = $this->_orderCollectionFactory->create()
            ->addFieldToFilter('entity_id',['gt'=>$lastOrderRun])
            ->addFieldToFilter('status',['in'=>$filterStatuses])
        ;

        $collection->getSelect()
            ->joinLeft(
               ['sales_shipment_track'=>$this->_resource->getTableName('sales_shipment_track')],
               'main_table.entity_id = sales_shipment_track.order_id',
               [
                   'title' => 'sales_shipment_track.title',
                   'order_id'=>'sales_shipment_track.order_id',
                   'track_number' => 'sales_shipment_track.track_number',
                   'created_at' => 'sales_shipment_track.created_at'
               ]
            )
            ->where('sales_shipment_track.created_at >= ?',$date)
            ->where('order_id IS NOT NULL')
            ->where('track_number IS NOT NULL')
            ->where('title LIKE "%'.$carrierTitle.'%"')
            ->order('entity_id ASC')
            ->group('entity_id')
            ->limit($orderPerRun)
        ;

        if(!$collection->count()){
            $collection->getSelect()->reset(\Zend_Db_Select::WHERE);
            $collection
                ->addFieldToFilter('status',['in'=>$filterStatuses])
            ;
            $collection
                ->getSelect()
                ->where('sales_shipment_track.created_at >= ?',$date)
                ->where('order_id IS NOT NULL')
                ->where('track_number IS NOT NULL')
                ->where('title LIKE "%'.$carrierTitle.'%"')
                ->limit($orderPerRun)
            ;
        }
        foreach($collection->getData() as $item){
            $this->changeOrderStatus($item);
        }

        return $this;
    }

    public function changeOrderStatus($args){
        $trackingNumber = preg_replace('/\s+/', '', $args['track_number']);
        $trackingData = $this->_shipmentTracking->getDetailsAndEvents($trackingNumber,RequestBuilder::LANG_DE);

        $status = $this->_dhlStatus->toOptionArray();

        $orderStatus = '';
        if(!empty($trackingData['events'])){
            $statusDescription = '';
            foreach($status as $value){
                if(isset($trackingData['events']['0'])){
                    $orderStatus = $trackingData['events']['0']['standard-event-code'];
                    if($value['value'] = $this->_dhlStatus->getStatusMapping($orderStatus)) {
                        $orderStatus = $this->_dhlStatus->getStatusMapping($orderStatus);
                        $statusDescription = $value['label'];
                    }
                }
            }
            if($orderStatus){
                $order = $this->_orderFactory->create()->load($args['entity_id']);
                $currentStatus = $order['status'];

                if($currentStatus != $orderStatus){
                    $order->setStatus($orderStatus);
                    $order->addStatusToHistory(false,$statusDescription);
                    $order->save();
                }
            }
        }

        $log = $this->_log->create()->load($args['entity_id'],'order_id');
        if(!empty($log->getData())){
            if($orderStatus == DhlStatus::DHL_DELIVERED){
                $log->delete();
                return $this;
            } else {
                $logData = [
                    'updated_at' => date('Y-m-d h:i:s')
                ];
                $log->addData($logData);
            }
        }{
            $log->addData([
                'order_id' => $args['entity_id'],
                'tracking_number' => $trackingNumber,
                'status' => $orderStatus,
                'increment_id' => $args['increment_id'],
                'updated_at' => date('Y-m-d h:i:s')
            ]);
        }
        try {
            $log->save();
        } catch (\Exception $e){
            $this->_logger->err($e->getMessage());
        }
    }
}
