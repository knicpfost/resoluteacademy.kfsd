<?php
#******************************************************************************
#                      WP Paypal Payment Terminal v1.0
#
#	Author: Convergine.com
#	http://www.convergine.com
#	Version: 1.0
#	Released: March 6 2011
#
#******************************************************************************
	global $wpdb;
	
	if($_POST['pppt_submit_service'] == 'yes') {
		//Form data sent
		$pt_title = $_POST['pppt_services_title'];
		$pt_descr = $_POST['pppt_services_descr'];
		$pt_price = $_POST['pppt_services_price'];
		
		if(is_numeric($pt_price) && !empty($pt_title)){
			$query="INSERT INTO ".$wpdb->prefix."pppt_services (pppt_services_title, pppt_services_descr, pppt_services_price) VALUES ('".addslashes(strip_tags($pt_title))."','".addslashes(strip_tags($pt_descr))."','".addslashes(strip_tags($pt_price))."')";
			mysql_query($query) or die(mysql_error());
			?><div class="updated"><p><strong><?php _e('Service added!' ); ?></strong></p></div><?php
		} else { 
		?><div class="updated"><p><strong><?php _e('Service not added! Please check your input. Price must contain numbers only and name cannot be blank.' ); ?></strong></p></div><?php
		}

 } $pppt_services_descr = "";

	if(!empty($_POST['toDelete']) && count($_POST["toDelete"])>0) {
		$deleted=0;
		for($i=0; $i<count($_POST["toDelete"]); $i++){
			$query="DELETE FROM ".$wpdb->prefix."pppt_services WHERE pppt_services_id='".$_POST["toDelete"][$i]."'";
			mysql_query($query) or die(mysql_error());
			$deleted++;
		}
		
		if($deleted>0){
		?> <div class="updated"><p><strong><?php _e('Selected service(s) deleted!' ); ?></strong></p></div><?php
		}
	}

?>

	<?php $wp_url = get_bloginfo('siteurl');  ?>
    <link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/css/admin-style.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/js/functions.js"></script>
		<div class="wrap">
			<?php    echo "<h2>" . __('PayPal Payment Terminal Services','') . "</h2>"; ?>

				<p><?php _e("Here you create and manage basic list of products, services or events you'd like to accept payments for. This list will show up on your website for clients to select from when they are going to make a payment." ); ?></p>
                
                
                <?php    echo "<h4>" . __( 'Add New Service', '' ) . "</h4>"; ?>
                <form name="pppt_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="pppt_submit_service" value="yes">
                    
                    <p><?php _e("Service Name: " ); ?><input type="text" name="pppt_services_title" id="pppt_services_title" value="" size="40"><?php _e(" (This is what customers will see in the services dropdown to select from when they decide to pay)" ); ?></p>
                    <p><?php _e("Service Price: " ); ?><input type="text" name="pppt_services_price" id="pppt_services_price" onkeyup="noAlpha(this)"  value="" size="40"><?php _e(" (Numbers only. ex. 10.99)" ); ?></p>
                    
                    <div id="poststuff">
						<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
						<?php _e("Service Description: " ); ?><?php the_editor($pppt_services_descr, $id = 'pppt_services_descr', $prev_id = 'pppt_services_descr', $media_buttons = false, $tab_index = 2);?><?php _e(" (Optional small text describing the service, won't be displayed to customer, internal use only at the moment)" ); ?>
                     	</div>
					</div> 
    
                    <p class="submit">
                    <input type="submit" name="Submit" value="<?php _e('Add Service', '' ) ?>" />
                    </p>
                    
                </form>




                
                <?php    echo "<h4>" . __('List of Services','') . "</h4>"; ?>
                <form name="pppt_form_del" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                <div class="services_table">
                    <div class="table_wrapper">
                        <div class="table_header">
                            <ul>
                            	<li class="deleter">&nbsp;</li>
                                <li><?php _e('Service Name')?></li>
                                <li><?php _e('Service Price')?></li>
                                <li class="lastColumn"><?php _e('Description')?></li>
                            </ul>
                        </div><br clear="all" />
                        <?php 
						//lets get all services from database
						$query="SELECT * FROM ".$wpdb->prefix."pppt_services ORDER BY pppt_services_title";
						$result=mysql_query($query) or die(mysql_error());
						if(mysql_num_rows($result)>0){
							$del=true;
							$rClass = "row_b";
							while($row=mysql_fetch_assoc($result)){
						?>
                        <div class="<?php echo $rClass=($rClass=="row_b"?"row_a":"row_b")?>">
                             <ul>
                             	<li class="deleter">&nbsp;&nbsp;<input type="checkbox" value="<?php echo $row["pppt_services_id"]?>" name="toDelete[]" /></li>
                                <li><?php echo stripslashes(strip_tags($row["pppt_services_title"]))?> <a href="admin.php?page=pppt_admin_services_edit&amp;pppt_serviceID=<?php echo $row["pppt_services_id"]?>">edit</a></li>
                                <li><?php echo number_format(stripslashes(strip_tags($row["pppt_services_price"])),2)?></li>
                                <li class="lastColumn"><?php echo stripslashes(strip_tags($row["pppt_services_descr"]))?></li>
                            </ul>
                        </div> <br clear="all" />
                        <?php }  } else { $del=false; ?>
                        <div class="row_msg">
                            <ul>
                                <li>0 service records found in the database</li>
                            </ul>
                        </div><br clear="all" />
                        <?php } ?>
                    </div>
                </div>
                <?php if($del){?>
                <p class="submit">
                	<input type="submit" name="Submit" value="<?php _e('Delete Selected Services', '' ) ?>" />
                </p>
                <? } ?>
                </form>
                
                
		</div>
        <div class="pt_footer">
        	<a href="http://www.convergine.com" target="_blank"><img src="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/images/convergine.png" /></a>
        </div>