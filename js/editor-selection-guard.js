/**
 * The block editor moves the meta box forms into its own layout, and moving an
 * iframe reloads it. When that happens after TinyMCE wrote its document (Firefox,
 * or a slow load elsewhere), TinyMCE keeps the old one: the editor shows blank,
 * getSelection() is null, and core's caret restore after a Visual/Code switch
 * (focusHTMLBookmarkInVisualEditor() in wp-admin/js/editor.js) throws from
 * select()/setRng(), taking marker cleanup and editor.save() with it.
 * See https://core.trac.wordpress.org/ticket/62450.
 *
 * Rebuild the editor on its textarea when its iframe reloads, and skip
 * select()/setRng() while there is no selection.
 */
tinymce.PluginManager.add('owcselectionguard', function (editor) {
	// PreInit, not init: the wordpress plugin's init handler fires tinymce-editor-init, which can
	// already restore the caret, and external plugins register their handlers after it. An iframe
	// that reloads before init also leaves TinyMCE waiting on stylesheets in the dead document, so
	// init never fires.
	editor.on('PreInit', function () {
		var selection = editor.selection;
		var select = selection.select.bind(selection);
		var setRng = selection.setRng.bind(selection);
		var iframe = editor.iframeElement;

		selection.select = function (node, content) {
			return selection.getSel() === null ? node : select(node, content);
		};

		selection.setRng = function (rng, forward) {
			if (selection.getSel() !== null) {
				setRng(rng, forward);
			}
		};

		if (!iframe) {
			return;
		}

		iframe.addEventListener('load', function () {
			if (editor.removed || iframe.contentDocument === editor.getDoc()) {
				return;
			}

			var id = editor.id;
			var settings = editor.settings;
			var wrap = document.getElementById('wp-' + id + '-wrap');
			var mode = wrap && wrap.classList.contains('html-active') ? 'html' : 'tmce';

			// remove() saves first; for a hidden editor the wordpress plugin keeps the textarea value.
			editor.remove();
			tinymce.init(settings);

			var rebuilt = tinymce.get(id);

			if (rebuilt) {
				rebuilt.on('init', function () {
					// After init_instance_callback, where Meta Box switches to the mode the field rendered in.
					setTimeout(function () {
						switchEditors.go(id, mode);
					});
				});
			}
		});
	});
});
