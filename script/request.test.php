<?php
/**
* @author: Jimmy Latour jimmy.eoxia@gmail.com
*/

echo "[+] Starting Request Tests" . PHP_EOL . PHP_EOL;

// Search for test files
$unitList = search_files('../', "/^.*\.php$/");
$string_post_unsecured = array();
$total_unsecured_line = 0;
$pattern = '#\$_POST|\$_GET|\$_REQUEST#';

// Loop on unitList
foreach($unitList as $test)
{
	// echo "[+] Testing -> " . $test . PHP_EOL;
  if ( $test != '../script/request.test.php' ) {
    $file = file_get_contents( $test );
    $string_post_unsecured[$test] = array();
    $lines = explode( PHP_EOL, $file );

    if ( !empty( $lines ) ) {
      foreach ( $lines as $key => $line ) {
        if ( preg_match( $pattern, $line ) ) {
          $lines[$key] = preg_replace( '#!empty\(.+?\$_POST|\$_GET|$_REQUEST\[\'.+\'\].+?\) \?#isU', '', $lines[$key] );
          if ( !preg_match( '#sanitize_.+#', $lines[$key] ) &&
            !preg_match( '#\*#', $lines[$key] ) &&
            !preg_match( '#\\/\/#', $lines[$key] ) &&
            !preg_match( '#(.+?int.+?)#', $lines[$key] ) ) {
              $string_post_unsecured[$test][$key + 1] = htmlentities($lines[$key]);
              $total_unsecured_line++;
          }
        };
      }
    }
  }
}

echo "[+] Total unsecured line : " . $total_unsecured_line . PHP_EOL;

if ( !empty( $string_post_unsecured ) ) {
  foreach( $string_post_unsecured as $name_file => $file ) {
    if ( !empty( $file ) ) {
      echo "[+] File : " . $name_file . ' => Unsecured $_POST|$_GET|$_REQUEST ' . count( $file ) . PHP_EOL;
      foreach ( $file as $line => $content ) {
        echo "[+] Line : " . $line . " => " . trim($content) . PHP_EOL;
      }
    }
  }
}

trigger_error( "[+] Total unsecured line : " . $total_unsecured_line, E_USER_ERROR );
echo "[+] Request Tests Finished" . PHP_EOL;

function search_files($folder, $pattern)
{
	$dir = new RecursiveDirectoryIterator($folder);
	$ite = new RecursiveIteratorIterator($dir);
	$files = new RegexIterator($ite, $pattern, RegexIterator::GET_MATCH);
	$fileList = array();
	foreach($files as $file)
	{
		$fileList[] = $file[0];
	}
	return $fileList;
}
