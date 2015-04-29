Contao Open Source CMS changelog
================================

Version 3.5.0-RC1 (2015-04-XX)
------------------------------

### New
Select multiple checkboxes by holding down the SHIFT key (see #7781).

### Changed
Show versions even if there is only one (see #7730).

### Fixed
Loosely check the `suhosin.memory_limit` setting (see #7696).

### Improved
Support specifying the database key length (see #7771).

### Improved
Check for ASCII strings in the `utf8_romanize()` function (see #7748).

### Fixed
Use `DIRECTORY_SEPARATOR` in conjunction with `str_replace()` (see #7769).

### Changed
`Controller::replaceInsertTags()` is now public static.

### Fixed
Restore the removed attributes of the "picture_default" templates (see #7752). 

### Changed
Moved the insert tag logic into a separate class.

### Improved
Show the upload limits in the file manager (see #7389).

### Improved
Also export the image meta data when exporting themes (see #7480). 

### Improved
Improve the model registry (see #7725).

### Changed
The templates now use short open tags.


Version 3.5.0-beta1 (2015-03-30)
--------------------------------

### New
Add a front end module to change the password (see #7418).

### Changed
Allow to copy and move newsletter recipients across channels (see #7570).

### New
Added the "newsListCountItems" and "newsListFetchItems" hooks (see #7694).

### New
Added the "compileArticle" hook (see #7686).

### New
Added the "picture" insert tag (see #7635 and #7718).

### Changed
Stop ignoring notices by defaut now that the error level is configurable.

### Updated
Updated respimage to version 1.3.0.

### Updated
Updated jQuery UI to version 1.11.4.

### Updated
Updated mediaelement.js to version 2.16.4.

### Updated
Updated Colorbox to version 1.6.0.

### Updated
Updated jQuery to version 1.11.2.

### Updated
Updated HTML5Shiv to version 3.7.2.

### Updated
Updated DropZone to version 3.12.0.

### Updated
Updated the ACE editor to version 1.1.8.

### Improved
Also convert image links in TinyMCE to `{{file}}` insert tags (see #7581).

### New
Support copying multiple records in the list view (see #7499).

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
Optimized the database queries (see #7450 and #7710).

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
