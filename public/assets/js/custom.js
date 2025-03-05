function loadingTemplate() {
    return '<div class="spinner-border text-primary" style="width: 2.5rem; height: 2.5rem" role="status"></div>';
}

function iconsFunction() {
    return {
        paginationSwitchDown: "fa fa-angle-down",
        paginationSwitchUp: "fa fa-angle-up",
        refresh: "fa fa-spinner",
        toggleOff: "fa fa-toggle-off",
        toggleOn: "fa fa-toggle-on",
        columns: "fa fa-th-list",
        fullscreen: "fa fa-arrows-alt",
        detailOpen: "fa fa-plus",
        detailClose: "fa fa-minus",
        search: "fa fa-search",
        clearSearch: "fa fa-trash",
        sortAsc: "fa fa-sort-asc",
        sortDesc: "fa fa-sort-desc",
        sortUnsort: "fa fa-sort",
        filter: "fa fa-filter",
        all: "fa fa-check-square",
        none: "fa fa-square",
        noMatch: "fa fa-times-circle",
        paginationSwitch: "fa fa-sort",
        export: "fa fa-download",
    };
}

function Alert(type, message) {
    switch (type) {
        case "success":
            $.notify(
                '<i class="icofont icofont-verification-check"></i>' + message,
                {
                    type: "success",
                    allow_dismiss: true,
                    delay: 2000,
                    showProgressbar: true,
                    timer: 300,
                    z_index: 1127,
                    animate: {
                        enter: "animated fadeInDown",
                        exit: "animated fadeOutUp",
                    },
                }
            );
            break;
        case "error":
            $.notify('<i class="icofont icofont-ui-close"></i>' + message, {
                type: "danger",
                allow_dismiss: true,
                delay: 2000,
                showProgressbar: true,
                timer: 300,
                z_index: 1127,
                animate: {
                    enter: "animated fadeInDown",
                    exit: "animated fadeOutUp",
                },
            });
            break;
        case "warning":
            $.notify('<i class="icofont icofont-warning"></i>' + message, {
                type: "warning",
                allow_dismiss: true,
                delay: 2000,
                showProgressbar: true,
                timer: 300,
                z_index: 1127,
                animate: {
                    enter: "animated fadeInDown",
                    exit: "animated fadeOutUp",
                },
            });
            break;
        case "info":
            $.notify('<i class="fa fa-bell-o"></i>' + message, {
                type: "theme",
                allow_dismiss: true,
                delay: 2000,
                showProgressbar: true,
                timer: 300,
                z_index: 1127,
                animate: {
                    enter: "animated fadeInDown",
                    exit: "animated fadeOutUp",
                },
            });
            break;
    }
}

// Select2 Global
$.fn.select2.defaults.set("theme", "bootstrap-5");
$.fn.select2.defaults.set("width", "100%");

// $(".js-select-2").select2({
//     allowClear: true,
//     dropdownParent: $(".modal").length > 0 ? $(".modal") : null,
//     minimumInputLength: 1,
//     ajax: {
//         // instead of writing the function to execute the request we use Select2's convenient helper
//         url: function (params) {
//             console.warn(params.q);

//             // get this attribute data url
//         },
//         dataType: "json",
//         quietMillis: 250,
//         data: function (term, page) {
//             return {
//                 term: term, // search term
//             };
//         },
//         results: function (data, page) {
//             console.warn(data.data);

//             // parse the results into the format expected by Select2.
//             // since we are using custom formatting functions we do not need to alter the remote JSON data
//             return { results: data.data };
//         },
//         cache: true,
//     },
//     // initSelection: function (element, callback) {
//     //     // the input tag has a value attribute preloaded that points to a preselected repository's id
//     //     // this function resolves that id attribute to an object that select2 can render
//     //     // using its formatResult renderer - that way the repository name is shown preselected
//     //     var id = $(element).val();
//     //     if (id !== "") {
//     //         $.ajax("https://api.github.com/repositories/" + id, {
//     //             dataType: "json",
//     //         }).done(function (data) {
//     //             callback(data);
//     //         });
//     //     }
//     // },
//     // formatResult: repoFormatResult, // omitted for brevity, see the source of this page
//     // formatSelection: repoFormatSelection, // omitted for brevity, see the source of this page
//     // dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller
//     // escapeMarkup: function (m) {
//     //     return m;
//     // }, // we do not want to escape markup since we are displaying html in results
// });

$(".js-select-2").each(function (index, element) {
    var item = $(element);
    if (item.data("url")) {
        InitSelect2(item, {
            url: item.data("url"),
            initialValue: item.data("value") ? item.data("value") : null,
        });
    } else {
        item.select2(
            {
                allowClear: true,
            },
            function () {
                if (item.data("value")) {
                    item.val(item.data("value")).trigger("change");
                }
            }
        );
    }
});

function InitSelect2(element, options) {
    $.ajax({
        type: "GET",
        url: options.url,
        dataType: "json",
        beforeSend: function () {
            element.addClass("loading");
        },
        success: function (data) {
            element.removeClass("loading");
            element.empty();
            element.select2({
                allowClear: true,
                dropdownParent: $(".modal").length > 0 ? $(".modal") : null,
                data: data.data,
            });
            if (options.initialValue) {
                element.val(options.initialValue).trigger("change");
            }
        },
        complete: function () {
            element.removeClass("loading");
        },
    });
}

// <input type='text' onkeypress='validateNumber(event)' />
function validateNumber(evt) {
    var theEvent = evt || window.event;
    var key = theEvent.keyCode || theEvent.which;
    key = String.fromCharCode(key);
    var regex = /[0-9]|\./;
    if (!regex.test(key)) {
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
    }
}

// Only numbers validate before paste text
$(document).on("paste", ".numInput", function (e) {
    e.preventDefault();
    var text = e.originalEvent.clipboardData.getData("text");
    text = text.replace(/[^0-9]/g, "");
    $(this).val(text);
});

$(document).on("click", ".one-checked", function () {
    $(".one-checked").not(this).prop("checked", false);
});

// var startTime = "10:00";
// var endTime = "12:00";
// var hours = convertTimeToHours(startTime, endTime);
function convertTimeToHours(startTime, endTime) {
    // Function to convert time to minutes
    function timeToMinutes(time) {
        var timeParts = time.split(":");
        var hours = parseInt(timeParts[0], 10);
        var minutes = parseInt(timeParts[1], 10);
        return hours * 60 + minutes;
    }

    // Convert both times to minutes
    var startMinutes = timeToMinutes(startTime);
    var endMinutes = timeToMinutes(endTime);

    // Calculate the difference in minutes
    var differenceInMinutes = endMinutes - startMinutes;

    // Convert minutes back to hours
    var differenceInHours = differenceInMinutes / 60;
    return differenceInMinutes;
}
