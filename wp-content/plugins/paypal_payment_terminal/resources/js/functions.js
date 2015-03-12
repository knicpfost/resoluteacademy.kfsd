/*
#******************************************************************************
#                      WP Paypal Payment Terminal v1.0
#
#	Author: Convergine.com
#	http://www.convergine.com
#	Version: 1.0
#	Released: March 6 2011
#
#******************************************************************************
*/
$(document).ready(function() {
	$('.row_a ul').hover(function() {
	  $(".row_a ul > li").addClass('pretty-hover');
	}, function() {
	  $(".row_a ul > li").removeClass('pretty-hover');
	});
	
	$('.row_b ul').hover(function() {
	  $(".row_b ul > li").addClass('pretty-hover');
	}, function() {
	  $(".row_b ul > li").removeClass('pretty-hover');
	});
});	

function noAlpha(obj){
	reg = /[^0-9.]/g;
	obj.value =  obj.value.replace(reg,"");
}
 
$(function() {
	$( "#date1" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
	});

	$( "#date2" ).datepicker({
			changeMonth: true,
			changeYear: true,
			dateFormat: "yy-mm-dd"
	});
});
 
/*
$(document).ready(function() {
	$('.row_a ul, .row_b ul').hover(function() {
	  $(this).addClass('pretty-hover');
	}, function() {
	  $(this).removeClass('pretty-hover');
	});
});	*/