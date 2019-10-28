(function ($) {
    "use strict"; // Start of use strict

    $(document).on('submit', '#regionForm', function (e) {
        e.preventDefault();
        let form = $(e.currentTarget);
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),
            // dataType: "json",
            success: function (data, textStatus, jqXHR) {
                $("#ajaxContainer").html(data);
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