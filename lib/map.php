<?php
include_once 'config.php';

/**
 * 
 * @map.php
 * 
 * Contains all the html and js needed to generate a map that contains all the images uploaded and show their name, number of upvotes and image on a marker click.
 * If users are also logged in they can upvote an image.
 * 
 */
?>

<div id="mapid"></div>
<script>
    long = 0;
    lat = 0;

    var map = L.map("mapid").setView([0, 0], 3);
    var markers = L.markerClusterGroup();

    map.on('popupopen', function() {
        var cont = document.getElementsByClassName('leaflet-popup-content')[0];
        var lst = cont.getElementsByTagName('script');
        for (var i = 0; i < lst.length; i++) {
            eval(lst[i].innerText)
        }
    });

    map.zoomControl.setPosition("bottomleft");

    <?php

    $files = glob("../uploads/*.{jpg,jpeg}", GLOB_BRACE);

    foreach ($files as $file) {
        $file = trim($file, "\.\.");

        $abs_file = '/Users/mp/Sites/diss' . $file;
        $exif = exif_read_data($abs_file);

        $sql = "SELECT * FROM Images WHERE absPath = '$abs_file'";
        $queryobj = mysqli_query($conn, $sql) or die($conn->error);

        $result = mysqli_fetch_assoc($queryobj);

        if ($result) {
            $id = $result['id'];
            $name = $result['imgName'];
        } else {
            $name = 'server error';
        }

        $long = $exif["GPSLongitude"];
        $lat = $exif["GPSLatitude"];

        if (empty($long) || empty($lat)) {
            $long = $result['longit'];
            $lat = $result['latit'];
    ?>
            var decLat = <?php echo json_encode($lat); ?>;
            var decLong = <?php echo json_encode($long); ?>;
        <?php
        } else {
            array_push($long, $exif['GPSLongitudeRef']);
            array_push($lat, $exif["GPSLatitudeRef"]);
        ?>
            var decLat = convertDMStoD(<?php echo json_encode($lat); ?>);
            var decLong = convertDMStoD(<?php echo json_encode($long); ?>);
        <?php
        }

        $votes_query = "SELECT count(picID) as pics FROM Votes WHERE picID = '$id'";
        $vote = mysqli_query($conn, $votes_query) or die($conn->error);

        $votes = mysqli_fetch_assoc($vote);
        $imgVotes = $votes['pics'];

        if ($loggedin) {
        ?>
            var popup_content = '<?php echo $name; ?> <img src="<?php echo '..' . $file; ?>"> <span><a href="#" class="vote far fa-thumbs-up"></a><p id="<?php echo $id;?>" class="votes"><?php echo $imgVotes ?></p></span>' + '<script> $(".vote").on("click", function(){vote(<?php echo $id ?>)}); </scr' + 'ipt>'

        <?php
        } else {
        ?>
            var popup_content = '<?php echo $name; ?> <img src="<?php echo '..' . $file; ?>"> <span><i class="votes"><?php echo $imgVotes ?></i></span>';
        <?php } ?>

        var marker = L.marker(new L.LatLng(decLat, decLong));

        marker.bindPopup(popup_content, {
            minWidth: 250
        });
        markers.addLayer(marker);
    <?php
    }
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

    echo <<< _MAP
        var CartoDB_Positron = L.tileLayer(
            "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                subdomains: "abcd",
                maxZoom: 19,
                noWrap: true
            }
        ).addTo(map);

        map.addLayer(markers);

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
            var m = L.marker([lat, long]);
            m.bindPopup("<i style='font-size: 0.75rem'>Your location</i>");
            m.addTo(map);
        }

        function errorHandle() {

        }

        function convertDMStoD(input) {

            var degrees = parseInt(input[0]);
            var mins = parseInt(input[1]);
            var secs = parseInt(input[2])/100000000;
            var direction = input[3]

            var dec = degrees + mins/60 + secs/(60*60);

            if (direction == "S" || direction == "W") {
                dec = dec * -1;
            }

            return dec;
        }
        

        getLocation();
    </script>

_MAP;