<?php
include('session.php');
include('phpsqlajax_dbinfo.php');

$default=1;
if(isset($_GET['num']))
$num=$_GET['num'];
else
$num=1;
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// FIJA EL MAPA DE INICIO EN LAS COORDENADAS 
if(isset($_GET['cLat']) and isset($_GET['cLong']))
{	
$cLat=$_GET['cLat'];
$cLong=$_GET['cLong'];

$zvalue=15;
$default=0;
}
else
{
$cLat=10.05587; /*-15.785894; */     
$cLong=-69.263263; /*35.006425; */
$zvalue=9;
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
 ?>

<!DOCTYPE html>
<html>
  <head>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">    
<title>Seguimiento Gps Pc</title>
    <!--  <link href="/maps/documentation/javascript/examples/default.css" rel="stylesheet">-->
    <style type="text/css">
	#map-canvas {position:fixed !important; position:absolute; top:0; left:220px; right:0; bottom:0; }
	.ITitle { font:Georgia, "Times New Roman", Times, serif; font-size:15px; }
	
.vname {
	background:url(images/bg/u_online.gif) left no-repeat; 
	width:100%; 
	margin-bottom:3px; 
	font-size:11px; 
	font-family:Arial, Helvetica, sans-serif; 
	float:left; 
}
.vname-offline {
	background:url(images/bg/u_offline.gif) left no-repeat; 
	width:100%; 
	margin-bottom:3px; 
	font-size:11px; 
	font-family:Arial, Helvetica, sans-serif; 
	float:left; 
}

.vname a { text-decoration:none; color:#22cc22; margin:1px 1px 2px 18px; }
.vname-offline a { text-decoration:none; color:#cccccc; margin:1px 1px 2px 18px; }

.vname a:hover { text-decoration:underline; color:#22cc22; margin:1px 1px 2px 18px; }
   .labels {
		color: red;
		background-color: white;
		font-family: "Lucida Grande", "Arial", sans-serif;
		font-size: 10px;
		padding:1px;
		text-align: center;
		height: 13px;     
		border: 1px solid black;
		-moz-border-radius: 3px;
		-webkit-border-radius: 3px;
		border-radius: 3px;
		white-space: nowrap;
   }

	</style>
    
                                                               
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
    <script type="text/javascript" src="js/markerwithlabel.js"></script>
    <script>
    
// CONTADOR 



var num=<?php echo $num;?>;	
// In the following example, markers appear when the user clicks on the map.
// The markers are stored in an array.
// The user can then click an option to hide, show or delete the markers.
var map;
var markers = [];

function initialize() {
  var haightAshbury = new google.maps.LatLng(<?php echo $cLat;?>, <?php echo $cLong; ?>);
  var mapOptions = {
    zoom: <?php echo $zvalue; ?>,
    center: haightAshbury,
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);

	  
  
timeout();	  
}

// FUNCION QUE HACE EL RECORRIDO Y COMIENZA LA DESCARGAR DE LA URL XML DE BD PARA MOSTRAR TODOS LOS DISPOSITIVOS AGREGADOS Y FIJA LOS MARCADORES EN EL MAPA

function timeout() { 

deleteMarkers();
 downloadUrl("phpsqlajax_genxml3.php?UID=<?php echo $GPSUSERID;?>", function(data) {
        var xml = data.responseXML;
        var markers = xml.documentElement.getElementsByTagName("marker");
       

		 for (var i = 0; i < markers.length; i++) {
			  	
			  var name = markers[i].getAttribute("name");
			  var course = markers[i].getAttribute("course");
			  var dt_image = markers[i].getAttribute("dt_image");
			  var dt_name = markers[i].getAttribute("dt_name");
			  var dt_id = markers[i].getAttribute("dt_id");
			  var dt_ver = markers[i].getAttribute("dt_ver");			  
			  var time = markers[i].getAttribute("time");
			  var speed = markers[i].getAttribute("speed");
              var label = markers[i].getAttribute("device_id");
			  var tdiff = markers[i].getAttribute("tdiff");
			  var Lat=parseFloat(markers[i].getAttribute("lat"));
			  var Lng=parseFloat(markers[i].getAttribute("lng")); 
			  var point = new google.maps.LatLng(Lat,Lng);
              //alert(dt_id);
              
	
  if(tdiff>=6)
  { 
	    
  var Acc="Apagado";
  speed=0; 
  }
  else var Acc="Encendido"; 
			
  // ENVIA LOS DATOS DE PARADA ANTES DE  6 MIN => Acc off
  
  if(speed>1) var status = Math.round(speed*1.852) + "Movimientos"; else var status="Detenido"; // Movimientos de Acuerdo a la Velocidad

  //CUADRO DE DIALOGO HTML DONDE SE ENCUENTRA LA INFORMACION DEL DISPOSITIVO
   
  var html ='<div style="margin:1px !important; font-size:12px;">' + "<b>" + name + "</b>  <br/> Angulo : " + course + "<br/> Última actualización : " + time + "<br/> Acc : " + Acc + "<br/> Velocidad : " + Math.round(speed*1.852) + " kmph<br/>Lat : " + Lat + "<br/>Lng : " + Lng + "<br/>" + "<a href=tracking.php?DID=" + label + ">Rastreo</a>" + " " + "<a href=playback.php?PlayBackDeviceId=" + label + "&Reset=1>Reproducir Ruta</a>" + " " + "<a href=tracking_orden_trabajo.php?DID=" + label + ">Orden de Trabajo</a>" + '</div>' ;

  	 
 document.getElementById("status"+label).innerHTML=status; // MUESTAR EL ESTADO DEL DISPOSITIVO
 
   if(num==(label))
	{ 

		map.panTo(new google.maps.LatLng(markers[i].getAttribute("lat"),markers[i].getAttribute("lng")));
        GetAddressByGoogle('divMarkerAddress', Lat, Lng);
        
	}
  		
// AÑADE UN MARCADOR CUANDO SE HACE CLIC EN EL MAPA

         addMarker(point,course,dt_image,html,name,i);

      
		 }  // BUCLE TERMINA AQUI
		
			 
   });   // DESCARGA DE LA URL TERMINA AQUI

}

// Añadir un marcador para el mapa y empujar a la matriz.

function addMarker(location,course,image,html,name,i) {

//COLOCA UNA IMAGEN DISTINTA SEGUN EL COURSE DEL DISPOSITIVO
	
	if(course<=22.5)
	{
		image = "images/"+image+"0.png"; // 0 angle img
	}
	else if (course<=67.5)
	{
		image = "images/"+image+"45.png"; // 45 angle img	
	}
	else if (course<=112.5)
	{
		image = "images/"+image+"90.png"; // 90 angle img	
	}
	else if (course<=157.5)
	{
		image = "images/"+image+"135.png"; // 135 angle img	
	}
	else if (course<=202.5)
	{
		image = "images/"+image+"180.png"; // 180 angle img	
	}		
	else if (course<=247.5)
	{
		image = "images/"+image+"225.png"; // 225 angle img	
	}
	else if (course<=292.5)
	{
		image = "images/"+image+"270.png"; // 270 angle img
	}		
	else
	{
		image = "images/"+image+"315.png"; // 315 angle img	
	}
	
	
	  var marker = new MarkerWithLabel({
		    position: location,
		    icon:image,
			labelContent: name,
			labelAnchor: new google.maps.Point(22, 0),
			labelClass: "labels", // the CSS class for the label
			labelStyle: {opacity: 0.75},
		    map: map
   
  });


var infoWindow = new google.maps.InfoWindow({maxWidth:300});
google.maps.event.addListener(marker, 'click', function() {
        infoWindow.setContent(html);
        infoWindow.open(map, marker);
      });


  markers.push(marker); 
 
}


// Sets the map on all markers in the array.
function setAllMap(map) {
  for (var i = 0; i < markers.length; i++) { 
    markers[i].setMap(map);
  }
}

//Removes the markers from the map, but keeps them in the array.
function clearMarkers() {
  setAllMap(null);
}

// Shows any markers currently in the array.
function showMarkers() {
  setAllMap(map);
}

// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
   setAllMap(null);
  markers = [];
}

google.maps.event.addDomListener(window, 'load', initialize);



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    function downloadUrl(url, callback) {
      var request = window.ActiveXObject ?
          new ActiveXObject('Microsoft.XMLHTTP') :
          new XMLHttpRequest;

      request.onreadystatechange = function() {
        if (request.readyState == 4) {
          request.onreadystatechange = doNothing;
          callback(request, request.status);
        }
      };


      request.open('GET', url, true);
      request.send(null);
	  
    }
    function doNothing() {}



///////////////////////////////////////////////////////////////

var geocoder = new google.maps.Geocoder();
//get geo location by name
function GetAddressByGoogle(t, lat, lng) {
    if (!geocoder) {
        geocoder = new google.maps.Geocoder();
    }
    if (lat != 0) {
        var latlng = new google.maps.LatLng(lat, lng);
        geocoder.geocode({ 'latLng': latlng }, function (results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    document.getElementById(t).innerHTML=results[1].formatted_address;
                } else {

                }
            } else {

            }
        });
    }
}


</script>
    

  
  </head>
  <body>
<form name="frmset" method="post" action="mapframe.php" >
<div id="disSecond" style="position:absolute; width:360px; height:25px; 
box-shadow:#888 1px 1px 0px; -moz-box-shadow:#888 1px 1px 0px; background-color:White; 
margin-left:1px; font-size:15px; margin-top:-9px; line-height:100%; z-index:999;">
Actualización en: <span id="input" style="color:Red;">20</span> segundos!  
<span id="divMarkerAddress"></span>

<input type="button" onclick="start()" value="Play"/>
<input type="button" onclick="stop()" value="Pause"/>
</div>

 <div style="width:210px; height:530px; overflow:scroll; margin:14px 2px 2px 0px; float:left;">

<script>

(function(){

	    function stop(){
	    clearInterval(timerId);
	    }
	    
	    function start(){	    
	    timerId= setInterval(function(){ timed(); },1000);
	    
	    }
	    
	    function timed() {
	        var i = document.getElementById('input');
	        i.innerHTML = parseInt(i.innerHTML)-1;
	        if (parseInt(i.innerHTML)==0) {
	        	 location.reload(true);
	     
	        }
	    }
	    var timerId = setInterval(function(){ timed(); },1000);
	  
	    
	window.stop = stop;
	window.start = start;
	})()


</script>
  
   
<?php
		$sql=mysql_query("SELECT * FROM users_devices JOIN devices ON devices.id=users_devices.devices_id JOIN positions ON 
		positions.id=devices.latestPosition_id WHERE users_id='$GPSUSERID' ORDER BY GroupId,devices.id ASC");
			$i=1;
			
			while($result=mysql_fetch_array($sql))
			{
				$LUtime=strtotime($result['time']);
				$now=strtotime(date("Y-m-d H:i:s"));
				$tdiff=abs($now - $LUtime) / 60;
				$daydiff=($tdiff/60)/24;
				
				$newgroupval=$result['GroupId'];
				
				$groupquery=mysql_query("SELECT * FROM device_groups WHERE gid='$newgroupval'");
				$groupresult=mysql_fetch_array($groupquery);
				$GROUPNAME=$groupresult['gname'];
							
				if($newgroupval<>$inigroupval)
				{
					echo '<div class="vname" style="background:#000 url(images/bg/top_bg.gif) repeat-x; color:#fff;">'.$GROUPNAME.'</div>';
					
					$inigroupval=$newgroupval;
				}
				
				//daydiff DETERMINA LOS DIAS QUE EL DISPOSITIVO HA ESTADO SIN TRANSMITIR Y CAMBIA SU ESTADO A OFFLINE
				
				if($daydiff>2) // VERIFICA QUE EL DISPOSITIVO ESTE SIN TRANSMITIR POR MAS DE 2 DIAS 
				{
				echo '<div class="vname-offline">
					<a href="tracking.php?&DID='.$result['devices_id'].'">'.$result['name'].'</a>
					<div id="status'.$result['devices_id'].'" style="font-size:11px; float:right; width:80px; color:#cccccc;"></div></div>';
				
				}
				else 
				
				{
					echo '<div class="vname" style="background:url(images/bg/u_online.gif) left no-repeat;">
					<a href="mapframe.php?cLat='.$result['latitude'].'&cLong='.$result['longitude'].'&num='.$result['devices_id'].'">'.$result['name'].'</a>
					<div id="status'.$result['devices_id'].'" style="float:right; width:80px; color:#22cc22;"></div></div>';
				}				
			}
        ?>

  </div>
  
  
  
    <div id="map-canvas"></div>
  </body>
</html>






