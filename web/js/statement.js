$('.chart-button').on('click', function(e) {
    e.preventDefault();
    showPreloader();
    $.ajax({
        url: $(this).attr('href'),
        type: 'get',
        success: function (data) {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(function() {
                drawChart(prepareDate(data));
            });
        },
        error: function () {
            hidePreloader();
            alert('Something went wrong... :(');
        }

    });
    return false;
});

window.addEventListener('resize', resize);

function resize() {
    if ($('#modal').is(':visible')) {
        drawChart(window.transactions);
    }
}

function prepareDate(data) {
    var rawData = JSON.parse(data);
    
    // an array of objects to array of arrays conversion
    window.transactions = $.map(rawData, function(transaction, key) {
        return [[ new Date(transaction.time), parseFloat(transaction.profit) ]];
    });
    
    return window.transactions;
}

function showPreloader() {
    $('.preloader-fog').show();
    $('.sk-circle').fadeIn();
}

function hidePreloader() {
    $('.preloader-fog').hide();
    $('.sk-circle').fadeOut();
}

function drawChart(transactions) {
    var chartData = new google.visualization.DataTable();
    chartData.addColumn('datetime', 'Time');
    chartData.addColumn('number', 'Profit');
    chartData.addRows(transactions);
    
    var chart = new google.visualization.LineChart(document.getElementById('chart'));
    google.visualization.events.addListener(chart, 'ready', hidePreloader);
    if (!$('#modal').is(':visible')) {
        $('#modal').modal('show');
    } else {
        $('#modal').trigger('shown.bs.modal');
    }
    $('#modal').on('shown.bs.modal', function (e) {
        var options = {
            'chartArea': {'width': '80%', 'height': '80%'},
            width: $('#modal .modal-body').width(),
            height: $('#modal .modal-body').height() * 0.9,
            explorer : {
                actions: ['dragToZoom', 'rightClickToReset'],
            },
          };
        chart.draw(chartData, options);
    });
    $('#modal').on('hide.bs.modal', function (e) {
        $('#chart').html('');
    });
}