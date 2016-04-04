var $select2 = $('.js-area-select2');

$(function () {
    $select2.select2({
        language: 'ru',
        ajax: {
            data: function (params) {
                return {
                    text: params.term
                };
            },
            processResults: function (data, params) {
                return {
                    results: data.items
                };
            }
        }
    });
});