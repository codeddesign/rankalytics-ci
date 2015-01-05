function toggle_visibility(id) {
    var current, show;

    $.each($('.link_toggle'), function (k, el) {
        current = $(el);
        show = (current.attr('id') == id) ? ((current.css('display') == 'none') ? 1 : 0): 0;
        (show) ? current.show() : current.hide();
    });
}