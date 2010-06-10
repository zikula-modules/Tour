<?php
/**
 * Zikula Application Framework
 *
 * @link http://www.zikula.org
 * @version $Id: Loader.class.php 22543 2007-07-31 12:50:09Z rgasch $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @author Simon Birtwistle simon@itbegins.co.uk
 * @package Zikula_Docs
 * @subpackage Tour
 */
class Tour_Api_User extends Zikula_Api
{
    /**
     * Get the sub links for the first time tour
     * @author Simon Birtwistle
     * @return string HTML string
     */
    public function getsublinks($args) {
        if (!SecurityUtil::checkPermission('Tour::', '::', ACCESS_READ)) {
            return array();
        }

        $ext = FormUtil::getPassedValue('ext', isset($args['ext']) ? $args['ext'] : null, 'GET');
        // Generate links for module tutorial pages.
        if (!empty($ext)) {
            return ModUtil::apiFunc('Tour', 'user', 'getextlinks');
        }

        $page = FormUtil::getPassedValue('page', isset($args['page']) ? $args['page'] : null, 'GET');
        $links = array();
        switch ($page) {
            case 'firsttime':
            case 'firsttimemodules':
            case 'firsttimeblocks':
            case 'firsttimethemes':
                $links[] = array('url' => ModUtil::url('Tour', 'user', 'display', array ('page' => 'firsttime')), 'text' => $this->__('Start'));
                $links[] = array('url' => ModUtil::url('Tour', 'user', 'display', array ('page' => 'firsttimethemes')), 'text' => $this->__('Themes'));
                $links[] = array('url' => ModUtil::url('Tour', 'user', 'display', array ('page' => 'firsttimemodules')), 'text' => $this->__('Modules'));
                $links[] = array('url' => ModUtil::url('Tour', 'user', 'display', array ('page' => 'firsttimeblocks')), 'text' => $this->__('Blocks'));
                break;
        }
        return $links;
    }

    /**
     * Get an extensions' page links.
     * @author Simon Birtwistle
     * @return string HTML string
     */
    public function getextlinks($args) {
        $ext = FormUtil::getPassedValue('ext', isset($args['ext']) ? $args['ext'] : null, 'GET');
        $exttype = FormUtil::getPassedValue('exttype', isset($args['exttype']) ? $args['exttype'] : 'module', 'GET');



        switch ($exttype) {
            case 'distro':
                $directory = 'docs/distribution';
                break;
            case 'module':
                $id = ModUtil::getIdFromName($ext);
                if (!$id) {
                    LogUtil::registerError($this->__f('Unknown module %s in Tour_userapi_getsublinks.', $ext));
                    System::redirect(ModUtil::url('Tour', 'user', 'main'));
                }
                $info = ModUtil::getInfo($id);
                $directory = 'modules/'.$info['directory'].'/pndocs/';
                break;
            case 'theme':
                $id = pnThemeGetIDFromName($ext);
                if (!$id) {
                    LogUtil::registerError($this->__f('Unknown theme %s in Tour_userapi_getsublinks.', $ext));
                    System::redirect(ModUtil::url('Tour', 'user', 'main'));
                }
                $info = pnThemeGetInfo($id);
                $directory = $info['directory'].'/pndocs/';
                break;
        }

        $directory = DataUtil::formatForOS($directory);
        $files = array();
        if ($handle = opendir($directory)) {
            while (false !== ($filename = readdir($handle))) {
                $files[] = $filename;
            }
            closedir($handle);
        }

        $files = preg_grep("/tour_page[0-9]\.htm.*/", $files);
        $links = array();
        foreach ($files as $file) {
            $pageno = str_replace(array('tour_page', '.htm', '.html'), '', $file);
            $links[] = array('url' => ModUtil::url('Tour', 'user', 'exttour', array ('page' => $pageno, 'ext' => $ext, 'exttype' => $exttype)), 'text' => $this->__f('Page %s', $pageno));
        }
        return $links;
    }
}