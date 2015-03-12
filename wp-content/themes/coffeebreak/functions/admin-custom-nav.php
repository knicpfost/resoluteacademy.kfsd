<?php

/*-----------------------------------------------------------------------------------

TABLE OF CONTENTS

- Woothemes Custom Navigation Setup
-- Woothemes Custom Navigation Setup
-- Woothemes Custom Navigation Menu Item
-- Woothemes Custom Navigation Scripts
- Woothemes Custom Navigation Interface
- Woothemes Custom Navigation Functions
-- woo_custom_navigation_output()
-- woo_custom_navigation_sub_items()
-- woo_get_pages()
-- woo_get_categories()
-- woo_custom_navigation_default_sub_items()
- Recursive Get Child Items Function
- Woothemes Custom Navigation Menu Widget

-----------------------------------------------------------------------------------*/


/*-----------------------------------------------------------------------------------*/
/* Woothemes Custom Navigation Menu Setup
/* Setup of the Menu
/* Add Menu Item to the theme
/* Scripts - JS and CSS
/*-----------------------------------------------------------------------------------*/
function woo_custom_navigation_setup() {

	//Custom Navigation Menu Setup

	//Override for menu descriptions
	update_option('woo_settings_custom_nav_advanced_options','yes');
	
	if ($_GET['page'] == 'custom_navigation') 
	{
	
		//CREATE Custom Menu tables
		global $wpdb;
		$table_name = $wpdb->prefix . "woo_custom_nav_records";
		
		if($wpdb->get_var("show tables like '$table_name'") != $table_name) 
		{
			$sql = "CREATE TABLE " . $table_name . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			position bigint(11) NOT NULL,
			post_id bigint(11) NOT NULL,
			parent_id bigint(11) NOT NULL,
			custom_title text NOT NULL,
			custom_link VARCHAR(55) NOT NULL,
			custom_description text NOT NULL,
			menu_icon text NOT NULL,
			link_type varchar(55) NOT NULL default 'custom',
			menu_id bigint(11) NOT NULL,
			UNIQUE KEY id (id)
			);";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		$table_name_menus = $wpdb->prefix . "woo_custom_nav_menus";
		
		if($wpdb->get_var("show tables like '$table_name_menus'") != $table_name_menus) 
		{
			$sql = "CREATE TABLE " . $table_name_menus . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			menu_name text NOT NULL,
			UNIQUE KEY id (id)
			);";
			
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
			
			//POPULATE with first menu
			$insert = "INSERT INTO ".$table_name_menus." (menu_name) "."VALUES ('Woo Menu 1')";
  			$results = $wpdb->query( $insert );
  			
  			//POPULATE with first menu content
  			//Pages
  			$table_name = $wpdb->prefix . "woo_custom_nav_records";
  			
  			//GET all current pages
  			$pages_args = array(
		    	'child_of' => 0,
				'sort_order' => 'ASC',
				'sort_column' => 'post_title',
				'hierarchical' => 1,
				'exclude' => '',
				'include' => '',
				'meta_key' => '',
				'meta_value' => '',
				'authors' => '',
				'parent' => 0,
				'exclude_tree' => '',
				'number' => '',
				'offset' => 0 );
			
			$pages_array = get_pages($pages_args);
			$counter = 1;
			
			//INSERT Loop
			foreach ($pages_array as $post) 
			{
				//CHECK if is top level element
				if ($post->post_parent == 0) 
				{
					//CHECK for existing page records
					$table_name_parent = $wpdb->prefix . "woo_custom_nav_records";
					$woo_result = $wpdb->get_results("SELECT id FROM ".$table_name_parent." WHERE post_id='".$post->post_parent."' AND link_type='page' AND menu_id='1'");
					
					if ($woo_result > 0) {
						$parent_id = $woo_result[0]->id;
					}
					else {
						$parent_id = 0;
					}
					
					//INSERT page								
					$insert = "INSERT INTO ".$table_name." (position,post_id,parent_id,custom_title,custom_link,custom_description,menu_icon,link_type,menu_id) "."VALUES ('".$counter."','".$post->ID."','".$parent_id."','".$post->post_title."','".get_permalink($post->ID)."','','','page','1')";
	  				$results = $wpdb->query( $insert );
	  				$counter++;
	 
	 				//$counter = get_children_menu_elements($post->ID, $counter, $post->ID, 'pages',1,$table_name); 
	 				$counter = get_children_menu_elements($post->ID, $counter, $post->post_parent, 'pages',1,$table_name);
 				}
 				//Do nothing
 				else
 				{
 				
 				}
 			}  			
  			
  			//GET all current categories
  			$category_args = array(
				'type'                     => 'post',
				'child_of'                 => 0,
				'orderby'                  => 'name',
				'order'                    => 'ASC',
				'hide_empty'               => false,
				'include_last_update_time' => false,
				'hierarchical'             => 0,
				'parent'             		=> 0,
				'depth'						=> 1,
				'exclude'                  => '',
				'include'                  => '',
				'number'                   => '',
				'pad_counts'               => false );
			
			
			$categories_array = get_categories($categories_args);

  			//POPULATE with second menu
			$insert = "INSERT INTO ".$table_name_menus." (menu_name) "."VALUES ('Woo Menu 2')";
  			$results = $wpdb->query( $insert );

			//POPULATE with second menu content
  			//GET all current pages
			
			$counter = 1;
			
  			//GET all current categories
			
			//INSERT Loop
			foreach ($categories_array as $cat_item) {
				
				//CHECK if is top level element
				if ($cat_item->parent == 0)
				{
					//CHECK for existing category records
					$table_name_parent = $wpdb->prefix . "woo_custom_nav_records";
					$woo_result = $wpdb->get_results("SELECT id FROM ".$table_name_parent." WHERE post_id='".$cat_item->parent."' AND link_type='category' AND menu_id='2'");
					
					if ($woo_result > 0) {
						$parent_id = $woo_result[0]->id;
					}
					else {
						$parent_id = 0;
					}
					
					//INSERT category
					$insert = "INSERT INTO ".$table_name." (position,post_id,parent_id,custom_title,custom_link,custom_description,menu_icon,link_type,menu_id) "."VALUES ('".$counter."','".$cat_item->cat_ID."','".$parent_id."','".$cat_item->cat_name."','".get_category_link($cat_item->cat_ID)."','','','category','2')";
	  				$results = $wpdb->query( $insert );
	 
	  				$counter++;
	  				
	  				$counter = get_children_menu_elements($cat_item->cat_ID, $counter, $cat_item->parent, 'categories',2,$table_name); 
 				}
 				//Do nothing
 				else {
 				
 				}
 			}

		}
		
		
	   	
	}

}

function woo_custom_navigation_menu() {

	//Woothemes Custom Navigation Menu	
	$woopage = add_submenu_page('woothemes', 'Custom Navigation', 'Custom Navigation', 8, 'custom_navigation', 'woo_custom_navigation');
	
	add_action("admin_print_scripts-$woopage", 'woo_custom_nav_scripts' );
	  	
}

function woo_custom_nav_scripts() {

	//STYLES AND JAVASCRIPT
	//JQUERY
	echo '<script src="'.get_bloginfo('template_directory').'/functions/js/jquery-1.3.2.min.js" type="text/javascript"></script>';
	//JQUERY UI
	echo '<script src="'.get_bloginfo('template_directory').'/functions/js/jquery-ui-1.7.2.custom.min.js" type="text/javascript"></script>';
	//Menu handler dynamic items
	echo '<script src="'.get_bloginfo('template_directory').'/functions/js/custom_menu_dynamic_items.js" type="text/javascript"></script>';
	//Menu handler default items
	echo '<script src="'.get_bloginfo('template_directory').'/functions/js/custom_menu_initial_items.js" type="text/javascript"></script>';
	//Menu Autocomplete
	echo '<script src="'.get_bloginfo('template_directory').'/functions/js/jquery.autocomplete.js" type="text/javascript"></script>';
	//Default Style
	echo '<link rel="stylesheet" href="'.get_bloginfo('template_directory').'/functions/css/custom_menu.css" type="text/css" media="all" />';
	   	
}



/*-----------------------------------------------------------------------------------*/
/* Woothemes Custom Navigation Menu Interface
/* woo_custom_navigation() is the main function for the Custom Navigation
/* See functions in admin-functions.php
/*-----------------------------------------------------------------------------------*/

