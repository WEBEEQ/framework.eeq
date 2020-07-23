$(document).ready(function() {
    function ajaxData(id, file) {
        $.get(file, function(data) {
            $("#" + id).html(data);
        });
    }

    $("#menu li").click(function() {
        document.location = $(this).children().attr("href");
    });

    $("#province").change(function() {
        ajaxData("select", "/ajax/miejsce," + this.value);
    });

    $("#frame").css("height", (document.documentElement.clientHeight - 155) + "px");
});
