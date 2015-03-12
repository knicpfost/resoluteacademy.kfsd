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
$wp_url = get_bloginfo('siteurl'); 
global $wpdb;
?>
        <link rel="stylesheet" media="screen" href="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/css/admin-style.css" />
		<div class="wrap">
			<?php    echo "<h2>" . __('PayPal Payment Terminal Overview','') . "</h2>"; ?>
				
                
		
				<p><?php _e("This simple plugin enables you to accept PayPal payments on your WP website and manage transactions in your website's WP control panel. " ); ?></p>
                
		
				<?php    echo "<h4>" . __('Last 15 Transactions','') . "</h4>"; ?>
                <div class="transactions_overview_table">
                    <div class="table_wrapper">
                        <div class="table_header">
                            <ul>
                            	<li class="deleter">&nbsp;</li>
                                <li><?php _e('Date')?></li>
                                <li><?php _e('Name')?></li>
                                <li><?php _e('Email')?></li>
                                <li><?php _e('Amount')?></li>
                                <li><?php _e('Service')?></li>
                                <li class="lastTransColumn"><?php _e('Transaction ID')?></li>
                            </ul>
                        </div><br clear="all" />
                        <?php 
						//lets get all services from database
						$query="SELECT * FROM ".$wpdb->prefix."pppt_transactions  WHERE 1 AND pppt_status='2' $sqlfilter $sqlorder";
						//echo $query;
						$result=mysql_query($query) or die(mysql_error());
						if(mysql_num_rows($result)>0){
							$del=true;
							$rClass = "row_b";
							while($row=mysql_fetch_assoc($result)){
						?>
                        <div class="<?php echo $rClass=($rClass=="row_b"?"row_a":"row_b")?>">
                             <ul>
                             	<li class="deleter">&nbsp;&nbsp;<input type="checkbox" value="<?php echo $row["pppt_id"]?>" name="toDelete[]" /></li>
                                <li><?php echo date("d M Y, h:i a", strtotime($row["pppt_dateCreated"]))?></li>
                                <li><?php echo stripslashes(strip_tags($row["pppt_payer_name"]))?></li>
                                <li><?php echo stripslashes(strip_tags($row["pppt_payer_email"]))?></li>
                                <li><?php echo number_format(stripslashes(strip_tags($row["pppt_amount"])),2); $ppptTotal+=$row["pppt_amount"];?></li>
                                <?php 
									$query2="SELECT pppt_services_title FROM ".$wpdb->prefix."pppt_services WHERE pppt_services_id='".$row["pppt_serviceID"]."'";
									$result2=mysql_query($query2);
									$row2=mysql_fetch_assoc($result2);
								?>
                                <li><?php if($row["pppt_serviceID"]!=0){ echo stripslashes(strip_tags($row2["pppt_services_title"])); } else { echo "N/A"; }?></li>
                            	<li class="lastTransColumn"><?php echo stripslashes(strip_tags($row["pppt_transaction_id"]))?></li>
                            </ul>
                        </div> <br clear="all" />
                        
                        <?php }  } else { $del=false; ?>
                        <div class="row_msg">
                            <ul>
                                <li>0 transactions found</li>
                            </ul>
                        </div><br clear="all" />
                        <?php } ?>
                    </div>
                 </div>
                 <a href="admin.php?page=pppt_admin_transactions">View All Transactions</a>
			
		</div>
        <div class="pt_footer">
        	<a href="http://www.convergine.com" target="_blank"><img src="<?php echo $wp_url?>/wp-content/plugins/paypal_payment_terminal/resources/images/convergine.png" /></a>
        </div>