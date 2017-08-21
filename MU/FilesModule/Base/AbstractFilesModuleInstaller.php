<?php
/**
 * Files.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link http://zikula.org
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\FilesModule\Base;

use Doctrine\DBAL\Connection;
use RuntimeException;
use Zikula\Core\AbstractExtensionInstaller;
use Zikula\CategoriesModule\Entity\CategoryRegistryEntity;

/**
 * Installer base class.
 */
abstract class AbstractFilesModuleInstaller extends AbstractExtensionInstaller
{
    /**
     * Install the MUFilesModule application.
     *
     * @return boolean True on success, or false
     *
     * @throws RuntimeException Thrown if database tables can not be created or another error occurs
     */
    public function install()
    {
        $logger = $this->container->get('logger');
        $userName = $this->container->get('zikula_users_module.current_user')->get('uname');
    
        // Check if upload directories exist and if needed create them
        try {
            $container = $this->container;
            $uploadHelper = new \MU\FilesModule\Helper\UploadHelper($container->get('translator.default'), $container->get('session'), $container->get('logger'), $container->get('zikula_users_module.current_user'), $container->get('zikula_extensions_module.api.variable'), $container->getParameter('datadir'));
            $uploadHelper->checkAndCreateAllUploadFolders();
        } catch (\Exception $exception) {
            $this->addFlash('error', $exception->getMessage());
            $logger->error('{app}: User {user} could not create upload folders during installation. Error details: {errorMessage}.', ['app' => 'MUFilesModule', 'user' => $userName, 'errorMessage' => $exception->getMessage()]);
        
            return false;
        }
        // create all tables from according entity definitions
        try {
            $this->schemaTool->create($this->listEntityClasses());
        } catch (\Exception $exception) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
            $logger->error('{app}: Could not create the database tables during installation. Error details: {errorMessage}.', ['app' => 'MUFilesModule', 'errorMessage' => $exception->getMessage()]);
    
            return false;
        }
    
        // set up all our vars with initial values
        $this->setVar('allowedExtensions', 'pdf, doc, docx, odt');
        $this->setVar('maxSize', '200k');
        $this->setVar('onlyParent', false);
        $this->setVar('specialCollectionMenue', false);
        $this->setVar('moderationGroupForCollections', '2');
        $this->setVar('moderationGroupForFiles', '2');
        $this->setVar('collectionEntriesPerPage', '10');
        $this->setVar('linkOwnCollectionsOnAccountPage', true);
        $this->setVar('fileEntriesPerPage', '10');
        $this->setVar('linkOwnFilesOnAccountPage', true);
        $this->setVar('enabledFinderTypes', [ 'collection' ,  'file' ]);
    
        $categoryRegistryIdsPerEntity = [];
    
        // add default entry for category registry (property named Main)
        $categoryHelper = new \MU\FilesModule\Helper\CategoryHelper(
            $this->container->get('translator.default'),
            $this->container->get('request_stack'),
            $logger,
            $this->container->get('zikula_users_module.current_user'),
            $this->container->get('zikula_categories_module.category_registry_repository'),
            $this->container->get('zikula_categories_module.api.category_permission')
        );
        $categoryGlobal = $this->container->get('zikula_categories_module.category_repository')->findOneBy(['name' => 'Global']);
    
        $registry = new CategoryRegistryEntity();
        $registry->setModname('MUFilesModule');
        $registry->setEntityname('CollectionEntity');
        $registry->setProperty($categoryHelper->getPrimaryProperty('Collection'));
        $registry->setCategory($categoryGlobal);
    
        try {
            $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
            $entityManager->persist($registry);
            $entityManager->flush();
        } catch (\Exception $exception) {
            $this->addFlash('error', $this->__f('Error! Could not create a category registry for the %entity% entity.', ['%entity%' => 'collection']));
            $logger->error('{app}: User {user} could not create a category registry for {entities} during installation. Error details: {errorMessage}.', ['app' => 'MUFilesModule', 'user' => $userName, 'entities' => 'collections', 'errorMessage' => $exception->getMessage()]);
        }
        $categoryRegistryIdsPerEntity['collection'] = $registry->getId();
    
