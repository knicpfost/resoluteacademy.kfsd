<?php

// =============================== Widget Functions ======================================

// Input Option: Listing Link Categories
function DisplayCats($name,$select)
{
	$linkcats = array();
	$linkcats = get_categories('type=post');
	
	echo '<p><label for="' . $name .  '_category">Link Category:
					<select name="' . $name .  '_category" class="widefat" style="width: 94% !important;">';
	
	foreach ( $linkcats as $singlecat ) {
		
		if ( $select == $singlecat->cat_name ) { echo '<option value="' . $singlecat->cat_name . '" selected="selected">' . $singlecat->cat_name . '</option>'; }
			else { echo '<option value="' . $singlecat->cat_name . '">' . $singlecat->cat_name . '</option>'; }
		
	}
	
	echo '</select></label></p>';

}

// =============================== News from the blog widget ======================================
function newsWidget()
{
	$settings = get_option("widget_newswidget");

	$title = $settings['title'];
	$number = $settings['number'];
	$category = $settings['category'];	

?>
			<div id="news" class="widget block">            
				<h3><?php if ($title <> "") echo $title; else echo 'Latest News'; ?></h3>
                <ul>
					<?php
						query_posts('showposts='. $number . '&category_name=' . $category);
				 	    while (have_posts()) : the_post(); 
					?>
					    <li><strong><a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo get_the_title($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></strong><span class="meta"><?php echo the_time('d F Y'); ?></span></li>
					    
					<?php endwhile; ?>
				</ul>
			</div><!-- /news -->
<?php
}

function newsWidgetAdmin() {

	$settings = get_option("widget_newswidget");

	// check if anything's been sent
	if (isset($_POST['update_news'])) {
		$settings['title'] = strip_tags(stripslashes($_POST['news_title']));
		$settings['number'] = strip_tags(stripslashes($_POST['news_number']));
		$settings['category'] = strip_tags(stripslashes($_POST['news_category']));		

		update_option("widget_newswidget",$settings);
	}

	DisplayCats('news',$settings['category']);
			
	echo '<p>
			<label for="news_title">Title:
			<input id="news_title" name="news_title" type="text" class="widefat" value="'.$settings['title'].'" /></label></p>';
	echo '<p>
			<label for="news_number">Number of posts:
			<input id="news_number" name="news_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_news" name="update_news" value="1" />';

}

register_sidebar_widget('Woo - Latest News', 'newsWidget');
register_widget_control('Woo - Latest News', 'newsWidgetAdmin', 200, 200);

// =============================== Twitter widget ======================================
function twitterWidget()
{
	$number = 5;
	$title = "Twitter";
	$settings = get_option("widget_twitterwidget");
	if ($settings['number']) $number = $settings['number'];
	if ($settings['title']) $title = $settings['title'];
	if ($settings['username']) $username = $settings['username'];

?>
<div class="block widget widget_twitter">
	<h3 class="hl"><?php echo $title; ?></h3>
	<ul id="twitter_update_list"><li></li></ul>		
    <p class="follow"><a href="http://www.twitter.com/<?php echo $username; ?>"><?php _e('Follow us on Twitter',woothemes); ?></a></p>
</div><?php
$GLOBALS[twitter_widget] = true;
}
register_sidebar_widget('Woo - Twitter', 'twitterWidget');

function twitterWidgetAdmin() {

	$settings = get_option("widget_twitterwidget");

	// check if anything's been sent
	if (isset($_POST['update_twitter'])) {
		$settings['username'] = strip_tags(stripslashes($_POST['twitter_username']));
		$settings['number'] = strip_tags(stripslashes($_POST['twitter_number']));
		$settings['title'] = strip_tags(stripslashes($_POST['twitter_title']));
		update_option("widget_twitterwidget",$settings);
	}

	echo '<p>
			<label for="twitter_username">Twitter username:
			<input id="twitter_username" name="twitter_username" type="text" class="widefat" value="'.$settings['username'].'" /></label></p>';

	echo '<p>
			<label for="twitter_number">Number of tweets (default = 5):
			<input id="twitter_number" name="twitter_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<p>
			<label for="twitter_title">Title
			<input id="twitter_title" name="twitter_title" type="text" class="widefat" value="'.$settings['title'].'" /></label></p>';
	echo '<input type="hidden" id="update_twitter" name="update_twitter" value="1" /></label>';


}
register_widget_control('Woo - Twitter', 'twitterWidgetAdmin', 200, 200);

// =============================== Flickr widget ======================================
function flickrWidget()
{
	$settings = get_option("widget_flickrwidget");

	$id = $settings['id'];
	$number = $settings['number'];

?>

<div id="flickr" class="block widget">
	<h3 class="widget_title"><?php _e('Photos on',woothemes); ?> <span>flick<span>r</span></span></h3>
	<div class="wrap">
		<div class="fix"></div>
		<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count=<?php echo $number; ?>&amp;display=latest&amp;size=s&amp;layout=x&amp;source=user&amp;user=<?php echo $id; ?>"></script>        
		<div class="fix"></div>
	</div>
</div>

<?php
}

function flickrWidgetAdmin() {

	$settings = get_option("widget_flickrwidget");

	// check if anything's been sent
	if (isset($_POST['update_flickr'])) {
		$settings['id'] = strip_tags(stripslashes($_POST['flickr_id']));
		$settings['number'] = strip_tags(stripslashes($_POST['flickr_number']));

		update_option("widget_flickrwidget",$settings);
	}

	echo '<p>
			<label for="flickr_id">Flickr ID (<a href="http://www.idgettr.com">idGettr</a>):
			<input id="flickr_id" name="flickr_id" type="text" class="widefat" value="'.$settings['id'].'" /></label></p>';
	echo '<p>
			<label for="flickr_number">Number of photos:
			<input id="flickr_number" name="flickr_number" type="text" class="widefat" value="'.$settings['number'].'" /></label></p>';
	echo '<input type="hidden" id="update_flickr" name="update_flickr" value="1" />';

}

register_sidebar_widget('Woo - Flickr', 'flickrWidget');
register_widget_control('Woo - Flickr', 'flickrWidgetAdmin', 400, 200);


// =============================== Ad 200x200 widget ======================================
function ad300Widget()
{
?>
<div id="advert_300x250" class="wrap widget">

	<?php if (get_option('woo_ad_300_adsense') <> "") { echo stripslashes(get_option('woo_ad_300_adsense')); ?>
	
	<?php } else { ?>
	
		<a href="<?php echo get_option('woo_ad_300_url'); ?>"><img src="<?php echo get_option('woo_ad_300_image'); ?>" alt="advert" /></a>
		
	<?php } ?>	

</div>
<?php 
}
register_sidebar_widget('Woo - Ad 300x250', 'ad300Widget');

// =============================== Search widget ======================================
function searchWidget()
{
include(TEMPLATEPATH . '/search-form.php');
}
register_sidebar_widget('Woo - Search', 'SearchWidget');

// =============================== CampaignMonitor Subscribe widget ======================================
function campaignmonitorWidget()
{
	$settings = get_option("widget_campaignmonitorwidget");

	$action = $settings['action'];
	$id = $settings['id'];
	$title = $settings['title'];

?>

<div id="campaignmonitor" class="block widget">

    <h3><?php echo $title; ?></h3>

    <form name="campaignmonitorform" id="campaignmonitorform" action="<?php echo $action; ?>" method="post">
        
        <input type="text" name="cm-<?php echo $id; ?>" id="<?php echo $id; ?>" class="field" value="<?php _e('Enter your e-mail address',woothemes); ?>" onfocus="if (this.value == '<?php _e('Enter your e-mail address',woothemes); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Enter your e-mail address',woothemes); ?>';}" />
        
        <input class="button" type="submit" name="submit" value="submit" />
        
    </form>
    
</div><!-- /campaignmonitor -->	

<?php
}

function campaignmonitorWidgetAdmin() {

	$settings = get_option("widget_campaignmonitorwidget");

	// check if anything's been sent
	if (isset($_POST['update_campaignmonitor'])) {
		$settings['id'] = strip_tags(stripslashes($_POST['campaignmonitor_id']));
		$settings['action'] = strip_tags(stripslashes($_POST['campaignmonitor_action']));
		$settings['title'] = strip_tags(stripslashes($_POST['campaignmonitor_title']));

		update_option("widget_campaignmonitorwidget",$settings);
	}

	echo '<p>
			<label for="campaignmonitor_title">Title:
			<input id="campaignmonitor_title" name="campaignmonitor_title" type="text" class="widefat" value="'.$settings['title'].'" /></label></p>';
	echo '<p>
			<label for="campaignmonitor_action">Your Campaign Monitor Form Action:
			<input id="campaignmonitor_action" name="campaignmonitor_action" type="text" class="widefat" value="'.$settings['action'].'" /></label></p>';			
	echo '<p>
			<label for="campaignmonitor_id">Your Campaign Monitor ID:
			<input id="campaignmonitor_id" name="campaignmonitor_id" type="text" class="widefat" value="'.$settings['id'].'" /></label></p>';						
	echo '<input type="hidden" id="update_campaignmonitor" name="update_campaignmonitor" value="1" />';

}

register_sidebar_widget('Woo - Campaign Monitor Subscription', 'campaignmonitorWidget');
register_widget_control('Woo - Campaign Monitor Subscription', 'campaignmonitorWidgetAdmin', 400, 200);


// =============================== Feedburner Subscribe widget ======================================
function feedburnerWidget()
{
	$settings = get_option("widget_feedburnerwidget");

	$id = $settings['id'];
	$title = $settings['title'];
	$google = $settings['google'];	

?>

<div id="feedburner" class="block widget">

    <h3><?php echo $title; ?></h3>

<form action="<?php if ($google) { ?>http://feedburner.google.com/fb/a/mailverify<?php } else { ?>http://www.feedburner.com/fb/a/emailverify<?php } ?>" method="post" target="popupwindow" onsubmit="window.open('<?php if ($google) { ?>http://feedburner.google.com/fb/a/mailverify?uri=<?php } else { ?>http://www.feedburner.com/fb/a/emailverifySubmit?feedId=<?php } ?><?php echo $id; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
        
        <input class="field" type="text" name="email" value="<?php _e('Enter your e-mail address',woothemes); ?>" onfocus="if (this.value == '<?php _e('Enter your e-mail address',woothemes); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php _e('Enter your e-mail address',woothemes); ?>';}" />
        <input type="hidden" value="<?php echo $id; ?>" name="uri"/>
        <input type="hidden" value="<?php bloginfo('name'); ?>" name="title"/>
        <input type="hidden" name="loc" value="en_US"/>
        
        <input class="button" type="submit" name="submit" value="submit" />
        
    </form>
    
</div><!-- /feedburner -->	

<?php
}

function feedburnerWidgetAdmin() {

	$settings = get_option("widget_feedburnerwidget");

	// check if anything's been sent
	if (isset($_POST['update_feedburner'])) {
		$settings['id'] = strip_tags(stripslashes($_POST['feedburner_id']));
		$settings['title'] = strip_tags(stripslashes($_POST['feedburner_title']));
		$settings['google'] = $_POST['subscribe_google'];		

		update_option("widget_feedburnerwidget",$settings);
	}

	echo '<p>
			<label for="feedburner_title">Title:
			<input id="feedburner_title" name="feedburner_title" type="text" class="widefat" value="'.$settings['title'].'" /></label></p>';
	echo '<p>
			<label for="feedburner_id">Your Feedburner ID:
			<input id="feedburner_id" name="feedburner_id" type="text" class="widefat" value="'.$settings['id'].'" /></label></p>';			
	echo '<input type="hidden" id="update_feedburner" name="update_feedburner" value="1" />';

	if ( $settings['google'] ) {
	
		echo '<p>
				<label for="subscribe_google">Use Feedburner Google URL?:
				<input id="subscribe_google" name="subscribe_google" type="checkbox" checked /></label></p>';			

	} else {

		echo '<p>
				<label for="subscribe_google">Use Feedburner Google URL?:
				<input id="subscribe_google" name="subscribe_google" type="checkbox" /></label></p>';			
	
	}

}

register_sidebar_widget('Woo - Feedburner Subscription', 'feedburnerWidget');
register_widget_control('Woo - Feedburner Subscription', 'feedburnerWidgetAdmin', 400, 200);


/* Deregister Default Widgets */

/*
function woo_deregister_widgets(){
    unregister_widget('WP_Widget_Search');         
}
add_action('widgets_init', 'woo_deregister_widgets');  
*/

?>