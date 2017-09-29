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

namespace MU\FilesModule\Twig;

use MU\FilesModule\Twig\Base\AbstractTwigExtension;

/**
 * Twig extension implementation class.
 */
class TwigExtension extends AbstractTwigExtension
{
	/**
	 * Returns a list of custom Twig functions.
	 *
	 * @return \Twig_SimpleFunction[]
	 */
	public function getFunctions()
	{
		$functions = parent::getFunctions();
		$functions[] = new \Twig_SimpleFunction('mufilesmodule_specialMenu', [$this, 'getSpecialMenu']);
		return $functions;
	}
	/**
	 * @return output
	 */
	public function getSpecialMenu($collectionId, $fileId)
	{
		return $this->listHelper->getCollectionMenue($collectionId, $fileId);
	}
    // feel free to add your own Twig extension methods here
}
