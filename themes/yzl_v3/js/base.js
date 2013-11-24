//页面公用类js

$(document).ready(function () {
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
})

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

////弹窗登陆
//function login_module(){//点击登录时，调用的函数
//	var mengban=document.getElementById("mengban");
//	mengban.className="mengban";
//	var divs=document.getElementById("login_module");
//	divs.style.display="block";
//}
//function close_login(){//关闭弹窗时，调用的函数
//	var mengban=document.getElementById("mengban");
//	mengban.className=" ";
//	document.getElementById("login_module").style.display = "none";
//	document.getElementById("register_module").style.display = "none";
//}
//function register_module(){//注册时，调用的函数
//	var mengban=document.getElementById("mengban");
//	mengban.className="mengban";
//	var divs=document.getElementById("register_module");
//	divs.style.display="block";
//}