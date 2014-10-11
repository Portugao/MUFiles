CKEDITOR.plugins.add('MUFiles', {
    requires: 'popup',
    lang: 'en,nl,de',
    init: function (editor) {
        editor.addCommand('insertMUFiles', {
            exec: function (editor) {
                var url = Zikula.Config.baseURL + Zikula.Config.entrypoint + '?module=MUFiles&type=external&func=finder&editor=ckeditor';
                // call method in MUFiles_finder.js and provide current editor
                MUFilesFinderCKEditor(editor, url);
            }
        });
        editor.ui.addButton('mufiles', {
            label: editor.lang.MUFiles.title,
            command: 'insertMUFiles',
         // icon: this.path + 'images/ed_mufiles.png'
            icon: '/modules/MUFiles/images/mufiles.png'
        });
    }
});