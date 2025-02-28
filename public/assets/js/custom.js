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
