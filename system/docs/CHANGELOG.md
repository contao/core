Contao Open Source CMS changelog
================================

Version 3.4.0-beta1 (2014-10-XX)
--------------------------------

### Changed
Use `addImageToTemplate()` in the `ContentHyperlink` class (see #7296).

### Changed
Removed the H2 sub-headlines in the back end (see #7248).

### Improved
Only create one `DcaExtractor` instance per table (see #7324).

### Improved
Add a CSS class indicating the number of columns in a gallery (see #7138).

### Improved
Allow to switch between the page and file picker in TinyMCE (see #6974).

### Improved
Show a message if logging in is required to comment (see #7031).

### New
Added the "sendNewsletter" hook (see #7222).

### Improved
Make the pagination template more flexible (see #7174).

### Improved
Limit the selectable file types depending on the element type (see #7003).

### New
Prevent timing attacks when verifying passwords (see #7115, #5853).

### Changed
Hide the "start" and "stop" fields if an element is not published (see #7148).

### New
Support the `backlink` configuration setting in the parent view (see #7083).

### New
Added a regex to check for nonnegative natural numbers (see #4392). This also
includes the "minval" and "maxval" flags to specify a miminum or maximum value.

### Improved
Optionally hide files without matching meta data in downloads (see #6874).

### New
Preserve the original CSS ID and classes in the alias elements (see #6638).

### Improved
Do not directly query the `INFORMATION_SCHEMA` database (see #7302).

### New
Added the "doNoTrim" flag to the `Widget` class (see #4287).

### Improved
Support simple tokens in registration and lost password mails (see #7101).

### Changes
Consider the options array in `Model::countBy()` (see #7033).

### New
Support SVG and SVGZ images (see #7108, #5908).

### Changed
Move the mime types array to a configuration file (see #6843).

### New
Added the `sort` flag to the `eval` section of the DCA (see #4072).

### New
Added the "onundo_callback" (see #7258).

### Improved
Consider the values of referenced fields in the back end search (see #4376).

### New
Add an option to export style sheets (see #7049).

### New
Added `widget-*` CSS classes to front end form fields (see #7041).

### Improved
Make the loading order of the style sheets configurable (see #6937).

### Removed
Remove the `rel="author` support (see #7291).

### New
Added `$item['isTrail']` to the navigation menu templates (see #7096).

### Improved
Handle `data-` and `ng-` attributes in `Widget::addAttributes()` (see #7095).

### Changed
Add the class "tableless" to the `member_` templates (see #7207).

### Improved
Added the `|async` flag to `$GLOBALS['TL_JAVASCRIPT']` (see #7172).

### New
Added the "link_name" insert tag (see #7218).

### Improved
Simplify the "member_grouped" template (see #7015).

### Changed
Make the front controller classes overwritable.
