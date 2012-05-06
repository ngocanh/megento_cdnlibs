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
 * @category  Megento
 * @package   Megento_CdnLibs
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @copyright 2012 Ngoc Anh Doan
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/ngocanh/megento_cdnlibs
 */

/**
 * jQuery system configuration model.
 *
 * @category  Megento
 * @package   Megento_CdnLibs
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @copyright 2012 Ngoc Anh Doan
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/ngocanh/megento_cdnlibs
 */
class Megento_CdnLibs_Model_System_Config_Cdn_Jquery extends Megento_CdnLibs_Model_System_Config
{
    /**
     * Keys using as namespace to access configurations of jQuery:
     *
     * <b>
     *  - Core
     *  - UI.
     * </b>
     *
     * and mapp to:
     *
     * <b>
     *  - 'nd_cdn_libs/jquery_core'
     *  - 'nd_cdn_libs/jquery_ui'
     * </b>
     */
    const SYSTEM_CONFIG_KEY_JQUERY_CORE = 'core',
          SYSTEM_CONFIG_KEY_JQUERY_UI = 'ui';

    /**
     * Mapp URL path <i>templates</i> to resource ID since each CDN resource does
     * it own way.
     *
     * @var array
     */
    protected $_jQueryMapping = array(
        Megento_CdnLibs_Model_System_Config_Source_Cdn_Resource::OPTION_VALUE_GOOGLE => array(
            'core' => 'ajax/libs/jquery/%1$s/jquery%2$s.js',
            'ui' => 'ajax/libs/jqueryui/%1$s/jqueryui%2$s.js',
            'themes' => 'ajax/libs/jqueryui/%1$s/themes/%2$s/jquery-ui.css'
        ),
        Megento_CdnLibs_Model_System_Config_Source_Cdn_Resource::OPTION_VALUE_MICROSOFT => array(
            'core' => 'ajax/jQuery/jquery-%1$s%2$s.js',
            'ui' => 'ajax/jquery.ui/%1$s/jquery-ui%2$s.js',
            'themes' => 'ajax/jquery.ui/%1$s/themes/%2$s/jquery-ui.css'
        ),
        Megento_CdnLibs_Model_System_Config_Source_Cdn_Resource::OPTION_VALUE_JQUERY => array(
            'core' => 'jquery-%1$s%2$s.js'
        )
    );

    /**
     * Mapp system config XML node to self::$_data array key.
     *
     * @var array
     * @see Megento_CdnLibs_Model_System_Config::_init()
     */
    protected $_xmlPath = array(
        self::SYSTEM_CONFIG_KEY_JQUERY_CORE => 'nd_cdn_libs/jquery_core',
        self::SYSTEM_CONFIG_KEY_JQUERY_UI => 'nd_cdn_libs/jquery_ui'
    );

    /**
     * Converts field names for data getter and handle configuration sections:
     *
     *  <i>
     *      - $this->getCoreResource() => $this->getData('core/resource');
     *      - $this->getCore() => $this->getData('core');
     *          <b>whole subset of configuration - 'core' which is mapped to 'jquery_core'</b>
     *      - $this->getUiVersion() => $this->getData('ui/version');
     *  </i>
     * 
     * Uses cache to eliminate unneccessary preg_match_all operations.
     *
     * @param string $name Section and explicit configuration key.
     *
     * @return string
     */
    protected function _underscore($name)
    {
        if (isset(self::$_underscoreCache[$name])) {
            return self::$_underscoreCache[$name];
        }

        $result = '';
        $isConfig = false;
        $matches = array();
        // Next operations may slowing down on a high number of accesses
        // but the results are cached anyway!
        preg_match_all('/[[A-Z]{1}[a-z]*]*/', $name, $matches);

        // We're looking for configuration sections like - currently 'core' and 'ui'.
        if ($matches) {
            $isConfig = in_array(
                strtolower($matches[0][0]),
                array(
                    self::SYSTEM_CONFIG_KEY_JQUERY_CORE,
                    self::SYSTEM_CONFIG_KEY_JQUERY_UI
                )
            );
        }

        if ($isConfig) {
            $result .= array_shift($matches[0]);
            $result .= (empty($matches[0]) ? '' : '/' . implode('_', $matches[0]));
        } else {
            $result = implode('_', $matches[0]);
        }

        $result = strtolower($result);
        self::$_underscoreCache[$name] = $result;

        return $result;
    }

    /**
     * Alias for getting jQuery Core config.
     *
     * @param string $key Systeml XML field to get value from - empty returns whole
     *                    config section.
     *
     * @return string|array
     */
    public function getCoreConfig($key = null)
    {
        return $this->getCore($key);
    }

    /**
     * Alias for getting jQuery UI config.
     *
     * @param string $key Systeml XML field to get value from - empty returns whole
     *                    config section.
     *
     * @return string|array
     */
    public function getUiConfig($key = null)
    {
        return $this->getUi($key);
    }

