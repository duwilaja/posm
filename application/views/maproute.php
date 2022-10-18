<?php
//$lat=$_GET['lat'];
//$lng=$_GET['lng'];
$z="15";
//$latlng=$lat.",".$lng;
$z="12";$latlng="-7.566139228199951,110.82310438156128";//$latlng="-6.175540717418276,106.82719230651857";} //monas
?>
<!DOCTYPE html>
<html>
<head>
	<title>Please Choose Your Location</title>
	<meta charset="utf-8" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css"/>
	<link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.1.0/dist/geosearch.css"/>
	<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
	<script src="https://unpkg.com/leaflet-geosearch@3.1.0/dist/bundle.min.js"></script>
	
</head>
<body>
	<div id="map" style="position: absolute; top: 35px; left: 0; width: 100%; height: 93%;"></div>

	<script>
		var map = L.map('map').setView([<?php echo $latlng;?>], <?php echo $z;?>);
		
		var route=[];
		var poly=null;
		<?php
		if($route!=''){?>
			route=<?php echo $route.';';?>
			poly=L.polyline(route,{color:"red"});
			poly.addTo(map);
		<?php
		}
		?>
		
		L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
		}).addTo(map);
		
		const provider = new window.GeoSearch.OpenStreetMapProvider();
		const search = new GeoSearch.GeoSearchControl({
		  provider: provider,
		  style: 'bar',
		  updateMap: true,
		  autoClose: true,
		}); // Include the search box with usefull params. Autoclose and updateMap in my case. Provider is a compulsory parameter.

		var routes = L.layerGroup();
		
		map.addControl(search);
		map.addLayer(routes);
		
		for(var i=0;i<route.length;i++){
			L.marker(route[i]).addTo(routes);
		}
		
		function onMapClick(e) {
			/*popup
				.setLatLng(e.latlng)
				.setContent("You clicked the map at " + e.latlng.toString())
				.openOn(map);*/
				
			L.marker(e.latlng).addTo(routes);
			route.push(e.latlng);
			if(poly!=null) map.removeLayer(poly);
			poly=L.polyline(route,{color:"red"});
			poly.addTo(map);
			
			//document.mapfrm.lat.value=e.latlng.lat;
			document.mapfrm.route.value=JSON.stringify(route);
			//map.panTo([e.latlng.lat,e.latlng.lng]);
		}

		map.on('click', onMapClick);

	function okclick(){
		parent.window.opener.document.getElementsByName('route')[0].value=document.mapfrm.route.value;
		parent.window.close();
	}
	function clearmap(){
		route=[];
		document.mapfrm.route.value="";
		map.removeLayer(routes);
		console.log(route);
		routes=L.layerGroup();
		map.addLayer(routes);
		map.removeLayer(poly);
	}
	</script>
	<form name="mapfrm">
	<textarea style="display:none;" name="route"><?php echo $route;?></textarea>
	<input type="button" onclick="clearmap();" value="Clear">
	<input type="button" onclick="okclick();" value="OK">
	<input type="button" onclick="parent.window.close();" value="Close">
	</form>
	
</body>
</html>
