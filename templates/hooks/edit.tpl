<fieldset>
    <legend>{gt text="MUFiles"}</legend>
    <div class="">
    <div class="mufiles-hook z-formrow">
        <label for="mufiles-collection">{gt text='Select a collection!'}</label>
        <select id="mufiles-collection" name="mufilescollection[]" multiple=multiple>
            {$collections}
        </select>
    </div>
    <div class="mufiles-hook z-formrow">
        <label for="mufiles-file">{gt text='Select a file!'}</label>
        <select id="mufiles-file" name="mufilesfile[]" multiple=multiple>          
            {$files}
        </select>
    </div>
    </div>
</fieldset>
