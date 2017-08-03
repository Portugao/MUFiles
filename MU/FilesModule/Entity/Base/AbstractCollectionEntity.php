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

namespace MU\FilesModule\Entity\Base;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Zikula\Core\Doctrine\EntityAccess;
use MU\FilesModule\Traits\StandardFieldsTrait;
use MU\FilesModule\Validator\Constraints as FilesAssert;

/**
 * Entity class that defines the entity structure and behaviours.
 *
 * This is the base entity class for collection entities.
 * The following annotation marks it as a mapped superclass so subclasses
 * inherit orm properties.
 *
 * @ORM\MappedSuperclass
 */
abstract class AbstractCollectionEntity extends EntityAccess
{
    /**
     * Hook standard fields behaviour embedding createdBy, updatedBy, createdDate, updatedDate fields.
     */
    use StandardFieldsTrait;

    /**
     * @var string The tablename this object maps to
     */
    protected $_objectType = 'collection';
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer", unique=true)
     * @var integer $id
     */
    protected $id = 0;
    
    /**
     * the current workflow state
     * @ORM\Column(length=20)
     * @Assert\NotBlank()
     * @FilesAssert\ListEntry(entityName="collection", propertyName="workflowState", multiple=false)
     * @var string $workflowState
     */
    protected $workflowState = 'initial';
    
    /**
     * @ORM\Column(length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min="0", max="255")
     * @var string $name
     */
    protected $name = '';
    
    /**
     * @ORM\Column(type="text", length=2000)
     * @Assert\NotNull()
     * @Assert\Length(min="0", max="2000")
     * @var text $description
     */
    protected $description = '';
    
    /**
     * @ORM\Column(type="integer")
     * @var integer $parentid
     */
    protected $parentid = 0;
    
    /**
     * @ORM\Column(type="boolean")
     * @Assert\NotNull()
     * @Assert\Type(type="bool")
     * @var boolean $inFrontend
     */
    protected $inFrontend = false;
    
    
    /**
     * @ORM\OneToMany(targetEntity="\MU\FilesModule\Entity\CollectionCategoryEntity", 
     *                mappedBy="entity", cascade={"all"}, 
     *                orphanRemoval=true)
     * @var \MU\FilesModule\Entity\CollectionCategoryEntity
     */
    protected $categories = null;
    
    /**
     * Unidirectional - One collection [collection] has many collections [collections] (INVERSE SIDE).
     *
     * @ORM\ManyToMany(targetEntity="MU\FilesModule\Entity\CollectionEntity")
     * @ORM\JoinTable(name="mu_files_collectioncollections",
     *      joinColumns={@ORM\JoinColumn(name="parentid", referencedColumnName="id" )},
     *      inverseJoinColumns={@ORM\JoinColumn(name="id", referencedColumnName="id" )}
     * )
     * @var \MU\FilesModule\Entity\CollectionEntity[] $collections
     */
    protected $collections = null;
    
    /**
     * Unidirectional - One aliascollection [collection] has many alilasfiles [files] (INVERSE SIDE).
     *
     * @ORM\ManyToMany(targetEntity="MU\FilesModule\Entity\FileEntity")
     * @ORM\JoinTable(name="mu_files_aliascollectionalilasfiles")
     * @var \MU\FilesModule\Entity\FileEntity[] $alilasfiles
     */
    protected $alilasfiles = null;
    
    
    /**
     * CollectionEntity constructor.
     *
     * Will not be called by Doctrine and can therefore be used
     * for own implementation purposes. It is also possible to add
     * arbitrary arguments as with every other class method.
     */
    public function __construct()
    {
        $this->collections = new ArrayCollection();
        $this->alilasfiles = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }
    
    /**
     * Returns the _object type.
     *
     * @return string
     */
    public function get_objectType()
    {
        return $this->_objectType;
    }
    