        // initialisation successful
        return true;
    }
    
    /**
     * Upgrade the MUFilesModule application from an older version.
     *
     * If the upgrade fails at some point, it returns the last upgraded version.
     *
     * @param integer $oldVersion Version to upgrade from
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables can not be updated
     */
    public function upgrade($oldVersion)
    {
    /*
        $logger = $this->container->get('logger');
    
        // Upgrade dependent on old version number
        switch ($oldVersion) {
            case '1.0.0':
                // do something
                // ...
                // update the database schema
                try {
                    $this->schemaTool->update($this->listEntityClasses());
                } catch (\Exception $exception) {
                    $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
                    $logger->error('{app}: Could not update the database tables during the upgrade. Error details: {errorMessage}.', ['app' => 'MUFilesModule', 'errorMessage' => $exception->getMessage()]);
    
                    return false;
                }
        }
    
        // Note there are several helpers available for making migrating your extension from Zikula 1.3 to 1.4 easier.
        // The following convenience methods are each responsible for a single aspect of upgrading to Zikula 1.4.x.
    
        // here is a possible usage example
        // of course 1.2.3 should match the number you used for the last stable 1.3.x module version.
        /* if ($oldVersion = '1.2.3') {
            // rename module for all modvars
            $this->updateModVarsTo14();
            
            // update extension information about this app
            $this->updateExtensionInfoFor14();
            
            // rename existing permission rules
            $this->renamePermissionsFor14();
            
            // rename existing category registries
            $this->renameCategoryRegistriesFor14();
            
            // rename all tables
            $this->renameTablesFor14();
            
            // remove event handler definitions from database
            $this->dropEventHandlersFromDatabase();
            
            // update module name in the hook tables
            $this->updateHookNamesFor14();
            
            // update module name in the workflows table
            $this->updateWorkflowsFor14();
        } * /
    
        // remove obsolete persisted hooks from the database
        //$this->hookApi->uninstallSubscriberHooks($this->bundle->getMetaData());
    */
    
        // update successful
        return true;
    }
    
    /**
     * Renames the module name for variables in the module_vars table.
     */
    protected function updateModVarsTo14()
    {
        $conn = $this->getConnection();
        $conn->update('module_vars', ['modname' => 'MUFilesModule'], ['modname' => 'Files']);
    }
    
    /**
     * Renames this application in the core's extensions table.
     */
    protected function updateExtensionInfoFor14()
    {
        $conn = $this->getConnection();
        $conn->update('modules', ['name' => 'MUFilesModule', 'directory' => 'MU/FilesModule'], ['name' => 'Files']);
    }
    
    /**
     * Renames all permission rules stored for this app.
     */
    protected function renamePermissionsFor14()
    {
        $conn = $this->getConnection();
        $componentLength = strlen('Files') + 1;
    
        $conn->executeQuery("
            UPDATE group_perms
            SET component = CONCAT('MUFilesModule', SUBSTRING(component, $componentLength))
            WHERE component LIKE 'Files%';
        ");
    }
    
    /**
     * Renames all category registries stored for this app.
     */
    protected function renameCategoryRegistriesFor14()
    {
        $conn = $this->getConnection();
        $componentLength = strlen('Files') + 1;
    
        $conn->executeQuery("
            UPDATE categories_registry
            SET modname = CONCAT('MUFilesModule', SUBSTRING(modname, $componentLength))
            WHERE modname LIKE 'Files%';
        ");
    }
    
    /**
     * Renames all (existing) tables of this app.
     */
    protected function renameTablesFor14()
    {
        $conn = $this->getConnection();
    
        $oldPrefix = 'files_';
        $oldPrefixLength = strlen($oldPrefix);
        $newPrefix = 'mu_files_';
    
        $sm = $conn->getSchemaManager();
        $tables = $sm->listTables();
        foreach ($tables as $table) {
            $tableName = $table->getName();
            if (substr($tableName, 0, $oldPrefixLength) != $oldPrefix) {
                continue;
            }
    
            $newTableName = str_replace($oldPrefix, $newPrefix, $tableName);
    
            $conn->executeQuery("
                RENAME TABLE $tableName
                TO $newTableName;
            ");
        }
    }
    
    /**
     * Removes event handlers from database as they are now described by service definitions and managed by dependency injection.
     */
    protected function dropEventHandlersFromDatabase()
    {
        \EventUtil::unregisterPersistentModuleHandlers('Files');
    }
    
    /**
     * Updates the module name in the hook tables.
     */
    protected function updateHookNamesFor14()
    {
        $conn = $this->getConnection();
    
        $conn->update('hook_area', ['owner' => 'MUFilesModule'], ['owner' => 'Files']);
    
        $componentLength = strlen('subscriber.files') + 1;
        $conn->executeQuery("
            UPDATE hook_area
            SET areaname = CONCAT('subscriber.mufilesmodule', SUBSTRING(areaname, $componentLength))
            WHERE areaname LIKE 'subscriber.files%';
        ");
    
        $conn->update('hook_binding', ['sowner' => 'MUFilesModule'], ['sowner' => 'Files']);
    
        $conn->update('hook_runtime', ['sowner' => 'MUFilesModule'], ['sowner' => 'Files']);
    
        $componentLength = strlen('files') + 1;
        $conn->executeQuery("
            UPDATE hook_runtime
            SET eventname = CONCAT('mufilesmodule', SUBSTRING(eventname, $componentLength))
            WHERE eventname LIKE 'files%';
        ");
    
        $conn->update('hook_subscriber', ['owner' => 'MUFilesModule'], ['owner' => 'Files']);
    
        $componentLength = strlen('files') + 1;
        $conn->executeQuery("
            UPDATE hook_subscriber
            SET eventname = CONCAT('mufilesmodule', SUBSTRING(eventname, $componentLength))
            WHERE eventname LIKE 'files%';
        ");
    }
    
    /**
     * Updates the module name in the workflows table.
     */
    protected function updateWorkflowsFor14()
    {
        $conn = $this->getConnection();
        $conn->update('workflows', ['module' => 'MUFilesModule'], ['module' => 'Files']);
        $conn->update('workflows', ['obj_table' => 'CollectionEntity'], ['module' => 'MUFilesModule', 'obj_table' => 'collection']);
        $conn->update('workflows', ['obj_table' => 'FileEntity'], ['module' => 'MUFilesModule', 'obj_table' => 'file']);
    }
    
    /**
     * Returns connection to the database.
     *
     * @return Connection the current connection
     */
    protected function getConnection()
    {
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $connection = $entityManager->getConnection();
    
        return $connection;
    }
    
    /**
     * Returns the name of the default system database.
     *
     * @return string the database name
     */
    protected function getDbName()
    {
        return $this->container->getParameter('database_name');
    }
    
    /**
     * Uninstall MUFilesModule.
     *
     * @return boolean True on success, false otherwise
     *
     * @throws RuntimeException Thrown if database tables or stored workflows can not be removed
     */
    public function uninstall()
    {
        $logger = $this->container->get('logger');
    
        try {
            $this->schemaTool->drop($this->listEntityClasses());
        } catch (\Exception $exception) {
            $this->addFlash('error', $this->__('Doctrine Exception') . ': ' . $exception->getMessage());
            $logger->error('{app}: Could not remove the database tables during uninstallation. Error details: {errorMessage}.', ['app' => 'MUFilesModule', 'errorMessage' => $exception->getMessage()]);
    
            return false;
        }
    
        // remove all module vars
        $this->delVars();
    
        // remove category registry entries
        $entityManager = $this->container->get('doctrine.orm.default_entity_manager');
        $registries = $this->container->get('zikula_categories_module.category_registry_repository')->findBy(['modname' => 'MUFilesModule']);
        foreach ($registries as $registry) {
            $entityManager->remove($registry);
        }
        $entityManager->flush();
    
        // remind user about upload folders not being deleted
        $uploadPath = $this->container->getParameter('datadir') . '/MUFilesModule/';
        $this->addFlash('status', $this->__f('The upload directories at "%path%" can be removed manually.', ['%path%' => $uploadPath]));
    
        // uninstallation successful
        return true;
    }
    
    /**
     * Build array with all entity classes for MUFilesModule.
     *
     * @return array list of class names
     */
    protected function listEntityClasses()
    {
        $classNames = [];
        $classNames[] = 'MU\FilesModule\Entity\CollectionEntity';
        $classNames[] = 'MU\FilesModule\Entity\CollectionCategoryEntity';
        $classNames[] = 'MU\FilesModule\Entity\FileEntity';
        $classNames[] = 'MU\FilesModule\Entity\HookAssignmentEntity';
    
        return $classNames;
    }
}
