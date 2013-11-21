//页面公用类js
jQuery(document).ready(function() {
	$("ul.nav_inner li").hover(
	    function(){
	        $(this).addClass("one_menu_hover");
	    },function() {
	        $(this).removeClass("one_menu_hover");
	    }
	);

});
// $("ul.nav_inner li").hover(
//     function(){
//         $(this).addClass("one_menu_hover");
//     },function() {
//         $(this).removeClass("one_menu_hover");
//     }
// );

//弹窗登陆
function login_module(){//点击登录时，调用的函数
	var mengban=document.getElementById("mengban");
	mengban.className="mengban";
	var divs=document.getElementById("login_module");
	divs.style.display="block";
}
function close_login(){//关闭弹窗时，调用的函数
	var mengban=document.getElementById("mengban");
	mengban.className=" ";
	document.getElementById("login_module").style.display = "none";
	document.getElementById("register_module").style.display = "none";
}
function register_module(){//注册时，调用的函数
	var mengban=document.getElementById("mengban");
	mengban.className="mengban";
	var divs=document.getElementById("register_module");
	divs.style.display="block";
}