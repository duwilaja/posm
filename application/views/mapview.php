<?php
//$lat=$_GET['lat'];
//$lng=$_GET['lng'];
$z="15";
$latlng=$lat.",".$lng;
if($lat==""||$lng==""){$z="12";$latlng="-7.566139228199951,110.82310438156128";}//$latlng="-6.175540717418276,106.82719230651857";} //monas
?>
<!DOCTYPE html>
<html>
<head>
	<title>Map View</title>
	<meta charset="utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
	<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.1.0/dist/geosearch.css"/>
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
	<script src="https://unpkg.com/leaflet-geosearch@3.1.0/dist/bundle.min.js"></script>
	
</head>
<body>
	<div id="map" style="position: absolute; top: 0px; left: 0; width: 100%; height: 100%;"></div>

	<script>

		var map = L.map('map').setView([<?php echo $latlng;?>], <?php echo $z;?>);

		L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		
		/*new L.Control.GeoSearch({
			provider: new L.GeoSearch.Provider.OpenStreetMap()
		}).addTo(map);*/

		var popup = L.popup();
		var marker = L.marker();
		
			var latlon=L.latLng(<?php echo $latlng;?>);
			//popup.setLatLng(latlon).setContent("Here.").openOn(map);
			marker.setLatLng(latlon).addTo(map);
/*
		const provider = new window.GeoSearch.OpenStreetMapProvider();
		const search = new GeoSearch.GeoSearchControl({
		  provider: provider,
		  style: 'bar',
		  updateMap: true,
		  autoClose: true,
		}); // Include the search box with usefull params. Autoclose and updateMap in my case. Provider is a compulsory parameter.

		map.addControl(search);
	*/	
		
		function onMapClick(e) {
			/*popup
				.setLatLng(e.latlng)
				.setContent("You clicked the map at " + e.latlng.toString())
				.openOn(map);*/
				
			marker.setLatLng(e.latlng).addTo(map);
			
			document.mapfrm.lat.value=e.latlng.lat;
			document.mapfrm.lng.value=e.latlng.lng;
			map.panTo([e.latlng.lat,e.latlng.lng]);
		}

		//map.on('click', onMapClick);

	function okclick(){
		latfld=latfld==""?"lat":latfld;
		lngfld=lngfld==""?"lng":lngfld;
		parent.window.opener.document.getElementsByName(latfld)[0].value=document.mapfrm.lat.value;
		parent.window.opener.document.getElementsByName(lngfld)[0].value=document.mapfrm.lng.value;
		parent.window.close();
	}
	</script>
	<!--form name="mapfrm">
	<input type="text" name="lat" value="<?php echo $lat;?>">
	<input type="text" name="lng" value="<?php echo $lng;?>">
	<input type="button" onclick="okclick();" value="OK">
	<input type="button" onclick="parent.window.close();" value="Close">
	</form-->
	
</body>
</html>
