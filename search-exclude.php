<?php
/*
Plugin Name: Search Exclude
Description: Exclude any page or post from search results by checking off the checkbox.
Version: 0.1
Author: Roman Pronskiy
Author URI: http://pronskiy.com
*/


/*
    Copyright Roman Pronskiy, 2012
    My plugins are created for WordPress, an open source software
    released under the GNU public license
    <http://www.gnu.org/licenses/gpl.html>. Therefore any part of
    my plugins which constitute a derivitive work of WordPress are also
    licensed under the GPL 3.0. My plugins are comprised of several
    different file types, including: php, cascading style sheets,
    javascript, as well as several image types including GIF, JPEG, and
    PNG. All PHP and JS files are released under the GPL 3.0 unless
    specified otherwise within the file itself. If specified as
    otherwise the files are licensed or dual licensed (as stated in
    the file) under the MIT <http://www.opensource.org/licenses/mit-license.php>,
    a compatible GPL license.
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
        add_action('save_post', array($this, 'postSave'));
        add_action('add_meta_boxes', array($this, 'addMetabox') );
        add_filter('pre_get_posts',array($this, 'searchFilter'));
    }

    function activate()
    {
        $excluded = get_option('sep_exclude');

        if (false === $excluded || !is_array($excluded)) {
            update_option('sep_exclude', array());
        }
    }

    public function addMetabox()
    {
        add_meta_box( 'sep_metabox_id', 'Search Exclude', array($this, 'metabox'), null);
    }

    public function metabox( $post )
    {
        $excluded = get_option('sep_exclude');
        $exclude = (false === array_search($post->ID, $excluded)) ? false : true;

        wp_nonce_field( 'sep_metabox_nonce', 'metabox_nonce' );
        ?>
        <div class="misc-pub-section">
            <label for="sep_exclude">
                <input type="hidden" name="sep[hidden]" id="sep_hidden" value="1" />
                <input type="checkbox" name="sep[exclude]" id="sep_exclude" value="1" <?php echo ($exclude) ? 'checked' : ''; ?> />
                Exclude from Search Results
            </label>
        </div>
        <?php
    }

    public function saveOptions()
    {
        if (isset($_POST['search_exclude_submit'])) {

            $excluded = $_POST['sep_exclude'];
            update_option('sep_exclude', $excluded);
        }
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
        if ($query->is_search) {
            $excluded = get_option('sep_exclude');
            $query->set('post__not_in', $excluded);
        }
        return $query;
    }

    public function postSave( $post_id )
    {
        if (!isset($_POST['sep'])) return $post_id;

        $sep = $_POST['sep'];
        $exclude = (isset($sep['exclude'])) ? $sep['exclude'] : 0 ;

        update_post_meta($post_id, 'sep_exclude', $exclude);



        if (false === $excluded) {
            $excluded = array();
        }

        $indSep = array_search($post_id, $excluded);
        if (false === $indSep) {
            $excluded[] = $post_id;
        }
        else {
            unset($excluded[$indSep]);;
        }


        update_option('sep_exclude', $excluded);
        return $post_id;
    }

    protected function savePostIdToSearchExclude($postId, $value)
    {
        $excluded = get_option('sep_exclude');

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

    }

    public function options()
    {
        ?>
    <div class="wrap">
        <?php screen_icon(); ?>
        <h2>Search Exclude</h2>
        <?php

        $excluded = get_option('sep_exclude');

        if (empty($excluded)) {
            ?>
            <p>No items excluded from search results yet.</p>
            <?php
        }
        else {
            $query = new WP_Query( array('post_type' => 'any', 'post__in' => $excluded, 'order'=>'ASC', 'nopaging' => true) );
            ?>

            <form method="post" action="options-general.php?page=search_exclude" enctype="multipart/form-data">
                <table cellspacing="0" class="wp-list-table widefat fixed pages">
                    <thead>
                    <tr>
                        <th style="" class="check-column" id="cb" scope="col"></th><th style="" class="column-title manage-column" id="title" scope="col"><span>Title</span></th><th style="" class="manage-column column-type" id="type" scope="col"><span>Type</span>
                    </tr>
                    </thead>

                    <tbody id="the-list">
                        <?php while ( $query->have_posts() ) : $query->the_post();?>
                    <tr valign="top" class="post-<?php the_ID()?> page type-page status-draft author-self" >
                        <th class="check-column" scope="row"><input type="checkbox" value="<?php the_ID()?>" name="sep_exclude[]" checked="checked"></th>
                        <td class="post-title page-title column-title"><strong><a title="Edit “<?php the_title()?>”" href="/wp-admin/post.php?post=<?php the_ID()?>&action=edit" class="row-title"><?php the_title()?></a></strong>
                        <td class="author column-author"><?php echo get_post_type();?></td>
                    </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <p class="submit"><input type="submit" name="search_exclude_submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
            </form>
            <?php
        }
        ?>
    </div>
    <?php
    }

}
$sep = new SearchExclude();

?>