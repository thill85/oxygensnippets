<?php
/**
 * Change query for Custom Post Type Archive.
 * For use with FacetWP Filtering.
 *
 * @package Oxygen Snippets
 */
 
 function project_update_cpt_query( $query ) {
	
	if( $query->is_main_query() && !is_admin() && is_post_type_archive( 'cpt' ) ) {
	    $query->set( 'order', 'ASC' );
		  $query->set( 'posts_per_page', '-1' );
	}

}
add_action( 'pre_get_posts', 'project_update_cpt_query' );