function woo_custom_navigation() {
	global $wpdb;
	?>

	<div class="wrap">
	<div id="no-js"><h3>You do not have JavaScript enabled in your browser. Please enabled it to access the Custom Menu functionality.</h3></div>
			
	    <?php
	     
	    //Get the theme name
	    $themename =  get_option('woo_themename');
	    
	    //Default Menu to show
		$menu_selected_id = 1;
		
		//CHECK which menu is selected and if menu is in edit already
		if ($_POST['switch_menu']) {
			//echo $_POST['menu_select'];
			$menu_selected_id = $_POST['menu_select'];
		}
		elseif ($_POST['menu_id_in_edit']){
			$menu_selected_id = $_POST['menu_id_in_edit'];
		}
		else {
		
		}
	    
	    
	    if ($_POST['set_woo_menu'])
	    {
	    	
	    	update_option('woo_custom_nav_menu', $_POST['enable_woo_menu']);
	    }
	    
	    //CHECK for existing woo custom menu
	 	$table_name = $wpdb->prefix . "woo_custom_nav_records";	 	
	 	$custom_nav_exists = $wpdb->query("SELECT id FROM ".$table_name." WHERE menu_id='".$menu_selected_id."'");
	    
		$postCounter = $_POST['licount'];
		
		if ($postCounter > 0) 
		{
			
			if ($_POST['switch_menu']) {
				
			}
			elseif ($_POST['add_menu']) {
				
				$table_name_custom_menu = $wpdb->prefix . "woo_custom_nav_menus";
	 			$insert_menu_name = $_POST['add_menu_name'];
	 			
	 			//CHECK for existing woo custom menu
	 			$existing_records = $wpdb->query("SELECT id FROM ".$table_name_custom_menu." WHERE menu_name='".$insert_menu_name."'");
	 			
	 			if ($insert_menu_name <> '') {
	 				if ($existing_records > 0) 
	 				{
	 					$messagesdiv = '<div id="message" class="error fade below-h2"><p>'.$insert_menu_name.' Menu has already created - please try another name</p></div>';	
	 				}
	 				else 
	 				{
	 					$wpdb->insert( $table_name_custom_menu, array( 'menu_name' => $insert_menu_name )); 	
	 					$messagesdiv = '<div id="message" class="updated fade below-h2"><p>'.$insert_menu_name.' Menu has been created!</p></div>';	
	 				}
	 			}
	 			else 
	 			{
	 				$messagesdiv = '<div id="message" class="error fade below-h2"><p>Please enter a valid Menu name</p></div>';
	 			}
	 			
				
			}
			else {
				
				$menu_id_in_edit = $_POST['menu_id_in_edit'];
				//After POST delete existing records in prep for Insert
				$wpdb->query("DELETE FROM ".$table_name." WHERE menu_id='".$menu_id_in_edit."'");
				
				//Loop through all POST variables
 				for ($k = 1;$k<= $postCounter; $k++) {
 					
 					$db_id = $_POST['dbid'.$k];
 					$post_id = $_POST['postmenu'.$k];
 					$parent_id = $_POST['parent'.$k];
 					$custom_title = $_POST['title'.$k];
 					$custom_linkurl = $_POST['linkurl'.$k];
 					$custom_description = $_POST['description'.$k];
 					$icon = $_POST['icon'.$k];
 					$position = $_POST['position'.$k];
 					$linktype = $_POST['linktype'.$k];
 					
 					if ($linktype == '')
 					{
 					
 					}
 					else
 					{
 						//If top level menu item
	 					if ($parent_id == 0)
	 					{
	 						//INSERT menu item record
	 						$wpdb->insert( $table_name, array( 'position' => $position, 'post_id' => $post_id, 'parent_id' => $parent_id, 'custom_title' => $custom_title, 'custom_link' => $custom_linkurl, 'custom_description' => $custom_description, 'menu_icon' => $icon, 'link_type' => $linktype, 'menu_id' => $menu_id_in_edit )); 	
	 					}
	 					//If not top level menu item
	 					else 
	 					{
	 						//INSERT menu item record
	 						$wpdb->insert( $table_name, array( 'position' => $position, 'post_id' => $post_id, 'parent_id' => '8000', 'custom_title' => $custom_title, 'custom_link' => $custom_linkurl, 'custom_description' => $custom_description, 'menu_icon' => $icon, 'link_type' => $linktype, 'menu_id' => $menu_id_in_edit  )); 	
	 						$lastid = $wpdb->insert_id;
	 						
	 						//GET the correct parent record
	 						$parentrecords = $wpdb->get_results("SELECT id FROM ".$table_name." WHERE position='".($parent_id)."' AND menu_id='".$menu_id_in_edit."'");
	 						
		 					if ($parentrecords > 0)
		 					{
		 						foreach ($parentrecords as $parentrecord)
		 						{
		 							$parent_id_update = $parentrecord->id;
		 						}
		 					}
		 					//UPDATE menu item record with correct parent
		 					$wpdb->update( $table_name, array( 'parent_id' => $parent_id_update ), array( 'id' => $lastid, 'menu_id' => $menu_id_in_edit ));
	 					}
 					}
 				}
 				//DISPLAY SUCCESS MESSAGE IF POST CORRECT
 				$messagesdiv = '<div id="message" class="updated fade below-h2"><p>'.$themename.'s Custom Menu has been updated!</p></div>';
	 				
 			}
		}
 		
 		//DISPLAY Custom Navigation
 		?>
		<div id="pages-left">
			<div class="inside">
			<h2 class="maintitle"><img class="logo" src="<?php bloginfo('template_directory'); ?>/functions/images/logo.png" alt="Woothemes" />Custom Navigation</h2>
			<?php
				
				//CHECK if custom menu has been enabled
				$enabled_menu = get_option('woo_custom_nav_menu');
			    $checked = strtolower($enabled_menu);
	
				if ($checked == 'true') {
				} else {
					echo '<div id="message-enabled" class="error fade below-h2"><p><strong>The Custom Menu has not been Enabled yet. Please enable it in order to use it --------></strong></p></div>';
				}
				
				
			?>
			<?php echo $messagesdiv; ?>
			<form onsubmit="updatepostdata()" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post"  enctype="multipart/form-data">
			
			<input type="hidden" name="licount" id="licount" value="0" />
			<input type="hidden" name="menu_id_in_edit" id="menu_id_in_edit" value="<?php echo $menu_selected_id; ?>" />
			
			<div class="sidebar-name">
			
				<div class="sidebar-name-arrow">
					<br/>
				</div>
				<?php 		
				
				//CHECK for existing woo custom menu	    		
	 			$table_name_menus = $wpdb->prefix . "woo_custom_nav_menus";
	 			if ($menu_id_in_edit > 0)
	 			{
	 				$custom_menu_name = $wpdb->get_results("SELECT menu_name FROM ".$table_name_menus." WHERE id='".$menu_id_in_edit."'");
	 			}
	 			else {
	 				$custom_menu_name = $wpdb->get_results("SELECT menu_name FROM ".$table_name_menus." WHERE id='".$menu_selected_id."'");
	 			}
	 			
	 			//Menu title 		
	    		?>
				<h3><?php echo $custom_menu_name[0]->menu_name; ?></h3>
				
			</div>
			
			<div id="nav-container">
				<ul id="custom-nav">
				
					<?php
						//DISPLAY existing menu
						if ($custom_nav_exists > 0) 
						{
							//SET output type
							$outputtype = "backend";
							//Outputs menu	
							if ($menu_id_in_edit > 0)
							{
								//MAIN OUTPUT FUNCTION
								woo_custom_navigation_output($outputtype, $custom_menu_name[0]->menu_name, $menu_id_in_edit);
							}
							else 
							{
								//MAIN OUTPUT FUNCTION
								woo_custom_navigation_output($outputtype, $custom_menu_name[0]->menu_name, $menu_selected_id);
							}
							
						}
						//DISPLAY default menu
						else 
						{	
							//Outputs default Pages
							$intCounter = woo_get_pages(0,'menu');			
							//Outputs default Categories
							$intCounter = woo_get_categories($intCounter,'menu');
						}				
					?>
				
				</ul>
			</div><!-- /#nav-container -->
			
			<p class="submit">
			
			<script type="text/javascript">
				updatepostdata();       		
			</script>
			
			<input id="save_bottom" name="save_bottom" type="submit" value="Save All Changes" /></p>
			</div><!-- /.inside -->
		</div>
		
		<div id="menu-right">
		
			<h2 class="heading">Options</h2>
			
			<div class="widgets-holder-wrap">
				<div class="sidebar-name">
					<div class="sidebar-name-arrow"></div>
					<h3>Setup Custom Menu</h3>
				</div>
				<div class="widget-holder">
						
					<?php
			    	
			    	//SETUP Woo Custom Menu
			    	
					$enabled_menu = get_option('woo_custom_nav_menu');
					   
			    	$checked = strtolower($enabled_menu);
    	
			    	?>
			    	
			    	<span >
			    		<label>Enable</label><input type="radio" name="enable_woo_menu" value="true" <?php if ($checked=='true') { echo 'checked="checked"'; } ?> />
			    		<label>Disable</label><input type="radio" name="enable_woo_menu" value="false" <?php if ($checked=='true') { } else { echo 'checked="checked"'; } ?> />
					</span><!-- /.checkboxes -->				
						
					<input id="set_woo_menu" type="submit" value="Set Menu" name="set_woo_menu" class="button" />
					
					<div class="fix"></div>
				</div>
			</div><!-- /.widgets-holder-wrap -->
			
			<div class="widgets-holder-wrap">
				<div class="sidebar-name">
					<div class="sidebar-name-arrow"></div>
					<h3>Menu Selector</h3>
				</div>
				<div class="widget-holder">
						
					<?php
					
					//Get Menu Items for SELECT OPTIONS 	
					$table_name_custom_menus = $wpdb->prefix . "woo_custom_nav_menus";
	 				$custom_menu_records = $wpdb->get_results("SELECT id,menu_name FROM ".$table_name_custom_menus);
	 			
	    			?>
				
					<select id="menu_select" name="menu_select">
						<?php 
						
						//DISPLAY SELECT OPTIONS
						foreach ($custom_menu_records as $custom_menu_record)
						{
							if (($menu_id_in_edit == $custom_menu_record->id) || ($menu_selected_id == $custom_menu_record->id)) {
								$selected_option = 'selected="selected"';
							}
							else {
								$selected_option = '';
							}
							?>
							<option value="<?php echo $custom_menu_record->id; ?>" <?php echo $selected_option; ?>><?php echo $custom_menu_record->menu_name; ?></option>
							<?php
							
						}
						?>
					</select>
					
					<input id="switch_menu" type="submit" value="Switch" name="switch_menu" class="button" />
					<input id="add_menu_name" name="add_menu_name" type="text" value=""  />
					<input id="add_menu" type="submit" value="Add Menu" name="add_menu" class="button" />
						
					<div class="fix"></div>
				</div>
			</div><!-- /.widgets-holder-wrap -->
			<?php $advanced_option_descriptions = get_option('woo_settings_custom_nav_advanced_options'); ?>
			<div class="widgets-holder-wrap" style="display:none;">
				<div class="sidebar-name">
					<div class="sidebar-name-arrow"></div>
					<h3>Top Level Menu Descriptions</h3>
				</div>
				<div class="widget-holder">	
					<span>Display Descriptions in Top Level Menu?</span>
			
					<?php
			    	
			    	//UPDATE and DISPLAY Menu Description Option
			    	if ($_POST['menu-descriptions'])
			    	{
			    		
						if ($_POST['switch_menu']) {
							
						}
						else {
							$menu_options_to_edit = $_POST['menu_id_in_edit'];
			    			update_option('woo_settings_custom_nav_'.$menu_options_to_edit.'_descriptions',$_POST['menu-descriptions']);	
						}
			    		
			    	}
			    	
			    	if ($menu_id_in_edit > 0)
					{
						$checkedraw = get_option('woo_settings_custom_nav_'.$menu_id_in_edit.'_descriptions');
					}
					else {
						$checkedraw = get_option('woo_settings_custom_nav_'.$menu_selected_id.'_descriptions');
					}
			    
			    	$checked = strtolower($checkedraw);
			    	
			    	if ($advanced_option_descriptions == 'no')
			    	{
			    		$checked = 'no';
			    	}
			    	
			    	?>
			    	
			    	<span class="checkboxes">
			    		<label>Yes</label><input type="radio" name="menu-descriptions" value="yes" <?php if ($checked=='yes') { echo 'checked="checked"'; } ?> />
			    		<label>No</label><input type="radio" name="menu-descriptions" value="no" <?php if ($checked=='yes') { } else { echo 'checked="checked"'; } ?> />
					</span><!-- /.checkboxes -->
			    	</form>
					<div class="fix"></div>
				</div>
			</div><!-- /.widgets-holder-wrap -->
			
			<div class="widgets-holder-wrap">
				<div class="sidebar-name">
					<div class="sidebar-name-arrow"></div>
					<h3>Add an Existing Page</h3>
				</div>
				<div class="widget-holder">
					
					<?php
					
					$pages_args = array(
		    		'child_of' => 0,
					'sort_order' => 'ASC',
					'sort_column' => 'post_title',
					'hierarchical' => 1,
					'exclude' => '',
					'include' => '',
					'meta_key' => '',
					'meta_value' => '',
					'authors' => '',
					'parent' => -1,
					'exclude_tree' => '',
					'number' => '',
					'offset' => 0 );
	
					//GET all pages		
					$pages_array = get_pages($pages_args);
				
					//CHECK if pages exist
					if ($pages_array)
					{
						foreach ($pages_array as $post)
						{
							//Add page name to 
							$page_name .= $post->post_title.'|';
						}
					}
					else
					{
						$page_name = "No pages available";
					}
						
					?>
					
					<script>
  						$(document).ready(function(){

							//GET PHP pages
    						var dataposts = "<?php echo $page_name; ?>".split("|");
						
							//Set autocomplete
							$("#page-search").autocomplete(dataposts);
						
							//Handle autocomplete result
							$("#page-search").result(function(event, data, formatted) {
    						
    							$("#existing-pages dt:contains('" + data + "')").css("display", "block");
    						
    							$('#show-pages').hide();
    							$('#hide-pages').show();
    						
							});
					
 						});
  					</script>


					<input type="text" onfocus="jQuery('#page-search').attr('value','');" id="page-search" value="Search Pages" /> 
					
					<a id="show-pages" style="cursor:pointer;" onclick="jQuery('#page-search').attr('value','');jQuery('#existing-pages dt').css('display','block');jQuery('#show-pages').hide();jQuery('#hide-pages').show();">View All</a> 
					<a id="hide-pages" style="cursor:pointer;" onclick="jQuery('#page-search').attr('value','Search Pages');jQuery('#existing-pages dt').css('display','none');jQuery('#show-pages').show();jQuery('#hide-pages').hide();">Hide All</a>
					
					<script type="text/javascript">
					
						jQuery('#hide-pages').hide();
					
					</script>
					
					<ul id="existing-pages" class="list">
						<?php
							//Get default Pages
							$intCounter = woo_get_pages($intCounter,'default');
						?>
					</ul>
				</div>
			</div><!-- /.widgets-holder-wrap -->
			
			<div class="widgets-holder-wrap">
				<div class="sidebar-name">
					<div class="sidebar-name-arrow"></div>
					<h3>Add an Existing Category</h3>
				</div>
				<div class="widget-holder">
					
					<?php
					
					//Custom GET categories query
					$categories = $wpdb->get_results("SELECT term_id FROM $wpdb->term_taxonomy WHERE taxonomy = 'category' ORDER BY term_id ASC");
					
					//CHECK for results
					if ($categories)
					{
						foreach($categories as $category) 
						{ 
							$cat_id = $category->term_id;
				
							$cat_args=array(
							 	'orderby' => 'name',
							  	'include' => $cat_id,
							  	'hierarchical' => 1,
						  		'order' => 'ASC'
				  			);
				  			
				  			$category_names=get_categories($cat_args);
							
							//Add category name to data string
							$cat_name .= $category_names[0]->name.'|';
							
				  		}
				  	}
				  	else
					{
						$cat_name = "No categories available";
					}
				  
					?>

					<script>
  						$(document).ready(function(){

							//GET PHP categories
    						var datacats = "<?php echo $cat_name; ?>".split("|");
							
							//Set autocomplete
							$("#cat-search").autocomplete(datacats);
						
							//Handle autocomplete result
							$("#cat-search").result(function(event, data, formatted) {
    						
    							$("#existing-categories dt:contains('" + data + "')").css("display", "block");
    						   						
    							$('#show-cats').hide();
    							$('#hide-cats').show();
    						
							});
					
 						});
  					</script>


					<input type="text" onfocus="jQuery('#cat-search').attr('value','');" id="cat-search" value="Search Categories" /> 
					
					<a id="show-cats" style="cursor:pointer;" onclick="jQuery('#cat-search').attr('value','');jQuery('#existing-categories dt').css('display','block');jQuery('#show-cats').hide();jQuery('#hide-cats').show();">View All</a> 
					<a id="hide-cats" style="cursor:pointer;" onclick="jQuery('#cat-search').attr('value','Search Categories');jQuery('#existing-categories dt').css('display','none');jQuery('#show-cats').show();jQuery('#hide-cats').hide();">Hide All</a>
					
					<script type="text/javascript">
					
						jQuery('#hide-cats').hide();
					
					</script>
					
					<ul id="existing-categories" class="list">
            			<?php
						 	//Get default Categories
            				$intCounter = woo_get_categories($intCounter,'default'); 				
						?>
       				</ul>
					
				</div>
			</div><!-- /.widgets-holder-wrap -->
			
			<div class="widgets-holder-wrap">
				<div class="sidebar-name">
					<div class="sidebar-name-arrow"></div>
					<h3>Add a Custom Url</h3>
				</div>
				<div class="widget-holder">
					<input id="custom_menu_item_url" type="text" value="http://"  />
					<label>URL</label><br />
           			<?php $templatedir = get_bloginfo('template_directory'); ?>
            		<input type="hidden" id="templatedir" value="<?php echo $templatedir; ?>" />
            		<input id="custom_menu_item_name" type="text" value="Menu Item" onfocus="jQuery('#custom_menu_item_name').attr('value','');"  />
            		<label>Menu Text</label><br />
           			<input id="custom_menu_item_description" type="text" value="A description" <?php if ($advanced_option_descriptions == 'no') { ?>style="display:none;"<?php } ?> onfocus="jQuery('#custom_menu_item_description').attr('value','');" />
           			<label <?php if ($advanced_option_descriptions == 'no') { ?>style="display:none;"<?php } ?> >Description</label>
           			<a class="addtomenu" onclick="appendToList('<?php echo $templatedir; ?>','Custom','','','','0');jQuery('#custom_menu_item_name').attr('value','Menu Item');jQuery('#custom_menu_item_description').attr('value','A description');">Add to menu</a>
					<div class="fix"></div>
				</div>
			</div><!-- /.widgets-holder-wrap -->
			
       </div>
    </div>
    
    <script type="text/javascript">
		document.getElementById('pages-left').style.display='block';
		document.getElementById('menu-right').style.display='block';
		document.getElementById('no-js').style.display='none';
	</script>

<?php

}




