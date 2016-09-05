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
 * Installer base class.
 */
class MUFiles_Base_Installer extends Zikula_AbstractInstaller
{
    /**
     * Install the MUFiles application.
     *
     * @return boolean True on success, or false.
     */
    public function install()
    {
        // Check if upload directories exist and if needed create them
        try {
            $controllerHelper = new MUFiles_Util_Controller($this->serviceManager);
            $controllerHelper->checkAndCreateAllUploadFolders();
        } catch (\Exception $e) {
            return LogUtil::registerError($e->getMessage());
        }
        // create all tables from according entity definitions
        try {
            DoctrineHelper::createSchema($this->entityManager, $this->listEntityClasses());
        } catch (\Exception $e) {
            if (System::isDevelopmentMode()) {
                return LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
            }
            $returnMessage = $this->__f('An error was encountered while creating the tables for the %s extension.', array($this->name));
            if (!System::isDevelopmentMode()) {
                $returnMessage .= ' ' . $this->__('Please enable the development mode by editing the /config/config.php file in order to reveal the error details.');
            }
            return LogUtil::registerError($returnMessage);
        }
    
        // set up all our vars with initial values
        $this->setVar('allowedExtensions', 'pdf,doc,docx,odt');
        $this->setVar('maxSize', 102400);
        $this->setVar('moderationGroupForCollections', 2);
        $this->setVar('moderationGroupForFiles', 2);
        $this->setVar('itemsPerPage', 10);
        $this->setVar('itemsPerPageBackend', 10);
        $this->setVar('onlyParent', false);
        $this->setVar('specialCollectionMenue', false);
    
        $categoryRegistryIdsPerEntity = array();
    
        // add default entry for category registry (property named Main)
        include_once 'modules/MUFiles/lib/MUFiles/Api/Base/Category.php';
        include_once 'modules/MUFiles/lib/MUFiles/Api/Category.php';
        $categoryApi = new MUFiles_Api_Category($this->serviceManager);
        $categoryGlobal = CategoryUtil::getCategoryByPath('/__SYSTEM__/Modules/Global');
    
        $registryData = array();
        $registryData['modname'] = $this->name;
        $registryData['table'] = 'Collection';
        $registryData['property'] = $categoryApi->getPrimaryProperty(array('ot' => 'Collection'));
        $registryData['category_id'] = $categoryGlobal['id'];
        $registryData['id'] = false;
        if (!DBUtil::insertObject($registryData, 'categories_registry')) {
            LogUtil::registerError($this->__f('Error! Could not create a category registry for the %s entity.', array('collection')));
        }
        $categoryRegistryIdsPerEntity['collection'] = $registryData['id'];
    
        // create the default data
        $this->createDefaultData($categoryRegistryIdsPerEntity);
    
        // register persistent event handlers
        $this->registerPersistentEventHandlers();
    
        // register hook subscriber bundles
        HookUtil::registerSubscriberBundles($this->version->getHookSubscriberBundles());
        
    
        // initialisation successful
        return true;
    }
    
    /**
     * Upgrade the MUFiles application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from.
     *
     * @return boolean True on success, false otherwise.
     */
    public function upgrade($oldVersion)
    {
    /*
        // Upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.0.0':
                // do something
                // ...
                // update the database schema
                try {
                    DoctrineHelper::updateSchema($this->entityManager, $this->listEntityClasses());
                } catch (\Exception $e) {
                    if (System::isDevelopmentMode()) {
                        return LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
                    }
                    return LogUtil::registerError($this->__f('An error was encountered while updating tables for the %s extension.', array($this->getName())));
                }
        }
    */
    
        // update successful
        return true;
    }
    
