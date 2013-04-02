// JavaScript Document

jQuery(document).ready(function($) {
    
    $( "#wp_mmg_plugin_admin_form" ).tabs({event: "mouseover"});
    
    VideoJS.setupAllWhenReady();
	
    // Manage width of photo div
    var width = $("#wp_mmg").width();
    var col = parseFloat($("#wp_mmg").attr("rel"));
    var item_width = width/col;
    $(".element").width(item_width);
    var border_right = parseFloat($(".element img").css("border-right-width"));
    var border_left = parseFloat($(".element img").css("border-left-width"));
    $(".element img").css("max-width", item_width-border_right-border_left-4);


    // Manage alignement;
    $("#wp_mmg .element:nth-child("+col+"n+1)").css("clear", "both");

    
    
    $("a.wp_mmg_lightbox").fancybox({
        'titlePosition'	:	'over',
        'onComplete'	:	function() {
            $("#fancybox-wrap").hover(function() {
                $("#fancybox-title").show();
            }, function() {
                $("#fancybox-title").hide();
            });
        }
    });
    
    $("a.wp_mmg_lightbox-movie").each(function(){
        var dWidth  = parseInt($(this).attr('href').match(/width=[0-9]+/i)[0].replace('width=',''))
        var dHeight = parseInt($(this).attr('href').match(/height=[0-9]+/i)[0].replace('height=',''));  
        $(this).fancybox({ 
            'width':dWidth,  
            'height':dHeight,
            'autoScale'         : false,
            'transitionIn'      : 'none',
            'transitionOut'     : 'none',
            'type'              : 'iframe',
            'padding'           : 0,
            'margin'            : 0,
            'scrolling'          : 'no'
        });
    }); 
    
    return false;

});