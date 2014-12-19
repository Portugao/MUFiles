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
 * Event handler implementation class for special purposes and 3rd party api support.
 */
class MUFiles_Listener_Base_ThirdParty
{
    /**
     * Listener for the 'get.pending_content' event with registration requests and
     * other submitted data pending approval.
     *
     * When a 'get.pending_content' event is fired, the Users module will respond with the
     * number of registration requests that are pending administrator approval. The number
     * pending may not equal the total number of outstanding registration requests, depending
     * on how the 'moderation_order' module configuration variable is set, and whether e-mail
     * address verification is required.
     * If the 'moderation_order' variable is set to require approval after e-mail verification
     * (and e-mail verification is also required) then the number of pending registration
     * requests will equal the number of registration requested that have completed the
     * verification process but have not yet been approved. For other values of
     * 'moderation_order', the number should equal the number of registration requests that
     * have not yet been approved, without regard to their current e-mail verification state.
     * If moderation of registrations is not enabled, then the value will always be 0.
     * In accordance with the 'get_pending_content' conventions, the count of pending
     * registrations, along with information necessary to access the detailed list, is
     * assemped as a {@link Zikula_Provider_AggregateItem} and added to the event
     * subject's collection.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function pendingContentListener(Zikula_Event $event)
    {
        $serviceManager = ServiceUtil::getManager();
        $workflowHelper = new MUFiles_Util_Workflow($this->serviceManager);
        
        $modname = 'MUFiles';
        $useJoins = false;
        
        $collection = new Zikula_Collection_Container($modname);
        $amounts = $workflowHelper->collectAmountOfModerationItems();
        if (count($amounts) > 0) {
            foreach ($amounts as $amountInfo) {
                $aggregateType = $amountInfo['aggregateType'];
                $description = $amountInfo['description'];
                $amount = $amountInfo['amount'];
                $viewArgs = array('ot' => $amountInfo['objectType'],
                                  'workflowState' => $amountInfo['state']);
                $aggregateItem = new Zikula_Provider_AggregateItem($aggregateType, $description, $amount, 'admin', 'view', $viewArgs);
                $collection->add($aggregateItem);
            }
        
            // add collected items for pending content
            if ($collection->count() > 0) {
                $event->getSubject()->add($collection);
            }
        }
    }
    
    /**
     * Listener for the `module.content.gettypes` event.
     *
     * This event occurs when the Content module is 'searching' for Content plugins.
     * The subject is an instance of Content_Types.
     * You can register custom content types as well as custom layout types.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function contentGetTypes(Zikula_Event $event)
    {
        // intended is using the add() method to add a plugin like below
        $types = $event->getSubject();
        
        
        // plugin for showing a single item
        $types->add('MUFiles_ContentType_Item');
        
        // plugin for showing a list of multiple items
        $types->add('MUFiles_ContentType_ItemList');
    }
    
    /**
     * Listener for the `module.scribite.editorhelpers` event.
     *
     * This occurs when Scribite adds pagevars to the editor page.
     * MUFiles will use this to add a javascript helper to add custom items.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function getEditorHelpers(Zikula_Event $event)
    {
        // intended is using the add() method to add a helper like below
        $helpers = $event->getSubject();
        
        $helpers->add(
            array('module' => 'MUFiles',
                  'type'   => 'javascript',
                  'path'   => 'modules/MUFiles/javascript/MUFiles_finder.js')
        );
    }
    
    /**
     * Listener for the `moduleplugin.tinymce.externalplugins` event.
     *
     * Adds external plugin to TinyMCE.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function getTinyMcePlugins(Zikula_Event $event)
    {
        // intended is using the add() method to add a plugin like below
        $plugins = $event->getSubject();
        
        $plugins->add(
            array('name' => 'mufiles',
                  'path' => 'modules/MUFiles/docs/scribite/plugins/TinyMce/plugins/mufiles/editor_plugin.js'
            )
        );
    }
    
    /**
     * Listener for the `moduleplugin.ckeditor.externalplugins` event.
     *
     * Adds external plugin to CKEditor.
     *
     * @param Zikula_Event $event The event instance.
     */
    public static function getCKEditorPlugins(Zikula_Event $event)
    {
        // intended is using the add() method to add a plugin like below
        $plugins = $event->getSubject();
        
        $plugins->add(
            array('name' => 'mufiles',
                  'path' => 'modules/MUFiles/docs/scribite/plugins/CKEditor/plugins/mufiles/',
                  'file' => 'plugin.js',
                  'img'  => 'ed_mufiles.gif'
            )
        );
    }
}
