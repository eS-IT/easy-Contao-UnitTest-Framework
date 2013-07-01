/**
 * Created with JetBrains PhpStorm.
 * User: pfroch
 * Date: 29.05.13
 * Time: 18:47
 * To change this template use File | Settings | File Templates.
 */

// Groesse und Position des Modal-Windows bestimmen
$(function() {
    var viewportWidth = $(window).width() / 2;
    var viewportHeight = $(window).height() / 2;
    $("[id^='modal_']").dialog({
        height: viewportHeight,
        width: viewportWidth,
        autoOpen: false,
        modal: true,
        position: "center",
        show: "puff",
        hide: "scale",
        buttons: {
            Ok: function() {
                $( this ).dialog( "close" );
            }
        }
    });
});

// chosen for test select
$(".chzn-select").chosen({'search_contains': true});

// Modal-Window oeffen
function openDialog(box){
        $("#modal_" + box).dialog( "open" );
        return false;
}

// Spinner einblenden
function spin(target){
    elem            = $(target);
    elem.empty();
    elem.addClass('spinner');

    var opts        = {
        lines: 10,              // 13 - The number of lines to draw
        length: 10,             // 20 - The length of each line
        width: 6,              // 10 - The line thickness
        radius: 14,             // 30 - The radius of the inner circle
        corners: 1,             // 1 - Corner roundness (0..1)
        rotate: 0,              // 0 - The rotation offset
        direction: 1,           // 1 - 1: clockwise, -1: counterclockwise
        color: '#000',          // #000 - #rgb or #rrggbb
        speed: 1,               // 1 - Rounds per second
        trail: 60,              // 60 - Afterglow percentage
        shadow: false,          // false - Whether to render a shadow
        hwaccel: false,         // false - Whether to use hardware acceleration
        className: 'spinner',   // 'spinner' - The CSS class to assign to the spinner
        zIndex: 2e9,            // 2e9 - The z-index (defaults to 2000000000)
        top: 'auto',            // 'auto' - Top position relative to parent in px
        left: 'auto'            // 'auto' - Left position relative to parent in px
    };

    var spinner = new Spinner(opts).spin();
    elem.append(spinner.el);
    $('form#testform').submit();
}