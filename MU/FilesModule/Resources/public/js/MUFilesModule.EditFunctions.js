'use strict';

/**
 * Resets the value of an upload / file input field.
 */
function mUFilesResetUploadField(fieldName) {
    jQuery('#' + fieldName).attr('type', 'input');
    jQuery('#' + fieldName).attr('type', 'file');
}

/**
 * Initialises the reset button for a certain upload input.
 */
function mUFilesInitUploadField(fieldName) {
    jQuery('#' + fieldName + 'ResetVal').click(function (event) {
        event.preventDefault();
        mUFilesResetUploadField(fieldName);
    }).removeClass('hidden');
}

var editedObjectType;
var editedEntityId;
var editForm;
var formButtons;
var triggerValidation = true;

function mUFilesTriggerFormValidation() {
    mUFilesExecuteCustomValidationConstraints(editedObjectType, editedEntityId);

    if (!editForm.get(0).checkValidity()) {
        // This does not really submit the form,
        // but causes the browser to display the error message
        editForm.find(':submit').first().click();
    }
}

function mUFilesHandleFormSubmit(event) {
    if (triggerValidation) {
        mUFilesTriggerFormValidation();
        if (!editForm.get(0).checkValidity()) {
            event.preventDefault();
            return false;
        }
    }

    // hide form buttons to prevent double submits by accident
    formButtons.each(function (index) {
        jQuery(this).addClass('hidden');
    });

    return true;
}

/**
 * Initialises an entity edit form.
 */
function mUFilesInitEditForm(mode, entityId) {
    if (jQuery('.mufiles-edit-form').length < 1) {
        return;
    }

    editForm = jQuery('.mufiles-edit-form').first();
    editedObjectType = editForm.attr('id').replace('EditForm', '');
    editedEntityId = entityId;

    if (jQuery('#moderationFieldsSection').length > 0) {
        jQuery('#moderationFieldsContent').addClass('hidden');
        jQuery('#moderationFieldsSection legend').addClass('pointer').click(function (event) {
            if (jQuery('#moderationFieldsContent').hasClass('hidden')) {
                jQuery('#moderationFieldsContent').removeClass('hidden');
                jQuery(this).find('i').removeClass('fa-expand').addClass('fa-compress');
            } else {
                jQuery('#moderationFieldsContent').addClass('hidden');
                jQuery(this).find('i').removeClass('fa-compress').addClass('fa-expand');
            }
        });
    }

    var allFormFields = editForm.find('input, select, textarea');
    allFormFields.change(function (event) {
        mUFilesExecuteCustomValidationConstraints(editedObjectType, editedEntityId);
    });

    formButtons = editForm.find('.form-buttons input');
    if (editForm.find('.btn-danger').length > 0) {
        editForm.find('.btn-danger').first().bind('click keypress', function (event) {
            if (!window.confirm(Translator.__('Do you really want to delete this entry?'))) {
                event.preventDefault();
            }
        });
    }
    editForm.find('button[type=submit]').bind('click keypress', function (event) {
        triggerValidation = !jQuery(this).attr('formnovalidate');
    });
    editForm.submit(mUFilesHandleFormSubmit);

    if (mode != 'create') {
        mUFilesTriggerFormValidation();
    }
}

/**
 * Initialises a relation field section with autocompletion and optional edit capabilities.
 */
function mUFilesInitRelationHandling(objectType, alias, idPrefix, includeEditing, inputType, createUrl) {
    if (inputType == 'autocomplete') {
        mUFilesInitAutoCompletion(objectType, alias, idPrefix, includeEditing);
    }
    if (includeEditing) {
        mUFilesInitInlineEditingButtons(objectType, alias, idPrefix, inputType, createUrl);
    }
}