/*-----------------------------------------------------------------------------------*/
/* WooThemes Custom Navigation Functions */
/* woo_custom_navigation_output() displays the menu in the back/frontend
/* woo_custom_navigation_sub_items() is a recursive sub menu item function
/* woo_get_pages()
/* woo_get_categories()
/* woo_custom_navigation_default_sub_items() is a recursive sub menu item function
/*-----------------------------------------------------------------------------------*/

//Main Output Function
function woo_custom_navigation_output($outputtype, $menu_name, $menu_id = 0, $widgetdescriptions = 0) {

		global $wpdb;
		$woo_custom_nav_menu_id = 0;
		$table_name = $wpdb->prefix . "woo_custom_nav_records";
		
		//Override for menu descriptions
		$advanced_option_descriptions = get_option('woo_settings_custom_nav_advanced_options');
		if ($advanced_option_descriptions == 'no')
		{
			$widgetdescriptions = 2;
		}
		
		//GET Menu Items
		//FRONTEND
		if ($outputtype == "frontend") 
		{
			$table_name_menus = $wpdb->prefix . "woo_custom_nav_menus";
			$woo_result = $wpdb->get_results("SELECT id FROM ".$table_name_menus." WHERE menu_name='".$menu_name."'");
			$woo_custom_nav_menu_id = $woo_result[0]->id;
			$woo_custom_nav_menu = $wpdb->get_results("SELECT id,post_id,parent_id,position,custom_title,custom_link,custom_description,menu_icon,link_type FROM ".$table_name." WHERE parent_id = '0' AND menu_id='".$woo_custom_nav_menu_id."' ORDER BY position ASC");
		}
		//BACKEND
		else {
			$woo_custom_nav_menu = $wpdb->get_results("SELECT id,post_id,parent_id,position,custom_title,custom_link,custom_description,menu_icon,link_type FROM ".$table_name." WHERE parent_id = '0' AND menu_id='".$menu_id."' ORDER BY position ASC");
		}
		
		//DISPLAY Loop
		foreach ($woo_custom_nav_menu as $woo_custom_nav_menu_items) {
			
			//PREPARE Menu Data
			//Page Menu Item
			if ($woo_custom_nav_menu_items->link_type == 'page')
			{
				$link = get_permalink($woo_custom_nav_menu_items->post_id);
				$title = get_the_title($woo_custom_nav_menu_items->post_id);
				$description = get_post_meta($woo_custom_nav_menu_items->post_id, 'page-description', true);
			}
			//Category Menu Item
			elseif ($woo_custom_nav_menu_items->link_type == 'category') 
			{
				$link = get_category_link($woo_custom_nav_menu_items->post_id);
				$title_raw = get_categories('include='.$woo_custom_nav_menu_items->post_id);
				$title =  $title_raw[0]->cat_name;	
				$description = strip_tags(category_description($woo_custom_nav_menu_items->post_id));
			}
			//Custom Menu Item
			else 
			{
				$link = $woo_custom_nav_menu_items->custom_link;
				$title =  $woo_custom_nav_menu_items->custom_title;
				$description = $woo_custom_nav_menu_items->custom_description;
			}
			
			//List Items
			?><li id="menu-<?php echo $woo_custom_nav_menu_items->position; ?>" value="<?php echo $woo_custom_nav_menu_items->position; ?>"><?php 
				
					//FRONTEND Link
					if ($outputtype == "frontend")
					{
						?><a href="<?php echo $link; ?>"><?php echo $title; ?><?php 
						
							if ( $advanced_option_descriptions == 'no' ) 
							{ 
								// 2 widget override do NOT display descriptions
								// 1 widget override display descriptions
								// 0 widget override not set
								if (($widgetdescriptions == 1) || ($widgetdescriptions == 0) )
								{
									?><span class="nav-description"><?php echo $description; ?></span><?php
								} 
								elseif ($widgetdescriptions == 2)
								{ }
								else
								{ }
							} 
							else 
							{
								// 2 widget override do NOT display descriptions
								// 1 widget override display descriptions
								// 0 widget override not set
								if ($widgetdescriptions == 1)
								{
									?><span class="nav-description"><?php echo $description; ?></span><?php
								} 
								elseif (($widgetdescriptions == 2) || ($widgetdescriptions == 0))
								{ }
								else 
								{ }
							}
							
						?></a><?php 
					}
					//BACKEND draggable and droppable elements
					elseif ($outputtype == "backend")
					{
						?>
						
						<dl>
							<dt>
								<span class="title"><?php echo $title; ?></span>
								<span class="controls">
								<span class="type"><?php echo $woo_custom_nav_menu_items->link_type; ?></span>
								<a id="remove<?php echo $woo_custom_nav_menu_items->position; ?>" onclick="removeitem(<?php echo $woo_custom_nav_menu_items->position; ?>)" value="<?php echo $woo_custom_nav_menu_items->position; ?>"><img class="remove" alt="Remove from Custom Menu" title="Remove from Custom Menu" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-close.png" /></a>
								<a target="_blank" href="<?php echo $link; ?>"><img alt="View Page" title="View Page" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-viewpage.png" /></a>
								</span>
							</dt>
						</dl>
						
						<a><span class=""></span></a>
						<input type="hidden" name="dbid<?php echo $woo_custom_nav_menu_items->position; ?>" id="dbid<?php echo $woo_custom_nav_menu_items->position; ?>" value="<?php echo $woo_custom_nav_menu_items->id; ?>" />
						<input type="hidden" name="postmenu<?php echo $woo_custom_nav_menu_items->position; ?>" id="postmenu<?php echo $woo_custom_nav_menu_items->position; ?>" value="<?php echo $woo_custom_nav_menu_items->post_id; ?>" />
						<input type="hidden" name="parent<?php echo $woo_custom_nav_menu_items->position; ?>" id="parent<?php echo $woo_custom_nav_menu_items->position; ?>" value="0" />
						<input type="hidden" name="title<?php echo $woo_custom_nav_menu_items->position; ?>" id="title<?php echo $woo_custom_nav_menu_items->position; ?>" value="<?php echo $title; ?>" />
						<input type="hidden" name="linkurl<?php echo $woo_custom_nav_menu_items->position; ?>" id="linkurl<?php echo $woo_custom_nav_menu_items->position; ?>" value="<?php echo $link; ?>" />
						<input type="hidden" name="description<?php echo $woo_custom_nav_menu_items->position; ?>" id="description<?php echo $woo_custom_nav_menu_items->position; ?>" value="<?php echo $description; ?>" />
						<input type="hidden" name="icon<?php echo $woo_custom_nav_menu_items->position; ?>" id="icon<?php echo $woo_custom_nav_menu_items->position; ?>" value="0" />
						<input type="hidden" name="position<?php echo $woo_custom_nav_menu_items->position; ?>" id="position<?php echo $woo_custom_nav_menu_items->position; ?>" value="<?php echo $woo_custom_nav_menu_items->position; ?>" />
						<input type="hidden" name="linktype<?php echo $woo_custom_nav_menu_items->position; ?>" id="linktype<?php echo $woo_custom_nav_menu_items->position; ?>" value="<?php echo $woo_custom_nav_menu_items->link_type; ?>" />
						
						<?php 
					}
					
					//DISPLAY menu sub items 
					if ($woo_custom_nav_menu_items->parent_id == 0) 
					{
						//FRONTEND
						if ($outputtype == "frontend") 
						{
							//Recursive function
							$intj = woo_custom_navigation_sub_items($woo_custom_nav_menu_items->id,$woo_custom_nav_menu_items->type,$table_name,$outputtype,$woo_custom_nav_menu_id);
						}
						//BACKEND
						else 
						{
							//Recursive function
							$intj = woo_custom_navigation_sub_items($woo_custom_nav_menu_items->id,$woo_custom_nav_menu_items->type,$table_name,$outputtype,$menu_id);
						}
					}
					else 
					{
						
					}
			?></li>
			<?php 
		}
}

