Contao Open Source CMS changelog
================================

Version 3.2.13 (2014-XX-XX)
---------------------------

### Fixed
Correctly check the permission to edit multiple files (see #7157).

### Fixed
Correctly handle other MySQL character sets (see #7140).

### Fixed
Correctly recognize Opera Mobile in the `Environment` class (see #5869).

### Fixed
Fix the grid offset for articles (see #7166).

### Fixed
Restore the basic entities in the source editor (see #7170).

### Fixed
Correctly build the breadcrumb trail in the style sheets module (see #7132).

### Fixed
Do not associate the "use SSL" option with sitemaps only (see #7163).

### Fixed
URL encode the pipe character in the Google web font URL (see #7120).

### Fixed
Handle double quotes in the title attribute of the `<link>` element (see #7124).

### Fixed
Use the `save_callback` when generating multiple aliases (see #7114).

### Update
Update SwiftMailer to version 5.2.1 (see #7110).

### Fixed
Correctly handle double quotes in comments (see #7102).

### Fixed
Ignore hidden files when building the internal cache (see #7098).

### Fixed
Correctly pass the insert ID of the undo record (see #6234).


Version 3.2.12 (2014-06-18)
---------------------------

### Fixed
Replace insert tags in external redirect targets (see #6765).

### Fixed
Also apply the font settings to the ACE element (see #7103).

### Fixed
Show the placeholder image in the "edit file" dialog if the original image
exceeds the maximum dimensions supported by the GD library (see #7032).

### Fixed
Preserve whitespace before `<textarea>` tags when minifying code (see #7087).

### Fixed
Restore the PHP 5.3 compatibility of the listing module (see #7078).

### Fixed
Do not offer to drop tables or fields if the safe mode is active (see #7085).

### Fixed
Correctly detect binary fields during theme export (see #7079).


Version 3.2.11 (2014-06-04)
---------------------------

### Fixed
Make `$this->locationLabel` available in the event list (see #7030).

### Fixed
Correctly set the root page title (see #7023).

### Fixed
Only show the sort hint if there is more than one element (see #6935).

### Fixed
Try to raise the PHP limits upon file synchronization (see #7035).


Version 3.2.10 (2014-05-21)
---------------------------

### Fixed
Correctly urlencode folder names in the file manager (see #6925).

### Update
Update MooTools to version 1.5.0 (see #6924).

### Fixed
Allow for up to 13 characters in `Validator::isEmail()` (see #6950).

### Fixed
Only fall back to the default option if there is no POST data (see #6899).

### Fixed
Do not override the event start time in `Events::addEvent()` (see #6701).

### Fixed
Correctly detect binary fields during theme import (see #6852).

### Fixed
Do not urldecode twice in `DC_Folder` (see #6840).

### Fixed
Standardize the fallback behavior of the downloads/gallery element (see #6662).

### Fixed
Correctly hide duplicated elements in the module wizard (see #6826).

### Fixed
Fix the mediabox "imgBackground" option (see #6866).

### Fixed
Strip double quotes in the options wizard (see #6919).

### Fixed
Strip the insert tag flags before passing the tag name to the hooks (see #6860).

### Fixed
Catch Swift exceptions when sending form data via e-mail (see #6941).

### Update
Update the back end date picker to version 2.2.0.

### Update
Update ACE to version 1.1.3.

### Fixed
Check for reserved article aliases before validating the alias name (see #6978).

### Fixed
Store the UUID of uploaded files in the session (see #6986).

### Fixed
Only assume a moved file or folder for new resources (see #6907).

### Fixed
Correctly strip the file extension in the `File` class (see #6968).

### Fixed
Remove the menu when `Swipe.kill()` is executed (see #6861).

### Fixed
Consider the protocol when embedding YouTube videos (see #6900).


Version 3.2.9 (2014-04-07)
--------------------------

### Fixed
Fixed a critical vulnerability of the install tool (see #6855).

### Fixed
Filter disabled groups in the registration module in the front end (see #6757).

### Fixed
Work around a bug in SimplePie with the "skip items" option (see #6107).

### Fixed
Fix the Swipe "continuous" option if there are exactly two slides (see #6812).

### Fixed
Apply `addslashes()` to strings in the `Config` class (see #6808).

### Fixed
Do not empty all fallback fields in sorting mode 4 (see #6498).

### Fixed
Do not allow template names to be longer than the DB fields (see #6819).

### Fixed
Correctly set the start time of a multi-day event (see #6802).

### Fixed
Correctly handle OR queries in the listing module (see #6344).

### Fixed
Use a monospaced font for the plain text newsletter preview (see #6790).

### Fixed
Adjust the `vScrollTo()` offset if the paste hint is visible (see #6478).


Version 3.2.8 (2014-03-12)
--------------------------

### Fixed
Add the "href" values for active breadcrumb menus to the template (see #6796).

### Fixed
The file/page tree widget did not work properly in "edit multiple" mode (#6788).

### Fixed
Preserve the referer ID when clicking the "switch to edit" button (see #6127).

### Fixed
Encode e-mail addresses in the "explanation" form field (see #6771).

### Fixed
Use a placeholder image if no thumbnail can be created (see #6754).

### Fixed
Pass additional arguments to the "replaceInsertTags" hook (see #6672).

### Fixed
Correctly initialize the `Session` class (see #6747).

### Fixed
Do not use `Input::setGet()` in the event modules (see #6733).

### Fixed
Correctly shorten the CSS `background` property (see #6709).

### Fixed
Do not use `UNION SELECT` when searching for parent pages (see #6704).

### Fixed
Disable `zlib.output_compression` when sending files to the browser (see #6717).

### Fixed
Consider the event time in the event list module (see #6719).

### Fixed
Make the newsletter recipient address available in the template (see #5782).

### Fixed
Correctly handle Unicode characters in `Validator::isGooglePlusId` (see #6707).

### Fixed
Fixed the arguments of two `CalendarEventsModel` methods (see #6781).

### Fixed
Pass the "tableless" flag to the "form_message" template (see #6772).

### Fixed
Update the `swipe.js` script so the "continuous" option works (see #6762).

### Fixed
Improve the `Search::removeEntry()` method (see #6785).

### Fixed
Correctly set the cookie path in the front mode in debug mode (see #6723).

### Fixed
Point to `Frontend::addToUrl()` in front end templates (see #6736).

### Fixed
Do not stop the cron job execution after the first interval.


Version 3.2.7 (2014-02-13)
--------------------------

### Fixed
Fix another weakness in the `Input` class and further harden the `deserialize()`
function. Thanks to Martin Auswöger for his input.


Version 3.2.6 (2014-02-12)
--------------------------

### Fixed
Further harden the `deserialize()` function and the `Input` class (see #6724).


Version 3.2.5 (2014-02-03)
--------------------------

### Fixed
Correctly load the parent pages in the navigation modules (see #6696).

### Fixed
Correctly encode URLs with GET parameters in the syndication links (see #6683).

### Fixed
Do not pass POST data to the `deserialize()` function, so it is not vulnerable
to PHP object injection. Thanks to Pedro Ribeiro for his input (see #6695).

### Fixed
Allow any character in passwords, especially the less-than symbol (see #6447).

### Fixed
Purge the image cache if a file is being renamed (see #6641).

### Fixed
Preserve tags in custom CSS definitions (see #6667).

### Fixed
Make the swipe CSS selectors more specific (see #6666).

### Fixed
Correctly optimize floating-point numbers in style sheets (see #6674).


Version 3.2.4 (2014-01-20)
--------------------------

### Fixed
Updated the Russian translation of the TinyMCE "typolinks" plugins (see #6224).

### Fixed
Do not create multiple stylect layers upon Ajax changes.

### Fixed
Some DCAs were missing the "rem" unit (see #6634).

### Fixed
Correctly trim the SQL statements in the `Database` class (see #6623).

### Fixed
Fix some broken back end icons (see #6214).

### Fixed
Show a hint in the news archive menu if there are no items (see #5888).

### Fixed
Prevent the back end tool tips from exceeding the screen width (see #6639).

### Fixed
Support the Google+ vanity name in addition to the numeric ID (see #6454).

### Fixed
Correctly detect Android tablets in the `Environment` class (see #5869).

### Fixed
Correctly resolve the module dependencies (see #6606).

### Fixed
Correctly unset the PHP session cookie depending on its parameters.

### Fixed
Fixed the XHTML variant of the comments form (see #5675).

### Fixed
Correctly assign articles to columns (see #6595).

### Fixed
Correctly merge the CSS classes in the `Hybrid` class (see #6601).


Version 3.2.3 (2013-12-20)
--------------------------

### Fixed
Correctly resize the mediaboxAdvanced in IE11 (see #6504).

### Fixed
Set the correct status header for cached files (see #6585).

### Fixed
Correctly set the empty value depending on the DB field (fixes #6550, #6544).

### Fixed
Prevent saving of detached models (see #6506).

### Fixed
Correctly determine the ACE editor's height (see #6578).

### Fixed
Always fall back to English if a language does not exist (see #6581).

### Fixed
Correctly display repeated events in the event list (see #6555).

### Fixed
Correctly show the available layout columns in the article module (see #6548).

### Fixed
Correctly show the "read more" link in the news list modules (see #6439).

### Updated
Updated html5shiv to version 3.7.0 (see #6543).

### Fixed
Support browsers with both mouse and touch support in the back end (see #6520).

### Fixed
Correctly handle multiple `RadioTable` widgets on the same page (see #6389).

### Fixed
Fixed two issues with the SQL cache (see #6507).

### Fixed
Do not require a redirect page for newsletter channels (see #6521).

### Fixed
Use the related field instead of `id` in the model query builder (see #6540).


Version 3.2.2 (2013-12-09)
--------------------------

### Fixed
Correctly support insert tags nested in shortened "iflng" tags (see #6509).

### Fixed
Do not require a foreign key to define a relation in the DCA (see #6524).

### Fixed
Use UUIDs as parent IDs in `Dbafs::addResource()` (see #6532).

### Fixed
Correctly set the default language (see #6533).

### Fixed
Correctly update the order fields in the database updater (see #6534).

### Fixed
Do not override the "href" property in `addImageToTemplate()` (see #6468).

### Fixed
Correctly handle URLs if page aliases are disabled (see #6502).

### Fixed
Handle UUIDs in `Model::getRelated()` (see #6525).

### Fixed
Hide records with only one version from the "changed elements" overview.

### Fixed
Use an auto-resizing textarea to store CSS selectors.

### Updated
Updated the ACE editor to version 1.1.2.

### Fixed
Prevent the ACE editor from overlapping the modal window (see #6497).

### Fixed
Use the default back end theme when running in safe mode (see #6505).


Version 3.2.1 (2013-11-29)
--------------------------

### Updated
Updated TinyMCE to version 3.5.10 to fix the IE11 issues (see #6479).

### Fixed
Optionally override the repository tables when importing a template (see #6470).

### Fixed
Only do the UUID conversion once even if the `Database\Updater` helper methods
are called multiple times (see #6481).

### Fixed
Correctly toggle the mobile/desktop view (see #6227).

### Fixed
Correctly detect UUIDs in the "file" insert tag (see #6472).

### Fixed
Correctly assign images to FAQs (see #6465).

### Fixed
Improved the speed and memory footprint of the news archive menu (see #6463).

### Fixed
Removed `CalendarEventsModel::findBoundaries()` (see #6467).


Version 3.2.0 (2013-11-21)
--------------------------

### Fixed
Handle UUID strings in the UUID related `FilesModel` methods (see #6445).

### Fixed
Applied some minor fixes to the database installer.

### Improved
Split the routines to convert database fields to UUIDs into separate methods:

 * `Database\Updater::convertSingleField($table, $field)`
 * `Database\Updater::convertMultiField($table, $field)`
 * `Database\Updater::convertOrderField($table, $field)`

### Fixed
Correctly show the folder protection status in the file picker (see #6433).

### Fixed
Correctly protect newly created folders (see #6432).

### Fixed
Correctly generate HTTPS URLs in the sitemap (see #6421).

### Fixed
Added the missing "sqlGetFromDca" hook (see #6425).

### Fixed
Support CSS selectors up to 1022 charachters long (see #6412).

### Fixed
Support UUIDs in `FilesModel::findByPk()`, `FilesModel::findById()` and
`FilesModel::findByMultipleById()` to be backwards compatible.

### Fixed
Set the correct empty value depending on the database field type (see #6424).

### Fixed
URL decode image paths when exporting to PDF (see #6411).

### Fixed
Do not add news and event URLs to the sitemap if the target page is exempt from
the sitemap (see #6418).

### Fixed
Allow special characters in `Validator::isUrl()` (see #6402).

### Fixed
Sort the list of available modules (see #6391).

### Fixed
Standardize the user home directoy name upon registration (see #6394).

### Fixed
Correctly handle "enum" fields in the database installer (see #6387).

### Fixed
Do not load a page from cache if a user is (potentially) logged in.

### Fixed
Skip empty locale strings when building the language cache.

### Improved
Slightly increased the contrast in the back end.

### Fixed
Fixed the ACE version number and added an inverted theme (see #6101).

### Fixed
Correctly handle "includeBlankOption" and numeric columns (see #6373).

### Fixed
Correctly detect IE11 in the `Environment::agent()` method (see #6378).

### Fixed
Disable the maintenance mode if a back end user is logged in (see #6353).

### Fixed
Correctly detect Android tablets in the `Environment` class (see #5869).

### Fixed
Create a new version if an element type changes (see #6363).

### Fixed
Purge the internal cache in the install tool (see #6357).

### Fixed
Add all resize modes to the TinyMCE thumbnail image screen (see #6362).

### Fixed
Correctly add the parameters to the TinyMCE thumbnail image (see #6361).

### Fixed
Disable HTML5 form validation in "select multiple" mode (see #6354).

### Fixed
Convert binary UUIDs to their hex equivalents in the diff view (see #6365).

### Fixed
Do not allow to create website root pages outside the root level (see #6360).

### Updated
Updated jQuery to version 1.10.2 and jQuery UI to version 1.10.3 (see #6367).

### Fixed
Correctly link to FAQs using the "faq" insert tag.

### Fixed
Correctly mark checkboxes and radio buttons as mandatory (see #6352).


Version 3.2.RC1 (2013-10-24)
----------------------------

### Fixed
Add the "onclick" event to the "select all" checkbox (see #6314).

### Improved
Only show the news/event source options if the user is allowed to access the
fields required to configure those options (see #5498).

### New
Added the "getAttributesFromDca" hook (see #6340).

### New
Add the "maintenance mode" and automatically enable it when an extension is
installed, upgraded or removed (see #4561).

### Fixed
Correctly handle "toggle visibility" and drag and drop requests via Ajax.

### Improved
Correctly display nested wrapper elements (see #5976).

### New
Added the "isVisibleElement" hook to determine whether an element is visible in
the front end (see #6311).

### Fixed
Handle tables without keys in `Database::listFields()` (see #6310).

### Fixed
Allow FAQ categories without a redirect page (see #6226).

### Fixed
Create a new version of a record if a sorting field changes (see #6285).

### Fixed
Show the teaser text of redirect events in the event list (see #6315).

### Improved
Support the "autocomplete", "autocorrect", "autocapitalize" and "spellcheck"
attributes in the Widget class, so they can be set in the DCA (see #6316).

### Fixed
Added some validation logic to the `Result::data_seek()` methods (see #6319).

### Improved
`Model::__callStatic()` now also supports "countBy" (see #5984).

```php
// new magic method
$count = PageModel::countByPid(3);

// will be mapped to
$count = PageModel::countBy('pid', 3);
```

### Updated
Updated mediaelement.js to version 2.13.1 (see #6318).

### Fixed
Correctly handle slashes and empty strings in the TinyMCE link tab.

### Fixed
Order the template list alphabetically (see #6276).

### Improved
Simplified the "iflng" insert tags (see #6291). You can now omit every closing
`{{iflng}}` tag but the last one, e.g.:

```
{{iflng::de}}Hallo Welt{{iflng::en}}Hello world{{iflng}}
```

### Updated
Updated Colorbox to version 1.4.31 (see #6309).

### Fixed
Create new UUIDs when duplicating files or folders (see #6301).

### Fixed
Correctly handle booleans, null and empty strings in the Validator (see #6287).

### Fixed
Correctly assign the user's home directory (see #6297).

### Changed
Move the "create IDE compat file" logic to a command line script (see #6270).


Version 3.2.beta2 (2013-10-10)
------------------------------

### New
Added a model registry (thanks to Tristan Lins) (see #6248).

### New
Added the "compileFormFields" hook (see #6253).

### Fixed
Append the article ID to the CSS ID if there is no alias (see #6267).

### Fixed
Use a PHP variable for the user agent in the back end (see #6277 and #3074).

### Updated
Updated TCPDF to version 3.0.38 (see #6268).

### Fixed
Correctly show the "toggle page status" icon (see #6282).

### Improved
Use a "show details" button in the file manager (see #6262).

### Changed
Use the micro clearfix hack in the CSS framework (see #6203).

### Fixed
Convert binary UUIDs to hex when using it in SQL statements (see #6265).

### Fixed
Convert binary data to UUIDs in `DC_Table::show()` (see #6257).

### New
Allow to define custom layout sections in the page layout (see #2885).

### New
Added the custom layout sections positions "top" and "bottom" (see #2885).

### Fixed
Use serialized arrays to store order field data (see #6255).

### Fixed
Do not strip leading numbers from file names (see #6189).

### Improved
Hide the script hint if a user cannot access to the layout module (see #6190).

### Fixed
Correctly generate image links (see #6249).

### New
Added the convenience method `PageModel::getFrontendUrl()` (see #6184).

### Removed
Removed the TinyMCE spell checker (see #6247).

### Improved
Do not show dates in the past if a recurring event has not expired (see #923).

### New
Pass the ID of the `tl_undo` record to the "ondelete_callback" (see #6234).

### New
Added the "br" insert tag to insert line breaks (see #6143).

### Fixed
Do not alter the order of the UUID chunks (no optimized order).


Version 3.2.beta1 (2013-09-27)
------------------------------

### Fixed
Make usernames case-sensitive.

### New
Added a `system/docs/UPGRADE.md` file to document API changes (see #6236).

### Changed
Send an "X-Ajax-Location" header to redirect upon Ajax requests (see #5647).

### New
Added new DCA table config flags (see #5254):

 * `closed`: no new rows can be added at all
 * `notEditable`: the rows cannot be edited
 * `notDeletable`: the rows cannot be deleted
 * `notSortable`: the order of the rows cannot be altered (new)
 * `notCopyable`: existing rows cannot be duplicated (new)
 * `notCreatable`: prevents to create rows but allows to duplicate rows (new)

The `closed` flag hence is a combination of `notCreatable` and `notCopyable`.

### Improved
Always show the save buttons in the modal windows (see #5985).

### New
Add the CSS classes "first" and "last" to articles/content elements (see #2583).

### New
The form generator now supports defining a minimum input length (see #4394).

### New
If you are running Contao via an SSL proxy server, you can now set the proxy
server domain in the back end settings (see #4615).

### Changed
Allow to alter any button set via the "buttons_callback" (see #4691). This
includes any edit, edit multiple, select or upload form and also includes the
option to unset or replace the default buttons.

[BC-BREAK] If you have been using the "buttons_callback" in version 3.0 or 3.1,
you will have to adjust your code to reflect the changes!

### Improved
Show the release notes when installing or upgrading an extension (see #5058).

### New
Add an `arc_[archive-id]` CSS class to all news list items (see #4998).

### New
You can now define a list of trusted proxy server IPs in the back end settings
to improve identifying the user's remote address (see #5830).

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
