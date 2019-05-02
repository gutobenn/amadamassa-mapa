// FORM
var address_in_area = false;
var coleta_area = 0;

var opcoesSelect = [
  ["Coleta na Amada Massa - segunda-feira", "Coleta na Amada Massa - quinta-feira"], // nenhuma área
  ["Coleta na Amada Massa - segunda-feira", "Coleta na Amada Massa - quinta-feira", "Entrega em casa - segunda-feira"],
  ["Coleta na Amada Massa - segunda-feira", "Coleta na Amada Massa - quinta-feira", "Entrega em casa - quinta-feira"],
  ["Coleta na Amada Massa - segunda-feira", "Coleta na Amada Massa - quinta-feira", "Entrega em casa - terça-feira"],
  ["Coleta na Amada Massa - segunda-feira", "Coleta na Amada Massa - quinta-feira", "Retirada no ponto X - quarta-feira"],
  ["Coleta na Amada Massa - segunda-feira", "Coleta na Amada Massa - quinta-feira", "Retirada no ponto Y - sexta-feira"]
];

// Validate fields before submission
document.getElementById("formpedido").addEventListener("submit", function(e) { // TODO mudar
    e.preventDefault();

    var valid = true;

    if (!address_in_area){
        console.log("Endereço fora da área de entrega!");
        valid = false;
    }

    //if (valid) document.getElementById("formpedido").submit();
});


/**
 * Adds a KMLLayer based on the URL passed. Clicking on a marker
 * results in the balloon content being loaded into the right-hand div.
 * @param {string} src A URL for a KML file.
 */
function loadKmlLayer(src, map) {
    var kmlLayer = new google.maps.KmlLayer(src, {
        suppressInfoWindows: true,
        preserveViewport: false,
        map: map
    });
    google.maps.event.addListener(kmlLayer, 'click', function(event) {
        var content = event.featureData.infoWindowHtml;
        var testimonial = document.getElementById('capture');
        testimonial.innerHTML = content;
    });
}

function initAutocomplete() {
    var markers = [];
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {
            lat: -30.0446,
            lng: -51.1570
        },
        zoom: 13,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var geoXml = new geoXML3.parser({
        map: map
    });
    geoXml.parse("mapa.kml");
    // Create the search box and link it to the UI element.
    var coleta_field = document.getElementById('endereco_coleta');
    var geocoder = new google.maps.Geocoder();

    coleta_field.addEventListener('blur', function() {
    	geocodeAddress(geocoder, map);
    });

	// Trigger event if address already filled. (ex: user is logged and address is saved on database)
	if(coleta_field.value != ""){
	    	geocodeAddress(geocoder, map);
	}


	function geocodeAddress(geocoder, resultsMap) {
		if(coleta_field.value === "") return;

		var coleta = document.getElementById('endereco_coleta').value + " Porto Alegre, RS - Brazil";

		geocoder.geocode({'address': coleta}, function(results_coleta, status_coleta) {
				if (status_coleta != 'OK') {
					console.log('Endereço de coleta não encontrado. Verifique se o endereço está correto.');
					return;
				}
				var place_coleta = results_coleta[0];
				//resultsMap.setCenter(place.geometry.location); // TODO setcenter

				// Clear out the old markers.
				markers.forEach(function(marker) {
					marker.setMap(null);
				});
				markers = [];

				// For each place, get the icon, name and location.
				var bounds = new google.maps.LatLngBounds();
                                var coleta_infowindow = new google.maps.InfoWindow({
				    content: "Local de Coleta"
                                });

				// Create a marker for each place.
				var coleta_marker = new google.maps.Marker({
				    map: map,
				    label: 'C',
				    title: place_coleta.name,
				    position: place_coleta.geometry.location
				});
				coleta_marker.addListener('click', function() {
                                    coleta_infowindow.open(map, coleta_marker);
				});

				markers.push(coleta_marker);

				/*if (place.geometry.viewport) {
				    // Only geocodes have viewport.
				    bounds.union(place.geometry.viewport);
				} else {
				    bounds.extend(place.geometry.location);
				}*/ // TODO viwerport

				address_in_area = true;
				var coleta_in_area = false;


				for (var i = 0; i < geoXml.docs[0].gpolygons.length; i++) {
				    if (google.maps.geometry.poly.containsLocation(place_coleta.geometry.location, geoXml.docs[0].gpolygons[i])) {
					coleta_in_area = true;
					coleta_area = i;
					i = 999; // Jump out of loop
				    }
				}


				if ( ! coleta_in_area ) {
					address_in_area = false;
					console.log("Endereço de coleta fora da área de entrega!");
					//return;
				}



        //console.log(coleta_area+1);
        var opcoesEntrega = document.getElementById("opcoes_entrega");
        var options_str = "";
        if (address_in_area){
          opcoesSelect[coleta_area+1].forEach( function(car) {
            options_str += '<option value="' + car + '">' + car + '</option>';
          });
        } else {
          opcoesSelect[0].forEach( function(car) {
            options_str += '<option value="' + car + '">' + car + '</option>';
          });
        }

        opcoesEntrega.innerHTML = options_str;

		})
	}
}

initAutocomplete();
