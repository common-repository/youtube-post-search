<?php
/**
 * @package Youtube Video Search
 */
/**
  Plugin Name: Youtube Video Search
  Plugin URI: https://wordpress.org/plugins/youtube-post-search/
  Description: Search Youtube videos from any pages or post adding just shortcode or php code. It gives real time search suggestions.
  Version: 2.2.7
  Author: Mauriya:Ryan
  Author URI: http://www.mauriya.me
  License: GPLv2 or later
  License URI: https://www.gnu.org/licenses/gpl-2.0.html
  Text Domain: Youtube Video Search Connection
 */
add_action('admin_menu', 'youtube_connection');
function youtube_connection() {
    $page_title = 'Youtube API Connection Setting';
    $menu_title = 'Youtube API Setting';
    $capability = 'manage_options';
    $menu_slug = 'youtube_search_setting';
    $function = 'youtube_search_setting';
    $icon_url = plugins_url( 'images/youtube.png', __FILE__ );
    $position = 18;

    add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}
/* Determine The Time Location START */
/* Determine The Time Location END */
function youtube_search_setting() {

	    if (isset($_POST['youtube_key'])) {
	        update_option('youtube_key', $_POST['youtube_key']);
	        $youtubekey = $_POST['youtube_key'];
	    } 
 	   $youtubekey = get_option('youtube_key', 'AIzaSyDAKDaBy_JDwcScSHqDQimOOLjdPImLanc' );
        if (isset($_POST['youtube_num'])) {
	        update_option('youtube_num', $_POST['youtube_num']);
	        $youtubenum = $_POST['youtube_num'];
	    } 
 	   $youtubenum = get_option('youtube_num', '20' );
    
    include 'setting/youtubekey.php';
}
/* Run the remove options END */
function remove_youtpstsrch_hook() {
	delete_option('youtube_key');
	delete_option('youtube_num');
	
}
register_deactivation_hook(__FILE__, 'remove_youtpstsrch_hook');
/* Run the remove options END */
add_action( 'admin_notices', 'ryan_admin_notice__success' );
function ryan_admin_notice__success() {
     if (isset($_POST['youtube_num'])) {
    ?>
    <div class="notice notice-success is-dismissible">
        <p>Youtube Post Search Plugin Updated!</p>
    </div>
    <?php
     }
}
function load_youtubesearchscript()
{ ?> 
<link rel="stylesheet" href="<?php echo plugins_url( '/css/style.css', __FILE__ ); ?>" type="text/css" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.0/themes/smoothness/jquery-ui.css" type="text/css" />
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" type="text/javascript"></script>
<?php
}

