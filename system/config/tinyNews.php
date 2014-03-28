<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * This is the tinyMCE (rich text editor) configuration file. Please visit
 * http://tinymce.moxiecode.com for more information.
 */
if ($GLOBALS['TL_CONFIG']['useRTE']):

?>
<script>window.tinymce || document.write('<script src="<?php echo TL_ASSETS_URL; ?>assets/tinymce/tinymce.gzip.js">\x3C/script>')</script>
<script>
tinymce.init({
  skin: "contao",
  selector: "#ctrl_<?php echo $this->strInputName; ?>",
  language: "<?php echo Backend::getTinyMceLanguage(); ?>",
  element_format: "html",
  document_base_url: "<?php echo Environment::get('base'); ?>",
  entities: "160,nbsp,60,lt,62,gt,173,shy",
  init_instance_callback: function(editor) {
    TinyCallback.getScrollOffset(editor);
  },
  file_browser_callback: function(field_name, url, type, win) {
    TinyCallback.fileBrowser(field_name, url, type, win);
  },
  doctype: "<!DOCTYPE html PUBLIC \"-//W3C//DTD HTML 3.2//EN\">",
  templates: "<?php echo TL_PATH; ?>/assets/tinymce/plugins/typolinks/typotemplates.php",
  plugins: "autosave,charmap,code,fullscreen,image,importcss,link,paste,searchreplace,tabfocus,table,template,visualblocks",
  browser_spellcheck: true,
  tabfocus_elements: ":prev,:next",
  extended_valid_elements: "b/strong,i/em",
  content_css: "<?php echo TL_PATH; ?>/system/themes/tinymce.css,<?php echo TL_PATH . "/" . Config::get('uploadPath'); ?>/tinymce.css",
  extended_valid_elements: "q[cite|class|title],article,section,hgroup,figure,figcaption",
  menubar: "file edit insert view format table",
  toolbar: "link image | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | undo redo | code"
});
</script>
<?php endif; ?>
