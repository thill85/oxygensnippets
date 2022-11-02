<?php
/**
 * Setup for loading ajax content when external scripts are not being used.
 * Does not require 'wp_localize_script' or 'wp_enqueue_script'.
 * Example usage: Advanced Scripts or Code Snippets plugin.
 *
 * Sources
 * 1. https://help.codesnippets.pro/article/48-ajax-within-snippets
 * 2. https://shellcreeper.com/wp-ajax-for-beginners/
 * 3. https://codex.wordpress.org/AJAX_in_Plugins
 * 
 */

const WP_AJAX_ACTION = 'bxcr_load_content';



// Action hooks for ajax callback
// wp_ajax_ used for logged in users
// wp_ajax_nopriv_ for not logged in users
// https://developer.wordpress.org/reference/hooks/wp_ajax_action/

add_action('wp_ajax_' . WP_AJAX_ACTION, 'bxcr_load_post_info');
add_action('wp_ajax_nopriv_' . WP_AJAX_ACTION, 'bxcr_load_post_info');



function bxcr_load_post_info(){

    // https://developer.wordpress.org/reference/functions/check_ajax_referer/
    check_ajax_referer( WP_AJAX_ACTION );
   // wp_send_json_success( 'it works!' );
    
   
    
    // Load post data
    $this_post_id = intval($_POST['post_id']);
    
     
    $args = array(
        'posts_per_page' => 1,
        'post_type'      => get_post_type($this_post_id),
        'p'              => $this_post_id,
        'no_found_rows'  => true,
    );

    $loop = new WP_Query($args);
    
    $loop->the_post(); 

    //output content
    
    echo '<div class="modal-info">';
    the_title('<h3>', '</h3>');
    the_content();
    echo '<a href="#" class="btn-book">Book Now</a>';
    echo '</div>';
    // restores original post data.
	  wp_reset_postdata();

    // Required to end ajax request
    // Prevents echoing out `0` via the die function in admin-ajax.php, in addition to the above output.
    wp_die();
}

// Send AJAX request

if ( ! is_admin() && ! defined( 'SHOW_CT_BUILDER' )) {

add_action('wp_footer', 'bxcr_ajax_request');

function bxcr_ajax_request(){

    $media_dir = wp_upload_dir();
    $media_dir = $media_dir['baseurl'] . '/2022/10/lfh_loader.gif';

    $data = array(
        'ajaxurl'    => admin_url( 'admin-ajax.php' ),
        'ajaxloader' => $media_dir,
        'action'     => WP_AJAX_ACTION,
        'nonce'      => wp_create_nonce(WP_AJAX_ACTION),
    );
?>

<script>

    const bxcr_data = <?php echo wp_json_encode( $data ); ?>;
    
    //console.log('ajaxurl = ' + bxcr_data.ajaxurl);
    //console.log('ajaxloader = ' + bxcr_data.ajaxloader);
    //console.log('action = ' + bxcr_data.action);
    //console.log('nonce = ' + bxcr_data.nonce);

    // Open Modal window and load data
    jQuery(document).on('click', '.vitamin-block', function(e){

        e.preventDefault();
        var $this = jQuery(this);
        var modal = document.getElementById('modal-218-271').parentElement;
        var vitamin_id = $this.data('postid');
        oxyShowModal(modal);
        bxcr_ajax_load(vitamin_id);

    });

    // Close Modal window
    /*jQuery(document).on('click', '.close_btn', function(e){

        // Close Modal Window
        if( typeof oxyCloseModal !== 'undefined' ) {
            oxyCloseModal();
        }

    });*/

    function bxcr_ajax_load(vitamin_id){

        var content_container = jQuery('.vitamin-modal');

        jQuery.ajax({

            url:bxcr_data.ajaxurl,
            type:'post',
            data:{
                action: bxcr_data.action,
                _ajax_nonce: bxcr_data.nonce,
                post_id: vitamin_id,
                
            },

            // pre-reqeust callback function (loading animation) .
            beforeSend: function(){
                content_container.html('<img class="lfh-loader" src="' + bxcr_data.ajaxloader + '" />');
            },
            
            // function to be called if the request succeeds.
            // `response` is data returned from the server.
            // '.trigger('post-load') required for other scripts depending  on javascript interactions after content insertion
            success: function(response){
                content_container.html(response);
                jQuery(document.body).trigger('post-load');
            },
            
            fail: function(){
                content_container.hide().html('<p>Error Loading Content.</p>').fadeIn('fast');
            }

        });
    }
</script>




<?php }} ?>