//RECURSIVE Sub Menu Items
function woo_custom_navigation_sub_items($post_id,$type,$table_name,$outputtype,$menu_id = 0) {

	global $wpdb;
	
	//GET sub menu items
	$woo_custom_nav_menu = $wpdb->get_results("SELECT id,post_id,parent_id,position,custom_title,custom_link,custom_description,menu_icon,link_type FROM ".$table_name." WHERE parent_id = '".$post_id."' AND menu_id='".$menu_id."' ORDER BY position ASC");
	
	if (empty($woo_custom_nav_menu))
	{
	
	}
	else
	{
		?><ul id="sub-custom-nav">
		<?php
		
		//DISPLAY Loop
		foreach ($woo_custom_nav_menu as $sub_item) 
		{
			//Figure out where the menu item sits
			$counter=$sub_item->position;
			
			//Prepare Menu Data
			//Category Menu Item
			if ($sub_item->link_type == 'category') 
			{
				$link = get_category_link($sub_item->post_id);
				$title_raw = get_categories('include='.$sub_item->post_id);
				$title =  $title_raw[0]->cat_name;
				$parent_id = $sub_item->parent_id;
				$post_id = $sub_item->post_id;
				$description = strip_tags(category_description($sub_item->post_id));
			}
			//Page Menu Item
			elseif ($sub_item->link_type == 'page')
			{
				$link = get_permalink($sub_item->post_id);
				$title = get_the_title($sub_item->post_id);
				$parent_id = $sub_item->parent_id;
				$post_id = $sub_item->post_id;
				$description = get_post_meta($woo_custom_nav_menu_items->post_id, 'page-description', true);
			}
			//Custom Menu Item
			else
			{
				$link = $sub_item->custom_link;
				$title = $sub_item->custom_title;
				$parent_id = $sub_item->parent_id;
				$post_id = $sub_item->post_id;
				$description = $sub_item->custom_description;
			}
		
			//List Items
			?><li id="menu-<?php echo $counter; ?>" value="<?php echo $counter; ?>"><?php 
						//FRONTEND
						if ($outputtype == "frontend")
						{
							?><a href="<?php echo $link; ?>"><?php echo $title; ?></a><?php 
						}
						//BACKEND
						elseif ($outputtype == "backend")
						{
							?>
							<dl>
							<dt>
								<span class="title"><?php echo $title; ?></span>
								<span class="controls">
								<span class="type"><?php echo $sub_item->link_type; ?></span>
								<a id="remove<?php echo $counter; ?>" onclick="removeitem(<?php echo $counter; ?>)" value="<?php echo $counter; ?>"><img class="remove" alt="Remove from Custom Menu" title="Remove from Custom Menu" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-close.png" /></a>
								<a target="_blank" href="<?php echo $link; ?>"><img alt="View Page" title="View Page" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-viewpage.png" /></a>
								</span>
							</dt>
							</dl>
							<a class="hide" href="<?php echo $link; ?>"><?php echo $title; ?></a>
							<input type="hidden" name="dbid<?php echo $counter; ?>" id="dbid<?php echo $counter; ?>" value="<?php echo $sub_item->id; ?>" />
							<input type="hidden" name="postmenu<?php echo $counter; ?>" id="postmenu<?php echo $counter; ?>" value="<?php echo $post_id; ?>" />
							<input type="hidden" name="parent<?php echo $counter; ?>" id="parent<?php echo $counter; ?>" value="<?php echo $parent_id; ?>" />
							<input type="hidden" name="title<?php echo $counter; ?>" id="title<?php echo $counter; ?>" value="<?php echo $title; ?>" />
							<input type="hidden" name="linkurl<?php echo $counter; ?>" id="linkurl<?php echo $counter; ?>" value="<?php echo $link; ?>" />
							<input type="hidden" name="description<?php echo $counter; ?>" id="description<?php echo $counter; ?>" value="<?php echo $description; ?>" />
							<input type="hidden" name="icon<?php echo $counter; ?>" id="icon<?php echo $counter; ?>" value="0" />
							<input type="hidden" name="position<?php echo $counter; ?>" id="position<?php echo $counter; ?>" value="<?php echo $counter; ?>" />
							<input type="hidden" name="linktype<?php echo $counter; ?>" id="linktype<?php echo $counter; ?>" value="<?php echo $sub_item->link_type; ?>" />
							
							<?php 
						}
						
						//Do recursion
						woo_custom_navigation_sub_items($sub_item->id,$sub_item->link_type,$table_name,$outputtype,$menu_id); 
			?></li>
			<?php 
	
		} 
	
	?></ul>
	<?php 
	
	} 
	
	return $parent_id;
 
}


