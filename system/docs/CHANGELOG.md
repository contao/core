Contao Open Source CMS changelog
================================

Version 3.2.RC1 (2013-10-XX)
----------------------------

### New
Added the convenience method `PageModel::getFrontendUrl()` (see #6184).

### Removed
Removed the TinyMCE spell checker (see #6247).

### Improved
Do not show dates in the past if a recurring event has not expired (see #923).

### Fixed
Correctly handle "toggle visibility" requests via Ajax.

### New
Pass the ID of the `tl_undo` record to the "ondelete_callback" (see #6234).

### New
Added the "br" insert tag to insert line breaks (see #6143).

### Fixed
Do not alter the order of the UUID chunks (no optimized order).


Version 3.2.beta1 (2013-09-27)
------------------------------

### Fixed
Make usernames case-sensitive.

### New
Added a `system/docs/UPGRADE.md` file to document API changes (see #6236).

### Changed
Send an "X-Ajax-Location" header to redirect upon Ajax requests (see #5647).

### New
Added new DCA table config flags (see #5254):

 * `closed`: no new rows can be added at all
 * `notEditable`: the rows cannot be edited
 * `notDeletable`: the rows cannot be deleted
 * `notSortable`: the order of the rows cannot be altered (new)
 * `notCopyable`: existing rows cannot be duplicated (new)
 * `notCreatable`: prevents to create rows but allows to duplicate rows (new)

The `closed` flag hence is a combination of `notCreatable` and `notCopyable`.

### Improved
Always show the save buttons in the modal windows (see #5985).

### New
Add the CSS classes "first" and "last" to articles/content elements (see #2583).

### New
The form generator now supports defining a minimum input length (see #4394).

### New
If you are running Contao via an SSL proxy server, you can now set the proxy
server domain in the back end settings (see #4615).

### Changed
Allow to alter any button set via the "buttons_callback" (see #4691). This
includes any edit, edit multiple, select or upload form and also includes the
option to unset or replace the default buttons.

[BC-BREAK] If you have been using the "buttons_callback" in version 3.0 or 3.1,
you will have to adjust your code to reflect the changes!

### Improved
Show the release notes when installing or upgrading an extension (see #5058).

### New
Add an `arc_[archive-id]` CSS class to all news list items (see #4998).

### New
You can now define a list of trusted proxy server IPs in the back end settings
to improve identifying the user's remote address (see #5830).

### Changed
Use `COLLATE utf8_bin` instead of `varbinary` to preserve case-sensitivity.

### New
Back end users can now store their Google+ profile ID, which will then be used
to add a `rel="author"` link in FAQs and news items (see #4914).

### Changed
Render the file tree view based on the eval flags "isGallery" and "isDownloads"
instead of making it depend on the "type" column (see #5884).

### Improved
Add tooltips to the preview height togglers (see #6213).

### Improved
Use translatable error screens wherever the application dies.

### Fixed
Show the 404 page of the language fallback website if the requested language
does not exist (see #5709).

### New
Added the "nullIfEmpty" flag to the "eval" section of the DCA (see #6186).

### Improved
Only cache the languages which are in use (see #6013).

### New
The "file" insert tag now also handles UUIDs (see #5512).

```
<img src="{{file::bb643d42-0026-ba97-11e3-ccd6e14e1c8a}}" alt="">
```

The insert tag can also be used in the internal style sheet editor.

### Improved
Purge the search index if a page is deleted (see #5897).

### Improved
Pass additional parameters to the "insertTagFlags" hooks (see #5806).

### Improved
Added a generic `Model::findMultipleByIds()` method (see #5805).

### Updated
Updated slimbox to version 1.8 (see #5747).

### Improved
Show error messages if a user is logged into the install tool (see #5001).

### New
Support using closures as DCA callbacks (see #5772).

```
$GLOBALS['TL_DCA']['tl_content'] = array
(
  'config' => array
  (
    'onload_callback' => array
    (
      function($dc) {
        // Your custom code
      },
      array('tl_content', 'showJsLibraryHint')
    )
  )
);
```

### New
Templates now support adding callables (see #6176).

```
$this->Template->sum = function($a, $b) {
  return $a + $b;
}

<?php echo $this->sum(3, 4); ?>
```

### Fixed
Remove the left-over uses of `inactiveModules` (see #6142).

### Fixed
Consider all extensions when scanning for `fileTree` fields (see #6058).

### Changed
Use unique IDs in the database assisted file system (see #5757).

### New
Optionally follow redirects in the `Request` class.

```
$request = new Request();
$request->redirect = true;
$request->send("http://domain.tld/script.php");
```

### New
Add basic authorization support to the `Request` class (see #6062).

### Improved
Wrap the SQL statements in the install tool in a scrollable div (see #6100).
