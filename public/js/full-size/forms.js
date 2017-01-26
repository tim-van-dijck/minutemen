var uploadCrop, map, marker;
var pos = {lat: 51.1791705,lng: 4.4191379};
var fileSelected = false;

$(function () {
	// MAPS
	if ($('#coords').length > 0) {
		$('#street, #number, #zip, #city').on('change', function() {
			setCoords();
		});
		if ($('#map').length > 0) { initMap(); }
	}

	// IMGS
	if ($('#banner').length > 0) { $('#banner').on('change', function() { setPreview(this); }); }
	if ($('#preview-img').length > 0) {
		$uploadCrop = $('#preview-img').croppie({
			enableExif: true,
			viewport: {
				width: 250,
				height: 250,
				type: 'square'
			},
			boundary: {
				width: 300,
				height: 300
			}
		});
		$('#full-img').on('change', function() {
            fileSelected = true;
		    readFile(this);
		});
	}
	$('.image-form').submit(function(e) { setImageData(); });

	// POSTS
	$('#post-form').submit(function(e) {
		e.preventDefault();
		ajaxSubmit($(this));
	});
	$('textarea[name="post"]').keydown(function(e) { postOnEnter(this, e); });
	$('#enter-form, #settle-game-form').submit(function(e) {
		e.preventDefault();
		ajaxSubmit($(this));
		$('#enter-event').modal('toggle');
        $('#settle-game').modal('toggle');
	});

	$('.game-settle').click(function(e) {
	    $team1 = $('#settle-game .team_1');
	    $team2 = $('#settle-game .team_2');

        $('#settle-game-form').attr('action', $(this).data('action'));
        $team1.find('.title').text($(this).find('.team_1 .title').text());
        $team1.find('.profile-img>img').attr('src', $(this).find('.team_1 .profile-img>img').attr('src'));

        $team2.find('.title').text($(this).find('.team_2 .title').text());
        $team2.find('.profile-img>img').attr('src', $(this).find('.team_2 .profile-img>img').attr('src'));
    });
});


// IMGS
function readFile(input) {
    console.log(input);
	if (input.files && input.files[0]) {
		if (input.files[0].size/1024 <= 2000) {
			var reader = new FileReader();
			
			reader.onload = function (e) {
				$uploadCrop.croppie('bind', {
					url: e.target.result
				});
			}
			
			reader.readAsDataURL(input.files[0]);
		} else {
			swal("Oops...", "the image upload is restricted to 2MB files.", 'error');
			resetEl($('#full-img'));
		}
	}
	else {
		swal("Oops...", "your browser doesn't support the FileReader API");
	}
}
function setImageData() {
    if (fileSelected) {
        $uploadCrop.croppie('result', {
            type: 'base64',
            size: 'viewport',
            format: 'png',
            quality: .8,
            circle: false
        }).then(function(src) {
            $('#img').val(src);
            $('#emblem').val(src);
        });
    }
}
function resetEl($el) {
	$el.wrap('<form>').closest('form').get(0).reset();
	$el.unwrap();
}
function setPreview(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('.banner>img').attr('src', e.target.result);
		}

		reader.readAsDataURL(input.files[0]);
	} else {
		$('.banner').hide();
	}
}

