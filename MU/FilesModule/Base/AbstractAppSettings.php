<?php
/**
 * Files.
 *
 * @copyright Michael Ueberschaer (MU)
 * @license http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public License
 * @author Michael Ueberschaer <info@homepages-mit-zikula.de>.
 * @link https://homepages-mit-zikula.de
 * @link https://ziku.la
 * @version Generated by ModuleStudio (https://modulestudio.de).
 */

namespace MU\FilesModule\Base;

use Symfony\Component\Validator\Constraints as Assert;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use Zikula\GroupsModule\Constant as GroupsConstant;
use Zikula\GroupsModule\Entity\RepositoryInterface\GroupRepositoryInterface;
use MU\FilesModule\Validator\Constraints as FilesAssert;

/**
 * Application settings class for handling module variables.
 */
abstract class AbstractAppSettings
{
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var GroupRepositoryInterface
     */
    protected $groupRepository;
    
    /**
     * Comma seperated without space.
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="2000")
     * @var text $allowedExtensions
     */
    protected $allowedExtensions = 'pdf,doc,docx,odt';
    
    /**
     * For example 4096 (bytes), 200k (kilobytes) or 2M ( megabytes).
     *
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="2000")
     * @var text $maxSize
     */
    protected $maxSize = '200k';
    
    /**
     * @Assert\IsTrue(message="This option is mandatory.")
     * @Assert\Type(type="bool")
     * @var boolean $onlyParent
     */
    protected $onlyParent = false;
    
    /**
     * @Assert\IsTrue(message="This option is mandatory.")
     * @Assert\Type(type="bool")
     * @var boolean $specialCollectionMenue
     */
    protected $specialCollectionMenue = false;
    
    /**
     * The amount of collections shown per page
     *
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $collectionEntriesPerPage
     */
    protected $collectionEntriesPerPage = 10;
    
    /**
     * Whether to add a link to collections of the current user on his account page
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $linkOwnCollectionsOnAccountPage
     */
    protected $linkOwnCollectionsOnAccountPage = true;
    
    /**
     * The amount of files shown per page
     *
     * @Assert\Type(type="integer")
     * @Assert\NotBlank()
     * @Assert\NotEqualTo(value=0)
     * @Assert\LessThan(value=100000000000)
     * @var integer $fileEntriesPerPage
     */
    protected $fileEntriesPerPage = 10;
    
    /**
     * Whether to add a link to files of the current user on his account page
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $linkOwnFilesOnAccountPage
     */
    protected $linkOwnFilesOnAccountPage = true;
    
    /**
     * Whether only own entries should be shown on view pages by default or not
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $showOnlyOwnEntries
     */
    protected $showOnlyOwnEntries = false;
    
    /**
     * Used to determine moderator user accounts for sending email notifications.
     *
     * @Assert\NotBlank()
     * @var integer $moderationGroupForCollections
     */
    protected $moderationGroupForCollections = 2;
    
    /**
     * Used to determine moderator user accounts for sending email notifications.
     *
     * @Assert\NotBlank()
     * @var integer $moderationGroupForFiles
     */
    protected $moderationGroupForFiles = 2;
    
    /**
     * Whether to allow moderators choosing a user which will be set as creator.
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $allowModerationSpecificCreatorForCollection
     */
    protected $allowModerationSpecificCreatorForCollection = false;
    
    /**
     * Whether to allow moderators choosing a custom creation date.
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $allowModerationSpecificCreationDateForCollection
     */
    protected $allowModerationSpecificCreationDateForCollection = false;
    
    /**
     * Whether to allow moderators choosing a user which will be set as creator.
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $allowModerationSpecificCreatorForFile
     */
    protected $allowModerationSpecificCreatorForFile = false;
    
    /**
     * Whether to allow moderators choosing a custom creation date.
     *
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $allowModerationSpecificCreationDateForFile
     */
    protected $allowModerationSpecificCreationDateForFile = false;
    
