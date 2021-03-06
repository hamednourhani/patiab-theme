<?php
/* Welcome to patiab :)
This is the core patiab file where most of the
main functions & features reside. If you have
any custom functions, it's best to put them
in the functions.php file.

Developed by: Eddie Machado
URL: http://themble.com/patiab/

  - head cleanup (remove rsd, uri links, junk css, ect)
  - enqueueing scripts & styles
  - theme support functions
  - custom menu output & fallbacks
  - related post function
  - page-navi function
  - removing <p> from around images
  - customizing the post excerpt
  - custom google+ integration
  - adding custom fields to user profiles

*/

/*********************
WP_HEAD GOODNESS
The default wordpress head is
a mess. Let's clean it up by
removing all the junk we don't
need.
*********************/

function patiab_head_cleanup() {
	// category feeds
	// remove_action( 'wp_head', 'feed_links_extra', 3 );
	// post and comment feeds
	// remove_action( 'wp_head', 'feed_links', 2 );
	// EditURI link
	remove_action( 'wp_head', 'rsd_link' );
	// windows live writer
	remove_action( 'wp_head', 'wlwmanifest_link' );
	// previous link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	// start link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	// links for adjacent posts
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	// WP version
	remove_action( 'wp_head', 'wp_generator' );
	// remove WP version from css
	add_filter( 'style_loader_src', 'patiab_remove_wp_ver_css_js', 9999 );
	// remove Wp version from scripts
	add_filter( 'script_loader_src', 'patiab_remove_wp_ver_css_js', 9999 );

} /* end patiab head cleanup */

//hide admin bar from front end
function my_function_admin_bar(){ 
	if(!is_admin()){
		return false; 
	}
}
add_filter( 'show_admin_bar' , 'my_function_admin_bar');
// A better title
// http://www.deluxeblogtips.com/2012/03/better-title-meta-tag.html
function rw_title( $title, $sep, $seplocation ) {
  global $page, $paged;

  // Don't affect in feeds.
  if ( is_feed() ) return $title;

  // Add the blog's name
  if ( 'right' == $seplocation ) {
    $title .= get_bloginfo( 'name' );
  } else {
    $title = get_bloginfo( 'name' ) . $title;
  }

  // Add the blog description for the home/front page.
  $site_description = get_bloginfo( 'description', 'display' );

  if ( $site_description && ( is_home() || is_front_page() ) ) {
    $title .= " {$sep} {$site_description}";
  }

  // Add a page number if necessary:
  if ( $paged >= 2 || $page >= 2 ) {
    $title .= " {$sep} " . sprintf( __( 'Page %s', 'dbt' ), max( $paged, $page ) );
  }

  return $title;

} // end better title

// remove WP version from RSS
function patiab_rss_version() { return ''; }

// remove WP version from scripts
function patiab_remove_wp_ver_css_js( $src ) {
	if ( strpos( $src, 'ver=' ) )
		$src = remove_query_arg( 'ver', $src );
	return $src;
}

// remove injected CSS for recent comments widget
function patiab_remove_wp_widget_recent_comments_style() {
	if ( has_filter( 'wp_head', 'wp_widget_recent_comments_style' ) ) {
		remove_filter( 'wp_head', 'wp_widget_recent_comments_style' );
	}
}

// remove injected CSS from recent comments widget
function patiab_remove_recent_comments_style() {
	global $wp_widget_factory;
	if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
		remove_action( 'wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style') );
	}
}

// remove injected CSS from gallery
function patiab_gallery_style($css) {
	return preg_replace( "!<style type='text/css'>(.*?)</style>!s", '', $css );
}


/*********************
SCRIPTS & ENQUEUEING
*********************/

