Contao Open Source CMS changelog
================================

Version 3.3.RC2 (2014-05-09)
----------------------------

### Update
Update TinyMCE to version 4.0.26.

### Fixed
Correctly set and explain the page title field (see #6953).

### Fixed
Correctly show the template sources (see #6875).

### Fixed
Support input tags without a "type" attribute in the CSS framwork (see #6902).

### Fixed
Import the `tinymce.css` style sheet in TinyMCE (see #6970).

### Fixed
Catch Swift exceptions when sending form data via e-mail (see #6941).

### Fixed
Try all locale variations when loading TinyMCE (see #6952).

### Fixed
Correctly overwrite the article template (see #6938).

### Fixed
Correctly wrap long labels in the tree view (see #6954).

### Fixed
Correctly add the WAI-ARIA attributes (see #6217).


Version 3.3.RC1 (2014-05-02)
----------------------------

### New
Allow to override the default form field template (see #4547).

### Changed
Only pass the current form data to the "processFormData" hook (see #6705).

### New
Add a DropZone-based file uploader (see #6064).

### New
Add permissions to import and export themes (see #5835).

### Improved
Make the fields of the meta wizard configurable in the DCA (see #4327).

### Improved
Also show the preview image when editing multiple files (see #6643).

### Improved
Show the file location below the "name" field in the file manager (see #6503).

### Improved
Add some basic WAI-ARIA attributes to the navigation menu (see #6217).

### Improved
Automatically convert file paths in TinyMCE into insert tags (see #5965).

### Changed
Move the custom layout section markup into template files (see #6531).

### Improved
Move the form field markup into the template files (see #6834).

### New
Add template inheritance and template insertion (see #6508 and #6934).

### New
Add a flexible back end theme.

### Update
Update colorbox to version 1.5.8.

### Update
Update mediaelement.js to version 2.14.2.

### Update
Update jQuery to version 1.11.0 and jQuery UI to version 1.10.4.

### Update
Update the color picker to version 1.4.

### Changed
Use the "bootstrap" theme for the date picker (see #6692).

### Update
Update the back end date picker to version 2.2.0.

### Update
Update ACE to version 1.1.3.

### Improved
Use the widget attributes instead of the DCA in the picker widgets (see #6881).

### Improved
Enable the interlace bit when creating image thumbnails (see #6529).

### Improved
Assign articles to layout sections with an article module only (see #6094).

### New
Add the "parseDate" hook (see #4260).

### New
Make the title tag configurable in the page layout (see #6783).

### New
Add helper methods to generate markup depending on the output type:

 - `Template::generateStyleTag()`
 - `Template::generateInlineStyle()`
 - `Template::generateScriptTag()`
 - `Template::generateInlineScript()`
 - `Template::generateFeedTag()`

### New
Add the "customizeSearch" hook (see #5223).

### New
Add a button to generate article aliases via "edit multiple" (see #6628).

### New
Add a pagination menu at the listing bottom (see #6377).

### Fixed
Only override element and module templates in the front end (see #6878).

### Changed
Use the `html5shiv-printshiv.js` script in the front end (see #6293).

### New
Added the "getLanguages" hook (see #6545).

### Changed
Render the table summary as `<caption>` in HTML5 (see #6295).

### Changed
Also convert paths without delimiter in `Combiner::fixPaths()` (see #6417).

### New
Add the "colorizeLogEntries" hook (see #5803).

### New
Added an "oncut_callback" and "oncopy_callback" to `DC_Folder` (see #6814).

### Improved
Support optional dependencies in the module loader (see #6835).

### New
Mark the beginning and end of each template in debug mode (see #6841).

### New
Added the insert tag flags "urlencode" and "rawurlencode" (see #6859).

### Improved
Add files and folders to the database in details view (see #6880).


Version 3.3.beta1 (2014-04-11)
------------------------------

### New
Add version control for editable files.

### New
Add a configurable "viewport" field to the page layout (see #6251).

### New
Split the layout builder CSS code into a static and a responsive style sheet,
so the responsive behaviour can be disabled (see #6251).

### New
Added more static convenience methods to the `Config` class:

 - `set()`: temporarily set a configuration value
 - `presist()`: permanently store a configuration value
 - `remove()`: permanently remove a configuration value

A static `get()` method has been available already.

### Update
Update TinyMCE to version 4.0.20 (see #1495).

### New
Handle `.scss` and `.less` files in the `Combiner`. This also allows to add SCSS
or LESS files as external style sheets to the page layout.

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