add_action('wp_head', 'load_youtubesearchscript');
add_action( 'wp_footer', 'custom_my_youtube_script');
// add shortcode
add_shortcode('youtubesearch', 'youtubesearch'); 
function youtubesearch($wth){
    ?>
  <form id="search-term" method="" onsubmit="return false;">
    <input id="query" type="text"/>
    <input type="submit" class="icon" value="" onsubmit="return false;"/>
  </form><br/>
<div class="container">
  <div id="now_video" style="width:100%"></div>
	<div class="row" id="search-results"></div>
</div>
<?php
}
function custom_my_youtube_script() {
    $youtubenum = get_option('youtube_num');
    $youtubekey = get_option('youtube_key');
    ?>
<script type='text/javascript'>
//var listoplyryn;
function getRequest(searchTerm) {
    url = 'https://www.googleapis.com/youtube/v3/search';
    let params = {
        maxResults: '<?php echo $youtubenum; ?>',
        part: 'snippet',
        order: 'viewCount',
        key: '<?php echo $youtubekey; ?>',
        q: searchTerm,
        type: 'video'
    };
  
    $.getJSON(url, params, function (searchTerm) {
        showResults(searchTerm);
    });
}


function addCommas(num) {
    let str = num.toString().split('.');
    if (str[0].length >= 4) {
        str[0] = str[0].replace(/(\d)(?=(\d{3})+$)/g, '$1,');
    }

    return str.join('.');
}   
    

function getstars(likes,dislikes) {
    let total = Number(likes) + Number(dislikes);
    let percent = (likes/total).toFixed(2)*100;
//    percent = percent.toFixed(2);
    return '<span class="star"><span class="star-rating" style="width: '+percent.toFixed(2)+'%;">&nbsp;</span></span>';
    
}
    
    
function getVideoCount(videov,i) {
    let filename = 'https://www.googleapis.com/youtube/v3/videos?id=' + videov +'&part=statistics&key='+'<?php echo $youtubekey; ?>'+'';
    fetch(filename).then((resp) => resp.text()).then(function(data) {
        let theitems = JSON.parse(data);
//        alert(JSON.stringify(theitems.items[0].statistics));
        let thenumber = String(JSON.stringify(theitems.items[0].statistics.viewCount));
        let thecomments = String(JSON.stringify(theitems.items[0].statistics.commentCount));
        let thelikes = String(JSON.stringify(theitems.items[0].statistics.likeCount));
        let thedislikes = String(JSON.stringify(theitems.items[0].statistics.dislikeCount));
        let finalnumber = thenumber.replace(/[^0-9]/g,'');
        let finalcomments = thecomments.replace(/[^0-9]/g,'');
        let likes = thelikes.replace(/[^0-9]/g,'');
        let dislikes = thedislikes.replace(/[^0-9]/g,'');
        document.getElementById("view"+i).innerHTML = '<b class="views">&nbsp;</b> '+addCommas(finalnumber)+'&nbsp;&nbsp;<b class="ratings">&nbsp;</b> '+ getstars(likes,dislikes)+'<br/><b class="comments">&nbsp;</b> '+addCommas(finalcomments);
    });
}
    
var listoplyryn;   
function showResults(results) {
    let html = "";
    let entries = results.items;
    listoplyryn = ',';
    let i = 0;
    $.each(entries, function (index, value) {
        let title = value.snippet.title;
        let thumbnail = value.snippet.thumbnails.medium.url;
        let descript = value.snippet.description;
        if(typeof value.id.videoId !== 'undefined') {
            getVideoCount(value.id.videoId,i);
            listoplyryn += ',' + value.id.videoId;
			html += '<div style="width: 280px; height: 460px; margin: auto; overflow: auto;"><div class="card" id="whatsup" style="width: 280px; height: auto;" onclick="getmevideo(\'' + value.id.videoId +'\');"><img class="card-img-top" src="' + thumbnail +'" alt="Card image cap"><div class="card-block"><h4 class="card-title">'+  title.substring(0, 47) +' ...</h4><p class="card-text" style="font-size:14px;">'+ descript.substring(0, 110) +' ... </p><p><div class="viewsdesc" id="view'+i+'">  </div></p></div></div></div>'; i++;
           } else {
			    html += '<div>No Results</div>';
		   }
    });  
    $('#search-results').html(html);
}

    jQuery(function() {
		    jQuery( "#query" ).autocomplete({
		      source: function( request, response ) {
                let searchTerm = $('#query').val();
                getRequest(searchTerm);
		      	//console.log(request.term);
                $('#search-term').on('click', function (event) {
                    event.preventDefault();
                    let searchTerm = $('#query').val();
                    getRequest(searchTerm);
                });
		    	let sqValue = [];
		        jQuery.ajax({
			        type: "POST",
			        url: "http://suggestqueries.google.com/complete/search?hl=en&ds=yt&client=youtube&hjson=t&cp=1",
			        dataType: 'jsonp',
			        data: jQuery.extend({
			            q: request.term
			        }, {  }),
			        success: function(data){
			        	console.log(data[1]);
			           	obj = data[1];
						jQuery.each( obj, function( key, value ) {
							sqValue.push(value[0]);
						});
			           	response( sqValue);
			        }
			    });
		      }
		    });
	});


function getmevideo(myvideo,i) {
    let mywidth = document.getElementById("now_video").clientWidth; 
    document.getElementById("now_video").innerHTML = '<iframe width="'+mywidth+'" height="'+(mywidth*0.5625)+'" src="https://www.youtube.com/embed/' + myvideo + '?playlist=' + listoplyryn + '&cc_load_policy=0&iv_load_policy=3&showinfo=0&modestbranding=1&fs=1&loop=1&hl=en_US&autoplay=1" frameborder="0" allowfullscreen></iframe>';
    window.location.hash = "search-term";
}
</script>
<?php
}
?>