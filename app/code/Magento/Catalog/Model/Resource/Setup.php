<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Magento_Catalog
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * Catalog entity setup
 *
 * @category    Magento
 * @package     Magento_Catalog
 * @author      Magento Core Team <core@magentocommerce.com>
 */
namespace Magento\Catalog\Model\Resource;

class Setup extends \Magento\Eav\Model\Entity\Setup
{
    /**
     * Category model factory
     *
     * @var \Magento\Catalog\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Indexer model factory
     *
     * @var \Magento\Index\Model\IndexerFactory
     */
    protected $_indexerFactory;

    /**
     * Migration setup model factory
     *
     * @var \Magento\Core\Model\Resource\Setup\MigrationFactory
     */
    protected $_resourceMigrationFactory;

    /**
     * Attribute resource model factory
     *
     * @var \Magento\Catalog\Model\Resource\Eav\AttributeFactory
     */
    protected $_eavAttributeResourceFactory;

    /**
     * Construct
     * 
     * @param \Magento\Core\Model\Resource\Setup\Context $context
     * @param \Magento\App\CacheInterface $cache
     * @param \Magento\Eav\Model\Resource\Entity\Attribute\Group\CollectionFactory $attrGrCollFactory
     * @param string $resourceName
     * @param \Magento\Catalog\Model\CategoryFactory $categoryFactory
     * @param \Magento\Index\Model\IndexerFactory $indexerFactory
     * @param \Magento\Core\Model\Resource\Setup\MigrationFactory $resourceMigrationFactory
     * @param \Magento\Catalog\Model\Resource\Eav\AttributeFactory $eavAttributeResourceFactory
     * @param string $moduleName
     * @param string $connectionName
     */
    public function __construct(
        \Magento\Core\Model\Resource\Setup\Context $context,
        \Magento\App\CacheInterface $cache,
        \Magento\Eav\Model\Resource\Entity\Attribute\Group\CollectionFactory $attrGrCollFactory,
        $resourceName,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Index\Model\IndexerFactory $indexerFactory,
        \Magento\Core\Model\Resource\Setup\MigrationFactory $resourceMigrationFactory,
        \Magento\Catalog\Model\Resource\Eav\AttributeFactory $eavAttributeResourceFactory,
        $moduleName = 'Magento_Catalog',
        $connectionName = ''
    ) {
        $this->_categoryFactory = $categoryFactory;
        $this->_indexerFactory = $indexerFactory;
        $this->_resourceMigrationFactory = $resourceMigrationFactory;
        $this->_eavAttributeResourceFactory = $eavAttributeResourceFactory;
        parent::__construct($context, $cache, $attrGrCollFactory, $resourceName, $moduleName, $connectionName);
    }

    /**
     * Creates category model
     *
     * @param array $data
     * @return \Magento\Catalog\Model\Category
     */
    public function createCategory($data = array())
    {
        return $this->_categoryFactory->create($data);
    }

    /**
     * Creates indexer model
     *
     * @param array $data
     * @return \Magento\Index\Model\Indexer
     */
    public function createIndexer($data = array())
    {
        return $this->_indexerFactory
            ->create($data);
    }

    /**
     * Creates setup migration model
     *
     * @param array $data
     * @return \Magento\Core\Model\Resource\Setup\Migration
     */
    public function createSetupMigration($data = array())
    {
        return $this->_resourceMigrationFactory->create($data);
    }

    /**
     * Creates eav attribute resource model
     *
     * @param array $data
     * @return \Magento\Catalog\Model\Resource\Eav\Attribute
     */
    public function createEavAttributeResource($data = array())
    {
        return $this->_eavAttributeResourceFactory->create($data);
    }