    /**
     * Which sections are supported in the Finder component (used by Scribite plug-ins).
     *
     * @Assert\NotNull()
     * @FilesAssert\ListEntry(entityName="appSettings", propertyName="enabledFinderTypes", multiple=true)
     * @var string $enabledFinderTypes
     */
    protected $enabledFinderTypes = 'collection###file';
    
    
    /**
     * AppSettings constructor.
     *
     * @param VariableApiInterface $variableApi VariableApi service instance
     * @param GroupRepositoryInterface $groupRepository GroupRepository service instance
     */
    public function __construct(
        VariableApiInterface $variableApi,
        GroupRepositoryInterface $groupRepository
    ) {
        $this->variableApi = $variableApi;
        $this->groupRepository = $groupRepository;
    
        $this->load();
    }
    
    /**
     * Returns the allowed extensions.
     *
     * @return text
     */
    public function getAllowedExtensions()
    {
        return $this->allowedExtensions;
    }
    
    /**
     * Sets the allowed extensions.
     *
     * @param text $allowedExtensions
     *
     * @return void
     */
    public function setAllowedExtensions($allowedExtensions)
    {
        if ($this->allowedExtensions !== $allowedExtensions) {
            $this->allowedExtensions = isset($allowedExtensions) ? $allowedExtensions : '';
        }
    }
    
    /**
     * Returns the max size.
     *
     * @return text
     */
    public function getMaxSize()
    {
        return $this->maxSize;
    }
    
    /**
     * Sets the max size.
     *
     * @param text $maxSize
     *
     * @return void
     */
    public function setMaxSize($maxSize)
    {
        if ($this->maxSize !== $maxSize) {
            $this->maxSize = isset($maxSize) ? $maxSize : '';
        }
    }
    
    /**
     * Returns the only parent.
     *
     * @return boolean
     */
    public function getOnlyParent()
    {
        return $this->onlyParent;
    }
    
    /**
     * Sets the only parent.
     *
     * @param boolean $onlyParent
     *
     * @return void
     */
    public function setOnlyParent($onlyParent)
    {
        if (boolval($this->onlyParent) !== boolval($onlyParent)) {
            $this->onlyParent = boolval($onlyParent);
        }
    }
    
    /**
     * Returns the special collection menue.
     *
     * @return boolean
     */
    public function getSpecialCollectionMenue()
    {
        return $this->specialCollectionMenue;
    }
    
    /**
     * Sets the special collection menue.
     *
     * @param boolean $specialCollectionMenue
     *
     * @return void
     */
    public function setSpecialCollectionMenue($specialCollectionMenue)
    {
        if (boolval($this->specialCollectionMenue) !== boolval($specialCollectionMenue)) {
            $this->specialCollectionMenue = boolval($specialCollectionMenue);
        }
    }
    
    /**
     * Returns the collection entries per page.
     *
     * @return integer
     */
    public function getCollectionEntriesPerPage()
    {
        return $this->collectionEntriesPerPage;
    }
    
    /**
     * Sets the collection entries per page.
     *
     * @param integer $collectionEntriesPerPage
     *
     * @return void
     */
    public function setCollectionEntriesPerPage($collectionEntriesPerPage)
    {
        if (intval($this->collectionEntriesPerPage) !== intval($collectionEntriesPerPage)) {
            $this->collectionEntriesPerPage = intval($collectionEntriesPerPage);
        }
    }
    
    /**
     * Returns the link own collections on account page.
     *
     * @return boolean
     */
    public function getLinkOwnCollectionsOnAccountPage()
    {
        return $this->linkOwnCollectionsOnAccountPage;
    }
    
    /**
     * Sets the link own collections on account page.
     *
     * @param boolean $linkOwnCollectionsOnAccountPage
     *
     * @return void
     */
    public function setLinkOwnCollectionsOnAccountPage($linkOwnCollectionsOnAccountPage)
    {
        if (boolval($this->linkOwnCollectionsOnAccountPage) !== boolval($linkOwnCollectionsOnAccountPage)) {
            $this->linkOwnCollectionsOnAccountPage = boolval($linkOwnCollectionsOnAccountPage);
        }
    }
    
    /**
     * Returns the file entries per page.
     *
     * @return integer
     */
    public function getFileEntriesPerPage()
    {
        return $this->fileEntriesPerPage;
    }
    
