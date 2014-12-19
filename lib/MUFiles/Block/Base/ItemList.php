<?php
/**
 * MUFiles.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @package MUFiles
 * @author Michael Ueberschaer <kontakt@webdesign-in-bremen.com>.
 * @link http://webdesign-in-bremen.com
 * @link http://zikula.org
 * @version Generated by ModuleStudio 0.7.0 (http://modulestudio.de).
 */

/**
 * Generic item list block base class.
 */
class MUFiles_Block_Base_ItemList extends Zikula_Controller_AbstractBlock
{
    /**
     * List of object types allowing categorisation.
     *
     * @var array
     */
    protected $categorisableObjectTypes;
    
    /**
     * Initialise the block.
     */
    public function init()
    {
        SecurityUtil::registerPermissionSchema('MUFiles:ItemListBlock:', 'Block title::');
    
        $this->categorisableObjectTypes = array('collection');
    }
    
    /**
     * Get information on the block.
     *
     * @return array The block information
     */
    public function info()
    {
        $requirementMessage = '';
        // check if the module is available at all
        if (!ModUtil::available('MUFiles')) {
            $requirementMessage .= $this->__('Notice: This block will not be displayed until you activate the MUFiles module.');
        }
    
        return array('module'          => 'MUFiles',
                     'text_type'       => $this->__('MUFiles list view'),
                     'text_type_long'  => $this->__('Display list of MUFiles objects.'),
                     'allow_multiple'  => true,
                     'form_content'    => false,
                     'form_refresh'    => false,
                     'show_preview'    => true,
                     'admin_tableless' => true,
                     'requirement'     => $requirementMessage);
    }
    
