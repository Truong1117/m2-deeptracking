<?php

namespace Commercers\DeepTracking\Cron\Dpd;

use Commercers\DeepTracking\Service\Api\Dpd\ParcelTrack;
use Magento\Sales\Model\OrderFactory;
use Magento\Framework\Model\ResourceModel\Iterator;
use Magento\Framework\App\ResourceConnection;
use Commercers\DeepTracking\Model\DpdLogFactory;
use Commercers\DeepTracking\Logger\Logger;
use Commercers\DeepTracking\Helper\Config;
use Commercers\DeepTracking\Model\Config\Source\Dpd\Status as DpdStatus;

class Track {

	/**
	 * @var DpdLogFactory
	 */
	protected $_logFactory;

	/**
     * @var Iterator
     */

	protected $_iterator;

	/**
	 * @var OrderFactory
	 */
	protected $_orderFactory;

	protected $_resource;

	protected $_dpdParcelTrack;

	/**
	 * @var Logger
	 */
	private $_logger;

    /**
     * @var Config
     */
    private $_config;

	public function __construct(
        ParcelTrack $dpdParcelTrack,
		OrderFactory $orderFactory,
		Iterator $iterator,
		ResourceConnection $resource,
        DpdLogFactory $logFactory,
		Logger $logger,
        Config $config
	) {
		$this->_orderFactory = $orderFactory;
		$this->_dpdParcelTrack = $dpdParcelTrack;
		$this->_iterator = $iterator;
		$this->_resource = $resource;
		$this->_logFactory = $logFactory;
		$this->_logger = $logger;
		$this->_config = $config;
	}

	public function execute()
	{
		if(!$this->_config->isDpdEnabled()){
			return;
		}
		$daysBack = $this->_config->getDpdDayBack();
		$orderPerRun = $this->_config->getDpdCronLimit();
        $carrierTitle = $this->_config->getDpdCarrierTitle();

		$log = $this->_logFactory->create()->getCollection();
		$log->getSelect()->order(array('updated_at DESC', 'order_id DESC'));

		$lastOrderRun = $log->getFirstItem()->getOrderId();

		if($daysBack <= 1){
			$daysBackTime = strtotime('-'.$daysBack." day");
		} else {
			$daysBackTime = strtotime('-'.$daysBack." days");
		}
		$date = date('Y-m-d h:i:s', $daysBackTime);

		$filterStatuses = explode(',',$this->_config->getDpdOrderStatuses());

		$collection = $this->_orderFactory->create()->getCollection();
		$collection
			->addFieldToFilter('entity_id',['gt'=>$lastOrderRun])
			->addFieldToFilter('status',['in'=>$filterStatuses])
		;

		$collection
			->getSelect()
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

		$this->_iterator
			->walk( $collection->getSelect(), [ [$this, 'changeOrderStatus'] ] );
		return $this;
	}

	public function changeOrderStatus($args)
	{
		$trackingNumber = preg_replace('/\s+/', '', $args['row']['track_number']);
		if(strlen($trackingNumber)>14){
			$trackingNumber = substr($trackingNumber, 0, 14);
		}

		$trackingData = $this->_dpdParcelTrack->getStatus($trackingNumber);
		$orderStatus = '';
		if(!empty($trackingData)){
			$orderStatus = $trackingData['statusCode'];

			if($orderStatus){
				$order = $this->_orderFactory->create()->load($args['row']['entity_id']);

				$currentStatus = $order->getStatus();

				if($currentStatus != $orderStatus){
					$order->setStatus($orderStatus);
					$order->addStatusToHistory(false,$trackingData['statusDescription']);
					$order->save();
				}
			}
		}
		$log = $this->_logFactory->create()->load($args['row']['entity_id'],'order_id');

		if(!empty($log->getData())){

			if( $orderStatus== DpdStatus::DPD_DELIVERED){
				$log->delete();
				return $this;
			} else {
				$logData = [
					'updated_at' => date('Y-m-d h:i:s')
				];
				$log->addData($logData);
			}

		} else {
			$logData = [
				'order_id' => $args['row']['entity_id'],
				'increment_id' => $args['row']['increment_id'],
				'tracking_number' => $args['row']['track_number'],
				'status' => $orderStatus,
				'updated_at' => date('Y-m-d h:i:s')
			];
			$log->setData($logData);

		}
		try {
			$log->save();
		} catch (\Exception $e){
			$this->_logger->err($e->getMessage());
		}
	}

}
