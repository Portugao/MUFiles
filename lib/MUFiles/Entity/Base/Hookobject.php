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

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;
use Zikula\Core\ModUrl;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for hookobject entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 *
 * @abstract
 */
abstract class MUFiles_Entity_Base_Hookobject extends Zikula_EntityAccess
{
    /**
     * @var string The tablename this object maps to.
     */
    protected $_objectType = 'hookobject';

    /**
     * @var MUFiles_Entity_Validator_Hookobject The validator for this entity.
     */
    protected $_validator = null;

    /**
     * @var boolean Option to bypass validation if needed.
     */
    protected $_bypassValidation = false;

    /**
     * @var array List of available item actions.
     */
    protected $_actions = array();

    /**
     * @var array The current workflow data of this object.
    */
    protected $__WORKFLOW__ = array();

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id.
    */
    protected $id = 0;

    /**
     * @ORM\Column(length=20)
     * @var string $workflowState.
     */
    protected $workflowState = 'initial';

    /**
     * @ORM\Column(length=50)
     * @var string $hookedModule.
     */
    protected $hookedModule = '';

    /**
     * @ORM\Column(length=50)
     * @var string $hookedObject.
     */
    protected $hookedObject = '';

    /**
     * @ORM\Column(type="bigint")
     * @var integer $areaId.
     */
    protected $areaId = 0;

    /**
     * @ORM\Column(length=255)
     * @var string $url.
     */
    protected $url = '';

    /**
     * @ORM\Column(type="bigint")
     * @var integer $objectId.
     */
    protected $objectId = 0;

