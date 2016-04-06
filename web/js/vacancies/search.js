(function ($) {
    $(function () {
        var $queryName = $('#vacanciessearchform-queryname'),
            $queryDescription = $('#vacanciessearchform-querydescription'),
            selectizeOptions = {
                delimiter: ',',
                persist: false,
                create: function (input) {
                    return {
                        value: input,
                        text: input
                    };
                }
            };

        if ($queryName.length != 0 && $queryDescription.length != 0) {
            $queryName.selectize(selectizeOptions);
            $queryDescription.selectize(selectizeOptions);
        }
    });
})(jQuery);