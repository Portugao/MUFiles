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

namespace MU\FilesModule\Helper;

use MU\FilesModule\Helper\Base\AbstractUploadHelper;

/**
 * Helper implementation class for upload handling.
 */
class UploadHelper extends AbstractUploadHelper
{
    /**
     * Determines the allowed file extensions for a given object type.
     *
     * @param string $objectType Currently treated entity type
     * @param string $fieldName  Name of upload field
     * @param string $extension  Input file extension
     *
     * @return array the list of allowed file extensions
     */
    protected function isAllowedFileExtension($objectType, $fieldName, $extension)
    {
        // determine the allowed extensions
        $allowedExtensions = array();
        switch ($objectType) {
            case 'file':
                $allowedExtensions = explode(',', $this->moduleVars['allowedExtensions']);
                    break;
        }
    
        if (count($allowedExtensions) > 0) {
            if (!in_array($extension, $allowedExtensions)) {
                return false;
            }
        }
    
        if (in_array($extension, $this->forbiddenFileTypes)) {
            return false;
        }
    
        return true;
    }
    
    /**
     * Creates all required upload folders for this application.
     *
     * @return Boolean Whether everything went okay or not
     */
    public function checkAndCreateAllUploadFolders()
    {
    	$result = true;
    
    	$result &= $this->checkAndCreateUploadFolder('file', 'uploadFile', $this->moduleVars['allowedExtensions']);
    
    	return $result;
    }
}