    /**
     * Prepare catalog attribute values to save
     *
     * @param array $attr
     * @return array
     */
    protected function _prepareValues($attr)
    {
        $data = parent::_prepareValues($attr);
        $data = array_merge($data, array(
            'frontend_input_renderer'       => $this->_getValue($attr, 'input_renderer'),
            'is_global'                     => $this->_getValue(
                $attr,
                'global',
                \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_GLOBAL
            ),
            'is_visible'                    => $this->_getValue($attr, 'visible', 1),
            'is_searchable'                 => $this->_getValue($attr, 'searchable', 0),
            'is_filterable'                 => $this->_getValue($attr, 'filterable', 0),
            'is_comparable'                 => $this->_getValue($attr, 'comparable', 0),
            'is_visible_on_front'           => $this->_getValue($attr, 'visible_on_front', 0),
            'is_wysiwyg_enabled'            => $this->_getValue($attr, 'wysiwyg_enabled', 0),
            'is_html_allowed_on_front'      => $this->_getValue($attr, 'is_html_allowed_on_front', 0),
            'is_visible_in_advanced_search' => $this->_getValue($attr, 'visible_in_advanced_search', 0),
            'is_filterable_in_search'       => $this->_getValue($attr, 'filterable_in_search', 0),
            'used_in_product_listing'       => $this->_getValue($attr, 'used_in_product_listing', 0),
            'used_for_sort_by'              => $this->_getValue($attr, 'used_for_sort_by', 0),
            'apply_to'                      => $this->_getValue($attr, 'apply_to'),
            'position'                      => $this->_getValue($attr, 'position', 0),
            'is_configurable'               => $this->_getValue($attr, 'is_configurable', 1),
            'is_used_for_promo_rules'       => $this->_getValue($attr, 'used_for_promo_rules', 0)
        ));
        return $data;
    }

