Contao Open Source CMS changelog
================================

Version 3.4.0-beta1 (2014-10-XX)
--------------------------------

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
