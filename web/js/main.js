(function ($) {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="taggable"]').selectize({
            delimiter: ',',
            persist: false,
            create: function (input) {
                return {
                    value: input,
                    text: input
                };
            }
        });
    })
})(jQuery);