    /**
     * url object
     * @var ModUrl
     *
     * @ORM\Column(type="object")
     *
     */
    protected $urlObject = null;


    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="create")
     * @var integer $createdUserId.
     */
    protected $createdUserId;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="update")
     * @var integer $updatedUserId.
     */
    protected $updatedUserId;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @var datetime $createdDate.
     */
    protected $createdDate;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     * @var datetime $updatedDate.
     */
    protected $updatedDate;

    /**
     * Bidirectional - Many hookcollection [hookobjects] have many collectionhook [collections] (OWNING SIDE).
     *
     * @ORM\ManyToMany(targetEntity="MUFiles_Entity_Collection", inversedBy="hookcollection", cascade={"remove"})
     * @ORM\JoinTable(name="mufiles_hookobject_collection")
     * @var MUFiles_Entity_Collection[] $collectionhook.
     */
    protected $collectionhook = null;
    /**
     * Bidirectional - Many hookfile [hookobjects] have many filehook [files] (OWNING SIDE).
     *
     * @ORM\ManyToMany(targetEntity="MUFiles_Entity_File", inversedBy="hookfile", cascade={"remove"})
     * @ORM\JoinTable(name="mufiles_hookobject_file")
     * @var MUFiles_Entity_File[] $filehook.
     */
    protected $filehook = null;

    /**
     * Constructor.
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     *
     * @param TODO
     */
    public function __construct()
    {
        $this->areaId = 1;
        $this->objectId = 1;
        $this->workflowState = 'initial';
        $this->initValidator();
        $this->initWorkflow();
        $this->collectionhook = new ArrayCollection();
        $this->filehook = new ArrayCollection();
    }

    /**
     * Get _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }

    /**
     * Set _object type.
     *
     * @param string $_objectType.
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        $this->_objectType = $_objectType;
    }

    /**
     * Get _validator.
     *
     * @return MUFiles_Entity_Validator_Hookobject
     */
    public function get_validator()
    {
        return $this->_validator;
    }

    /**
     * Set _validator.
     *
     * @param MUFiles_Entity_Validator_Hookobject $_validator.
     *
     * @return void
     */
    public function set_validator(MUFiles_Entity_Validator_Hookobject $_validator = null)
    {
        $this->_validator = $_validator;
    }

    /**
     * Get _bypass validation.
     *
     * @return boolean
     */
    public function get_bypassValidation()
    {
        return $this->_bypassValidation;
    }

    /**
     * Set _bypass validation.
     *
     * @param boolean $_bypassValidation.
     *
     * @return void
     */
    public function set_bypassValidation($_bypassValidation)
    {
        $this->_bypassValidation = $_bypassValidation;
    }

    /**
     * Get _actions.
     *
     * @return array
     */
    public function get_actions()
    {
        return $this->_actions;
    }

    /**
     * Set _actions.
     *
     * @param array $_actions.
     *
     * @return void
     */
    public function set_actions(array $_actions = Array())
    {
        $this->_actions = $_actions;
    }

    /**
     * Get __ w o r k f l o w__.
     *
     * @return array
     */
    public function get__WORKFLOW__()
    {
        return $this->__WORKFLOW__;
    }

    /**
     * Set __ w o r k f l o w__.
     *
     * @param array $__WORKFLOW__.
     *
     * @return void
     */
    public function set__WORKFLOW__(array $__WORKFLOW__ = Array())
    {
        $this->__WORKFLOW__ = $__WORKFLOW__;
    }


    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param integer $id.
     *
     * @return void
     */
    public function setId($id)
    {
        if ($id != $this->id) {
            $this->id = $id;
        }
    }

    /**
     * Get workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }

    /**
     * Set workflow state.
     *
     * @param string $workflowState.
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($workflowState != $this->workflowState) {
            $this->workflowState = $workflowState;
        }
    }

    /**
     * Get hooked module.
     *
     * @return string
     */
    public function getHookedModule()
    {
        return $this->hookedModule;
    }

    /**
     * Set hooked module.
     *
     * @param string $hookedModule.
     *
     * @return void
     */
    public function setHookedModule($hookedModule)
    {
        if ($hookedModule != $this->hookedModule) {
            $this->hookedModule = $hookedModule;
        }
    }

    /**
     * Get hooked object.
     *
     * @return string
     */
    public function getHookedObject()
    {
        return $this->hookedObject;
    }

    /**
     * Set hooked object.
     *
     * @param string $hookedObject.
     *
     * @return void
     */
    public function setHookedObject($hookedObject)
    {
        if ($hookedObject != $this->hookedObject) {
            $this->hookedObject = $hookedObject;
        }
    }

    /**
     * Get area id.
     *
     * @return integer
     */
    public function getAreaId()
    {
        return $this->areaId;
    }

    /**
     * Set area id.
     *
     * @param integer $areaId.
     *
     * @return void
     */
    public function setAreaId($areaId)
    {
        if ($areaId != $this->areaId) {
            $this->areaId = $areaId;
        }
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url.
     *
     * @param string $url.
     *
     * @return void
     */
    public function setUrl($url)
    {
        if ($url != $this->url) {
            $this->url = $url;
        }
    }

    /**
     * Get object id.
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set object id.
     *
     * @param integer $objectId.
     *
     * @return void
     */
    public function setObjectId($objectId)
    {
        if ($objectId != $this->objectId) {
            $this->objectId = $objectId;
        }
    }

    /**
     * Get url object.
     *
     * @return object
     */
    public function getUrlObject()
    {
        return $this->urlObject;
    }

    /**
     * Set url object.
     * @param object $urlObject.
     */
    public function setUrlObject(ModUrl $urlObject)
    {
        if ($urlObject != $this->urlObject) {
            $this->urlObject = $urlObject;
        }
    }

    /**
     * Get created user id.
     *
     * @return integer
     */
    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }

    /**
     * Set created user id.
     *
     * @param integer $createdUserId.
     *
     * @return void
     */
    public function setCreatedUserId($createdUserId)
    {
        $this->createdUserId = $createdUserId;
    }

    /**
     * Get updated user id.
     *
     * @return integer
     */
    public function getUpdatedUserId()
    {
        return $this->updatedUserId;
    }

    /**
     * Set updated user id.
     *
     * @param integer $updatedUserId.
     *
     * @return void
     */
    public function setUpdatedUserId($updatedUserId)
    {
        $this->updatedUserId = $updatedUserId;
    }

    /**
     * Get created date.
     *
     * @return datetime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set created date.
     *
     * @param datetime $createdDate.
     *
     * @return void
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;
    }

    /**
     * Get updated date.
     *
     * @return datetime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set updated date.
     *
     * @param datetime $updatedDate.
     *
     * @return void
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;
    }


    /**
     * Get collectionhook.
     *
     * @return MUFiles_Entity_Collection[]
     */
    public function getCollectionhook()
    {
        return $this->collectionhook;
    }

    /**
     * Set collectionhook.
     *
     * @param MUFiles_Entity_Collection[] $collectionhook.
     *
     * @return void
     */
    public function setCollectionhook($collectionhook)
    {
        foreach ($collectionhook as $collectionSingle) {
            $this->addCollectionhook($collectionSingle);
        }
    }

    /**
     * Adds an instance of MUFiles_Entity_Collection to the list of collectionhook.
     *
     * @param MUFiles_Entity_Collection $collection The instance to be added to the collection.
     *
     * @return void
     */
    public function addCollectionhook(MUFiles_Entity_Collection $collection)
    {
        $this->collectionhook->add($collection);
    }

    /**
     * Removes an instance of MUFiles_Entity_Collection from the list of collectionhook.
     *
     * @param MUFiles_Entity_Collection $collection The instance to be removed from the collection.
     *
     * @return void
     */
    public function removeCollectionhook(MUFiles_Entity_Collection $collection)
    {
        $this->collectionhook->removeElement($collection);
    }

    /**
     * Get filehook.
     *
     * @return MUFiles_Entity_File[]
     */
    public function getFilehook()
    {
        return $this->filehook;
    }

    /**
     * Set filehook.
     *
     * @param MUFiles_Entity_File[] $filehook.
     *
     * @return void
     */
    public function setFilehook($filehook)
    {
        foreach ($filehook as $fileSingle) {
            $this->addFilehook($fileSingle);
        }
    }

    /**
     * Adds an instance of MUFiles_Entity_File to the list of filehook.
     *
     * @param MUFiles_Entity_File $file The instance to be added to the collection.
     *
     * @return void
     */
    public function addFilehook(MUFiles_Entity_File $file)
    {
        $this->filehook->add($file);
    }

    /**
     * Removes an instance of MUFiles_Entity_File from the list of filehook.
     *
     * @param MUFiles_Entity_File $file The instance to be removed from the collection.
     *
     * @return void
     */
    public function removeFilehook(MUFiles_Entity_File $file)
    {
        $this->filehook->removeElement($file);
    }



    /**
     * Post-Process the data after the entity has been constructed by the entity manager.
     * The event happens after the entity has been loaded from database or after a refresh call.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - no access to associations (not initialised yet)
     *
     * @see MUFiles_Entity_Hookobject::postLoadCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostLoadCallback()
    {
        // echo 'loaded a record ...';
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
        $usesCsvOutput = FormUtil::getPassedValue('usecsvext', false, 'GETPOST', FILTER_VALIDATE_BOOLEAN);

        $this['id'] = (int) ((isset($this['id']) && !empty($this['id'])) ? DataUtil::formatForDisplay($this['id']) : 0);
        $this->formatTextualField('workflowState', $currentFunc, $usesCsvOutput, true);
        $this->formatTextualField('hookedModule', $currentFunc, $usesCsvOutput);
        $this->formatTextualField('hookedObject', $currentFunc, $usesCsvOutput);
        $this['areaId'] = (int) ((isset($this['areaId']) && !empty($this['areaId'])) ? DataUtil::formatForDisplay($this['areaId']) : 0);
        $this->formatTextualField('url', $currentFunc, $usesCsvOutput);
        $this['objectId'] = (int) ((isset($this['objectId']) && !empty($this['objectId'])) ? DataUtil::formatForDisplay($this['objectId']) : 0);
        $this->formatObjectField('urlObject', $currentFunc, $usesCsvOutput);

        $this->prepareItemActions();

        return true;
    }

    /**
     * Formats a given textual field depending on it's actual kind of content.
     *
     * @param string  $fieldName     Name of field to be formatted.
     * @param string  $currentFunc   Name of current controller action.
     * @param string  $usesCsvOutput Whether the output is CSV or not (defaults to false).
     * @param boolean $allowZero     Whether 0 values are allowed or not (defaults to false).
     */
    protected function formatTextualField($fieldName, $currentFunc, $usesCsvOutput = false, $allowZero = false)
    {
        if ($currentFunc == 'edit') {
            // apply no changes when editing the content
            return;
        }

        if ($usesCsvOutput == 1) {
            // apply no changes for CSV output
            return;
        }

        $string = '';
        if (isset($this[$fieldName])) {
            if (!empty($this[$fieldName]) || ($allowZero && $this[$fieldName] == 0)) {
                $string = $this[$fieldName];
                if ($this->containsHtml($string)) {
                    $string = DataUtil::formatForDisplayHTML($string);
                } else {
                    $string = DataUtil::formatForDisplay($string);
                    $string = nl2br($string);
                }
            }
        }

        $this[$fieldName] = $string;
    }

    /**
     * Checks whether any html tags are contained in the given string.
     * See http://stackoverflow.com/questions/10778035/how-to-check-if-string-contents-have-any-html-in-it for implementation details.
     *
     * @param $string string The given input string.
     *
     * @return boolean Whether any html tags are found or not.
     */
    protected function containsHtml($string)
    {
        return preg_match("/<[^<]+>/", $string, $m) != 0;
    }

    /**
     * Formats a given object field.
     *
     * @param string  $fieldName     Name of field to be formatted.
     * @param string  $currentFunc   Name of current controller action.
     * @param string  $usesCsvOutput Whether the output is CSV or not (defaults to false).
     */
    protected function formatObjectField($fieldName, $currentFunc, $usesCsvOutput = false)
    {
        if ($currentFunc == 'edit') {
            // apply no changes when editing the content
            return;
        }

        if ($usesCsvOutput == 1) {
            // apply no changes for CSV output
            return;
        }

        $this[$fieldName] = (isset($this[$fieldName]) && !empty($this[$fieldName])) ? DataUtil::formatForDisplay($this[$fieldName]) : '';
    }

    /**
     * Pre-Process the data prior to an insert operation.
     * The event happens before the entity managers persist operation is executed for this entity.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - no identifiers available if using an identity generator like sequences
     *     - Doctrine won't recognize changes on relations which are done here
     *       if this method is called by cascade persist
     *     - no creation of other entities allowed
     *
     * @see MUFiles_Entity_Hookobject::prePersistCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPrePersistCallback()
    {
        $this->validate();

        return true;
    }

    /**
     * Post-Process the data after an insert operation.
     * The event happens after the entity has been made persistant.
     * Will be called after the database insert operations.
     * The generated primary key values are available.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *
     * @see MUFiles_Entity_Hookobject::postPersistCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostPersistCallback()
    {
        return true;
    }

    /**
     * Pre-Process the data prior a delete operation.
     * The event happens before the entity managers remove operation is executed for this entity.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL DELETE statement
     *
     * @see MUFiles_Entity_Hookobject::preRemoveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreRemoveCallback()
    {
        // delete workflow for this entity
        $workflow = $this['__WORKFLOW__'];
        if ($workflow['id'] > 0) {
            $result = (bool) DBUtil::deleteObjectByID('workflows', $workflow['id']);
            if ($result === false) {
                $dom = ZLanguage::getModuleDomain('MUFiles');
                return LogUtil::registerError(__('Error! Could not remove stored workflow. Deletion has been aborted.', $dom));
            }
        }

        return true;
    }

    /**
     * Post-Process the data after a delete.
     * The event happens after the entity has been deleted.
     * Will be called after the database delete operations.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL DELETE statement
     *
     * @see MUFiles_Entity_Hookobject::postRemoveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostRemoveCallback()
    {

        return true;
    }

    /**
     * Pre-Process the data prior to an update operation.
     * The event happens before the database update operations for the entity data.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL UPDATE statement
     *     - changes on associations are not allowed and won't be recognized by flush
     *     - changes on properties won't be recognized by flush as well
     *     - no creation of other entities allowed
     *
     * @see MUFiles_Entity_Hookobject::preUpdateCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreUpdateCallback()
    {
        $this->validate();

        return true;
    }

    /**
     * Post-Process the data after an update operation.
     * The event happens after the database update operations for the entity data.
     *
     * Restrictions:
     *     - no access to entity manager or unit of work apis
     *     - will not be called for a DQL UPDATE statement
     *
     * @see MUFiles_Entity_Hookobject::postUpdateCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostUpdateCallback()
    {
        return true;
    }

    /**
     * Pre-Process the data prior to a save operation.
     * This combines the PrePersist and PreUpdate events.
     * For more information see corresponding callback handlers.
     *
     * @see MUFiles_Entity_Hookobject::preSaveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPreSaveCallback()
    {
        $this->validate();

        return true;
    }

    /**
     * Post-Process the data after a save operation.
     * This combines the PostPersist and PostUpdate events.
     * For more information see corresponding callback handlers.
     *
     * @see MUFiles_Entity_Hookobject::postSaveCallback()
     * @return boolean true if completed successfully else false.
     */
    protected function performPostSaveCallback()
    {
        return true;
    }



    /**
     * Returns the formatted title conforming to the display pattern
     * specified for this entity.
     */
    public function getTitleFromDisplayPattern()
    {
        $serviceManager = ServiceUtil::getManager();
        $listHelper = new MUFiles_Util_ListEntries(ServiceUtil::getManager());

        $formattedTitle = ''
                . $this->getHookedModule();

        return $formattedTitle;
    }

    /**
     * Initialises the validator and return it's instance.
     *
     * @return MUFiles_Entity_Validator_Hookobject The validator for this entity.
     */
    public function initValidator()
    {
        if (!is_null($this->_validator)) {
            return $this->_validator;
        }
        $this->_validator = new MUFiles_Entity_Validator_Hookobject($this);

        return $this->_validator;
    }

    /**
     * Sets/retrieves the workflow details.
     *
     * @param boolean $forceLoading load the workflow record.
     */
    public function initWorkflow($forceLoading = false)
    {
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
        $isReuse = FormUtil::getPassedValue('astemplate', '', 'GETPOST', FILTER_SANITIZE_STRING);

        // apply workflow with most important information
        $idColumn = 'id';

        $serviceManager = ServiceUtil::getManager();
        $workflowHelper = new MUFiles_Util_Workflow($serviceManager);

        $schemaName = $workflowHelper->getWorkflowName($this['_objectType']);
        $this['__WORKFLOW__'] = array(
                'module' => 'MUFiles',
                'state' => $this['workflowState'],
                'obj_table' => $this['_objectType'],
                'obj_idcolumn' => $idColumn,
                'obj_id' => $this[$idColumn],
                'schemaname' => $schemaName);

        // load the real workflow only when required (e. g. when func is edit or delete)
        if ((!in_array($currentFunc, array('main', 'view', 'display')) && empty($isReuse)) || $forceLoading) {
            $result = Zikula_Workflow_Util::getWorkflowForObject($this, $this['_objectType'], $idColumn, 'MUFiles');
            if (!$result) {
                $dom = ZLanguage::getModuleDomain('MUFiles');
                LogUtil::registerError(__('Error! Could not load the associated workflow.', $dom));
            }
        }

        if (!is_object($this['__WORKFLOW__']) && !isset($this['__WORKFLOW__']['schemaname'])) {
            $workflow = $this['__WORKFLOW__'];
            $workflow['schemaname'] = $schemaName;
            $this['__WORKFLOW__'] = $workflow;
        }
    }

    /**
     * Resets workflow data back to initial state.
     * To be used after cloning an entity object.
     */
    public function resetWorkflow()
    {
        $this->setWorkflowState('initial');

        $serviceManager = ServiceUtil::getManager();
        $workflowHelper = new MUFiles_Util_Workflow($serviceManager);

        $schemaName = $workflowHelper->getWorkflowName($this['_objectType']);
        $this['__WORKFLOW__'] = array(
                'module' => 'MUFiles',
                'state' => $this['workflowState'],
                'obj_table' => $this['_objectType'],
                'obj_idcolumn' => 'id',
                'obj_id' => 0,
                'schemaname' => $schemaName);
    }

    /**
     * Start validation and raise exception if invalid data is found.
     *
     * @return void.
     *
     * @throws Zikula_Exception Thrown if a validation error occurs
     */
    public function validate()
    {
        if ($this->_bypassValidation === true) {
            return;
        }

        $result = $this->initValidator()->validateAll();
        if (is_array($result)) {
            throw new Zikula_Exception($result['message'], $result['code'], $result['debugArray']);
        }
    }

    /**
     * Return entity data in JSON format.
     *
     * @return string JSON-encoded data.
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Collect available actions for this entity.
     */
    protected function prepareItemActions()
    {
        if (!empty($this->_actions)) {
            return;
        }

        $currentLegacyControllerType = FormUtil::getPassedValue('lct', 'user', 'GETPOST', FILTER_SANITIZE_STRING);
        $currentFunc = FormUtil::getPassedValue('func', 'main', 'GETPOST', FILTER_SANITIZE_STRING);
        $dom = ZLanguage::getModuleDomain('MUFiles');
        if ($currentLegacyControllerType == 'admin') {
            if (in_array($currentFunc, array('main', 'view'))) {
                $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'display', 'arguments' => array('ot' => 'hookobject', 'id' => $this['id'])),
                        'icon' => 'preview',
                        'linkTitle' => __('Open preview page', $dom),
                        'linkText' => __('Preview', $dom)
                );
                $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'display', 'arguments' => array('ot' => 'hookobject', 'id' => $this['id'])),
                        'icon' => 'display',
                        'linkTitle' => str_replace('"', '', $this->getTitleFromDisplayPattern()),
                        'linkText' => __('Details', $dom)
                );
            }
            if (in_array($currentFunc, array('main', 'view', 'display'))) {
                $component = 'MUFiles:Hookobject:';
                $instance = $this->id . '::';
                if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
                    $this->_actions[] = array(
                            'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'hookobject', 'id' => $this['id'])),
                            'icon' => 'edit',
                            'linkTitle' => __('Edit', $dom),
                            'linkText' => __('Edit', $dom)
                    );
                    $this->_actions[] = array(
                            'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => array('ot' => 'hookobject', 'astemplate' => $this['id'])),
                            'icon' => 'saveas',
                            'linkTitle' => __('Reuse for new item', $dom),
                            'linkText' => __('Reuse', $dom)
                    );
                }
                if (SecurityUtil::checkPermission($component, $instance, ACCESS_DELETE)) {
                    $this->_actions[] = array(
                            'url' => array('type' => 'admin', 'func' => 'delete', 'arguments' => array('ot' => 'hookobject', 'id' => $this['id'])),
                            'icon' => 'delete',
                            'linkTitle' => __('Delete', $dom),
                            'linkText' => __('Delete', $dom)
                    );
                }
            }
            if ($currentFunc == 'display') {
                $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'view', 'arguments' => array('ot' => 'hookobject')),
                        'icon' => 'back',
                        'linkTitle' => __('Back to overview', $dom),
                        'linkText' => __('Back to overview', $dom)
                );
            }

            // more actions for adding new related items
            $authAdmin = SecurityUtil::checkPermission($component, $instance, ACCESS_ADMIN);

            $uid = UserUtil::getVar('uid');
            if ($authAdmin || (isset($uid) && isset($this->createdUserId) && $this->createdUserId == $uid)) {

                $urlArgs = array('ot' => 'collection',
                        'hookcollection' => $this->id);
                if ($currentFunc == 'view') {
                    $urlArgs['returnTo'] = 'adminViewHookobject';
                } elseif ($currentFunc == 'display') {
                    $urlArgs['returnTo'] = 'adminDisplayHookobject';
                }
                $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => $urlArgs),
                        'icon' => 'add',
                        'linkTitle' => __('Create collection', $dom),
                        'linkText' => __('Create collection', $dom)
                );

                $urlArgs = array('ot' => 'file',
                        'hookfile' => $this->id);
                if ($currentFunc == 'view') {
                    $urlArgs['returnTo'] = 'adminViewHookobject';
                } elseif ($currentFunc == 'display') {
                    $urlArgs['returnTo'] = 'adminDisplayHookobject';
                }
                $this->_actions[] = array(
                        'url' => array('type' => 'admin', 'func' => 'edit', 'arguments' => $urlArgs),
                        'icon' => 'add',
                        'linkTitle' => __('Create file', $dom),
                        'linkText' => __('Create file', $dom)
                );
            }
        }
        if ($currentLegacyControllerType == 'user') {
            if (in_array($currentFunc, array('main', 'view'))) {
                $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'display', 'arguments' => array('ot' => 'hookobject', 'id' => $this['id'])),
                        'icon' => 'display',
                        'linkTitle' => str_replace('"', '', $this->getTitleFromDisplayPattern()),
                        'linkText' => __('Details', $dom)
                );
            }
            if (in_array($currentFunc, array('main', 'view', 'display'))) {
                $component = 'MUFiles:Hookobject:';
                $instance = $this->id . '::';
                if (SecurityUtil::checkPermission($component, $instance, ACCESS_EDIT)) {
                    $this->_actions[] = array(
                            'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => array('ot' => 'hookobject', 'id' => $this['id'])),
                            'icon' => 'edit',
                            'linkTitle' => __('Edit', $dom),
                            'linkText' => __('Edit', $dom)
                    );
                    $this->_actions[] = array(
                            'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => array('ot' => 'hookobject', 'astemplate' => $this['id'])),
                            'icon' => 'saveas',
                            'linkTitle' => __('Reuse for new item', $dom),
                            'linkText' => __('Reuse', $dom)
                    );
                }
                if (SecurityUtil::checkPermission($component, $instance, ACCESS_DELETE)) {
                    $this->_actions[] = array(
                            'url' => array('type' => 'user', 'func' => 'delete', 'arguments' => array('ot' => 'hookobject', 'id' => $this['id'])),
                            'icon' => 'delete',
                            'linkTitle' => __('Delete', $dom),
                            'linkText' => __('Delete', $dom)
                    );
                }
            }
            if ($currentFunc == 'display') {
                $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'view', 'arguments' => array('ot' => 'hookobject')),
                        'icon' => 'back',
                        'linkTitle' => __('Back to overview', $dom),
                        'linkText' => __('Back to overview', $dom)
                );
            }

            // more actions for adding new related items
            $authAdmin = SecurityUtil::checkPermission($component, $instance, ACCESS_ADMIN);

            $uid = UserUtil::getVar('uid');
            if ($authAdmin || (isset($uid) && isset($this->createdUserId) && $this->createdUserId == $uid)) {

                $urlArgs = array('ot' => 'collection',
                        'hookcollection' => $this->id);
                if ($currentFunc == 'view') {
                    $urlArgs['returnTo'] = 'userViewHookobject';
                } elseif ($currentFunc == 'display') {
                    $urlArgs['returnTo'] = 'userDisplayHookobject';
                }
                $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => $urlArgs),
                        'icon' => 'add',
                        'linkTitle' => __('Create collection', $dom),
                        'linkText' => __('Create collection', $dom)
                );

                $urlArgs = array('ot' => 'file',
                        'hookfile' => $this->id);
                if ($currentFunc == 'view') {
                    $urlArgs['returnTo'] = 'userViewHookobject';
                } elseif ($currentFunc == 'display') {
                    $urlArgs['returnTo'] = 'userDisplayHookobject';
                }
                $this->_actions[] = array(
                        'url' => array('type' => 'user', 'func' => 'edit', 'arguments' => $urlArgs),
                        'icon' => 'add',
                        'linkTitle' => __('Create file', $dom),
                        'linkText' => __('Create file', $dom)
                );
            }
        }
    }

    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return Array The resulting arguments list.
     */
    public function createUrlArgs()
    {
        $args = array('ot' => $this['_objectType']);

        $args['id'] = $this['id'];

        if (isset($this['slug'])) {
            $args['slug'] = $this['slug'];
        }

        return $args;
    }

    /**
     * Create concatenated identifier string (for composite keys).
     *
     * @return String concatenated identifiers.
     */
    public function createCompositeIdentifier()
    {
        $itemId = $this['id'];

        return $itemId;
    }

    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'mufiles.ui_hooks.hookobjects';
    }

    /**
     * Returns an array of all related objects that need to be persited after clone.
     *
     * @param array $objects The objects are added to this array. Default: array()
     *
     * @return array of entity objects.
     */
    public function getRelatedObjectsToPersist(&$objects = array()) {
        return array();
    }

    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     */
    public function __toString()
    {
        return $this->getId();
    }

    /**
     * Clone interceptor implementation.
     * This method is for example called by the reuse functionality.
     * Performs a quite simple shallow copy.
     *
     * See also:
     * (1) http://docs.doctrine-project.org/en/latest/cookbook/implementing-wakeup-or-clone.html
     * (2) http://www.php.net/manual/en/language.oop5.cloning.php
     * (3) http://stackoverflow.com/questions/185934/how-do-i-create-a-copy-of-an-object-in-php
     * (4) http://www.pantovic.com/article/26/doctrine2-entity-cloning
     */
    public function __clone()
    {
        // If the entity has an identity, proceed as normal.
        if ($this->id) {
            // unset identifiers
            $this->setId(0);

            // init validator
            $this->initValidator();

            // reset Workflow
            $this->resetWorkflow();

            $this->setCreatedDate(null);
            $this->setCreatedUserId(null);
            $this->setUpdatedDate(null);
            $this->setUpdatedUserId(null);


        }
        // otherwise do nothing, do NOT throw an exception!
    }
}
