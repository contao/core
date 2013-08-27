Contao Open Source CMS Changelog
================================

Version 3.1.3 (2013-XX-XX)
--------------------------

### Fixed
Handle all possible errors when uploading files (see #5934).


Version 3.1.2 (2013-08-27)
--------------------------

### Fixed
Add the global date format in `PageModel::loadDetails()` (see #6104).

### Fixed
Do not override the referer upon Ajax requests (see #5956).

### Fixed
Fixed the content slider in IE < 9 (see #5878).

### Fixed
Do not set a database driver by default (see #6088).

### Fixed
Decode punycode domains in the listing module (see #5946).

### Fixed
Show all themes a template is defined in (see #6071).

### Fixed
Do not add the domain name twice in `redirectToFrontendPage()` (see #6076).

### Fixed
Use the `currentLogin` field to sort users by their last login (see #5949).

### Fixed
Fix the offset handling in the CSS grid (see #5943).

### Fixed
Do not use the `date`, `time` and `datetime` input types (see #5918).

### Fixed
Show tooltips for selected single images in the file picker (see #6031).

### Fixed
Correctly synchronize if a sub folder is selected (see #5979).

### Fixed
Correctly handle password which are longer than 64 characters (see #6015).

### Fixed
Added missing Vietnamese characters to the UFT8 mapper (see #6010).

### Fixed
Decode entities in the page and file pickers (see #5989).

### Fixed
Ensure that the default user and group are integer values (see #6017).

### New
Added the Czech typolinks translations (thanks to ShiraNai7) (see #6051).

### Fixed
Added an option to purge the search cache (see #6041).

### Fixed
Preserve the repository tables when importing a theme (see #6037).

### Fixed
Pass the module to `getAttributesFromDca()` in the registration and personal
data module classes (see #6002).

### Fixed
Validate the e-mail address when creating an admin user (see #6003).

### Fixed
Fix the newslist pagination count (see #5997).

### Fixed
Make the GD image max width and height parameters mandatory (see #5940).

### Fixed
Replace all insert tags when exporting a page as PDF (see #5990).

### Fixed
Correctly validate the options in `Widget::isValidOption()` (see #5951).

### Fixed
Decode IDNA domains in any system mail (see #5932).

### Fixed
Store integers bigger than `PHP_INT_MAX` as string (see #5939).

### Fixed
Fix the alignment of the versions menu in IE (see #5962).

### Fixed
Do not cache the result of `Model::count*()` (see #5973).

### Fixed
Added some missing office file extensions to the configuration (see #6021).

### Fixed
Fixed the "indexPage" hook (see #5967).

### Fixed
Do not copy the autologin hash when duplicating members (see #5945).

### Fixed
Added .svgz support to the default `.htaccess` file (see #5938).


Version 3.1.1 (2013-06-25)
--------------------------

### Fixed
Append the query string when forwarding (see #5867).

### Fixed
Decouple the file/page picker breadcrumb from the file/page manager (see #5899).

### Fixed
Also show the mandatory star in password confirmation fields (see #5926).

### Fixed
Only return one IP address in `Environment::get('ip')` (see #5830).

### Fixed
Explicitly check for `.php` files when scanning DCA files (see #5898).

### Fixed
Replaced all dummy `.htaccess` files with `.gitignore` files.

### Fixed
Quote wildcard characters in MySQL `LIKE` queries (see #5896).

### Fixed
Correctly align the version drop-down menu in Safari (see #5854).

### Fixed
Make sure `window.$` is mapped to MooTools (see #5892).

### Fixed
Do not add sort buttons to table row headers (see #5845).

### Fixed
Show the newsletter channels upon registration (see #5874).

### Updated
Updated ACE to version 1.1.01 (fixes #5852).

### Fixed
Correctly handle hidden pages in the custom navigation module (see #5832).

### Fixed
Support FAQs with images on the FAQ page (see #5810).

### Fixed
Support using commas in folder names in the file selector (see #5823).

### Fixed
Ignore the `auto_item` parameter when forwarding internally (see #5886).

### Fixed
Added support for old IE versions to swipe.js (see #5862).

### Fixed
Correctly bypass the cache if `bypassCache` is set (see #5872).

### Fixed
Preserve the CSS3PIE behavior file path when combining style sheets (see #5848).

### Fixed
Support all known template types in the autoload creator (see #5857).

### Fixed
Correctly adjust the accordion elements to the new DB structure (see #5820).

### Fixed
Added `E_USER_DEPRECATED` to the list of error constants (see #5839).


Version 3.1.0 (2013-05-21)
--------------------------

### Fixed
Set the image dimensions if an image is added in TinyMCE (see #5790).

### Fixed
Pass the host and language to subpages when generating the menu (see #3765).

### Fixed
Improve the timeout calculation of the command scheduler to better support
minutely jobs (see #5775).

### Fixed
Do not used cached `scan()` results in the `Files` class (see #5795).

### Fixed
Do not throw an exception during RSS feed generation if a news archive or
calendar is linked to an invalid target page (see #5781).

### Fixed
Fix the tabindexes if there are multiple wizards on the same apge (see #5779).

### Fixed
Also support textareas when autofocusing input fields (see #5774).

### Fixed
Add a page to the XML sitemap even if it is not indexed internally (see #5714).

### Fixed
Correctly auto-create the page aliases (see #5765).

### Fixed
Limit the width of the table names in the version overview (see #5769).

### Fixed
Urldecode file names in the file manager in "edit multiple" mode (see #5764).

### Fixed
Do not propagate the click events of the edit icons (see #5731).

### Fixed
Fix the return value of `ModuleLoader::getDisabled()` (see #5726).

### Fixed
Re-add the website title to the login screen (see #5749).

### Fixed
Store the language when creating an administrator account (see #5718).

### Fixed
Do not show the URL encoded file name on the confirmation screen (see #5543).

### Fixed
Correctly add image enclosures to calendar feeds (see #5685).

### Fixed
Pass the news count to `ModuleNews::parseArticle()` (see #5708).

### Fixed
Detect the Silk user agent of the Kindle Fire (see #5679).

### Fixed
Correctly auto-generate folder URL aliases (see #5697).

### Fixed
Register the CSV import in the news and calendar modules (see #5701).

### Fixed
Do not run the database updates in a fresh installation (see #5694).

### Fixed
Correctly update the edit links in the module wizard (see #5687).

### Fixed
Make sure the jQuery related database fields exist when the version 3.1 update
is executed (see #5689).

### Fixed
Convert the page language to a locale when looking for meta data (see #5678).

### Fixed
Replace existing `file` parameters in the download elements (see #5683).

### Fixed
Do not use `count()` in a `for` loop condition (see #5681).

### Fixed
Hide the back link and "save and back" buttons in the modal dialog.

### Fixed
Do not store an empty `tl_versions.editUrl` field upon Ajax requests.

### Fixed
Correctly reload or redirect upon Ajax requests (see #5647).

### Fixed
Preserve protocol relative URLs when minifying the HTML markup (see #5664).

### Fixed
Correctly export the `pubDate` of calendar feeds (see #5641).

### Fixed
Remove script tags when printing an article as PDF (see #5626).

### Fixed
Purge the internal cache when disabling the Contao safe mode (see #5579).

### Fixed
Correctly set the end date of duplicated events (see #5608).

### Fixed
Apply the access restrictions of content elements and modules in the front end
only (see #5603).


Version 3.1.RC1 (2013-04-18)
----------------------------

### Fixed
Correctly detect open HTML tags in `String::substrHtml()` (see #5669).

### New
Added the "initializeSystem" hook (see #5665).

### Fixed
Do not trigger the "setNewPassword" hook twice (see #5247).

### Improved
Inject the command scheduler script inline to reduce the number of JS files.

### Fixed
Store IDN domains in Punycode format (see #5571).

### Fixed
Re-added the row classes to the image gallery (see #5658).

### New
Added the `File::getModel()` and `Folder::getModel()` methods (see #5656).

### Improved
Made the error screens translatable.

### Updated
Updated SwiftMailer to version 4.3.1.

### Removed
Removed the MooTools mediaelement.js version.

### New
Show a hint if a content element needs a MooTools or jQuery template.

### Improved
Support assigning icons for global operations in the DCA (see #5541).

### Fixed
Show the edit buttons of selected nodes in the file manager.

### Fixed
Do not rebuild the URLs for the search index (see #5509).

### Updated
Updated TCPDF to version 6.0.010 (see #5614).

### Changed
The tablesort script can now be added as `moo_` or `j_` template in the page
layout, so the library can be replaced if needed.

### Updated
Updated Swipe.js to version 2.0 and reworked the slider implementation.

### Updated
Updated Picker.Date to version 2.0.0.

### Updated
Updated Colorbox to version 1.4.11.

### Updated
Updated jQuery UI to version 1.10.2.

### Updated
Updated jQuery to version 1.9.1 (see #5600).

### Updated
Updated html5shiv to version 3.6.2.

### New
You can now click elements to edit them:

 * `[Ctrl] + click`: edit the element
 * `[Ctrl] + [Shift] + click`: edit the element's header

On Mac OS the command key is used instead:

 * `[Cmd] + click`: edit the element
 * `[Cmd] + [Shift] + click`: edit the element's header

On touch devices, you can to touch an element twice to edit it.

### Improved
Allow to set the slider delay and speed in the wrapper element.

### Fixed
Always check the `/templates` folder for customized templates even if there is
a theme-specific template folder (see #5547).

### Fixed
Make `$this->inColumn` available in content elements and hybrids (see #5442).

### Fixed
Support all `white-space` settings in the style sheet editor (see #4519).

### Improved
Show a preview image when editing image meta data (see #4948).

### Improved
Merge the custom layout sections upon theme import (see #5000).

### New
Support creating indexes on multiple columns in the DCA. Usage example:

```
'sql' => array
(
  'keys' => array
  (
    'id' => 'primary',
    'pid,name' => 'unique',
  )
)
```

This will create a unique index over the `pid` and `name` columns.

### New
Added a `$GLOBALS['TL_BODY']` array to add custom code at the end of a page
independent from MooTools or jQuery (see #5583).

### Improved
Edit all related elements (e.g. alias elements) in a modal dialog.

### Improved
Added an option to disable modules in the page layout (see #5558).

### Fixed
Added a vendor library to parse the Contao changelog file (see #5569).


Version 3.1.beta1 (2013-03-28)
------------------------------

### New
Added the "parseWidget" hook (see #5553).

### Changed
Link insert tags and navigation modules are now domain-aware (see #3765).

### Improved
Improved the database-assisted file system:

 * The file picker now shows the file system instead of the database
 * You can switch from the file picker to the file manager in the popup dialog
 * The file picker automatically adds the selected resources to the database

This means that you do not have to manually synchronize the file system and the
database anymore. Further improvements:

 * Added a breadcrumb menu to limit the nodes of the page and file picker
 * `md5_file()` is no longer applied to files bigger than 2 GB
 * A folder hash is now calculated of the file names instead of their hashes
 * All DBAFS-related routines have been centralized in the `Dbafs` class
 * Permission to synchronize the file system can be given to regular users

### Improved
Use the Contao page and file pickers in TinyMCE (see #1698).

### Improved
Pre-fill the "alt" and "caption" fields with the file meta data (see #5225).

### Improved
Override the page description if a certain article is requested (see #4742).

### Changed
Moved `System::reload()`, `System::redirect()` and `System::addToUrl()` to the
`Controller` class (see #4698).

### Improved
Always show the image thumbnails in the "FileTree" widget (see #4358).

### New
Added a location field to the calendar module (see #1870).

### New
Added new methods to models and model collections:

 * `Model::getResult()`:      returns the database object
 * `Collection::fetchAll()`:  returns the data of all models as array
 * `Collection::getResult()`: returns the database object

### New
Insert tags now support flags (see #4717). Usage example:

 * `{{ua::browser|uncached}}`
 * `{{page::title|decodeEntities|strtoupper}}`

Currently supported flags are:

 * `uncached`:        preserve the tag when writing the cache file
 * `refresh`:         regenerate the output ignoring any cached version
 * `addslashes`:      quote the output with slashes
 * `stripslashes`:    un-quote the output
 * `standardize`:     standardize the output
 * `ampersand`:       convert ampersands to entities
 * `specialchars`:    convert special characters to entities
 * `nl2br`:           new line to `<br>`
 * `nl2br_pre`:       new line to `<br>` except in preformatted text
 * `strtolower`:      string to lower case
 * `utf8_strtolower`: Unicode aware string to lowercase
 * `strtoupper`:      string to upper case
 * `utf8_strtoupper`: Unicode aware string to uppercase
 * `ucfirst`:         make first character uppercase
 * `lcfirst`:         make first character lowercase
 * `ucwords`:         uppercase the first characeter of each word
 * `trim`:            strip whitespace from the beginning and end
 * `rtrim`:           strip whitespace from the beginning
 * `ltrim`:           strip whitespace from the end
 * `utf8_romanize`:   romanize the output
 * `strrev`:          reverse the output string
 * `encodeEmail`:     decode encoded e-mail addresses
 * `decodeEntities`:  decode entities
 * `number_format`:   format a number (`System::getFormattedNumber()`)
 * `currency_format`: format a currency (`System::getFormattedNumber()`)
 * `readable_size`:   convert the output to a human readable size

Custom flags can be added via the "insertTagFlags" hook.


### New
Added an option to purge the system log to the maintenance module (see #4701).

### New
Added the `File::resizeTo()` method (see #3883).

### Improved
Use the new HTML5 form fields in the front end (see #4138).

### New
Added the "getPageLayout" hook (see #4736).

### New
Added more options to the event list module (see #5481):

 * show upcoming events of the next month/year
 * show past events of the previous month/year

### New
Added the "modifyFrontendPage" hook (see #4291).

### New
Replaced CodeMirror with ACE (see #5332).

### New
The newsletter subscription module now also stores the confirmation date of
the double opt-in subscription (see #5200).

### Improved
Comments with a reply now have a special icon (see #3202).

### Changed
Renamed `responsive.css` to `grid.css` (see #5475).

### Updated
Updated mediaelement.js to version 2.11.3 (see #5495).

### New
Made the number of pagination links configurable (see #4601).

### New
Added an option to disable CSS3PIE in the style sheet settings (see #4985).

### Improved
Link the file/page picker and file/page manager (see #4856).

### Changed
Made the years in monthly archive menus clickable (see #4450).

### New
The form generator now supports the "novalidate" attribute (see #4263).

### New
Added access protection for articles (see #1869).

### New
Added the `Widget::addAttribute()` method (see #4744).

### Changed
Back end forms are no longer saved if the form is auto-submitted (see #4077).
This will prevent empty field warnings when switching element types.

### Changed
The "show details" link now opens in a modal window (see #5188).

### Changed
The search index is now rebuilt via Ajax requests.

### Improved
Improved the back end referer management to support multiple tabs (see #5436).

### New
Automatically synchronize the database when working on a file or folder in the
upload directory with the `File` or `Folder` class (see #4991).

### Improved
Moved the "protect folder" settings to the edit screen (see #5376). Also, Contao
now shows a "possibly not working" note if not an Apache server is used.

### Improved
Added a "file upload" button to each folder in the file manager (see #3432).

### Changed
Added a "default" option to the login language select menu (see #5088).

### Improved
Show the database ID of a file in the file popup (see #5211).

### Changed
Made `Config::get()` static (see #3135).

### Improved
Add the autoloader files to the internal cache.

### New
Support multiple filter panels in the DCA (see #4542).

```
// Define the panel layout
$GLOBALS['TL_DCA']['…']['panelLayout'] = 'filter;filter;sort,limit';

// Assign the filters to the panels
$GLOBALS['TL_DCA']['…']['fields']['country']['filter'] = 1; // or true
$GLOBALS['TL_DCA']['…']['fields']['groups']['filter'] = 2;
```

### New
Added the "panel_callback" to add custom panels to a DCA view (see #4542).

### New
Added a content element slider based on swipe.js (see #4600).

### Improved
Replaced the up and down arrows with a drag handle where applicable and made
the items sortable via drag and drop (see #4434). This includes:

 * the checkbox wizard
 * the list wizard (content element)
 * the table wizard (content element)
 * the module wizard (page layout)
 * the options wizard (form generator)
 * the key/value wizard

The parent view now uses the drag handle to move items, too.

### Changed
Handle the plus in words like "Google+" or "A+B" in the seach (see #4497). This
will require to rebuild the search index after the update!

### Fixed
Decouple the `Automator` class from `Backend`, so the database connection is not
established by default (see #5362).

### Changed
Moved `Controller::getTimeZones()` to `System::getTimeZones()`.

### Changed
Moved `System::getModelClassFromTable()` to `Model::getClassFromTable()`.

### Changed
Moved `System::parseDate()` to `Date::parse()`.

### Changed
Moved `Controller::prepareForWidget()` to `Widget::setAttributesFromDca()`.

Make sure to always call the inherited method of the specific widget class and
not the one of the parent class:

```
// Wrong
new TextField(Widget::setAttributesFromDca(…));

// Correct
new TextField(TextField::setAttributesFromDca(…));
```

See #4697 for more information.

### New
Added the redirect status code 307 to `System::redirect()` (see #5375).

### New
Added the `File::sendToBrowser()` method (see #4696).

### Changed
Made all static methods of the `Input` class public (see #3382).

### New
Optionally connect to the database via socket (see #5181).

### Improved
Allow to scroll the page when moving elements via drag and drop (see #5081).

### New
Added the `Database\Statement::executeCached()` method, which replaces existing
cache entries with the new result set (see #5310).

### Improved
Use a local version of "Architects Daughter" in the back end (see #5312).

### Changed
Do not build the internal cache automatically (see #5307).

### Changed
Replaced the member select menu in the front end preview pane with a text field
to enter a username (see #5320). The text field supports autocompletion based on
an HTML5 data list which is dynamically filled via Ajax.

### Fixed
Fixed the `moo_mediaelement.xhtml` template (see #5309).

### Fixed
Allow users to edit comments of news archives and calendars even if they are not
allowed to access the news or calendar module (see #5174).

### Improved
Better file synchronization statistics (see #4908).

### Changed
Always show the mandatory star in the field labels (see #4730).

### Fixed
Trigger the error 404 page if a request parameter is set twice (see #4277).

### Changed
Moved the Transifex `.xlf` files to the `languages` folders of the modules and
tweaked the `System::loadLanguageFile()` method to handle them (see #5005).

### Changed
Moved `Controller::getPageDetails()` to `PageModel::findWithDetails()` and
`PageModel->loadDetails()` (see #4692).

### Changed
Moved `Controller::findContentElement()` to `ContentElement::findClass()` and
`Controller::findFrontendModule()` to `Module::findClass()` (see #4684).

### Changed
Moved `Controller::optionChecked()` and `Controller::optionSelected()` to the
`Widget` class (see #4665).

### Changed
Moved `System::getIndexFreeRequest()` to `Environment::get('indexFreeRequest')`
(see #4685).

### Changed
Moved `Controller::printArticleAsPdf()` to `ModuleArticle::generatePdf()`
(see #4683).

### Changed
Moved `Controller::generateImage()` to `Image::getHtml()` (see #4664).

### Changed
Moved `System::splitFriendlyName()` to `String::splitFriendlyEmail()` and
made the `Email` class independent from the `System` class (see #4694).

### Changed
Moved `Controller::getBackendThemes()` to `Backend::getThemes()` (see #4694).

### Changed
Moved `Controller::getTheme()` to `Backend::getTheme()` (see #4662).

### Improved
Added the classes "toggle_desktop" and "toggle_mobile" to the desktop/mobile
switch link (see #4975).

### Improved
Language dialects such as `fr_FR` are now supported (see #4836). Note that
locales like `fr_FR` (used to store user languages) differ from language codes
like `fr-FR` (used to define page languages).

### Improved
The pageTree widget is now sortable just like the fileTree widget and the custom
and quick navigation modules consider the custom order (see #4936).


Version 3.0.6 (2013-03-21)
--------------------------

### Fixed
Do not add links to news, events, FAQs or newsletters to the sitemap if the
target page has not been published (see #5520).

### Fixed
Include the local configuration file twice, once before and once after the
module configuration files are parsed (see #5490). This will make settings like
the debug or safe mode work properly.

### Fixed
Correctly set the RSS feed self-reference (see #5478).

### Fixed
Remove `&shy;` and `&nbsp;` from RSS and Atom feeds (see #5473).

### Fixed
Do not remove the grid column margin on mobile devices (see #5475).

### Fixed
Store the relative path to the installation in the `pathconfig.php` (see #5339).

### Fixed
Correctly send the comment moderation mails (see #5443).

### Fixed
Correctly create the user home directory upon registration (see #5437).

### Improved
Made the `.htaccess` files Apache 2.4 ready (see #5032).

### Fixed
Also truncate opened files in `File::truncate()` (see #5459).

### Fixed
Added the "allowTransparency" attribute to the mediabox script (see #5077).

### Fixed
The submit button label was not shown in the `FormSubmit` widget (see #5434).

### Fixed
Show invisible elements in the back end preview (see #5449).

### Fixed
Allow to create forward pages without a specific target (see #5453).

### Fixed
Updated the TinyMCE typolinks plugin (see #5329).

### Fixed
Correctly initialize the user's pagemounts (see #5454).

### Fixed
Support loading static JavaScripts in the `config.php` files (see #4890).

### Fixed
Show all articles if the article list module is in the same column (see #5373).

### Fixed
Do not show `mail_` templates from theme folders (see #5379).

### Fixed
Consider only published events when finding the calendar boundaries and only
render the previous and next links if there are events (see #5426).

### Fixed
Do not override the header and footer height in the layout builder (see #5368).

### Fixed
Correctly reset fallback, default and "do not copy" fields (see #5252).


Version 3.0.5 (2013-02-19)
--------------------------

### Fixed
Removed the pixel unit from the video width and height attributes (see #5383).

### Fixed
Correctly load the language files (see #5384).


Version 3.0.4 (2013-02-14)
--------------------------

### Fixed
Correctly split the words when adding to the search index (see #5363).

### Fixed
If an eagerly loaded relation does not exist, return `null` instead of an empty
model in `Model::getRelated()` (see #5356).

### Fixed
Throw an exception if the file system and the database are out of sync and
show a meaningful error message (see #5101).

### Fixed
Return an associative array in `Model_Collection::fetchEach()` if the requested
field is **not** `id` (see #5134).

### Fixed
Make eagerly loaded "pageTree" fields mandatory again (see #4866).

### Fixed
Do not use forward pages as upper page in the book navigation (see #5074).

### Fixed
Correctly show the "empty news list" note (see #5304).

### Fixed
Correctly sort values by an external order field (see #5322).

### Fixed
Define the login status constants in the back end (see #4099, #5279).

### Fixed
Make sure the drag'n'drop hints do not overlay the field labels (see #5338).

### Fixed
Apply the color picker to single fields as well (see #5240).

### Fixed
Correctly close the SimpleModal overlay with the escape key (see #5297).

### Updated
Update TinyMCE to version 3.5.8 (see #5273).

### Fixed
Correctly check for nested arrays in `Widget::isValidOption()` (see #5328).

### Fixed
Preserve the order of multi source fields when exporting a theme (see #5237).

### Fixed
Also check whether the target exists when creating new folders (see #5260).

### Fixed
Load the core `autoload.php` files first (see #5261).

### Fixed
Support `null` as column default value in the DCA (see #5252).

### New
Added the `$blnDoNotCreate` option to the `Files` class, which makes the class
write to a temporary file first and then move it to its destination in one
atomic operation. This fixes some cache issues (see #5307).

### Fixed
Handle `@` blocks when importing style sheets (see #5250).

### Fixed
Show the newsletter list even if there is no jumpTo page configured in the
channel and show the enclosures in the newsletter reader (see #5233).

### Fixed
Added an option to load model relations uncached (see #5248, #5102). Also fixed
the `array_merge()` order so the default options can be overriden.

### Updated
Updated SimplePie to version 1.3.1 (see #5207).

### Updated
Updated SwiftMailer to version 4.3.0 (see #5263).

### Fixed
The jQuery accordion script did not work with minified markup (see #5245).

### Fixed
Removed the "spaceToUnderscore" option from all alias fields (see #5266).

### Fixed
The media content element now supports .ogg files (see #5282).

### Fixed
Do not rewrite requests for .mp3, .mp4, .webm or .ogv files (see #5258, #5284).

### Fixed
Correctly determin the last run of the command scheduler (see #5278).

### Fixed
Make the jQuery accordion behave like the MooTools version (see #5251).

### Fixed
Added support for more advanced media queries (see #5236).

### Fixed
Added the missing `UserGroupModel` class (see #5218).

### Fixed
Handle the case that `glob()` returns `false` (see #5226).

### Fixed
The table sorter did not work if jQuery and MooTools were active (see #5228).

### Fixed
Copy all content elements if pages are duplicated with childs (see #5241).

### Fixed
Added lazy template loading for newsletter mail templates.


Version 3.0.3 (2013-01-08)
--------------------------

### Fixed
Do not separate a style sheet with a font-face selector if the definition is
invisible or the media type of the style sheet is "all" (see #5216).

### Fixed
Looking for theme templates broke the install routine (see #5210).

### Fixed
Correctly handle empty newsletter channel selections.


Version 3.0.2 (2013-01-07)
--------------------------

### Fixed
Throw an error if FileTree or PageTree widgets are left blank although they are
marked as mandatory in the DCA (see #5131).

### Fixed
Modules and Hybrids included via content element were shown even if the content
element was invisible or not published (see #5203).

### Fixed
Do not try to limit the template selection to a particular theme but show all
available themes instead (see #5095).

### Fixed
Correctly build the comments subscription confirmation URL (see #5201).

### Fixed
Update the database if a file is being uploaded in the front end (see #5137).

### Fixed
Do not send a 404 header if an enclosure is requested and cannot be find by a
module; there might be another module which can (see #5178).

### Fixed
Consider the `save_callback` of the password field in `tl_user` when a back end
user is forced to change his password (see #5138).

### Fixed
Random images now open in the lightbox if configured (see #5191).

### Fixed
Find e-mail addresse like `a@b.com` in `String::encodeEmail()` (see #5175).

### Fixed
Make sure there is a minimal MooTools core version for the command scheduler
(see #5195).

### Fixed
Made `Model::getPk()` and `Model::getTable()` static (see #5128).

### Fixed
Do not move resources in the file manager if the targets exist. Otherwise the
database might get out of sync with the file system (see #5145).

### Fixed
Convert automatically generated article alias names if the page uses folder URL
style alias names (see #5168).

### Fixed
The newsletter system did not yet handle file ID attachments (see #5118).

### Fixed
The gallery and downloads element now support using the user's home directory
again (see #5113).

### Fixed
Added an option to load models uncached (see #5102).

### Fixed
Added support for `CURRENT_DATE`, `CURRENT_TIME` and `CURRENT_TIMESTAMP` to the
database installer (see #5089).

### Fixed
Store the whole database row in `Calendar::addEvent()` so e.g. RSS feeds with
the event text instead of just the teaser are being rendered (see #5085).

### Fixed
Purge the internal cache after a module has been (de)activated (see #5016).

### Fixed
Do not cache the `system/cron/cron.txt` file (see #5105).

### Fixed
Do not create content elements for news and events which redirect to articles,
pages or external URLs during the version 3 update (see #5117).

### Fixed
Handle incorrectly closed indexer comments (see #5119).

### Fixed
The table content element did not assign the correct CSS class names when there
was only one row and one column (see #5140).

### Fixed
Consider the dynamic ptable when copying/deleting content elements (see #5041).

### Fixed
Scan templates in the autoload creator even if there are no classes (see #5158).

### Fixed
Corrected the main column margin when using the layout builder in combination
with the responsive grid (see #5170).

### Fixed
Consider the sorting order of external style sheets (see #5038).

### Fixed
The numeric file mounts of a user were overridden by the real paths (see #5083).


Version 3.0.1 (2012-11-29)
--------------------------

### Fixed
Exclude the undo module from the list of allowable back end modules (see #5056).

### Fixed
`Validator::isAlias()` did not support Unicode characters (see #5033).

### Fixed
Group the search results by their parent IDs when searching the extended tree
view, e.g. the article tree (see #5051).

### Fixed
Correctly generate the debug bar markup on XHTML pages (see #5031).

### Fixed
Handle radial gradients when importing style sheets (see #4640).

### Fixed
More abstract and effective algorithm to determin the number of files in the
"purge data" maintenance module (see #5028).

### Fixed
Fixed two wrong class paths (see #5027).

### Fixed
Correctly add event images to the templates (see #5002).

### Changed
Replaced the automatic copyright notice with a meta generator tag.

### Fixed
Do not strip tags from passwords (see #4977).

### Fixed
Correctly show the number of returned rows in the debug bar (see #4981).

### Fixed
Correctly add the RSS feed base URLs (see #4994).

### Fixed
Fixed an issue in the mediaelement.js MooTools adapter (see #4917).

### Fixed
Correctly assing the classes "first" and "last" in the (mini) calendar if the
week does not start on Sunday (see #4970).

### Fixed
Correctly handle URL parameters appended to the empty domain (see #4972).


Version 3.0.0 (2012-10-30)
--------------------------

### Updated
Updated all vendor scripts and assets to their latest version (see #4966).

### Fixed
Handle existing folders during a theme import (see #4952).

### Fixed
Show an error message instead of an exception if a template cannot be imported
in the install tool (see #4961).

### Fixed
Readded the "active" class to the custom navigation module (see #4963).

### Fixed
Always convert file IDs to paths when exporting themes (see #4952).

### Fixed
Mark active forward pages with "forward" instead of "active" (see #4822).

### Updated
Updated jQuery UI to version 1.9.1 (see #4953).

### Fixed
Remove HTML tags when overriding the page title (see #4955).

### Fixed
Correctly route pages if the language is not added to the URL and there are
multiple results or folder URL aliases (see #4872).

### Fixed
Do not cache pages if the request contains a token (see #4702).

### Fixed
Make the original element passed to a `Hybrid` object available (see #4556).

### Fixed
Show an error message instead of throwing an Exception if the file system and
the database are out of sync (see #4438).

### Fixed
Removed the deprecated workarounds for storing .xml files in the root directory.
Since the autogenerated .xml files now reside in the `share/` subfolder, .xml
files in the root directory will not be touched by `Automator::purgeXmlFiles()`.

### Fixed
Make sure the install tool and – after the version 3 update – the back end
remain accessible if the Contao 3 files are just added to an existing Contao 2
installation (which is not recommended) (see #4907).

### Fixed
Prevent deleting referenced content elements using "edit multiple" (see #4898).

### Fixed
Removed some left-over `ENT_COMPAT` constants (see #4889).

### Fixed
The too simple folder hash algorithm caused issues with the file synchronization
and was replaced with a more sophisticated one (see #4934).

### Fixed
Updated mediaelement.js to version 2.9.5 (see #4917).

### Fixed
If folder URLs are enabled in the back end settings, generate folder URL aliases
in the site structure (see #4933).

### Fixed
Readded the default value for textareas to the form generator (see #4932).

### Fixed
Readded the option to limit the file tree to a certain path (see #4926).

### Improved
Added a hint that selected files can be dragged to re-order them (see #4838).

### Fixed
Correctly add news and event images as RSS feed enclosures (see #4928).

### Fixed
Correctly scale videos (see #4896).

### Fixed
Readd a language to the meta editor drop-down if it is deleted (see #4716).

### Fixed
Add the static JavaScript file before the non-static ones (see #4890).

### Fixed
Correctly check permissions to toggle the visibility of content elements now
that they can be used everywhere (see #4894).

### Fixed
Added an accessible jQuery accordion variant (see #4900).

### Fixed
Correctly link to FAQs via insert tag (see #4905).

### Fixed
Correctly handle wildcards in the page and file picker (see #4910).

### Fixed
Correctly handle the case that a front end module is included in a page layout
more than once (see #4849).

### Fixed
Correctly detect the language fragment in the error 404 page (see #4669).

### Fixed
Correctly check for the version 2.9 update in the install tool (see #4920).

### Fixed
Automatically adjust the CSS framework if the layout builder and the responsive
grid are combined (see #4824).

### Fixed
Pass the cache status to all recursive `replaceInsertTags()` calls (see #4402).

### Updated
Updated jQuery to version 1.8.2 and jQuery UI to version 1.8.24 (see #4848).

### Fixed
The autoload creator now correctly reads files (see #4876).

### Fixed
Encode single quotes in JavaScript calls (see #4889).

### Fixed
Do not add a content element to news or events without text (see #4882).

### Fixed
Fixed the automatic page alias generator (see #4880).


Version 3.0.RC2 (2012-09-27)
----------------------------

### Fixed
Correctly handle small class files in the autoload creator (see #4876).

### Fixed
The Email class now correctly embeds all kind of images (see #4562).

### Fixed
Consider the dynamic parent table when deleting child records (see #4867).

### Fixed
Correctly detect the namespace in the autoload creator and support custom
configurations per path (see #4776).

### Fixed
Do not regenerate the `autoload.php` files when generating the IDE compatibility
file (see #4810).

### Fixed
Model class names which cannot be build from the corresponding table name can
now be registered in the `$GLOBALS['TL_MODELS']` array (see #4796).

### Fixed
Removed the back end context menu, because it was buggy in IE and did not work
at all on touch-based devices (see #4459).

### Fixed
Do not set a right boundary for the calendar navigation if there are events with
unlimited recurrences (see #4862).

### Fixed
Gradient angles are now converted from the new syntax to the legacy syntax for
the prefixed versions (see #4569). This also means that from now on you have to
use the new syntax, e.g. "to bottom" instead of "top" and "180deg" instead of
"270deg" to generate a top to bottom gradient.

### Fixed
Show the hint arrows in all "imageSize" fields by default (see #4326).

### Updated
Updated TCPDF to version 5.9.192 and fixed some CHMOD settings (see #4819).

### Fixed
Added the classes "first" and "last" to the breadcrumb menu to be more in line
with the other navigation templates (see #4833).

### Fixed
Correctly link to articles and FAQs when using insert tags (see #4835).

### Fixed
Do not add the file picker to the list of referer addresses (see #4855).

### Fixed
The CAPTCHA form field now supports the "placeholder" attribute (see #4865).

### Fixed
Correctly add enclosures to RSS/Atom feeds (see #4853).

### Fixed
Handle numeric IDs in the "image" insert tag (see #4805).

### Fixed
If folder URLs are disabled in the back end settings, the "generate alias" job
(edit multiple) will generate simple aliases (see #4846).

### Fixed
Correctly handle replacements when uploading files (see #4818).

### Fixed
Only limit `getTemplateGroup()` to a theme in the articles module (see #4808).

### Fixed
Decode Punycode domains when used via insert tag (see #4753).

### Fixed
Correctly handle open tags in `String::substrHtml()` (see #4773).

### Fixed
Correctly handle units when importing style sheets (see #4721).

### Fixed
Manually merge the legacy `database.sql` definitions (see #4766).

### Fixed
Skip news archives and calendars without a jumpTo page when creating RSS feeds
(see #4784).

### Fixed
Index the content of the download(s) element (see #4755).

### Fixed
The mediabox plugin did not play Vimeo videos (see #4770).

### Fixed
Comments can now be sorted descending again (see #4782).

### Fixed
Readded the news list "skip items" feature (see #4783).

### Fixed
Use the `Validator` class to validate date and time formats (see #4762).

### Fixed
Do not add invalid "float" commands to images (see #4758).

### Fixed
Fixed the CHANGELOG parser in the back end (see #4190).

### Fixed
Fixed the SyntaxHighlighter "html-script" option (see #4748).

### Fixed
Do not offer to drop all tables when installing an extension (see #4622).

### Fixed
Consider the domain, language and publication settings when searching for a
folder-style alias (see #4652).

### Fixed
Ignore case when entering an extension name in the extension installer of the
repository manager (see #4689).

### Fixed
Consider the language of a forward target when setting up a forward page or
using a `{{link}}` insert tag (see #4706).

### Fixed
Allow to import other white-space values than `nowrap` (see #4519).

### Fixed
Show the teaser text in the full view if a news item or event does not have a
text, so linking and commenting is possible (see #4630).

### Fixed
The style sheet importer now handles background gradients (see #4640).

### Updated
Update jQuery to version 1.8.1 (see #4678).

### Fixed
Correctly determine in the install tool whether it is a fresh installation or
the version 3 update is required (see #4676). Also, scan the files directory if
an administrator account is created during the installation.

### Fixed
If an article is selected, do not hide articles in other columns (see #4740).

### Fixed
Support uppercase TLDs when validating e-mail addresses (see #4738).

### Fixed
Do not show the tool tips if the title is empty (see #4672).

### Fixed
Show the back button when editing multiple elements in parent view (see #4709).

### Changed
Changed the `Controller::replaceInsertTags()` logic so non-cacheable tags are
preserved by default (see #4712).

### Fixed
Correctly link new items and events to articles (see #4728).

### Fixed
Output the dynamic HEAD tags before the static ones (see #4700).

### Fixed
Do not cache the page if the `file` parameter is set (see #4702).

### Changed
Renamed the public module folders to "assets" (see #4667).

### Added
You can now exempt folders from the files synchronisation (see #4522). Exempt
folders will also be hidden in the TinyMCE popup file select menu.

### Fixed
The newsletter subscription modules now work again (see #4660).

### Fixed
Add the static files URL to images added in the rich text editor.

### Fixed
Add the SyntaxHighlighter scripts at the page bottom.

### Fixed
Do not add the jQuery/MooTools scripts as separate scripts.

### Fixed
The new file structure only allows for two static URLs pointing to the upload
folder (`TL_FILES_URL`) and the assets folder (`TL_ASSETS_URL`) (see #4638). The
old constants will remain available for reasons of backwards compatibility.

### Fixed
Clone the Model and not the Collection when copying files (see #4628).

### Fixed
The "custom navigation" and "quick link" modules did not show if there was only
a single page (see #4616).

### Fixed
The quick navigation module could not jump to pages named "index" (see #4611).

### Fixed
Replaced `SplFileInfo::getExtension()` which is only available from PHP 5.3.6
with `pathinfo($info->getFilename())` (see #4619).

### Fixed
Do not send a 404 header if a download element does not find a file to send to
the client. There might be other download elements which do (see #4632).

### Fixed
Do not create files without file name in the extension creator (see #4635).

### Fixed
Moved `Controller::restoreBasicEntities()` to the `String` class (see #4646).

### Fixed
The file picker can now be accessed properly by regular users.

### Fixed
Make the modules' html folders accessible during the update and create the
required files in the extension creator as well.

### Fixed
Pass the minimum cron timeout value to the cron trigger so minutely cron jobs
are correctly executed (the minimum interval used to be 5 minutes).

### Fixed
Do not write to the local configuration file in the cron script (see #4483).

### Fixed
Downgraded the chosen plugin to make it work again (see #4595).


Version 3.0.RC1 (2012-08-08)
----------------------------

### New
Added a `config/autoload.ini` file to optionally disable the registration of
namespaces, classes or templates in the autoload creator (see #4591).

### Changed
Renamed the `system/modules/*/html` folders to `public`, since the only thing
which is not stored in there are HTML files.

### New
Moved all vendor PHP libraries to `system/vendor` and the Contao library to
`system/modules/core/library`. Also moved all vendor JavaScript plugins to the
`assets` folder.

### New
Added the "indexPage" hook (see #4506).

### New
Added the "prepareFormData" hook (see #4538).

### New
Added an option to get notified of new comments by e-mail (see #3858).

### Improved
Stop the script execution after the main controller is finished or when a back
end or front end template has been output (see #4565). This will prevent code
which has been injected at the end of a PHP file from being executed.

### Updated
Updated all third-party plugins and libraries to their latest version.

### Added
Insert tags can now dynamically add style sheets, JavaScripts and additional
head tags to the page (see #4203).

### Added
Support nested insert tags (one nesting level) (see #4402). Thanks a lot to
Christoph Wiechert for his great finds and hard work.

### New
Added a back end upload widget (see #4244).

### New
Added the "toggle_view" insert tag to toggle between mobile and desktop view, so
the autodetected status can be overridden (see #4308).

### Improved
Show some meta information in the "FileTree" widget (see #4330).

### Fixed
Removed all unnecessary prefixed format definitions (see #4463).

### Changed
Take the external style sheets from the database-aided file system (see #4324).

### Improved
`File::getContent()` now removed BOMs (see #4469).

### New
Added an option to limit the page picker to a predefined node set (see #3563).
To use the feature, set the `rootNodes` key in the DCA:

```
$GLOBALS['TL_DCA'][$table]['fields'][$field]['rootNodes'] = array(2, 6, 7);
```

### Changed
The breadcrumb templates now uses lists to render its links (see #1258).

### New
Added the insert tag "post" to access POST data (see #4448).

### Fixed
Fall back to `CRYPT_SHA256` or `CRYPT_BLOWFISH` if `CRYPT_SHA512` is not
available and throw an exception if none of these algorithms exists.

### Fixed
Themes can now be linked with a template folder again (see #4360).

### New
Content elements can now be used everywhere (no kidding).

### Changed
Adjusted the maintenance module to the new Live Update (coming soon).

### New
Added a button to the safe mode notice, which allows administrators to disable
the safe mode without having to open the back end settings.

### Fixed
Use the correct path to font-face style sheets (see #4475).

### New
Added a "requestTokenWhitelist" array to the Contao configuration which can be
used to exempt domains from the request token check (see #3164). Example:

```
$GLOBALS['TL_CONFIG']['requestTokenWhitelist'][] = 'facebook.com';
```

The code above can be added in the local configuration file.

### Changed
Make the return value of `Database\Result::fetchEach()` an associative array
with the ID as key and the requested field as value.

### Changed
Contao now uses `crypt()` to generate stronger password hashes (see #3225).

### Changed
Load the core modules before the extension modules.

### New
Added a separate field to enter the link title to the "hyperlink" and "download"
elements (see #4068).

### Fixed
Hide the `MAX_FILE_SIZE` form field if there is no upload field (see #4001).

### Changed
Moved the meta viewport tag to its own PHP variable so it can be replaced with
a custom version if necessary (see #4335).

### Changed
Image galleries are now rendered as unordered lists (see #4130). The Contao CSS
framework will format them respecting the "thumbnails per row" setting.

### Changed
The session and authentication cookies are now "http-only" (see #4185).

### Improved
It is now possible to choose multiple analytics templates (see #4328).

### Improved
Improved the "latest changes" overview on the back end welcome page and added
links to edit or restore the changed element (see #4336).

### New
Added a YouTube content element based on mediaplayer.js (see #4363).

### New
Added an additional routine to check boxes, radio buttons and select menus,
which compares the user input with the given options (see #4383).

### Fixed
Ignore DCA files which do not relate to a database table when building the DCA
extracts during installation (see #4316).

### Fixed
The Combiner now correctly supports all kind of relative paths (see #4161).

### New
Added a jQuery tablesort plugin (see #4393).

### Fixed
Adjusted the permission checks and reworked the "content elements everywhere"
structure (it is now entirely configured in the DCA).

### Fixed
Since the command scheduler now supports minutely jobs, the `cron.php` file has
to be adjusted accordingly (see #4425).

### New
Added a development .htaccess file (thanks to Wael M. Nasreddine) (see #4419).

### Improved
Do not force a password change in the back end if an administrator switches to
an account (see #3984). Thanks a lot to psi-4ward and aschempp for their work.

### Improved
If "addLanguageToUrl" is enabled and a request without the language fragment
ends up in a 404 error, automatically add the language of the corresponding root
page and redirect sending a 301 header (see #4028).

### Changed
Passwords can now contain special characters (see #4047).

### New
Background images up to a configurable size can optionally be embedded in style
sheets as data: string (see #3884).

### New
Added "rem" to the list of CSS units (see #4395).

### Fixed
Fixed a few remaining issues with the unique pagination variables (see #4141).

### Changed
The layout builder now uses the holy grail CSS code to generate the columns.
This moves the main column above the left and right column, which is good for
SEO purposes and for repsonsive designs. If it turns out that this is not
backwards compatible, it can be reverted.

### New
Made the Contao CSS framework configurable in the page layout module and added
an optional reset style sheet and a responsive 12-column grid. If none of the
components are selected, the CSS framework is entirely bypassed.

### Fixed
Add the request token to "save and new" requests (see #4329).

### Fixed
Make sure the size of a resized image is at least 1 pixel (see #4323).


Version 3.0.beta1 (2012-05-17)
------------------------------

### New
Content elements can now be shown/hidden at a certain time (see #4187).

### New
Added a video/audio player content element based on mediaelement.js.

### Changed
Pagination variables are now unique (see #4141).

### New
Added `Folder->size` to the `Folder` class (see #3903).

### Improved
Added better page titles in the back end (see #3980).

### Changed
Made the number of login attempts configurable (see #3923).

### Changed
Themes, style sheets, newsletter recipients, list entries and table enties to be
imported can now be directly uploaded.

### Changed
Do not instantiate the four default objects in `System::__construct()` anymore,
but rather lazy load them when required using `__get()`.

### Changed
Made the `Cache`, `Encryption`, `Environment`, `Input`, `RequestToken` and
`Search` classes static, again leaving the Singleton routines untouched for
for backwards compatibility.

### Changed
Made the `String` methods static and left the Singleton routines so the class
can still be used in object context (I actually wonder that PHP supports it, but
it does) (see #3898).

### New
Added the classes `Validator` and `Idna` to encapsulate validation and IDNA
domain name encoding in static methods.

### New
Added support for minutely cron jobs (see #3895).

### Changed
Moved the "getCountries" hook to the end of the `getCountries()` method so it
passes the sorted array instead of the raw one (see #3823).

### New
Added the new "Share on Google+" button to the article header.

### Changed
Merged the "rep_base" and "rep_client" modules into one "repository" module.

### Changed
Merged the "backend" and "frontend" modules into one "core" module and added
support for subfolders to structure the different types of classes: "classes",
"drivers", "elements", "forms", "models", "modules", "pages" and "widgets".

### New
Just to mention it: There is a new DCA option which allows you to add custom
buttons to the edit screen (next to "save", "save and close" etc.):

```
$GLOBALS['TL_DCA']['tl_page']['edit'] => array
(
  'buttons_callback' => array
  (
    array('tl_page', 'addAliasButton')
  )
);
```

You can see the example in the system/modules/core/dca/tl_page.php file.

### Improved
Much better "purge data" maintenance job, which can be extended and uses the
Automator to get things done, so every job can also be triggered via cron.

### Changed
New approach to handle mobile devices: instead of redirecting to another website
root page, you can now define a separate layout for mobile pages. This allows us
to re-use the existing site structure and content and to leave out (or include)
certain modules, scripts and style sheets if a visitor uses a mobile device.

### Improved
Better page layout edit screen: row icons, separate analytics templates, jQuery
and MooTools configuration in subpalettes.

### Changed
The color picker can now be activated in the data container (see #3874).

### Improved
Added an `addMultiple()` method to the Combiner (see #3814).

### Improved
Added `$arrFile` as fourth parameter of the "getCombinedFile"-hook (see #3945).

### Improved
Show the media query in the style sheet overview (see #4042).

### Improved
Prevent administrators from disabling their own account (see #4102).

### Changed
Removed the IE6 layout fix which added an non-breaking space to every activated
column (see #23).

### New
Also add the classes "float_above" and "float_below" to image containers (which
now can only have the classes "float_left" and "float_right") (see #4157).

### Improved
Do not generate links to previous and next months in the calendar if they do not
contain any events (see #4160).

### Improved
Added a better hint when choosing the position of a new element (tree view).

### New
Re-added the developer's module to the core, since it has to be adjusted upon
almost every update. Will move the task center to the ER in exchange.

### New
Added a diff view to examine the changes between two versions of a record.

### New
Added a log rotate job to the Automator.

### New
Added a meta wizard to manage file meta information with a GUI.

### Improved
Replaced the default browser tooltips with the MooTools tips interface.

### New
Added a magic method to the `Model` and `Model\Collection` classes, so you can
call `Model::findByName($name)` instead of `Model::findBy('name', $name)`. The
first method call will be rewritten to the second one.

### New
Added a `hash()` method to calculate the MD5 hash of files and folders.

### New
Added a custom drag&drop sorting order to image gallery elements.

### Changed
Moved the changelog file into a protected subdirectory (see #4049).

### Improved
Added a much better page picker which opens in a modal window and shows the site
structure instead of just a drop-down menu.

### Improved
Show a confirmation screen if an invalid URL has been detected instead of the
default error screen, so deep linking in the back end remains possible.

### New
Check the request token in the back end when `$_GET['act']` is set (see #4007).

### New
The TreeView is now searchable.

### New
Added support for folder-style URLs (see #3921). This is meant as a proof of
concept to see whether it works and is useful. We might have to add a feature to
the site structure to automatically generate folder-style aliases.

Using folder-style URLs requires one additional database query if the request
contains more than one parameter, therefore it can be disabled in the settings.

### New
Improved the `moo/j_analytics` templates and added `moo/j_piwik` templates in
case someone wants to use Piwik instead of Google Analytics.

### New
Improved the RSS feed handling of the calendar module accordingly.

### New
Improved the RSS feed handling in the news module (will do the same for the
calendar module). Feeds are no longer bound to a news archive and can include
multiple archives now.

### New
Added a nicer dialog script in the back end.

### New
Added `mootools-mobile.js` and swipe support to the mediabox and slimbox.

### New
You can now add external style sheets from the files directory to page layouts.
They are then treated like the internal style sheets, meaning they can be added
to the combiner by adding the `|static` flag to the file name.

### Changed
Rewrote all front end JavaScripts so they run in "no-conflict" mode, which means
you don't have to decide "MooTools or jQuery" anymore but can have them both.

### New
You can now choose jQuery instead of MooTools in the front end. Also, there is
a jQuery mediabox alternative called "colorbox" (template `j_colorbox`).

### Changed
Modified the plugins folder structure to prepare for jQuery support.

### Changed
Split the models into `Model` (single record) and `ModelCollection` (multiple
models) to have a "cleaner" implementaion (thanks to Andreas Schempp).

### Changed
Merged the "registration", "rss_reader" and "tpl_editor" module into the core
modules ("backend" and "frontend").

### New
All front end modules of the core modules now use Models.

### New
Added lazy and eager loading of related records to the Model class. Usage:

```
$objArticle = ArticleModel::findByPk(5);

// The author will be eager loaded (no additional DB query)
echo $objArticle->getRelated('author')->name; // Kevin Jones

// The parent page will be lazy loaded if it is requested
echo $objArticle->getRelated('pid')->title; // Music Academy
```

Relations are defined in the DCA files.

### Changed
All core modules are now using namespaces and can thus be overriden.

### Changed
Ported the news extension into its own namespace. Note that this is completely
optional and does not have to be done with your custom modules! I am just doing
it so the classes can be overriden by an extension.

### Changed
Use the TemplateLoader in the `getTemplate()` and `getTemplateGroup()` methods.

### Improved
Much nicer debug output.

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
