<?php
namespace Commercers\DeepTracking\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\Context;

class Config extends AbstractHelper {

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    const SYS_XML_SECTION = 'deeptracking';

    const SYS_XML_DPD_GROUP = 'dpd_group';
    const SYS_XML_DPD_GENERAL_GROUP = 'general';
    const SYS_XML_DPD_SERVICE_CREDENTIALS_GROUP = 'service_credentials';

    const SYS_XML_DHL_GROUP = 'dhl_group';
    const SYS_XML_DHL_GENERAL_GROUP = 'general';
    const SYS_XML_DHL_SERVICE_CREDENTIALS_GROUP = 'service_credentials';

    const SYS_XML_HELLMANN_GROUP = 'hellmann_group';
    const SYS_XML_HELLMANN_GENERAL_GROUP = 'general';
    const SYS_XML_HELLMANN_SERVICE_CREDENTIALS_GROUP = 'service_credentials';

    const SYS_XML_UPS_GROUP = 'ups_group';
    const SYS_XML_UPS_GENERAL_GROUP = 'general';
    const SYS_XML_UPS_SERVICE_CREDENTIALS_GROUP = 'service_credentials';

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        Context $context
    )
    {
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    public function getConfigValue($section, $masterGroup, $group, $field , $storeId = null)
    {
        return $this->_scopeConfig->getValue(
            $section. "/" . $masterGroup . "/" . $group . "/" . $field,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /* DPD SYSTEM CONFIG */
    public function getDpdGroupValue($group, $field, $storeId = null)
    {
        return $this->getConfigValue(static::SYS_XML_SECTION, static::SYS_XML_DPD_GROUP, $group, $field, $storeId);
    }
    public function getDpdGeneralConfigValue($field, $storeId = null)
    {
        return $this->getDpdGroupValue(static::SYS_XML_DPD_GENERAL_GROUP,$field, $storeId);
    }
    public function getDpdServiceCredentialsConfigValue($field, $storeId = null)
    {
        return $this->getDpdGroupValue(static::SYS_XML_DPD_SERVICE_CREDENTIALS_GROUP,$field, $storeId);
    }
    public function isDpdEnabled($storeId = null)
    {
        return $this->getDpdGeneralConfigValue('enabled',$storeId);
    }
    public function getDpdOrderStatuses($storeId = null)
    {
        return $this->getDpdGeneralConfigValue('order_statuses',$storeId);
    }
    public function getDpdDayBack($storeId = null)
    {
        return $this->getDpdGeneralConfigValue('days_back',$storeId);
    }
    public function getDpdCronLimit($storeId = null)
    {
        return $this->getDpdGeneralConfigValue('limit',$storeId);
    }
    public function getDpdCronExpress($storeId = null)
    {
        return $this->getDpdGeneralConfigValue('cron',$storeId);
    }
    public function getDpdCarrierTitle($storeId = null)
    {
        return $this->getDpdGeneralConfigValue('carrier_title',$storeId);
    }
    public function getDpdDelisid($storeId = null){
        return $this->getDpdServiceCredentialsConfigValue('delisid',$storeId);
    }
    public function getDpdPassword($storeId = null){
        return $this->getDpdServiceCredentialsConfigValue('password',$storeId);
    }
    public function getDpdSoapHeaderUrl($storeId = null){
        return $this->getDpdServiceCredentialsConfigValue('soap_header_url',$storeId);
    }
    public function getDpdLogin($storeId = null){
        return $this->getDpdServiceCredentialsConfigValue('login',$storeId);
    }
    public function getDpdParcelLifeCycle($storeId = null){
        return $this->getDpdServiceCredentialsConfigValue('parcel_life_cycle',$storeId);
    }

    /* DHL SYSTEM CONFIG */
    public function getDhlGroupValue($group, $field, $storeId = null)
    {
        return $this->getConfigValue(static::SYS_XML_SECTION, static::SYS_XML_DHL_GROUP, $group, $field, $storeId);
    }
    public function getDhlGeneralConfigValue($field, $storeId = null)
    {
        return $this->getDhlGroupValue(static::SYS_XML_DHL_GENERAL_GROUP,$field, $storeId);
    }
    public function getDhlServiceCredentialsConfigValue($field, $storeId = null)
    {
        return $this->getDhlGroupValue(static::SYS_XML_DHL_SERVICE_CREDENTIALS_GROUP,$field, $storeId);
    }
    public function isDhlEnabled($storeId = null)
    {
        return $this->getDhlGeneralConfigValue('enabled',$storeId);
    }
    public function getDhlOrderStatuses($storeId = null)
    {
        return $this->getDhlGeneralConfigValue('order_statuses',$storeId);
    }
    public function getDhlDayBack($storeId = null)
    {
        return $this->getDhlGeneralConfigValue('days_back',$storeId);
    }
    public function getDhlCronLimit($storeId = null)
    {
        return $this->getDhlGeneralConfigValue('limit',$storeId);
    }
    public function getDhlCronExpress($storeId = null)
    {
        return $this->getDhlGeneralConfigValue('cron',$storeId);
    }
    public function getDhlCarrierTitle($storeId = null)
    {
        return $this->getDhlGeneralConfigValue('carrier_title',$storeId);
    }
    public function getDhlCredentials($storeId = null)
    {
        return $this->getDhlServiceCredentialsConfigValue('credentials',$storeId);
    }
    public function getDhlCigUser($storeId = null)
    {
        return $this->getDhlServiceCredentialsConfigValue('cig_user',$storeId);
    }
    public function getDhlCigPassword($storeId = null)
    {
        return $this->getDhlServiceCredentialsConfigValue('cig_password',$storeId);
    }
    public function getDhlTntUser($storeId = null)
    {
        return $this->getDhlServiceCredentialsConfigValue('tnt_user',$storeId);
    }
    public function getDhlTntPassword($storeId = null)
    {
        return $this->getDhlServiceCredentialsConfigValue('tnt_password',$storeId);
    }

    /* HELLMANN SYSTEM CONFIG */
    public function getHellmannGroupValue($group, $field, $storeId = null)
    {
        return $this->getConfigValue(static::SYS_XML_SECTION, static::SYS_XML_HELLMANN_GROUP, $group, $field, $storeId);
    }
    public function getHellmannGeneralConfigValue($field, $storeId = null)
    {
        return $this->getHellmannGroupValue(static::SYS_XML_HELLMANN_GENERAL_GROUP,$field, $storeId);
    }
    public function getHellmannServiceCredentialsConfigValue($field, $storeId = null)
    {
        return $this->getHellmannGroupValue(static::SYS_XML_HELLMANN_SERVICE_CREDENTIALS_GROUP,$field, $storeId);
    }
    public function isHellmannEnabled($storeId = null)
    {
        return $this->getHellmannGeneralConfigValue('enabled',$storeId);
    }
    public function getHellmannOrderStatuses($storeId = null)
    {
        return $this->getHellmannGeneralConfigValue('order_statuses',$storeId);
    }
    public function getHellmannDayBack($storeId = null)
    {
        return $this->getHellmannGeneralConfigValue('days_back',$storeId);
    }
    public function getHellmannCronLimit($storeId = null)
    {
        return $this->getHellmannGeneralConfigValue('limit',$storeId);
    }
    public function geHellmannCronExpress($storeId = null)
    {
        return $this->getHellmannGeneralConfigValue('cron',$storeId);
    }
    public function getHellmannCarrierTitle($storeId = null)
    {
        return $this->getHellmannGeneralConfigValue('carrier_title',$storeId);
    }
    public function getHellmannApiUrl($storeId = null)
    {
        return $this->getHellmannServiceCredentialsConfigValue('api_url',$storeId);
    }

    /* UPS SYSTEM CONFIG */
    public function getUpsGroupValue($group, $field, $storeId = null)
    {
        return $this->getConfigValue(static::SYS_XML_SECTION, static::SYS_XML_UPS_GROUP, $group, $field, $storeId);
    }
    public function getUpsGeneralConfigValue($field, $storeId = null)
    {
        return $this->getUpsGroupValue(static::SYS_XML_UPS_GENERAL_GROUP,$field, $storeId);
    }
    public function getUpsServiceCredentialsConfigValue($field, $storeId = null)
    {
        return $this->getUpsGroupValue(static::SYS_XML_UPS_SERVICE_CREDENTIALS_GROUP,$field, $storeId);
    }
    public function isUpsEnabled($storeId = null)
    {
        return $this->getUpsGeneralConfigValue('enabled',$storeId);
    }
    public function getUpsOrderStatuses($storeId = null)
    {
        return $this->getUpsGeneralConfigValue('order_statuses',$storeId);
    }
    public function getUpsDayBack($storeId = null)
    {
        return $this->getUpsGeneralConfigValue('days_back',$storeId);
    }
    public function getUpsCronLimit($storeId = null)
    {
        return $this->getUpsGeneralConfigValue('limit',$storeId);
    }
    public function geUpsCronExpress($storeId = null)
    {
        return $this->getUpsGeneralConfigValue('cron',$storeId);
    }
    public function getUpsCarrierTitle($storeId = null)
    {
        return $this->getUpsGeneralConfigValue('carrier_title',$storeId);
    }
    public function getUpsUserId($storeId = null)
    {
        return $this->getUpsServiceCredentialsConfigValue('user_id',$storeId);
    }
    public function getUpsPassword($storeId = null)
    {
        return $this->getUpsServiceCredentialsConfigValue('password',$storeId);
    }
    public function getUpsAccessKey($storeId = null)
    {
        return $this->getUpsServiceCredentialsConfigValue('access_key',$storeId);
    }
    public function getUpsIsSandbox($storeId = null)
    {
        return $this->getUpsServiceCredentialsConfigValue('is_sandbox',$storeId);
    }
}
