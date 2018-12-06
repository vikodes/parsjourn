/**
 * This is a class to parse links and create list of emails
 */

function Parser(list) {
    this.list = list;
}

Parser.prototype = {
    // recursive ajax for each item of list
    run: function (i) {
        i = i || 0;
        this.progress(i);

        if (i >= this.list.length) {
            $(".dashboard-current-document").text("");
            $(".dashboard-status").text("Job is done");
            return;
        }

        // prepare query

        var that = this;
        var query = $.param({
            url: this.list[i]
        });
        var url = "silex/item?" + query;

        // update dashboard
        $(".dashboard-current-document").text(this.list[i]);

        // call ajax

        $.ajax(url).done(function (res) {
            that.append(res);
        }).always(function() {
            that.run(i + 1);
        });
    },

    // show progress bar
    progress: function (done) {
        // calculate things
        var total = this.list ? this.list.length : 0;
        var percent = Math.round(done / total * 100) + "%";

        // change progress bar
        $(".progress-bar").css("width", percent);
        $(".progress-bar").text(percent);
        $(".dashboard-documents-found").text(total);
        document.title = "GoatParser "+ percent;

        // is finished things
        var is_finished = done < total;
        $("button").prop("disabled", is_finished);
        $(".progress-bar").toggleClass("active", is_finished);

    },

    // append textarea with new emails
    append: function (emails) {
        var textarea = $("#results");

        for (var i = 0; i < emails.length; ++i) {
            var email = emails[i];
            // @todo check for unique

            // append email
            var text = textarea.val() + "\n" + email;

            // filter not unique values
            var lines = text.split("\n").filter(function(el, index, arr) {
                return index == arr.indexOf(el);
            });

            // set values
            $("#results").val(lines.join("\n").trim());

        }

        // scroll to the bottom of textarea
        textarea.animate({
            scrollTop: textarea[0].scrollHeight - textarea.height()
        });

        // update dashboard
        var text = textarea.val();
        var count = text.length ? text.split("\n").length : 0;
        $(".dashboard-emails-found").text(count);
    }
};
