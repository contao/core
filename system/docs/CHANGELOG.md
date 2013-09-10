Contao Open Source CMS Changelog
================================

Version 3.2.beta1 (2013-XX-XX)
------------------------------

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
