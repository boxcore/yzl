//页面公用类js

jQuery(document).ready(function () {
    $("#navmenu ul li:has(div.two_menu)").hover(function () {
    	// alert(1111);
        // $(this).children("a").css({color: "#fff"});
        if ($(this).find("div.two_menu").length > 0) {
            // $(this).children("div.two_menu").stop(true, true).slideDown(100)
            $(this).addClass("one_menu_hover");
            $('.two_menu', this).attr({style:'display:block;'});
        }
    }, function () {
        // $(this).children("a").css({color: "#fff"});
        // $(this).children("div.two_menu").stop(true, true).slideUp("fast")
        $(this).removeClass("one_menu_hover");
        $('.two_menu', this).attr({style:'display:none;'});
    });

    $("#login_btn").click(function(){
        $('#login_pop').skygqbox();
    });

});

// jQuery(document).ready(function() {
// 	$("ul.nav_inner li").hover(
// 	    function(){
// 	        $(this).addClass("one_menu_hover");
// 	    },function() {
// 	        $(this).removeClass("one_menu_hover");
// 	    }
// 	);

// });
// 
// 
// $("ul.nav_inner li").hover(
//     function(){
//         $(this).addClass("one_menu_hover");
//     },function() {
//         $(this).removeClass("one_menu_hover");
//     }
// );

(function($){
    $.getUrlParam = function(name)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = window.location.search.substr(1).match(reg);
        if (r!=null) return unescape(r[2]); return null;
    }
})(jQuery);
