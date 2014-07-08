<?php
/*
Plugin Name: Search Exclude
Description: Exclude any page or post from the WordPress search results by checking off the checkbox.
Version: 1.0.5
Author: Roman Pronskiy
Author URI: http://pronskiy.com
Plugin URI: http://wordpress.org/plugins/search-exclude/
*/

/*
Copyright (c) 2012 Roman Pronskiy

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
        add_filter('pre_get_posts',array($this, 'searchFilter'));
    }

    /**
     * @param $postId int the ID of the post
     * @param $value bool indicates whether post should be excluded from the search results or not
     */
    protected function savePostIdToSearchExclude($postId, $value)
    {
        $excluded = $this->getExcluded();

        $indSep = array_search($postId, $excluded);
        if ($value) {
            if (false === $indSep) {
                $excluded[] = $postId;
            }
        }
        else {
            if (false !== $indSep) {
                unset($excluded[$indSep]);
            }
        }
        $this->saveExcluded($excluded);
    }

    /**
     * @param $excluded array IDs of posts to be saved for excluding from the search results
     */
    protected function saveExcluded($excluded)
    {
        update_option('sep_exclude', $excluded);
    }

    protected function getExcluded()
    {
        $excluded = get_option('sep_exclude');

        if (!is_array($excluded)) {
            $excluded = array();
        }
        return $excluded;
    }

    function activate()
    {
        $excluded = $this->getExcluded();

        if (empty($excluded)) {
            $this->saveExcluded(array());
        }
    }

    public function addMetabox()
    {
        add_meta_box( 'sep_metabox_id', 'Search Exclude', array($this, 'metabox'), null);
    }

    public function metabox( $post )
    {
        $excluded = $this->getExcluded();
        $exclude = !(false === array_search($post->ID, $excluded));

        wp_nonce_field( 'sep_metabox_nonce', 'metabox_nonce' );
        include(dirname(__FILE__) . '/metabox.php');
    }

    public function adminMenu()
    {
        add_options_page(
            'Search Exclude',
            'Search Exclude',
            10,
            'search_exclude',
            array($this, 'options')
        );
    }

    public function searchFilter($query)
    {
        if ((!is_admin() || DOING_AJAX) && $query->is_search) {
            $query->set('post__not_in', array_merge($query->get('post__not_in'), $this->getExcluded()));
        }
        return $query;
    }

    public function postSave( $post_id )
    {
        if (!isset($_POST['sep'])) return $post_id;

        $sep = $_POST['sep'];
        $exclude = (isset($sep['exclude'])) ? $sep['exclude'] : 0 ;

        $this->savePostIdToSearchExclude($post_id, $exclude);

        return $post_id;
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
        include(dirname(__FILE__) . '/options.php');
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
