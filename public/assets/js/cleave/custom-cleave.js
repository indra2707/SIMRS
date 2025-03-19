$(".ktp-number").each(function (index, ele) {
    new Cleave(ele, {
        delimiter: " ",
        blocks: [4, 2, 6, 4],
        numericOnly: true,
    });
});

$(".phone-number").each(function (index, ele) {
    new Cleave(ele, {
        prefix: "+62",
        delimiters: [" "],
        blocks: [3, 3, 9],
        numericOnly: true,
    });
});

$(".npwp-number").each(function (index, ele) {
    new Cleave(ele, {
        delimiters: [".", ".", ".", "-", "."],
        blocks: [2, 3, 3, 1, 3, 4],
        numericOnly: true,
        uppercase: true,
    });
});

$(".rupiah-number").each(function (index, ele) {
    new Cleave(ele, {
        numeral: true,
        prefix: "Rp ",
        signBeforePrefix: $(this).val() < 0,
    });
});

function InitCleaveJs(element, options) {
    switch (options.type) {
        case "ktp":
            var ele = new Cleave(element, {
                delimiter: " ",
                blocks: [4, 2, 6, 4],
                numericOnly: true,
            });
            break;
        case "phone":
            var ele = new Cleave(element, {
                prefix: "+62",
                delimiters: [" "],
                blocks: [3, 3, 9],
                numericOnly: true,
            });
            break;
        case "npwp":
            var ele = new Cleave(element, {
                delimiters: [".", ".", ".", "-", "."],
                blocks: [2, 3, 3, 1, 3, 4],
                numericOnly: true,
                uppercase: true,
            });
            break;
        case "rupiah":
            var ele = new Cleave(element, {
                numeral: true,
                prefix: "Rp ",
                // signBeforePrefix: $(this).val() < 0,
            });
            break;
        default:
            break;
    }
    if (options.initValue) {
        ele.setRawValue(options.initValue);
    }
}
