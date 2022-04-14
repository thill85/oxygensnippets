add_image_size( 'image-480', 480, 9999 );
add_image_size( 'image-640', 640, 9999 );
add_image_size( 'image-720', 720, 9999 );
add_image_size( 'image-960', 960, 9999 );
add_image_size( 'image-1168', 1168, 9999 );
add_image_size( 'image-1440', 1440, 9999 );
add_image_size( 'image-1920', 1920, 9999 );

function my_custom_sizes( $sizes ) {
	return array_merge( $sizes, array(
		'image-480' => 'image-480',
		'image-640' => 'image-640',
		'image-720' => 'image-720',
		'image-960' => 'image-960',
		'image-1168' => 'image-1168',
		'image-1440' => 'image-1440',
		'image-1920' => 'image-1920',
	) );
}
add_filter( 'image_size_names_choose', 'my_custom_sizes' );
