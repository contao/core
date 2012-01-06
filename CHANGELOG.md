Contao Open Source CMS Changelog
================================

Version 2.11.0 (XXXX-XX-XX)
---------------------------

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
