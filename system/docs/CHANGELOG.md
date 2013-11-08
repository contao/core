Contao Open Source CMS Changelog
================================

Version 3.1.5 (2013-11-08)
--------------------------

### Fixed
Correctly handle shorthand byte values (see #6345).

### Fixed
Also update the sitemap if a news/event feed is updated (see #5727).

### Fixed
Correctly sort by date in the listing module (see #5609).

### Fixed
Correctly handle the autologin key if a member is duplicated (see #5945).

### Fixed
Correctly export pages as PDF (see #6317).


Version 3.1.4 (2013-10-14)
--------------------------

### Fixed
Do not show the debug bar in the modal dialog (see #6302).

### Fixed
Ignore the "maxlength" setting in certain form fields (see #6283).

### Fixed
Correctly show the "toggle page status" icon (see #6282).

### Removed
Removed the TinyMCE spell checker (see #6247).

### Updated
Updated TCPDF to version 3.0.38 (see #6268).

### Fixed
Correctly render the pages breadcrumb menu for non-admin users (see #6067).

### Fixed
Correctly handle the accordion fields during the version 3.1 update (see #6229).

### Fixed
Correctly handle special characters in page aliases (see #6232).


Version 3.1.3 (2013-09-24)
--------------------------

### Fixed
Consider the additional arguments in `Frontend::jumpToOrReload()` (see #5734).

### Fixed
Prevent article aliases from using reserved names (see #6066).

### Fixed
Correctly update the RSS feeds if a news item or event changes (see #6102).

### Fixed
Correctly link to news and calendar feeds via insert tag (see #6164).

### Fixed
Make the CSS ID available in the custom navigation module (see #6129).

### Fixed
Do not cache the "toggle_view" insert tag (see #6172).

### Fixed
Unset the primary key if a model is deleted (see #6162).

### Fixed
Support `tel:` and `sms:` upon IDNA conversion (see #6148).

### Fixed
Apply the width and height to the audio player as well (see #6114).

### Fixed
Do not exit after a template has been output (see #5570).

### Changed
Drop the database query cache (see #6070). This renders `executeUncached()` and
`executeCached()` deprecated. Use `execute()` instead.

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
