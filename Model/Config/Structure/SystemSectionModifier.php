<?php

namespace Commercers\DeepTracking\Model\Config\Structure;

class SystemSectionModifier
{
    /**
     * Identifiers of special payment method configuration groups
     *
     * @var array
     */
    private static $specialGroups = [
        'dpd_group',
        'dhl_group',
        'hellmann_group',
        'ups_group'
    ];

    /**
     * Returns changed section structure.
     * To move config to specific configuration group specify "displayIn"
     * attribute in system.xml file equals to any id of predefined special group.
     *
     * @param array $initialStructure
     * @return array
     */
    public function modify(array $initialStructure)
    {
        $changedStructure = array_fill_keys(self::$specialGroups, []);

        foreach ($initialStructure as $childSection => $childData) {
            if (in_array($childSection, self::$specialGroups)) {
                if (isset($changedStructure[$childSection]['children'])) {
                    $children = $changedStructure[$childSection]['children'];
                    if (isset($childData['children'])) {
                        $children += $childData['children'];
                    }
                    $childData['children'] = $children;
                    unset($children);
                }
                $changedStructure[$childSection] = $childData;
            } else {
                $moveInstructions = $this->getMoveInstructions($childSection, $childData);
                if (!empty($moveInstructions)) {
                    foreach ($moveInstructions as $moveInstruction) {
                        unset($childData['children'][$moveInstruction['section']]);
                        unset($moveInstruction['data']['displayIn']);
                        $changedStructure
                        [$moveInstruction['parent']]
                        ['children']
                        [$moveInstruction['section']] = $moveInstruction['data'];
                    }
                }
            }
        }

        return $changedStructure;
    }

    /**
     * Recursively collect groups that should be moved to special section
     *
     * @param string $section
     * @param array $data
     * @return array
     */
    private function getMoveInstructions($section, $data)
    {
        $moved = [];

        if (array_key_exists('children', $data)) {
            foreach ($data['children'] as $childSection => $childData) {
                $movedChildren = $this->getMoveInstructions($childSection, $childData);
                if (isset($movedChildren[$childSection])) {
                    unset($data['children'][$childSection]);
                }
                $moved[] = $movedChildren;
            }
        }

        if (isset($data['displayIn']) && in_array($data['displayIn'], self::$specialGroups)) {
            $moved[] = [
                $section => [
                    'parent' => $data['displayIn'],
                    'section' => $section,
                    'data' => $data
                ]
            ];
        }

        return array_merge([], ...$moved);
    }
}
