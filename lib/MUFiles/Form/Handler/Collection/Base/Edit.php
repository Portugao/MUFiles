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
 * This handler class handles the page events of editing forms.
 * It aims on the collection object type.
 *
 * More documentation is provided in the parent class.
 */
class MUFiles_Form_Handler_Collection_Base_Edit extends MUFiles_Form_Handler_Common_Edit
{
    /**
     * Pre-initialise hook.
     *
     * @return void
     */
    public function preInitialize()
    {
        parent::preInitialize();
    
        $this->objectType = 'collection';
        $this->objectTypeCapital = 'Collection';
        $this->objectTypeLower = 'collection';
    
        $this->hasPageLockSupport = true;
        $this->hasCategories = true;
        // array with list fields and multiple flags
        $this->listFields = array('workflowState' => false);
    }

    /**
     * Initialize form handler.
     *
     * This method takes care of all necessary initialisation of our data and form states.
     *
     * @param Zikula_Form_View $view The form view instance.
     *
     * @return boolean False in case of initialization errors, otherwise true.
     */
    public function initialize(Zikula_Form_View $view)
    {
        parent::initialize($view);
    
        if ($this->mode == 'create') {
            $modelHelper = new MUFiles_Util_Model($this->view->getServiceManager());
            if (!$modelHelper->canBeCreated($this->objectType)) {
                LogUtil::registerError($this->__('Sorry, but you can not create the collection yet as other items are required which must be created before!'));
    
                return $this->view->redirect($this->getRedirectUrl(null));
            }
        }
    
        $entity = $this->entityRef;
        
        // assign identifiers of predefined incoming relationships
        // editable relation, we store the id and assign it now to show it in UI
        $this->relationPresets['parent'] = FormUtil::getPassedValue('parent', '', 'GET');
        if (!empty($this->relationPresets['parent'])) {
            $relObj = ModUtil::apiFunc($this->name, 'selection', 'getEntity', array('ot' => 'collection', 'id' => $this->relationPresets['parent']));
            if ($relObj != null) {
                $relObj->addChildren($entity);
            }
        }
    
        // save entity reference for later reuse
        $this->entityRef = $entity;
    
        $entityData = $entity->toArray();
    
        if (count($this->listFields) > 0) {
            $helper = new MUFiles_Util_ListEntries($this->view->getServiceManager());
    
            foreach ($this->listFields as $listField => $isMultiple) {
                $entityData[$listField . 'Items'] = $helper->getEntries($this->objectType, $listField);
                if ($isMultiple) {
                    $entityData[$listField] = $helper->extractMultiList($entityData[$listField]);
                }
            }
        }
    
        // assign data to template as array (makes translatable support easier)
        $this->view->assign($this->objectTypeLower, $entityData);
    
        if ($this->mode == 'edit') {
            // assign formatted title
            $this->view->assign('formattedEntityTitle', $entity->getTitleFromDisplayPattern());
        }
    
        // everything okay, no initialization errors occured
        return true;
    }

    /**
     * Post-initialise hook.
     *
     * @return void
     */
    public function postInitialize()
    {
        parent::postInitialize();
    }

    /**
     * Get list of allowed redirect codes.
     *
     * @return array list of possible redirect codes
     */
    protected function getRedirectCodes()
    {
        $codes = parent::getRedirectCodes();
    
        return $codes;
    }

    /**
     * Get the default redirect url. Required if no returnTo parameter has been supplied.
     * This method is called in handleCommand so we know which command has been performed.
     *
     * @param array  $args List of arguments.
     *
     * @return string The default redirect url.
     */
    protected function getDefaultReturnUrl($args)
    {
        // redirect to the list of collections
        $viewArgs = array('ot' => $this->objectType);
        $url = ModUtil::url($this->name, FormUtil::getPassedValue('type', 'user', 'GETPOST'), 'view', $viewArgs);
    
        return $url;
    }

    /**
     * Command event handler.
     *
     * This event handler is called when a command is issued by the user.
     *
     * @param Zikula_Form_View $view The form view instance.
     * @param array            $args Additional arguments.
     *
     * @return mixed Redirect or false on errors.
     */
    public function handleCommand(Zikula_Form_View $view, &$args)
    {
        $result = parent::handleCommand($view, $args);
        if ($result === false) {
            return $result;
        }
    
        return $this->view->redirect($this->getRedirectUrl($args));
    }
    
