$(".js-select-2").each(function (index, element) {
    var item = $(element);
    if (item.data("url")) {
        InitSelect2(item, {
            url: item.data("url"),
            initialValue: item.data("value") ? item.data("value") : null,
        });
    } else {
        item.select2({
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
        url: options.url,
        method: "GET",
        dataType: "json",
        async: false,
        data: {
            values: options.initialValue
        },
        beforeSend: function () {
            element.addClass("loading");
        },
        success: function (data) {
            element.removeClass("loading");
            element.empty();
            element.select2({
                allowClear: true,
                dropdownParent: options.dropdownParent ? options.dropdownParent : null,
                ajax: {
                    type: "GET",
                    url: options.url,
                    dataType: "json",
                    data: function (params) {
                        return {
                            search: params.term,
                        };
                    },
                    processResults: function (data) {
                        // var results = [];
                        // $.each(data.data, function (index, item) {
                        //     results.push({
                        //         id: item.id,
                        //         text: item.text,
                        //     });
                        // })
                        return {
                            results: data.data
                        };
                    },
                },
                data: data.data,
            });
            element.val(options.initialValue).trigger("change");
        },
    });
}

function InitSelect2Array(element, options) {
    // element.removeClass("loading");
    element.select2({
        allowClear: true,
        dropdownParent: options.dropdownParent ? options.dropdownParent : null,
        data: options.data,
    });
    if (options.initialValue) {
        element.val(options.initialValue).trigger("change");
    }
}