    /**
     * Generates URI to jQuery core resource, assemble according to set configs
     * in back end.
     *
     * @return string
     *
     * @todo Implementing custom CDN resource
     */
    public function getCoreUri()
    {
        if (!($coreUri = $this->getData(self::SYSTEM_CONFIG_KEY_JQUERY_CORE . '/uri'))) {
            $cdnResourceId = (int) $this->getCoreResource();
            
            if ($cdnResourceId > Megento_CdnLibs_Model_System_Config_Source_Cdn_Resource::OPTION_VALUE_CUSTOM) {
                $resource = Mage::getSingleton('cdnlibs_source/cdn_resource')
                    ->getResource($cdnResourceId);

                $resource .= $this->_jQueryMapping[$cdnResourceId][self::SYSTEM_CONFIG_KEY_JQUERY_CORE];
            } else {
                // TODO: custom CDN resource
            }

            $coreUri = sprintf(
                $resource,
                $this->getCoreVersion(),
                $this->getCoreMinified() ? '.min' : ''
            );

            $this->_data[self::SYSTEM_CONFIG_KEY_JQUERY_CORE]['uri'] = $coreUri;
        }

        return $coreUri;
    }

    /**
     * Generates URI to jQuery UI theme resource, assemble according to set configs
     * in back end.
     *
     * @return string
     *
     * @todo Implementing custom CDN resource
     */
    public function getUiThemeUri()
    {
        if (!($uiThemeUri = $this->getData(self::SYSTEM_CONFIG_KEY_JQUERY_UI . '/theme_uri'))) {
            $cdnResourceId = (int) $this->getUiConfig('resource');

            if ($cdnResourceId > Megento_CdnLibs_Model_System_Config_Source_Cdn_Resource::OPTION_VALUE_JQUERY) {
                $resource = Mage::getSingleton('cdnlibs_source/cdn_resource')
                    ->getResource($cdnResourceId);

                $resource .= $this->_jQueryMapping[$cdnResourceId]['themes'];
            } else {
                // TODO: custom CDN resource
            }

            $uiThemeUri = sprintf(
                $resource,
                $this->getUiVersion(),
                Mage::getSingleton('cdnlibs_source/cdn_jquery_ui_themes')
                    ->getThemeById($this->getUiTheme())
            );

            $this->_data[self::SYSTEM_CONFIG_KEY_JQUERY_UI]['theme_uri'] = $uiThemeUri;
        }

        return $uiThemeUri;
    }

    /**
     * Generates URI to jQuery UI resource, assemble according to set configs
     * in back end.
     *
     * @return string
     *
     * @todo Implementing custom CDN resource
     */
    public function getUiUri()
    {
        if (!$this->isUiEnabled()) {
            return '';
        }

        if (!($uiUri = $this->getData(self::SYSTEM_CONFIG_KEY_JQUERY_UI . '/uri'))) {
            $cdnResourceId = (int) $this->getUiConfig('resource');

            if ($cdnResourceId > Megento_CdnLibs_Model_System_Config_Source_Cdn_Resource::OPTION_VALUE_JQUERY) {
                $resource = Mage::getSingleton('cdnlibs_source/cdn_resource')
                    ->getResource($cdnResourceId);

                $resource .= $this->_jQueryMapping[$cdnResourceId][self::SYSTEM_CONFIG_KEY_JQUERY_UI];
            } else {
                // TODO: custom CDN resource
            }

            $uiUri = sprintf(
                $resource,
                $this->getUiVersion(),
                $this->getUiMinified() ? '.min' : ''
            );

            $this->_data[self::SYSTEM_CONFIG_KEY_JQUERY_UI]['uri'] = $uiUri;
        }

        return $uiUri;
    }
    
    /**
     * Get URI of jQuery core resource.
     *
     * @return string
     * 
     * @alias self::getCoreUri()
     */
    public function getUriCore()
    {
        return $this->getCoreUri();
    }

    /**
     * Get URI of jQuery UI resource.
     *
     * @return string
     *
     * @alias self::getUiUri()
     */
    public function getUriUi()
    {
        return $this->getUiUri();
    }

    /**
     * Get URI of jQuery UI theme.
     *
     * @return string
     *
     * @alias self::getUiThemeUri()
     */
    public function getUriUiTheme()
    {
        $this->getUiThemeUri();
    }

    /**
     * Check if jQuery (core) is enabled.
     *
     * @return bool
     *
     * @alias self::isEnabled()
     */
    public function isCoreEnabled()
    {
        return $this->isEnabled();
    }

    /**
     * Check if jQuery (core) is enabled.
     *
     * @return bool
     */
    public function isEnabled()
    {
        return (bool) $this->getCoreEnable();
    }

    /**
     * Check if jQuery UI is useable, which implicates that jQuery (core) is
     * active.
     *
     * @return bool
     *
     * @see self::isEnabled()
     */
    public function isUiEnabled()
    {
        return $this->isCoreEnabled() && (bool) $this->getUiEnable();
    }
}