    /**
     * Sets the file entries per page.
     *
     * @param integer $fileEntriesPerPage
     *
     * @return void
     */
    public function setFileEntriesPerPage($fileEntriesPerPage)
    {
        if (intval($this->fileEntriesPerPage) !== intval($fileEntriesPerPage)) {
            $this->fileEntriesPerPage = intval($fileEntriesPerPage);
        }
    }
    
    /**
     * Returns the link own files on account page.
     *
     * @return boolean
     */
    public function getLinkOwnFilesOnAccountPage()
    {
        return $this->linkOwnFilesOnAccountPage;
    }
    
    /**
     * Sets the link own files on account page.
     *
     * @param boolean $linkOwnFilesOnAccountPage
     *
     * @return void
     */
    public function setLinkOwnFilesOnAccountPage($linkOwnFilesOnAccountPage)
    {
        if (boolval($this->linkOwnFilesOnAccountPage) !== boolval($linkOwnFilesOnAccountPage)) {
            $this->linkOwnFilesOnAccountPage = boolval($linkOwnFilesOnAccountPage);
        }
    }
    
    /**
     * Returns the show only own entries.
     *
     * @return boolean
     */
    public function getShowOnlyOwnEntries()
    {
        return $this->showOnlyOwnEntries;
    }
    
    /**
     * Sets the show only own entries.
     *
     * @param boolean $showOnlyOwnEntries
     *
     * @return void
     */
    public function setShowOnlyOwnEntries($showOnlyOwnEntries)
    {
        if (boolval($this->showOnlyOwnEntries) !== boolval($showOnlyOwnEntries)) {
            $this->showOnlyOwnEntries = boolval($showOnlyOwnEntries);
        }
    }
    
    /**
     * Returns the moderation group for collections.
     *
     * @return integer
     */
    public function getModerationGroupForCollections()
    {
        return $this->moderationGroupForCollections;
    }
    
    /**
     * Sets the moderation group for collections.
     *
     * @param integer $moderationGroupForCollections
     *
     * @return void
     */
    public function setModerationGroupForCollections($moderationGroupForCollections)
    {
        if ($this->moderationGroupForCollections !== $moderationGroupForCollections) {
            $this->moderationGroupForCollections = $moderationGroupForCollections;
        }
    }
    
    /**
     * Returns the moderation group for files.
     *
     * @return integer
     */
    public function getModerationGroupForFiles()
    {
        return $this->moderationGroupForFiles;
    }
    
    /**
     * Sets the moderation group for files.
     *
     * @param integer $moderationGroupForFiles
     *
     * @return void
     */
    public function setModerationGroupForFiles($moderationGroupForFiles)
    {
        if ($this->moderationGroupForFiles !== $moderationGroupForFiles) {
            $this->moderationGroupForFiles = $moderationGroupForFiles;
        }
    }
    
    /**
     * Returns the allow moderation specific creator for collection.
     *
     * @return boolean
     */
    public function getAllowModerationSpecificCreatorForCollection()
    {
        return $this->allowModerationSpecificCreatorForCollection;
    }
    
    /**
     * Sets the allow moderation specific creator for collection.
     *
     * @param boolean $allowModerationSpecificCreatorForCollection
     *
     * @return void
     */
    public function setAllowModerationSpecificCreatorForCollection($allowModerationSpecificCreatorForCollection)
    {
        if (boolval($this->allowModerationSpecificCreatorForCollection) !== boolval($allowModerationSpecificCreatorForCollection)) {
            $this->allowModerationSpecificCreatorForCollection = boolval($allowModerationSpecificCreatorForCollection);
        }
    }
    
    /**
     * Returns the allow moderation specific creation date for collection.
     *
     * @return boolean
     */
    public function getAllowModerationSpecificCreationDateForCollection()
    {
        return $this->allowModerationSpecificCreationDateForCollection;
    }
    
    /**
     * Sets the allow moderation specific creation date for collection.
     *
     * @param boolean $allowModerationSpecificCreationDateForCollection
     *
     * @return void
     */
    public function setAllowModerationSpecificCreationDateForCollection($allowModerationSpecificCreationDateForCollection)
    {
        if (boolval($this->allowModerationSpecificCreationDateForCollection) !== boolval($allowModerationSpecificCreationDateForCollection)) {
            $this->allowModerationSpecificCreationDateForCollection = boolval($allowModerationSpecificCreationDateForCollection);
        }
    }
    
