$(document).ready(function(){
    var timer;

    $("#form_nameVern").on('keyup', function() { // everytime keyup event
        clearTimeout(timer);
        timer = window.setTimeout(function() {
            var input = $("#form_nameVern").val(); // We take the input value
            if ( input.length >= 2 ) { // Minimum characters = 2 (you can change)
                $('#match').html('<img src="' + window.loader + '" />'); // Loader icon apprears in the <div id="match"></div>
                var data = {input: input}; // We pass input argument in Ajax
                $.ajax({
                    type: "POST",
                    url: "ajax/autocomplete/update/data", // call the php file ajax/tuto-autocomplete.php (check the routine we defined)
                    data: data, // Send dataFields var
                    dataType: 'json', // json method
                    timeout: 5000,
                    success: function(response){ // If success
                        $('#match').html(response.speciesList); // Return data (UL list) and insert it in the <div id="match"></div>
                        $('#matchList li').on('click', function() { // When click on an element in the list
                            $('#form_nameVern').val($(this).text()); // Update the field with the new element
                            $('#match').text(''); // Clear the <div id="match"></div>
                        });
                    },
                    error: function(err) { // if error
                        console.log(err);
                        $('#match').text('Problem!');
                    }
                });
            } else {
                $('#match').text(''); // If less than 2 characters, clear the <div id="match"></div>
            }
        }, 1000);
    });
});