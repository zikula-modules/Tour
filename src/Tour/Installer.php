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

class Tour_Installer extends Zikula_Installer
{
    /**
     * initialise the tour module
     *
     */
    public function install() {
        return true;
    }

    public function upgrade($oldversion) {
        return true;
    }

    public function uninstall() {
        return true;
    }
}
