<?php
include('session.php');
include('phpsqlajax_dbinfo.php');


$sql=mysql_query("SELECT * FROM users WHERE id='$GPSUSERID'");
$result=mysql_fetch_array($sql);
$GPSUSERNAME=strtoupper($result['login']);
?>
<!DOCTYPE html>
<html>
  <head>
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<META HTTP-EQUIV="content-type" CONTENT="text/html; charset=UTF-8">
    <title>Seguimiento GPS Pedro Camejo</title>
    
<style>
 html, body
	{
		height: 100%;
		width: 100%;
		margin: 0px;
		padding: 0px;
		overflow: hidden;
	}
#iframe { width:100%; height:650px; }
.ITitle { font:Georgia, "Times New Roman", Times, serif; font-size:15px; }

.lnk { 
padding-top:2px;
margin:2px;
background:#dd0103 url(images/bg/meun_out.gif) repeat-x;
font-family:Arial, Helvetica, sans-serif;
font-size:11px;
font-weight:bold;
width:65px;
height:22px;
float:left;
border-radius:5px;
border:1px solid #006;
border-bottom:none;
text-align:center;
}
a:hover .lnk { background:#dd0103 url(images/bg/menu_over.gif) repeat-x; }
a:active .lnk { background:#dd0103 url(images/bg/menu_over.gif) repeat-x; }
</style>
    
  </head>
  <body>
  <table width="100%" height="100%" border="0" style="margin-top:-2px; margin-left:-1px;">
      <tr>
        <td height="53" style="background:url(images/bg/top_bg.gif) repeat-x;">
        <div style="float:left; padding-botom:5px;"><img style="margin-bottom:5px;" src="images/bg/images_logo_cn.gif" height="53" alt="Globe GPS"></div>
        <div style="margin: 10px 50px; font-size:18px; float:left; color:#FFF; font-family:Arial, Helvetica, sans-serif; font-weight:bold; 
        width:250px;">
        <?php echo $GPSUSERNAME; ?>
        
        </div>
        <div align="right" style="margin: 12px 12px; font-size:13px; float:right; color:#FFF; 
        font:Georgia, 'Times New Roman', Times, serif bold; width:510px;">
            <a href="gis.php" style="text-decoration:none; color:#FFF;"><div class="lnk">Inicio</div></a>
            <a href="reports.php" style="text-decoration:none; color:#FFF;"><div class="lnk">Reportes</div></a>
            <a href="settings.php" style="text-decoration:none; color:#FFF;"><div class="lnk">Ajustes</div></a>
            <a href="comando.php" style="text-decoration:none; color:#FFF;"><div class="lnk">Comando</div></a>
            <a href="programa.php" style="text-decoration:none; color:#FFF;"><div class="lnk">Programar</div></a>
            <a href="gis2.php" style="text-decoration:none; color:#FFF;"><div class="lnk">Visualizar</div></a>            
            <a href="logout.php" style="text-decoration:none; color:#FFF;"><div class="lnk">Cerrar</div></a> 
        </div>
        </td>
      </tr>
      <tr>
        <td width="80%" align="left" valign="top"><iframe frameborder="0" name="iframe" src="mapframe.php" id="iframe"></iframe></td>
    </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
  </table>
  </body>
</html>

