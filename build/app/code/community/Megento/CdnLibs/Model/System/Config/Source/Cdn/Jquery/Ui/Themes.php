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
 * Source model for jQuery UI theme selection.
 *
 * @category  Megento
 * @package   Megento_CdnLibs
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @copyright 2012 Ngoc Anh Doan
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/ngocanh/megento_cdnlibs
 */
class Megento_CdnLibs_Model_System_Config_Source_Cdn_Jquery_Ui_Themes
{
    const OPTION_VALUE_CUSTOM_THEME = 1;

    /**
     * Array index 0 and 1 are already used for:
     *
     *  - '-- Please Select --' and
     *  - 'Specify Theme'
     */
    const OPTION_VALUE_OFFSET = 2;
    
    protected $_opions = array();

    /**
     * List of themes shipped with jQuery UI 1.8.17
     *
     * @var array
     */
    protected $_themes = array(
        'base', 'black-tie', 'blitzer', 'cupertino', 'dark-hive',
        'dot-luv', 'eggplant', 'excite-bike', 'flick', 'hot-sneaks',
        'humanity', 'le-frog', 'mint-choc', 'overcast', 'pepper-grinder',
        'redmond', 'smoothness', 'south-street', 'start', 'sunny',
        'swanky-purse', 'trontastic', 'ui-darkness', 'ui-lightness', 'vader'
    );

    /**
     * Get corresponding theme name to ID.
     *
     * @param int $id Theme ID to name from.
     *
     * @return string
     *
     * @see self::OPTION_VALUE_OFFSET
     */
    public function getThemeById($id)
    {
        $theme = '';

        if ($id) {
            // note the offset!
            $id = (int) $id - self::OPTION_VALUE_OFFSET;

            if (isset($this->_themes[$id])) {
                $theme = $this->_themes[$id];
            }
        }

        return $theme;
    }

    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        if (empty($this->_options)) {
            $this->_options = array(
                array('value' => 0, 'label' => Mage::helper('adminhtml')->__('-- Please Select --')),
                array(
                    'value' => self::OPTION_VALUE_CUSTOM_THEME,
                    'label' => Mage::helper('nd_cdnlibs')->__('Specify Theme')
                )
            );

            foreach ($this->_themes as $key => $theme) {
                $this->_options[] = array('value' => $key + self::OPTION_VALUE_OFFSET, 'label' => $theme);
            }
        }

        return $this->_options;
    }
}