//Outputs All Pages and Sub Items
function woo_get_pages($counter,$type) {

	$pages_args = array(
		    'child_of' => 0,
			'sort_order' => 'ASC',
			'sort_column' => 'post_title',
			'hierarchical' => 1,
			'exclude' => '',
			'include' => '',
			'meta_key' => '',
			'meta_value' => '',
			'authors' => '',
			'parent' => -1,
			'exclude_tree' => '',
			'number' => '',
			'offset' => 0 );
	
	//GET all pages		
	$pages_array = get_pages($pages_args);
	
	$intCounter = $counter;
	$parentli = $intCounter;
	
	if ($pages_array)
	{
		//DISPLAY Loop
		foreach ($pages_array as $post)
		{
	
			if ($post->post_parent == 0)
			{
				//Custom Menu
				if ($type == 'menu')
				{
					?>
					
					<li id="menu-<?php echo $intCounter; ?>" value="<?php echo $intCounter; ?>">
				
						<dl>
						<dt>
						<span class="title"><?php echo $post->post_title; ?></span>
						<span class="controls">
							<a id="remove<?php echo $intCounter; ?>" onclick="removeitem(<?php echo $intCounter; ?>)" value="<?php echo $intCounter; ?>">
								<img class="remove" alt="Remove from Custom Menu" title="Remove from Custom Menu" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-close.png" />
							</a>
							<a target="_blank" href="<?php echo get_permalink($post->ID); ?>">
								<img alt="View Page" title="View Page" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-viewpage.png" />
							</a>
						</span>
						
						</dt>
						</dl>
						<a class="hide" href="<?php echo get_permalink($post->ID); ?>"><span class="title"><?php echo $post->post_title; ?></span>
		    	    	</a>
		    	    	<input type="hidden" name="postmenu<?php echo $intCounter; ?>" id="postmenu<?php echo $intCounter; ?>" value="<?php echo $post->ID; ?>" />
						<input type="hidden" name="parent<?php echo $intCounter; ?>" id="parent<?php echo $intCounter; ?>" value="0" />
						<input type="hidden" name="title<?php echo $intCounter; ?>" id="title<?php echo $intCounter; ?>" value="<?php echo $post->post_title; ?>" />
						<input type="hidden" name="linkurl<?php echo $intCounter; ?>" id="linkurl<?php echo $intCounter; ?>" value="<?php echo get_permalink($post->ID); ?>" />
						<input type="hidden" name="description<?php echo $intCounter; ?>" id="description<?php echo $intCounter; ?>" value="0" />
						<input type="hidden" name="icon<?php echo $intCounter; ?>" id="icon<?php echo $intCounter; ?>" value="0" />
						<input type="hidden" name="position<?php echo $intCounter; ?>" id="position<?php echo $intCounter; ?>" value="<?php echo $intCounter; ?>" />
						<input type="hidden" name="linktype<?php echo $intCounter; ?>" id="linktype<?php echo $intCounter; ?>" value="page" />
						
						<?php $parentli = $post->ID; ?>
						<?php $intCounter++; ?>			                
						<?php
						
							//Recursive function
							$intCounter = woo_custom_navigation_default_sub_items($post->ID, $intCounter, $parentli, 'pages', 'menu');
						
						?>
					
					</li>
					
					<?php 
					
				}
				//Sidebar Menu
				elseif ($type == 'default')
				{
					?>
					 
					 <li>
				        <dl>
				        <dt>
				        <?php
				        	$post_text = $post->post_title;
				        	$post_url = get_permalink($post->ID);
				        	$post_id = $post->ID;
				        	$post_parent_id = $post->post_parent;
				        ?>
				        <?php $templatedir = get_bloginfo('template_directory'); ?>
				        <span class="title"><?php echo $post->post_title; ?></span> <a onclick="appendToList('<?php echo $templatedir; ?>','Page','<?php echo $post_text; ?>','<?php echo $post_url; ?>','<?php echo $post_id; ?>','<?php echo $post_parent_id ?>')" name="<?php echo $post->post_title; ?>" value="<?php echo get_permalink($post->ID); ?>"><img alt="Add to Custom Menu" title="Add to Custom Menu" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-add.png" /></a></dt>
				        </dl>
				        <?php $parentli = $post->ID; ?>
						<?php $intCounter++; ?>			    
				        <?php
						
							//Recursive function
							$intCounter = woo_custom_navigation_default_sub_items($post_id, $intCounter, $parentli, 'pages', 'default');
						
						 ?>
					        
					</li>
	
					<?php 
				
				}
				else
				{
				
				}	
			} 
		} 
	}
	else 
	{
		echo 'Not Found';
	}

	return $intCounter;
}

