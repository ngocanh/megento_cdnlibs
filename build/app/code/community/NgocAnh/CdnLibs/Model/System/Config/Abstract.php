<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE_AFL.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
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
 * @category  NgocAnh
 * @package   NgocAnh_CdnLibs
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @copyright 2012 Ngoc Anh Doan
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/ngocanh/ngocanh_mage_cdnlibs
 */

/**
 * Abstract configuration class - yet read-only.
 * 
 * @category  NgocAnh
 * @package   NgocAnh_CdnLibs
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @copyright 2012 Ngoc Anh Doan
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/ngocanh/ngocanh_mage_cdnlibs
 */
abstract class NgocAnh_CdnLibs_Model_System_Config_Abstract extends Varien_Object
{
    /**
     * System XML path to load.
     *
     * @var string|array
     */
    protected $_xmlPath = '';

    /**
     * @see self::_init()
     *
     * @return NgocAnh_CdnLibs_Model_System_Config_Abstract
     */
    protected function _construct()
    {
        return $this->_init();
    }

    /**
     * Fill up instance with configuration
     *
     * @return NgocAnh_CdnLibs_Model_System_Config_Abstract
     */
    protected function _init()
    {
        $registryKey = '_singleton/' . strtolower(get_class($this));

        if (($singleton = Mage::registry($registryKey))) {
            return $singleton;
        }

        if (empty($this->_xmlPath)) {
            Mage::throwException(
                get_class($this) . ' could\'t be loaded, $_xmlPath is empty!'
            );
        }

        if (is_array($this->_xmlPath)) {
            foreach ($this->_xmlPath as $key => $path) {
                $this->_data[$key] = Mage::getStoreConfig($path);
            }
        } else {
            $this->_data = Mage::getStoreConfig($this->_xmlPath);
        }

        Mage::register($registryKey, $this);

        return $this;
    }
}
