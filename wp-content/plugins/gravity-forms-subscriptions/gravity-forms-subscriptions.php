<?php
/*
Plugin Name: Gravity Forms Subscriptions Add-On
Plugin URI: http://www.seodenver.com/plugins/gravity-forms-subscriptions/
Description: Allow your Gravity Forms forms to switch between product and subscription payment types.
Author: Katz Web Services, Inc.
Version: 1.0
Author URI: http://www.katzwebservices.com

Copyright 2011 Katz Web Services, Inc.  (email: info@katzwebservices.com)
 
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/


add_action('init', array('SubscriptionMod', 'init'));

class SubscriptionMod {
		
		function init() {
			add_filter('gform_paypal_query', array('SubscriptionMod', 'gform_paypal_query'), 1, 3);
			add_filter('admin_head', array('SubscriptionMod', 'js'));
			add_filter("gform_add_field_buttons", array('SubscriptionMod', "add_field_buttons"));
			
			load_plugin_textdomain('gravity-forms-subscriptions', FALSE, '/gravity-forms-subscriptions/languages' );
		}
		

		function gform_paypal_query($query_string, $form, $entry) {
			
				$config = GFPayPalData::get_feed_by_form($form["id"]);
				$config = $config[0];
				
				$product_type = self::get_subscription_field_value($form);
				
				// Do the default if there's no field.
				if($product_type === false) { return $query_string; }
				
				preg_match('/([0-9]+)\s?([a-zA-Z]{1})?/', $product_type, $product_type_details);
				
				// Do the default if there's no matches
				if(empty($product_type_details)) { return $query_string; }

				$product_type_number = !empty($product_type_details[1]) ? $product_type_details[1] : $product_type;
				$product_type_type = !empty($product_type_details[1]) ? $product_type_details[2] : '';
				
				switch($product_type_number){
		            case 0 :
		                $query_string = self::get_product_query_string($form, $entry);
		            break;
					default:
						$product_type_type = strtoupper($product_type_type);
						$allowed_types = array('D', 'M', 'W', 'Y');
						if(in_array($product_type_type, $allowed_types)) {
							$config["meta"]["billing_cycle_type"] = $product_type_type;
						}
						$config["meta"]["billing_cycle_number"] = $product_type_number;
		                $query_string = self::get_subscription_query_string($config, $form, $entry);
		            break;
		        }
		        
		        return $query_string;
		}
		
		function get_subscription_field_value($form) {
			foreach($form['fields'] as $field) {
				if($field['type'] == 'select' || $field['type'] == 'radio' && !empty($field['subscriptionSelect'])) {
					return isset($_POST['input_'.$field['id']]) ? $_POST['input_'.$field['id']] : false;
				}
			}
		}
		
		function instructions() {
			ob_start();
		?>
	    %s
	        %sInstructions%s
	
	        %sSet up a PayPal feed for this form and %smake sure the Transaction Type is "Subscriptions".%s If a user chooses an option with a value of %s0%s, the Transaction Type will be converted to "Products and Services".%s
	
	        %sCustomizing the Options%s
	
	        %sYou can enter your own custom timeframes, such as %s3w%s for 3 weeks. Have a number proceeded by a date string. Use the following keys:%s
	
	        %s
	        	%s0%s =&gt; Product or Service; No Subscription%s
	        	%sD%s =&gt; Day (Between 1-90)%s
	        	%sW%s =&gt; Week (Between 1-52)%s
	        	%sM%s =&gt; Month (Between 1-24)%s
	        	%sY%s =&gt; Year (Between 1-5)
	        %s
	
	        %sView the existing choice values for examples.%s
	    %s
	    <?php 
	    	$instructions = ob_get_contents();
	    	
	    	ob_clean();
			
			$instructions = __(sprintf($instructions,
			'<div class="alert_gray" style="padding:8px; margin-bottom:1em;">',
				'<h3>', '</h3>', 
				'<p>', '<strong>', '</strong>', '<code style="font-size:1em;">', '</code>', '</p>',
		        '<h4>', '</h4>', 
		        '<p>', '<code style="font-size:1em;">', '</code>', '</p>',
		        '<p>', 
			        '<code style="font-size:1em;">', '</code>', '<br />',
			        '<code style="font-size:1em;">', '</code>', '<br />',
			        '<code style="font-size:1em;">', '</code>', '<br />',
			        '<code style="font-size:1em;">', '</code>', '<br />',
			        '<code style="font-size:1em;">', '</code>',
		        '</p>',
		        '<p>', '</p>',
	        '</div>'
	        ), 'gravity-forms-subscriptions');
			
			return preg_replace('/\s+/ism', ' ', $instructions);
		}
		
		function js() {
			?>
			<script type="text/javascript">
			
			jQuery(document).ready(function($) {
				
				jQuery(document).bind("gform_load_field_settings", function(event, field, form){
					
			    	if(field["type"] === "select" || field["type"] === 'radio' && field["subscriptionSelect"] !== 'false' && field["useAsEntryLink"] !== '') {
		        		$('.alert_gray').remove();
		        		$('#field_choice_values_enabled').parent('div').hide();
		        		$('.other_choice_setting').hide();
		        		$('.choices_setting').prepend('<?php echo self::instructions(); ?>');
		        	}
		        });
			});
						
			function SetDefaultValues_subscription_radio(field) {
				return SetDefaultValues_subscription_select(field, true);
			}
			
			function SetDefaultValues_subscription_select(field, radio) {
					field.label = "<?php _e("Payment Frequency", "gravity-forms-subscriptions"); ?>";
					field.adminLabel = "<?php _e("Subscription", "gravity-forms-subscriptions"); ?>";
					field.adminOnly = false;
					field.enableChoiceValue = true;
					field.subscriptionSelect = true;
		            if(!field.choices)
		                field.choices = new Array(
		                	new Choice("<?php _e("One-Time", "gravity-forms-subscriptions"); ?>", '0'),
		                	new Choice("<?php _e("Once Daily", "gravity-forms-subscriptions"); ?>",'1d'),
		                	new Choice("<?php _e("Once Weekly", "gravity-forms-subscriptions"); ?>",'1w'),
		                	new Choice("<?php _e("Once Monthly", "gravity-forms-subscriptions"); ?>",'1m'),
		                	new Choice("<?php _e("Every 6 Months", "gravity-forms-subscriptions"); ?>",'6m'),
		                	new Choice("<?php _e("Once Yearly", "gravity-forms-subscriptions"); ?>",'1y')
		                );
					
		            field.inputs = new Array();
		            for(var i=1; i<=field.choices.length; i++)
		                field.inputs.push(new Input(field.id + (i/10), field.choices[i-1].text));
		            
		            field.type = (radio === true) ? 'radio' : 'select';
			        
		           	return field;
			}
					
			</script>
		<?php
		}
		
		function add_field_buttons($field_groups){
			foreach($field_groups as $key => $group) {
				if($group['name'] == 'pricing_fields') {
					$field_groups["{$key}"]['fields'][] = array(
						'class' => 'button',
						'value' => __('Subscribe (Select)', 'gravity-forms-subscriptions'),
						'title' => __('Toggle between one-time payment or subscription using a drop-down menu', 'gravity-forms-subscriptions'),
						'onclick' => "StartAddField('subscription_select');"
					);
					$field_groups["{$key}"]['fields'][] = array(
						'class' => 'button',
						'style' => 'font-size:90%',
						'value' => __('Subscribe (Radio)', 'gravity-forms-subscriptions'),
						'title' => __('Toggle between one-time payment or subscription using radio buttons', 'gravity-forms-subscriptions'),
						'onclick' => "StartAddField('subscription_radio');"
					);
				}
			}
			
			return $field_groups;
		}
		
		private static function get_product_query_string($form, $entry){
	        $fields = "";
	        $products = GFCommon::get_product_fields($form, $entry);
	        $product_index = 1;
	        $total = 0;
	
	        foreach($products["products"] as $product){
	            $option_fields = "";
	            $price = GFCommon::to_number($product["price"]);
	            if(isset($product["options"]) && is_array($product["options"])){
	                $option_index = 1;
	                foreach($product["options"] as $option){
	                    $field_label = urlencode($option["field_label"]);
	                    $option_name = urlencode($option["option_name"]);
	                    $option_fields .= "&on{$option_index}_{$product_index}={$field_label}&os{$option_index}_{$product_index}={$option_name}";
	                    $price += GFCommon::to_number($option["price"]);
	                    $option_index++;
	                }
	            }
	            $name = urlencode($product["name"]);
	            $fields .= "&item_name_{$product_index}={$name}&amount_{$product_index}={$price}&quantity_{$product_index}={$product["quantity"]}{$option_fields}";
	            $total += $price;
	
	            $product_index++;
	        }
	        $shipping = !empty($products["shipping"]["price"]) ? "&shipping_1={$products["shipping"]["price"]}" : "";
	        $fields .= "{$shipping}&cmd=_cart&upload=1";
	
	        return $total > 0 ? $fields : false;
	    }
	
	    private static function get_subscription_query_string($config, $form, $entry){
	
	        $products = GFCommon::get_product_fields($form, $entry);
	        $amount = 0;
	        foreach($products["products"] as $id => $product){
	            if($id == $config["meta"]["recurring_amount_field"]){
	                $amount_field = $product;
	                $amount = GFCommon::to_number($product["price"]) * $product["quantity"];
	                break;
	            }
	        }
			
			$trial = '';
	        if($config["meta"]["trial_period_enabled"]){
	            $trial_amount = GFCommon::to_number($config["meta"]["trial_amount"]);
	            if(empty($trial_amount))
	                $trial_amount = 0;
	            $trial = "&a1={$trial_amount}&p1={$config["meta"]["trial_period_number"]}&t1={$config["meta"]["trial_period_type"]}";
	        }
	
	        $recurring_times= !empty($config["meta"]["recurring_times"]) ? "&srt={$config["meta"]["recurring_times"]}" : "";
	        $option_info = self::get_subscription_option_info($amount_field);
	        $amount += $option_info["price"];
	        $item_name = urlencode($option_info["label"]);
	        $recurring_retry = $config["meta"]["recurring_retry"] ? "1" : "0";
	        $query_string = "&cmd=_xclick-subscriptions&item_name={$item_name}{$trial}&a3={$amount}&p3={$config["meta"]["billing_cycle_number"]}&t3={$config["meta"]["billing_cycle_type"]}&src=1&sra={$recurring_retry}{$recurring_times}";
	
	        return $amount > 0 ? $query_string : false;
	    }
	
	    private static function get_subscription_option_info($product){
	        $option_price = 0;
	        $option_labels = array();
	        if(isset($product["options"]) && is_array($product["options"])){
	            foreach($product["options"] as $option){
	                $option_price += $option["price"];
	                $option_labels[] = $option["option_label"];
	            }
	        }
	        $label = empty($option_labels) ? $product["name"] : $product["name"] . " - " . implode(", " , $option_labels);
	        if(strlen($label) > 127)
	            $label = $product["name"] . " - " . __("with options", "gravityformspaypal");
	
	        return array("price" => $option_price, "label" => $label);
	    }

}
?>