Contao Open Source CMS changelog
================================

Version 3.3.beta1 (2014-XX-XX)
------------------------------

### New
Display the used template path in the source code when in debug mode.

### New
Added more static convenience methods to the `Config` class:

 - `set()`: temporarily set a configuration value
 - `presist()`: permanently store a configuration value
 - `remove()`: permanently remove a configuration value

A static `get()` method has been available already.

### Update
Update TinyMCE to version 4.0.20 (see #1495).

### New
Added additional style sheet types:

 - Internal editor: use the internal editor
 - SCSS: compile the style sheet from SCSS code
 - LESS: compile the style sheet from LESS code
 - External file: include an external file

### New
Allow to override the default module or content element template (see #4547).

### Improved
Create a new version if a member changes their data in the front end.

### Improved
Shorten the file paths in the `FileTree` widget (see #6488).

### Improved
Hide the details page link in the listing module if the details page condition
is not met (see #6332).

### New
Make the file system synchronization available on the command line (see #6815).

### New
Make the `Automator` methods available on the command line (see #6815).

### Changed
Moved the asset version constants to `$GLOBALS['TL_ASSETS']` (see #5759).

### New
Added a "preview front end as member" button (see #6546).

### Changed
Hide forward pages if they point to unpublished target pages (see #6376).

### Changed
Only enable the debug mode in the FE if there is a BE user (see #6450).

### Changed
Do not require MooTools or jQuery for the command scheduler (see #6755).

### Changed
Use the new Google Universal Analytics code snippet (see #6103).

### Improved
Add `$parent` as fourth parameter to the "compileDefinition" hook (see #6697).

### Update
Update TCPDF to version 6.0.062.

### Changed
Enable the maintanance mode by default (see #6758).

### New
Added a markdown content element (see #6052).

### Changed
Merged the "newsarchive" and "newsarchive_empty" templates (see #6647).

### Changed
Make the following functions public static (see #6351):

 - `Controller::getArticle`
 - `Controller::getContentElement`
 - `Controller::getForm`
 - `Controller::getFrontendModule`

### New
Support editing the front end preview page via the "url" parameter (see #6471).

### Improved
Do not combine .js and .css files when running in debug mode (see #6450).

### New
Added a `DcaLoader` class to decouple the DCA loading process (see #5441). DCAs
can now be loaded anywhere using `Controller::loadDataContainer()`.

### Changed
Convert slashes to hyphens in the `standardize()` function (see #6396).

### Improved
Add a `getModel()` method to modules, elements and hybrids (see #6492).

### Improved
Support the "HAVING" command in the `Model\QueryBuilder` class (see #6446).

### Changed
Use class constants for `BackendUser::isAllowed()`.