    /**
     * Returns the allow moderation specific creator for file.
     *
     * @return boolean
     */
    public function getAllowModerationSpecificCreatorForFile()
    {
        return $this->allowModerationSpecificCreatorForFile;
    }
    
    /**
     * Sets the allow moderation specific creator for file.
     *
     * @param boolean $allowModerationSpecificCreatorForFile
     *
     * @return void
     */
    public function setAllowModerationSpecificCreatorForFile($allowModerationSpecificCreatorForFile)
    {
        if (boolval($this->allowModerationSpecificCreatorForFile) !== boolval($allowModerationSpecificCreatorForFile)) {
            $this->allowModerationSpecificCreatorForFile = boolval($allowModerationSpecificCreatorForFile);
        }
    }
    
    /**
     * Returns the allow moderation specific creation date for file.
     *
     * @return boolean
     */
    public function getAllowModerationSpecificCreationDateForFile()
    {
        return $this->allowModerationSpecificCreationDateForFile;
    }
    
    /**
     * Sets the allow moderation specific creation date for file.
     *
     * @param boolean $allowModerationSpecificCreationDateForFile
     *
     * @return void
     */
    public function setAllowModerationSpecificCreationDateForFile($allowModerationSpecificCreationDateForFile)
    {
        if (boolval($this->allowModerationSpecificCreationDateForFile) !== boolval($allowModerationSpecificCreationDateForFile)) {
            $this->allowModerationSpecificCreationDateForFile = boolval($allowModerationSpecificCreationDateForFile);
        }
    }
    
    /**
     * Returns the enabled finder types.
     *
     * @return string
     */
    public function getEnabledFinderTypes()
    {
        return $this->enabledFinderTypes;
    }
    
    /**
     * Sets the enabled finder types.
     *
     * @param string $enabledFinderTypes
     *
     * @return void
     */
    public function setEnabledFinderTypes($enabledFinderTypes)
    {
        if ($this->enabledFinderTypes !== $enabledFinderTypes) {
            $this->enabledFinderTypes = isset($enabledFinderTypes) ? $enabledFinderTypes : '';
        }
    }
    
    
    /**
     * Loads module variables from the database.
     */
    protected function load()
    {
        $moduleVars = $this->variableApi->getAll('MUFilesModule');
    
        if (isset($moduleVars['allowedExtensions'])) {
            $this->setAllowedExtensions($moduleVars['allowedExtensions']);
        }
        if (isset($moduleVars['maxSize'])) {
            $this->setMaxSize($moduleVars['maxSize']);
        }
        if (isset($moduleVars['onlyParent'])) {
            $this->setOnlyParent($moduleVars['onlyParent']);
        }
        if (isset($moduleVars['specialCollectionMenue'])) {
            $this->setSpecialCollectionMenue($moduleVars['specialCollectionMenue']);
        }
        if (isset($moduleVars['collectionEntriesPerPage'])) {
            $this->setCollectionEntriesPerPage($moduleVars['collectionEntriesPerPage']);
        }
        if (isset($moduleVars['linkOwnCollectionsOnAccountPage'])) {
            $this->setLinkOwnCollectionsOnAccountPage($moduleVars['linkOwnCollectionsOnAccountPage']);
        }
        if (isset($moduleVars['fileEntriesPerPage'])) {
            $this->setFileEntriesPerPage($moduleVars['fileEntriesPerPage']);
        }
        if (isset($moduleVars['linkOwnFilesOnAccountPage'])) {
            $this->setLinkOwnFilesOnAccountPage($moduleVars['linkOwnFilesOnAccountPage']);
        }
        if (isset($moduleVars['showOnlyOwnEntries'])) {
            $this->setShowOnlyOwnEntries($moduleVars['showOnlyOwnEntries']);
        }
        if (isset($moduleVars['moderationGroupForCollections'])) {
            $this->setModerationGroupForCollections($moduleVars['moderationGroupForCollections']);
        }
        if (isset($moduleVars['moderationGroupForFiles'])) {
            $this->setModerationGroupForFiles($moduleVars['moderationGroupForFiles']);
        }
        if (isset($moduleVars['allowModerationSpecificCreatorForCollection'])) {
            $this->setAllowModerationSpecificCreatorForCollection($moduleVars['allowModerationSpecificCreatorForCollection']);
        }
        if (isset($moduleVars['allowModerationSpecificCreationDateForCollection'])) {
            $this->setAllowModerationSpecificCreationDateForCollection($moduleVars['allowModerationSpecificCreationDateForCollection']);
        }
        if (isset($moduleVars['allowModerationSpecificCreatorForFile'])) {
            $this->setAllowModerationSpecificCreatorForFile($moduleVars['allowModerationSpecificCreatorForFile']);
        }
        if (isset($moduleVars['allowModerationSpecificCreationDateForFile'])) {
            $this->setAllowModerationSpecificCreationDateForFile($moduleVars['allowModerationSpecificCreationDateForFile']);
        }
        if (isset($moduleVars['enabledFinderTypes'])) {
            $this->setEnabledFinderTypes($moduleVars['enabledFinderTypes']);
        }
    
        // prepare group selectors, fallback to admin group for undefined values
        $adminGroupId = GroupsConstant::GROUP_ID_ADMIN;
        $groupId = $this->getModerationGroupForCollections();
        if ($groupId < 1) {
            $groupId = $adminGroupId;
        }
    
        $this->setModerationGroupForCollections($this->groupRepository->find($groupId));
        $groupId = $this->getModerationGroupForFiles();
        if ($groupId < 1) {
            $groupId = $adminGroupId;
        }
    
        $this->setModerationGroupForFiles($this->groupRepository->find($groupId));
    }
    
