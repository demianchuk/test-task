/* Semantic progress bar */
var progressbar = $('.progress');
if ($(progressbar).length) {
    $(progressbar).progress({
        percent: $(progressbar).attr('data-value')
    });
}