Contao Open Source CMS changelog
================================

Version 3.2.RC1 (2013-10-24)
----------------------------

### Fixed
Add the "onclick" event to the "select all" checkbox (see #6314).

### Improved
Only show the news/event source options if the user is allowed to access the
fields required to configure those options (see #5498).

### New
Added the "getAttributesFromDca" hook (see #6340).

### New
Add the "maintenance mode" and automatically enable it when an extension is
installed, upgraded or removed (see #4561).

### Fixed
Correctly handle "toggle visibility" and drag and drop requests via Ajax.

### Improved
Correctly display nested wrapper elements (see #5976).

### New
Added the "isVisibleElement" hook to determine whether an element is visible in
the front end (see #6311).

### Fixed
Handle tables without keys in `Database::listFields()` (see #6310).

### Fixed
Allow FAQ categories without a redirect page (see #6226).

### Fixed
Create a new version of a record if a sorting field changes (see #6285).

### Fixed
Show the teaser text of redirect events in the event list (see #6315).

### Improved
Support the "autocomplete", "autocorrect", "autocapitalize" and "spellcheck"
attributes in the Widget class, so they can be set in the DCA (see #6316).

### Fixed
Added some validation logic to the `Result::data_seek()` methods (see #6319).

### Improved
`Model::__callStatic()` now also supports "countBy" (see #5984).

```php
// new magic method
$count = PageModel::countByPid(3);

// will be mapped to
$count = PageModel::countBy('pid', 3);
```

### Updated
Updated mediaelement.js to version 2.13.1 (see #6318).

### Fixed
Correctly handle slashes and empty strings in the TinyMCE link tab.

### Fixed
Order the template list alphabetically (see #6276).

### Improved
Simplified the "iflng" and "ifnlng" insert tags (see #6291). You can now omit
every closing `{{iflng}}` tag but the last one, e.g.:

```
{{iflng::de}}Hallo Welt{{iflng::en}}Hello world{{iflng}}
```

### Updated
Updated Colorbox to version 1.4.31 (see #6309).

### Fixed
Create new UUIDs when duplicating files or folders (see #6301).

### Fixed
Correctly handle booleans, null and empty strings in the Validator (see #6287).

### Fixed
Correctly assign the user's home directory (see #6297).

### Changed
Move the "create IDE compat file" logic to a command line script (see #6270).


Version 3.2.beta2 (2013-10-10)
------------------------------

### New
Added a model registry (thanks to Tristan Lins) (see #6248).

### New
Added the "compileFormFields" hook (see #6253).

### Fixed
Append the article ID to the CSS ID if there is no alias (see #6267).

### Fixed
Use a PHP variable for the user agent in the back end (see #6277 and #3074).

### Updated
Updated TCPDF to version 3.0.38 (see #6268).

### Fixed
Correctly show the "toggle page status" icon (see #6282).

### Improved
Use a "show details" button in the file manager (see #6262).

### Changed
Use the micro clearfix hack in the CSS framework (see #6203).

### Fixed
Convert binary UUIDs to hex when using it in SQL statements (see #6265).

### Fixed
Convert binary data to UUIDs in `DC_Table::show()` (see #6257).

### New
Allow to define custom layout sections in the page layout (see #2885).

### New
Added the custom layout sections positions "top" and "bottom" (see #2885).

### Fixed
Use serialized arrays to store order field data (see #6255).

### Fixed
Do not strip leading numbers from file names (see #6189).

### Improved
Hide the script hint if a user cannot access to the layout module (see #6190).

### Fixed
Correctly generate image links (see #6249).

### New
Added the convenience method `PageModel::getFrontendUrl()` (see #6184).

### Removed
Removed the TinyMCE spell checker (see #6247).

### Improved
Do not show dates in the past if a recurring event has not expired (see #923).

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
