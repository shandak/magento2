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
namespace Magento\Core\App\Request;

class PathInfoProcessor implements \Magento\App\Request\PathInfoProcessorInterface
{
    /**
     * @var \Magento\Core\Model\StoreManager
     */
    private $_storeManager;

    /**
     * @param \Magento\Core\Model\StoreManager $storeManager
     */
    public function __construct(\Magento\Core\Model\StoreManager $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    /**
     * Process path info
     *
     * @param \Magento\App\RequestInterface $request
     * @param string $pathInfo
     * @return string
     */
    public function process(\Magento\App\RequestInterface $request, $pathInfo)
    {
        $pathParts = explode('/', ltrim($pathInfo, '/'), 2);
        $storeCode = $pathParts[0];

        $stores = $this->_storeManager->getStores(true, true);
        if (isset($stores[$storeCode]) && $stores[$storeCode]->isUseStoreInUrl()) {
            if (!$request->isDirectAccessFrontendName($storeCode)) {
                $this->_storeManager->setCurrentStore($storeCode);
                $pathInfo = '/' . (isset($pathParts[1]) ? $pathParts[1] : '');
                return $pathInfo;
            } elseif (!empty($storeCode)) {
                $request->setActionName('noRoute');
                return $pathInfo;
            }
            return $pathInfo;
        }
        return $pathInfo;
    }
}