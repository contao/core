Contao Open Source CMS Changelog
================================

Version 2.11.11 (2013-04-03)
----------------------------

### Fixed
Pass the style attribute to empty image gallery table cells (see #5485).

### Fixed
Do not override the website path in the default config file (see #5339).


Version 2.11.10 (2013-03-21)
----------------------------

### Fixed
Cast varchar date fields to int when selecting from the database (see #5503).

### Fixed
Only unset POST variables if `Widget::submitInput()` returns `true` (see #5474).

### Fixed
Strictly compare values when determining whether to save or not (see #5471).

### Updated
Updated TinyMCE to version 3.5.8 (see #5329).

### Fixed
Correctly show the "invalid date and time" error message (see #5480).

### Fixed
Correctly split the words when adding to the search index (see #5363).

### Fixed
Correctly load TinyMCE in IE7 and IE8 (see #5346).

### Fixed
Send the correct cache headers in "client cache only" mode (see #5358).

### Fixed
Remove the session of deleted or disabled users (see #5353).

### Fixed
Correctly set the cookie paths (see #5339).


Version 2.11.9 (2013-02-05)
---------------------------

### Fixed
Support numeric front end dates in the form generator (see #5238).

### Fixed
Support whitespace characters when parsing simple tokens (see #5323).

### Fixed
Allow to run multiple TinyMCE instances with different configurations on the
same page (thanks to Andreas Schempp) (see #4453).

### Fixed
Correctly trigger the "saveNewPassword" hook (see #5247).

### Fixed
Consider the `save_callback` of the password field in `tl_user` when a back end
user is forced to change his password (see #5138).

### Fixed
Do not group standalone lightbox elements on HTML5 pages (see #3742).

### Fixed
Anonymize IP addresses in `Form::processFormData()` (see #5255).

### Fixed
Replaced the 1200 pixel limit when resizing images with the values defined in
the system settings (see #5268).

### Fixed
Make sure there is an array in `Controller::generateMargin()` (see #5217).

### Fixed
More robust input validation in the back end filter menu and no more absolute
paths in error messages printed to the screen (thanks to aulmn) (see #4971).

### Fixed
Unset non-existing fields when restoring versions (see #5219).


Version 2.11.8 (2013-01-07)
---------------------------

### Fixed
Make sure entered dates map to an existing date (see #5086).

### Fixed
Fixed the MySQLi field count (see #5182).

### Fixed
The Date class should return `00:00` for `Date(0)->time` (see #4249).

### Reverted
Handle dependencies when updating extensions (see #3804).

### Fixed
Fixed the unprefixed CSS gradient output (see #4569). 

### Fixed
Fixed a small formatting issue in the Music Academy theme (see #5160).

### Fixed
Show all extensions in the log when updating multiple at once (see #5144).

### Fixed
Standardize RSS feed aliases (see #5096).

### Fixed
Make the `FileUpload` constructor public (see #5054).

### Fixed
Use `isset()` in the `Database::fetch*()` methods (see #4990).

### Fixed
Changed the `System::getReadableSize()` algorithm to powers of two (see #4283).

### Fixed
Removed Tahiti and the Netherlands Antilles from the countries list (see #3791).

### Fixed
Also adjust the `be_navigation.html5` template to the new "getUserNavigation"
hook changes (see #3411).


Version 2.11.7 (2012-11-29)
---------------------------

### Fixed
Only execute runonce files after the DB tables have been created (see #5061).

### Fixed
Add an empty option in the TimePeriod widget if there are none (see #5067).

### Fixed
Handle auto_items in the `Frontend::addToUrl()` method (see #5037).

### Fixed
Do not use `specialchars()` in the "page" insert tag (see #4687).

### Fixed
Set the return path when sending e-mails (see #5004).

### Fixed
Handle border color names when importing style sheets (see #5034).

### Fixed
Prevent the "Illegal string offset" error in back end widgets (see #4979).

### Fixed
Handle dependencies when updating extensions (see #3804).

### Fixed
Switched all comments of the example website to "moderated" (see #4995).

### Fixed
Replaced the automatic copyright notice with a meta generator tag.

### Fixed
Remove HTML tags when overriding the page title (see #4955).

### Fixed
Decode entities in meta tags like "description" (see #4949).

### Fixed
Remove newsletter subscriptions when a member closes his account (see #4943).

### Fixed
Prevent deleting referenced content elements using "edit multiple" (see #4898).

### Updated
Updated SwiftMailer to version 4.2.1 (see #4935).

### Fixed
Set the file permissions depending on the server's umask setting (see #4941).

### Fixed
Correctly handle external image URLs in the image element (see #4923).

### Fixed
Fixed the too eager IP address anonymization (see #4924).

### Fixed
Fixed the automatic page alias generator (see #4880).


Version 2.11.6 (2012-09-26)
---------------------------

### Fixed
Correctly handle root pages in `Controller::getPageDetails()` (see #4610).

### Fixed
Consider the page language when forwarding (see #4841).

### Fixed
URL encode the enclosure URLs in RSS/Atom feeds (see #4839).

### Fixed
Also create empty templates folders if a theme is imported (see #4793).

### Fixed
Decode Punycode domains when used via insert tag (see #4753).

### Fixed
Correctly handle open tags in `String::substrHtml()` (see #4773).

### Fixed
Correctly handle units when importing style sheets (see #4721).

### Fixed
The mediabox plugin did not play Vimeo videos (see #4770).

### Fixed
Correctly align stylect menus in the form generator in the back end (see #4557).

### Fixed
Add a link if a news item or event points to an internal page (see #4671).

### Fixed
Wrap the MooTools fallback into CDATA tags on XHTML pages (see #4680).

### Fixed
Do not add a default value to textareas (see #4722).

### Fixed
Do not override the comments array in case login is required to comment,
otherwise no commets will be shown (see #4064).


Version 2.11.5 (2012-07-25)
---------------------------

### Fixed
Crop theme preview images so they are not being distorted (see #4361).

### Fixed
The IDNA convert class did (again) not run under PHP 5.2 (see #4044).

### Fixed
Fixed an issue with `getImage()` not working correctly when the `$target`
parameter was set (thanks to Tristan Lins) (see #4166).

### Updated
Updated TinyMCE to version 3.5.5 to finally fix the issue with links pointing to
the empty domain not being handled correctly (see #132).

### Changed
Directly go to the new Live Update client if the file exists.

### Fixed
Correctly check the permissions to manage undo steps (see #4535).

### Fixed
Fixed the issue with new pages being inserted into first-level pages having the
wrong default page type (see #4507).

### Fixed
Limit the "inputUnit" fields in the style sheet generator to 20 characters so
they are stored correctly in the database (see #4472).

### Fixed
Update the style sheets when changing the theme, in case the global style sheet
variables have changed (see #4471).

### Fixed
Added better border radius hints in the style sheet editor (see #4379).

### Fixed
Fixed the HTML5 "form action attribute must not be empty" issue (see #3997).

### Fixed
Fixed the SOAP compression issue in PHP 5.4 (see #4087).

### Fixed
Fixed the "division by zero" issue in the listing module (see #4485).

### Fixed
Do not hide the current page in the quick navigation (see #4523).

### Fixed
The "addEntry" hook does not intefere with the user object anymore (see #4414).

### Fixed
The function `Controller::generateImage()` did not urldecode (see #4384).

### Fixed
Check if there is a text field when auto-focussing (see #4422).

### Fixed
Set the correct headers to prevent browser caching (see #4436).

### Fixed
Min- and max-width/height now support `inherit` and `none` (see #4449).


Version 2.11.4 (2012-06-12)
---------------------------

### Fixed
Fixed a critical privilege escalation vulnerability which allowed regular users
to make themselves administrators (thanks to Fabian Mihailowitsch) (see #4427).

### Fixed
Support insert tags as external redirect target (see #4373).

### Updated
Updated the CSS3PIE plugin to version 1.0.0 (see #4378).

### Fixed
Re-applied the "autofocus the first field" patch (see #4297).

### Fixed
The pagination menu fix was missing in the listing, search and RSS reader
modules (see #4292).

### Fixed
Added the "required" attribute to the captcha input field (see #4247).

### Fixed
Correctly tell Google Analytics to anonymize the visitor's IP (see #4290). Heads
up: Adjust your `moo_analytics` templates accordingly!

### Fixed
Correctly align stylect menus in Safari and Opera (see #4284).


Version 2.11.3 (2012-05-04)
---------------------------

### Fixed
Always check all modules when looking for `runonce.php` files (see #4200).

### Fixed
Correctly insert the date picker in the DOM tree (see #4158).

### Fixed
Open popup windows so they are not blocked (see #4243).

### Fixed
Replaced `is_a()` with `instanceof` in the simplepie plugin (see #4212).

### Fixed
Use `round()` instead of `ceil()` when resizing images (see #3806).

### Fixed
Correctly handle empty FAQ categories in the front end modules (see #4084).

### Fixed
The comments module does no longer throw an error if there are no comments and
the number of comments per page is greater 0 (see #4064).

### Fixed
Correctly sort content element and module types in the help wizard (see #4156).

### Fixed
Add the admin e-mail address of a website root page to the page object so it can
be used in the form generator (see #4115).

### Fixed
Add a "protected" icon to subpages of a protected page (see #4123).

### Fixed
Allow "disabled" and "readonly" attributes in the back end (see #4131).

### Fixed
Add a log entry if a new version is created by toggling the visibility of an
element via Ajax (see #4163).

### Fixed
Re-added the version 2.9.2 update code in the install tool template (see #4165).

### Fixed
Correctly check the permission to edit tasks (see #4140).

### Fixed
Check the uploader class before instantiation (see #4086).

### Fixed
Convert the "rel" attribute inserted by TinyMCE to a "data-lightbox" attribute
if it is an HTML5 page (see #4073).

### Fixed
Uploaded files should now be resized correctly (see #3806).

### Fixed
Fixed the "setCookie" hook (see #4116).

### Fixed
Fixed the mediabox .mp4 file not found issue (see #4066).

### Fixed
The stylect menus in the module wizard are now duplicated correctly (see #4079).

### Fixed
Define `BE|FE_USER_LOGGED_IN` in the cron script (see #4099).

### Fixed
Correctly align the versions drop-down menu (see #4083).


Version 2.11.2 (2012-03-14)
---------------------------

### Fixed
Fixed an issue with the CSS3PIE url being incorrectly rewritten (see #4074).

### Fixed
Fixed a security vulnerability in the file manager which allowed back end users
to download files from the `tl_files` directory even if they were not mounted in
their profile (thanks to Marko Cupic).

### Fixed
Fixed a potential XSS vulnerability in the undo module (thanks to Oliver Klee).
The issue is not considered critical, because it requires the script tag to be
in the list of allowed HTML tags, which is not the case by default.

### Fixed
The IDNA convert class did not run under PHP 5.2 (see #4044).


Version 2.11.1 (2012-03-08)
---------------------------

### Fixed
Store the date added when creating an admin user upon installation (see #4054).

### Fixed
Purge the Zend Optimizer+ cache after writing the local configuration file.

### Fixed
The IDNA convert class did not run under PHP 5.2 (see #4044).

### Fixed
Inject error messages of checkbox and radio groups inside the fieldset, so they
can be associated with it (accessibility) and do not break the CSS formatting.
This change does not require any template adjustments (see #3392).

### Fixed
Correctly handle tabs and line breaks when importing CSV data (see #4025).

### Fixed
Event feeds did not show the date anymore (see #4026).

### Fixed
Preserve absolute URLs in style sheets in the Combiner (see #4002).

### Fixed
Support all kinds of keydown events in the stylect plugin, so options can be
selected by pressing the first key of their label (see #3812).

### Added
Added a separate version check for LTS releases.

### Fixed
Prevent the auto_item feature from generating duplicate content (see #4012).

### Fixed
Do not add the `language` parameter when forwarding to a page (see #4011).

### Fixed
The date picker in the back end did not work correctly due to MooTools failing
to parse dates correctly (see #3954).

### Fixed
The TinyMCE links popup failed under certain conditions (see #3995).

### Fixed
Correctly add the language to insert tag links (see #3983).

### Fixed
When creating an admin user in the install tool, the username was not validated
correctly (see #4006).

### Updated
Updated MooTools to version 1.4.5 which fixes a critical bug.

### Fixed
Relative URLs are now validated correctly (`'rgxp'=>'url'`) (see #3792). 

### Fixed
Adjust the submit button height in Opera (see #3940).

### Fixed
The front end preview drop-down menu did not use the stylect plugin.

### Fixed
Use the Facebook sharer instead a third-party app (see #3990).

### Fixed
Preserve IE conditionals like `[if (lt IE 9) & (!IEMobile)]` when replacing
ampersands in the front end (see #3985).

### Fixed
Set the maximum length of `inputUnit` fields to 200 (see #3987).

### Fixed
If an image with a title was added to a text element, the lightbox did not show
the title anymore (see #3986).

### Fixed
The hyperlink element did not output the link title anymore (see #3973).

### Fixed
Send a 404 header and do not index or cache a page if there is a pagination menu
and the `page` parameter is outside the range of existing pages. Now that list
and reader modules can be shown on the same page, it is likely that those pages
will be cached. This fix prevents the search index and temporary directory from
being flooded with non-existing resources (such as `?page=100000`).

### Fixed
Fixed the module wizard so you can use the stylect menu of a duplicated element
without having to reload the page (see #3970).

### New
Added the Slovenian translation of the TinyMCE "typolinks" plugin (thanks a lot
to Davor) (see #3952)

### Fixed
Fixed the "getContentElement", "getFrontendModule" and "getForm" hooks, so they
pass the generated content to the callback function (see #3962).

### Fixed
Correctly handle pages with the alias name "index" (see #3961).

### Fixed
Patched the MooTools core script to fix the accordion effect (see #3956).

### Fixed
The slimbox style sheets are now compatible with the combiner.


Version 2.11.0 (2012-02-15)
---------------------------

### Fixed
Also show todays events and running events in the RSS feed (see #3917).

### Fixed
Added "eot|woff|svg|ttf|htm" to the default .htaccess file (see #3930).

### Fixed
Fixed extracting the page alias when no URL suffix is used (see #3913).

### Fixed
Correctly calculate the width of the stylect select element in webkit.

### Updated
Updated MooTools to version 1.4.4 (see #3906).

### Fixed
Trigger the Slimbox with the data-lightbox attribute (see #3908).

### Fixed
Removed the HTML5 `article` and `section` tags as it turned out that semantics
cannot be generated automatically (see #3833).

### Updated
Updated MooTools to version 1.4.3 (see #3837).

### Fixed
Do not output (back end) system messages in the front end (see #3838).

### Fixed
Return the `renameTo()` status in the `Folder` class similar to how it is done
in the `File` class (see #3872).

### Fixed
Trigger the Stylect plugin after loading a subpalette (see #3850).

### Fixed
Correctly redirect to front end pages (see #3843).

### Fixed
Handle external image URLs when generating style sheets (see #3832).

### Fixed
Handle empty format definitions when generating style sheets (see #3830).

### Fixed
More accurate format definition validation (see #3824).

### Fixed
Correctly close the `rel="prev"` and `rel="next"` link tags (see #3821).

### Fixed
The stylect plugin does not break mutiple select menus anymore (see #3819).

### Fixed
Request static resources and Google web fonts via `https://` when the main page
is using an SSL connection (see #3802).

### Fixed
Images with spaces in the name are now displayed correctly (see #3817).

### Fixed
Do not load the empty URL from cache if the language is added and the empty
domain will be redirected.

### Fixed
Fixed an issue in the `Database_Statement::debugQuery()` method.

### Fixed
Correctly redirect when using an include content element (see #3766).

### Fixed
The stylect plugin did not work in IE < 9 (see #3628).


Version 2.11.RC2 (2012-01-12)
-----------------------------

### New
Added the event list formats "all upcoming of the current month/year" and "all
past of the current month/year" (thanks to Dominik Zogg) (see #3801).

### New
Added the "getRootPageFromUrl" hook.

### Fixed
Encrypt the default value of an encrypted field when creating new records or
duplicating existing records (see #3740).

### New
Added the "getCookie" hook (see #3233).

### New
Added the `copyTo()` method to the `File` and `Folder` class (see #3800).

### Fixed
Do not generate news or calendar feeds if there is no target page (see #3786).

### Fixed
You can now use a textual date format in the front end without breaking the
"registration" and "personal data" modules, which will fall back to the numeric
back end date.

### Fixed
Fixed the case-insensitive search in the back end (see #3789).

### Fixed
Support data: URIs in the style sheet generator (see #3661).

### New
Added a "header_callback" to the parent view to format the header fields of the
parent table (see #3417).

### New
Added an option to anonymize IP addresses which are stored in the database and
IP addresses which are sent to Google Analytics (see #3406 and #2052). This does
not include the `tl_session` table though, because IP addresses are bound to the
session for security reasons.

### Fixed
Force line-breaks in the filter menu so the filters do not exceed the column
width (see #3777).

### New
Added an "isAssociative" flag to the "eval" section of the DCA to mark numeric
arrays as associative (see #3185).

### Fixed
The Email class now handles files with special characters (see #3713).

### Fixed
Correctly URL-encode image URLs (see #3751).

### Improved
Added a fallback which loads the local MooTool core script if the Google CDN is
not available, e.g. if you are not connected to the Internet (see #3619)

### Fixed
Re-added a color picker to the style sheets module (see #3228).

### Fixed
Do not import commented definitions when importing style sheets (see #3478).

### Fixed
Correctly idna-encode domain names (see #3649).

### Added
Added a `chmod()` method to the `File` and `Folder` class (see #3641).

### New
Added the Russian and Ukrainian translations for the TinyMCE "typolinks" plugin
(thanks to DyaGa) (see #3648)

### Improved
Support the CSS "ex" unit (see #3652).

### Fixed
Correctly set the CSS ID and class of articles when just their teaser is shown
(see #3656). Note that the teaser element has its own CSS ID/class field.

### Fixed
Correctly set the classes "first" and "last" in the RSS reader (see #3687).

### Improved
Mark past and upcoming events with a special CSS class (see #3692).

### Fixed
Restore basic entities before auto-generating an alias (see #3767).

### Improved
Remove a page from the search index if it does not exist anymore (see #3761).

### Fixed
Select menus using the "chosen" plugin were not displayed when they were in a
collapsed palette (see #3627).

### Improved
When using Contao via SSL, automatically switch to an SSL connection when
running the Live Update (see #3538).

### New
Added the "sqlCompileCommands", "sqlGetFromFile" and "sqlGetFromDB" hooks to the
`DbInstaller` class (see #3281).

### Improved
Added the CSS class "trail" to the back end navigation (see #3301).

### Changed
The static URL is now prepended automatically and does not need to be added in
your custom script (see #3469). The change is backwards compatible, so you do
not need to change your existing modules.

### Improved
Added an option to include dynamically added style sheets in the combined CSS
file (see #3161). This is done by passing a second "argument" to the URL:

  `$GLOBAL['TL_CSS'][] = 'style.css|screen|static';`

Important: If you add a style sheet to the combined file, do not add a static
URL like `TL_PLUGINS_URL` to the path!

### Fixed
Provide an ID for single lightbox images in HTML5 (see #3742).

### Fixed
The front controller now works with the `index.php` fragment again (see #3689).

### Fixed
Do not override the global settings upon every call of `getPageDetails()`.

### Improved
In a FAQ list module you can now optionally add an FAQ reader module to
automatically switch to the full article if an item has been selected. This
allows us to use the FAQ list and FAQ reader on the same page.

### Fixed
Do not remove the combined style sheets when updating the CSS files (see #3605).
It might break the layout of cached pages.

### Fixed
Consider the sitemap setting when finding searchable pages (see #3728).

### Improved
Improved the chosen implementation so it is optional in the front end and can
be added using the "moo_chosen" template. Also, the styled select scripts have
been moved to a plugin ("stylect") and can be loaded in the front end now.

### Fixed
Correctly handle the "add language to URL" feature in sitemaps and feeds.

### New
You can now optionally skip the `items/` and `events/` fragment in the URL and
make Contao discover the item automatically. If the number of fragments is even,
the Controller will add the second fragment as `$_GET['auto_item']`, which you
can then use in your reader modules to set the item.


Version 2.11.RC1 (2011-12-30)
-----------------------------

### New
The back end file uploader can now be replaced with a custom one (see #3236).
Also, the uploader now tries to use the HTML5 "multiple" attribute in the file
input field if the browser supports it. 

### Improved
Added edit buttons to the module wizard to directly jump to a front end module
from a page layout (see #2847). Also, a link to the style sheets of a theme has
been added to the style sheets section of the page layout. This was done using
the new "xlabel" callback which works like the "wizard" callback but is added to
the label instead of to the input field.

### New
Force a back end user to change his password upon the next login (see #2928).

### Changed
Make the user agent and OS list in the `Environment` class editable (see #3410).

### Updated
Updated all plugins to their latest versions.

### Improved
Optionally limit the total number of images in a gallery (see #2652).

### Improved
The file picker now accepts a second argument to filter by file type. Separate
multiple file names with comma, e.g. _gif,jpg,png_ (see #2618).

### Updated
Updated TinyMCE to version 3.4.7 (see #3601).

### New
Added advanced image crop modes to define more precisely which part of an image
shall be preserved when cropping it (see #2422). 

### New
Support entering the image title in addition to the alternate text (see #3494).

### Improved
Restore deleted records even if the database schema has changed (see #3550).

### Changed
Removed the general IE6 warning and added it only to the install tool and the
back end login screen (see #3646).

### Improved
If an image link target is another image and the fullscreen view is enabled,
do not open the target image in a new window but in the lightbox (see #1703).

### New
Added the methods `protect()` and `unprotect()` to the Folder class (see #2978).

### New
Added the "storeFormData" hook which is triggered before the form data is
written into the database (see #3182).

### New
Added the insert tags "email_open" and "email_url" to ouput the opening link
tag or the encoded e-mail address only (see #3514).

### Improved
Do not show the number of comments if a news article forwards to another page
or external URL (see #3505).

### Improved
Added the breadcrumb folder navigation to the template editor (see #3684).

### New
Added support for Google web fonts to the page layout. The fonts are added to
the page by including a style sheet from the Google APIs.

### New
Added a "showColumns" option to the list view which makes the core engine
output the records as table rows (see the member list).

### Removed
Removed the "parse style sheet" option from the newsletter reader, since the
functionality has been removed from the newsletter module already.

### Improved
Move the chosen initialization from the DataContainer class to the select
widget, so it also works in the front end.

### Improved
Replaced all `if (count() < 1)` with `if (empty())` and all `if (count() > 0)`
with `if (!empty())`.

### Improved
Automatically add `rel="prev"` and `rel="next"` links to the page header if
there is a pagination module (see #3515).

### Improved
Pass the page data to the breadcrumb template as `data` (see #3607).

### New
Added the "getCacheKey" hook (see #3288).

### New
Added the "getFrontendModule" hook (#3244), the "getCountries" hook (#2819) and
the "getForm" hook.

### New
In an event list module you can now optionally add an event reader module to
automatically switch to the full article if an event has been selected. This
allows us to use the event list and event reader on the same page.


Version 2.11.beta1 (2011-11-10)
-------------------------------

### Changed
The Contao framework style sheet (`system/contao.css`) is now included in the
combined CSS file and can be disabled in the page layout settings (see #3609).

### Improved
Added FAQ permissions in the back end (see #2682) as well as a new module which
generates all questions and answers on a single page.

### Improved
In a news archive module you can now optionally add a news reader module to
automatically switch to the full article if an item has been selected. This
allows us to use the news archive and news reader on the same page.

### Changed
The page language is now determined by the root page language (see #3580).
This has a lot of advantages and a small catch: you will not be able to create
websites without a root page anymore - which was not recommended anyway.

### Improved
Added a nice cross-browser select menu style (progressive enhancement).

### New
Added a "chosen" widget to the templates editor (see #3587).

### Updated
Updated CodeMirror to version 2.16 (see #3475).

### Improved
Throw a 404 error if a request is mapped to Contao but the URL suffix does not
match (see #2864). This is e.g. the case if Contao is set as `ErrorDocument` in
the Apache configuration.

### Improved
Replaced the `rel="lightbox"` attribute with `data-lightbox=""` in all HTML5
templates, because `rel="lightbox"` is not a valid attribute actually.

### Improved
Also embed background images in newsletters and e-mails (see #2659).

### Changed
The local configuration files are now generated by the install tool and not
included in the core configuration anymore. Upon a fresh installation, the user
is now automatically redirected to the install tool (see #3586).

### Changed
Removed the page properties from the "env" insert tag and added a "page" insert
tag instead.

### Changed
Custom TinyMCE configuration files do not have to start with "tiny" anymore
(see #3582).

### Changed
Pass the content elements as array to the `mod_article` template, so custom
output formats can access the elements separately (see #3502).

### New
Added the "getArticle" hook (see #2332).

### Improved
Added a cache for `getPageDetails()` and outsourced the cache functionality into
a separate library (see #3577).

### New
Global style sheet variables can now also be defined in the theme settings
(see #3366). Style sheet variables override theme variables.

### New
Added support for the "placeholder" and "required" attributes in HTML5 forms.
Textareas in HTML5 now support the "maxlength" attribute. Will also add the
"autofocus" attribute in one of the next commits.

### Improved
Added support for the "tabindex", "placeholder" and "size" attribute to the form
generator (see #1505, #3241 and #2394).

### New
Added a new regexp type to the Widget validator to validate a comma separated
list of e-mail addresses (see #2899).

### Improved
Added a rudimentary abstraction layer for messages stored in the session.

### New
Added a hook to add system messages to the back end welcome screen (see #3162).

### Changed
Moved the task center to a separate module (might be moved to the ER later).

### New
According to Google's recommendations for multilingual websites, you can now
optionally add the language string as first URL parameter in addition to
working with different domains (see http://bit.ly/oZfzYK). If you activate the
feature in the back end settings, Contao will generate URLs like

  http://domain.tld/en/about.html
  http://domain.tld/de/about.html

In this context, the default `.htaccess` file has been improved and should now
execute faster and produce less overhead.

### Updated
Updated CodeMirror to version 2.15 (see #3475) and added a fullscreen mode
which you can toggle with F11 (see #3376).

### Updated
Updated SimplePie to version 1.2.1 (see #3482).

### Updated
Updated the IDNA plugin to version 0.8.0 (see #3481).

### Updated
Added MooTools 1.4.1 (see #3456).

### Improved
Replaced `is_null()` with `=== null` (see #3533).

### Improved
Mark disabled format definitions in the style sheet editor (see #3368).

### Removed
Finally removed the old one-column back end form layout (see #3534).

### Changed
Added the classes `col_first` and `col_last` to the labels of the calendar
and mini-calendar (see #1718).

### Improved
Assign the image width to the caption element so long captions do not break
the layout (see #3517).

### Improved
Removed unnecessary whitespace from inline tags.

### Changed
Changed the input field type in the `mod_search_*.html5` templates from "text"
to "search" (see #3380). If you want Webkit browsers to render the field like
a normal text field, use `input[type="search"] { -webkit-appearance:none; }`.

### Changed
Adjusted the back end JavaScript to the changes of the previous commit. The
`Request.Contao` class now handles both plain text and JSON responses for
reasons of backwards compatibility with version 2.10.

### Changed
Changed the request token system from "one token per request" to "one token
per session" (see #3214). This change will allow to remove most of the
JavaScript routines to update the request token upon Ajax request (they have
not yet been removed though).

### Fixed
Redirect to the login screen upon Ajax requests when the session has expired
(see #3516). This fix required some changes to the `Request.Contao` JavaScript
class insofar as you have to pass `evalScripts:true` wherever you want the
redirect mechanism to apply. Also, the `Request.Mixed` class has been replaced
with the `Request.Contao` class and is not required anymore (it will remain for
reasons of backwards compatibility though).

### Improved
Do not link to `?page=1` in the pagination menu but omit the parameter entirely,
so there is no duplicate content being generated (see #3518).

### Changed
In the global buttons callback, pass the "class" value instead of the "icon"
value as fourth argument, since there is no icon setting (see #3504).

### Changed
Do not copy the date and time of duplicated news and events (see #3463).

### Changed
Modify the `getInstance()` method of the `Singleton` classes so it uses
`new self()` instead of e.g. `new Config()` (see #2969).

### Improved
Only show the menu to rebuild the search index in the maintenance module if the
search has not been disabled in the back end settings (see #3425).

### Updated
Updated CSS3PIE to version 1.0beta5 (see #3454).

### Fixed
Make all e-mail fields in the database the same size (see #3458).

### Improved
Make the Database classes independent from the `DB_DRIVER` constant (see #3452)
and use factory methods in the driver classes instead.

### Improved
Prevent setting the upload path to one of the Contao core folders (see #3418)
so the installation cannot be modified with the file manager.

### New
Added a safe operation mode in which only core modules are loaded. It can be
used to debug an installation or to prevent possible incompatibilities after
an update. It is configurable in the back end.

### Improved
Disable all caches if the debug mode is enabled (see #3285). This includes
HTML and CSS minification, the front end page cache and the system cache for
`getTemplate()`, `getImage()` and `__autoload()`.

### Improved
Make `display_errors` and `error_reporting` configurable independently, so error
messages are logged even when they are not displayed (see #3338).
