<?php
/**
 * Neubau functions and definitions
 *
 * @package Neubau
 * @since Neubau 1.0
 */

 /*-----------------------------------------------------------------------------------*/
/* Sets up the content width value based on the theme's design.
/*-----------------------------------------------------------------------------------*/

if ( ! isset( $content_width ) )
		$content_width = 700;

function neubau_adjust_content_width() {
		global $content_width;

		if ( is_page_template( 'full-width.php' ) )
				$content_width = 2010;
}
add_action( 'template_redirect', 'neubau_adjust_content_width' );


/*-----------------------------------------------------------------------------------*/
/* Sets up theme defaults and registers support for various WordPress features.
/*-----------------------------------------------------------------------------------*/

function neubau_setup() {

	// Make Neubau available for translation. Translations can be added to the /languages/ directory.
	load_theme_textdomain( 'neubau', get_template_directory() . '/languages' );

	// Remove support form block widget screens.
	remove_theme_support( 'widgets-block-editor' );

	// Add support for Block Styles.
	add_theme_support( 'wp-block-styles' );

	// Add support for wider content.
	add_theme_support( 'align-wide' );

	// Add support responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Add support for editor font sizes.
	add_theme_support( 'editor-font-sizes', array(
		array(
			'name' => __( 'small', 'neubau' ),
			'shortName' => __( 'S', 'neubau' ),
			'size' => 18,
			'slug' => 'small'
		),
		array(
			'name' => __( 'regular', 'neubau' ),
			'shortName' => __( 'M', 'neubau' ),
			'size' => 24,
			'slug' => 'regular'
		),
		array(
			'name' => __( 'large', 'neubau' ),
			'shortName' => __( 'L', 'neubau' ),
			'size' => 30,
			'slug' => 'large'
		),
		array(
			'name' => __( 'larger', 'neubau' ),
			'shortName' => __( 'XL', 'neubau' ),
			'size' => 35,
			'slug' => 'larger'
		)
	) );

	// Disable custom editor font sizes.
	add_theme_support('disable-custom-font-sizes');

	// Add editor color palette.
	add_theme_support( 'editor-color-palette', array(
		array(
			'name' => __( 'black', 'neubau' ),
			'slug' => 'black',
			'color' => '#000000',
		),
		array(
			'name' => __( 'white', 'neubau' ),
			'slug' => 'white',
			'color' => '#ffffff',
		),
		array(
			'name' => __( 'light grey', 'neubau' ),
			'slug' => 'light-grey',
			'color' => '#ececed',
		),
		array(
			'name' => __( 'grey', 'neubau' ),
			'slug' => 'grey',
			'color' => '#a9a9a9',
		),
		array(
			'name' => __( 'yellow', 'neubau' ),
			'slug' => 'yellow',
			'color' => '#e7b547',
		),
		array(
			'name' => __( 'red', 'neubau' ),
			'slug' => 'red',
			'color' => '#d7464d',
		),
		array(
			'name' => __( 'green', 'neubau' ),
			'slug' => 'green',
			'color' => '#85c066',
		),
		array(
			'name' => __( 'blue', 'neubau' ),
			'slug' => 'blue',
			'color' => '#0066ff',
		),
	) );

	// This theme styles the visual editor to resemble the theme style.
	add_editor_style( array( 'css/editor-style.css', neubau_fonts_url() ) );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	// This theme uses wp_nav_menu().
	register_nav_menus( array (
		'primary' => __( 'Primary menu', 'neubau' ),
	) );

	// Implement the Custom Header feature
	require get_template_directory() . '/inc/custom-header.php';

	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'neubau_custom_background_args', array(
		'default-color'	=> 'fff',
		'default-image'	=> '',
	) ) );

	// This theme uses post thumbnails.
	add_theme_support( 'post-thumbnails' );

	//  Adding several sizes for Post Thumbnails
	add_image_size( 'neubau-small', 580, 9999, false  );
	add_image_size( 'neubau-medium', 800, 9999, false );

}
add_action( 'after_setup_theme', 'neubau_setup' );


/*-----------------------------------------------------------------------------------*/
/* Register Google fonts for Neubau.
/*-----------------------------------------------------------------------------------*/

if ( ! function_exists( 'neubau_fonts_url' ) ) :

function neubau_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Work Sans, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Work Sans font: on or off', 'neubau' ) ) {
		$fonts[] = 'Work Sans:300,400,600,800';
	}

	/* translators: If there are characters in your language that are not supported by Amiri, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Amiri font: on or off', 'neubau' ) ) {
		$fonts[] = 'Amiri:400,400italic,700,700italic';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;


/*-----------------------------------------------------------------------------------*/
/*  Enqueue scripts and styles
/*-----------------------------------------------------------------------------------*/