//Outputs All Categories and Sub Items
function woo_get_categories($counter, $type) {

	$category_args = array(
			'type'                     => 'post',
			'child_of'                 => 0,
			'orderby'                  => 'name',
			'order'                    => 'ASC',
			'hide_empty'               => true,
			'include_last_update_time' => false,
			'hierarchical'             => 1,
			'exclude'                  => '',
			'include'                  => '',
			'number'                   => '',
			'pad_counts'               => false );
	
	$intCounter = $counter;	
	
	//GET all categories	
	$categories_array = get_categories($categories_args);
	
	if ($categories_array)
	{
		//DISPLAY Loop
		foreach ($categories_array as $cat_item)
		{

			if ($cat_item->parent == 0)
			{
				//Custom Menu
				if ($type == 'menu')
				{
					?>
	    
			    	<li id="menu-<?php echo $intCounter; ?>" value="<?php echo $intCounter; ?>">
			    		<dl>
			            <dt>
			            	<span class="title"><?php echo $cat_item->cat_name; ?></span>
							<span class="controls">
							<a id="remove<?php echo $intCounter; ?>" onclick="removeitem(<?php echo $intCounter; ?>)" value="<?php echo $intCounter; ?>">
								<img class="remove" alt="Remove from Custom Menu" title="Remove from Custom Menu" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-close.png" />
							</a>
							<a target="_blank" href="<?php echo get_category_link($cat_item->cat_ID); ?>">
								<img alt="View Page" title="View Page" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-viewpage.png" />
							</a>
							</span>
					
			            </dt>
			            </dl>
			            <a class="hide" href="<?php echo get_category_link($cat_item->cat_ID); ?>"><span class="title"><?php echo $cat_item->cat_name; ?></span>
			            <?php 
			            $use_cats_raw = get_option('woo_settings_custom_nav_descriptions');
			   			$use_cats = strtolower($use_cats_raw);
			   			if ($use_cats == 'yes') { ?>
			            <br/> <span><?php echo $cat_item->category_description; ?></span>
			            <?php } ?>
			                    	</a>
			            <input type="hidden" name="postmenu<?php echo $intCounter; ?>" id="postmenu<?php echo $intCounter; ?>" value="<?php echo $cat_item->cat_ID; ?>" />
			            <input type="hidden" name="parent<?php echo $intCounter; ?>" id="parent<?php echo $intCounter; ?>" value="0" />
			            <input type="hidden" name="title<?php echo $intCounter; ?>" id="title<?php echo $intCounter; ?>" value="<?php echo $cat_item->cat_name; ?>" />
						<input type="hidden" name="linkurl<?php echo $intCounter; ?>" id="linkurl<?php echo $intCounter; ?>" value="<?php echo get_category_link($cat_item->cat_ID); ?>" />
						<input type="hidden" name="description<?php echo $intCounter; ?>" id="description<?php echo $intCounter; ?>" value="0" />
						<input type="hidden" name="icon<?php echo $intCounter; ?>" id="icon<?php echo $intCounter; ?>" value="0" />
						<input type="hidden" name="position<?php echo $intCounter; ?>" id="position<?php echo $intCounter; ?>" value="<?php echo $intCounter; ?>" />
						<input type="hidden" name="linktype<?php echo $intCounter; ?>" id="linktype<?php echo $intCounter; ?>" value="category" />
						
			            <?php $parentli = $cat_item->cat_ID; ?>
			            <?php $intCounter++; ?>			                
			           	<?php
						
							//Recursive function
							$intCounter = woo_custom_navigation_default_sub_items($cat_item->cat_ID, $intCounter, $parentli, 'categories','menu');
							
						?>
			            
			    	</li>
			    	
			    	<?php 
			    }
			    //Sidebar Menu
			    elseif ($type == 'default')
			    {
			    	?>
			    	<li>
						<dl>
						<dt>
						<?php
	        			$post_text = $cat_item->cat_name;
	        			$post_url = get_category_link($cat_item->cat_ID);
	        			$post_id = $cat_item->cat_ID;
	        			$post_parent_id = $cat_item->parent;
	        			?>
	        			<?php $templatedir = get_bloginfo('template_directory'); ?>
						<span class="title"><?php echo $cat_item->cat_name; ?></span> <a onclick="appendToList('<?php echo $templatedir; ?>','Category','<?php echo $post_text; ?>','<?php echo $post_url; ?>','<?php echo $post_id; ?>','<?php echo $post_parent_id ?>')" name="<?php echo $cat_item->cat_name; ?>" value="<?php echo get_category_link($cat_item->cat_ID);  ?>"><img alt="Add to Custom Menu" title="Add to Custom Menu"  src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-add.png" /></a> </dt>
						</dl>
						<?php $parentli = $cat_item->cat_ID; ?>
			            <?php $intCounter++; ?>		
						<?php 
							//Recursive function
							$intCounter = woo_custom_navigation_default_sub_items($cat_item->cat_ID, $intCounter, $parentli, 'categories','default');
						?>
						
					</li>
					
					<?php 
			    }	
			} 
		}
	}
	else 
	{
		echo 'Not Found';
	}
	
	return $intCounter;
}

//RECURSIVE Sub Menu Items of default categories and pages
function woo_custom_navigation_default_sub_items($childof, $intCounter, $parentli, $type, $outputtype) {

	$counter = $intCounter;
	
	//Custom Menu
	if ($outputtype == 'menu') 
	{
		$sub_args = array(
		'child_of' => $childof,
		'parent' => $childof);
	}
	//Sidebar Menu
	elseif ($outputtype == 'default') 
	{
		$sub_args = array(
		'child_of' => $childof,
		'parent' => $childof);
	}
	else 
	{
		
	}
	
	//Get Sub Category Items			
	if ($type == 'categories')
	{
		$sub_array = get_categories($sub_args);	
	}
	//Get Sub Page Items
	elseif ($type == 'pages')
	{
		$sub_array = get_pages($sub_args);
	}
	
	
	if ($sub_array)
	{
		?>
		
		<ul id="sub-custom-nav-<?php echo $type ?>">
		
		<?php
		//DISPLAY Loop
		foreach ($sub_array as $sub_item)
		{
			//Prepare Menu Data
			//Category Menu Item
			if ($type == 'categories') 
			{
				$link = get_category_link($sub_item->cat_ID);
				$title = $sub_item->cat_name;
				$parent_id = $sub_item->cat_ID;
				$itemid = $sub_item->cat_ID;
				$linktype = 'category';
				$appendtype= 'Category';
			}
			//Page Menu Item
			elseif ($type == 'pages')
			{
				$link = get_permalink($sub_item->ID);
				$title = $sub_item->post_title;
				$parent_id = $sub_item->ID;
				$linktype = 'page';
				$itemid = $sub_item->ID;
				$appendtype= 'Page';
			}
			//Custom Menu Item
			else 
			{
				$linktype = 'custom';
				$appendtype= 'Custom';
			}
			
			//Custom Menu
			if ($outputtype == 'menu')
			{
				?>
				<li id="menu-<?php echo $counter; ?>" value="<?php echo $counter; ?>">
					<dl>
					<dt>
						<span class="title"><?php echo $title; ?></span>
							<span class="controls">
								<a id="remove<?php echo $counter; ?>" onclick="removeitem(<?php echo $counter; ?>)" value="<?php echo $counter; ?>">
									<img class="remove" alt="Remove from Custom Menu" title="Remove from Custom Menu" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-close.png" />
								</a>
								<a target="_blank" href="<?php echo $link; ?>">
									<img alt="View Page" title="View Page" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-viewpage.png" />
								</a>
						</span>
			
					</dt>
					</dl>
					<a class="hide" href="<?php echo $link; ?>"><?php echo $title; ?></a>
					<input type="hidden" name="dbid<?php echo $counter; ?>" id="dbid<?php echo $counter; ?>" value="<?php echo $sub_item->id; ?>" />
					<input type="hidden" name="postmenu<?php echo $counter; ?>" id="postmenu<?php echo $counter; ?>" value="<?php echo $parent_id; ?>" />
					<input type="hidden" name="parent<?php echo $counter; ?>" id="parent<?php echo $counter; ?>" value="<?php echo $parentli; ?>" />
					<input type="hidden" name="title<?php echo $counter; ?>" id="title<?php echo $counter; ?>" value="<?php echo $title; ?>" />
					<input type="hidden" name="linkurl<?php echo $counter; ?>" id="linkurl<?php echo $counter; ?>" value="<?php echo $link; ?>" />
					<input type="hidden" name="description<?php echo $counter; ?>" id="description<?php echo $counter; ?>" value="0" />
					<input type="hidden" name="icon<?php echo $counter; ?>" id="icon<?php echo $counter; ?>" value="0" />
					<input type="hidden" name="position<?php echo $counter; ?>" id="position<?php echo $counter; ?>" value="<?php echo $counter; ?>" />
					<input type="hidden" name="linktype<?php echo $counter; ?>" id="linktype<?php echo $counter; ?>" value="<?php echo $linktype; ?>" />
					<?php $counter++; ?>
					<?php 
						
						//Do recursion
						$counter = woo_custom_navigation_default_sub_items($parent_id, $counter, $parent_id, $type, 'menu'); 
						
					?>
					
				</li>
				<?php 
			}
			//Sidebar Menu
			elseif ($outputtype == 'default')
			{
					
				?>
				<li>
					<dl>
					<dt>
					
					<?php $templatedir = get_bloginfo('template_directory'); ?>
					<span class="title"><?php echo $title; ?></span> <a onclick="appendToList('<?php echo $templatedir; ?>','<?php echo $appendtype; ?>','<?php echo $title; ?>','<?php echo $link; ?>','<?php echo $itemid; ?>','<?php echo $parent_id ?>')" name="<?php echo $title; ?>" value="<?php echo $link; ?>"><img alt="Add to Custom Menu" title="Add to Custom Menu" src="<?php echo get_bloginfo('template_directory'); ?>/functions/images/ico-add.png" /></a> </dt>
					</dl>
					<?php 
					
						//Do recursion
						$counter = woo_custom_navigation_default_sub_items($itemid, $counter, $parent_id, $type, 'default');
						
					?>
				</li>
					  
				<?php 
			}
			
		}
		?>
		
		</ul>
		
	<?php 
	}
	
	return $counter;

}

