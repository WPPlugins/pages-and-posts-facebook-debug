<?php
add_action('wp_ajax_papfbauto', 'papfbauto');
function papfbauto(){
    $response = array();
    if($_POST['papfbauto_value']==0 || $_POST['papfbauto_value']==1){
		$papfbauto_value=$_POST['papfbauto_value'];
		$act_value=get_option('papfbauto_value');
		if ($act_value==0 || $act_value==1) { update_option( 'papfbauto_value', $papfbauto_value ); }
		else { add_option( 'papfbauto_value', $papfbauto_value); }
		$response['response'] = "ok";
    }
	else { $response['response'] = "error"; }
    header( "Content-Type: application/json" );
    if (isset($response)) { echo json_encode($response); }
    exit();
}
function papfbauto_check( $post_id ) {
	$act_value=get_option('papfbauto_value');
	if (isset($_GET['post']) && $_GET['post']>0 && $_GET['action']=='edit' && $act_value==1) {
		$post_url = get_permalink( $_GET['post'] );
		echo '
		<script>
		jQuery.post(
			"https://graph.facebook.com",
			{
				id: "'.$post_url.'",
				scrape: true
			},
			function(response){
				/*console.log(response);*/
			}
		);
		</script>';
		
	}
}
add_action( 'admin_footer', 'papfbauto_check' );
?>