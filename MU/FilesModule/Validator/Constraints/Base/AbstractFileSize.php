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

namespace MU\FilesModule\Validator\Constraints\Base;

use Symfony\Component\Validator\Constraint;

/**
 * File Size validation constraint.
 */
abstract class AbstractFileSize extends Constraint
{
    /**
     * Entity name
     * @var string
     */
    public $entityName = '';

    /**
     * Size of to upload file
     * @var integer
     */
    public $fileSize;


    /**
     * @inheritDoc
     */
    public function validatedBy()
    {
        return 'mu_files_module.validator.file_size.validator';
    }
}