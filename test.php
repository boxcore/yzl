<script>
    $(document).ready(function () {
        $("#navmenu ul li:has(ul)").hover(function () {
            $(this).children("a").css({color: "#fff"});
            if ($(this).find("li").length > 0) {
                $(this).children("ul").stop(true, true).slideDown(100)
            }
        }, function () {
            $(this).children("a").css({color: "#fff"});
            $(this).children("ul").stop(true, true).slideUp("fast")
        });
    })
</script>