    /**
     * Uninstall MUFiles.
     *
     * @return boolean True on success, false otherwise.
     */
    public function uninstall()
    {
        // delete stored object workflows
        $result = Zikula_Workflow_Util::deleteWorkflowsForModule($this->getName());
        if ($result === false) {
            return LogUtil::registerError($this->__f('An error was encountered while removing stored object workflows for the %s extension.', array($this->getName())));
        }
    
        try {
            DoctrineHelper::dropSchema($this->entityManager, $this->listEntityClasses());
        } catch (\Exception $e) {
            if (System::isDevelopmentMode()) {
                return LogUtil::registerError($this->__('Doctrine Exception: ') . $e->getMessage());
            }
            return LogUtil::registerError($this->__f('An error was encountered while dropping tables for the %s extension.', array($this->name)));
        }
    
        // unregister persistent event handlers
        EventUtil::unregisterPersistentModuleHandlers($this->name);
    
        // unregister hook subscriber bundles
        HookUtil::unregisterSubscriberBundles($this->version->getHookSubscriberBundles());
        
    
        // remove all module vars
        $this->delVars();
    
        // remove category registry entries
        ModUtil::dbInfoLoad('Categories');
        DBUtil::deleteWhere('categories_registry', 'modname = \'' . $this->name . '\'');
    
        // remove all thumbnails
        $manager = $this->getServiceManager()->getService('systemplugin.imagine.manager');
        $manager->setModule($this->name);
        $manager->cleanupModuleThumbs();
    
        // remind user about upload folders not being deleted
        $uploadPath = FileUtil::getDataDirectory() . '/' . $this->name . '/';
        LogUtil::registerStatus($this->__f('The upload directories at [%s] can be removed manually.', $uploadPath));
    
        // uninstallation successful
        return true;
    }
    
    /**
     * Build array with all entity classes for MUFiles.
     *
     * @return array list of class names.
     */
    protected function listEntityClasses()
    {
        $classNames = array();
        $classNames[] = 'MUFiles_Entity_Collection';
        $classNames[] = 'MUFiles_Entity_CollectionCategory';
        $classNames[] = 'MUFiles_Entity_File';
        $classNames[] = 'MUFiles_Entity_Hookobject';
    
        return $classNames;
    }
    
    /**
     * Create the default data for MUFiles.
     *
     * @param array $categoryRegistryIdsPerEntity List of category registry ids.
     *
     * @return void
     */
    protected function createDefaultData($categoryRegistryIdsPerEntity)
    {
        $entityClass = 'MUFiles_Entity_Collection';
        $this->entityManager->getRepository($entityClass)->truncateTable();
        $entityClass = 'MUFiles_Entity_File';
        $this->entityManager->getRepository($entityClass)->truncateTable();
        $entityClass = 'MUFiles_Entity_Hookobject';
        $this->entityManager->getRepository($entityClass)->truncateTable();
    }
    
