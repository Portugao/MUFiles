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

namespace MU\FilesModule\HookProvider\Base;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig_Environment;
use Zikula\Bundle\HookBundle\Category\UiHooksCategory;
use Zikula\Bundle\HookBundle\Hook\DisplayHook;
use Zikula\Bundle\HookBundle\Hook\DisplayHookResponse;
use Zikula\Bundle\HookBundle\Hook\Hook;
use Zikula\Bundle\HookBundle\Hook\ProcessHook;
use Zikula\Bundle\HookBundle\Hook\ValidationHook;
use Zikula\Bundle\HookBundle\HookProviderInterface;
use Zikula\Bundle\HookBundle\ServiceIdTrait;
use Zikula\Common\Translator\TranslatorInterface;
use MU\FilesModule\Entity\Factory\EntityFactory;

/**
 * Base class for ui hooks provider.
 */
abstract class AbstractCollectionUiHooksProvider implements HookProviderInterface
{
    use ServiceIdTrait;

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var EntityFactory
     */
    protected $entityFactory;

    /**
     * @var Twig_Environment
     */
    protected $templating;

    /**
     * CollectionUiHooksProvider constructor.
     *
     * @param TranslatorInterface $translator
     * @param RequestStack        $requestStack
     * @param EntityFactory       $entityFactory
     * @param Twig_Environment    $twig
     */
    public function __construct(
        TranslatorInterface $translator,
        RequestStack $requestStack,
        EntityFactory $entityFactory,
        Twig_Environment $twig
    ) {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
        $this->entityFactory = $entityFactory;
        $this->templating = $twig;
    }

    /**
     * @inheritDoc
     */
    public function getOwner()
    {
        return 'MUFilesModule';
    }
    