/*-----------------------------------------------------------------------------------*/
/* Recursive get children */
/*-----------------------------------------------------------------------------------*/

function get_children_menu_elements($childof, $intCounter, $parentli, $type, $menu_id, $table_name) {

	$counter = $intCounter;
	
	global $wpdb;
	
	
	
	//Get Sub Category Items			
	if ($type == 'categories')
	{
		$sub_args = array(
			'child_of' => $childof,
			'hide_empty'  => false,
			'parent' => $childof);
		$sub_array = get_categories($sub_args);	
	}
	//Get Sub Page Items
	elseif ($type == 'pages')
	{
		$sub_args = array(
			'child_of' => $childof,
			'parent' => $childof);
	
		$sub_array = get_pages($sub_args);
		
	}
	else {
	
	}
	
	if ($sub_array)
	{
		//DISPLAY Loop
		foreach ($sub_array as $sub_item)
		{
			//Is child
			if (($sub_item->parent == $childof) || ($sub_item->post_parent == $childof))
			{
				//Prepare Menu Data
				//Category Menu Item
				if ($type == 'categories') 
				{
					$link = get_category_link($sub_item->cat_ID);
					$title = $sub_item->cat_name;
					$parent_id = $sub_item->category_parent;
					$itemid = $sub_item->cat_ID;
					$linktype = 'category';
					$appendtype= 'Category';
				}
				//Page Menu Item
				elseif ($type == 'pages')
				{
					$link = get_permalink($sub_item->ID);
					$title = $sub_item->post_title;
					$parent_id = $sub_item->post_parent;
					$linktype = 'page';
					$itemid = $sub_item->ID;
					$appendtype= 'Page';
				}
				//Custom Menu Item
				else 
				{
					$linktype = 'custom';
					$appendtype= 'Custom';
				}
				
				//CHECK for existing parent records
				//echo $parent_id;
				$woo_result = $wpdb->get_results("SELECT id FROM ".$table_name." WHERE post_id='".$parent_id."' AND link_type='".$linktype."' AND menu_id='".$menu_id."'");
				//print_r($woo_result);
				if ($woo_result > 0) {
					$parent_id = $woo_result[0]->id;
				}
				else {
					//$parent_id = 0;
				}
				
				//INSERT item
				$insert = "INSERT INTO ".$table_name." (position,post_id,parent_id,custom_title,custom_link,custom_description,menu_icon,link_type,menu_id) "."VALUES ('".$counter."','".$itemid."','".$parent_id."','".$title."','".$link."','','','".$linktype."','".$menu_id."')";
	  			$results = $wpdb->query( $insert );
	 
	  			$counter++;
	  			$counter = get_children_menu_elements($itemid, $counter, $parent_id, $type, $menu_id, $table_name);
			}	
			//Do nothing
			else {
			
			}
		}
	}
	return $counter;
}

/*---------------------------------------------------------------------------------*/
/* Woothemes Custom Navigation Menu Widget */
/*---------------------------------------------------------------------------------*/

class Woo_NavWidget extends WP_Widget {

	function Woo_NavWidget() {
		$widget_ops = array('description' => 'Use this widget to add one of your Woo Custom Navigation Menus as a widget.' );
		parent::WP_Widget(false, __('Woo - Custom Nav Menu', 'woothemes'),$widget_ops);      
	}

	function widget($args, $instance) {  
		$navmenu = $instance['navmenu'];
		$navtitle = $instance['navtitle'];
		$navdeveloper = strtolower($instance['navdeveloper']);
		$navdiv = strtolower($instance['navdiv']);
		$navul = strtolower($instance['navul']);
		$navdivid = $instance['navdivid'];
		$navdivclass = $instance['navdivclass'];
		$navulid = $instance['navulid'];
		$navulclass = $instance['navulclass'];
		
		//Override for menu descriptions
		$advanced_option_descriptions = get_option('woo_settings_custom_nav_advanced_options');
		if ($advanced_option_descriptions == 'no') 
		{  
			$navwidgetdescription = 2;
		} 
		else
		{
			$navwidgetdescription = $instance['navwidgetdescription'];
		}
		$menuexists = false;
		
		global $wpdb;
		
		//GET menu name
		if ($navmenu > 0)
		{
			$table_name_menus = $wpdb->prefix . "woo_custom_nav_menus";
			$woo_result = $wpdb->get_results("SELECT menu_name FROM ".$table_name_menus." WHERE id='".$navmenu."'");
			$woo_custom_nav_menu_name = $woo_result[0]->menu_name;
			$menuexists = true;
		}
		//Do nothing
		else 
		{
			$menuexists = false;
		}
		?>
		
		<?php 
			//DEVELOPER settings enabled
			if ($navdeveloper == 'yes') 
			{ 
				//DISPLAY Custom DIV
				if ($navdiv == 'yes') 
				{ 
					?>
					<div id="<?php echo $navdivid;  ?>" class="<?php echo $navdivclass; ?>">
					<?php 
				}
				//Do NOT display DIV
				else 
				{
					
				} 
				
			} 
			//DISPLAY default DIV
			else 
			{
				?>
				<div class="widget">
				<?php 
			}
		?>
		
			<h3><?php echo $navtitle; ?></h3>
			<?php 
			
			if ($menuexists) 
			{
				?>
        		<?php 
        		
        		//DEVELOPER settings enabled
				if ($navdeveloper == 'yes') 
				{ 
					//DISPLAY Custom UL
					if ($navul == 'yes') 
					{ 
						?>
						<ul id="<?php echo $navulid;  ?>" class="<?php echo $navulclass; ?>">
						<?php 
					}
					//Do NOT display UL
					else 
					{
						
					} 
					
				} 
				//DISPLAY default UL
				else 
				{
					?>
					<ul class="custom-nav">
					<?php 
				}
        		
        		?>
				
						<?php
							//DISPLAY custom navigation menu
							if (get_option('woo_custom_nav_menu') == 'true') {
        						woo_custom_navigation_output('frontend', ''.$woo_custom_nav_menu_name.'',0,$navwidgetdescription);
        					}				
						?>
				
				<?php 
				
					//DEVELOPER settings enabled
					if ($navdeveloper == 'yes') 
					{ 
						//DISPLAY Custom UL
						if ($navul == 'yes') 
						{ 
							?>
							</ul>
							<?php 
						}
						//Do NOT display UL
						else 
						{
							
						} 
						
					} 
					//DISPLAY default UL
					else 
					{
						?>
						</ul>
						<?php 
					}
					
				?>
			<?php
			}
			else
			{
				echo "You have not setup the custom navigation widget correctly, please check your settings in the backend.";
			}
			?>
		<?php 
			//DEVELOPER settings enabled
			if ($navdeveloper == 'yes') 
			{ 
				//DISPLAY Custom DIV
				if ($navdiv == 'yes') 
				{ 
					?>
					</div>
					<?php 
				}
				//Do NOT display DIV
				else 
				{
					
				} 
				
			} 
			//DISPLAY default DIV
			else 
			{
				?>
				</div>
				<?php 
			}
		?><!-- /#nav-container -->
			
			<?php
	}