    /**
     * Register persistent event handlers.
     * These are listeners for external events of the core and other modules.
     */
    protected function registerPersistentEventHandlers()
    {
        // core -> 
        EventUtil::registerPersistentModuleHandler('MUFiles', 'api.method_not_found', array('MUFiles_Listener_Core', 'apiMethodNotFound'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'core.preinit', array('MUFiles_Listener_Core', 'preInit'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'core.init', array('MUFiles_Listener_Core', 'init'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'core.postinit', array('MUFiles_Listener_Core', 'postInit'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'controller.method_not_found', array('MUFiles_Listener_Core', 'controllerMethodNotFound'));
    
        // front controller -> MUFiles_Listener_FrontController
        EventUtil::registerPersistentModuleHandler('MUFiles', 'frontcontroller.predispatch', array('MUFiles_Listener_FrontController', 'preDispatch'));
    
        // installer -> MUFiles_Listener_Installer
        EventUtil::registerPersistentModuleHandler('MUFiles', 'installer.module.installed', array('MUFiles_Listener_Installer', 'moduleInstalled'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'installer.module.upgraded', array('MUFiles_Listener_Installer', 'moduleUpgraded'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'installer.module.uninstalled', array('MUFiles_Listener_Installer', 'moduleUninstalled'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'installer.subscriberarea.uninstalled', array('MUFiles_Listener_Installer', 'subscriberAreaUninstalled'));
    
        // modules -> MUFiles_Listener_ModuleDispatch
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module_dispatch.postloadgeneric', array('MUFiles_Listener_ModuleDispatch', 'postLoadGeneric'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module_dispatch.preexecute', array('MUFiles_Listener_ModuleDispatch', 'preExecute'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module_dispatch.postexecute', array('MUFiles_Listener_ModuleDispatch', 'postExecute'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module_dispatch.custom_classname', array('MUFiles_Listener_ModuleDispatch', 'customClassname'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module_dispatch.service_links', array('MUFiles_Listener_ModuleDispatch', 'serviceLinks'));
    
        // mailer -> MUFiles_Listener_Mailer
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.mailer.api.sendmessage', array('MUFiles_Listener_Mailer', 'sendMessage'));
    
        // page -> MUFiles_Listener_Page
        EventUtil::registerPersistentModuleHandler('MUFiles', 'pageutil.addvar_filter', array('MUFiles_Listener_Page', 'pageutilAddvarFilter'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'system.outputfilter', array('MUFiles_Listener_Page', 'systemOutputfilter'));
    
        // errors -> MUFiles_Listener_Errors
        EventUtil::registerPersistentModuleHandler('MUFiles', 'setup.errorreporting', array('MUFiles_Listener_Errors', 'setupErrorReporting'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'systemerror', array('MUFiles_Listener_Errors', 'systemError'));
    
        // theme -> MUFiles_Listener_Theme
        EventUtil::registerPersistentModuleHandler('MUFiles', 'theme.preinit', array('MUFiles_Listener_Theme', 'preInit'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'theme.init', array('MUFiles_Listener_Theme', 'init'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'theme.load_config', array('MUFiles_Listener_Theme', 'loadConfig'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'theme.prefetch', array('MUFiles_Listener_Theme', 'preFetch'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'theme.postfetch', array('MUFiles_Listener_Theme', 'postFetch'));
    
        // view -> MUFiles_Listener_View
        EventUtil::registerPersistentModuleHandler('MUFiles', 'view.init', array('MUFiles_Listener_View', 'init'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'view.postfetch', array('MUFiles_Listener_View', 'postFetch'));
    
        // user login -> MUFiles_Listener_UserLogin
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.ui.login.started', array('MUFiles_Listener_UserLogin', 'started'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.ui.login.veto', array('MUFiles_Listener_UserLogin', 'veto'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.ui.login.succeeded', array('MUFiles_Listener_UserLogin', 'succeeded'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.ui.login.failed', array('MUFiles_Listener_UserLogin', 'failed'));
    
        // user logout -> MUFiles_Listener_UserLogout
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.ui.logout.succeeded', array('MUFiles_Listener_UserLogout', 'succeeded'));
    
        // user -> MUFiles_Listener_User
        EventUtil::registerPersistentModuleHandler('MUFiles', 'user.gettheme', array('MUFiles_Listener_User', 'getTheme'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'user.account.create', array('MUFiles_Listener_User', 'create'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'user.account.update', array('MUFiles_Listener_User', 'update'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'user.account.delete', array('MUFiles_Listener_User', 'delete'));
    
        // registration -> MUFiles_Listener_UserRegistration
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.ui.registration.started', array('MUFiles_Listener_UserRegistration', 'started'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.ui.registration.succeeded', array('MUFiles_Listener_UserRegistration', 'succeeded'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.ui.registration.failed', array('MUFiles_Listener_UserRegistration', 'failed'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'user.registration.create', array('MUFiles_Listener_UserRegistration', 'create'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'user.registration.update', array('MUFiles_Listener_UserRegistration', 'update'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'user.registration.delete', array('MUFiles_Listener_UserRegistration', 'delete'));
    
        // users module -> MUFiles_Listener_Users
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.users.config.updated', array('MUFiles_Listener_Users', 'configUpdated'));
    
        // group -> MUFiles_Listener_Group
        EventUtil::registerPersistentModuleHandler('MUFiles', 'group.create', array('MUFiles_Listener_Group', 'create'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'group.update', array('MUFiles_Listener_Group', 'update'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'group.delete', array('MUFiles_Listener_Group', 'delete'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'group.adduser', array('MUFiles_Listener_Group', 'addUser'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'group.removeuser', array('MUFiles_Listener_Group', 'removeUser'));
    
        // special purposes and 3rd party api support -> MUFiles_Listener_ThirdParty
        EventUtil::registerPersistentModuleHandler('MUFiles', 'get.pending_content', array('MUFiles_Listener_ThirdParty', 'pendingContentListener'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.content.gettypes', array('MUFiles_Listener_ThirdParty', 'contentGetTypes'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'module.scribite.editorhelpers', array('MUFiles_Listener_ThirdParty', 'getEditorHelpers'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'moduleplugin.tinymce.externalplugins', array('MUFiles_Listener_ThirdParty', 'getTinyMcePlugins'));
        EventUtil::registerPersistentModuleHandler('MUFiles', 'moduleplugin.ckeditor.externalplugins', array('MUFiles_Listener_ThirdParty', 'getCKEditorPlugins'));
    }
}
