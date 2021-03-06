<?php
/**
 * \Magento\Install\Model\Config
 *
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
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\Install\Model;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Install\Model\Config
     */
    private $_object;

    /**
     * @var \Magento\ObjectManager
     */
    private $_objectManager;

    public function setUp()
    {
        $this->_objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        /** @var $cacheTypeList \Magento\Core\Model\Cache\TypeListInterface */
        $cacheTypeList = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->create('Magento\Core\Model\Cache\TypeListInterface');
        $types = array_keys($cacheTypeList->getTypes());

        /** @var $cacheState \Magento\Core\Model\Cache\StateInterface */
        $cacheState = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()
            ->get('Magento\Core\Model\Cache\StateInterface');
        foreach ($types as $type) {
            $cacheState->setEnabled($type, false);
        }
        $cacheState->persist();

        /** @var \Magento\App\Dir $dirs */
        $dirs = $this->_objectManager->create(
            'Magento\App\Dir', array(
                'baseDir' => BP,
                'dirs' => array(
                    \Magento\App\Dir::MODULES => __DIR__ . '/_files',
                    \Magento\App\Dir::CONFIG => __DIR__ . '/_files'
                )
            )
        );

        /** @var \Magento\Module\Declaration\FileResolver $modulesDeclarations */
        $modulesDeclarations = $this->_objectManager->create(
            'Magento\Module\Declaration\FileResolver', array(
                'applicationDirs' => $dirs,
            )
        );


        /** @var \Magento\Module\Declaration\Reader\Filesystem $filesystemReader */
        $filesystemReader = $this->_objectManager->create(
            'Magento\Module\Declaration\Reader\Filesystem', array(
                'fileResolver' => $modulesDeclarations,
            )
        );

        /** @var \Magento\Module\ModuleList $modulesList */
        $modulesList = $this->_objectManager->create(
            'Magento\Module\ModuleList', array(
                'reader' => $filesystemReader,
            )
        );

        /** @var \Magento\Module\Dir\Reader $moduleReader */
        $moduleReader = $this->_objectManager->create(
            'Magento\Module\Dir\Reader', array(
                'moduleList' => $modulesList
            )
        );
        $moduleReader->setModuleDir('Magento_Test', 'etc', __DIR__ . '/_files/Magento/Test/etc');

        /** @var \Magento\Core\Model\Config\FileResolver $fileResolver */
        $fileResolver = $this->_objectManager->create(
            'Magento\Core\Model\Config\FileResolver', array(
                'moduleReader' => $moduleReader,
            )
        );

        /** @var \Magento\Install\Model\Config\Reader $configReader */
        $configReader = $this->_objectManager->create(
            'Magento\Install\Model\Config\Reader', array(
                'fileResolver' => $fileResolver,
            )
        );

        $configData =  $this->_objectManager->create(
            'Magento\Install\Model\Config\Data', array(
                'reader' => $configReader,
            )
        );

        $this->_object =  $this->_objectManager->create(
            'Magento\Install\Model\Config', array(
                'dataStorage' => $configData,
            )
        );
    }

    public function testGetWizardSteps()
    {
        $steps = $this->_object->getWizardSteps();
        $this->assertEquals(2, count($steps));
        $this->assertCount(2, $steps);
        $this->assertEquals(array('begin', 'locale'), array($steps[0]->getName(), $steps[1]->getName()));
    }

    public function testGetWritableFullPathsForCheck()
    {
        $directories = $this->_object->getWritableFullPathsForCheck();
        $this->assertEquals(2, count($directories));
        $this->assertCount(2, $directories);
        $this->assertEquals('1', $directories['etc']['existence']);
        $this->assertEquals('0', $directories['etc']['recursive']);
        $this->assertTrue(array_key_exists('path', $directories['etc']));
        $this->assertEquals('1', $directories['var']['existence']);
        $this->assertEquals('1', $directories['var']['recursive']);
        $this->assertTrue(array_key_exists('path', $directories['var']));
    }

    public function testGetPathForCheck()
    {
        $directories = $this->_object->getPathForCheck();
        $this->assertEquals(2, count($directories['writeable']));
        $this->assertCount(2, $directories['writeable']);
        $expected = array(
            array(
                'existence' => '1',
                'recursive' => '0'
            ),
            array(
                'existence' => '1',
                'recursive' => '1'
            ),
        );
        $this->assertEquals($expected, $directories['writeable']);

    }

    public function testMergeCompleteAndPartial()
    {
        $fileList = array(
            __DIR__ . '/_files/install_wizard_complete.xml',
            __DIR__ . '/_files/install_wizard_partial.xml'
        );
        $fileResolverMock = $this->getMockBuilder('Magento\Config\FileResolverInterface')
            ->setMethods(array('get'))
            ->disableOriginalConstructor()
            ->getMock();
        $fileResolverMock->expects($this->once())
            ->method('get')
            ->with($this->equalTo('install_wizard.xml'))
            ->will($this->returnValue($fileList));

        $configReader = $this->_objectManager->create(
            'Magento\Install\Model\Config\Reader', array(
                'fileResolver' => $fileResolverMock,
            )
        );

        $configData =  $this->_objectManager->create(
            'Magento\Install\Model\Config\Data', array(
                'reader' => $configReader,
            )
        );

        /** @var \Magento\Install\Model\Config $model */
        $model = $this->_objectManager->create(
            'Magento\Install\Model\Config', array(
                'dataStorage' => $configData,
            )
        );

        $expectedSteps = array(
            array(
                'name' => "begin",
                'controller' => 'wizard_custom',
                'action' => 'begin',
                'code' => 'License Agreement Updated'
            ),
            array(
                'name' => "after_end",
                'controller' => 'wizard_custom',
                'action' => 'after_end',
                'code' => 'One more thing..'
            )
        );

        $steps = $model->getWizardSteps();

        $counter = 0;
        foreach ($steps as $step) {
            if (isset($expectedSteps[$counter])) {
                $this->assertEquals($expectedSteps[$counter], $step->getData());
                $counter++;
            } else {
                $this->fail('It is more Install steps than expected');
            }
        }
        if (count($expectedSteps) > $counter+1) {
            $this->fail('Some expected steps are missing');
        }
        $pathesForCheck = $model->getWritableFullPathsForCheck();
        $this->assertArrayHasKey('etc', $pathesForCheck);
        $this->assertArrayHasKey('media', $pathesForCheck);
        $this->assertArrayHasKey('lib', $pathesForCheck);
        $this->assertEquals('1', $pathesForCheck['etc']['recursive']);
    }
}
