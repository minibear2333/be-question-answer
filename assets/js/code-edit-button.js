(function() {
    tinymce.create('tinymce.plugins.dwqaCodeEmbed', {
        init : function(ed, url) {
            // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
            ed.addCommand('dwqaCodeEmbedCommand', function() {
                var selected_text = tinyMCE.activeEditor.selection.getContent();
                if( selected_text ) {
                    tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '<pre>'+selected_text+'</pre>' );
                }else{
                    tinyMCE.activeEditor.execCommand( 'mceInsertContent', false, '<pre>插入代码</pre>' );
                }
            });

            // Register example button
            ed.addButton('dwqaCodeEmbed', {
                    title : '插入代码（shift+enter）',
                    cmd : 'dwqaCodeEmbedCommand',
                    image : false,
                    icon: 'code'
            });

            // Add a node change handler, selects the button in the UI when a image is selected
            ed.onNodeChange.add(function(ed, cm, n) {
                    cm.setActive('dwqaCodeEmbed', n.nodeName == 'IMG');
            });
        },

        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                    longname : 'Import code area',
                    author : 'DesignWall',
                    authorurl : 'http://designwall.com',
                    infourl : 'http://designwall.com',
                    version : "1.0"
            };
        }
    });
    // Register plugin
    tinymce.PluginManager.add('dwqaCodeEmbed', tinymce.plugins.dwqaCodeEmbed);
})();