	function update($new_instance, $old_instance) {                
		return $new_instance;
	}

	function form($instance) {        
		$navmenu = esc_attr($instance['navmenu']);
		$navtitle = esc_attr($instance['navtitle']);
		$navdeveloper = esc_attr($instance['navdeveloper']);
		$navdiv = esc_attr($instance['navdiv']);
		$navul = esc_attr($instance['navul']);
		$navdivid = esc_attr($instance['navdivid']);
		$navdivclass = esc_attr($instance['navdivclass']);
		$navulid = esc_attr($instance['navulid']);
		$navulclass = esc_attr($instance['navulclass']);
		$navwidgetdescription = esc_attr($instance['navwidgetdescription']);
				
		global $wpdb;
				
		//GET Menu Items for SELECT OPTIONS 	
		$table_name_custom_menus = $wpdb->prefix . "woo_custom_nav_menus";
		$custom_menu_records = $wpdb->get_results("SELECT id,menu_name FROM ".$table_name_custom_menus);
		
		//CHECK if menus exist
		if ($custom_menu_records > 0)
		{
		
			?>
			
			 <p>
	            <label for="<?php echo $this->get_field_id('navmenu'); ?>"><?php _e('Select Menu:','woothemes'); ?></label>
				
				<select id="<?php echo $this->get_field_id('navmenu'); ?>" name="<?php echo $this->get_field_name('navmenu'); ?>">
					<?php 
					
					//DISPLAY SELECT OPTIONS
					foreach ($custom_menu_records as $custom_menu_record)
					{
						if ($navmenu == $custom_menu_record->id) {
							$selected_option = 'selected="selected"';
						}
						else {
							$selected_option = '';
						}
						?>
						<option value="<?php echo $custom_menu_record->id; ?>" <?php echo $selected_option; ?>><?php echo $custom_menu_record->menu_name; ?></option>
						<?php
						
					}
					?>
				</select>
	
			</p>
			
			<p>
				
		        <label for="<?php echo $this->get_field_id('navtitle'); ?>"><?php _e('Title:','woothemes'); ?></label>
		    	<input type="text" name="<?php echo $this->get_field_name('navtitle'); ?>" value="<?php echo $navtitle; ?>" class="widefat" id="<?php echo $this->get_field_id('navtitle'); ?>" />
		    </p>
		    
	    	<p>
			<?php
			    $checked = strtolower($navdeveloper);
			?>
			
			<label for="<?php echo $this->get_field_id('navdeveloper'); ?>"><?php _e('Advanced Options:','woothemes'); ?></label><br />    	
			<span class="checkboxes">
			   	<label>Yes</label><input type="radio" id="<?php echo $this->get_field_name('navdeveloper'); ?>" name="<?php echo $this->get_field_name('navdeveloper'); ?>" value="yes" <?php if ($checked=='yes') { echo 'checked="checked"'; } ?> />
			    <label>No</label><input type="radio" id="<?php echo $this->get_field_name('navdeveloper'); ?>" name="<?php echo $this->get_field_name('navdeveloper'); ?>" value="no" <?php if ($checked=='yes') { } else { echo 'checked="checked"'; } ?> />
			</span><!-- /.checkboxes -->
			
			</p>
		    
		    <?php 
		    
		    //DEVELOPER settings
		    if ($checked == 'yes')
		    {
		    	?>
		    	
		    	<p>
				<?php
				    $checked = strtolower($navdiv);
				?>
				
				<label for="<?php echo $this->get_field_id('navdiv'); ?>"><?php _e('Wrap in container DIV:','woothemes'); ?></label><br />	
				<span class="checkboxes">
				   	<label>Yes</label><input type="radio" id="<?php echo $this->get_field_name('navdiv'); ?>" name="<?php echo $this->get_field_name('navdiv'); ?>" value="yes" <?php if ($checked=='yes') { echo 'checked="checked"'; } ?> />
				    <label>No</label><input type="radio" id="<?php echo $this->get_field_name('navdiv'); ?>" name="<?php echo $this->get_field_name('navdiv'); ?>" value="no" <?php if ($checked=='yes') { } else { echo 'checked="checked"'; } ?> />
				</span><!-- /.checkboxes -->
			
			</p>
			
			<?php
			
			if ($checked == 'yes')
			{
			
				?>
				
				<p>
				
		            <label for="<?php echo $this->get_field_id('navdivid'); ?>"><?php _e('DIV id:','woothemes'); ?></label>
		            <input type="text" name="<?php echo $this->get_field_name('navdivid'); ?>" value="<?php echo $navdivid; ?>" class="widefat" id="<?php echo $this->get_field_id('navdivid'); ?>" />
		        </p>
		        <p>
				
		            <label for="<?php echo $this->get_field_id('navdivclass'); ?>"><?php _e('DIV class:','woothemes'); ?></label>
		            <input type="text" name="<?php echo $this->get_field_name('navdivclass'); ?>" value="<?php echo $navdivclass; ?>" class="widefat" id="<?php echo $this->get_field_id('navdivclass'); ?>" />
		        </p>
				
				<?php
				
			}
			
			?>
			
			<p>
				<?php
				    $checked = strtolower($navul);
				?>
				
				<label for="<?php echo $this->get_field_id('navul'); ?>"><?php _e('Wrap in container UL:','woothemes'); ?></label><br />    	
				<span class="checkboxes">
				   	<label>Yes</label><input type="radio" id="<?php echo $this->get_field_name('navul'); ?>" name="<?php echo $this->get_field_name('navul'); ?>" value="yes" <?php if ($checked=='yes') { echo 'checked="checked"'; } ?> />
				    <label>No</label><input type="radio" id="<?php echo $this->get_field_name('navul'); ?>" name="<?php echo $this->get_field_name('navul'); ?>" value="no" <?php if ($checked=='yes') { } else { echo 'checked="checked"'; } ?> />
				</span><!-- /.checkboxes -->
			
			</p>
			
			<?php
		
			if ($checked == 'yes')
			{
			
				?>
				
				<p>
				
		            <label for="<?php echo $this->get_field_id('navulid'); ?>"><?php _e('UL id:','woothemes'); ?></label>
		            <input type="text" name="<?php echo $this->get_field_name('navulid'); ?>" value="<?php echo $navulid; ?>" class="widefat" id="<?php echo $this->get_field_id('navulid'); ?>" />
		        </p>
		        <p>
				
		            <label for="<?php echo $this->get_field_id('navulclass'); ?>"><?php _e('UL class:','woothemes'); ?></label>
		            <input type="text" name="<?php echo $this->get_field_name('navulclass'); ?>" value="<?php echo $navulclass; ?>" class="widefat" id="<?php echo $this->get_field_id('navulclass'); ?>" />
		        </p>
				
				<?php
				
			}
			
			?>
			<?php $advanced_option_descriptions = get_option('woo_settings_custom_nav_advanced_options'); ?>
			<p <?php if ($advanced_option_descriptions == 'no') { ?>style="display:none;"<?php } ?>>
			
	           <?php
				    $checked = strtolower($navwidgetdescription);
				?>
				
				<label for="<?php echo $this->get_field_id('navwidgetdescription'); ?>"><?php _e('Show Top Level Descriptions:','woothemes'); ?></label><br />    	
				<span class="checkboxes">
				   	<label>Yes</label><input type="radio" id="<?php echo $this->get_field_name('navwidgetdescription'); ?>" name="<?php echo $this->get_field_name('navwidgetdescription'); ?>" value="1" <?php if ($checked=='1') { echo 'checked="checked"'; } ?> />
				    <label>No</label><input type="radio" id="<?php echo $this->get_field_name('navwidgetdescription'); ?>" name="<?php echo $this->get_field_name('navwidgetdescription'); ?>" value="2" <?php if ($checked=='1') { } else { echo 'checked="checked"'; } ?> />
				</span><!-- /.checkboxes -->
	        </p>
		    	<?php 
		    }
		    //Do nothing
		    else 
		    {
		    	
		    }
			
		}
		//Error message for menus not existing
		else 
		{
			?>
			<p>
		    	<label><?php _e('The Custom Menu has not been configured correctly.  Please check your theme settings before adding this widget.','woothemes'); ?></label>
			</p>
			<?php
		}
	}
	
} 

//CHECK if Custom Nav Menu is Enabled
if (get_option('woo_custom_nav_menu') == 'true') 
{
	register_widget('Woo_NavWidget');
}


?>