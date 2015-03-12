<?php get_header(); ?>

	<!-- Featured Slider -->
	<?php include (TEMPLATEPATH . "/includes/slider.php"); ?>
	<!-- Featured Slider end -->

	<div id="main-content" class="home">           
    <div class="content">
		<div class="col-left">
			<div id="main">
                                                                                    
                <!-- Post Starts -->
                <div class="post wrap">
                
				<?php
                    // Split the main content pages from the options, and put in an array
                    $featpages = get_option('woo_main_pages');
                    $featarr=split(",",$featpages);
                    $featarr = array_diff($featarr, array(""));
                ?>
				<?php foreach ( $featarr as $featitem ) { ?>
                <?php query_posts('page_id=' . $featitem); ?>
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>		        					

					<?php if ( get_post_meta($post->ID, 'image', true) ) { ?>
                    <img src="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" alt="" class="home-icon" />				
					<?php } ?> 
                                                         
                   <div <?php if ( get_post_meta($post->ID, 'image', true) ) { ?>class="feature"<?php } ?>>
                       <h3><?php the_title(); ?></h3>
                       <?php the_content(); ?>
                    </div>
                    <div class="hr"></div>
        
                <?php endwhile; endif; ?>
                <?php } ?>
                    
                </div>
                <!-- Post Ends -->
                                                    
			<?php //endwhile; endif; ?>  
                        
            </div><!-- main ends -->
        </div><!-- .col-left ends -->

        <?php get_sidebar('home'); ?>
	
    </div><!-- .content Ends -->
    </div><!-- #main-content ends -->
    	
<?php get_footer(); ?>