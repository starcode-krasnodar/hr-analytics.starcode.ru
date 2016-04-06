(function ($) {
    $(function () {
        var $hcEmployment = $('#hc-employment'),
            $hcSchedule = $('#hc-schedule'),
            $queryName = $('#vacanciesanalyticsform-queryname'),
            $queryDescription = $('#vacanciesanalyticsform-querydescription'),
            defaultOptions = {
                title: {
                    text: null
                },
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                tooltip: {
                    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: false
                        },
                        showInLegend: true
                    }
                }
            },
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

        if ($hcEmployment.length != 0 && $hcSchedule.length != 0) {
            $hcEmployment.highcharts($.extend(defaultOptions, {
                data: {
                    table: 'hc-employment-datatable'
                }
            }));

            $hcSchedule.highcharts($.extend(defaultOptions, {
                data: {
                    table: 'hc-schedule-datatable'
                }
            }));
        }

        if ($queryName.length != 0 && $queryDescription.length != 0) {
            $queryName.selectize(selectizeOptions);
            $queryDescription.selectize(selectizeOptions);
        }
    });
})(jQuery);