$(document).ready(function(){
    var timer;

    $("#form_nameVern").on('keyup', function() {
        clearTimeout(timer);
        timer = window.setTimeout(function() {
            var input = $("#form_nameVern").val();
            if ( input.length >= 2 ) {
                $("#navbar-search-button").show();
                $('#match').show();
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
                            $('#match').hide();
                        });
                        $(document).mouseup(function (e)
                        {
                            var container = $("#match");

                            if (!container.is(e.target) // if the target of the click isn't the container...
                                && container.has(e.target).length === 0) // ... nor a descendant of the container
                            {
                                container.hide();
                            }
                        });
                    },
                    error: function() {
                        $('#match').text('Erreur');
                    }
                });
            } else {
                $('#match').text('');
                $('#match').hide();
            }
        }, 1000);
    });

    $("input[id='form_range']").TouchSpin({
        verticalbuttons: true,
        verticalupclass: 'glyphicon glyphicon-plus',
        verticaldownclass: 'glyphicon glyphicon-minus',
        min: 0,
        max: 20,
        step: 0.001,
        decimals: 3
    });
});