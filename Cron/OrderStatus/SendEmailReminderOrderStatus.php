<?php
namespace Commercers\DeepTracking\Cron\OrderStatus;

class SendEmailReminderOrderStatus
{
    const XML_DEEPTRACKING_ENABLED = 'deeptracking/email_notification/enabled';
    const XML_DEEPTRACKING_EMAIL_ADDRESSES = 'deeptracking/email_notification/email_addresses';
    const XML_DEEPTRACKING_SHIPPING_STATUS = 'deeptracking/email_notification/shipping_status';
    const XML_DEEPTRACKING_DELAY_IN_DAYS = 'deeptracking/email_notification/delay_in_days';

    protected $_scopeConfig;
    protected $_orderCollectionFactory;
    protected $helperEmailFactory;
    public function __construct(
        \Commercers\DeepTracking\Helper\EmailFactory $helperEmailFactory,
        \Magento\Sales\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\View\Result\PageFactory $pageFactory
    )
    {
        $this->helperEmailFactory = $helperEmailFactory;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_scopeConfig = $scopeConfig;
        $this->_pageFactory = $pageFactory;
    }

    public function execute()
    {
        $emailAddress = $this->getScopeConfigValue(self::XML_DEEPTRACKING_EMAIL_ADDRESSES);
        $arrEmailAddress = explode(',', preg_replace('/\s+/', '', $emailAddress));
        if (!$this->getScopeConfigValue(self::XML_DEEPTRACKING_ENABLED)){
            return;
        }
        $orderCollection = $this->getOrderCollection()->getData();
        $arrOrderId = '';
        try {
            if(!$orderCollection){
                return;
            }
            foreach ($orderCollection as $order){
                $arrOrderId .= $order['entity_id'] . ', ';
            }
            $helperEmail = $this->helperEmailFactory->create();
            $helperEmail->sendReminderEmail($arrEmailAddress,$arrOrderId);
        } catch (Exception $ex) {
            echo $ex->getMessage();exit;
        }
        return;
    }
    public function getOrderCollection()
    {
        $delayInDays = intval($this->getScopeConfigValue(self::XML_DEEPTRACKING_DELAY_IN_DAYS))*86400;
        $shippingStatus = $this->getScopeConfigValue(self::XML_DEEPTRACKING_SHIPPING_STATUS);
        $arrShippingStatus = explode(',',$shippingStatus);
        $minusDays = time() - $delayInDays;
        $collection = $this->_orderCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('updated_at', array('gteq' => date("Y/m/d H:i:s",$minusDays)))
            ->addAttributeToFilter('status', array('in' => array($arrShippingStatus))); //Add condition if you wish
        return $collection;
    }

    public function getScopeConfigValue($value){
        return $this->_scopeConfig->getValue($value);
    }
}
