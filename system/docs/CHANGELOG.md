Contao Open Source CMS changelog
================================

Version 3.5.0-beta1 (2015-XX-XX)
--------------------------------

### New
Support copying all records in the list view (see #7499).

### Fixed
Do not strip opening arrow brackets when stripping tags (see #3998).

### Improved
Simplify the `moo_mediabox` templates (see #7521).

### Changed
Always return the model in the `File` and `Folder` classes (see #7567).

### Fixed
Consistently ignore hidden system files (see #7536).

### New
Make the calendar model available in the templates (see #7388).

### Changed
Render the 404 page if the request contains an invalid date format (see #7545).

### Changed
Always render the 404 page if a news/event/FAQ alias is invalid (see #7238).

### New
Prevent calling a page via ID if there is a page alias (see #7661).

### Improved
Use closures to lazy-load content elements in the news/event list (see #7614).

### Improved
Add a log entry if a back end user switches to another account (see #7441).

### Improved
Optionally use the `ProxyRequest` class in the automator (see #7681).

### Fixed
Add a unique index for member usernames, too (see #7701).

### New
Add a diff view for custom templates (see #7599).

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
