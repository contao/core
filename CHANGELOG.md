Contao Open Source CMS Changelog
================================

Version 3.0.beta1 (XXXX-XX-XX)
------------------------------

### Changed
Modules are now disabled by adding a `.skip` file instead of using the global
`inactiveModules` array. This way the local configuration file does not have to
be included twice in the `Config` class.

### Changed
Moved the Contao framework into the 'library' folder.

### Changed
Renamed the default configuration file from `config.php` to `default.php`, so
hopefully less people will try to edit the file directly.

### Changed
Moved the generated scripts to `assets/css` and `assets/js` and the thumbnails
to `assets/images`. Also moved the feed files into the `share` folder.

### Changed
Renamed the `tl_files` directory to `files`.
