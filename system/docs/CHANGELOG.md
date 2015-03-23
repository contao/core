Contao Open Source CMS changelog
================================

Version 3.5.0-beta1 (2015-XX-XX)
--------------------------------

### New
Added the "postAuthenticate" hook (see #7493).

### New
Pass `$arrFields` as fourth argument in the "prepareFormData" hook (see #7693).

### Fixed
Return a boolean value in the `*User::authenticate()` method (see #7497).

### New
Make `count`, `page` and `keywords` available in the search module (see #7577).

### New
Added the "getPageStatusIcon" hook (see #7556).

### Fixed
Improve the cache handling for empty URLs (see #7618).

### Improved
Improved the IDE compatibility (see #7634).
