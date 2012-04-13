Contao Open Source CMS Changelog
================================

Version 3.0.beta1 (XXXX-XX-XX)
------------------------------

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

### New
Added a "redirect if mobile device" setting to website root pages to forward
visitors using a mobile device to another website root page. We recommend to
use the sub-domains "www.domain.tld" and "m.domain.tld".

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

// The author will be eager loaded
echo $objArticle->author['name']; // Kevin Jones

// The parent page can be lazy loaded
$objArticle->getRelated('pid');
echo $objArticle->pid['title']; // Music Academy
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
