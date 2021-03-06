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

class Tour_Controller_User extends Zikula_Controller
{
    /**
     * Main user function, simply returnt he tour index page.
     * @author Simon Birtwistle
     * @return string HTML string
     */
    public function main() {
        return $this->display();
    }

    /**
     * Display a tour page
     * @author Simon Birtwistle
     * @return string HTML string
     */
    public function display() {
        $page = FormUtil::getPassedValue('page', 'home', 'GET');

        if ($page == 'extensions') {
            $content = ModUtil::func('Tour', 'user', 'extensions');
        } else {
            $lang = ZLanguage::transformFS(ZLanguage::getLanguageCode());
            $lang = ZLanguage::transformFS(ZLanguage::getLanguageCode());
            if ($this->view->template_exists($lang.'/tour_user_display_'.$page.'.htm')) {
                $content = $this->view->fetch($lang.'/tour_user_display_'.$page.'.htm');
            } else {
                $content = $this->view->fetch('en/tour_user_display_'.$page.'.htm');
            }
        }

        return $content;
    }

    /**
     * Cycle through all installed modules looking for available module tours
     * @author Simon Birtwistle
     * @return string HTML string
     */
    public function extensions() {
        $modules = ModUtil::getAllMods();
        $modpages = array();
        foreach ($modules as $mod) {
            if (file_exists('modules/'.$mod['directory'].'/pndocs/tour_page1.htm')) {
                $modpages[] = $mod['name'];
            }
        }
        $themes = ThemeUtil::getAllThemes();
        $themepages = array();
        foreach ($themes as $theme) {
            if (file_exists('themes/'.$theme['directory'].'/pndocs/tour_page1.htm')) {
                $themepages[] = $theme['name'];
            }
        }

        $this->view->assign('modpages', $modpages)
                       ->assign('themepages', $themepages);
        $lang = ZLanguage::transformFS(ZLanguage::getLanguageCode());
        if ($this->view->template_exists($lang.'/tour_user_extensions.htm')) {
            $content = $this->view->fetch($lang.'/tour_user_extensions.htm');
        } else {
            $content = $this->view->fetch('en/tour_user_extensions.htm');
        }

        return $content;
    }

    /**
     * Display a tour page from an installed extension, or the distribution's tour page
     * @author Simon Birtwistle
     * @return string HTML string
     */
    public function exttour() {
        $page = FormUtil::getPassedValue('page', '1', 'GET');
        $ext = FormUtil::getPassedValue('ext', '', 'GET');
        $exttype = FormUtil::getPassedValue('exttype', 'module', 'GET');

        switch ($exttype) {
            case 'distro':
                $directory = 'docs/distribution';
                break;
            case 'module':
                $id = ModUtil::getIdFromName($ext);
                if (!$id) {
                    LogUtil::registerError($this->__f('Unknown module %s in Tour_user_exttour.', $ext));
                    System::redirect(ModUtil::url('Tour', 'user', 'main'));
                }
                $info = ModUtil::getInfo($id);
                $directory = 'modules/'.$info['directory'].'/pndocs';
                break;
            case 'theme':
                $id = ThemeUtil::getIDFromName($ext);
                if (!$id) {
                    LogUtil::registerError($this->__f('Unknown theme %s in Tour_user_exttour.', $ext));
                    System::redirect(ModUtil::url('Tour', 'user', 'main'));
                }
                $info = ThemeUtil::getInfo($id);
                $directory = $info['directory'].'/pndocs';
                break;
        }

        $lang = ZLanguage::transformFS(ZLanguage::getLanguageCode());
        $files = array($directory.'/'.$lang.'/tour_page'.$page.'.htm', $directory.'/tour_page'.$page.'.htm');

        $exists = false;
        foreach ($files as $file) {
            $file = DataUtil::formatForOS($file);
            $file = getcwd().'/'.$file;
            if (file_exists($file)) {
                $exists = true;
                break;
            }
        }

        if (!$exists) {
            LogUtil::registerError(__('Tour file does not exist!', $dom));
            return System::redirect(ModUtil::url('Tour', 'user', 'extensions', $dom));
        }

        return $this->view->fetch('tour_user_menu.htm').$this->view->fetch('file://'.$file);
    }
}