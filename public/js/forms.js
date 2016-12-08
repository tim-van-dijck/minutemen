var uploadCrop, map, marker;
var pos = {lat: 51.1791705,lng: 4.4191379};

$(function () {
	// MAPS
	if ($('#map').length > 0) { initMap(); }
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
		$('#full-img').on('change', function() { readFile(this); });
	}
	$('.image-form').submit(function(e) { setImageData(); });

	// POSTS
	$('#post-form').submit(function(e) {
		e.preventDefault();
		ajaxSubmit($(this));
	});
	$('textarea[name="post"]').keydown(function(e) { postOnEnter(this, e); });
	$('#enter-form').submit(function(e) {
		e.preventDefault();
		ajaxSubmit($(this));
		$('#enter-event').modal('toggle');
	});
});


// IMGS
function readFile(input) {
	console.log(input.files[0].size/1024);
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
function resetEl($el) {
	$el.wrap('<form>').closest('form').get(0).reset();
	$el.unwrap();
}
function setPreview(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('.banner>img').attr('src', e.target.result);
			$('.banner').show();
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
		
		if ($('#coords').length > 0) {
			pos = new google.maps.LatLng(data.results[0].geometry.location.lat, data.results[0].geometry.location.lng);
			map.setCenter(pos);
			map.setZoom(15);
			marker.setPosition(pos);
		}

		$('#coords').val(data.results[0].geometry.location.lat + ';' + data.results[0].geometry.location.lng);
	});
}
function initMap() {
	map = new google.maps.Map(document.getElementById("map"), {
		zoom: 9,
		mapTypeControl: false,
		disableDefaultUI: true,
		center: {lat: pos.lat, lng: pos.lng} // current marker position
	});
	marker = new google.maps.Marker({
		position: {lat: pos.lat, lng: pos.lng} // current marker position
	});
	marker.setMap(map);
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