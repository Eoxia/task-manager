<?php if ( !defined( 'ABSPATH' ) ) exit; ?>

<?php 
if ( !empty( $_POST['filter'] ) ):
	foreach( $_POST['filter'] as $filter ):
		$filter = str_replace( 'wpeo-', '', $filter );
		echo $filter;
		echo ' : ' . $list_time[$filter]['elapsed'] . '/' . $list_time[$filter]['estimated'];
	endforeach;
endif;

?>