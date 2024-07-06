var errorSound = new Howl({
    src: ['/assets/files/error.mp3'],
    html5: true,
    volume: 0.3,
});
var successSound = new Howl({
    src: ['/assets/files/success.mp3'],
    html5: true,
    volume: 0.3,
});


$(document).ready(function () {
    $('.select2').selectize();
});


// copyclipboard-alert
$(function() {
    $(".copy-message").click(function() {
        let message = $($(this).data('message')).html();
        navigator.clipboard.writeText(message);
        $(".copyclipboard-alert").show();
        setTimeout(() => {
            $(".copyclipboard-alert").hide();
        }, 300);
    });
});