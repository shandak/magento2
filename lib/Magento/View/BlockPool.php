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
 * @copyright Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\View;

use Magento\View\Layout;
use Magento\View\Element\BlockInterface;
use Magento\Core\Model\BlockFactory;
use Magento\ObjectManager;

class BlockPool
{
    /**
     * @var \Magento\Core\Model\BlockFactory
     */
    protected $blockFactory;

    /**
     * @var array
     */
    protected $blocks = array();

    /**
     * @param ObjectManager $objectManager
     * @param BlockFactory $blockFactory
     */
    public function __construct(ObjectManager $objectManager, BlockFactory $blockFactory)
    {
        $this->objectManager = $objectManager;
        $this->blockFactory = $blockFactory;
    }

    /**
     * @param string $name
     * @param string $class
     * @param array $arguments [optional]
     * @return BlockPool
     * @throws \InvalidArgumentException
     */
    public function add($name, $class, array $arguments = array())
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(__('Invalid Block class name: ' . $class));
        }

        $block = $this->blockFactory->createBlock($class, $arguments);

        $this->blocks[$name] = $block;

        return $this;
    }

    /**
     * @param string $name
     * @return BlockInterface | null
     */
    public function get($name = null)
    {
        if (!isset($name)) {
            return $this->blocks;
        }

        return isset($this->blocks[$name]) ? $this->blocks[$name] : null;
    }
}
