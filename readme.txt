=== Search Exclude ===
Contributors: quadlayers, pronskiy, williamdodson, stevelock
Donate link: https://quadlayers.com/
Tags: search exclude, search, wordpress search, exclude post, exclude page
Requires at least: 4.7
Requires PHP: 5.6
Tested up to: 6.8
Stable tag: 2.5.7
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Hide any post or page from the search results.

== Presentation ==

[QuadLayers](https://quadlayers.com/) | [Community](https://www.facebook.com/groups/quadlayers/)

== Description ==

With this plugin you can exclude any page, post or whatever from the WordPress search results by checking off the corresponding checkbox on post/page edit page.
Supports quick and bulk edit.

On the plugin settings page you can also see the list of all the items that are hidden from search.

== Installation ==

1. Upload `search-exclude` directory to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to any post/page edit page and check off the checkbox `Exclude from Search Results` if you don't want the post/page to be shown in the search results

== Frequently Asked Questions ==

= Does this plugin affect SEO? =

No, it does not affect crawling and indexing by search engines.
The ONLY thing it does is hiding selected post/pages from your site search page. Not altering SEO indexing.

If you want posts/pages to be hidden from search engines you may add the following snippet to your `functions.php`:
`
function add_meta_for_search_excluded()
{
    global $post;
    if (false !== array_search($post->ID, get_option('sep_exclude', array()))) {
        echo '<meta name="robots" content="noindex,nofollow" />', "\n";
    }
}
add_action('wp_head', 'add_meta_for_search_excluded');
`

Note: already indexed pages will remain indexed for quite a while. In order to remove them from Google index, you may use Google Search Console (or similar tool for other engines).

= Are there any hooks or actions available to customize plugin behaviour? =

Yes.
There is an action `searchexclude_hide_from_search`.
You can pass any post/page/custom_post ids as an array in the first parameter.
The second parameter specifies state of visibility in search. Pass true if you want to hide posts/pages,
or false - if you want show them in the search results.

Example:
Let's say you want "Exclude from Search Results" checkbox to be checked off by default
for newly created posts, but not pages. In this case you can add following code
to your theme's function.php:

`
add_filter('default_content', 'exclude_new_post_by_default', 10, 2);
function exclude_new_post_by_default($content, $post)
{
	if ('post' === $post->post_type) {
        do_action('searchexclude_hide_from_search', array($post->ID), true);
	}
}
`

Also there is a filter `searchexclude_filter_search`.
With this filter you can turn on/off search filtering dynamically.
Parameters:
$exclude - current search filtering state (specifies whether to filter search or not)
$query - current WP_Query object

By returning true or false you can turn search filtering respectively.

Example:
Let's say you need to disable search filtering if searching by specific post_type.
In this case you could add following code to you functions.php:
`
add_filter('searchexclude_filter_search', 'filterForProducts', 10, 2);
function filterForProducts($exclude, $query)
{
    return $exclude && 'product' !== $query->get('post_type');
}
`

== Screenshots ==

1. screenshot-1.png
2. screenshot-2.png

== Changelog ==

= 2.5.7 =
* fix: php 7.2 errors
= 2.5.6 =
* fix: update dependencies 

= 2.5.5 =
* fix: update dependencies 

= 2.5.4 =
- fix: improve get_terms to reduce term load time

= 2.5.3 =
* fix: user editor posts permissions

= 2.5.2 =
* fix: update dependencies 

= 2.5.1 =
* fix: update dependencies 

= 2.5.0 =
* fix: security issues

= 2.4.9 =
* fix: load plugin textdomain

= 2.4.8 =
* WordPress compatibility

= 2.4.7 =
* fix: terms exclusion 
* fix: Content Control plugin compatibility

= 2.4.6 =
* fix: update readme.txt

= 2.4.5 =
* fix: search exclude in ajax

= 2.4.4 =
* fix: load plugin textdomain

= 2.4.3 =
* fix: search exclude documentation url

= 2.4.2 =
* fix: update jetpack autoload

= 2.4.1 =
* fix: php errors

= 2.4.0 =
* fix: implement jetpack autoload

= 2.3.0 =
* fix: php errors
* fix: improve allowed screen logic
* fix: scripts in custom post types
* fix: advanced custom fields compatibility

= 2.2.0 =
* fix: php errors

= 2.1.9 =
* fix: php errors
* fix: compatibility with classic editor

= 2.1.8 =
* WordPress 6.7 compatibility

= 2.1.7 =
* fix: WordPress 6.1.3 compatibility
* fix: big queries 
* Remove notification 

= 2.1.6 =
* Refactor update 

= 2.1.5 =
* Refactor

= 2.1.4 =
* WordPress compatibility

= 2.1.3 =
* WordPress compatibility

= 2.1.2 =
* WordPress compatibility

= 2.1.1 =
* Packages update

= 2.1.0 =
* WordPress compatibility

= 2.0.9 =
* fix: PHP errors

= 2.0.8 =
* Translation strings

= 2.0.7 =
* WordPress compatibility

= 2.0.6 =
* WordPress compatibility

= 2.0.5 =
* Update portfolio link

= 2.0.4 =
* WordPress compatibility

= 2.0.3 =
* fix: strings translations

= 2.0.2 =
* fix: strings translations

= 2.0.1 =
* fix: strings translations

= 2.0.0 =
* i18n implemented
* Composer implemented
* Autoload implemented
* Rename files to fit WordPress Development rules
* Rename classes to fit WordPress Development rules
* Rename variables to fit WordPress Development rules

= 1.3.1 =
* Author update.

= 1.3.0 =
* fix: and rework bulk edit: The `Bulk actions` dropdown now offers hide/show actions.

= 1.2.7 =
* This is a security release. All users are encouraged to upgrade.
* fix: possible XSS vulnerability.

= 1.2.6 =
* fix: compatibility with WordPress 5.5

= 1.2.5 =
* Security release. More protection added.

= 1.2.4 =
* Security release. All users are encouraged to update.
* Added filter searchexclude_filter_permissions.

= 1.2.2 =
* Added action searchexclude_hide_from_search
* Added filter searchexclude_filter_search
* Fixed Bulk actions for Firefox

= 1.2.1 =
* Fixed bug when unable to save post on PHP <5.5 because of boolval() usage


= 1.2.0 =
* Added quick and bulk edit support
* Tested up to WP 4.1

= 1.1.0 =
* Tested up to WP 4.0
* Do not show Plugin on some service pages in Admin
* Fixed conflict with bbPress
* Fixed deprecation warning when DEBUG is on

= 1.0.6 =
* Fixed search filtering for AJAX requests

= 1.0.5 =
* Not excluding items from search results on admin interface

= 1.0.4 =
* Fixed links on settings page with list of excluded items
* Tested up to WP 3.9

= 1.0.3 =
* Added support for excluding attachments from search results
* Tested up to WP 3.8

= 1.0.2 =
* Fixed: Conflict with Yoast WordPress SEO plugin

= 1.0.1 =
* Fixed: PHP 5.2 compatibility

= 1.0 =
* Initial release
