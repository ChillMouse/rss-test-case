$(function (){
    const button = $('#send-ajax');
    const form = $('form');
    const forOutput = $('main');

    function sendFormToApi() {
        $.ajax({
            url: 'php/reader.php',
            method: 'post',
            data: form.serialize(),
            error: function () {
                console.error('Ошибка');
            },
            success: function (e) {
                if (e.toString().length != 0) {
                    forOutput.html(e);
                } else {
                    forOutput.text("Пусто...");
                }
            }
        });
    }

    button.click(sendFormToApi);
});