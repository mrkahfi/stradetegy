/********************************************
 **** UI-Pro jQuery Plugin
 **** Created by: wintercounter
 **** Version: 1.2
 **** Available at CodeCanyon
 ********************************************/

;(function($) {

	var 		documentWidth,
			documentHeight,
			i;

	// Init things
	$.uiPro = function(settings) {
		params = null;
		params = $.extend({
			
			// Params
			init : 'both',
                        leftMenu : false,
                        rightMenu : false,
			threshold : 40,
			onMouse : true,
			onSwipe : true

			
		}, settings);

		
		/***** Some Essential Things *****/
		
		documentWidth = $(document).width();
		documentHeight = $(document).height();
		
		/***** Init Menus *****/
			
		$.uiPro.init();

	}
	
	/************* Inits ************/
	$.uiPro.init = function() {
            
		var leftMenu = false;
		var rightMenu = false;
		var left = right = false;
		
		if(params.init == 'left' || params.init == 'both'){
		    
		    if($.uiPro.createMenu('left')){
			    
			    left = $('#uipro_left');
			    
		    }
    
		}
		
		if(params.init == 'right' || params.init == 'both'){
		    
		    if($.uiPro.createMenu('right')){
			    
			    right = $('#uipro_right');
		    
		    }
    
		}
		
		if(left || right){
			
			var touchX = 0;
			var binds = params.onMouse ? 'mousemove ' : '';
			binds = params.onSwipe ? binds + 'touchstart ' : binds + '';
			binds = binds + 'uiRightOpen uiLeftOpen uiRightClose uiLeftClose';
			var flag = false;
			
			$(document).bind(binds, function(e){
				
				if (flag && e.type != 'uiRightOpen' && e.type != 'uiLeftOpen' && e.type != 'uiRightClose' && e.type != 'uiLeftClose') {
					return;
				}
				
				if(typeof(e.originalEvent) != 'undefined' && typeof(e.originalEvent.touches) != 'undefined'){
					touchX = e.originalEvent.touches[0].pageX;
				}
				
				if(left){
				
					if((e.pageX < params.threshold && !left.hasClass('active')) || (!left.hasClass('active') && e.type == 'touchstart' && touchX < (params.threshold + 100)) || e.type == 'uiLeftOpen'){
						
						left.addClass('active');
						flag = true;
						setTimeout(function(){ flag = false; }, 500);
						
					}
					else if((left.hasClass('active') && e.pageX > left.width()) || (left.hasClass('active') && e.type == 'touchstart' && touchX < (left.width() + 100)) || e.type == 'uiLeftClose'){
						
						left.removeClass('active');
						flag = true;
						setTimeout(function(){ flag = false; }, 500);
						
					}
				
				}
				
				if(right){
					
					if((e.pageX > (documentWidth - params.threshold) && !right.hasClass('active')) || (!right.hasClass('active') && e.type == 'touchstart' && touchX > (documentWidth - params.threshold - 100)) || e.type == 'uiRightOpen'){
						
						right.addClass('active');
						flag = true;
						setTimeout(function(){ flag = false; }, 500);
						
					}
					else if((right.hasClass('active') && e.pageX < (documentWidth - right.width())) || (right.hasClass('active') && e.type == 'touchstart' && touchX > (documentWidth - right.width() - 100)) || e.type == 'uiRightClose'){
						
						right.removeClass('active');
						flag = true;
						setTimeout(function(){ flag = false; }, 500)
						
					}
					
				}
				
			});
		
		}
            
            
        }
	
	$.uiPro.open = function(which) {
		var event = which == 'left' ? 'uiLeftOpen' : 'uiRightOpen';
		$(document).trigger(event);
	}
	
	$.uiPro.close = function(which) {
		var event = which == 'left' ? 'uiLeftClose' : 'uiRightClose';
		$(document).trigger(event);
	}
	
	$.uiPro.toggle = function(which) {
		
		var event = false;
		
		if ($('#uipro_' + which).hasClass('active')) {
			event = which == 'left' ? 'uiLeftClose' : 'uiRightClose';
		}
		else{
			event = which == 'left' ? 'uiLeftOpen' : 'uiRightOpen';
		}
		
		$(document).trigger(event);
	}
        
        /************ PRIVATES *************/
        $.uiPro.createMenu = function(pos){
            
            var menu = $.uiPro.parseMenuItems(pos);
            
            if(menu){
                
                $('body').append('<div id="uipro_' + pos + '" class="uipro">' + menu + '</div>');
                
                return true;
	
            }
            else{
		
                return false;
	
            }
            
        }
        
        $.uiPro.parseMenuItems = function(pos){
		
		setTimeout(function(){
			var c = $('#uipro_' + pos + ' ul li').length;
			$('#uipro_' + pos + ' ul').css({'height': c * 100 + 'px', 'marginTop': (((c * 100) / 2) * -1) + 'px'});
		},100);
		
            if(typeof(params[pos + 'Menu']) == 'object'){
                
                var out = "<ul>";
                var items = params[pos + 'Menu'];
		
                
                for(var item in items){
			
			item = items[item];
			item.target = (typeof item.target == 'undefined') ? '_self' : item.target;
			
			out += '<li><a class="a_' + item.klass + '" href="' + item.link + '" target="' + item.target + '"><i class="' + item.klass + '"></i><span>' + item.label + '</span></a></li>\n';
			
                }
                
                out += '\n</ul>';
                
                return out;
                
            }
	    else if(typeof(params[pos + 'Menu']) == 'string'){
		
		return $("<div />").append($(params[pos + 'Menu']).clone()).html();
		
	    }
            else{
		
                return false;
	
            }
            
        }
        
} )( jQuery );