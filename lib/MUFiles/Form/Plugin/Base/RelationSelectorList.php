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
 * Relation selector plugin base class.
 */
class MUFiles_Form_Plugin_Base_RelationSelectorList extends MUFiles_Form_Plugin_AbstractObjectSelector
{
    /**
     * Get filename of this file.
     * The information is used to re-establish the plugins on postback.
     *
     * @return string
     */
    public function getFilename()
    {
        return __FILE__;
    }

    /**
     * Load event handler.
     *
     * @param Zikula_Form_View $view    Reference to Zikula_Form_View object.
     * @param array            &$params Parameters passed from the Smarty plugin function.
     *
     * @return void
     */
    public function load(Zikula_Form_View $view, &$params)
    {
        $this->processRequestData($view, 'GET');

        // load list items
        parent::load($view, $params);

        // preprocess selection: collect id list for related items
        $this->preprocessIdentifiers($view, $params);
    }

    /**
     * Entry point for customised css class.
     */
    protected function getStyleClass()
    {
        return 'z-form-relationlist';
    }

    /**
     * Decode event handler.
     *
     * @param Zikula_Form_View $view Reference to Zikula_Form_View object.
     *
     * @return void
     */
    public function decode(Zikula_Form_View $view)
    {
        parent::decode($view);

        // postprocess selection: reinstantiate objects for identifiers
        $this->processRequestData($view, 'POST');
    }
}
