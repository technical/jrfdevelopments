(function() {
	// Load plugin specific language pack
	tinymce.PluginManager.requireLangPack('wp_mmg');
	
	tinymce.create('tinymce.plugins.wp_mmg', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			// Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');

			ed.addCommand('mcewp_mmg', function() {
				ed.windowManager.open({
					file : url + '/popup.php',
					width : 600 + ed.getLang('wp_mmg.delta_width', 0),
					height : 400 + ed.getLang('wp_mmg.delta_height', 0),
					inline : 1
				}, {
					plugin_url : url // Plugin absolute URL
				});
			});

			// Register example button
			ed.addButton('wp_mmg', {
				title : 'wp_mobileme_gallery',
				cmd : 'mcewp_mmg',
				image : url + '/wp_mmg.png'
			});

		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
					longname  : 'wp_mobileme_gallery',
					author 	  : 'Sébastien Gillard',
					authorurl : 'http://sebastiengillard.fr',
					infourl   : 'http://sebastiengillard.fr',
					version   : "0.1"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('wp_mmg', tinymce.plugins.wp_mmg);
})();