function neubau_scripts() {
	global $wp_styles;

	// Add fonts, used in the main stylesheet.
	wp_enqueue_style( 'neubau-fonts', neubau_fonts_url(), array(), null );

	// Loads JavaScript to pages with the comment form to support sites with threaded comments (when in use)
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );

	// Loads stylesheets.
	wp_enqueue_style( 'neubau-style', get_stylesheet_uri(), array(), '20151030' );
	wp_enqueue_style( 'neubau-animatecss', get_template_directory_uri() . '/css/animate.min.css', array(), '3.5.0' );

	// Loads bar script
	wp_enqueue_script( 'neubau-loadingbar', get_template_directory_uri() . '/js/pace.min.js', array( 'jquery' ), '1.0.0' );

	// Loading viewpoint checker script
	wp_enqueue_script( 'neubau-viewportchecker', get_template_directory_uri() . '/js/jquery.viewportchecker.min.js', array( 'jquery' ), '1.8.5' );

	// Loads Post Masonry
	wp_enqueue_script( 'imagesLoaded', get_template_directory_uri() . '/js/imagesLoaded.js', array( 'jquery' ), '3.2.0' );
	wp_enqueue_script( 'neubau-postmasonry', get_template_directory_uri() . '/js/postmasonry.js', array( 'jquery', 'masonry' ), '20151128', true );

	// Loads Custom Neubau JavaScript functionality
	wp_enqueue_script( 'neubau-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20151128', true );
	wp_localize_script( 'neubau-script', 'screenReaderText', array(
		'expand'   => '<span class="screen-reader-text">' . __( 'expand child menu', 'neubau' ) . '</span>',
		'collapse' => '<span class="screen-reader-text">' . __( 'collapse child menu', 'neubau' ) . '</span>',
	) );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

}
add_action( 'wp_enqueue_scripts', 'neubau_scripts' );


/*-----------------------------------------------------------------------------------*/
/* Load block editor styles.
/*-----------------------------------------------------------------------------------*/
function neubau_block_editor_styles() {
 wp_enqueue_style( 'neubau-block-editor-styles', get_template_directory_uri() . '/block-editor.css');
 wp_enqueue_style( 'neubau-fonts', neubau_fonts_url(), array(), null );
}
add_action( 'enqueue_block_editor_assets', 'neubau_block_editor_styles' );


/*-----------------------------------------------------------------------------------*/
/* Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
/*-----------------------------------------------------------------------------------*/

function neubau_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'neubau_page_menu_args' );


/*-----------------------------------------------------------------------------------*/
/* Sets the authordata global when viewing an author archive.
/*-----------------------------------------------------------------------------------*/

function neubau_setup_author() {
	global $wp_query;

	if ( $wp_query->is_author() && isset( $wp_query->post ) ) {
		$GLOBALS['authordata'] = get_userdata( $wp_query->post->post_author );
	}
}
add_action( 'wp', 'neubau_setup_author' );


/*-----------------------------------------------------------------------------------*/
/* Short Title.
/*-----------------------------------------------------------------------------------*/

function neubau_title_limit($length, $replacer = '...') {
 $string = the_title('','',FALSE);
 if(strlen($string) > $length)
 $string = (preg_match('/^(.*)\W.*$/', substr($string, 0, $length+1), $matches) ? $matches[1] : substr($string, 0, $length)) . $replacer;
 echo $string;
}


/*-----------------------------------------------------------------------------------*/
/* Multiple Custom Excerpt Lengths
/*-----------------------------------------------------------------------------------*/

function neubau_excerpt($limit) {
 $excerpt = explode(' ', get_the_excerpt(), $limit);
 if (count($excerpt)>=$limit) {
 array_pop($excerpt);
 $excerpt = implode(" ",$excerpt).'...';
 } else {
 $excerpt = implode(" ",$excerpt);
 }
 $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
 return $excerpt;
}

function content($limit) {
 $content = explode(' ', get_the_content(), $limit);
 if (count($content)>=$limit) {
 array_pop($content);
 $content = implode(" ",$content).'...';
 } else {
 $content = implode(" ",$content);
 }
 $content = preg_replace('/[.+]/','', $content);
 $content = apply_filters('the_content', $content);
 $content = str_replace(']]>', ']]&gt;', $content);
 return $content;
}


/*-----------------------------------------------------------------------------------*/
/* Add custom max excerpt lengths.
/*-----------------------------------------------------------------------------------*/

function custom_excerpt_length( $length ) {
	return 45;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );


/*-----------------------------------------------------------------------------------*/
/* Replace "[...]" with custom read more in excerpts.
/*-----------------------------------------------------------------------------------*/

function neubau_excerpt_more( $more ) {
	global $post;
	return '&hellip;';
}
add_filter( 'excerpt_more', 'neubau_excerpt_more' );


/*-----------------------------------------------------------------------------------*/
/* Add Theme Customizer CSS
/*-----------------------------------------------------------------------------------*/