    /**
     * Display the block.
     *
     * @param array $blockinfo the blockinfo structure
     *
     * @return string output of the rendered block
     */
    public function display($blockinfo)
    {
        // only show block content if the user has the required permissions
        if (!SecurityUtil::checkPermission('MUFiles:ItemListBlock:', "$blockinfo[title]::", ACCESS_OVERVIEW)) {
            return false;
        }
    
        // check if the module is available at all
        if (!ModUtil::available('MUFiles')) {
            return false;
        }
    
        // get current block content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
        $vars['bid'] = $blockinfo['bid'];
    
        // set default values for all params which are not properly set
        if (!isset($vars['objectType']) || empty($vars['objectType'])) {
            $vars['objectType'] = 'collection';
        }
        if (!isset($vars['sorting']) || empty($vars['sorting'])) {
            $vars['sorting'] = 'default';
        }
        if (!isset($vars['amount']) || !is_numeric($vars['amount'])) {
            $vars['amount'] = 5;
        }
        if (!isset($vars['template'])) {
            $vars['template'] = 'itemlist_' . DataUtil::formatForOS($vars['objectType']) . '_display.tpl';
        }
        if (!isset($vars['customTemplate'])) {
            $vars['customTemplate'] = '';
        }
        if (!isset($vars['filter'])) {
            $vars['filter'] = '';
        }
    
        if (!isset($vars['catIds'])) {
            $primaryRegistry = ModUtil::apiFunc('MUFiles', 'category', 'getPrimaryProperty', array('ot' => $vars['objectType']));
            $vars['catIds'] = array($primaryRegistry => array());
            // backwards compatibility
            if (isset($vars['catId'])) {
                $vars['catIds'][$primaryRegistry][] = $vars['catId'];
                unset($vars['catId']);
            }
        } elseif (!is_array($vars['catIds'])) {
            $vars['catIds'] = explode(',', $vars['catIds']);
        }
    
        ModUtil::initOOModule('MUFiles');
    
        $controllerHelper = new MUFiles_Util_Controller($this->serviceManager);
        $utilArgs = array('name' => 'list');
        if (!isset($vars['objectType']) || !in_array($vars['objectType'], $controllerHelper->getObjectTypes('block', $utilArgs))) {
            $vars['objectType'] = $controllerHelper->getDefaultObjectType('block', $utilArgs);
        }
    
        $objectType = $vars['objectType'];
    
        $entityClass = 'MUFiles_Entity_' . ucfirst($objectType);
        $entityManager = $this->serviceManager->getService('doctrine.entitymanager');
        $repository = $entityManager->getRepository($entityClass);
    
        $this->view->setCaching(Zikula_View::CACHE_ENABLED);
        // set cache id
        $component = 'MUFiles:' . ucfirst($objectType) . ':';
        $instance = '::';
        $accessLevel = ACCESS_READ;
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_COMMENT)) {
            $accessLevel = ACCESS_COMMENT;
        }
        if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
            $accessLevel = ACCESS_EDIT;
        }
        $this->view->setCacheId('view|ot_' . $objectType . '_sort_' . $vars['sorting'] . '_amount_' . $vars['amount'] . '_' . $accessLevel);
    
        $template = $this->getDisplayTemplate($vars);
    
        // if page is cached return cached content
        if ($this->view->is_cached($template)) {
            $blockinfo['content'] = $this->view->fetch($template);
            return BlockUtil::themeBlock($blockinfo);
        }
    
        // create query
        $where = $vars['filter'];
        $orderBy = $this->getSortParam($vars, $repository);
        $qb = $repository->genericBaseQuery($where, $orderBy);
    
        $properties = null;
        if (in_array($vars['objectType'], $this->categorisableObjectTypes)) {
            $properties = ModUtil::apiFunc('MUFiles', 'category', 'getAllProperties', array('ot' => $objectType));
        }
    
        // apply category filters
        if (in_array($objectType, $this->categorisableObjectTypes)) {
            if (is_array($vars['catIds']) && count($vars['catIds']) > 0) {
                $qb = ModUtil::apiFunc('MUFiles', 'category', 'buildFilterClauses', array('qb' => $qb, 'ot' => $objectType, 'catids' => $vars['catIds']));
            }
        }
    
        // get objects from database
        $currentPage = 1;
        $resultsPerPage = $vars['amount'];
        list($query, $count) = $repository->getSelectWherePaginatedQuery($qb, $currentPage, $resultsPerPage);
        $entities = $repository->retrieveCollectionResult($query, $orderBy, true);
    
        // assign block vars and fetched data
        $this->view->assign('vars', $vars)
                   ->assign('objectType', $objectType)
                   ->assign('items', $entities)
                   ->assign($repository->getAdditionalTemplateParameters('block'));
    
        // assign category properties
        $this->view->assign('properties', $properties);
    
        // set a block title
        if (empty($blockinfo['title'])) {
            $blockinfo['title'] = $this->__('MUFiles items');
        }
    
        $blockinfo['content'] = $this->view->fetch($template);;
    
        // return the block to the theme
        return BlockUtil::themeBlock($blockinfo);
    }
    
    /**
     * Returns the template used for output.
     *
     * @param array $vars List of block variables.
     *
     * @return string the template path.
     */
    protected function getDisplayTemplate($vars)
    {
        $templateFile = $vars['template'];
        if ($templateFile == 'custom') {
            $templateFile = $vars['customTemplate'];
        }
    
        $templateForObjectType = str_replace('itemlist_', 'itemlist_' . DataUtil::formatForOS($vars['objectType']) . '_', $templateFile);
    
        $template = '';
        if ($this->view->template_exists('contenttype/' . $templateForObjectType)) {
            $template = 'contenttype/' . $templateForObjectType;
        } elseif ($this->view->template_exists('block/' . $templateForObjectType)) {
            $template = 'block/' . $templateForObjectType;
        } elseif ($this->view->template_exists('contenttype/' . $templateFile)) {
            $template = 'contenttype/' . $templateFile;
        } elseif ($this->view->template_exists('block/' . $templateFile)) {
            $template = 'block/' . $templateFile;
        } else {
            $template = 'block/itemlist.tpl';
        }
    
        return $template;
    }
    
    /**
     * Determines the order by parameter for item selection.
     *
     * @param array               $vars       List of block variables.
     * @param Doctrine_Repository $repository The repository used for data fetching.
     *
     * @return string the sorting clause.
     */
    protected function getSortParam($vars, $repository)
    {
        if ($vars['sorting'] == 'random') {
            return 'RAND()';
        }
    
        $sortParam = '';
        if ($vars['sorting'] == 'newest') {
            $idFields = ModUtil::apiFunc('MUFiles', 'selection', 'getIdFields', array('ot' => $vars['objectType']));
            if (count($idFields) == 1) {
                $sortParam = $idFields[0] . ' DESC';
            } else {
                foreach ($idFields as $idField) {
                    if (!empty($sortParam)) {
                        $sortParam .= ', ';
                    }
                    $sortParam .= $idField . ' DESC';
                }
            }
        } elseif ($vars['sorting'] == 'default') {
            $sortParam = $repository->getDefaultSortingField() . ' ASC';
        }
    
        return $sortParam;
    }
    
    /**
     * Modify block settings.
     *
     * @param array $blockinfo the blockinfo structure
     *
     * @return string output of the block editing form.
     */
    public function modify($blockinfo)
    {
        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
    
        // set default values for all params which are not properly set
        if (!isset($vars['objectType']) || empty($vars['objectType'])) {
            $vars['objectType'] = 'collection';
        }
        if (!isset($vars['sorting']) || empty($vars['sorting'])) {
            $vars['sorting'] = 'default';
        }
        if (!isset($vars['amount']) || !is_numeric($vars['amount'])) {
            $vars['amount'] = 5;
        }
        if (!isset($vars['template'])) {
            $vars['template'] = 'itemlist_' . DataUtil::formatForOS($vars['objectType']) . '_display.tpl';
        }
        if (!isset($vars['customTemplate'])) {
            $vars['customTemplate'] = '';
        }
        if (!isset($vars['filter'])) {
            $vars['filter'] = '';
        }
    
        if (!isset($vars['catIds'])) {
            $primaryRegistry = ModUtil::apiFunc('MUFiles', 'category', 'getPrimaryProperty', array('ot' => $vars['objectType']));
            $vars['catIds'] = array($primaryRegistry => array());
            // backwards compatibility
            if (isset($vars['catId'])) {
                $vars['catIds'][$primaryRegistry][] = $vars['catId'];
                unset($vars['catId']);
            }
        } elseif (!is_array($vars['catIds'])) {
            $vars['catIds'] = explode(',', $vars['catIds']);
        }
    
        $this->view->setCaching(Zikula_View::CACHE_DISABLED);
    
        // assign the appropriate values
        $this->view->assign($vars);
    
        // clear the block cache
        $this->view->clear_cache('block/itemlist_display.tpl');
        $this->view->clear_cache('block/itemlist_' . DataUtil::formatForOS($vars['objectType']) . '_display.tpl');
        $this->view->clear_cache('block/itemlist_display_description.tpl');
        $this->view->clear_cache('block/itemlist_' . DataUtil::formatForOS($vars['objectType']) . '_display_description.tpl');
    
        // Return the output that has been generated by this function
        return $this->view->fetch('block/itemlist_modify.tpl');
    }
    
    /**
     * Update block settings.
     *
     * @param array $blockinfo the blockinfo structure
     *
     * @return array the modified blockinfo structure.
     */
    public function update($blockinfo)
    {
        // Get current content
        $vars = BlockUtil::varsFromContent($blockinfo['content']);
    
        $vars['objectType'] = $this->request->request->filter('objecttype', 'collection', FILTER_SANITIZE_STRING);
        $vars['sorting'] = $this->request->request->filter('sorting', 'default', FILTER_SANITIZE_STRING);
        $vars['amount'] = (int) $this->request->request->filter('amount', 5, FILTER_VALIDATE_INT);
        $vars['template'] = $this->request->request->get('template', '');
        $vars['customTemplate'] = $this->request->request->get('customtemplate', '');
        $vars['filter'] = $this->request->request->get('filter', '');
    
        $controllerHelper = new MUFiles_Util_Controller($this->serviceManager);
        if (!in_array($vars['objectType'], $controllerHelper->getObjectTypes('block'))) {
            $vars['objectType'] = $controllerHelper->getDefaultObjectType('block');
        }
    
        $primaryRegistry = ModUtil::apiFunc('MUFiles', 'category', 'getPrimaryProperty', array('ot' => $vars['objectType']));
        $vars['catIds'] = array($primaryRegistry => array());
        if (in_array($vars['objectType'], $this->categorisableObjectTypes)) {
            $vars['catIds'] = ModUtil::apiFunc('MUFiles', 'category', 'retrieveCategoriesFromRequest', array('ot' => $vars['objectType']));
        }
    
        // write back the new contents
        $blockinfo['content'] = BlockUtil::varsToContent($vars);
    
        // clear the block cache
        $this->view->clear_cache('block/itemlist_display.tpl');
        $this->view->clear_cache('block/itemlist_' . ucfirst($vars['objectType']) . '_display.tpl');
        $this->view->clear_cache('block/itemlist_display_description.tpl');
        $this->view->clear_cache('block/itemlist_' . ucfirst($vars['objectType']) . '_display_description.tpl');
    
        return $blockinfo;
    }
}
