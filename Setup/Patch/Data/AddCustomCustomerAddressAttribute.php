<?php


namespace Inchoo\SampleEAV\Setup\Patch\Data;


use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Eav\Model\Config as EavConfig;

class AddCustomCustomerAddressAttribute implements DataPatchInterface
{

    private $eavSetupFactory;

    private $moduleDataSetup;

    private $eavConfig;

    public function __construct(EavSetupFactory $eavSetupFactory, ModuleDataSetupInterface $moduleDataSetup, EavConfig $eavConfig)
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavConfig = $eavConfig;
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

        $attributeCode = 'is_home_address';

        $eavSetup->addAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, $attributeCode, [
            'type' => 'int',
            'input' => 'boolean',
            'label' => 'Is home address?',
            'required' => 0,
            'user_defined' => 1,
            'default' => 0,
            'system' => 0,
            'position' => 50,
        ]);

        $eavSetup->addAttributeToSet(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            AddressMetadataInterface::ATTRIBUTE_SET_ID_ADDRESS,
            null,
            $attributeCode
        );

        $attribute = $this->eavConfig->getAttribute(AddressMetadataInterface::ENTITY_TYPE_ADDRESS, $attributeCode);
        $attribute->setData('used_in_forms', [
            'adminhtml_customer_address',
            'customer_address_edit',
            'customer_register_address'
        ]);
        $attribute->getResource()->save($attribute);
    }
}
