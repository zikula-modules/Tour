<?php
/**
 * Zikula Application Framework
 *
 * @link http://www.zikula.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author Simon Birtwistle simon@itbegins.co.uk
 */

class Tour_Version extends Zikula_Version
{
    public function getMetaData()
    {
        $meta = array();
        $meta['displayname']      = __('Tour');
        //!module name that appears in URL
        $meta['url']              = __('tour');
        $meta['description']      = __('First time configuration and Zikula Tour.');
        $meta['version']          = '1.3.0';
        $meta['contact']          = 'http://zikula.org/';
        $meta['securityschema']   = array();
        return $meta;
    }
}