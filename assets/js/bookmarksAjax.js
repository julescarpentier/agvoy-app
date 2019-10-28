(function ($) {
    "use strict"; // Start of use strict

    $(document).on('click', '.bookmark', function (e) {
        e.preventDefault();
        let a = $(e.currentTarget);
        $.ajax({
            url: a.attr('href'),
            type: "POST",
            success: function (data, textStatus, jqXHR) {
                let i = a.find("i[class*=fa-");
                i.toggleClass("far").toggleClass("fas");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR.responseText);
                console.log(textStatus);
                console.log(errorThrown);
                alert("Ajax request failed.");
            }
        });
    });
})(jQuery); // End of use strict