    /**
     * Saves module variables into the database.
     */
    public function save()
    {
        // normalise group selector values
        $group = $this->getModerationGroupForCollections();
        $group = is_object($group) ? $group->getGid() : intval($group);
        $this->setModerationGroupForCollections($group);
        $group = $this->getModerationGroupForFiles();
        $group = is_object($group) ? $group->getGid() : intval($group);
        $this->setModerationGroupForFiles($group);
    
        $this->variableApi->set('MUFilesModule', 'allowedExtensions', $this->getAllowedExtensions());
        $this->variableApi->set('MUFilesModule', 'maxSize', $this->getMaxSize());
        $this->variableApi->set('MUFilesModule', 'onlyParent', $this->getOnlyParent());
        $this->variableApi->set('MUFilesModule', 'specialCollectionMenue', $this->getSpecialCollectionMenue());
        $this->variableApi->set('MUFilesModule', 'collectionEntriesPerPage', $this->getCollectionEntriesPerPage());
        $this->variableApi->set('MUFilesModule', 'linkOwnCollectionsOnAccountPage', $this->getLinkOwnCollectionsOnAccountPage());
        $this->variableApi->set('MUFilesModule', 'fileEntriesPerPage', $this->getFileEntriesPerPage());
        $this->variableApi->set('MUFilesModule', 'linkOwnFilesOnAccountPage', $this->getLinkOwnFilesOnAccountPage());
        $this->variableApi->set('MUFilesModule', 'showOnlyOwnEntries', $this->getShowOnlyOwnEntries());
        $this->variableApi->set('MUFilesModule', 'moderationGroupForCollections', $this->getModerationGroupForCollections());
        $this->variableApi->set('MUFilesModule', 'moderationGroupForFiles', $this->getModerationGroupForFiles());
        $this->variableApi->set('MUFilesModule', 'allowModerationSpecificCreatorForCollection', $this->getAllowModerationSpecificCreatorForCollection());
        $this->variableApi->set('MUFilesModule', 'allowModerationSpecificCreationDateForCollection', $this->getAllowModerationSpecificCreationDateForCollection());
        $this->variableApi->set('MUFilesModule', 'allowModerationSpecificCreatorForFile', $this->getAllowModerationSpecificCreatorForFile());
        $this->variableApi->set('MUFilesModule', 'allowModerationSpecificCreationDateForFile', $this->getAllowModerationSpecificCreationDateForFile());
        $this->variableApi->set('MUFilesModule', 'enabledFinderTypes', $this->getEnabledFinderTypes());
    }
}