<!DOCTYPE html>
<HTML>
	<?php
	require('wp-blog-header.php');
	
	function getFileList($dir) { 
		# array to hold return value $retval = array(); 
		# add trailing slash if missing 
		
		if(substr($dir, -1) != "/") $dir .= "/"; 
		# open pointer to directory and read list of files  
		$d = @dir($dir) or die("getFileList: Failed opening directory $dir for reading"); 
		
		while(false !== ($entry = $d->read())) { 
			# skip hidden files  
				if($entry[0] == ".") continue; 
				if(is_dir("$dir$entry")) { 
					$retval[] = array( 
						"name" => "$dir$entry/", 
						"type" => filetype("$dir$entry"), 
						"size" => 0, 
						"lastmod" => filemtime("$dir$entry") ); 
				} elseif(is_readable("$dir$entry")) { 
					$retval[] = array( 
						"name" => "$dir$entry", 
						"type" => mime_content_type("$dir$entry"), 
						"size" => filesize("$dir$entry"),
						"lastmod" => filemtime("$dir$entry") ); 
				} 
		} 
		$d->close(); 
		return $retval; 
	}
	?>
	
<!--	<base href="http://doverknoll.com/aldiechurch/" /> -->
	<?php
		$theme_base = 'wp-content/themes/twentyten';
		include($theme_base . '/header.php');
	?>
	
	
	<DIV id='container'>
		
		<H2>Redeeming Features Newsletter</H2>
		
		<ul>
		<!-- Create a link for each newsletter in the newsletters directory -->
		<?php
			$url_base = 'http://doverknoll.com/aldiechurch';
			$news_dir = '/docs/newsletters/';
			$files = getFileList('.' . $news_dir);
			//TODO: Sort by filename
			
			foreach($files as $newsletter) {
				//Use regular expressions to change 2010-08.pdf to Aug 2010
				$file_title = $newsletter['name']; 
				$file_title = preg_replace('/.+?\//', '', $file_title);
				$file_name = $file_title;
				$file_title = preg_replace('/\.pdf/', '', $file_title);
				
				//$file_title = preg_replace('/(20\d{2})-(\d{2})/', '$2/$1', $file_title);
				
				$pat = array('/(20\d{2})-(01)/', '/(20\d{2})-(02)/', '/(20\d{2})-(03)/', '/(20\d{2})-(04)/', '/(20\d{2})-(05)/', '/(20\d{2})-(06)/', '/(20\d{2})-(07)/', '/(20\d{2})-(08)/', '/(20\d{2})-(09)/', '/(20\d{2})-(10)/', '/(20\d{2})-(11)/', '/(20\d{2})-(12)/');
				$rep = array('Jan $1', 'Feb $1', 'Mar $1', 'Apr $1', 'May $1', 'Jun $1', 'Jul $1', 'Aug $1', 'Sep $1', 'Oct $1', 'Nov $1', 'Dec $1');
				$file_title = preg_replace($pat, $rep, $file_title);
				
				
				?> <li><a href='Showpdf.php?url=<?= $url_base . $news_dir .  $file_name ?>'><?= $file_title ?></a></li>
			<?php } ?>

	</DIV>
	
	
	<?php
			include($theme_base . '/footer.php');
	?>
</HTML>