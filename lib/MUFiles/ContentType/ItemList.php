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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Tue Aug 06 08:44:10 CEST 2013.
 */

/**
 * Generic item list content plugin implementation class.
 */
class MUFiles_ContentType_ItemList extends MUFiles_ContentType_Base_ItemList
{
    // feel free to extend the content type here
}

function MUFiles_Api_ContentTypes_itemlist($args)
{
    return new MUFiles_Api_ContentTypes_itemListPlugin();
}
