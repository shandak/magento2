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
 * @package     Magento_Core
 * @subpackage  integration_tests
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Magento\Core\Helper;

class DataTest extends \PHPUnit_Framework_TestCase
{
    const DATE_TIMEZONE = 'America/Los_Angeles'; // hardcoded in the installation

    const DATE_FORMAT_SHORT_ISO = 'M/d/yy'; // en_US
    const DATE_FORMAT_SHORT = 'n/j/y';

    const TIME_FORMAT_SHORT_ISO = 'h:mm a'; // en_US
    const TIME_FORMAT_SHORT = 'g:i A'; // // but maybe "a"

    const DATETIME_FORMAT_SHORT_ISO = 'M/d/yy h:mm a';
    const DATETIME_FORMAT_SHORT = 'n/j/y g:i A';

    /**
     * @var \Magento\Core\Helper\Data
     */
    protected $_helper = null;

    /**
     * @var \DateTime
     */
    protected $_dateTime = null;

    protected function setUp()
    {
        $this->_helper = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Core\Helper\Data');
        $this->_dateTime = new \DateTime;
        $this->_dateTime->setTimezone(new \DateTimeZone(self::DATE_TIMEZONE));
    }

    public function testCurrency()
    {
        $price = 10.00;
        $priceHtml = '<span class="price">$10.00</span>';
        $this->assertEquals($priceHtml, $this->_helper->currency($price));
        $this->assertEquals($priceHtml, $this->_helper->formatCurrency($price));
    }

    public function testFormatPrice()
    {
        $price = 10.00;
        $priceHtml = '<span class="price">$10.00</span>';
        $this->assertEquals($priceHtml, $this->_helper->formatPrice($price));
    }

    public function testFormatDate()
    {
        $this->assertEquals($this->_dateTime->format(self::DATE_FORMAT_SHORT), $this->_helper->formatDate());

        $this->assertEquals(
            $this->_dateTime->format(self::DATETIME_FORMAT_SHORT), $this->_helper->formatDate(null, 'short', true)
        );

        $zendDate = new \Zend_Date($this->_dateTime->format('U'));
        $this->assertEquals(
            $zendDate->toString(self::DATETIME_FORMAT_SHORT_ISO),
            $this->_helper->formatTime($zendDate, 'short', true)
        );
    }

    public function testFormatTime()
    {
        $this->assertEquals($this->_dateTime->format(self::TIME_FORMAT_SHORT), $this->_helper->formatTime());

        $this->assertEquals(
            $this->_dateTime->format(self::DATETIME_FORMAT_SHORT), $this->_helper->formatTime(null, 'short', true)
        );

        $zendDate = new \Zend_Date($this->_dateTime->format('U'));
        $this->assertEquals(
            $zendDate->toString(self::TIME_FORMAT_SHORT_ISO),
            $this->_helper->formatTime($zendDate, 'short')
        );
    }

    /**
     * @magentoAppIsolation enabled
     */
    public function testIsDevAllowedDefault()
    {
        $this->assertTrue($this->_helper->isDevAllowed());
    }

    /**
     * @magentoConfigFixture current_store dev/restrict/allow_ips 192.168.0.1
     * @magentoAppIsolation enabled
     */
    public function testIsDevAllowedTrue()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\TestFramework\Request $request */
        $request = $objectManager->get('Magento\TestFramework\Request');
        $request->setServer(array('REMOTE_ADDR' => '192.168.0.1'));

        $this->assertTrue($this->_helper->isDevAllowed());
    }

    /**
     * @magentoConfigFixture current_store dev/restrict/allow_ips 192.168.0.1
     * @magentoAppIsolation enabled
     */
    public function testIsDevAllowedFalse()
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();

        /** @var \Magento\TestFramework\Request $request */
        $request = $objectManager->get('Magento\TestFramework\Request');
        $request->setServer(array('REMOTE_ADDR' => '192.168.0.3'));

        $this->assertFalse($this->_helper->isDevAllowed());
    }

    public function testCopyFieldset()
    {
        $fieldset = 'sales_copy_order';
        $aspect = 'to_edit';
        $data = array(
            'customer_email' => 'admin@example.com',
            'customer_group_id' => '1',
        );
        $source = new \Magento\Object($data);
        $target = new \Magento\Object();
        $expectedTarget = new \Magento\Object($data);
        $expectedTarget->setDataChanges(true); // hack for assertion

        $this->assertNull($this->_helper->copyFieldsetToTarget($fieldset, $aspect, 'invalid_source', array()));
        $this->assertNull($this->_helper->copyFieldsetToTarget($fieldset, $aspect, array(), 'invalid_target'));
        $this->assertEquals(
            $target,
            $this->_helper->copyFieldsetToTarget('invalid_fieldset', $aspect, $source, $target)
        );
        $this->assertSame($target, $this->_helper->copyFieldsetToTarget($fieldset, $aspect, $source, $target));
        $this->assertEquals($expectedTarget, $target);
    }

    public function testCopyFieldsetArrayTarget()
    {
        $fieldset = 'sales_copy_order';
        $aspect = 'to_edit';
        $data = array(
            'customer_email' => 'admin@example.com',
            'customer_group_id' => '1',
        );
        $source = new \Magento\Object($data);
        $target = array();
        $expectedTarget = $data;

        $this->assertEquals(
            $target,
            $this->_helper->copyFieldsetToTarget('invalid_fieldset', $aspect, $source, $target)
        );
        $this->assertEquals(
            $expectedTarget,
            $this->_helper->copyFieldsetToTarget($fieldset, $aspect, $source, $target));
    }

    public function testDecorateArray()
    {
        $original = array(
            array('value' => 1),
            array('value' => 2),
            array('value' => 3),
        );
        $decorated = array(
            array('value' => 1, 'is_first' => true, 'is_odd' => true),
            array('value' => 2, 'is_even' => true),
            array('value' => 3, 'is_last' => true, 'is_odd' => true),
        );

        // arrays
        $this->assertEquals($decorated, $this->_helper->decorateArray($original, ''));

        // \Magento\Object
        $sample = array(
            new \Magento\Object($original[0]),
            new \Magento\Object($original[1]),
            new \Magento\Object($original[2]),
        );
        $decoratedVo = array(
            new \Magento\Object($decorated[0]),
            new \Magento\Object($decorated[1]),
            new \Magento\Object($decorated[2]),
        );
        foreach ($decoratedVo as $obj) {
            $obj->setDataChanges(true); // hack for assertion
        }
        $this->assertEquals($decoratedVo, $this->_helper->decorateArray($sample, ''));
    }

    public function testJsonEncodeDecode()
    {
        $data = array('one' => 1, 'two' => 'two');
        $jsonData = '{"one":1,"two":"two"}';
        $this->assertEquals($jsonData, $this->_helper->jsonEncode($data));
        $this->assertEquals($data, $this->_helper->jsonDecode($jsonData));
    }

    public function testGetDefaultCountry()
    {
        $this->assertEquals('US', $this->_helper->getDefaultCountry());
    }
}
