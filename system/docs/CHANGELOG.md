Contao Open Source CMS changelog
================================

Version 3.5.14 (2016-06-16)
---------------------------

### Fixed
Always use the relative path in DC_Folder (see #8370).


Version 3.5.13 (2016-06-15)
---------------------------

### Fixed
Use the correct empty value when resetting copied fields (see #8365).

### Fixed
Remove the "required" attribute if a subpalette is closed (see #8192).

### Fixed
Correctly generate the feed links in a multi-domain setup (see #8329).

### Fixed
Correctly calculate the maximum file size for DropZone (see #8098).

### Fixed
Do not adjust the start date of a multi-day event (see #8194).

### Fixed
Versionize and show password changes (see #8301).

### Fixed
Make File::$dirname an absolute path again (see #8325).

### Fixed
Store the full URLs in the search index (see contao/core-bundle#491).

### Fixed
Standardize the group names in the checkbox widget (see #8002).

### Fixed
Prevent models from being registered twice (see #8224).

### Fixed
Prevent horizontal scrolling in the ACE editor (see #8328).

### Fixed
Correctly render the breadcrumb links in the template editor (see #8341).

### Fixed
Remove the role attributes from the navigation templates (see #8343).

### Fixed
Do not add `role="tablist"` to the accordion container (see #8344).


Version 3.5.12 (2016-04-22)
---------------------------

### Fixed
Correctly handle files with uppercase file extensions (see #8317).


Version 3.5.11 (2016-04-21)
---------------------------

### Fixed
Correctly pass the channel ID to the newsletter list template (see #8311).

### Fixed
Do not encode the database password (see #8314).

### Fixed
Fixed adding new folders in the file manager (see #8315).


Version 3.5.10 (2016-04-20)
---------------------------

### Fixed
Always trigger the "isVisibleElement" hook (see #8312).

### Fixed
Do not change all sessions when switching users (see #8158).

### Fixed
Do not allow to close fieldsets with empty required fields (see #8300).

### Fixed
Make the path related properties of the File class binary-safe (see #8295).

### Fixed
Always allow to navigate to the current month in the calendar (see #8283).

### Fixed
Correctly validate and decode IDNA e-mail addresses (see #8306).

### Fixed
Do not add the debug bar resources if `hideDebugBar` is enabled (see #8307).

### Fixed
Skip forward pages entirely in the book navigation module (see #5074).

### Fixed
Do not add the X-Priority header in the Email class (see #8298).

### Fixed
Fix an error message in the newsletter subscription module (see #7887).

### Fixed
Determine the search index checksum in a more reliable way (see #7652).


Version 3.5.9 (2016-03-21)
--------------------------

### Fixed
Prevent the autofocus attribute from being added multiple times (see #8281).

### Fixed
Respect the SSL settings of the root page when generating sitemaps (see #8270).

### Fixed
Read from the temporary file if it has not been closed yet (see #8269).

### Fixed
Always use HTTPS if the target server supports SSL connections (see #8183).

### Fixed
Adjust the meta wizard field length to the column length (see #8277).

### Fixed
Correctly handle custom mime icon paths (see #8275).

### Fixed
Only log errors that have been configured to get logged (see #8267).

### Fixed
Show the 404 error page if an unpublished article is requested (see #8264).

### Fixed
Correctly count the URLs when rebuilding the search index (see #8262).

### Fixed
Ensure that every image has a width and height attribute (see #8162).

### Fixed
Set the correct mime type when embedding SVG images (see #8245).

### Fixed
Handle the "float_left" and "float_right" classes in the back end (see #8239).

### Fixed
Consider the fallback language if a page alias is ambiguous (see #8142).

### Fixed
Fix the error 403/404 redirect (see contao/website#74).


Version 3.5.8 (2016-03-01)
--------------------------

### Fixed
Re-add the `$blnFixDomain` argument to keep backwards compatibility.


Version 3.5.7 (2016-02-29)
--------------------------

### Fixed
Always fix the domain and language when generating URLs (see #8238).

### Fixed
Fix two issues with the flexible back end theme (see #8227).

### New
Added new versioning hooks (see #8168).

 * "oncreate_version_callback" (supersedes "onversion_callback")
 * "onrestore_version_callback" (supersedes "onrestore_callback")

### Fixed
Correctly toggle custom page type icons (see #8236).

### Fixed
Fix the domain in all article, news, event and FAQ insert tags (see #8204).

### Fixed
Update mediaelement.js to version 2.19.0.1 (see #8217).

### Fixed
Correctly render the links in the monthly/yearly event list menu (see #8140).

### Fixed
Skip the registration related fields if a user is duplicated (see #8185).

### Fixed
Correctly show the form field type help text (see #8200).

### Fixed
Correctly create the initial version of a record (see #8141).

### Fixed
Correctly show the "expand preview" buttons (see #8146).

### Fixed
Correctly check that a password does not match the username (see #8209).

### Fixed
Check if a directory exists before executing `mkdir()` (see #8150).

### Fixed
Do not link to the maintenance module if the user cannot access it (see #8151).

### Fixed
Show the "new folder" button in the template manager (see #8138).


Version 3.5.6 (2015-11-27)
--------------------------

### Fixed
Correctly determine the protocol delimiter in `Idna::encodeUrl()`.

### Fixed
Handle relative URLs when following redirects in the Request class (see #7799).

### Fixed
Correctly handle empty UUIDs when comparing versions (see #7971).

### Fixed
Remove the "required" attribute when setting up TinyMCE (see #8131).


Version 3.5.5 (2015-11-25)
--------------------------

### Fixed
Fix the domain when forwarding in the page controllers (see #8123).

### Fixed
Use the feed URL instead of the base URL for enclosures (see #8116).

### Fixed
Fix the `<time>` tags and standardize the event templates (see #8012).

### Fixed
Handle empty `href` attributes in the book navigation (see #8104).

### Fixed
Do not store e-mail addresses in the newsletter (un)subscription log.

### Fixed
Correctly encrypt fields upon registration (see #8110).

### Fixed
Correctly render required single checkboxes in the back end (see #7731).

### Fixed
Correctly store multi select menus if no value is selected (see #7760).

### Fixed
Prevent recursion when rendering 403/404 pages (see #8060).

### Fixed
Map the `FileTree` widget to `FormFileUpload` in the front end (see #8091).

### Fixed
Preserve the user input when loading image meta data (see #8108).

### Fixed
Show the "toggle all" buttons in "edit multiple" mode (see #5622).

### Fixed
Disable the gallery pagination if the images are sorted randomly (see #8033).

### Fixed
Set the correct empty value when copying elements (see #8064).

### Fixed
Correctly hide forward pages with no public subpages (see #8054).

### Fixed
Correctly render the page picker if the value starts with `#` (see #8055).

### Fixed
Correctly render the "group" option in the radio button and checkbox widgets.

### Fixed
Correctly set the ID when toggling fields via Ajax (see #8043).

### Fixed
Support call, sms and app hyperlinks when converting relative URLs (see #8102).

### Fixed
Correctly check if a folder is protected when loading subfolders.

### Fixed
Correctly check the synchronization status when copying or moving files.

### Fixed
Adjust the code to be compatible with PHP7 (see #8018).

### Fixed
Correctly show the UUID in the back end file manager popup (see #8058).


Version 3.5.4 (2015-10-09)
--------------------------

### Fixed
Do not add the back end language in the meta wizard (see #8056).

### Fixed
Do not add excluded files to the DBAFS if they are edited in the file manager.

### Fixed
Add the `|flatten` insert tag flag to handle arrays (see #8021).

### Fixed
Check for excluded folders in the back end file popup (see #8003).

### Fixed
Fixed a wrong option name when initializing sortables (see #8053).

### Fixed
Translate UUIDs to paths in the parent view header fields.

### Fixed
Trigger the options_callback for the parent view header fields (see #8031).

### Fixed
Correctly create the initial version of a member without username (see #8037).

### Fixed
Improve the performance of the debug bar (see #7839).

### Fixed
Correctly output the event details in the `event_list` template (see #8041).

### Fixed
Only modify empty `href` attributes in the `nav_` template (see #8006, #8038).

### Fixed
Correctly show the group headlines in the repository DB updater (see #8020).

### Fixed
Improve the e-mail regex to also match the new TLDs (see #7984).

### Fixed
Ensure that the database port is not empty (see #7950).

### Fixed
Remove the left-over usages of `$this->v2warning` (see #8027).

### Fixed
Support the `hasDetails` variable in the event reader (see #8011).


Version 3.5.3 (2015-09-10)
--------------------------

### Fixed
Correctly handle dimensionless SVG images (see #7882).

### Fixed
Correctly fill in the image meta data in news, events and FAQs (see #7907).

### Fixed
Enable the `strictMath` option of the LESS parser (see #7985).

### Fixed
Consider the pagination menu when inserting at the top (see #7895).

### Fixed
Use en-dashes in event intervals (see #7978).

### Fixed
Store the correct edit URL in the back end personal data module (see #7987).

### Fixed
Adjust the breadcrumb trail when creating new folders (see #7980).

### Fixed
Use `$this->hasText` in news and event templates (see #7993).

### Fixed
Convert the HTML content to XHTML when generating Atom feeds (see #7996).

### Fixed
Correctly link the items in the files breadcrumb menu (see #7965).

### Fixed
Handle explicit collations matching the default collation (see #7979).

### Fixed
Fix the duplicate content check in the front end controller (see #7661).

### Fixed
Correctly parse dates in MooTools (see #7983).

### Fixed
Register the related models in the registry (see contao/core-bundle#333).

### Fixed
Correctly escape in the `findMultipleFilesByFolder()` method (see #7966).

### Fixed
Override the tabindex handling of the accordion to ensure that the togglers are
always focusable via keyboard (see #7963).

### Fixed
Correctly generate the news and event menu URLs (see #7953).

### Fixed
Check the script when storing the front end referer (see #7908).

### Fixed
Fix the back end pagination menu (see #7956).

### Fixed
Handle option callbacks in the back end help (see #7951).

### Fixed
Fixed the external links in the text field help wizard (see #7954) and the
keyboard shortcuts link on the back end start page (see #7935).

### Fixed
Fixed the CSS group field explanations (see #7949).

### Fixed
Use ./ instead of an empty href (see #7967).

### Fixed
Correctly detect Microsoft Edge (see #7970).

### Fixed
Respect the "order" parameter in the `findMultipleByIds()` method (see #7940).

### Fixed
Always trigger the "parseDate" hook (see #4260).

### Fixed
Allow to instantiate the `InsertTags` class (see #7946).

### Fixed
Do not parse the image `src` attribute to determine the state of an element,
because the image path might have been replaced with a `data:` string (e.g. by
the Apache module "mod_pagespeed").


Version 3.5.2 (2015-07-24)
--------------------------

### Fixed
Revert some of the PhpStorm code inspector changes (see #7937).


Version 3.5.1 (2015-07-24)
--------------------------

### Fixed
Add a `StringUtil` class to restore PHP 7 compatibility (see contao/core-bundle#309).

### Fixed
Fix the `Validator::isEmail()` method (see contao/core-bundle#313).

### Fixed
Strip tags before auto-generating aliases (see #7857).

### Fixed
Correctly encode the URLs in the popup file manager (see #7929).

### Fixed
Check for the comments module when compiling the news meta fields (see #7901).

### Fixed
Also sort the newsletter channels alphabetically in the front end (see #7864).

### Fixed
Disable responsive images in the back end preview (see #7875).

### Fixed
Overwrite the request string when generating news/event feeds (see #7756).

### Fixed
Store the static URLs with the cached file (see #7914).

### Fixed
Correctly check the subfolders in the `hasAccess()` method (see #7920).

### Fixed
Updated the countries list (see #7918).

### Fixed
Respect the `notSortable` flag in the parent (see #7902).

### Fixed
Round the maximum upload size to an integer value (see #7880).

### Fixed
Make the markup minification less aggressive (see #7734).

### Fixed
Filter the indices in `Database::getFieldNames()` (see #7869).

### Fixed
Back-ported two fixes from the upstream versions.


Version 3.5.0 (2015-06-05)
--------------------------

### Updated
Updated TinyMCE to version 4.1.10.

### Updated
Updated respimage to version 1.4.0.

### Updated
Updated jQuery to version 1.11.3.

### Updated
Updated Colorbox to version 1.6.1.

### Fixed
Consistently sanitize the names of uploaded files (see #7852).

### Fixed
Fixed loading cached pages with both a mobile and desktop layout (see #7859).

### Fixed
Omit the `index.php` fragment if the request string is empty (see #7757).

### Fixed
Adjust the edit URLs in the versions menu in "edit multiple" mode (see #7745).

### Fixed
Do not cache the login module if there is an error (see #7824).

### Fixed
Correctly handle encrypted rows (see #7815).

### Fixed
Only create a new version in the personal data module if something actually
changed (see #7415).

### Fixed
Also fire the "modifyFrontendPage" hook when loading from cache (see #7457).

### Fixed
Fixed several minor issues with the registration module (see #7816).

### Fixed
Update the revision date if a member updates their personal data (see #7818).

### Fixed
Do not allow to restore versions in the back end user settings (see #7713).

### Fixed
Use the timestamp of an element to initialize its first version (see #7730).

### Fixed
Hide the "edit header" button if there are no editable fields (see #7770).

### Fixed
Make the "form_submit" templates overwritable again (see #7854).

### Fixed
Correctly inherit empty page permissions (see #6782).

### Fixed
Decode the GET parameters before setting them in the `Input` class (see #7829).

### Fixed
Fixed the "specified value 't' is not a valid email address" error (see #7784).

### Fixed
Correctly set `data-` or `ng-` attributes in the widgets (see #7772).

### Fixed
Correctly display the headline in the template editor (see #7746).

### Fixed
Make `Validator::isValidUrl()` RFC 3986 compliant (see #7790).

### Fixed
Fixed switching between the page and file picker in the URL wizard (see #5863).

### Fixed
Make the "the old password is incorrect" message translatable (see #7793).

### Fixed
Fix copying multiple items in parent view (see #7776).

### Fixed
Disable the "compare template" icon for folders (see #7802).

### Fixed
Fix the field order in the template diff view (see #7808).

### Fixed
Validate the coordinates in the `Image::setImportantPart()` method (see #7804).

### Fixed
Only add order fields of binary fields in the DCA extractor (see #7785).


Version 3.5.0-RC1 (2015-04-30)
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
Updated the Ace code editor to version 1.1.8.

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
