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
 * The mufilesColourInput plugin handles fields carrying a html colour code.
 * It provides a colour picker for convenient editing.
 *
 * @param array            $params  All attributes passed to this function from the template.
 * @param Zikula_Form_View $view    Reference to the view object.
 *
 * @return string The output of the plugin.
 */
function smarty_function_mufilesColourInput($params, $view)
{
    return $view->registerPlugin('MUFiles_Form_Plugin_ColourInput', $params);
}