    /**
     * @inheritDoc
     */
    public function getCategory()
    {
        return UiHooksCategory::NAME;
    }
    
    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->translator->__('Collection ui hooks provider');
    }

    /**
     * @inheritDoc
     */
    public function getProviderTypes()
    {
        return [
            UiHooksCategory::TYPE_DISPLAY_VIEW => 'view',
            UiHooksCategory::TYPE_FORM_EDIT => 'displayEdit',
            UiHooksCategory::TYPE_VALIDATE_EDIT => 'validateEdit',
            UiHooksCategory::TYPE_PROCESS_EDIT => 'processEdit',
            UiHooksCategory::TYPE_FORM_DELETE => 'displayDelete',
            UiHooksCategory::TYPE_VALIDATE_DELETE => 'validateDelete',
            UiHooksCategory::TYPE_PROCESS_DELETE => 'processDelete'
        ];
    }

    /**
     * Display hook for view/display templates.
     *
     * @param DisplayHook $hook
     */
    public function view(DisplayHook $hook)
    {
        $response = $this->renderDisplayHookResponse($hook, 'hookDisplayView');
        $hook->setResponse($response);
    }

    /**
     * Display hook for create/edit forms.
     *
     * @param DisplayHook $hook
     */
    public function displayEdit(DisplayHook $hook)
    {
        $response = $this->renderDisplayHookResponse($hook, 'hookDisplayEdit');
        $hook->setResponse($response);
    }

    /**
     * Validate input from an item to be edited.
     *
     * @param ValidationHook $hook
     */
    public function validateEdit(ValidationHook $hook)
    {
        return true;
    }

    /**
     * Perform the final update actions for an edited item.
     *
     * @param ProcessHook $hook
     */
    public function processEdit(ProcessHook $hook)
    {
        $url = $hook->getUrl();
        if (null === $url || !is_object($url)) {
            return;
        }
        $url = $url->toArray();

        $entityManager = $this->entityFactory->getObjectManager();

        // update url information for assignments of updated data object
        $qb = $entityManager->createQueryBuilder();
        $qb->select('tbl')
           ->from($this->getHookAssignmentEntity(), 'tbl');
        $qb = $this->addContextFilters($qb, $hook);

        $query = $qb->getQuery();
        $assignments = $query->getResult();

        foreach ($assignments as $assignment) {
            $assignment->setSubscriberUrl($url);
        }

        $entityManager->flush();
    }

    /**
     * Display hook for delete forms.
     *
     * @param DisplayHook $hook
     */
    public function displayDelete(DisplayHook $hook)
    {
        $response = $this->renderDisplayHookResponse($hook, 'hookDisplayDelete');
        $hook->setResponse($response);
    }

    /**
     * Validate input from an item to be deleted.
     *
     * @param ValidationHook $hook
     */
    public function validateDelete(ValidationHook $hook)
    {
        return true;
    }

    /**
     * Perform the final delete actions for a deleted item.
     *
     * @param ProcessHook $hook
     */
    public function processDelete(ProcessHook $hook)
    {
        // delete assignments of removed data object
        $qb = $this->entityFactory->getObjectManager()->createQueryBuilder();
        $qb->delete($this->getHookAssignmentEntity(), 'tbl');
        $qb = $this->addContextFilters($qb, $hook);

        $query = $qb->getQuery();
        $query->execute();
    }

    /**
     * Returns the area name used by this provider.
     *
     * @return string
     */
    protected function getAreaName()
    {
        return 'provider.mufilesmodule.ui_hooks.collections';
    }

    /**
     * Returns the entity for hook assignment data.
     *
     * @return string
     */
    protected function getHookAssignmentEntity()
    {
        return 'MU\FilesModule\Entity\HookAssignmentEntity';
    }

    /**
     * Adds common hook-based filters to a given query builder.
     *
     * @param QueryBuilder $qb
     * @param Hook $hook
     *
     * @return QueryBuilder
     */
    protected function addContextFilters(QueryBuilder $qb, Hook $hook)
    {
        $qb->where('tbl.subscriberOwner = :moduleName')
           ->setParameter('moduleName', $hook->getCaller())
           ->andWhere('tbl.subscriberAreaId = :areaId')
           ->setParameter('areaId', $hook->getAreaId())
           ->andWhere('tbl.subscriberObjectId = :objectId')
           ->setParameter('objectId', $hook->getId())
           ->andWhere('tbl.assignedEntity = :objectType')
           ->setParameter('objectType', 'collection');

        return $qb;
    }

    /**
     * Returns a list of assigned entities for a given hook context.
     *
     * @param Hook $hook
     *
     * @return array
     */
    protected function selectAssignedEntities(Hook $hook)
    {
        list ($assignments, $assignedIds) = $this->selectAssignedIds($hook);
        if (!count($assignedIds)) {
            return [[], []];
        }

        $entities = $this->entityFactory->getRepository('collection')->selectByIdList($assignedIds);

        return [$assignments, $entities];
    }

    /**
     * Returns a list of assigned entity identifiers for a given hook context.
     *
     * @param Hook $hook
     *
     * @return array
     */
    protected function selectAssignedIds(Hook $hook)
    {
        $qb = $this->entityFactory->getObjectManager()->createQueryBuilder();
        $qb->select('tbl')
           ->from($this->getHookAssignmentEntity(), 'tbl');
        $qb = $this->addContextFilters($qb, $hook);
        $qb->add('orderBy', 'tbl.updatedDate DESC');

        $query = $qb->getQuery();
        $assignments = $query->getResult();

        $assignedIds = [];
        foreach ($assignments as $assignment) {
            $assignedIds[] = $assignment->getAssignedId();
        }

        return [$assignments, $assignedIds];
    }

    /**
     * Returns the response for a display hook of a given context.
     *
     * @param Hook   $hook
     * @param string $context
     *
     * @return DisplayHookResponse
     */
    protected function renderDisplayHookResponse(Hook $hook, $context)
    {
        list ($assignments, $assignedEntities) = $this->selectAssignedEntities($hook);
        $template = '@MUFilesModule/Collection/includeDisplayItemListMany.html.twig';

        $templateParameters = [
            'items' => $assignedEntities,
            'context' => $context,
            'routeArea' => ''
        ];

        if ($context == 'hookDisplayView') {
            // add context information to template parameters in order to provide means
            // for adding new assignments and removing existing assignments
            $templateParameters['assignments'] = $assignments;
            $templateParameters['subscriberOwner'] = $hook->getCaller();
            $templateParameters['subscriberAreaId'] = $hook->getAreaId();
            $templateParameters['subscriberObjectId'] = $hook->getId();
            $url = $hook->getUrl();
            $templateParameters['subscriberUrl'] = (null !== $url && is_object($url)) ? $url->serialize() : serialize([]);
        }

        $output = $this->templating->render($template, $templateParameters);

        return new DisplayHookResponse($this->getAreaName(), $output);
    }
}
