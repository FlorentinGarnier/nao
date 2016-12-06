$(document).ready(function(){
    var x = $("#form_latitude");
    var y = $("#form_longitude");
    $('#bouton-validation').hide();

    function showOther() {
        $('#form_especeautre').val("");

        if($("#form_espece option:selected").text() == "Autre") {
            $('.to-hide').show();
        } else {
            $('.to-hide').hide();
        }
    }

    function rewrite() {
        if($('#form_latitude').val() != "") {
            $('.preview-geo').html("Latitude : " + $('#form_latitude').val() + "°, longitude : " + $('#form_longitude').val() + "°");
        }

        if($('#form_longitude').val() != "") {
            $('.preview-geo').html("Latitude : " +$('#form_latitude').val() + "°, longitude : " + $('#form_longitude').val() + "°");
        }

        if($("#form_espece option:selected").text() != "Autre") {
            $('.preview-espece').html($("#form_espece option:selected").text());
        } else {
            if($('#form_especeautre').val() != "") {
                $('.preview-espece').html($('#form_especeautre').val());
            }
        }

        if($('#form_dateObservation').val() != "") {
            $('.preview-date').html("Observé le " + $('#form_dateObservation').val());
        }

        if($('#form_observateur').val() != "") {
            $('.preview-observateur').html("Observé par " + $('#form_observateur').val());
        }

        if($('#form_city').val() != "") {
            $('.preview-lieu').html("Lieu de l'observation : " + $('#form_city').val() + ", " + $("#form_departement option:selected").text());
        }

        if($("#form_departement option:selected").text() != "Non précisé") {
            $('.preview-lieu').html("Lieu de l'observation : " + $('#form_city').val() + ", " + $("#form_departement option:selected").text());
            requiredEntry();
        }
    }

    function requiredEntry() {
        if(
            $('#form_city').val() != ""
            && $("#form_departement option:selected").text() != "Non précisé"
            && $('#form_dateObservation').val() != ""
            && ($("#form_espece option:selected").text() != "Autre" || $('#form_especeautre').val() != "")
        ) {
            $('#bouton-validation').show();
        } else {
            $('#bouton-validation').hide();
        }
    }

    $('#form_espece').on("change", function() {
        $('.preview-espece').html($("#form_espece option:selected").text());
        showOther();
        requiredEntry();
    });

    $('#form_dateObservation').on("change", function() {
        $('.preview-date').html("Observé le " + $(this).val());
        requiredEntry();
    });

    $('#form_dateObservation').on("keydown", function() {
        return false;
    });

    $('#form_observateur').on("change keyup", function() {
        $('.preview-observateur').html("Observé par " + $(this).val());
    });

    $('#form_city').on("change keyup", function() {
        $('.preview-lieu').html("Lieu de l'observation : " + $(this).val() + ", " + $("#form_departement option:selected").text());
        requiredEntry();
    });

    $('#form_departement').on("change change", function() {
        $('.preview-lieu').html("Lieu de l'observation : " + $('#form_city').val() + ", " + $("#form_departement option:selected").text());
        requiredEntry();
    });

    $('#form_especeautre').on("change keyup", function() {
        $('.preview-espece').html($(this).val());
        requiredEntry();
    });

    x.on("change keyup", function() {
        $('.preview-geo').html("Latitude : " + $(this).val() + "°, longitude : " + $('#form_longitude').val() + "°");
    });

    x.keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 188]) !== -1 ||
                // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
                // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    y.on("change keyup", function() {
        $('.preview-geo').html("Latitude : " +$('#form_latitude').val() + "°, longitude : " + $(this).val() + "°");
    });

    y.keydown(function (e) {
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190, 188]) !== -1 ||
                // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
                // Allow: Ctrl+C
            (e.keyCode == 67 && e.ctrlKey === true) ||
                // Allow: Ctrl+X
            (e.keyCode == 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

    $("#form_image").on('change', function() {
        //Get count of selected files
        var countFiles = $(this)[0].files.length;
        var imgPath = $(this)[0].value;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        var image_holder = $(".image-holder");
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
    rewrite();
});