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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Tue Aug 06 08:44:09 CEST 2013.
 */

/**
 * The mufilesActionUrl modifier creates the URL for a given action.
 *
 * @param string $urlType      The url type (admin, user, etc.)
 * @param string $urlFunc      The url func (view, display, edit, etc.)
 * @param array  $urlArguments The argument array containing ids and other additional parameters
 *
 * @return string Desired url in encoded form.
 */
function smarty_modifier_mufilesActionUrl($urlType, $urlFunc, $urlArguments)
{
    return DataUtil::formatForDisplay(ModUtil::url('MUFiles', $urlType, $urlFunc, $urlArguments));
}
