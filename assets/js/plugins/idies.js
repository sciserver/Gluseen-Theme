+function ($) {
	$(document).ready(function(){

		//ensure all links with an external-link class open a new tab
		var $ext_links = $(".external-link>a");
		$.merge($ext_links , $("a.external-link"));
	   $($ext_links).each(function(){
			$(this).attr("target","_blank")
		});
	
		//set up the expand-all and collapse-all buttons
		$("button.expand-all").click(function(){
			$("div." + $(this).attr('data-group') + ">article>div.collapse").each(function(){ $(this).collapse('show') });			
		});
		$("button.collapse-all").click(function(){
			$("div." + $(this).attr('data-group') + ">article>div.collapse").each(function(){ $(this).collapse('hide') });			
		});
	})
}(jQuery);
;