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

namespace MU\FilesModule\Listener\Base;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zikula\Core\Event\GenericEvent;
use Zikula\MailerModule\MailerEvents;

/**
 * Event handler base class for mailing events.
 */
abstract class AbstractMailerListener implements EventSubscriberInterface
{
    /**
     * Makes our handlers known to the event system.
     */
    public static function getSubscribedEvents()
    {
        return [
            MailerEvents::SEND_MESSAGE_START   => ['sendMessageStart', 5],
            MailerEvents::SEND_MESSAGE_PERFORM => ['sendMessagePerform', 5],
            MailerEvents::SEND_MESSAGE_SUCCESS => ['sendMessageSuccess', 5],
            MailerEvents::SEND_MESSAGE_FAILURE => ['sendMessageFailure', 5]
        ];
    }
    
    /**
     * Listener for the `module.mailer.api.sendmessage` event.
     * Occurs when a new message should be sent.
     *
     * Invoked from `Zikula\MailerModule\Api\MailerApi#sendMessage`.
     * Subject is `Zikula\MailerModule\Api\MailerApi` with `SwiftMessage $message` object.
     * This is a notifyUntil event so the event must `$event->stopPropagation()` and set any
     * return data into `$event->data`, or `$event->setData()`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     * @param GenericEvent $event The event instance
     */
    public function sendMessageStart(GenericEvent $event)
    {
    }
    
    /**
     * Listener for the `module.mailer.api.perform` event.
     * Occurs right before a message is sent.
     *
     * Invoked from `Zikula\MailerModule\Api\MailerApi#sendMessage`.
     * Subject is `Zikula\MailerModule\Api\MailerApi` with `SwiftMessage $message` object.
     * This is a notifyUntil event so the event must `$event->stopPropagation()` and set any
     * return data into `$event->data`, or `$event->setData()`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     * @param GenericEvent $event The event instance
     */
    public function sendMessagePerform(GenericEvent $event)
    {
    }
    
    /**
     * Listener for the `module.mailer.api.success` event.
     * Occurs after a message has been sent successfully.
     *
     * Invoked from `Zikula\MailerModule\Api\MailerApi#performSending`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     * @param GenericEvent $event The event instance
     */
    public function sendMessageSuccess(GenericEvent $event)
    {
    }
    
    /**
     * Listener for the `module.mailer.api.failure` event.
     * Occurs when a message could not be sent.
     *
     * Invoked from `Zikula\MailerModule\Api\MailerApi#performSending`.
     *
     * You can access general data available in the event.
     *
     * The event name:
     *     `echo 'Event: ' . $event->getName();`
     *
     * The current request's type: `MASTER_REQUEST` or `SUB_REQUEST`.
     * If a listener should only be active for the master request,
     * be sure to check that at the beginning of your method.
     *     `if ($event->getRequestType() !== HttpKernelInterface::MASTER_REQUEST) {
     *         return;
     *     }`
     *
     * The kernel instance handling the current request:
     *     `$kernel = $event->getKernel();`
     *
     * The currently handled request:
     *     `$request = $event->getRequest();`
     *
     * @param GenericEvent $event The event instance
     */
    public function sendMessageFailure(GenericEvent $event)
    {
    }
}
