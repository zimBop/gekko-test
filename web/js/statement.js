$('.chart-button').on('click', function(e) {
    e.preventDefault();
    showPreloader();
    $.ajax({
        url: $(this).attr('href'),
        type: 'get',
        success: function (data) {
            drawChart(JSON.parse(data));
        },
        error: function () {
            hidePreloader();
            alert('Something went wrong... :(');
        }

    });
    return false;
});

function showPreloader() {
    $('.preloader-fog').show();
    $('.sk-circle').fadeIn();
}

function hidePreloader() {
    $('.preloader-fog').hide();
    $('.sk-circle').fadeOut();
}

function drawChart(data) {
    hidePreloader();
}