    /**
     * Get success or error message for default operations.
     *
     * @param Array   $args    Arguments from handleCommand method.
     * @param Boolean $success Becomes true if this is a success, false for default error.
     *
     * @return String desired status or error message.
     */
    protected function getDefaultMessage($args, $success = false)
    {
        if ($success !== true) {
            return parent::getDefaultMessage($args, $success);
        }
    
        $message = '';
        switch ($args['commandName']) {
            case 'submit':
                        if ($this->mode == 'create') {
                            $message = $this->__('Done! Collection created.');
                        } else {
                            $message = $this->__('Done! Collection updated.');
                        }
                        break;
            case 'delete':
                        $message = $this->__('Done! Collection deleted.');
                        break;
            default:
                        $message = $this->__('Done! Collection updated.');
                        break;
        }
    
        return $message;
    }

    /**
     * This method executes a certain workflow action.
     *
     * @param Array $args Arguments from handleCommand method.
     *
     * @return bool Whether everything worked well or not.
     */
    public function applyAction(array $args = array())
    {
        // get treated entity reference from persisted member var
        $entity = $this->entityRef;
    
        $action = $args['commandName'];
    
        try {
            // execute the workflow action
            $workflowHelper = new MUFiles_Util_Workflow($this->view->getServiceManager());
            $success = $workflowHelper->executeAction($entity, $action);
        } catch(\Exception $e) {
            LogUtil::registerError($this->__f('Sorry, but an unknown error occured during the %s action. Please apply the changes again!', array($action)));
        }
    
        $this->addDefaultMessage($args, $success);
    
        if ($success && $this->mode == 'create') {
            // store new identifier
            foreach ($this->idFields as $idField) {
                $this->idValues[$idField] = $entity[$idField];
            }
        }
    
    
        return $success;
    }

    /**
     * Get url to redirect to.
     *
     * @param array  $args List of arguments.
     *
     * @return string The redirect url.
     */
    protected function getRedirectUrl($args)
    {
        if ($this->inlineUsage == true) {
            $urlArgs = array('idPrefix'    => $this->idPrefix,
                             'commandName' => $args['commandName']);
            $urlArgs = $this->addIdentifiersToUrlArgs($urlArgs);
    
            // inline usage, return to special function for closing the Zikula.UI.Window instance
            return ModUtil::url($this->name, FormUtil::getPassedValue('type', 'user', 'GETPOST'), 'handleInlineRedirect', $urlArgs);
        }
    
        if ($this->repeatCreateAction) {
            return $this->repeatReturnUrl;
        }
    
        // normal usage, compute return url from given redirect code
        if (!in_array($this->returnTo, $this->getRedirectCodes())) {
            // invalid return code, so return the default url
            return $this->getDefaultReturnUrl($args);
        }
    
        // parse given redirect code and return corresponding url
        switch ($this->returnTo) {
            case 'admin':
                return ModUtil::url($this->name, 'admin', 'main');
            case 'adminView':
                return ModUtil::url($this->name, 'admin', 'view', array('ot' => $this->objectType));
            case 'adminDisplay':
                if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
                    $urlArgs = $this->addIdentifiersToUrlArgs();
                    $urlArgs['ot'] = $this->objectType;
                    return ModUtil::url($this->name, 'admin', 'display', $urlArgs);
                }
                return $this->getDefaultReturnUrl($args);
            case 'user':
                return ModUtil::url($this->name, 'user', 'main');
            case 'userView':
                return ModUtil::url($this->name, 'user', 'view', array('ot' => $this->objectType));
            case 'userDisplay':
                if ($args['commandName'] != 'delete' && !($this->mode == 'create' && $args['commandName'] == 'cancel')) {
                    $urlArgs = $this->addIdentifiersToUrlArgs();
                    $urlArgs['ot'] = $this->objectType;
                    return ModUtil::url($this->name, 'user', 'display', $urlArgs);
                }
                return $this->getDefaultReturnUrl($args);
            default:
                return $this->getDefaultReturnUrl($args);
        }
    }
}
