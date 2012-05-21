Contao Open Source CMS Changelog
================================

Version 3.0.beta2 (XXXX-XX-XX)
------------------------------

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
Added a magic method to the `Model` and `Model_Collection` classes, so you can
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
