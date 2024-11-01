<?php
/**
 * Plugin Name: WP Tiles Gallery
 * Plugin URI: http://URI_Of_Page_Describing_Plugin_and_Updates
 * Description: This is a very simple and Tiles design gallery plugin for WordPress.
 * Version: 1.0
 * Author: ASIF SAHO
 * Author URI: http://www.asifsaho.me
 * License: Really? give a credit/pingback and do whatever you want ;)
 */



/*
* Register Gallery Custom Post
*/

function tiles_gallery() {

	$labels = array(
		'name'                => _x( 'Tiles Gallery', 'Tiles Gallery General Name', 'text_domain' ),
		'singular_name'       => _x( 'Tiles Gallery', 'Tiles Gallery Singular Name', 'text_domain' ),
		'menu_name'           => __( 'Tiles Gallery', 'text_domain' ),
		'parent_item_colon'   => __( 'Parent Tiles:', 'text_domain' ),
		'all_items'           => __( 'All Tiles', 'text_domain' ),
		'view_item'           => __( 'View Tiles', 'text_domain' ),
		'add_new_item'        => __( 'Add New Tiles', 'text_domain' ),
		'add_new'             => __( 'Add New Tiles', 'text_domain' ),
		'edit_item'           => __( 'Edit Tiles', 'text_domain' ),
		'update_item'         => __( 'Update Tiles', 'text_domain' ),
		'search_items'        => __( '	Search Tiles', 'text_domain' ),
		'not_found'           => __( 'Not found', 'text_domain' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
		);
	$args = array(
		'label'               => __( 'Tiles Gallery', 'text_domain' ),
		'description'         => __( 'Tiles Gallery Description', 'text_domain' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'excerpt', 'thumbnail' ),
		'taxonomies'          => array( 'category'),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => true,
		'menu_position'       => 6,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		);
	register_post_type( 'TilesGallery', $args );

}
// Hook into the 'init' action
add_action( 'init', 'tiles_gallery', 1 );



//triggering requered CSS and JS

function tiles_scripts() {
	wp_enqueue_style('tiles_gallery_styles', plugin_dir_url(__FILE__).'tiles-gallery.css');
	wp_enqueue_script('prettyPhotoPopup', plugin_dir_url(__FILE__).'jquery.prettyPhoto.js', array(jquery));
}

add_action('wp_enqueue_scripts','tiles_scripts', 10, 1);


// shortcode register

// function bartag_func( $atts ) {
// 	extract( shortcode_atts( array(
// 		'foo' => 'something',
// 		'bar' => 'something else',
// 	), $atts ) );

// 	return "foo = {$foo}";
// }
// add_shortcode( 'bartag', 'bartag_func' );


// [tiles foo="foo-value"]
function tiles_gallery_shortcode( $atts ) {
	extract( shortcode_atts( array(
		'category' => '',
		), $atts ) );

	// wp query

		$args = array('post_type' => 'TilesGallery', 'category_name' => $category);

		$tiles_query = new WP_Query( $args ); ?>

		<?php if ( $tiles_query->have_posts() ) : 

		$tiles_featch_data =
		'<div id="tiles-gallery">
			<article>
				<div class="gallery gallery-col-4">';	

				while ( $tiles_query->have_posts() ) : $tiles_query->the_post();
				$tiles_featch_data .= '<div class="item"> <img src="'.wp_get_attachment_url( get_post_thumbnail_id($post->ID) ).'" alt=""><a class="tilesGalleryPop" href="'.wp_get_attachment_url( get_post_thumbnail_id($post->ID) ).'">
					<div class="overlay">
						<h4>'.get_the_title().'</h4>
						<p>'.get_the_excerpt().'</p>
					</div>
			</a>
		</div>';
		endwhile;
		$tiles_featch_data .= '</div>
	</article>
</div>';

wp_reset_postdata(); ?>

<?php else:  ?>
	<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
<?php endif;

//return shortcode data
return "{$tiles_featch_data}";
}

add_shortcode( 'tiles', 'tiles_gallery_shortcode' );


function prettyPhotoTrigger(){
	echo '<script>jQuery(document).ready(function(){
			jQuery(".tilesGalleryPop").prettyPhoto();
		})</script>';
	}

add_action('wp_footer', 'prettyPhotoTrigger');

// Example Shortcode
// [tiles category="one"]