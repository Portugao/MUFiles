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

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

use DoctrineExtensions\Paginate\Paginate;

/**
 * Repository class used to implement own convenience methods for performing certain DQL queries.
 *
 * This is the concrete repository class for collection entities.
 */
class MUFiles_Entity_Repository_Collection extends MUFiles_Entity_Repository_Base_Collection
{
    /**
     * Adds default filters as where clauses.
     *
     * @param Doctrine\ORM\QueryBuilder $qb         Query builder to be enhanced.
     * @param array                     $parameters List of determined filter options.
     *
     * @return Doctrine\ORM\QueryBuilder Enriched query builder instance.
     */
    protected function applyDefaultFilters(QueryBuilder $qb, $parameters = array())
    {
        $currentModule = ModUtil::getName();//FormUtil::getPassedValue('module', '', 'GETPOST');
        $currentLegacyControllerType = FormUtil::getPassedValue('lct', 'user', 'GETPOST');
        if ($currentLegacyControllerType == 'admin' && $currentModule == 'MUFiles') {
            return $qb;
        }
    
        if (!in_array('workflowState', array_keys($parameters)) || empty($parameters['workflowState'])) {
            // per default we show approved collections only
            $onlineStates = array('approved');
            $qb->andWhere('tbl.workflowState IN (:onlineStates)')
               ->setParameter('onlineStates', $onlineStates)
               ->andWhere('tbl.inFrontend = :state')
               ->setParameter('state', 1);
        }
    
        return $qb;
    }
}
