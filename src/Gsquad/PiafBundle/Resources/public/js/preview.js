$(document).ready(function(){
    var x = $("#form_latitude");
    var y = $("#form_longitude");

    $('#form_espece').on("change", function() {
        $('#preview-espece').html($("#form_espece option:selected").text());
    });

    $('#form_dateObservation').on("change", function() {
        $('#preview-date').html("Observé le " + $(this).val());
    });

    $('#form_observateur').on("keyup", function() {
        $('#preview-observateur').html("Observé par " + $(this).val());
    });

    $('#form_city').on("keyup", function() {
        $('#preview-lieu').html("Lieu de l'observation : " + $(this).val() + ", " + $("#form_departement option:selected").text());
    });

    $('#form_departement').on("change", function() {
        $('#preview-lieu').html("Lieu de l'observation : " + $('#form_city').val() + ", " + $("#form_departement option:selected").text());
    });

    x.on("keyup", function() {
        $('#preview-geo').html("Latitude : " + $(this).val() + "°, longitude : " + $('#form_longitude').val() + "°");
    });

    y.on("keyup", function() {
        $('#preview-geo').html("Latitude : " +$('#form_latitude').val() + "°, longitude : " + $(this).val() + "°");
    });

    $("#form_image").on('change', function() {
        //Get count of selected files
        var countFiles = $(this)[0].files.length;
        var imgPath = $(this)[0].value;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = $("#image-holder");
        image_holder.empty();
        if (extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if (typeof(FileReader) != "undefined") {
                //loop for each file selected for uploaded.
                for (var i = 0; i < countFiles; i++)
                {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $("<img />", {
                            "src": e.target.result,
                            "class": "thumb-image img-observation"
                        }).appendTo(image_holder);
                    };
                    image_holder.show();
                    reader.readAsDataURL($(this)[0].files[i]);
                }
            }
        } else {
            alert("Merci de sélectionner une image");
        }
    });

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                x.val(position.coords.latitude);
                y.val(position.coords.longitude);
            });
        }
    }

    getLocation();
});