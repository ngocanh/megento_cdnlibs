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
 * Child block class of 'head' for rendering additional external JS/CSS CDN resources.
 *
 * @category  NgocAnh
 * @package   NgocAnh_CdnLibs
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @copyright 2012 Ngoc Anh Doan
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/ngocanh/ngocanh_mage_cdnlibs
 */
class NgocAnh_CdnLibs_Block_Html_Head_Cdn_Resource extends Mage_Core_Block_Template
{
    /**
     * jQuery.noConflict short hand to get compatible with prototype.js' <i>$</i>
     */
    const DEFAULT_JQUERY_SHORTCUT = '$j';

    /**
     * Keys to access JS/CSS items.
     */
    const ITEM_KEY_CSS = 'css',
          ITEM_KEY_JS = 'js';

    /**
     * Configuration instance for jQuery
     *
     * @var NgocAnh_CdnLibs_Model_System_Config_Cdn_Jquery
     */
    protected $_jQueryConfig = null;

    /**
     * Default template - make sure the logic is implemented in template if
     * replacing the default one!
     *
     * @var string
     */
    protected $_template = 'ngocanh/html/head/cdn/resource.phtml';

    /**
     * helper to add protocol to each resource and generating a valid URL
     *
     * @var NgocAnh_CdnLibs_Helper_ExternalUrl
     */
    protected $_urlHelper = null;

    /**
     * On init add external resources set in back end:
     *
     *  <i>
     *      - jQuery Core
     *      - jQuery UI and theme
     *  </i>
     *
     * @return NgocAnh_CdnLibs_Block_Html_Head_Cdn_Resource
     */
    protected function _construct()
    {
        if ($this->isEnabled()) {
            $this->addJs($this->getJqueryConfig()->getCoreUri());
        }

        if ($this->isUiEnabled()) {
            if ($this->getJqueryConfig()->getUiConfig('theme')) {
                $this->addCss($this->getJqueryConfig()->getUiThemeUri());
            }
            
            $this->addJs($this->getJqueryConfig()->getUiUri());
        }

        return $this;
    }

    /**
     * Add CSS file resource to HEAD entity
     *
     * @param string $src
     * @param string $params
     *
     * @return NgocAnh_CdnLibs_Block_Html_Head_Cdn_Resource
     */
    public function addCss($src, $params = array())
    {
        if (empty($params) || empty($params['media'])) {
            $params['media'] = 'all';
        }

        return $this->addItem(self::ITEM_KEY_CSS, $src, $params);
    }

    /**
     * Add JavaScript file resource to HEAD entity
     *
     * @param string $src
     * @param string $params
     *
     * @return NgocAnh_CdnLibs_Block_Html_Head_Cdn_Resource
     */
    public function addJs($src, $params = array())
    {
        if (empty($params) || empty($params['type'])) {
            $params['type'] = 'text/javascript';
        }

        return $this->addItem(self::ITEM_KEY_JS, $src, $params);
    }

    /**
     * Add external resources to head.
     *
     * Allowed types:
     *
     *  <i>
     *      - js
     *      - css
     *  </i>
     *
     * @param string $type   Resource type
     * @param string $src
     * @param array  $params Additional options list:
     *      <i><ul>
     *          <li>protocolCheck</li>
     *          <li>media</li>
     *          <li>type</li>
     *      </ul></i>
     * @param string $if     Not implemented yet.
     * @param string $cond   Not implemented yet.
     *
     * @return NgocAnh_CdnLibs_Block_Html_Head_Cdn_Resource
     *
     * @todo $if and $cond implementation
     */
    public function addItem($type, $src, $params = array(), $if = null, $cond = null)
    {
        $src = $this->getUrlHelper()->getUrl(
            $src,
            (isset($params['protocolCheck'])) ? (bool) $params['protocolCheck'] : false
        );

        $this->_data['items'][$type][$src] = array(
            'params' => $params,
            'if' => $if,
            'cond' => $cond
        );

        return $this;
    }

    /**
     * Get a list of CSS items.
     *
     * @return array
     */
    public function getCssItems()
    {
        return $this->_data['items'][self::ITEM_KEY_CSS];
    }

    /**
     * Get jQuery configuration instance.
     *
     * @return NgocAnh_CdnLibs_Model_System_Config_Cdn_Jquery
     */
    public function getJqueryConfig()
    {
        if (is_null($this->_jQueryConfig)) {
            $this->_jQueryConfig = Mage::getModel('cdnlibs_config/cdn_jquery');
        }

        return $this->_jQueryConfig;
    }

    /**
     * jQuery shortcut - default is <i>$j</>.
     *
     * @see self::DEFAULT_JQUERY_SHORTCUT
     *
     * @return string
     */
    public function getShortcut()
    {
        return $this->getJqueryConfig()->getCoreConfig('abbreviation') ?
            $this->getJqueryConfig()->getCoreConfig('abbreviation') :
            self::DEFAULT_JQUERY_SHORTCUT;
    }

    /**
     * Get a list of external JS resources.
     *
     * @return array
     */
    public function getJsItems()
    {
        return $this->_data['items'][self::ITEM_KEY_JS];
    }

    /**
     * Helper to prepend protocol and complete the URL.
     *
     * @return NgocAnh_CdnLibs_Helper_ExternalUrl
     */
    public function getUrlHelper()
    {
        if (is_null($this->_urlHelper)) {
            $this->_urlHelper = Mage::helper('nd_cdnlibs/externalUrl');
        }

        return $this->_urlHelper;
    }

    /**
     * Check if jQuery Core is enabled.
     *
     * @return bool
     */
    public function isjQueryCoreEnabled()
    {
        return $this->getJqueryConfig()->isCoreEnabled();
    }

    /**
     * Check if jQuery Core and UI are enabled.
     *
     * @return bool
     */
    public function isjQueryUiEnabled()
    {
        return $this->getJqueryConfig()->isUiEnabled();
    }
}
