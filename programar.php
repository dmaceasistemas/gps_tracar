<?php
include('session.php');
include('phpsqlajax_dbinfo.php');
include("includes/savelog.php");

if($GPSPRIVILEGE=='End-User') header('location:restricted.php');

session_start();

$sql=mysql_query("SELECT * FROM users WHERE id='$GPSUSERID'");
$result=mysql_fetch_array($sql);
$GPSUSERNAME=strtoupper($result['login']);

$Programar=$_GET['Programar'];
$Encendido=$_GET['Encendido'];
$Emergencia=$_GET['Emergencia'];
$Delete=$_GET['Delete'];
$UID=$_GET['UID'];
$DID=$_GET['DID'];

$touser=$_GET['touser'];
$vehicle=$_GET['vehicle'];
$linea = substr($vehicle, 0, 4);

 if (($linea == '0414') || ($linea == '0424')) {
	$apn="apn123456 internet.movistar.ve";	
}else{
 if (($linea == '0416') || ($linea == '0426')) {
	$apn="apn123456 int.movilnet.com.ve";	
}
}

$doc = new DOMDocument;

if(isset($Programar))


{
     
	?><script type="text/javascript"> if(confirm('Esta seguro que desea Apagar el Vehiculo?')){


		for (var i = 0; i < 8; i++) {
			   <?php sleep(5);?> 
	           if (i==1)
	        <?php $doc->load("http://172.16.5.1:9090/sendsms?phone=$vehicle&text=reset123456"); ?>               
		           }else{
		       <?php sleep(5);?>    	    	  
			   if (i==2)         
		    <?php $doc->load("http://172.16.5.1:9090/sendsms?phone=$vehicle&text=begin123456"); ?>
		           }else{
		       <?php sleep(5);?>
		 	   if (i==3)         
		    <?php $doc->load("http://172.16.5.1:9090/sendsms?phone=$vehicle&text=time zone123456 -4:30"); ?>
		           }else{
		       <?php sleep(5);?>
			   if (i==4)         
		    <?php $doc->load("http://172.16.5.1:9090/sendsms?phone=$vehicle&text=$apn"); ?>		
		           }else{
		       <?php sleep(5);?>
			   if (i==5)         
			<?php $doc->load("http://172.16.5.1:9090/sendsms?phone=$vehicle&text=adminip123456 200.11.218.237 5001"); ?>
		           }else{
		       <?php sleep(5);?>
			   if (i==6)         
			<?php $doc->load("http://172.16.5.1:9090/sendsms?phone=$vehicle&text=Gprs123456"); ?>
		           }else{
		       <?php sleep(5);?>
			   if (i==7)         
			<?php $doc->load("http://172.16.5.1:9090/sendsms?phone=$vehicle&text=fix300s***n123456"); ?>
	       }
	     }
       
     </script>
     
     <?php

}


if(isset($Delete))
{
 mysql_query("DELETE FROM users_devices WHERE users_id='$UID' AND devices_id='$DID'");
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/smoothness/jquery-ui-1.8.2.custom.css" />
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script> 
<script type="text/javascript" src="js/jquery-ui-1.8.2.custom.min.js"></script>


<title>Apagado y Encendido</title>
<style type="text/css">
#draggable { display:none; }
</style>
<script type="text/javascript">
function dialog(uid,did) {
$("#draggable").dialog({modal:true});
document.getElementById("UID").value=uid;	
document.getElementById("DID").value=did;
}

	
</script>


<style type="text/css">
	#map-canvas {position:fixed !important; position:absolute; top:0; left:1px; right:0; bottom:0; }
	.ITitle { font:Georgia, "Times New Roman", Times, serif; font-size:15px; }
	
.vname { 
	background:url(images/bg/u_online.gif) left no-repeat; 
	width:100%; 
	margin-bottom:3px; 
	font-size:12px; 
	font-family:Arial, Helvetica, sans-serif; 
	float:left; 
}
.vname a { text-decoration:none; color:#22cc22; margin:1px 1px 2px 18px; }
.vname a:hover { text-decoration:underline; color:#22cc22; margin:1px 1px 2px 18px; }
</style>
</head>

<body style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">


</div>
<div id="map-canvas">
<div style="width:100%; padding:5px; background:#ffffff;">
<table width="100%" border="0">
  <tr>
    <td bgcolor="#000000"><div style="color:#FFF; font-weight:bold; background:url(images/top_bg.gif) repeat-x;">::  PROGRAMAR VEHICULOS</div></td>
  </tr>
  <tr>
    <td><div style="font-size:12px; color:#F00; font-weight:bold;"><?php echo $ermsg;?></div></td>
  </tr>
  <tr>
    <td>
    <form name="frmset" method="get" action="programar.php">
    <table width="100%" border="0">

      <tr>
        <td align="left">Seleccione Vehículo</td>
        <td align="left"><select name="vehicle">
        <option value="0">- Seleccione Vehículo -</option>
          <?php 
		if($GPSPRIVILEGE=='admin')
		{
			$sql=mysql_query("SELECT * FROM devices");
			while($row=mysql_fetch_array($sql))
			{
				echo '<option value="'.$row['telefono'].'">'.$row['name'].'</option>';	
			}
		}
		else
		{
			$sql=mysql_query("SELECT * FROM users_devices JOIN devices ON devices.id=users_devices.devices_id WHERE users_id='$GPSUSERID' ");
			while($row=mysql_fetch_array($sql))
			{
				echo '<option value="'.$row['telefono'].'">'.$row['name'].'</option>';	
			}
		}			
		?>
        </select>         
          <font color="#FF0000">*</font></td>
        
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
        <td align="left">&nbsp;</td>
      </tr>
      <tr>
        <td align="left">&nbsp;</td>
        <td align="left">
        <?php
       
       
	    echo '<input type="submit" name="Programar" id="Programar" value="Programar Vehiculo"  />';
				
		?>
        </td>
      </tr>
    </table></form>
    </td>
  </tr>
</table>

</div>
<br />

<div style="width:100%; padding:1px; background:#ffffff;">
  <table width="100%" border="0">
    <tr>
      <td bgcolor="#000000"><div style="color:#FFF; font-weight:bold; background:url(images/top_bg.gif) repeat-x;">::</div></td>
    </tr>

</form>
</div>
</div>

</body>
</html>

