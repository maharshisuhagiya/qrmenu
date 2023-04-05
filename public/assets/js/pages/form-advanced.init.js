document.addEventListener("DOMContentLoaded", function () {
    var e = document.querySelectorAll(".choice-picker");
    for (i = 0; i < e.length; ++i) {
        var a = e[i];
        var plshoder= $(a).attr("placeholder");
        new Choices(a, {
            placeholderValue: plshoder,
             itemSelectText: '',
             classNames: {
                containerInner:"choices__inner form-select",
            },
        });
        var is_remove =$(a).data('remove_attr');
        if(is_remove)
       $(a).parents('.choices').removeAttr(is_remove)
    }

    var e = document.querySelectorAll(".choice-picker-multiple");
    var choicesele = [];
    for (i = 0; i < e.length; ++i) {
        var a = e[i];
        $(a).find('[value=""]').prop("disabled", 1);
        var removeable = a.getAttribute("data-remove");
        var placeholder = a.getAttribute("placeholder") ?? "select";
        choicesele[i] = new Choices(a, {
            removeItemButton: removeable,
            itemSelectText: placeholder,
             itemSelectText: '',
            classNames: {
                containerInner:"form-select pb-0 pt-1",
                containerOuter: "choices mb-0 form-group",
            },
        });

        choicesele[i].passedElement.element.addEventListener(
            "change",
            function (event) {
                var ele = $(event.target);
                if (ele) {
                    var newArray = ele.val().filter(function (v) {
                        return v !== "";
                    });
                    if ((refEle = $("#" + ele.data("id")))) {
                        refEle.val(newArray.join(","));
                    }
                    refEle.click();
                }
            },
            false
        );
    }
    var e = document.querySelectorAll(".form-select");

    if ($(".choices-text-unique-values").length > 0) {

        new Choices(".choices-text-unique-values", {
            paste: !1,
            duplicateItemsAllowed: !1,
            editItems: !0,
            removeItemButton: true,
             itemSelectText: '',
             classNames: {

             }
        });
    }
});

