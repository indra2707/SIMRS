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

// Alert Message Notification
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

// Clear Input Field
function clearFormInputFields(element) {
    var elements = $(element);
    elements.removeClass('was-validated');
    elements.find('input, select, textarea').val('');
    elements.find('input:radio, input:checkbox').prop('checked', false);
    // Select2 Reset
    elements.find('select').val(null).trigger('change');
    elements.find('input[name="_token"]').val($('meta[name="csrf-token"]').attr('content'));
}
