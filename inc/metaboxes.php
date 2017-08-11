<?php
function add_papfbdebug_meta_box() {
	add_meta_box('papfbdebug_meta_box','Facebook Debug', 'show_papfbdebug_meta_box', 'page', 'side', 'high' );
	add_meta_box('papfbdebug_meta_box_post','Facebook Debug', 'show_papfbdebug_meta_box', 'post', 'side', 'high' );
}
add_action('add_meta_boxes', 'add_papfbdebug_meta_box');
function show_papfbdebug_meta_box() {
	global $post;
	echo '<input class="button button-primary button-large" id="papfbdebug" value="'.__( 'Empty Facebook cache' , 'pagesandpostsfacebookdebugguer' ).'" type="submit">
	<script type="text/javascript">
	function buttonencours(actbutton) {
		if (actbutton.val()=="'.__( 'Empty Facebook cache' , 'pagesandpostsfacebookdebugguer' ).'") { actbutton.val("'.__( 'Cleaning in progress' , 'pagesandpostsfacebookdebugguer' ).' ."); }
		else if (actbutton.val()=="'.__( 'Cleaning in progress' , 'pagesandpostsfacebookdebugguer' ).' .") { actbutton.val("'.__( 'Cleaning in progress' , 'pagesandpostsfacebookdebugguer' ).' .."); }
		else if (actbutton.val()=="'.__( 'Cleaning in progress' , 'pagesandpostsfacebookdebugguer' ).' ..") { actbutton.val("'.__( 'Cleaning in progress' , 'pagesandpostsfacebookdebugguer' ).' ..."); }
		else if (actbutton.val()=="'.__( 'Cleaning in progress' , 'pagesandpostsfacebookdebugguer' ).' ...") { actbutton.val("'.__( 'Cleaning in progress' , 'pagesandpostsfacebookdebugguer' ).' ."); }
		if (actbutton.val()!="'.__( 'Cache cleaning finished' , 'pagesandpostsfacebookdebugguer' ).'") {
			setTimeout(function(){
				buttonencours(actbutton);
			}, 500);
		}
		else {
			setTimeout(function(){
				actbutton.val("'.__( 'Empty Facebook cache' , 'pagesandpostsfacebookdebugguer' ).'").removeClass("button-secondary").addClass("button-primary");
			}, 500);
		}
	}
	jQuery("#papfbdebug").click(function(e){
		e.preventDefault();
		actpermalink="'.get_permalink($post->ID).'";
		actbutton=jQuery(this);
		jQuery(this).removeClass("button-primary").addClass("button-secondary");
		setTimeout(function(){
			buttonencours(actbutton);
		}, 100);
		jQuery.post(
				"https://graph.facebook.com",
				{
					id: actpermalink,
					scrape: true
				},
				function(response){
					actbutton.val("'.__( 'Cache cleaning finished' , 'pagesandpostsfacebookdebugguer' ).'");
				}
			);
		
	});
	</script>';
}
?>