function neubau_customize_css() {
		?>
	<style type="text/css">
	<?php if ('#000000' != get_theme_mod( 'link_color' ) ) { ?>
	.entry-content a, .comment-text a { color: <?php echo get_theme_mod('link_color', '#000000'); ?>;}
	<?php } ?>
	<?php if ('#bababa' != get_theme_mod( 'footer_bg_color' ) ) { ?>
	.footer-widgets { background: <?php echo get_theme_mod('footer_bg_color', '#bababa'); ?>;}
	<?php } ?>
	.pace .pace-progress, #overlay-wrap {background: <?php echo get_theme_mod('hover_mobilemenu_color'); ?>;}
	.entry-header h2.entry-title a:hover {color: <?php echo get_theme_mod('hover_mobilemenu_color'); ?>;}
	@media screen and (min-width: 1100px) {
	#overlay-wrap {background: #ffffff;}
	}
	</style>
		<?php
}
add_action( 'wp_head', 'neubau_customize_css');


/*-----------------------------------------------------------------------------------*/
/* Remove inline styles printed when the gallery shortcode is used.
/*-----------------------------------------------------------------------------------*/

add_filter('use_default_gallery_style', '__return_false');


/**
 * Callback to change just html output on a comment.
 */
function neubau_comments_callback($comment, $args, $depth){
	//checks if were using a div or ol|ul for our output
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( $args['has_children'] ? 'parent' : '', $comment ); ?>>

		<div id="comment-<?php comment_ID(); ?>" class="comment">
			<div class="comment-avatar">
				<?php echo get_avatar( $comment, 100 ); ?>
			</div>

			<div class="comment-wrap">
				<div class="comment-details">
					<div class="comment-author">

						<?php printf( ( '%s' ), wp_kses_post( sprintf( '%s', get_comment_author_link() ) ) ); ?>
					</div><!-- end .comment-author -->
					<div class="comment-meta">
						<span class="comment-time"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
							<?php
							/* translators: 1: date */
								printf( esc_html__( '%1$s', 'neubau' ),
								get_comment_date());
							?></a>
						</span>
						<?php edit_comment_link( esc_html__(' Edit', 'neubau'), '<span class="comment-edit">', '</span>'); ?>
					</div><!-- end .comment-meta -->
				</div><!-- end .comment-details -->

				<div class="comment-text">
				<?php comment_text(); ?>
					<?php if ( $comment->comment_approved == '0' ) : ?>
						<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'neubau' ); ?></p>
					<?php endif; ?>
				</div><!-- end .comment-text -->
				<?php if ( comments_open () ) : ?>
					<div class="comment-reply"><?php comment_reply_link( array_merge( $args, array( 'reply_text' => esc_html__( 'Reply', 'neubau' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?></div>
				<?php endif; ?>
			</div><!-- end .comment-wrap -->
		</div><!-- end .comment -->
	<?php
}

/*-----------------------------------------------------------------------------------*/
/* Register widgetized areas
/*-----------------------------------------------------------------------------------*/

function neubau_widgets_init() {

	register_sidebar( array (
		'name' => esc_html__( 'Posts Sidebar', 'neubau' ),
		'id' => 'sidebar-1',
		'description' => esc_html__( 'Widgets appear in the left-aligned sidebar on single posts.', 'neubau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array (
		'name' => esc_html__( 'Footer 1', 'neubau' ),
		'id' => 'sidebar-2',
		'description' => esc_html__( 'Widgets appear in first column of the Footer widget area.', 'neubau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array (
		'name' => esc_html__( 'Footer 2', 'neubau' ),
		'id' => 'sidebar-3',
		'description' => esc_html__( 'Widgets appear in second column of the Footer widget area.', 'neubau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array (
		'name' => esc_html__( 'Footer 3', 'neubau' ),
		'id' => 'sidebar-4',
		'description' => esc_html__( 'Widgets appear in third column of the Footer widget area.', 'neubau' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => "</aside>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

}
add_action( 'widgets_init', 'neubau_widgets_init' );


/*-----------------------------------------------------------------------------------*/
/* Extends the default WordPress body classes
/*-----------------------------------------------------------------------------------*/
function neubau_body_class( $classes ) {

	if ( is_page_template( 'page-templates/full-width.php' ) ) {
		$classes[] = 'fullwidth';
	}

	if ( has_header_image() ) {
		$classes[] = 'custom-logo';
	}

	return $classes;
}
add_filter( 'body_class', 'neubau_body_class' );


/*-----------------------------------------------------------------------------------*/
/* Customizer additions
/*-----------------------------------------------------------------------------------*/
require get_template_directory() . '/inc/customizer.php';

/*-----------------------------------------------------------------------------------*/
/* Load Jetpack compatibility file.
/*-----------------------------------------------------------------------------------*/
require get_template_directory() . '/inc/jetpack.php';

/*-----------------------------------------------------------------------------------*/
/* Grab the Neubau Custom shortcodes.
/*-----------------------------------------------------------------------------------*/
require( get_template_directory() . '/inc/shortcodes.php' );

/*-----------------------------------------------------------------------------------*/
/* Add One Click Demo Import code.
/*-----------------------------------------------------------------------------------*/
require get_template_directory() . '/inc/demo-installer.php';