// MAPS
function setCoords() {
	var address = $('#number').val()+'+'+$('#street').val()+'+'+$('#zip').val()+'+'+$('#city').val();

	$.get('https://maps.google.com/maps/api/geocode/json?sensor=false&key=AIzaSyDuJIisroEDUZcSowh6tqA_LG9Vmn1C4IQ&address='+address, function (data) {
		
		if ($('#map').length > 0) {
			pos = new google.maps.LatLng(data.results[0].geometry.location.lat, data.results[0].geometry.location.lng);
			map.setCenter(pos);
			map.setZoom(15);
			marker.setPosition(pos);
		}

		if($('').length > 0) {
            for (var i=0; i < data.results[0].address_components.length; i++) {
                for (var j=0; j < data.results[0].address_components[i].types.length; j++) {
                    if (data.results[0].address_components[i].types[j] == "country") {
                        country = data.results[0].address_components[i];
                        $('#country').val(country.long_name);
                    }
                }
            }
        }

		$('#coords').val(data.results[0].geometry.location.lat + ';' + data.results[0].geometry.location.lng);
	});
}
function initMap() {
    map = addContactGoogleMaps("map", pos.lat, pos.lng);
}
function addContactGoogleMaps(container, latitude, longitude) {
    var zoom = 9,
        disable = true,
        scroll = false,
        styledMap = new google.maps.StyledMapType(
            [
                {
                    "featureType": "all",
                    "elementType": "labels.text.fill",
                    "stylers": [
                        {
                            "saturation": 36
                        },
                        {
                            "color": "#333333"
                        },
                        {
                            "lightness": 40
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.text.stroke",
                    "stylers": [
                        {
                            "visibility": "on"
                        },
                        {
                            "color": "#ffffff"
                        },
                        {
                            "lightness": 16
                        }
                    ]
                },
                {
                    "featureType": "all",
                    "elementType": "labels.icon",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 20
                        }
                    ]
                },
                {
                    "featureType": "administrative",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "color": "#fefefe"
                        },
                        {
                            "lightness": 17
                        },
                        {
                            "weight": 1.2
                        }
                    ]
                },
                {
                    "featureType": "landscape",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#ccc"
                        },
                        {
                            "lightness": 10
                        }
                    ]
                },
                {
                    "featureType": "poi",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#c6c6c6"
                        },
                        {
                            "lightness": 10
                        },
                        {
                            "visibility": "simplified"
                        }
                    ]
                },
                {
                    "featureType": "poi.park",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#dedede"
                        },
                        {
                            "lightness": 10
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "lightness": 17
                        },
                        {
                            "color": "#cc4020"
                        }
                    ]
                },
                {
                    "featureType": "road.highway",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#232427"
                        },
                        {
                            "lightness": 1
                        },
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "road.arterial",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#434548"
                        },
                        {
                            "lightness": 1
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry.fill",
                    "stylers": [
                        {
                            "visibility": "on"
                        }
                    ]
                },
                {
                    "featureType": "road.local",
                    "elementType": "geometry.stroke",
                    "stylers": [
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "transit",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#f2f2f2"
                        },
                        {
                            "lightness": 19
                        },
                        {
                            "visibility": "off"
                        }
                    ]
                },
                {
                    "featureType": "water",
                    "elementType": "geometry",
                    "stylers": [
                        {
                            "color": "#e9e9e9"
                        },
                        {
                            "lightness": 17
                        }
                    ]
                }
            ],
            {name: "Styled Map"}
        ),
        mapCenter = new google.maps.LatLng(latitude, longitude),

        mapOptions = {
            zoom: zoom,
            panControl: true,
            zoomControl: disable,
            scaleControl: true,
            mapTypeControl: false,
            streetViewControl: false,
            overviewMapControl: false,
            minZoom : 2,
            scrollwheel: scroll,
            center: mapCenter,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        },
        map = new google.maps.Map(document.getElementById(container), mapOptions);

    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');

    marker = new google.maps.Marker({
        position: mapCenter,
        map: map,
    });

    function customCenter(latLng) {
        map.setCenter(latLng);
    }

    customCenter(mapCenter);

    google.maps.event.addDomListener(window, 'resize', function() {
        customCenter(mapCenter);
    });

    return map;
}

// POSTS
function ajaxSubmit($form) {
	$.post($form.attr('action'), $form.serialize(), function () {
		$form.trigger('reset');
		$('#team').val('').trigger('change');
		getFeed();
	});
}
function postOnEnter(el, e) {
	if (e.keyCode == 13 && $(el).value != "" && !e.shiftKey) {
		$(el.form).submit();
	}
}