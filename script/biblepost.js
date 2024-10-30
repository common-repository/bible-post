// JavaScript Document
(function() {
	tinymce.PluginManager.requireLangPack('biblepost');
	tinymce.create('tinymce.plugins.biblepostPlugin', {
		init : function(ed, url) {
			ed.addCommand('mcebiblepost', function() {
				ed.windowManager.open({
					file : url + '/biblepost_tinymce.php',
					width : 400 + ed.getLang('biblepost.delta_width', 0),
					height : 390 + ed.getLang('biblepost.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url, // Plugin absolute URL
					some_custom_arg : 'custom arg' // Custom argument
				});
			});
			ed.addButton('biblepost', {
				title : 'biblepost.descripcion',
				image : url+'/icon_bible.png',
				cmd : 'mcebiblepost'
				/*
				onclick : function() {
					 ed.selection.setContent('[tinyplugin]' + ed.selection.getContent() + '[/tinyplugin]');
 
				}
				*/
			});
		},
		createControl : function(n, cm) {
			return null;
		}
	});
	tinymce.PluginManager.add('biblepost', tinymce.plugins.biblepostPlugin);
})();