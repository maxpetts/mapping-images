var available = true;

(function () {
	'use strict';
	window.addEventListener('load', function () {
		// Get the forms we want to add validation styles to
		var forms = document.getElementsByClassName('needs-validation');
		// Loop over them and prevent submission
		var validation = Array.prototype.filter.call(forms, function (form) {
			form.addEventListener('submit', function (event) {
				if (form.checkValidity() === false || available === false) {
					event.preventDefault();
					event.stopPropagation();
				}
				form.classList.add('was-validated');
			}, false);
		});
	}, false);
})();

$(document).ready(function () {

	// Check username availability
	$("#signUser").keyup(function () {
		available = true;
		var uname = $("#signUser").val();
		$("#userMsg").empty();

		$.post("../lib/checkuser.php", {
			suggestion: uname
		}, function (data) {
			$("#userMsg").html(data);
			$("#signup-submit").attr("disabled");
			available = false;
		});
	});

	// Sign user up
	$("#signup-form").submit(function (event) {
		event.preventDefault();

		var fName = $("#userFName").val();
		var lName = $("#userLName").val();
		var email = $("#userEmail").val();
		var user = $("#signUser").val();
		var pass = $("#pass").val();
		var pass2 = $("#pass2").val();

		$.post("/~mp/diss/lib/signup.php", {
			f_name: fName,
			l_name: lName,
			email: email,
			usname: user,
			pass: pass,
			repeat_pass: pass2
		}, function (data) {
			console.log("signup" + data);
			if (data == "success") {
				$("#sign-bod").siblings().hide();
				$("#sign-bod").hide().html("<center><h2>Signup successful</h2></center>").show("slow");
				setTimeout(function () {
					$("#sign-bod").html("<center><h2>Logging you in..</h2></center>");
				}, 5000);
				setTimeout(location.reload.bind(location), 4000);
			} else if (data == "email") {
				var temp = $("#sign-bod").html();

				$("#sign-bod").siblings().hide();
				$("#sign-bod").hide().html("<center><h2>A user with that email already exists.</h2></center>").show("slow");
				setTimeout(function () {
					$("#sign-bod").fadeIn().html(temp).siblings().show();
				}, 5000);
			}
		});
	});

	// Log user in
	$("#login-form").submit(function (event) {
		event.preventDefault();
		var user = $("#user").val();
		var passw = $("#pass").val();

		$.post("diss/lib/login.php", {
			usname: user,
			pword: passw
		}, function (data) {

			if (data == "success") {
				//$("#login-bod").html("<h1>Logging you in</h1>").delay(5000);
				$("#login-bod").hide().html("<center>Logging you in..\n\nif nothing happens please refresh. </center>").show("slow").siblings().hide();
				setTimeout(function() {
					$("#sign-bod").fadeIn().html(temp).siblings().show();
				} , 4000);
				location.reload.bind(location)

				//window.location.reload();
			} else if (data == "invalid") {
				var content = $("#login-content").html();

				$("#login-bod").hide().html("<div class='modal-body'><h2><center>Incorrect details</h2></center></div>").show("slow").siblings().hide();
				setTimeout(function () {
					$("#login-content").html(content);
				}, 5000);

			}
		});
	});

	// Preview photo and check exif[long] & [lat]
	var fd = new FormData();

	$("#uploadImg").change(function (e) {

		readURL(this);
		$("#preview-img").show("slow");
		console.log('photo shown');

		var file = $('#uploadImg')[0].files[0];
		fd.append('uploadImg', file);

		$.ajax({
			url: '../../lib/checklonglat.php',
			type: 'post',
			data: fd,
			contentType: false,
			processData: false,
			success: function (data) {
				console.log("data"+ data);
				if (data == "neither") {
					$("#img-attr").append("<label class='text-secondary' for='long'>Enter Longitude</label><input type='text' class='form-control' id='long' name='long' placeholder='Longitude'>");
					$("#img-attr").append("<label class='text-secondary' for='lat'>Enter Latitude</label><input type='text' class='form-control' id='lat' name='lat' placeholder='Longitude'>");
				} else if (data == "nolat") {
					$("#img-attr").append("<label class='text-secondary' for='lat'>Enter Latitude</label><input type='text' class='form-control' id='lat' name='lat' placeholder='Longitude'>");
				} else if (data == "nolong") {
					$("#img-attr").append("<label class='text-secondary' for='long'>Enter Longitude</label><input type='text' class='form-control' id='long' name='long' placeholder='Longitude'>");
				}
			}
		});
	});

	// Upload photo
	$("#upload-form").submit(function (event) {
		console.log('submitted');
		event.preventDefault();
		var imgName = $("#img_name").val();
		var long = $("#long").val();
		var lat = $("#lat").val();

		fd.append('img_name', imgName);
		fd.append('long', long);
		fd.append('lat', lat);

		console.log(imgName);

		$.ajax({
			url: '../lib/upload.php',
			type: 'post',
			data: fd,
			contentType: false,
			processData: false,
			success: function (data) {
				console.log(data);
				$("#upload-bod").siblings().hide();
				if (data == "uploaded") {
					$("#upload-bod").html("<center>upload successful</center>");
					setTimeout(location.reload.bind(location), 2500);
				} else if (data == "file error") {
					$("#upload-bod").html("<center>file transfer error<br>The '_www' user must have permissions in the folder</center>");
				} else if (data == "db error") {
					$("#upload-bod").html("<center>database error</center>");
				} else {
					$("#upload-bod").html("<center>unknown error</center>");
				}
			}
		});
	});

});


var uploadBodhtml = $("#upload-bod").html();

function clearImgInp() {
	$("#upload-bod").html(uploadBodhtml);
	/*$
	("#img-input").val("");
	$("#upload-file").html("Choose file");
	$("#preview-img").hide();
	*/
}

function readURL(input) {
	if (input.files && input.files[0]) {
		var reader = new FileReader();

		reader.onload = function (e) {
			$('#preview-img').attr('src', e.target.result);
		};

		reader.readAsDataURL(input.files[0]); // convert to base64 string
	}
}

function vote(imgID) {
	console.log('before post');
	$.post("../lib/upvote.php", {
		img_id: imgID
	}, function (data) {
		console.log(imgID);
		var target = $("#" + imgID);
		var no = parseInt(target.text(), 10);
		//console.log($(this).siblings());

		if (data == 'complete') {
			no++;
			console.log(no);
			target.html(no);
			//target.attr('value', no);
		} else if (data == 'dupe') {
			target.after("<p>you've already liked this image</p>");
			console.log(data);
		} else if (data == 'error') {
			target("soz idk what happened");
			console.log(data);
		}
	});
}