// loading modernizr and jquery, and reply script
function patiab_scripts_and_styles() {

  global $wp_styles,$wp_scripts; // call global $wp_styles variable to add conditional wrapper around ie stylesheet the WordPress way

  if (!is_admin()) {

		// $please_wait = array(
		// 	'theme_dir' => get_template_directory_uri(),
			
		// );
		
		// modernizr (without media query polyfill)
		// wp_register_script( 'please-wait', get_stylesheet_directory_uri() . '/js/lib/please-wait.min.js', array(), '', false );
		// wp_register_script( 'please-wait-custom', get_stylesheet_directory_uri() . '/js/please-wait-custom.js', array('please-wait'), '', false );

		// /wp_localize_script( 'please-wait-custom', 'theme_info', $please_wait );



		wp_register_script( 'patiab-modernizr', get_stylesheet_directory_uri() . '/js/lib/modernizr.custom.min.js', array(), '2.5.3', false );

		// register main stylesheet
		
		wp_register_style( 'font-awesome', get_stylesheet_directory_uri() . '/css/font-awesome.min.css', array(), '', 'all' );
		wp_register_style( 'patiab-stylesheet', get_stylesheet_directory_uri() . '/css/style.css', array(), '', 'all' );
		wp_register_style( 'patiab-rtl-stylesheet', get_stylesheet_directory_uri() . '/css/rtl.css', array('patiab-stylesheet'), '', 'all' );
		wp_register_style( 'patiab-ie-only', get_stylesheet_directory_uri() . '/css/ie.css', array(), '' );

    // comment reply script for threaded comments
    if ( is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
		  wp_enqueue_script( 'comment-reply' );
    }

		//adding scripts file in the footer
		
		
		
		wp_register_script( 'scrolltofixed', get_stylesheet_directory_uri() . '/js/lib/jquery-scrolltofixed-min.js', array('jquery'), '', true );
		// wp_register_script( 'onscreen', get_stylesheet_directory_uri() . '/js/lib/jquery.onscreen.min.js', array('jquery'), '', true );
		wp_register_script( 'respond-js', get_stylesheet_directory_uri() . '/js/lib/respond.js', array(), '', false );
		wp_register_script( 'pie', get_stylesheet_directory_uri() . '/js/lib/PIE.js', array('jquery'), '', false );
		wp_register_script( 'flexie', get_stylesheet_directory_uri() . '/js/lib/flexie.js', array('jquery'), '', false );
		wp_register_script( 'selectivizr', get_stylesheet_directory_uri() . '/js/lib/selectivizr-min.js', array(), '', false );
		wp_register_script( 'cssfx', get_stylesheet_directory_uri() . '/js/lib/cssfx.js', array(), '', false );
		wp_register_script( 'patiab-js', get_stylesheet_directory_uri() . '/js/scripts.js', array('jquery','scrolltofixed'), '', true );
		
		wp_register_script( 'google-map', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCSatHC6AP22DyNRlWCyT8EZnEPo175cl4"', array(), '', true );
		wp_register_script( 'map-loader', get_stylesheet_directory_uri() . '/js/map-loader.js', array('google-map'), '', true );
		
		
		// enqueue styles and scripts
		//wp_enqueue_script( 'please-wait' );
		//wp_enqueue_script( 'please-wait-custom' );
		//wp_enqueue_script( 'patiab-modernizr' );
	
		wp_enqueue_script( 'modernizr-patiab' );


		wp_enqueue_style('font-awesome' );
		wp_enqueue_style( 'patiab-stylesheet' );
		if(is_rtl()){
			wp_enqueue_style('patiab-rtl-stylesheet');
		}
		wp_enqueue_style( 'patiab-ie-only' );

		$wp_styles->add_data( 'patiab-ie-only', 'conditional', 'lt IE 9' ); // add conditional wrapper around ie stylesheet
		
		

		/*
		I recommend using a plugin to call jQuery
		using the google cdn. That way it stays cached
		and your site will load faster.
		*/
		if(preg_match('/(?i)msie [5-8]/',$_SERVER['HTTP_USER_AGENT'])){
			// if IE<=8
			wp_enqueue_script( 'respond-js' );
			wp_enqueue_script( 'pie' );
			wp_enqueue_script( 'flexie' );
			wp_enqueue_script( 'selectivizr' );
			wp_enqueue_script( 'cssfx' );
		}
		if(is_page_template('page-templates/search-pharmacy.php')){
			wp_enqueue_script( 'google-map' );
			wp_enqueue_script( 'map-loader' );
		}
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'scrolltofixed' );

		// wp_enqueue_script( 'onscreen' );
		
		wp_enqueue_script( 'patiab-js' );
		

			
	}
}

