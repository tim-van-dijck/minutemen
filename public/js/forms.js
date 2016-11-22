var uploadCrop;

$(function () {
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

	$('#full-img').on('change', function () { readFile(this); });
	$('#register-form').submit(function(e) { setImageData(); });
});

function readFile(input, $target) {
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