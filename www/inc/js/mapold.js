long = 0;
lat = 0;

var map = L.map("mapid").setView([0, 0], 12);
var markers = L.markerClusterGroup();

map.zoomControl.setPosition("bottomleft");

<?php

require_once 'config.php';

$files = glob("/Users/mp/Sites/diss/uploads/*.{jpg,jpeg}");

foreach($files as $file) {
	$exif = exif_read_data($file);

	$name = mysqli_query($conn, "SELECT imgName FROM Images WHERE locat='$file'");
	
	$long = $exif["GPSLongitude"];
	$longdir = $exif['GPSLongitudeRef'];
	
	$lat = $exif["GPSLatitude"];
	$latdir = $exif["GPSLatitudeRef"];

	?>

	var decLat = convertDMStoD(<?php echo $long.$longdir; ?>);
	var decLong = convertDMStoD(<?php echo $lat.$latdir; ?>);

	var title = <?php echo $name; ?>
	var marker = L.marker(new L.LatLng(decLat, decLong), {title: title});
	marker.bindPopup(title);
	markers.addLayer(marker);
<?php
}
?>

/*
var markers = L.markerClusterGroup();
		
for (var i = 0; i < addressPoints.length; i++) {
  var a = addressPoints[i];
  var title = a[2];
  var marker = L.marker(new L.LatLng(a[0], a[1]), { title: title });
  marker.bindPopup(title);
  markers.addLayer(marker);
}

map.addLayer(markers);
*/

var CartoDB_Positron = L.tileLayer(
	"https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", {
		attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
		subdomains: "abcd",
		maxZoom: 19,
	}
).addTo(map);

function getLocation() {
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(setCurrPosition, errorHandle, {
			timeout: 60000
		});
	} else {
		x.innerHTML = "Geolocation is not supported by this browser.";
	}
}

function setCurrPosition(position) {
	long = position.coords.longitude;
	lat = position.coords.latitude;

	map.panTo([lat, long]);
	L.marker([lat, long]).addTo(map);
}

function errorHandle() {

}

function convertDMStoD(input) {
	input = input.split(/[^\d\w]+/);
	var degrees = input[0];
	var mins = input[1];
	var secs = input[2];
	var direction = input[3]

	var dec = degrees + mins/60 + secs/120;

	if (direction == "S" || direction == "W") {
        dec = dec * -1;
	}
	
	return dec;
}

getLocation();


