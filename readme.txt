=== Search Exclude ===
Contributors: pronskiy, williamdodson, stevelock
Tags: admin, plugin, search
Requires at least: 3.3
Tested up to: 5.3
Stable tag: 1.2.5
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Hide any post or page from the search results.

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
The only thing it does is hiding selected post/pages from your site search page. Not altering SEO indexing.

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
add_filter('default_content', 'excludeNewPostByDefault', 10, 2);
function excludeNewPostByDefault($content, $post)
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
