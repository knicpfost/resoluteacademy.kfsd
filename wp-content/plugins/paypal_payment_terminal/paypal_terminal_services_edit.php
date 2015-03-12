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
	
	
	//$pppt_serviceID = $_GET['pppt_serviceID'];
	
	$ssdurl = $_SERVER['REQUEST_URI'];
	if(stristr($ssdurl, "&pppt_serviceID=")){
		$myurltemp = explode("&pppt_serviceID=",$ssdurl);
		$pppt_serviceID = $myurltemp[1];
	}

	
	if($_POST['pppt_submit_service'] == 'yes' && !empty($pppt_serviceID) && is_numeric($pppt_serviceID)) {
		//Form data sent
		$pt_title = $_POST['pppt_services_title'];
		$pt_descr = $_POST['pppt_services_descr'];
		$pt_price = $_POST['pppt_services_price'];
		
		if(is_numeric($pt_price) && !empty($pt_title)){
			$query="UPDATE ".$wpdb->prefix."pppt_services SET pppt_services_title='".addslashes(strip_tags($pt_title))."', pppt_services_descr='".addslashes(strip_tags($pt_descr))."', pppt_services_price='".addslashes(strip_tags($pt_price))."' WHERE pppt_services_id='".$pppt_serviceID."'";
			mysql_query($query) or die(mysql_error());
			?><div class="updated"><p><strong><?php _e('Service updated! <a href="admin.php?page=pppt_admin_services">Click here</a> to go back to all services' ); ?></strong></p></div><?php
		} else { 
		?><div class="updated"><p><strong><?php _e('Service not updated! Please check your input. Price must contain numbers only and name cannot be blank.' ); ?></strong></p></div><?php
		}

 	} 
	
	
	$query2="SELECT * FROM ".$wpdb->prefix."pppt_services WHERE pppt_services_id='".$pppt_serviceID."'";
	$result2=mysql_query($query2) or die(mysql_error()); 	
	$row2=mysql_fetch_assoc($result2);
	$pppt_services_title = $row2["pppt_services_title"];
	$pppt_services_price = $row2["pppt_services_price"];
	$pppt_services_descr = $row2["pppt_services_descr"];
?>

	<?php $wp_url = get_bloginfo('siteurl');  ?>
    <link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/css/admin-style.css" />
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
	<script type="text/javascript" src="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/js/functions.js"></script>
		<div class="wrap">
			<?php    echo "<h2>" . __('PayPal Payment Terminal Services','') . "</h2>"; ?>

                <?php    echo "<h4>" . __( 'Edit Service', '' ) . "</h4>"; ?>
                <form name="pppt_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="pppt_submit_service" value="yes">
                    <input type="hidden" name="pppt_serviceID" value="<?php echo $pppt_serviceID?>">
                    
                    <p><?php _e("Service Name: " ); ?><input type="text" name="pppt_services_title" id="pppt_services_title" value="<?php echo $pppt_services_title?>" size="40"><?php _e(" (This is what customers will see in the services dropdown to select from when they decide to pay)" ); ?></p>
                    <p><?php _e("Service Price: " ); ?><input type="text" name="pppt_services_price" id="pppt_services_price" onkeyup="noAlpha(this)"  value="<?php echo $pppt_services_price?>" size="40"><?php _e(" (Numbers only. ex. 10.99)" ); ?></p>
                    
                    <div id="poststuff">
						<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
						<?php _e("Service Description: " ); ?><br /><textarea name="pppt_services_descr" style="width:450px; height:100px;"><?php echo $pppt_services_descr?></textarea>
                     	</div>
					</div> 
    
                    <p class="submit">
                    <input type="submit" name="Submit" value="<?php _e('Update Service', '' ) ?>" />
                    </p>
                    
                </form>
                
                
		</div>
        <div class="pt_footer">
        	<a href="http://www.convergine.com" target="_blank"><img src="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/images/convergine.png" /></a>
        </div>