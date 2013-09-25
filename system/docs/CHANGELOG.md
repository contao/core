Contao Open Source CMS Changelog
================================

Version 3.2.beta1 (2013-XX-XX)
------------------------------

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
