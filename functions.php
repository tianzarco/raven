<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'raven', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/lib/languages', 'raven' ) );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Raven Theme', 'raven' ) );
define( 'CHILD_THEME_URL', 'http://wpcanada.ca/our-themes/raven/' );
define( 'CHILD_THEME_VERSION', '1.1.1' );

//* Add HTML5 markup structure
add_theme_support( 'html5' );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue Javascript files
add_action( 'wp_enqueue_scripts', 'raven_enqueue_scripts' );
function raven_enqueue_scripts() {

	//* Responsive Menu script
	wp_enqueue_script( 'raven-responsive-menu', get_stylesheet_directory_uri() . '/lib/js/responsive-menu.js', array( 'jquery' ), '1.0.0', true );

	//* Backstretch script
	if ( ! get_background_image() )
		return;

	wp_enqueue_script( 'raven-pro-backstretch', get_bloginfo( 'stylesheet_directory' ) . '/lib/js/backstretch.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'raven-pro-backstretch-set', get_bloginfo('stylesheet_directory').'/lib/js/backstretch-set.js' , array( 'jquery', 'raven-pro-backstretch' ), '1.0.0' );

	wp_localize_script( 'raven-pro-backstretch-set', 'BackStretchImg', array( 'src' => str_replace( 'http:', '', get_background_image() ) ) );
}

//* Enqueue CSS files
add_action( 'wp_enqueue_scripts', 'raven_enqueue_styles' );
function raven_enqueue_styles() {

	wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family=PT+Serif:400,700|Open+Sans:400,700', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'raven-dashicons-style', get_stylesheet_uri(), array('dashicons'), '1.0' );
	wp_enqueue_style( 'raven-genericons-style', get_stylesheet_directory_uri() . '/lib/font/genericons.css' );
}

//* Add new image sizes
add_image_size( 'mini-square', 80, 80, TRUE );
add_image_size( 'square', 100, 100, TRUE );
add_image_size( 'large-square', 120, 120, TRUE );

//* Add support for custom background
add_theme_support( 'custom-background' );

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Reposition the primary navigation
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_before_header', 'genesis_do_nav' );

//* Add the welcome text section to front page
add_action( 'genesis_before_loop', 'raven_welcome_text' );
function raven_welcome_text() {

	if ( ! is_front_page() || get_query_var( 'paged' ) >= 2 )
		return;

	genesis_widget_area( 'welcome_text', array(
		'before' => '<div class="welcome-text widget-area">',
		'after'  => '</div>',
	) );

}

//* Add before entry widget area to single post page
add_action( 'genesis_before_entry', 'raven_before_entry' ); 
function raven_before_entry() {

	if ( ! is_singular( 'post' ) )
		return;

	genesis_widget_area( 'before-entry', array(
		'before' => '<div class="before-entry widget-area"><div class="wrap">',
		'after'  => '</div></div>',
	) );

}

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Add single post navigation
add_action( 'genesis_after_entry', 'genesis_prev_next_post_nav', 9 );

//* Customize the post info function
add_filter( 'genesis_post_info', 'raven_post_info_filter' );
function raven_post_info_filter( $post_info ) {

	$post_info = '[post_date] [post_author_link] [post_comments] [post_edit]';
	return $post_info;

}

//* Modify comment form
add_filter( 'comment_form_defaults', 'raven_comment_form_defaults' );
function raven_comment_form_defaults( $defaults ) {
 
	$defaults['title_reply'] = __( 'Join the Discussion!' );
	$defaults['comment_notes_before'] = '<p class="comment-box">' . __( 'Please submit your comment with a real name.' );
	$defaults['comment_notes_after'] = '<p class="comment-box">' . __( 'Thanks for your feedback!' );
	return $defaults;
 
}

//* Customize search form placeholder text
add_filter( 'genesis_search_text', 'raven_search_text' );
function raven_search_text( $text ) {
	return esc_attr( __( 'Search this website...' ) );
}

// Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

//* Theme activation message
add_action( 'admin_notices', 'raven_welcome' );
function raven_welcome() {
	global $pagenow;
	if ( is_admin() && isset($_GET['activated'] ) && $pagenow == "themes.php" ) {
		$settings = sprintf( __( '<strong>Well done!</strong> You have successfully activated Raven. You can configure your settings via the <a href="%s" title="Genesis Framework Settings">Genesis settings page</a>. If you have any questions be sure to read the theme documentation at <strong>http://docs.wpcanada.ca</strong>', 'raven' ) , admin_url( 'admin.php?page=genesis' ) );
		$output = printf( '<div class="updated"><p>%1$s</p></div>', $settings );
	}
}

//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'welcome_text',
	'name'        => __( 'Welcome Text', 'raven' ),
	'description' => __( 'This is the welcome text widget area.', 'raven' ),
) );
genesis_register_sidebar( array(
	'id'		=> 'before-entry',
	'name'		=> __( 'Before Entry', 'raven' ),
	'description'	=> __( 'This is the Before Entry widget area.', 'raven' ),
) );
