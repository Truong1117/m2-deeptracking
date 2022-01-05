<?php

namespace Commercers\DeepTracking\Model\Config;

use Magento\Config\Model\Config\ScopeDefiner;
use Magento\Config\Model\Config\Structure;
use Magento\Config\Model\Config\Structure\Element\Section;
use Magento\Framework\App\ObjectManager;
use Commercers\DeepTracking\Model\Config\Structure\SystemSectionModifier;

class StructurePlugin
{
    /**
     * @var ScopeDefiner
     */
    private $scopeDefiner;

    /**
     * @var SystemSectionModifier
     */
    private $systemSectionModifier;

    /**
     * @param ScopeDefiner $scopeDefiner
     * @param SystemSectionModifier|null $systemSectionModifier
     */
    public function __construct(
        ScopeDefiner $scopeDefiner,
        SystemSectionModifier $systemSectionModifier = null
    ) {
        $this->scopeDefiner = $scopeDefiner;
        $this->systemSectionModifier = $systemSectionModifier
                                      ?: ObjectManager::getInstance()->get(SystemSectionModifier::class);
    }

    public function aroundGetElementByPathParts(Structure $subject, \Closure $proceed, array $pathParts)
    {
        $isSectionChanged = $pathParts[0] == 'deeptracking';

        $result = $proceed($pathParts);

        if ($isSectionChanged && $result) {
            if ($result instanceof Section) {
                $this->restructureSystemConfig($result);
                $result->setData(
                    array_merge(
                        $result->getData(),
                        ['showInDefault' => true, 'showInWebsite' => true, 'showInStore' => true]
                    ),
                    $this->scopeDefiner->getScope()
                );
            }
        }

        return $result;
    }

    private function restructureSystemConfig(Section $result)
    {
        $sectionData = $result->getData();
        $sectionInitialStructure = isset($sectionData['children']) ? $sectionData['children'] : [];
        $sectionChangedStructure = $this->systemSectionModifier->modify($sectionInitialStructure);
        $sectionData['children'] = $sectionChangedStructure;
        $result->setData($sectionData, $this->scopeDefiner->getScope());
    }
}
