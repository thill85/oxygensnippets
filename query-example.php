<?php 

/**
 * WP_Query Example
 * Using ACF Relationship field to display post that match current post ID.
 * Example Setup: Create a home CPT and community CPT.  Within the home CPT, add an ACF relationship field that pulls in the community posts.
 * Use this query in the Community Single Template to check for post ID's, in the relationship field, that match the current page.
 *
 *
 * @package Oxygen Snippets
 */


// Get the ID of the current page
$currentID = get_the_ID();

$args = array(
	
	'post_type' => 'lfh_home',
	'posts_per_page' => -1,
	'meta_query' => array(
		'relation' => 'AND',
			array(
				'key'	 => 'lfh_related_community',
				'value' => $currentID,
				'compare' => 'LIKE',
			),
			array(
				'key'	 => 'status',
				'value' => array('active', 'available'), 
				'compare' => 'IN',
			),
	
	),
	
	'post_status' => 'publish',

);

$homes1 = new WP_Query($args);


if($homes1->have_posts()){
	while($homes1->have_posts()){
		$homes1->the_post(); 
		
		the_title();
	}
} 

wp_reset_postdata();

?>
