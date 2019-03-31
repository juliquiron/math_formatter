Mathematical calculation formatter
=================================

This module provides a formatter for text fields evaluating its value as an arithmetical mathematical expression.

For example, the field value `10+2` will be rendered as `12`.

Features
--------

It supports:
 - both integer and float values.
 - basic arithmetic operators: `+`, `-`, `*` and `/`

The value can be rendered in multiple ways:
 - As a result
 - As a operation attaching its result on mouse over
 - With a React component that also uses the mouse over behavior

Install instructions
--------------------

Place the module into your modules folder.

Install the module dependencies:
 - [GraphQL](https://www.drupal.org/project/graphql) drupal module. Use composer to install it and its dependencies.

Browse to `/admin/modules` in your Drupal installation and enable the module.

For your content types, in the `Manage display` tab set the format to 'Calculated value'. It supports field types:
 - text (formatted) (not supported by the React component)
 - text (plain)
 - text (plain, long)
Note: remember to save after setting up the display options.

By default, without using the React component, the expression will be evaluated on page load and render only the result. You can set up to show the expression and only evaluate and show the result when the visitor interacts with it with placing the mouse on it. To configure it in the `Manage display` tab, in the configuration of each field display, select the option `Asynchronously`.

