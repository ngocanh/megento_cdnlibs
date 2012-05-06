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
 * Source model for CDN resource selection.
 *
 * @category  Megento
 * @package   Megento_CdnLibs
 * @author    Ngoc Anh Doan <ngoc@nhdoan.de>
 * @copyright 2012 Ngoc Anh Doan
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @link      https://github.com/ngocanh/megento_cdnlibs
 */
class Megento_CdnLibs_Model_System_Config_Source_Cdn_Resource
{
    const CDN_GOOGLE = 'ajax.googleapis.com/',
          CDN_MICROSOFT = 'ajax.aspnetcdn.com/',
          CDN_JQUERY = 'code.jquery.com/';
    
    const OPTION_VALUE_CUSTOM = 1,
          OPTION_VALUE_JQUERY = 2,
          OPTION_VALUE_GOOGLE = 3,
          OPTION_VALUE_MICROSOFT = 4;

    protected $_mapping = array(
        self::OPTION_VALUE_CUSTOM => array(
            'label' => 'Specify your own resource',
            'resource' => null
        ),
        self::OPTION_VALUE_GOOGLE => array(
            'label' => 'Google',
            'url-path' => 'ajax/libs/',
            'resource' => self::CDN_GOOGLE
        ),
        self::OPTION_VALUE_MICROSOFT => array(
            'label' => 'Microsoft',
            'url-path' => 'ajax/',
            'resource' => self::CDN_MICROSOFT
        ),
        self::OPTION_VALUE_JQUERY => array(
            'label' => 'jQuery',
            'url-path' => '',
            'resource' => self::CDN_JQUERY
        )
    );

    /**
     * List of option select.
     *
     * @var array
     */
    protected $_options = array();

    /**
     * @alias self::getResourceById()
     *
     * @param int $cdnId CDN resource ID.
     *
     * @return string
     */
    public function getResource($cdnId)
    {
        return $this->getResourceById($cdnId);
    }

    /**
     *
     * @param int $cdnId CDN resource ID.
     * 
     * @return type
     */
    public function getResourceById($cdnId)
    {
        $resource = '';
        $cdnId = (int) $cdnId;

        if (isset($this->_mapping[$cdnId])) {
            $resource = $this->_mapping[$cdnId]['resource'];
        }

        return $resource;
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
                array('value' => 0, 'label' => Mage::helper('adminhtml')->__('-- Please Select --'))
            );

            foreach ($this->_mapping as $key => $data) {
                $this->_options[] = array('value' => $key, 'label' => Mage::helper('nd_cdnlibs')->__($data['label']));
            }
        }

        return $this->_options;
    }
}
