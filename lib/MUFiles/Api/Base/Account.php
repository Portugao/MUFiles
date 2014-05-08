<?php
/**
 * MUFiles.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license 
 * @package MUFiles
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.6.2 (http://modulestudio.de).
 */

/**
 * Account api base class.
 */
class MUFiles_Api_Base_Account extends Zikula_AbstractApi
{
    /**
     * Return an array of items to show in the your account panel.
     *
     * @param array $args List of arguments.
     *
     * @return array List of collected account items
     */
    public function getall(array $args = array())
    {
        // collect items in an array
        $items = array();
    
        $useAccountPage = $this->getVar('useAccountPage', true);
        if ($useAccountPage === false) {
            return $items;
        }
    
        $userName = (isset($args['uname'])) ? $args['uname'] : UserUtil::getVar('uname');
        // does this user exist?
        if (UserUtil::getIdFromName($userName) === false) {
            // user does not exist
            return $items;
        }
    
        if (!SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_OVERVIEW)) {
            return $items;
        }
    
    
        // Create an array of links to return
        if (SecurityUtil::checkPermission($this->name . '::', '::', ACCESS_ADMIN)) {
            $items[] = array(
                'url'   => ModUtil::url($this->name, 'admin', 'main'),
                'title' => $this->__('M u files Backend'),
                'icon'   => 'configure.png',
                'module' => 'core',
                'set'    => 'icons/large'
            );
        }
    
        // return the items
        return $items;
    }
}
