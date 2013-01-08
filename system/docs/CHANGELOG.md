Contao Open Source CMS Changelog
================================

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
it so the classes can be overriden by an extension

### Changed
Use the TemplateLoader in the `getTemplate()` and `getTemplateGroup()` methods.

### New
Added a merge script (`contao/merge.php`) which automatically prepares Contao 2
extensions for Contao 3 by creating the `config/autoload.php` file.

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