    /**
     * Default entites and attributes
     *
     * @return array
     */
    public function getDefaultEntities()
    {
        return array(
            'catalog_category'               => array(
                'entity_model'                   => 'Magento\Catalog\Model\Resource\Category',
                'attribute_model'                => 'Magento\Catalog\Model\Resource\Eav\Attribute',
                'table'                          => 'catalog_category_entity',
                'additional_attribute_table'     => 'catalog_eav_attribute',
                'entity_attribute_collection'    => 'Magento\Catalog\Model\Resource\Category\Attribute\Collection',
                'default_group'                  => 'General Information',
                'attributes'                     => array(
                    'name'               => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Name',
                        'input'                      => 'text',
                        'sort_order'                 => 1,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                    'is_active'          => array(
                        'type'                       => 'int',
                        'label'                      => 'Is Active',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                        'sort_order'                 => 2,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                    'url_key'            => array(
                        'type'                       => 'varchar',
                        'label'                      => 'URL Key',
                        'input'                      => 'text',
                        'backend'                    => 'Magento\Catalog\Model\Category\Attribute\Backend\Urlkey',
                        'required'                   => false,
                        'sort_order'                 => 3,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                    'description'        => array(
                        'type'                       => 'text',
                        'label'                      => 'Description',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'sort_order'                 => 4,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'wysiwyg_enabled'            => true,
                        'is_html_allowed_on_front'   => true,
                        'group'                      => 'General Information',
                    ),
                    'image'              => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Image',
                        'input'                      => 'image',
                        'backend'                    => 'Magento\Catalog\Model\Category\Attribute\Backend\Image',
                        'required'                   => false,
                        'sort_order'                 => 5,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                    'meta_title'         => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Page Title',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 6,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                    'meta_keywords'      => array(
                        'type'                       => 'text',
                        'label'                      => 'Meta Keywords',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'sort_order'                 => 7,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                    'meta_description'   => array(
                        'type'                       => 'text',
                        'label'                      => 'Meta Description',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'sort_order'                 => 8,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                    'display_mode'       => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Display Mode',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Catalog\Model\Category\Attribute\Source\Mode',
                        'required'                   => false,
                        'sort_order'                 => 10,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Display Settings',
                    ),
                    'landing_page'       => array(
                        'type'                       => 'int',
                        'label'                      => 'CMS Block',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Catalog\Model\Category\Attribute\Source\Page',
                        'required'                   => false,
                        'sort_order'                 => 20,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Display Settings',
                    ),
                    'is_anchor'          => array(
                        'type'                       => 'int',
                        'label'                      => 'Is Anchor',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                        'required'                   => false,
                        'sort_order'                 => 30,
                        'group'                      => 'Display Settings',
                    ),
                    'path'               => array(
                        'type'                       => 'static',
                        'label'                      => 'Path',
                        'required'                   => false,
                        'sort_order'                 => 12,
                        'visible'                    => false,
                        'group'                      => 'General Information',
                    ),
                    'position'           => array(
                        'type'                       => 'static',
                        'label'                      => 'Position',
                        'required'                   => false,
                        'sort_order'                 => 13,
                        'visible'                    => false,
                        'group'                      => 'General Information',
                    ),
                    'all_children'       => array(
                        'type'                       => 'text',
                        'required'                   => false,
                        'sort_order'                 => 14,
                        'visible'                    => false,
                        'group'                      => 'General Information',
                    ),
                    'path_in_store'      => array(
                        'type'                       => 'text',
                        'required'                   => false,
                        'sort_order'                 => 15,
                        'visible'                    => false,
                        'group'                      => 'General Information',
                    ),
                    'children'           => array(
                        'type'                       => 'text',
                        'required'                   => false,
                        'sort_order'                 => 16,
                        'visible'                    => false,
                        'group'                      => 'General Information',
                    ),
                    'url_path'           => array(
                        'type'                       => 'varchar',
                        'required'                   => false,
                        'sort_order'                 => 17,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'visible'                    => false,
                        'group'                      => 'General Information',
                    ),
                    'custom_design'      => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Custom Design',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Core\Model\Theme\Source\Theme',
                        'required'                   => false,
                        'sort_order'                 => 10,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Custom Design',
                    ),
                    'custom_design_from' => array(
                        'type'                       => 'datetime',
                        'label'                      => 'Active From',
                        'input'                      => 'date',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                        'required'                   => false,
                        'sort_order'                 => 30,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Custom Design',
                    ),
                    'custom_design_to'   => array(
                        'type'                       => 'datetime',
                        'label'                      => 'Active To',
                        'input'                      => 'date',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                        'required'                   => false,
                        'sort_order'                 => 40,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Custom Design',
                    ),
                    'page_layout'        => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Page Layout',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Catalog\Model\Category\Attribute\Source\Layout',
                        'required'                   => false,
                        'sort_order'                 => 50,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Custom Design',
                    ),
                    'custom_layout_update' => array(
                        'type'                       => 'text',
                        'label'                      => 'Custom Layout Update',
                        'input'                      => 'textarea',
                        'backend'                    => 'Magento\Catalog\Model\Attribute\Backend\Customlayoutupdate',
                        'required'                   => false,
                        'sort_order'                 => 60,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Custom Design',
                    ),
                    'level'              => array(
                        'type'                       => 'static',
                        'label'                      => 'Level',
                        'required'                   => false,
                        'sort_order'                 => 24,
                        'visible'                    => false,
                        'group'                      => 'General Information',
                    ),
                    'children_count'     => array(
                        'type'                       => 'static',
                        'label'                      => 'Children Count',
                        'required'                   => false,
                        'sort_order'                 => 25,
                        'visible'                    => false,
                        'group'                      => 'General Information',
                    ),
                    'available_sort_by'  => array(
                        'type'                       => 'text',
                        'label'                      => 'Available Product Listing Sort By',
                        'input'                      => 'multiselect',
                        'source'                     => 'Magento\Catalog\Model\Category\Attribute\Source\Sortby',
                        'backend'                    => 'Magento\Catalog\Model\Category\Attribute\Backend\Sortby',
                        'sort_order'                 => 40,
                        'input_renderer'             =>
                            'Magento\Catalog\Block\Adminhtml\Category\Helper\Sortby\Available',
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Display Settings',
                    ),
                    'default_sort_by'    => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Default Product Listing Sort By',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Catalog\Model\Category\Attribute\Source\Sortby',
                        'backend'                    => 'Magento\Catalog\Model\Category\Attribute\Backend\Sortby',
                        'sort_order'                 => 50,
                        'input_renderer'             =>
                            'Magento\Catalog\Block\Adminhtml\Category\Helper\Sortby\DefaultSortby',
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Display Settings',
                    ),
                    'include_in_menu'    => array(
                        'type'                       => 'int',
                        'label'                      => 'Include in Navigation Menu',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                        'default'                    => '1',
                        'sort_order'                 => 10,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'General Information',
                    ),
                    'custom_use_parent_settings' => array(
                        'type'                       => 'int',
                        'label'                      => 'Use Parent Category Settings',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                        'required'                   => false,
                        'sort_order'                 => 5,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Custom Design',
                    ),
                    'custom_apply_to_products' => array(
                        'type'                       => 'int',
                        'label'                      => 'Apply To Products',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                        'required'                   => false,
                        'sort_order'                 => 6,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Custom Design',
                    ),
                    'filter_price_range' => array(
                        'type'                       => 'decimal',
                        'label'                      => 'Layered Navigation Price Step',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 51,
                        'input_renderer'             => 'Magento\Catalog\Block\Adminhtml\Category\Helper\Pricestep',
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Display Settings',
                    ),
                )
            ),
            'catalog_product'                => array(
                'entity_model'                   => 'Magento\Catalog\Model\Resource\Product',
                'attribute_model'                => 'Magento\Catalog\Model\Resource\Eav\Attribute',
                'table'                          => 'catalog_product_entity',
                'additional_attribute_table'     => 'catalog_eav_attribute',
                'entity_attribute_collection'    => 'Magento\Catalog\Model\Resource\Product\Attribute\Collection',
                'attributes'                     => array(
                    'name'               => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Name',
                        'input'                      => 'text',
                        'sort_order'                 => 1,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'searchable'                 => true,
                        'visible_in_advanced_search' => true,
                        'used_in_product_listing'    => true,
                        'used_for_sort_by'           => true,
                    ),
                    'sku'                => array(
                        'type'                       => 'static',
                        'label'                      => 'SKU',
                        'input'                      => 'text',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Sku',
                        'unique'                     => true,
                        'sort_order'                 => 2,
                        'searchable'                 => true,
                        'comparable'                 => true,
                        'visible_in_advanced_search' => true,
                    ),
                    'description'        => array(
                        'type'                       => 'text',
                        'label'                      => 'Description',
                        'input'                      => 'textarea',
                        'sort_order'                 => 3,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'searchable'                 => true,
                        'comparable'                 => true,
                        'wysiwyg_enabled'            => true,
                        'is_html_allowed_on_front'   => true,
                        'visible_in_advanced_search' => true,
                    ),
                    'short_description'  => array(
                        'type'                       => 'text',
                        'label'                      => 'Short Description',
                        'input'                      => 'textarea',
                        'sort_order'                 => 4,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'searchable'                 => true,
                        'comparable'                 => true,
                        'wysiwyg_enabled'            => true,
                        'is_html_allowed_on_front'   => true,
                        'visible_in_advanced_search' => true,
                        'used_in_product_listing'    => true,
                    ),
                    'price'              => array(
                        'type'                       => 'decimal',
                        'label'                      => 'Price',
                        'input'                      => 'price',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Price',
                        'sort_order'                 => 1,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'searchable'                 => true,
                        'filterable'                 => true,
                        'visible_in_advanced_search' => true,
                        'used_in_product_listing'    => true,
                        'used_for_sort_by'           => true,
                        'apply_to'                   => 'simple,configurable,virtual',
                        'group'                      => 'Prices',
                    ),
                    'special_price'      => array(
                        'type'                       => 'decimal',
                        'label'                      => 'Special Price',
                        'input'                      => 'price',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Price',
                        'required'                   => false,
                        'sort_order'                 => 2,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'used_in_product_listing'    => true,
                        'apply_to'                   => 'simple,configurable,virtual',
                        'group'                      => 'Prices',
                    ),
                    'special_from_date'  => array(
                        'type'                       => 'datetime',
                        'label'                      => 'Special Price From Date',
                        'input'                      => 'date',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Startdate',
                        'required'                   => false,
                        'sort_order'                 => 3,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'used_in_product_listing'    => true,
                        'apply_to'                   => 'simple,configurable,virtual',
                        'group'                      => 'Prices',
                    ),
                    'special_to_date'    => array(
                        'type'                       => 'datetime',
                        'label'                      => 'Special Price To Date',
                        'input'                      => 'date',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                        'required'                   => false,
                        'sort_order'                 => 4,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'used_in_product_listing'    => true,
                        'apply_to'                   => 'simple,configurable,virtual',
                        'group'                      => 'Prices',
                    ),
                    'cost'               => array(
                        'type'                       => 'decimal',
                        'label'                      => 'Cost',
                        'input'                      => 'price',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Price',
                        'required'                   => false,
                        'user_defined'               => true,
                        'sort_order'                 => 5,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'apply_to'                   => 'simple,virtual',
                        'group'                      => 'Prices',
                    ),
                    'weight'             => array(
                        'type'                       => 'decimal',
                        'label'                      => 'Weight',
                        'input'                      => 'weight',
                        'sort_order'                 => 5,
                        'apply_to'                   => \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                    ),
                    'manufacturer'       => array(
                        'type'                       => 'int',
                        'label'                      => 'Manufacturer',
                        'input'                      => 'select',
                        'required'                   => false,
                        'user_defined'               => true,
                        'searchable'                 => true,
                        'filterable'                 => true,
                        'comparable'                 => true,
                        'visible_in_advanced_search' => true,
                        'apply_to'                   => \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                    ),
                    'meta_title'         => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Meta Title',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 1,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Meta Information',
                    ),
                    'meta_keyword'       => array(
                        'type'                       => 'text',
                        'label'                      => 'Meta Keywords',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'sort_order'                 => 2,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Meta Information',
                    ),
                    'meta_description'   => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Meta Description',
                        'input'                      => 'textarea',
                        'required'                   => false,
                        'note'                       => 'Maximum 255 chars',
                        'class'                      => 'validate-length maximum-length-255',
                        'sort_order'                 => 3,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Meta Information',
                    ),
                    'image'              => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Base Image',
                        'input'                      => 'media_image',
                        'frontend'                   => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                        'required'                   => false,
                        'sort_order'                 => 1,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Images',
                    ),
                    'small_image'        => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Small Image',
                        'input'                      => 'media_image',
                        'frontend'                   => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                        'required'                   => false,
                        'sort_order'                 => 2,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'used_in_product_listing'    => true,
                        'group'                      => 'Images',
                    ),
                    'thumbnail'          => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Thumbnail',
                        'input'                      => 'media_image',
                        'frontend'                   => 'Magento\Catalog\Model\Product\Attribute\Frontend\Image',
                        'required'                   => false,
                        'sort_order'                 => 3,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'used_in_product_listing'    => true,
                        'group'                      => 'Images',
                    ),
                    'media_gallery'      => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Media Gallery',
                        'input'                      => 'gallery',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Media',
                        'required'                   => false,
                        'sort_order'                 => 4,
                        'group'                      => 'Images',
                    ),
                    'old_id'             => array(
                        'type'                       => 'int',
                        'required'                   => false,
                        'sort_order'                 => 6,
                        'visible'                    => false,
                    ),
                    'group_price'         => array(
                        'type'                       => 'decimal',
                        'label'                      => 'Group Price',
                        'input'                      => 'text',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Groupprice',
                        'required'                   => false,
                        'sort_order'                 => 6,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'apply_to'                   => 'simple,configurable,virtual',
                        'group'                      => 'Prices',
                    ),
                    'tier_price'         => array(
                        'type'                       => 'decimal',
                        'label'                      => 'Tier Price',
                        'input'                      => 'text',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Tierprice',
                        'required'                   => false,
                        'sort_order'                 => 6,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'apply_to'                   => 'simple,configurable,virtual',
                        'group'                      => 'Prices',
                    ),
                    'color'              => array(
                        'type'                       => 'int',
                        'label'                      => 'Color',
                        'input'                      => 'select',
                        'required'                   => false,
                        'user_defined'               => true,
                        'searchable'                 => true,
                        'filterable'                 => true,
                        'comparable'                 => true,
                        'visible_in_advanced_search' => true,
                        'apply_to'                   => \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE,
                    ),
                    'news_from_date'     => array(
                        'type'                       => 'datetime',
                        'label'                      => 'Set Product as New from Date',
                        'input'                      => 'date',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                        'required'                   => false,
                        'sort_order'                 => 7,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'used_in_product_listing'    => true,
                    ),
                    'news_to_date'       => array(
                        'type'                       => 'datetime',
                        'label'                      => 'Set Product as New to Date',
                        'input'                      => 'date',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                        'required'                   => false,
                        'sort_order'                 => 8,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'used_in_product_listing'    => true,
                    ),
                    'gallery'            => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Image Gallery',
                        'input'                      => 'gallery',
                        'required'                   => false,
                        'sort_order'                 => 5,
                        'group'                      => 'Images',
                    ),
                    'status'             => array(
                        'type'                       => 'int',
                        'label'                      => 'Status',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Catalog\Model\Product\Status',
                        'sort_order'                 => 9,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_WEBSITE,
                        'searchable'                 => true,
                        'used_in_product_listing'    => true,
                    ),
                    'url_key'            => array(
                        'type'                       => 'varchar',
                        'label'                      => 'URL Key',
                        'input'                      => 'text',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Urlkey',
                        'required'                   => false,
                        'sort_order'                 => 10,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'used_in_product_listing'    => true,
                    ),
                    'url_path'           => array(
                        'type'                       => 'varchar',
                        'required'                   => false,
                        'sort_order'                 => 11,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'visible'                    => false,
                    ),
                    'minimal_price'      => array(
                        'type'                       => 'decimal',
                        'label'                      => 'Minimal Price',
                        'input'                      => 'price',
                        'required'                   => false,
                        'sort_order'                 => 7,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'visible'                    => false,
                        'apply_to'                   => 'simple,configurable,virtual',
                        'group'                      => 'Prices',
                    ),
                    'is_recurring'       => array(
                        'type'                       => 'int',
                        'label'                      => 'Enable Recurring Profile',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                        'required'                   => false,
                        'note'                       =>
                            'Products with recurring profile participate in catalog as nominal items.',
                        'sort_order'                 => 1,
                        'apply_to'                   => 'simple,virtual',
                        'is_configurable'            => false,
                        'group'                      => 'Recurring Profile',
                    ),
                    'recurring_profile'  => array(
                        'type'                       => 'text',
                        'label'                      => 'Recurring Payment Profile',
                        'input'                      => 'text',
                        'backend'                    => 'Magento\Catalog\Model\Product\Attribute\Backend\Recurring',
                        'required'                   => false,
                        'sort_order'                 => 2,
                        'apply_to'                   => 'simple,virtual',
                        'is_configurable'            => false,
                        'group'                      => 'Recurring Profile',
                    ),
                    'visibility'         => array(
                        'type'                       => 'int',
                        'label'                      => 'Visibility',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Catalog\Model\Product\Visibility',
                        'default'                    => '4',
                        'sort_order'                 => 12,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                    ),
                    'custom_design'      => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Custom Design',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Core\Model\Theme\Source\Theme',
                        'required'                   => false,
                        'sort_order'                 => 1,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Design',
                    ),
                    'custom_design_from' => array(
                        'type'                       => 'datetime',
                        'label'                      => 'Active From',
                        'input'                      => 'date',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                        'required'                   => false,
                        'sort_order'                 => 2,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Design',
                    ),
                    'custom_design_to'   => array(
                        'type'                       => 'datetime',
                        'label'                      => 'Active To',
                        'input'                      => 'date',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Datetime',
                        'required'                   => false,
                        'sort_order'                 => 3,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Design',
                    ),
                    'custom_layout_update' => array(
                        'type'                       => 'text',
                        'label'                      => 'Custom Layout Update',
                        'input'                      => 'textarea',
                        'backend'                    => 'Magento\Catalog\Model\Attribute\Backend\Customlayoutupdate',
                        'required'                   => false,
                        'sort_order'                 => 4,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Design',
                    ),
                    'page_layout'        => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Page Layout',
                        'input'                      => 'select',
                        'source'                     => 'Magento\Catalog\Model\Product\Attribute\Source\Layout',
                        'required'                   => false,
                        'sort_order'                 => 5,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Design',
                    ),
                    'category_ids'       => array(
                        'type'                       => 'static',
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_GLOBAL,
                        'required'                   => false,
                        'sort_order'                 => 13,
                        'visible'                    => false,
                    ),
                    'options_container'  => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Display Product Options In',
                        'input'                      => 'select',
                        'source'                     =>
                            'Magento\Catalog\Model\Entity\Product\Attribute\Design\Options\Container',
                        'required'                   => false,
                        'default'                    => 'container2',
                        'sort_order'                 => 6,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'group'                      => 'Design',
                    ),
                    'required_options'   => array(
                        'type'                       => 'static',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 14,
                        'visible'                    => false,
                        'used_in_product_listing'    => true,
                    ),
                    'has_options'        => array(
                        'type'                       => 'static',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 15,
                        'visible'                    => false,
                    ),
                    'image_label'        => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Image Label',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 16,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'visible'                    => false,
                        'used_in_product_listing'    => true,
                        'is_configurable'            => false,
                    ),
                    'small_image_label'  => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Small Image Label',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 17,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'visible'                    => false,
                        'used_in_product_listing'    => true,
                        'is_configurable'            => false,
                    ),
                    'thumbnail_label'    => array(
                        'type'                       => 'varchar',
                        'label'                      => 'Thumbnail Label',
                        'input'                      => 'text',
                        'required'                   => false,
                        'sort_order'                 => 18,
                        'global'                     => \Magento\Catalog\Model\Resource\Eav\Attribute::SCOPE_STORE,
                        'visible'                    => false,
                        'used_in_product_listing'    => true,
                        'is_configurable'            => false,
                    ),
                    'created_at'         => array(
                        'type'                       => 'static',
                        'input'                      => 'text',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Time\Created',
                        'sort_order'                 => 19,
                        'visible'                    => false,
                    ),
                    'updated_at'         => array(
                        'type'                       => 'static',
                        'input'                      => 'text',
                        'backend'                    => 'Magento\Eav\Model\Entity\Attribute\Backend\Time\Updated',
                        'sort_order'                 => 20,
                        'visible'                    => false,
                    ),
                )
            )
        );
    }

    /**
     * Returns category entity row by category id
     *
     * @param int $entityId
     * @return array
     */
    protected function _getCategoryEntityRow($entityId)
    {
        $select = $this->getConnection()->select();

        $select->from($this->getTable('catalog_category_entity'));
        $select->where('entity_id = :entity_id');

        return $this->getConnection()->fetchRow($select, array('entity_id' => $entityId));
    }

    /**
     * Returns category path as array
     *
     * @param array $category
     * @param array $path
     * @return string
     */
    protected function _getCategoryPath($category, $path = array())
    {
        $path[] = $category['entity_id'];

        if ($category['parent_id'] != 0) {
            $parentCategory = $this->_getCategoryEntityRow($category['parent_id']);
            if ($parentCategory) {
                $path = $this->_getCategoryPath($parentCategory, $path);
            }
        }

        return $path;
    }
}
