document.addEventListener("DOMContentLoaded", function () {

    if ($("#price-mask").length > 0) {
        var currencyMask = IMask(document.getElementById("price-mask"), {
            mask: [
                { mask: "" },
                {
                    mask: "num",
                    lazy: false,
                    blocks: {
                        num: {
                            mask: Number,
                            scale: 2,
                            padFractionalZeros: true,
                            radix: ".",
                            mapToRadix: ["."],
                        },
                    },
                },
            ],
        });
    }
    if ($("#preparation_time").length > 0) {
        $title = $("#preparation_time").data('time_formate')
        var currencyMask = IMask(document.getElementById("preparation_time"), {
            mask: [
                { mask: "" },
                {
                    mask: "num " + ($title ? $title :'minutes') ,
                    lazy: false,
                    blocks: {
                        num: {
                            mask: Number,
                            scale: 0,
                            normalizeZeros: false,
                            radix: ":",
                            mapToRadix: ["."],
                            max: 300,
                        },
                    },
                },
            ],
        });
    }
});
