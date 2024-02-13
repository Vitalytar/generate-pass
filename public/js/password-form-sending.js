$(document).ready(function () {
    $('#passwordForm').submit(function (event) {
        event.preventDefault();

        $.ajax({
            type: 'POST',
            url: '/',
            data: $(this).serialize(),
            success: function (response) {
                $('#passwordResult').html('<h2>Generated Password:</h2><p>' + response.password + '</p>');
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    });
});
