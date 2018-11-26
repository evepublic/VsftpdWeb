

<h1>FTP LOG</h1>
<table class="log">
<tr><th>Info</th><th>Size</th><th>State</th><th>User</th><th>File Name</th></tr>
<?php
		// $myfile should be log file path
	$myfile = $log_path;
	if (file_exists($myfile) ) { 
		if (filesize($myfile) > 0 ) {

			$ic = 0;
			$ic_max = 200;  // stops after this number of rows
			$handle = popen( "tac $myfile " , "r");
			while (!feof($handle) && ++$ic<=$ic_max) {
			//for ($ic = -1; $ic >-101; $ic-- ) {
			$buffer = fgets($handle, 4096);
			if ($buffer===false) {
			continue;
			}
			
			
			
			//size
			$size = strstr($buffer, "/", true);
			$pos = strrpos($size, ".")+ 1;
			
			$size = substr($size,$pos);
			$size = strstr($size, " ");

			$size = trim($size);
			
			$si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
			$base = 1024;
			if ($size == 0) {
			$msize = '0 '.$si_prefix[0];
			}
			else {
			$class = min((int)log((int)$size , $base) , count($si_prefix) - 1);
			$msize = sprintf('%1.2f' , $size / pow($base,$class)) . ' ' . $si_prefix[$class];
			}
			//
			
			//name
			$name = strstr($buffer, "/");
			$name = strstr($name, " _ ", true);
			$name = substr($name,0,-1);
			//

			//state and user
			$state = strstr($buffer, " _ ");
			$user = $state;
			$state = strstr($state, "g", true);
			$state = substr($state,3, -1);
			if ($state == 'i') $state = 'Uploaded';
			else if ($state == 'o') $state = 'Downloaded';
			
			$user = strstr($user, "g");
			$user = strstr($user, "ftp", true);
			$user = substr($user,2, -1);
			//b _ i
		
			$info = strstr($buffer, $size, true);
			
			
			echo "<tr>
			
			<td>".$info."</td>
			<td>".$msize."</td>
			<td>".$state."</td>
			<td>".$user."</td>
			<td>".$name."</td>
			</tr>";
			
			
			
			}
			pclose($handle);

		} else { echo "<h2>---Log file is empty---</h2>";}
	} else { echo "<h2>---Log file path is incorrect---</h2>";}

?>
</table>
