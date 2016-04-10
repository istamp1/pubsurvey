<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

    <style type="text/css">
      html { height: 100% }
      body { height: 100%; margin: 0; padding: 0 }
      #map-canvas { height: 100% }
    </style>

    <link rel="stylesheet" type="text/css" href="../style/main.css">

    <script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDErT4ETb9XNTiUy1rtwb3-82K5JyQcZLc">
    </script>

    <script type="text/javascript">
		function initialize() {
			// centre and zoom map
			var mapOptions = {
				center: new google.maps.LatLng(52.6283, 1.2967),
				zoom: 13
			};
			// create map
			var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
			// data
			var pubData = <?php echo json_encode($pubs); ?>;
			// set up vars
			var markers = [];
			var infowindow = new google.maps.InfoWindow({
				content: "temp"
			});
			// create markers
			for(i=0; i < pubData.length; i++) {
				var myLatlng = new google.maps.LatLng(pubData[i]['Lat'], pubData[i]['Longt']);
//				var contentString = '<div>' + pubData[i]['PubName'] + '</div>';
				var contentString = pubData[i]['html'];
				var marker = new google.maps.Marker({
					position: myLatlng,
					map: map,
					html: contentString
				});
				markers[i] = marker;
			};
			// add an InfoWindow to each marker
			for (var i = 0; i < markers.length; i++) {
			var marker = markers[i];
			google.maps.event.addListener(marker, 'mouseover', function () {
				infowindow.setContent(this.html);
				infowindow.open(map, this);
			});
			}
		}
		google.maps.event.addDomListener(window, 'load', initialize);
    </script>

    <title>Pub Map</title>

  </head>

  <body>
	<?php include '../pubsurvey/application/controllers/session.php'; ?>

	<div class="header" id="heading" style="width:100%">
		<div id="logo">
            <a href=""><img src="http://www.norwichcamra.org.uk/pix/1brlogo.jpg"></a>
        </div>
        <div id="title" style="width:800px">
            <div>
                Norwich & Norfolk CAMRA
            </div>
            <div id="title-left" style="width:420px;height:105px;margin-top:30px">
                <div><span id="year">Pub Survey <?php echo $year?></span></div>
            </div>
            <div id="title-right"><?php echo $stats;?></div>
        </div>
        <div id="login" style="float:right">
            <div id="loginmenu">
                <ul>
                    <li class="loginmenuitem">
                        <a class="undec-link" href="http://www.norwichcamra.org.uk/pubdb/GetPubsByLocation.php" target="_blank">Norfolk Pubs |</a>
                    </li>
                    <li class="loginmenuitem">
                        <a class="undec-link" href="http://whatpub.com" target="_blank">What Pub |</a>
                    </li>
                    <li class="loginmenuitem">
                        <a class="undec-link" href="http://www.norwichcamra.org.uk" target="_blank">Branch |</a>
                    </li>
                    <li class="loginmenuitem">
                        <a class="undec-link" href="http://www.camra.org.uk" target="_blank">CAMRA |</a>
                    </li>
                </ul>
            </div>
            <a href="http://www.camra.org.uk" target="_blank"><img style="padding: 10px 0 0 0; float: right;" src="http://www.norwichcamra.org.uk/pubdb/gifs/CAMRAlogoS.gif"></a>
        </div>
    </div>
    <div class="clear"></div>
	</div>

    <div id="map-canvas"/>

  </body>

</html>
