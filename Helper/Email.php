<?php

namespace Commercers\DeepTracking\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\Escaper;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;

class Email extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_EMAIL_TEMPLATE_SENT_REMINDER = 'deeptracking/email_notification/email_send_reminder';

    protected $inlineTranslation;
    protected $escaper;
    protected $transportBuilder;
    protected $logger;
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context,
        StateInterface $inlineTranslation,
        Escaper $escaper,
        TransportBuilder $transportBuilder
    ) {
        parent::__construct($context);
        $this->_storeManager = $storeManager;
        $this->inlineTranslation = $inlineTranslation;
        $this->escaper = $escaper;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $context->getLogger();
    }

    protected function getConfigValue($path,$storeId)
    {
        return $this->scopeConfig->getValue(
            $path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function sendReminderEmail($arrEmailAddress,$arrOrderId)
    {
        try {
            $this->inlineTranslation->suspend();
            $senderInfo = [
                'email' => $this->getConfigValue('trans_email/ident_general/email',$this->_storeManager->getStore()->getId()),
                'name' => $this->getConfigValue('trans_email/ident_general/name',$this->_storeManager->getStore()->getId())
            ];
            $emailReceivers = $arrEmailAddress;
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($this->getTemplateId(self::XML_PATH_EMAIL_TEMPLATE_SENT_REMINDER))
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'arr_order_id'  => $arrOrderId,
                ])
                ->setFrom($senderInfo)
                ->addTo($emailReceivers)
                ->getTransport();
            $transport->sendMessage();
            $this->inlineTranslation->resume();
        } catch (\Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }
    protected function getTemplateId($xmlPath)
    {
        return $this->getConfigValue($xmlPath,$this->_storeManager->getStore()->getId());
    }
}