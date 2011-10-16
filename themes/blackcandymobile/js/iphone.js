var $j = jQuery.noConflict();

$j(document).ready(function(){

		
		$j("body").addClass("iphone portrait");
		$j.iPhone.disableTextSizeAdjust();	

		$j(document).iPhone.orientchange(
			function(){
				$j("body").removeClass("landscape");
				$j("body").addClass("portrait");
			},
			function(){
				$j("body").removeClass("portrait");
				$j("body").addClass("landscape");
			}
		);

});
