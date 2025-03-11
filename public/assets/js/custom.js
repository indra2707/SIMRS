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
                dropdownParent: options.dropdownParent,
                // data: data.data,
                ajax: {
                    type: "GET",
                    url: options.url,
                    dataType: "json",
                    data: function (params) {
                        return {
                            term: params.term,
                        };
                    },
                    processResults: function (data) {
                        return { results: data.data };
                    },
                },
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

function InitSelect2Array(element, options) {
    // element.removeClass("loading");
    element.select2({
        allowClear: true,
        dropdownParent: $(".modal").length > 0 ? $(".modal") : null,
        data: options.data,
    });
    if (options.initialValue) {
        element.val(options.initialValue).trigger("change");
    }
}
