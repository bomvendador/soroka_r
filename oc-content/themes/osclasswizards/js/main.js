	
	function invokeScript(){
		
		var _selector = $('#plugin-hook select');
		if(typeof _selector.html() !== 'undefined'){
			_selector.each(function(key,value){
				if(!$(this).next().is('a')) selectUi($(this));
			});
		}

		//checkbox
		var callbacks_list = $('');
		$('input').on('ifCreated ifClicked ifChanged ifChecked ifUnchecked ifDisabled ifEnabled ifDestroyed', function(event){
			callbacks_list.prepend('');
		}).iCheck({
			checkboxClass: 'square',
			radioClass: 'circle',
			increaseArea: '20%'
		});
	}
	
	
		/********* Togggle Function *********/
	
	
	$(document).ready(function(){
		
		$(".toggle").click(function(){
		$(".links").slideToggle(400);
			return false;
		});
		
		
		$(".language span").click(function(){
			$(".language ul").slideToggle(400);
			return false;
		});
		
		$("#show_filters").click(function(){
			$("#filters_shown").slideToggle(400);
			return false;
		});
		
			
		//invoke script
		invokeScript();
		
		/********* Theme Function Fixes Js *********/

		
		//item page
		$('#mask_as_form select').on('change', function() {
			$('#mask_as_form').submit();
		});
		
		
		//when other plugin hooks activated
		$('.item-post, #plugin-hook').on('mouseover hover click', function(){
			invokeScript();		
		});
		
	
		
		
	});
	






