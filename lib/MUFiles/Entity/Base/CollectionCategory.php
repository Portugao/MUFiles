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

use Doctrine\ORM\Mapping as ORM;

/**
 * Entity extension domain class storing collection categories.
 *
 * This is the base category class for collection entities.
 */
class MUFiles_Entity_Base_CollectionCategory extends Zikula_Doctrine2_Entity_EntityCategory
{
    /**
     * @ORM\ManyToOne(targetEntity="MUFiles_Entity_Collection", inversedBy="categories")
     * @ORM\JoinColumn(name="entityId", referencedColumnName="id")
     * @var MUFiles_Entity_Collection
     */
    protected $entity;
    
    /**
     * Get reference to owning entity.
     *
     * @return MUFiles_Entity_Collection
     */
    public function getEntity()
    {
        return $this->entity;
    }
    
    /**
     * Set reference to owning entity.
     *
     * @param MUFiles_Entity_Collection $entity
     */
    public function setEntity(/*MUFiles_Entity_Collection */$entity)
    {
        $this->entity = $entity;
    }
}
