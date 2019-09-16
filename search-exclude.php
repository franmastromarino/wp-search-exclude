<?php
/*
Plugin Name: Search Exclude
Description: Hide any page or post from the WordPress search results by checking off the checkbox.
Version: 1.2.2
Author: Roman Pronskiy
Author URI: http://pronskiy.com
Plugin URI: http://wordpress.org/plugins/search-exclude/
*/

/*
Copyright (c) 2012-2015 Roman Pronskiy

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class SearchExclude
{
    protected $excluded;

    public function __construct()
    {
        $this->registerHooks();
    }

    public function registerHooks()
    {
        register_activation_hook( __FILE__, array($this, 'activate') );
        add_action('admin_init', array($this, 'saveOptions') );
        add_action('admin_menu', array($this, 'adminMenu'));
        add_action('post_updated', array($this, 'postSave'));
        add_action('edit_attachment', array($this, 'postSave'));
        add_action('add_meta_boxes', array($this, 'addMetabox') );
        add_filter('pre_get_posts', array($this, 'searchFilter'));

        add_filter('bbp_has_replies_query', array($this, 'flagBbPress'));

        add_filter('manage_posts_columns', array($this, 'addColumn'));
        add_filter('manage_pages_columns', array($this, 'addColumn'));
        add_action('manage_posts_custom_column', array($this, 'populateColumnValue'), 10, 2);
        add_action('manage_pages_custom_column', array($this, 'populateColumnValue'), 10, 2);
        add_action('bulk_edit_custom_box', array($this, 'addBulkEditCustomBox'));
        add_action('quick_edit_custom_box', array($this, 'addQuickEditCustomBox'));
        add_action('admin_print_scripts-edit.php', array($this, 'enqueueEditScripts'));
        add_action('wp_ajax_search_exclude_save_bulk_edit', array($this, 'saveBulkEdit'));
        add_action('admin_enqueue_scripts', array($this, 'addStyle'));

        /**
         * Hook can be used outside of the plugin.
         *
         * You can pass any post/page/custom_post ids in the array with first parameter.
         * The second parameter specifies states of visibility in search to be set.
         * Pass true if you want to hide posts/pages, or false - if you want show them in the search results.
         *
         * Example:
         * Let's say you want "Exclude from Search Results" checkbox to be checked off by default
         * for newly created posts, but not pages. In this case you can add following code
         * to your theme's function.php:
         *
         * <code>
         * add_filter('default_content', 'excludeNewPostByDefault', 10, 2);
         * function excludeNewPostByDefault($content, $post)
         * {
         *      if ('post' === $post->post_type) {
         *          do_action('searchexclude_hide_from_search', array($post->ID), true);
         *      }
         * }
         * </code>
         *
         * @param array $postIds array of post IDs
         * @param bool $hide
         */
        add_action('searchexclude_hide_from_search', array($this, 'savePostIdsToSearchExclude'), 10, 2);
    }

    /**
     * @param $postId int the ID of the post
     * @param $exclude bool indicates whether post should be excluded from the search results or not
     */
    protected function savePostIdToSearchExclude($postId, $exclude)
    {
        $this->savePostIdsToSearchExclude(array(intval($postId)), $exclude);
    }

    public function savePostIdsToSearchExclude($postIds, $exclude)
    {
        $exclude  = (bool) $exclude;
        $excluded = $this->getExcluded();

        if ($exclude) {
            $excluded = array_unique(array_merge($excluded, $postIds));
        } else {
            $excluded = array_diff($excluded, $postIds);
        }
        $this->saveExcluded($excluded);
    }

    /**
     * @param $excluded array IDs of posts to be saved for excluding from the search results
     */
    protected function saveExcluded($excluded)
    {
        update_option('sep_exclude', $excluded);
        $this->excluded = $excluded;
    }

    protected function getExcluded()
    {
        if (null === $this->excluded) {
            $this->excluded = get_option('sep_exclude');
            if (!is_array($this->excluded)) {
                $this->excluded = array();
            }
        }

        return $this->excluded;
    }

    protected function isExcluded($postId)
    {
        return false !== array_search($postId, $this->getExcluded());
    }

    protected function view($view, $params = array())
    {
        extract($params);
        include(dirname(__FILE__) . '/views/' . $view . '.php');
    }

    public function saveBulkEdit()
    {
        $postIds = !empty($_POST['post_ids']) ? $_POST[ 'post_ids' ] : false;
        $exclude = isset($_POST['sep_exclude']) && '' !== $_POST['sep_exclude']  ? $_POST[ 'sep_exclude' ] : null;
        if (is_array($postIds) && null !== $exclude) {
            $this->savePostIdsToSearchExclude($postIds, $exclude);
        }
    }

    public function enqueueEditScripts()
    {
        wp_enqueue_script(
            'search-exclude-admin-edit',
            plugin_dir_url( __FILE__ ) . 'js/search_exclude.js',
            array( 'jquery', 'inline-edit-post' ),
            '',
            true
        );
    }

    public function addStyle()
    {
        wp_register_style('search-exclude-stylesheet', plugins_url('/css/style.css', __FILE__ ));
        wp_enqueue_style('search-exclude-stylesheet');
    }

    public function addQuickEditCustomBox($columnName)
    {
        if ('search_exclude' == $columnName) {
            $this->view('quick_edit');
        }
    }

    public function addBulkEditCustomBox($columnName)
    {
        if ('search_exclude' == $columnName) {
            $this->view('bulk_edit');
        }
    }

    public function addColumn($columns)
    {
        $columns['search_exclude'] = 'Search Exclude';
        return $columns;
    }

    public function populateColumnValue($columnName, $postId)
    {
        if ('search_exclude' == $columnName) {
            $this->view('column_cell', array('exclude' => $this->isExcluded($postId), 'postId' => $postId));
        }
    }

    public function activate()
    {
        $excluded = $this->getExcluded();

        if (empty($excluded)) {
            $this->saveExcluded(array());
        }
    }

    public function addMetabox()
    {
        $currentScreen = get_current_screen();
        /* Do not show meta box on service pages */
        if (empty($currentScreen->post_type)) {
            return;
        }
        add_meta_box('sep_metabox_id', 'Search Exclude', array($this, 'metabox'), null, 'side');
    }

    public function metabox($post)
    {
        wp_nonce_field( 'sep_metabox_nonce', 'metabox_nonce' );
        $this->view('metabox', array('exclude' => $this->isExcluded($post->ID)));
    }

    public function adminMenu()
    {
        add_options_page(
            'Search Exclude',
            'Search Exclude',
            'manage_options',
            'search_exclude',
            array($this, 'options')
        );
    }

    public function searchFilter($query)
    {
        $exclude =
            (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX))
            && $query->is_search
            && !$this->isBbPress($query);

        $exclude = apply_filters('searchexclude_filter_search', $exclude, $query);

        if ($exclude) {
            $query->set('post__not_in', array_merge(array(), $this->getExcluded()));
        }

        return $query;
    }

    public function isBbPress($query)
    {
        return $query->get('___s2_is_bbp_has_replies');
    }

    /**
     * Flags a WP Query has being a `bbp_has_replies()` query.
     * @attaches-to ``add_filter('bbp_has_replies_query');``
     *
     * @param array $args Query arguments passed by the filter.
     *
     * @return array The array of ``$args``.
     *
     * @see Workaround for bbPress and the `s` key. See: <http://bit.ly/1obLpv4>
     */
    public function flagBbPress($args)
    {
        return array_merge($args, array('___s2_is_bbp_has_replies' => true));
    }

    public function postSave($postId)
    {
        if (!isset($_POST['sep'])) return $postId;

        $sep = $_POST['sep'];
        $exclude = (isset($sep['exclude'])) ? $sep['exclude'] : 0 ;

        $this->savePostIdToSearchExclude($postId, $exclude);

        return $postId;
    }

    public function options()
    {
        $excluded = $this->getExcluded();
        $query = new WP_Query( array(
            'post_type' => 'any',
            'post_status' => 'any',
            'post__in' => $excluded,
            'order'=>'ASC',
            'nopaging' => true,
        ));
        $this->view(
            'options',
            array(
                'excluded' => $excluded,
                'query' => $query,
            )
        );
    }

    public function saveOptions()
    {
        if (isset($_POST['search_exclude_submit'])) {

            $excluded = $_POST['sep_exclude'];
            $this->saveExcluded($excluded);
        }
    }
}
$pluginSearchExclude = new SearchExclude();
