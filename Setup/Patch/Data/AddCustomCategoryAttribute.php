<?php


namespace Inchoo\SampleEAV\Setup\Patch\Data;


use Magento\Catalog\Api\Data\CategoryAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddCustomCategoryAttribute implements DataPatchInterface
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

        $entityType = CategoryAttributeInterface::ENTITY_TYPE_CODE;

        $eavSetup->addAttribute($entityType, 'external_id', [
            'label' => 'External ID',
            'user_defined' => 1,
            'unique' => 1,
        ]);

        $setId = $eavSetup->getDefaultAttributeSetId($entityType);
        $groupId = $eavSetup->getDefaultAttributeGroupId($entityType, $setId);
        $eavSetup->addAttributeToSet($entityType, $setId, $groupId, 'external_id');

    }
}
