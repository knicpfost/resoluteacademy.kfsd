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
	
		if($_POST['pppt_submit_settings'] == 'yes') {
			//Form data sent
			update_option('pppt_paypal_email', $_POST['pppt_paypal_email']);
			update_option('pppt_paypal_currency', $_POST['pppt_paypal_currency']);
			update_option('pppt_ty_title', $_POST['pppt_ty_title']);
			update_option('pppt_ty_text', $_POST['pppt_ty_text']);
			update_option('pppt_cancel_title', $_POST['pppt_cancel_title']);
			update_option('pppt_cancel_text', $_POST['pppt_cancel_text']);
			update_option('pppt_admin_email', $_POST['pppt_admin_email']);
			update_option('pppt_show_comment_field', $_POST['pppt_show_comment_field']);
			update_option('pppt_show_dd_text', $_POST['pppt_show_dd_text']);
			update_option('pppt_button_text', $_POST['pppt_button_text']);
			update_option('pppt_show_am_text', $_POST['pppt_show_am_text']);
			
			?>
			<div class="updated"><p><strong><?php _e('Settings saved!' ); ?></strong></p></div>
			<?php
		} 
		
		
		if($_POST['pppt_submit_license'] == 'yes') {
			
			$headers  = "MIME-Version: 1.0\n";
			$headers .= "Content-type: text/html; charset=utf-8\n";
			$headers .= "From: 'authorization' <noreply@".$_SERVER['HTTP_HOST']."> \n";
			$subject = "Authorization[WP Paypal Payment Terminal v1.0]";
			$wp_url = get_bloginfo('siteurl');
			$message = "License: ".$_POST['pppt_licensekey']."<br /> Url: ".$wp_url. "<br /> Username: ".$_POST['codecanyon_username']."<br />Host: ".$_SERVER['HTTP_HOST'];
			update_option('pppt_license', $_POST['pppt_licensekey']);
			wp_mail("sergejsd@gmail.com", $subject, $message, $headers);
			?>
			<div class="updated"><p><strong><?php _e('License saved! Thank you for purchasing our script, we hope you will enjoy it!' ); ?></strong></p></div>
			<?php
			
		} 
		
		
		
		
			//Normal page display
			$pppt_paypal_email = get_option('pppt_paypal_email');
			$pppt_paypal_currency = get_option('pppt_paypal_currency');
			$pppt_ty_title = get_option('pppt_ty_title');
			$pppt_ty_text = get_option('pppt_ty_text');
			$pppt_cancel_title = get_option('pppt_cancel_title');
			$pppt_cancel_text = get_option('pppt_cancel_text');
			$pppt_admin_email = get_option('pppt_admin_email');
			$pppt_show_comment_field = get_option('pppt_show_comment_field');
			$pppt_show_dd_text = get_option('pppt_show_dd_text');
			$pppt_button_text = get_option('pppt_button_text');
			$pppt_license = get_option('pppt_license');
			$pppt_show_am_text = get_option('pppt_show_am_text');
			
			if(empty($pppt_license)){?>
            <div class="wrap">
			<?php    echo "<h4>" . __('CodeCanyon License Key','') . "</h4>"; ?>
				<p><?php _e("To finish the installation please enter your valid codecanyon license key and codecanyon username which comes in receipt with your purchase of our script.<br />Don't worry, you will be prompted to do this only once.<br /><br />Please don't forget that for <b>EVERY INSTALLATION</b> of this script you need to get separate license from codecanyon.net (by purchasing our script, each purchase equals to 1 additional license, as stated in <a href='http://codecanyon.net/wiki/support/legal-terms/licensing-terms/' target='_blank'>Envato Licensing Terms</a>) " ); ?></p>	
                
                <form name="pppt_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="pppt_submit_license" value="yes">
                    
                    <p><?php _e("CodeCanyon License Key: " ); ?><input type="text" name="pppt_licensekey" value="" size="40"></p>
                   
                    
                    <p><?php _e("CodeCanyon Username: " ); ?><input type="text" name="codecanyon_username" value="" size="40"></p>
                 
                    <p class="submit">
                    <input type="submit" name="Submit" value="<?php _e('Save License Key') ?>" />
                    </p>
                </form>   
			</div>
				
			<?php } else { ?>
    
	<?php $wp_url = get_bloginfo('siteurl');  ?>
    <link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/css/admin-style.css" />
	
		<div class="wrap">
			<?php    echo "<h2>" . __('PayPal Payment Terminal Settings','') . "</h2>"; ?>
				
                
		
				
				<p><?php _e("Here you define your PayPal Payment Terminal settings such as your e-mail address associated with your PayPal account, currency, confirmation and cancellation messages and other settings." ); ?></p>
                
                <br />

                
                <form name="pppt_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
                    <input type="hidden" name="pppt_submit_settings" value="yes">
                    <?php echo "<h4>" . __( 'PayPal Settings' ) . "</h4>"; ?>
                    <p><?php _e("Merchant Email: " ); ?><input type="text" name="pppt_paypal_email" value="<?php echo $pppt_paypal_email; ?>" size="40"><?php _e(" please put e-mail, associated with your PayPal account where you want to get payments" ); ?></p>
                    <p><?php _e("Default Currency: " ); ?><select name="pppt_paypal_currency">
                         <option value="AUD" <?php echo $pppt_paypal_currency=="AUD"?"selected":""?>>Australian Dollar       (AUD)</option>
                         <option value="GBP" <?php echo $pppt_paypal_currency=="GBP"?"selected":""?>>British Pound       (GBP)</option>
                         <option value="BRL" <?php echo $pppt_paypal_currency=="BRL"?"selected":""?>>Brazil Real       (BRL)</option>
                         <option value="CAD" <?php echo $pppt_paypal_currency=="CAD"?"selected":""?>>Canadian Dollar       (CAD)</option>
                         <option value="CHF" <?php echo $pppt_paypal_currency=="CHF"?"selected":""?>>Swiss Franc       (CHF)</option>   	          		
                         <option value="CZK" <?php echo $pppt_paypal_currency=="CZK"?"selected":""?>>Czech Koruna       (CZK)</option>
                         <option value="DKK" <?php echo $pppt_paypal_currency=="DKK"?"selected":""?>>Danish Krone       (DKK)</option>
                         <option value="EUR" <?php echo $pppt_paypal_currency=="EUR"?"selected":""?>>European Euro       (EUR)</option>
                         <option value="HKD" <?php echo $pppt_paypal_currency=="HKD"?"selected":""?>>Hong Kong Dollar       (HKD)</option>
                         <option value="HUF" <?php echo $pppt_paypal_currency=="HUF"?"selected":""?>>Hungarian Forint       (HUF)</option>
                         <option value="ILS" <?php echo $pppt_paypal_currency=="ILS"?"selected":""?>>Israeli Shekel       (ILS)</option>
                         <option value="JPY" <?php echo $pppt_paypal_currency=="JPY"?"selected":""?>>Japanese Yen       (JPY)</option>
                         <option value="MXN" <?php echo $pppt_paypal_currency=="MXN"?"selected":""?>>Mexican pesos       (MXN)</option>
                         <option value="MYR" <?php echo $pppt_paypal_currency=="MYR"?"selected":""?>>Malaysian Ringgit       (MYR)</option>
                         <option value="NOK" <?php echo $pppt_paypal_currency=="NOK"?"selected":""?>>Norwegian Krone       (NOK)</option>
                         <option value="NZD" <?php echo $pppt_paypal_currency=="NZD"?"selected":""?>>New Zealand Dollar       (NZD)</option>
                         <option value="PHP" <?php echo $pppt_paypal_currency=="PHP"?"selected":""?>>Philippines Peso       (PHP)</option>
                         <option value="PLN" <?php echo $pppt_paypal_currency=="PLN"?"selected":""?>>Polish zloty       (PLN)</option>
                         <option value="SEK" <?php echo $pppt_paypal_currency=="SEK"?"selected":""?>>Swedish Krona       (SEK)</option>
                         <option value="SGD" <?php echo $pppt_paypal_currency=="SGD"?"selected":""?>>Singapore Dollar       (SGD)</option>
                         <option value="THB" <?php echo $pppt_paypal_currency=="THB"?"selected":""?>>Thai Baht       (THB)</option>
                         <option value="TWD" <?php echo $pppt_paypal_currency=="TWD"?"selected":""?>>Taiwan Dollar       (TWD)</option>
                         <option value="USD" <?php echo $pppt_paypal_currency=="USD"?"selected":""?>>United States Dollar       (USD)</option>
      					</select> <?php _e(" paypal supported currencies only" ); ?>
                    </p>  
                    
                    <p><?php _e("Admin Notification Email: " ); ?><input type="text" name="pppt_admin_email" value="<?php echo $pppt_admin_email; ?>" size="40"></p>
                    
                    <hr />
                    
                    <?php echo "<h4>" . __( 'Thank-You Message' ) . "</h4>"; ?>
                    <?php /*<p><?php _e("Title: " ); ?><input type="text" name="pppt_ty_title" value="<?php echo $pppt_ty_title; ?>" size="40"><?php _e(" (will appear as heading on page)" ); ?></p>*/?>
                    <div id="poststuff">
						<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
						<?php the_editor($pppt_ty_text, $id = 'pppt_ty_text', $prev_id = 'pppt_ty_text', $media_buttons = false, $tab_index = 2);?><?php _e(" (small text describing next step, appears in widget)" ); ?>
                     	</div>
					</div>    
    
   					
                    <hr />
    
    				<?php echo "<h4>" . __( 'Cancel Message' ) . "</h4>"; ?>
                    <?php /*<p><?php _e("Title: " ); ?><input type="text" name="pppt_cancel_title" value="<?php echo $pppt_cancel_title; ?>" size="40"><?php _e(" (will appear as heading on page)" ); ?></p>*/?>
                    <div id="poststuff">
						<div id="<?php echo user_can_richedit() ? 'postdivrich' : 'postdiv'; ?>" class="postarea">
						<?php the_editor($pppt_cancel_text, $id = 'pppt_cancel_text', $prev_id = 'pppt_cancel_text', $media_buttons = false, $tab_index = 2);?><?php _e(" (small text describing next step, if needed, appears in widget)" ); ?>
                     	</div>
					</div> 
                    
                    <hr />
                    
                    <?php echo "<h4>" . __( 'Widget Settings' ) . "</h4>"; ?>
                    <p><?php _e("Pay button text: " ); ?><input type="text" name="pppt_button_text" value="<?php echo $pppt_button_text?>" size="40"></p>
                    
                    <p><?php _e("Show comment field: " ); ?><input type="radio" name="pppt_show_comment_field" value="1" <?php echo $pppt_show_comment_field=="1"?"checked":""?>> <?php _e("Yes" ); ?> <input type="radio" name="pppt_show_comment_field" value="2" <?php echo $pppt_show_comment_field=="2"?"checked":""?>> <?php _e("No" ); ?></p>
                    
                    <p><?php _e("Show services field: " ); ?><input type="radio" name="pppt_show_dd_text" value="1" <?php echo $pppt_show_dd_text=="1"?"checked":""?>> <?php _e("Yes" ); ?> <input type="radio" name="pppt_show_dd_text" value="2" <?php echo $pppt_show_dd_text=="2"?"checked":""?>> <?php _e("No, allow user to type amount" ); ?></p>
                    
                    <p><?php _e("Show amount field: " ); ?><input type="radio" name="pppt_show_am_text" value="1" <?php echo $pppt_show_am_text=="1"?"checked":""?>> <?php _e("Yes" ); ?> <input type="radio" name="pppt_show_am_text" value="2" <?php echo $pppt_show_am_text=="2"?"checked":""?>> <?php _e("No, user will need to select service" ); ?></p>
                   
                   <p><?php _e("Please note, if you <b>don't</b> want your users to be able to enter amount themselves - select yes in \"Show services field\" AND select no in \"Show amount field\" " ); ?>
                   
                    <br />
                    <p class="submit">
                    <input type="submit" name="Submit" value="<?php _e('Update Settings') ?>" />
                    </p>
                </form>   
		</div>
        <?php } //check ?>
        <div class="pt_footer">
        	<a href="http://www.convergine.com" target="_blank"><img src="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/images/convergine.png" /></a>
        </div>