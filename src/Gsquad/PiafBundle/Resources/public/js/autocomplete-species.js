$(document).ready(function(){
    var timer;

    $("#form_nameVern").on('keyup', function() {
        clearTimeout(timer);
        timer = window.setTimeout(function() {
            var input = $("#form_nameVern").val();
            if ( input.length >= 2 ) {
                $("#navbar-search-button").show();
                $('#match').html('<img src="' + window.loader + '" />');
                var data = {input: input};
                $.ajax({
                    type: "POST",
                    url: "ajax/autocomplete/update/data",
                    data: data,
                    dataType: 'json',
                    timeout: 5000,
                    success: function(response){
                        $('#match').html(response.speciesList);
                        $('#matchList li').on('click', function() {
                            $('#form_nameVern').val($(this).text());
                            $('#match').text('');
                        });
                    },
                    error: function() {
                        $('#match').text('Erreur');
                    }
                });
            } else {
                $('#match').text('');
            }
        }, 1000);
    });
});