jQuery(document).ready(function() {
	
	jQuery("#tabs ul li a").click(function(){
		
		jQuery('.blocksection').css("display","none");
		var data_value = jQuery(this).attr("data");

		var div_class = "."+data_value;

		jQuery(div_class).fadeIn("slow");

	});




});


