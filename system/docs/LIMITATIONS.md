Contao Open Source CMS known limitations
========================================

Models and database connections
-------------------------------

The model registry currently only supports the main database connection. The
Contao framework supports opening additional database connections, however, you
cannot make models use those.

More information: https://github.com/contao/core/pull/6248


Moving content elements as non-admin user
-----------------------------------------

Non-admin users cannot copy or move content elements between different parent
types, e.g. from an article to a news item or from a news item to an event. They
can only copy or move the elements from e.g. one article to another article.

More information: https://github.com/contao/core/issues/5234


Dependencies when updating extensions
-------------------------------------

The extension repository (ER2) does not handle dependencies correctly when an
extension is upgraded. It does upgrade dependencies which are already installed,
but fails to install new dependencies that have been added in the meantime.

More information: https://github.com/contao/core/issues/3804
