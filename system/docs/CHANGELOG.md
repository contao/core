Contao Open Source CMS Changelog
================================

Version 3.2.beta1 (2013-XX-XX)
------------------------------

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
