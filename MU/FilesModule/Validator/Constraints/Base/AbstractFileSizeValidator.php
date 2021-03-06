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
use Symfony\Component\Validator\ConstraintValidator;
use Zikula\Common\Translator\TranslatorInterface;
use Zikula\Common\Translator\TranslatorTrait;
use Zikula\ExtensionsModule\Api\ApiInterface\VariableApiInterface;
use MU\FilesModule\Helper\UploadHelper;

/**
 * File size validator.
 */
abstract class AbstractFileSizeValidator extends ConstraintValidator
{
    use TranslatorTrait;
    
    /**
     * @var VariableApiInterface
     */
    protected $variableApi;
    
    /**
     * @var UploadHelper
     */
    protected $uploadHelper;
    

    /**
     * ListEntryValidator constructor.
     *
     * @param TranslatorInterface $translator        Translator service instance
     * @param VariableApiInterface $variableApi      VariableApiInterface service instance
     * @param UploadHelper $uploadHelper             UploadHelper service instance
     */
    public function __construct(TranslatorInterface $translator, VariableApiInterface $variableApi, UploadHelper $uploadHelper)
    {
        $this->setTranslator($translator);
        $this->variableApi = $variableApi;
        $this->uploadHelper = $uploadHelper;
    }

    /**
     * Sets the translator.
     *
     * @param TranslatorInterface $translator Translator service instance
     */
    public function setTranslator(/*TranslatorInterface */$translator)
    {
        $this->translator = $translator;
    }

    /**
     * @inheritDoc
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }

        $pos = strrpos($value, '/');
        $filePath = substr($value, 0, $pos);
        $fileName = substr($value, $pos + 1);

        // we get the in the setting set max size for uploads
    	$maxSize = $this->variableApi->get('MUFilesModule', 'maxSize');
    	// we get the meta datas for the current file
    	$metaData = $this->uploadHelper->readMetaDataForFile($fileName, $value);
    	$currentSize = $metaData['size'];
    	// we calculate the amount of bytes
    	$posk = strrpos($maxSize, 'k');
    	if ($posk != false) {
    		$maxSizeBytes = $maxSize * 1024;
    		$currentSize = $currentSize / 1024;
    		$currentSize = number_format($currentSize, 2) . ' ' . $this->__('KB');
    	}
    	$posk = strrpos($maxSize, 'M');
    	if ($posk != false) {
    		$maxSizeBytes = $maxSize * 1024 * 1024;
    		$currentSize = $currentSize / 1024 / 1024;
    		$currentSize = number_format($currentSize, 2) . ' ' . $this->__('MB');
    	}
   	
    	if ($metaData['size'] > $maxSizeBytes) {
    		$this->context->buildViolation(
    		    $this->__f('The size of your file is "%thisSize%". Allowed is only a size of "%allowedSize%"!', [
    				'%thisSize%' => $currentSize,
    		    	'%allowedSize%' => $maxSize
    		    ])
    			)->addViolation();
    	}
    }
}
