$(function () {

    $("button").click(initParser);

    $("#parent").keyup(function (event) {
        if (event.which === 13) {
            initParser();
        }
    });

});

function initParser() {

    // please care about my hosting
    if ($("button").prop("disabled")) {
        console.log("no ddos");
        return;
    }

    // get parent url
    var parent = $("#parent").val();
    if (!parent) {
        alert("please specify url first");
        return;
    }

    // reset dashboard
    $("button").prop("disabled", true);
    $(".dashboard-status").text("Scanning for documents...");
    $(".progress-bar").css("width", "0%");
    $(".progress-bar").text("");

    // get request url
    var query = $.param({
        parent: parent
    });
    var url = "silex/list?" + query;

    // call ajax to collect documents
    $.ajax(url).done(function (res) {

        if (res.length == 0) {
            $(".dashboard-status").text("No document found, stopped");
            $("button").prop("disabled", false);
            return;
        }

        $(".dashboard-status").text("Scanning for emails...");

        var parser = new Parser(res);
        parser.run();
    });
}