    /**
     * Sets the _object type.
     *
     * @param string $_objectType
     *
     * @return void
     */
    public function set_objectType($_objectType)
    {
        if ($this->_objectType != $_objectType) {
            $this->_objectType = $_objectType;
        }
    }
    
    
    /**
     * Returns the id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Sets the id.
     *
     * @param integer $id
     *
     * @return void
     */
    public function setId($id)
    {
        if (intval($this->id) !== intval($id)) {
            $this->id = intval($id);
        }
    }
    
    /**
     * Returns the workflow state.
     *
     * @return string
     */
    public function getWorkflowState()
    {
        return $this->workflowState;
    }
    
    /**
     * Sets the workflow state.
     *
     * @param string $workflowState
     *
     * @return void
     */
    public function setWorkflowState($workflowState)
    {
        if ($this->workflowState !== $workflowState) {
            $this->workflowState = isset($workflowState) ? $workflowState : '';
        }
    }
    
    /**
     * Returns the name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Sets the name.
     *
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        if ($this->name !== $name) {
            $this->name = isset($name) ? $name : '';
        }
    }
    
    /**
     * Returns the description.
     *
     * @return text
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Sets the description.
     *
     * @param text $description
     *
     * @return void
     */
    public function setDescription($description)
    {
        if ($this->description !== $description) {
            $this->description = isset($description) ? $description : '';
        }
    }
    
    /**
     * Returns the parentid.
     *
     * @return integer
     */
    public function getParentid()
    {
        return $this->parentid;
    }
    
    /**
     * Sets the parentid.
     *
     * @param integer $parentid
     *
     * @return void
     */
    public function setParentid($parentid)
    {
        if (intval($this->parentid) !== intval($parentid)) {
            $this->parentid = intval($parentid);
        }
    }
    
    /**
     * Returns the in frontend.
     *
     * @return boolean
     */
    public function getInFrontend()
    {
        return $this->inFrontend;
    }
    
    /**
     * Sets the in frontend.
     *
     * @param boolean $inFrontend
     *
     * @return void
     */
    public function setInFrontend($inFrontend)
    {
        if (boolval($this->inFrontend) !== boolval($inFrontend)) {
            $this->inFrontend = boolval($inFrontend);
        }
    }
    
    /**
     * Returns the categories.
     *
     * @return ArrayCollection[]
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    
    /**
     * Sets the categories.
     *
     * @param ArrayCollection $categories
     *
     * @return void
     */
    public function setCategories(ArrayCollection $categories)
    {
        foreach ($this->categories as $category) {
            if (false === $key = $this->collectionContains($categories, $category)) {
                $this->categories->removeElement($category);
            } else {
                $categories->remove($key);
            }
        }
        foreach ($categories as $category) {
            $this->categories->add($category);
        }
    }
    
    /**
     * Checks if a collection contains an element based only on two criteria (categoryRegistryId, category).
     *
     * @param ArrayCollection $collection
     * @param \MU\FilesModule\Entity\CollectionCategoryEntity $element
     *
     * @return bool|int
     */
    private function collectionContains(ArrayCollection $collection, \MU\FilesModule\Entity\CollectionCategoryEntity $element)
    {
        foreach ($collection as $key => $category) {
            /** @var \MU\FilesModule\Entity\CollectionCategoryEntity $category */
            if ($category->getCategoryRegistryId() == $element->getCategoryRegistryId()
                && $category->getCategory() == $element->getCategory()
            ) {
                return $key;
            }
        }
    
        return false;
    }
    
    /**
     * Returns the collections.
     *
     * @return \MU\FilesModule\Entity\CollectionEntity[]
     */
    public function getCollections()
    {
        return $this->collections;
    }
    
    /**
     * Sets the collections.
     *
     * @param \MU\FilesModule\Entity\CollectionEntity[] $collections
     *
     * @return void
     */
    public function setCollections($collections)
    {
        foreach ($this->collections as $collectionSingle) {
            $this->removeCollections($collectionSingle);
        }
        foreach ($collections as $collectionSingle) {
            $this->addCollections($collectionSingle);
        }
    }
    
