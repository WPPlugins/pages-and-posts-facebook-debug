<?php
function pagesandpostsfacebookdebugguer_menu() {
        add_menu_page('Facebook debug', 'Facebook debug', 8, 'pagesandpostsfacebookdebugguer_panel','pagesandpostsfacebookdebugguer_panel', 'dashicons-facebook',2);
}
add_action("admin_menu", "pagesandpostsfacebookdebugguer_menu");
function pagesandpostsfacebookdebugguer_panel() {	
	echo '<br><h1>Facebook debug</h1>';
	$output='';
	$args = array(
        'numberposts'       => -1
    );
	$pages = get_pages($args); 
	$nbpages=0;
	$nbposts=0;
	$nbtotal=0;
	$urlslist='';
	foreach ( $pages as $page ) {
		$nbpages++;
		if ($urlslist=='') { $urlslist=get_page_link( $page->ID ); }
		else { $urlslist.='#@-@#'.get_page_link( $page->ID ); }
	}
	$args = array(
        'numberposts'       => -1
    );
	wp_reset_query();
	$posts = get_posts($args);
	//if ( have_posts() ) {
		foreach ( $posts as $post ) : setup_postdata( $post );
			$nbposts++;
			if ($urlslist=='') { $urlslist=get_the_permalink(); }
			else { $urlslist.='#@-@#'.get_the_permalink(); }
		endforeach; 
	//}
	$nbtotal=$nbpages+$nbposts;
	$output.='<style>.wpallpagesscrapefb {width:100%;text-align:center;padding:10px;background:#0066cc;color:#FFF;border:1px solid #FFF;cursor:pointer;}.wpallpagesscrapefb:hover {background:#fff;color:#0066cc;border:1px solid #0066cc;}.wpallpagesscrapefbresults{display:none;margin-top:10px;color:#0066cc;padding;:10px;}</style>'.__( 'A total of ' , 'pagesandpostsfacebookdebugguer' ).' <strong>'.$nbpages.' '.__( 'Page ' , 'pagesandpostsfacebookdebugguer' ).($nbpages>1 ? 's' : '').'</strong> '.__( 'and' , 'pagesandpostsfacebookdebugguer' ).' <strong>'.$nbposts.' '.__( 'Post' , 'pagesandpostsfacebookdebugguer' ).($nbposts>1 ? 's' : '').'</strong> '.__( 'has been found' , 'pagesandpostsfacebookdebugguer' ).'.<br><br><input type="checkbox" id="papfbauto" '.(get_option('papfbauto_value')==1 ? 'checked' : '').'> '.__( 'Automatic debug when updating a page/post' , 'pagesandpostsfacebookdebugguer' ).'<br><br><input type="submit" class="wpallpagesscrapefb" style="width:auto;" value="'.__( 'Start debug of all URLs' , 'pagesandpostsfacebookdebugguer' ).'"><div class="wpallpagesscrapefbresults">&nbsp;</div>
	<script>
	jQuery("#papfbauto").change(function() {
		if (jQuery("#papfbauto").prop("checked")==true) { papfbauto_value=1; }
		else { papfbauto_value=0; }
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: { "action": "papfbauto" , "papfbauto_value": papfbauto_value }					
		}).done(function( msg ) {
			resp=msg.response;
			if (resp == "error"){
				alert("Erreur !");
			}
			else {
				
			}
		});
	});
	
	function buttonencours(actbutton) {
		actnbresults=jQuery("#wpallpagesscrapefbresultsnb").text();
		if (actnbresults==nbtotal) {
			actbutton.val("'.__( 'Cache cleaning finished' , 'pagesandpostsfacebookdebugguer' ).'");
		}
		if (actbutton.val()=="'.__( 'Start debug of all URLs' , 'pagesandpostsfacebookdebugguer' ).'") { actbutton.val("'.__( 'Cleaning in progress' , 'pagesandpostsfacebookdebugguer' ).' ."); }
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
				actbutton.val("'.__( 'Start debug of all URLs' , 'pagesandpostsfacebookdebugguer' ).'");
			}, 500);
		}
	}
	jQuery(".wpallpagesscrapefb").click(function() {
		
		actbutton=jQuery(this);
		setTimeout(function(){
			buttonencours(actbutton);
		}, 100);
		jQuery(".wpallpagesscrapefbresults").html("'.__( 'Debug start on ' , 'pagesandpostsfacebookdebugguer' ).' '.$nbtotal.' URL'.($nbtotal>1 ? 's' : '').' : <span id=\"wpallpagesscrapefbresultsnb\">0</span> / '.$nbtotal.' '.__( 'completed' , 'pagesandpostsfacebookdebugguer' ).'.").fadeIn();
		nbtotal='.$nbtotal.';
		urlslist=\''.$urlslist.'\';
		expurlslist=urlslist.split("#@-@#"); 
		expurlslist.forEach(function(entry) {
			jQuery.post(
				"https://graph.facebook.com",
				{
					id: entry,
					scrape: true
				},
				function(response){
					actnbresults=jQuery("#wpallpagesscrapefbresultsnb").text();
					actnbresults=parseInt(actnbresults)+1;
					jQuery("#wpallpagesscrapefbresultsnb").text(actnbresults);
					/*console.log(response);*/
					/*if (actnbresults==nbtotal) {
						jQuery(".wpallpagesscrapefbresults").append("<br><br><span style=\"color:#009900\">'.__( 'Process completed !' , 'pagesandpostsfacebookdebugguer' ).'</span>");
					}*/
				}
			);
		});
		jQuery.post(
			"https://graph.facebook.com",
			{
				id: "'.site_url().'",
				scrape: true
			},
			function(response){
				/*console.log(response);*/
				if (actnbresults==nbtotal) {
					jQuery(".wpallpagesscrapefbresults").append("<br><br><span style=\"color:#009900\">'.__( 'Process completed !' , 'pagesandpostsfacebookdebugguer' ).'</span>");
				}
			}
		);
	});
	</script>';
	echo $output;
}
?>