$(document).ready(function() {
    $('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $('.autocomplete').prop('disabled', true);
    $.get('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json', function(res) {
        var list = JSON.parse(res);
        $('.autocomplete').prop('disabled', false);
        $('.autocomplete').autocomplete({
            minLength: 1,
            source: function(req, res) {
                var filtered = list.filter(l => l.Symbol.indexOf(req.term.toUpperCase()) != -1 );

                res(filtered);
            },
            select: function(event, ui) {
                $('.autocomplete').val(ui.item.Symbol);
                $('#search_form_company_code').val(ui.item.Symbol);
                $('#search_form_company_name').val(ui.item['Company Name']);

                return false;
            }
        }).data('ui-autocomplete')._renderItem = function (ul, item) {
            return $('<li>')
                .append('<a>' + item['Company Name'] + ' ( ' + item.Symbol + ' ) </a>')
                .appendTo(ul);
        };
    });

    function getDate(element)
    {
        var today = new Date();
        var selectedDate = new Date(element.value);

        if (selectedDate > today) {
            return today;
        }

        return selectedDate;
    }

    var from = $('.from-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        maxDate: new Date()
    }).on('change', function() {
        to.datepicker('option', 'minDate', getDate(this))
    })

    var to = $('.to-datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
        maxDate: new Date()
    }).on('change', function() {
        from.datepicker('option', 'maxDate', getDate(this));
    });

});


var chart = {
    data: {
        low: new Array(),
        high: new Array()
    },
    labels: new Array(),
    init: function(jsonData) {
        var jsonData = JSON.parse(jsonData);
        this.parseData(jsonData);
        this.initChart();
    },
    parseData: function(data) {
        $.each(data, function( key, val ) {
            var date = new Date(val.date);
            var dateLabel = date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear();

            this.labels.push(dateLabel);
            this.data.low.push(val.low);
            this.data.high.push(val.high);
        }.bind(this));
    },
    initChart: function() {
        const data = {
            labels: this.labels,
            datasets: [
                {
                    label: 'Low',
                    data: this.data.low,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)'
                },
                {
                    label: 'High',
                    data: this.data.low,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)'
                }

            ]
        };
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            },
        };
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );


    }
};