    /**
     * Adds an instance of \MU\FilesModule\Entity\CollectionEntity to the list of collections.
     *
     * @param \MU\FilesModule\Entity\CollectionEntity $collection The instance to be added to the collection
     *
     * @return void
     */
    public function addCollections(\MU\FilesModule\Entity\CollectionEntity $collection)
    {
        $this->collections->add($collection);
    }
    
    /**
     * Removes an instance of \MU\FilesModule\Entity\CollectionEntity from the list of collections.
     *
     * @param \MU\FilesModule\Entity\CollectionEntity $collection The instance to be removed from the collection
     *
     * @return void
     */
    public function removeCollections(\MU\FilesModule\Entity\CollectionEntity $collection)
    {
        $this->collections->removeElement($collection);
    }
    
    /**
     * Returns the alilasfiles.
     *
     * @return \MU\FilesModule\Entity\FileEntity[]
     */
    public function getAlilasfiles()
    {
        return $this->alilasfiles;
    }
    
    /**
     * Sets the alilasfiles.
     *
     * @param \MU\FilesModule\Entity\FileEntity[] $alilasfiles
     *
     * @return void
     */
    public function setAlilasfiles($alilasfiles)
    {
        foreach ($this->alilasfiles as $fileSingle) {
            $this->removeAlilasfiles($fileSingle);
        }
        foreach ($alilasfiles as $fileSingle) {
            $this->addAlilasfiles($fileSingle);
        }
    }
    
    /**
     * Adds an instance of \MU\FilesModule\Entity\FileEntity to the list of alilasfiles.
     *
     * @param \MU\FilesModule\Entity\FileEntity $file The instance to be added to the collection
     *
     * @return void
     */
    public function addAlilasfiles(\MU\FilesModule\Entity\FileEntity $file)
    {
        $this->alilasfiles->add($file);
    }
    
    /**
     * Removes an instance of \MU\FilesModule\Entity\FileEntity from the list of alilasfiles.
     *
     * @param \MU\FilesModule\Entity\FileEntity $file The instance to be removed from the collection
     *
     * @return void
     */
    public function removeAlilasfiles(\MU\FilesModule\Entity\FileEntity $file)
    {
        $this->alilasfiles->removeElement($file);
    }
    
    
    
    /**
     * Creates url arguments array for easy creation of display urls.
     *
     * @return array The resulting arguments list
     */
    public function createUrlArgs()
    {
        return [
            'id' => $this->getId()
        ];
    }
    
    /**
     * Returns the primary key.
     *
     * @return integer The identifier
     */
    public function getKey()
    {
        return $this->getId();
    }
    
    /**
     * Determines whether this entity supports hook subscribers or not.
     *
     * @return boolean
     */
    public function supportsHookSubscribers()
    {
        return true;
    }
    
    /**
     * Return lower case name of multiple items needed for hook areas.
     *
     * @return string
     */
    public function getHookAreaPrefix()
    {
        return 'mufilesmodule.ui_hooks.collections';
    }
    
    /**
     * Returns an array of all related objects that need to be persisted after clone.
     * 
     * @param array $objects The objects are added to this array. Default: []
     * 
     * @return array of entity objects
     */
    public function getRelatedObjectsToPersist(&$objects = []) 
    {
        return [];
    }
    
    /**
     * ToString interceptor implementation.
     * This method is useful for debugging purposes.
     *
     * @return string The output string for this entity
     */
    public function __toString()
    {
        return 'Collection ' . $this->getKey() . ': ' . $this->getName();
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
     */
    public function __clone()
    {
        // if the entity has no identity do nothing, do NOT throw an exception
        if (!$this->id) {
            return;
        }
    
        // otherwise proceed
    
        // unset identifier
        $this->setId(0);
    
        // reset workflow
        $this->setWorkflowState('initial');
    
        $this->setCreatedBy(null);
        $this->setCreatedDate(null);
        $this->setUpdatedBy(null);
        $this->setUpdatedDate(null);
    
    
        // clone categories
        $categories = $this->categories;
        $this->categories = new ArrayCollection();
        foreach ($categories as $c) {
            $newCat = clone $c;
            $this->categories->add($newCat);
            $newCat->setEntity($this);
        }
    }
}
