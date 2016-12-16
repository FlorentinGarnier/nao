function initMap() {
    var myLatLng = [];
    var markersArray = [];

    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 46.403709, lng: 2.502637},
        scrollwheel: false,
        zoom: 5
    });

    for(var x = 0; x < latitudes.length; x++ ) {
        if(scale != 'chercheur') {
            var latModified = latitudes[x] + 0.0035;
            var longModified = longitudes[x] + 0.0035;

            myLatLng[x] = {lat: latModified, lng: longModified};
        } else {
            myLatLng[x] = {lat: latitudes[x], lng: longitudes[x]};
        }
    }

    function setIconSize(size) {
        var icon = {
            url: circle,
            scaledSize: new google.maps.Size(18 * Math.pow(2, size), 18 * Math.pow(2, size)),
            origin: new google.maps.Point(0,0),
            anchor: new google.maps.Point(9 * Math.pow(2, size), 9 * Math.pow(2, size))
        };

        return icon;
    }

    var zoomIcons = [];

    if(scale != 'adherent') {
        zoomIcons = [null, null, null, null, null];
        for(var a = 1; a<7; a++) {
            zoomIcons.push(setIconSize(a));
        }
    } else {
        zoomIcons = [null, null, null, null, null, null, null, null, null, null, null, null];
        for(var c = 1; c<11; c++) {
            zoomIcons.push(setIconSize(c));
        }
    }

    var title = "observation";

    for(var y = 0; y < myLatLng.length; y++ ) {

        if(scale == 'chercheur') {
            title += " Latitude: " + myLatLng[y].lat;
            title += ", longitude: " + myLatLng[y].lng;
        }

        markersArray[y] = new google.maps.Marker({
            position: myLatLng[y],
            map: map,
            title: title
        });

        if(scale != 'chercheur') {
            markersArray[y].setIcon(zoomIcons[map.getZoom()]);
        }
    }

    var maxZoomLevel;

    if(scale == 'adherent') {
        maxZoomLevel = 15;
    } else {
        maxZoomLevel = 10;
    }

    google.maps.event.addListener(map, 'zoom_changed', function() {
        if(scale != 'chercheur') {
            if (map.getZoom() > maxZoomLevel) map.setZoom(maxZoomLevel);
        }

        function clearOverlays() {
            for (var i = 0; i < markersArray.length; i++ ) {
                markersArray[i].setMap(null);
            }
            markersArray.length = 0;
        }

        clearOverlays();

        title = "observation";

        for(var y = 0; y < myLatLng.length; y++ ) {

            if(scale == 'chercheur') {
                title += " Latitude: " + myLatLng[y].lat;
                title += ", longitude: " + myLatLng[y].lng;
            }

            markersArray[y] = new google.maps.Marker({
                position: myLatLng[y],
                map: map,
                title: title
            });

            if(scale != 'chercheur') {
                markersArray[y].setIcon(zoomIcons[map.getZoom()]);
            }
        }
    });
}