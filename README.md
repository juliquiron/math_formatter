Mathematical calculation formatter
=================================

This module provides a formatter for text fields evaluating its value as an arithmetical mathematical expression.

For example, the field value `10+2` will be rendered as `12`. It supports
 - both integer and float values.
 - basic arithmetic operators: `+`, `-`, `*` and `/`

Install instructions
--------------------

Place the module into your modules folder.

Browse to `/admin/modules` in your Drupal installation and enable the module.

For your content types, in the `Manage display` tab set the format to 'Calculated value'. It supports field types:
 - text (formatted)
 - text (plain)
 - text (plain, long)
Note: remember to save after setting up the display options.

By default the expression will be evaluated on page load and render only the result. You can set up to show the expression and only evaluate and show the result when the visitor interacts with it with placing the mouse on it. To configure it in the `Manage display` tab, in the configuration of each field display, select the option `Asynchronously`.

