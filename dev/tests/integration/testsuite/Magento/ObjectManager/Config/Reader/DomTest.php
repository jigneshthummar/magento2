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
 * @copyright   Copyright (c) 2013 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Magento\ObjectManager\Config\Reader;

class DomTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\ObjectManager\Config\Reader\Dom
     */
    protected $_model;

    /**
     * @var array
     */
    protected $_fileList;

    /**
     * @var \Magento\App\Config\FileResolver\Primary
     */
    protected $_fileResolverMock;

    /**
     * @var \DOMDocument
     */
    protected $_mergedConfig;

    /**
     * @var \Magento\App\Config\ValidationState
     */
    protected $_validationState;

    /**
     * @var \Magento\ObjectManager\Config\SchemaLocator
     */
    protected $_schemaLocator;

    /**
     * @var \Magento\ObjectManager\Config\Mapper\Dom
     */
    protected $_mapper;

    protected function setUp()
    {
        $fixturePath = realpath(__DIR__ . '/../../_files') . DIRECTORY_SEPARATOR;
        $this->_fileList = array(
            $fixturePath . 'config_one.xml',
            $fixturePath . 'config_two.xml',
        );

        $this->_fileResolverMock = $this->getMock(
            'Magento\App\Config\FileResolver\Primary', array(), array(), '', false
        );
        $this->_fileResolverMock->expects($this->once())->method('get')->will($this->returnValue($this->_fileList));
        $this->_mapper = new \Magento\ObjectManager\Config\Mapper\Dom();
        $this->_validationState =
            new \Magento\App\Config\ValidationState(\Magento\App\State::MODE_DEFAULT);
        $this->_schemaLocator = new \Magento\ObjectManager\Config\SchemaLocator();

        $this->_mergedConfig = new \DOMDocument();
        $this->_mergedConfig->load($fixturePath . 'config_merged.xml');
    }

    public function testRead()
    {
        $model = new \Magento\ObjectManager\Config\Reader\Dom(
            $this->_fileResolverMock,
            $this->_mapper,
            $this->_schemaLocator,
            $this->_validationState
        );
        $this->assertEquals($this->_mapper->convert($this->_mergedConfig), $model->read('scope'));
    }

}