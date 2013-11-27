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

$().ready(function() {
    $("#user_login").validate({
//            errorContainer: $(".container_error"),
//            errorLabelContainer: $(".container_error ol"),
//            wrapper: 'div',
//            errorLabelContainer: $("#form1 div.error"),

        rules: {
            username: {
                required:true,
                rangelength:[6,18]
            },
            password: {
                required: true,
                minlength: 5
            },
            captcha: {
                required: true
            }
        },
        messages: {
            username: {
                required:"请输入姓名",
                rangelength:'至少6个以上的字'
            },
            password: {
                required: "请输入密码",
                minlength: jQuery.format("密码不能小于{0}个字 符")
            },
            captcha: {
                required: "请输入验证码"
            }
        },
        /* 失去焦点时不验证 */
        onfocusout: false,
        onkeyup: false,
        showErrors: function(errorMap, errorList) {
            var msg = "";
            $.each( errorList, function(i,v){
                msg += (v.message+"\r\n");
            });
            if(msg!="") alert(msg);
        },
        onsubmit: true

    });

    $('#show_weixin').hover(
        function(){
            $('#weixin_img').attr({style:"display:block;"})
        },
        function(){
            $('#weixin_img').attr({style:"display:none;"})
        }
    );

    //
    var local_url = window.location.href;
    var input = $("input[name='back_act']");
    if(!input.val()){
        input.val(local_url);
    };

});


