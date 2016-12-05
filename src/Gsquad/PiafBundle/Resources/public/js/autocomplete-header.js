$(document).ready(function(){
    var timer;

    $("#navbar-search").on('keyup', function() {
        clearTimeout(timer);
        timer2 = window.setTimeout(function() {
            var input = $("#navbar-search").val();
            if ( input.length >= 2 ) {
                $('#navbar-match').html('<img src="' + window.loader + '" />');
                var data = {input: input};
                $.ajax({
                    type: "POST",
                    url: "ajax/autocomplete/update/data",
                    data: data,
                    dataType: 'json',
                    timeout: 8000,
                    success: function(response){
                        $('#navbar-match').html(response.speciesList);
                        $('#matchList li').on('click', function() {
                            $('#navbar-search').val($(this).text());
                            $('#navbar-match').text('');
                        });
                    },
                    error: function() {
                        $('#navbar-match').text('Erreur');
                    }
                });
            } else {
                $('#navbar-match').text('');
            }
        }, 1000);
    });
});