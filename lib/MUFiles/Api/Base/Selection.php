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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Tue Aug 06 08:44:09 CEST 2013.
 */

/**
 * Selection api base class.
 */
class MUFiles_Api_Base_Selection extends Zikula_AbstractApi
{
    /**
     * Gets the list of identifier fields for a given object type.
     *
     * @param string $args['ot'] The object type to be treated (optional).
     *
     * @return array List of identifier field names.
     */
    public function getIdFields(array $args = array())
    {
        $objectType = $this->determineObjectType($args, 'getIdFields');
        $entityClass = 'MUFiles_Entity_' . ucfirst($objectType);
        $objectTemp = new $entityClass(); 
        $idFields = $objectTemp->get_idFields();
    
        return $idFields;
    }
    
    /**
     * Selects a single entity.
     *
     * @param string  $args['ot']       The object type to retrieve (optional).
     * @param mixed   $args['id']       The id (or array of ids) to use to retrieve the object (default=null).
     * @param boolean $args['useJoins'] Whether to include joining related objects (optional) (default=true).
     * @param boolean $args['slimMode'] If activated only some basic fields are selected without using any joins (optional) (default=false).
     *
     * @return mixed Desired entity object or null.
     */
    public function getEntity(array $args = array())
    {
        if (!isset($args['id'])) {
            return LogUtil::registerArgsError();
        }
        $objectType = $this->determineObjectType($args, 'getEntity');
        $repository = $this->getRepository($objectType);
    
        $idValues = $args['id'];
        $useJoins = isset($args['useJoins']) ? ((bool) $args['useJoins']) : true;
        $slimMode = isset($args['slimMode']) ? ((bool) $args['slimMode']) : false;
    
        $entity = $repository->selectById($idValues, $useJoins, $slimMode);
    
        return $entity;
    }
    
    /**
     * Selects a list of entities by different criteria.
     *
     * @param string  $args['ot']       The object type to retrieve (optional).
     * @param string  $args['where']    The where clause to use when retrieving the collection (optional) (default='').
     * @param string  $args['orderBy']  The order-by clause to use when retrieving the collection (optional) (default='').
     * @param boolean $args['useJoins'] Whether to include joining related objects (optional) (default=true).
     * @param boolean $args['slimMode'] If activated only some basic fields are selected without using any joins (optional) (default=false).
     *
     * @return Array with retrieved collection.
     */
    public function getEntities(array $args = array())
    {
        $objectType = $this->determineObjectType($args, 'getEntities');
        $repository = $this->getRepository($objectType);
    
        $where = isset($args['where']) ? $args['where'] : '';
        $orderBy = isset($args['orderBy']) ? $args['orderBy'] : '';
        $useJoins = isset($args['useJoins']) ? ((bool) $args['useJoins']) : true;
        $slimMode = isset($args['slimMode']) ? ((bool) $args['slimMode']) : false;
    
        return $repository->selectWhere($where, $orderBy, $useJoins, $slimMode);
    }
    
    /**
     * Selects a list of entities by different criteria.
     *
     * @param string  $args['ot']             The object type to retrieve (optional).
     * @param string  $args['where']          The where clause to use when retrieving the collection (optional) (default='').
     * @param string  $args['orderBy']        The order-by clause to use when retrieving the collection (optional) (default='').
     * @param integer $args['currentPage']    Where to start selection.
     * @param integer $args['resultsPerPage'] Amount of items to select.
     * @param boolean $args['useJoins']       Whether to include joining related objects (optional) (default=true).
     * @param boolean $args['slimMode']       If activated only some basic fields are selected without using any joins (optional) (default=false).
     *
     * @return Array with retrieved collection and amount of total records affected by this query.
     */
    public function getEntitiesPaginated(array $args = array())
    {
        $objectType = $this->determineObjectType($args, 'getEntitiesPaginated');
        $repository = $this->getRepository($objectType);
    
        $where = isset($args['where']) ? $args['where'] : '';
        $orderBy = isset($args['orderBy']) ? $args['orderBy'] : '';
        $currentPage = isset($args['currentPage']) ? $args['currentPage'] : 1;
        $resultsPerPage = isset($args['resultsPerPage']) ? $args['resultsPerPage'] : 25;
        $useJoins = isset($args['useJoins']) ? ((bool) $args['useJoins']) : true;
        $slimMode = isset($args['slimMode']) ? ((bool) $args['slimMode']) : false;
    
        return $repository->selectWherePaginated($where, $orderBy, $currentPage, $resultsPerPage, $useJoins, $slimMode);
    }
    
    /**
     * Determines object type using controller util methods.
     *
     * @param string $args['ot'] The object type to retrieve (optional).
     * @param string $methodName Name of calling method.
     *
     * @return string the object type.
     */
    protected function determineObjectType(array $args = array(), $methodName = '')
    {
        $objectType = isset($args['ot']) ? $args['ot'] : '';
        $controllerHelper = new MUFiles_Util_Controller($this->serviceManager);
        $utilArgs = array('api' => 'selection', 'action' => $methodName);
        if (!in_array($objectType, $controllerHelper->getObjectTypes('api', $utilArgs))) {
            $objectType = $controllerHelper->getDefaultObjectType('api', $utilArgs);
        }
    
        return $objectType;
    }
    
    /**
     * Returns repository instance for a certain object type.
     *
     * @param string $objectType The desired object type.
     *
     * @return mixed Repository class instance or null.
     */
    protected function getRepository($objectType = '')
    {
        if (empty($objectType)) {
            return LogUtil::registerArgsError();
        }
    
        $entityClass = 'MUFiles_Entity_' . ucwords($objectType);
    
        return $this->entityManager->getRepository($entityClass);
    }
}
