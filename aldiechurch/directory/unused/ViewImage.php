<?php
	//connect to database. Username and password need to be changed
	set_include_path('..');
	include('DbConnect.php');

	header('Content-type: image/jpg');
	
	//get decoded image data from database
	$res=mysql_query("SELECT * FROM dir_families WHERE famlyid=1");
	
	//fetch data from database
	$data=mysql_fetch_array($res);
	$encoded=$data['picture'];	
?>
<HTML>
	<BODY>
	<?php
			//decode and echo the image data
			echo base64_decode($encoded);
	?>
	</BODY>
</HTML>