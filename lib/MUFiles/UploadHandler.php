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
 * @version Generated by ModuleStudio 0.6.0 (http://modulestudio.de) at Tue Aug 06 08:44:10 CEST 2013.
 */

/**
 * Upload handler implementation class.
 */
class MUFiles_UploadHandler extends MUFiles_Base_UploadHandler
{
    /**
     * Constructor initialising the supported object types.
     */
    public function __construct()
    {
        $this->allowedObjectTypes = array('file');
        $this->imageFileTypes = array('gif', 'jpeg', 'jpg', 'png', 'swf');
        $this->forbiddenFileTypes = array('cgi', 'pl', 'asp', 'phtml', 'php', 'php3', 'php4', 'php5', 'exe', 'com', 'bat', 'jsp', 'cfm', 'shtml');
        $this->allowedFileSizes = array('file' => array('uploadFile' => ModUtil::getVar('MUFiles', 'maxSize')));
    }
    
    /**
     * Determines the allowed file extensions for a given object type.
     *
     * @param string $objectType Currently treated entity type.
     * @param string $fieldName  Name of upload field.
     * @param string $extension  Input file extension.
     *
     * @return array the list of allowed file extensions
     */
    protected function isAllowedFileExtension($objectType, $fieldName, $extension)
    {
        // determine the allowed extensions
        $allowedExtensions = array();
        switch ($objectType) {
            case 'file':
                $allowedExtensions = explode(',', ModUtil::getVar('allowedExtension'));
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
}