/*********************
THEME SUPPORT
*********************/

// Adding WP 3+ Functions & Theme Support
function patiab_theme_support() {

	// wp thumbnails (sizes handled in functions.php)
	add_theme_support( 'post-thumbnails' );

	// default thumb size
	set_post_thumbnail_size(700,300 , true);
	
	// wp custom background (thx to @bransonwerner for update)
	add_theme_support( 'custom-background',
	    array(
	    'default-image' => '',    // background image default
	    'default-color' => '',    // background color default (dont add the #)
	    'wp-head-callback' => '_custom_background_cb',
	    'admin-head-callback' => '',
	    'admin-preview-callback' => ''
	    )
	);

	// rss thingy
	add_theme_support('automatic-feed-links');

	// to add header image support go here: http://themble.com/support/adding-header-background-image-support/

	// adding post format support
	add_theme_support( 'post-formats',
		array(
			'aside',             // title less blurb
			'gallery',           // gallery of images
			'link',              // quick link to other site
			'image',             // an image
			'quote',             // a quick quote
			'status',            // a Facebook like status update
			'video',             // video
			'audio',             // audio
			'chat'               // chat transcript
		)
	);

	// wp menus
	add_theme_support( 'menus' );

	// registering wp3+ menus
	register_nav_menus(
		array(
			'main-menu' => __( 'The Main Menu', 'patiab' ),   // main nav in header
			'side-menu' => __( 'The Side Menu', 'patiab' ),   // main nav in header
		)
	);

} /* end patiab theme support */


/*********************
RELATED POSTS FUNCTION
*********************/

// Related Posts Function (call using patiab_related_posts(); )
function patiab_related_posts() {
	echo '<ul id="patiab-related-posts">';
	global $post;
	$tags = wp_get_post_tags( $post->ID );
	if($tags) {
		foreach( $tags as $tag ) {
			$tag_arr .= $tag->slug . ',';
		}
		$args = array(
			'tag' => $tag_arr,
			'numberposts' => 5, /* you can change this to show more */
			'post__not_in' => array($post->ID)
		);
		$related_posts = get_posts( $args );
		if($related_posts) {
			foreach ( $related_posts as $post ) : setup_postdata( $post ); ?>
				<li class="related_post"><a class="entry-unrelated" href="<?php the_permalink() ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; }
		else { ?>
			<?php echo '<li class="no_related_post">' . __( 'No Related Posts Yet!', 'patiab' ) . '</li>'; ?>
		<?php }
	}
	wp_reset_postdata();
	echo '</ul>';
} /* end patiab related posts function */

/*********************
PAGE NAVI
*********************/

// Numeric Page Navi (built into the theme by default)
function patiab_page_navi() {
  global $wp_query;
  $bignum = 999999999;
  if ( $wp_query->max_num_pages <= 1 )
    return;
  echo '<nav class="pagination">';
  echo paginate_links( array(
    'base'         => str_replace( $bignum, '%#%', esc_url( get_pagenum_link($bignum) ) ),
    'format'       => '',
    'current'      => max( 1, get_query_var('paged') ),
    'total'        => $wp_query->max_num_pages,
    'prev_text'    => '&larr;',
    'next_text'    => '&rarr;',
    'type'         => 'list',
    'end_size'     => 3,
    'mid_size'     => 3
  ) );
  echo '</nav>';
} /* end page navi */

/*********************
RANDOM CLEANUP ITEMS
*********************/

// remove the p from around imgs (http://css-tricks.com/snippets/wordpress/remove-paragraph-tags-from-around-images/)
function patiab_filter_ptags_on_images($content){
	return preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
}

// This removes the annoying […] to a Read More link
function patiab_excerpt_more($more) {
	global $post;
	// edit here if you like
	return '<a class="excerpt-read-more" href="'. get_permalink( $post->ID ) . '" title="'. __( 'Read ', 'patiab' ) . esc_attr( get_the_title( $post->ID ) ).'">'. '[...]' .'</a>';
}

add_action('wp_head','patiab_inline_style' );
function patiab_inline_style(){
	
}

?>
