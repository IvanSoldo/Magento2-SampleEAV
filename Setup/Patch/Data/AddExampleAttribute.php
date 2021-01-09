<?php


namespace Inchoo\SampleEAV\Setup\Patch\Data;


use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddExampleAttribute implements DataPatchInterface
{

    private $eavSetupFactory;

    private $moduleDataSetup;

    public function __construct(EavSetupFactory $eavSetupFactory, ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }

    public function apply()
    {

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $entityType = ProductAttributeInterface::ENTITY_TYPE_CODE;

        $setId = $eavSetup->getDefaultAttributeSetId($entityType);
        $groupId = $eavSetup->getDefaultAttributeGroupId($entityType, $setId);
        $groupName = $eavSetup->getAttributeGroup($entityType, $setId, $groupId, 'attribute_group_name');

        $eavSetup->addAttribute(ProductAttributeInterface::ENTITY_TYPE_CODE, 'legacy_sku', [
            'label' => 'Legacy SKU',
            'required' => 0,
            'user_defined' => 1,
            'unique' => 1,
            'searchable' => 1,
            'visible_on_front' => 1,
            'visible_in_advanced_search' => 1,
            'is_used_in_grid' => 1,
            'group' => $groupName,
            'sort_order' => 30,
        ]);
    }
}
