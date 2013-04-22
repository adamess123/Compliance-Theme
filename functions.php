<?php
require_once('functions/base.php');   			# Base theme functions
require_once('custom-taxonomies.php');  		# Where per theme taxonomies are defined
require_once('custom-post-types.php');  		# Where per theme post types are defined
require_once('functions/admin.php');  			# Admin/login functions
require_once('functions/config.php');			# Where per theme settings are registered
require_once('shortcodes.php');         		# Per theme shortcodes

//Add theme-specific functions here.

/**
 * Allow shortcodes in widgets
 **/
add_filter('widget_text', 'do_shortcode');


/**
 * Hide unused admin tools (Links, Comments, etc)
 **/
function hide_admin_links() {
	remove_menu_page('link-manager.php');
	remove_menu_page('edit-comments.php');
}
add_action('admin_menu', 'hide_admin_links');


/**
 * Display the Below the Fold content for this theme.
 *
 * @return string
 * @author Jo Greybill
 **/
function get_below_the_fold() {
	$options = get_option(THEME_OPTIONS_NAME);
	$title	 = $options['btf_title'];
	$blurb	 = $options['btf_blurb'];
	$cta	 = $options['btf_cta'];
	$link	 = $options['btf_link'];
	
	ob_start(); 
	?>
	<div class="row" id="below-the-fold">
		<hr class="span12" />
		<div class="span2" id="below-the-fold-icon">
			<p><img src="<?=THEME_IMG_URL?>/below-the-fold.png" alt="<?=$title?>" title="<?=$title?>" /></p>
		</div>
		<div class="span7" id="below-the-fold-content">
			<h3><?=$title?></h3>
			<p><?=$blurb?></p>
		</div>
		<div class="span3" id="below-the-fold-cta">
			<a href="<?=do_shortcode($link);?>"><?=$cta?></a>
		</div>
	</div>	
	<?php
	return ob_get_clean();	
}


/**
 * Return the home page featured content.
 * This function is flexible and accounts for the number
 * of features specified in the Theme Options, returning the 
 * correct Bootstrap .span's to accomodate what is specified.
 *
 * @return string
 * @author Jo Greybill 
 **/
function get_home_featured_content() {
	$options 		= get_option(THEME_OPTIONS_NAME);
	$feature_cols	= 3;  // number of feature columns (max 3)
	$feature_1 		= $options['home_feature_1'];
	$feature_2 		= $options['home_feature_2'];
	$feature_3 		= $options['home_feature_3'];
	$features		= array($feature_1, $feature_2, $feature_3);
	
	// Set our $feature_cols value based on features
	// that point to some page content, and unset any
	// features from the $features array that don't point
	// to anything
	foreach ($features as $f) {
		switch ($f) {
			case 'none':
				$feature_cols = $feature_cols - 1;
				// This will probably unset all 'none' vals the
				// 1st time it runs, but this is ok in this case.
				unset($features[array_search($f, $features)]);
				break;
			default:
				break;
		}
	}
	
	// Determine the Bootstrap classes for the feature
	// pieces based on the number of features set to display
	switch ($feature_cols) {
		case 0:
			break;
		case 1:
			$spanclass = 'span12';
			break;
		case 2:
			$spanclass = 'span6';
			break;
		case 3:
		default:
			$spanclass = 'span4';
			break;
	}
	
	if ($feature_cols > 0) { 
		ob_start();
	?>
		<div class="row" id="home-features">
			<hr class="span12" />
		<?php foreach ($features as $f) { ?>
			<?php
				$page = get_post($f);
				$title = $page->post_title;
				$desc = get_post_meta($f->ID, 'page_description', true);
			?>
			<div class="<?=$spanclass?> home-feature">
				<?=get_the_post_thumbnail($f->ID, 'thumbnail', array('class' => 'home-feature-icon'));?>
				<div class="home-feature-textwrap">
					<h3><?=$title?></h3>
					<p><?=$desc?></p>
				</div>
			</div>		
		<?php } ?>
		</div>
	<?php
		return ob_get_clean();
	}
}
	
?>