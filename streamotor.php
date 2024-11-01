<?php
/*
Plugin Name: STREAMOTOR 
Plugin URI: http://www.streamotor.com/wordpress/
Description: Manage your Streamotor video content from Wordpress.
Version: 1.0.5
Author: Imavex, LLC 
Author URI: http://www.imavex.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

$strm_verbose = "STREAMOTOR WordPress Plugin";
$strm_version = "1.0.5";
$strm_int_version = preg_replace("/[^\d+]/", "", $strm_version);
$strm_db_version = "1.0.0";

$strm_http_plugin_path = plugins_url() . "/streamotor/";

require_once(ABSPATH . 'wp-content/plugins/streamotor/includes/utility.php');
require_once(ABSPATH . 'wp-content/plugins/streamotor/includes/aff.php');
require_once(ABSPATH . 'wp-content/plugins/streamotor/includes/smapi.inc.php');

/*************************************************************/
// admin tools 
/*************************************************************/
function strm_manage() 
{
	global $strm_version, $strm_http_plugin_path;
	?>
		

	<div id='strm'>
		<div id='strm_messageCenter' style='display: none;'><ul></ul></div>
		<h1><a href="http://www.streamotor.com/" target="_new"><img src="<?php echo $strm_http_plugin_path;?>images/h-logo.png" border="0"></a></h1>
		<h3>Plugin by Imavex, LLC - Version: <?php echo $strm_version;?></h3>
		<div id='strm_credentials'>
		<?php
			$res = strm_checkCredentials();
			if($res)
				echo "Authenticated as <span>" . $res["u"] . "</span><br>";
		?>
		<a href="javascript:;" onClick="strm_showSetup();">Setup Credentials</a>
		</div>

		<div id='strm_social' style="display: none;">
			<a href='javascript:;' onClick="strm_loadManager('sharing');">Click here</a> to connect your Streamotor account to these social networks to broaden your reach.
			<ul>
				<li id='strm_social_youtube'><img src='<?php echo $strm_http_plugin_path;?>images/youtube_icon.png' ><span>Inactive</span></li>
				<li id='strm_social_facebook'><img src='<?php echo $strm_http_plugin_path;?>images/facebook_icon_grey.png'><span>Inactive</span></li>
				<li id='strm_social_vimeo'><img src='<?php echo $strm_http_plugin_path;?>images/vimeo_icon_grey.png'><span>Inactive</span></li>
			</ul>
		</div>

		<div id='strm_list_header'>
			<a href="javascript:;" onClick="strm_loadManager('uploadVideo');" class="strm_mainButton"><div><img src="<?php echo $strm_http_plugin_path;?>images/film_add.png"></div><p>Upload Video</p></a>
			<a href="javascript:;" onClick="strm_loadManager('addCategory','');" class="strm_mainButton"><div><img src="<?php echo $strm_http_plugin_path;?>images/folder_add.png"></div><p>Create Category</p></a>
			<a href="javascript:;" onClick="strm_getTopCategories();" class="strm_mainButton"><div><img src="<?php echo $strm_http_plugin_path;?>images/arrow_rotate_clockwise.png"></div><p>Refresh</p></a>
			<a href="javascript:;" onClick="strm_loadManager('reports');" class="strm_mainButton"><div><img src="<?php echo $strm_http_plugin_path;?>images/chart_curve.png"></div><p>Stats</p></a>
			<a href="javascript:;" onClick="strm_loadSocial();" class="strm_mainButton"><div><img src="<?php echo $strm_http_plugin_path;?>images/transmit_blue.png"></div><p>Connect</p></a>
			<div class="clearfix"><!-- --></div>
		</div>
		<div id="strm_searchContainer">
				<a href="javascript:strm_search('',0,10)">Latest Videos</a> |
				<a href="javascript:strm_search('pending',0,10);">Pending Videos</a><br>
			<input type="text" id='strm_searchTerm'><br>
		</div>

		<div id='strm_container'>
			<div id='strm_list'>
			</div>
			<div id="strm_search">
				<h2 id='strm_searchTitle' style='margin-top: 0'>Latest Videos</h2>

				<div id='strm_searchResults'>
				</div>
			</div>
			<div class="clearfix"><!-- --></div>
		</div>
		
		<div id='strm_processing_div' class='strm_window' align='center' style='display: none;'>
			<div style='width: 120px;'>
				<div style='float: left;'><img src='<?php echo $strm_http_plugin_path;?>/images/loader.gif'></div>
				<div style='float: left; padding-left: 5px;'>Processing</div>
				<div class='clear: fix'><!-- --></div>
			</div>
		</div>

		<div id='strm_loading_div' class='strm_window' align='center' style='display: none;'>
			<div style='width: 100px;'>
				<div style='float: left;'><img src='<?php echo $strm_http_plugin_path;?>/images/loader.gif'></div>
				<div style='float: left; padding-left: 5px;'>Loading</div>
				<div class='clear: fix'><!-- --></div>
			</div>
		</div>
	
		<div class="strm_window" id="strm_setup" style="display: none;">
			<img src="<?php echo $strm_http_plugin_path;?>images/h-logo.png" border="0">
			<form id="strm_setupForm" onSubmit="strm_saveSettings(0); return false;">
			<input type="hidden" name="action" value="strm_save_settings">
			<input type="hidden" name="id" value="">
			<div id="strm_signup_left">
				<h2>Setup your credentials</h2>
				<div class="strm_form_field">
					Username*<br>
					<input type='text' name='data_u' id='strm_data_u'>
				</div>
				<div class="strm_form_field">
					Password*<br>
					<input type='password' name='data_p' id='strm_data_p'>
				</div>
				<div class="strm_form_field">
					<input type="button" class="button button-secondary action" id="manageButton" value="Save" onClick="strm_saveSettings(1);">
					<input type="button" class="button button-secondary action" id="manageButton" value="Cancel" onClick="strm_hideSetup('strm_setup',0);">
				</div>

				<h2>Sign-Up for Streamotor</h2>
				<?php
					$strm_aff = get_option("strm_aff");
					if(empty($strm_aff) || $strm_aff == 1)
						$sign_url = "http://www.streamotor.com/signup.php";
					else	
						$sign_url = "http://www.streamotor.com/signup.php?strm_aff=" . $strm_aff;
				?>
				<a class="strm_signup" href="<?php echo $sign_url;?>"><span>Sign Up</span></a>
			</div>
			<div id="strm_signup_right">
				<div id="strm_signup">
					<iframe id="strm_signup_iframe" src="" scrolling="no" border="0"></iframe>
				</div>
			</div>
			</form>
		</div>

		<div class="strm_window" id="strm_manageVideo" style="display: none;">
			<form id="strm_manageVideoForm" onSubmit="return false;">
			<input type="hidden" name="cmd" value="">
			<input type="hidden" name="action" value="strm_post_data">
			<input type="hidden" name="id" value="">
			<h2>Create a Directory Listing</h2>
			<div class="strm_form_field">
				Video Name*<br>
				<input type='text' name='Name'>
			</div>
			<div class="strm_form_field">
				Description<br>
				<textarea name='Description'></textarea><br>
				<a href="#">Editor</a>
			</div>
			<?php
			$res = strm_checkCredentials();
			?>
			<div class="strm_form_field">
				<input type="button" class="button button-secondary action" id="manageButton" value="Save" onClick="strm_postManageListing()">
				<input type="button" class="button button-secondary action" value="Cancel" onClick="strm_hideManageForm();">
			</div>
			</form>
		</div>
	</div>	
	<script>
		jQuery(document).ready( function () {
			strm_getTopCategories();
			strm_search("",0,10);
			
			var default_text= "Supply a search term and hit 'Enter'...";
			var my_input_id = "strm_searchTerm";

			jQuery("#" + my_input_id).focus( function () {
			   if(jQuery(this).attr("value") == default_text)
			   {
				   jQuery(this).attr("value", "");
			   }
			});
			

			jQuery("#" + my_input_id).blur ( function () {
			   if(jQuery(this).attr("value") == "")
			   {
				   jQuery(this).attr("value", default_text);
			   }
			});

			jQuery("#" + my_input_id).attr("value", default_text);

			jQuery("#" + my_input_id).keypress( function (event) {
				if(event.which == 13)
				{
					event.preventDefault();
					strm_search(jQuery(this).val(),0,10);
				}
			});

		});

		strm_buildPopupWindow();
		</script>

		<div id='strm_application_test'>
			<iframe id="strm_application_test_iframe" src='http://www.streamotor.com/apiSessionTest.php?start=1&redirect=<?php echo urlencode(plugins_url() . "/streamotor/includes/blank.html"); ?>' frameborder="0" ALLOWTRANSPARENCY="true"></iframe>
		</div>
		<script>
			jQuery(document).ready( function () {
				strm_runApplicationTest();
			});

		</script>
	<?php
}

function strm_adminHeader() 
{
global $_SERVER;
	//only include javascript on certain pages in wp-admin
	if($_GET["page"] ==  "streamotor" ||
	  (strpos($_SERVER["REQUEST_URI"], "post.php") !== false) || 
	  (strpos($_SERVER["REQUEST_URI"], "post-new.php") !== false))
	{
		?>
		<link rel='stylesheet' href='<?php echo plugins_url();?>/streamotor/css/sm.css.php' type='text/css' media='screen' />
		<script language="javascript" src="<?php echo plugins_url();?>/streamotor/js/sm.js.php"></script>
		<?php
	}
}

function strm_wp_init()
{
	global $strm_setup_aff;		
	if(strpos(getcwd(), "wp-admin") !== false && strpos($_SERVER["REQUEST_URI"], "streamotor") !== false) //admin
	{
		$tmpaff = get_option("strm_aff");
		if(empty($tmpaff))
		{
			if(empty($strm_setup_aff))
				add_option("strm_aff", 1, "", "no");
			else	
				add_option("strm_aff", $strm_setup_aff, "", "no");
		}


		global $wp_scripts;
		if(is_array($wp_scripts->queue))
		{

			for($q=0;$q<sizeof($wp_scripts->queue);$q++)
			{
				$key = $wp_scripts->queue[$q];
				if($key != 'jquery' && $key != 'jquery-ui-core' && $key != 'jquery-ui-widget' && $key != 'jquery-ui-accordian' && $key != 'jquery-ui-css')
					wp_deregister_script("$key");
			}
		}
		
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');
		wp_enqueue_script('jquery-ui-widget');
		wp_enqueue_script('jquery-ui-accordion');
		wp_register_style('jquery-ui-css', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/smoothness/jquery-ui.css');
		wp_enqueue_style('jquery-ui-css');
	}
}



/*************************************************************/
// AJAX functions 
/*************************************************************/

function _strm_ajax_getVideos()
{
global $_GET;

	$res = strm_checkCredentials();
	if($res)
	{
		if(!is_numeric($_GET["page"]))
			$_GET["page"] = 0;
		if(!is_numeric($_GET["perpage"]))
			$_GET["perpage"] = 10;

		
		$smObj = new Streamotor;
		$smObj->init($res['u'], $res['p'], $res["aff"]);

		$res = $smObj->getSMVideos($_GET["search"], $_GET["page"], $_GET["perpage"]) ;
		if($res == false)
		{
			_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
			return false;
		}
		else if($res == -1)
		{
			$err = "No Videos";
		}
		
		_strm_ajaxOutputResponse($res, $err, "Title");

	}
	else
	{
		_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
	}
	exit();
}

function _strm_ajax_getCategoryInfo()
{
global $_GET;

	$res = strm_checkCredentials();
	if($res)
	{
		$smObj = new Streamotor;
		$smObj->init($res['u'], $res['p'], $res["aff"]);
		$cat = $smObj->getSMCategory($_GET["CategoryID"], $_GET["Page"], 10);
		if($cat == false)
		{
			_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
			return false;
		}
		else if($cat == -1)
		{
			$err = "There was an error getting category information.";
		}
		_strm_ajaxOutputResponse($cat, $err, "CategoryID");
	}
	else
	{
		_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
	}
	
	exit();
}

function _strm_ajax_getCategoryPages()
{
global $_GET;

	$res = strm_checkCredentials();
	if($res)
	{
	}
	else
	{
		_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
	}
	
	exit();
}

function _strm_ajax_saveSettings()
{
global $_POST;

	$res = strm_checkCredentials();
	update_option("strmp",base64_encode($_POST["data_p"]));
	update_option("strmu",base64_encode($_POST["data_u"]));

}	

function strm_checkCredentials()
{
	$m09="c3RybXU=";$m10="c3RybXA=";$m11=get_option(base64_decode($m09),"");$m12=get_option(base64_decode($m10),"");$m92=get_option("strm_aff",1);if(empty($m11)||empty($m12)) return false; else { $m13['u']=base64_decode($m11);$m13['p']=base64_decode($m12);$m13['aff'] = $m92;return$m13; }
}

function _strm_ajaxOutputResponse($rs, $err = "", $check_field = 0)
{
	if(empty($err))
	{
		if( !empty($rs) )
		{
			header("Content-type: application/json");
			echo json_encode($rs);
		}
		else
		{
			_strm_ajaxOutputError("No results");
		}
	}	
	else
	{
		if($err == "failed_login")
			_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
		else	
			_strm_ajaxOutputError($err);
	}
}

function _strm_ajaxOutputError($errmsg, $jscmd = "")
{
		$errObj["error"] = $errmsg; 
		if(!empty($jscmd))
			$errObj["run"] = $jscmd;

		header("Content-type: application/json");
		echo json_encode($errObj);
		exit();
}

function _strm_ajax_loadSharing()
{
global $strm_http_plugin_path;
	if($res = strm_checkCredentials())
	{
		$smObj = new Streamotor;	
		$smObj->init($res['u'], $res['p'], $res["aff"]);
		$obj = $smObj->getSMGetSharing();
		
		if($obj == false)
		{
			_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
			return false;
		}
		
		_strm_ajaxOutputResponse($obj, $err);
	}
	else
	{
		_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
	}
	exit();
}

function _strm_ajax_loadManager()
{
global $strm_http_plugin_path;
	if($res = strm_checkCredentials())
	{
		$smObj = new Streamotor;	
		$smObj->init($res['u'], $res['p'], $res["aff"]);
		$redirect_after_save = $strm_http_plugin_path . "includes/blank.html";
		$url = $smObj->getSMManageURL($_GET["type"], $_GET["catid"], $_GET["videoid"], $redirect_after_save);
		
		if($url == false)
		{
			_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
			return false;
		}
		else if($url == -1)
		{
			$err = "There was an error capturing the manager data.";

		}
		
		$urlObj["URL"] =  $url;
		_strm_ajaxOutputResponse($urlObj, $err);

	}
	else
	{
		_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
	}
	exit();
}

function _strm_ajax_getTopCategories()
{
global $_GET;
	
	$res = strm_checkCredentials();

	if($res)
	{
		$smObj = new Streamotor;
		$smObj->init($res['u'], $res['p'], $res["aff"]);
		$cats = $smObj->getSMCategories();

		if($cats == false)
		{
			_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
			return;
		}
		else if($cats == -1)
		{
			$err = "No Catgories";
		}
		
		_strm_ajaxOutputResponse($cats, $err);
	}
	else
	{
		_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
	}
	
	exit();
}

function _strm_ajax_getVideo()
{
global $_GET, $wpdb;


	if($res = strm_checkCredentials())
	{
	
		$smObj = new Streamotor;
		$smObj->init($res['u'], $res['p'], $res["aff"]);
		$rs = $smObj->getSMVideo($_GET["id"]);

		if($rs == false)
		{
			_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
			return;
		}
		else if($rs == -1)
		{
			$err = "There are was an error getting video information.";
		}
		$rs = _strm_ajaxOutputResponse($rs, $err);
	}
	else
	{
		_strm_ajaxOutputError("Please setup your Streamotor credentials","strm_showSetup");
	}
	
	exit();
}

/*************************************************************/
// sp submit page 
/*************************************************************/
function strm_submit_page () 
{
global $post, $strm_http_plugin_path;
    $post_id = $post->ID;
?>
	<div class="postbox">
		<h3 class="hndle"><span>Streamotor Video:</span> 
			<span id='strm_box_searchTitle'>Latest Videos</span>
			<div id='strm_box_loader'><img src='<?php echo $strm_http_plugin_path;?>/images/loader.gif' border='0'></div>
		</h3>
		<div id="strm_box_container">
			<div id="strm_box_search">
				<div id="strm_box_searchContainer">
					<input type="text" id='strm_box_searchTerm'><br>
					<a href="javascript:strm_box_search('',0,10)">Latest Videos</a> |
					<a href="upload.php?page=streamotor">Manage Videos</a>
				</div>
			</div>
			<div id="strm_box_searchResults">
			</div>
		</div>
	</div>
	<script>
		jQuery(document).ready( function () {
			//strm_search("",0,10);
			
			var default_text= "Supply a search term and hit 'Enter'...";
			var my_input_id = "strm_box_searchTerm";

			jQuery("#" + my_input_id).focus( function () {
			   if(jQuery(this).attr("value") == default_text)
			   {
				   jQuery(this).attr("value", "");
			   }
			});
			

			jQuery("#" + my_input_id).blur ( function () {
			   if(jQuery(this).attr("value") == "")
			   {
				   jQuery(this).attr("value", default_text);
			   }
			});

			jQuery("#" + my_input_id).attr("value", default_text);

			jQuery("#" + my_input_id).keypress( function (event) {
				if(event.which == 13)
				{
					event.preventDefault();
					strm_box_search(jQuery(this).val(),0,24);
				}
			});
			
			strm_box_search('',0,24)
		});

		</script>

<?php

}

/*************************************************************/
// wp setup 
/*************************************************************/
//runs when the plugin is activated
function strm_install() 
{
	/*global $wpdb;
	global $strm_db_version;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	*/
}

function strm_pluginMenu() {
  add_media_page( 'Streamotor', 'Streamotor', 'edit_posts', 'streamotor', 'strm_manage' );
}

/*************************************************************/
// front end content 
/*************************************************************/
function strm_extract_video_html($content)
{
	if(strpos($content, "strm_video_embed_"))
	{
		if(preg_match_all("/<img\s+id=\"strm_video_embed_(\w+)\"[^>]+>/", $content, $match))
		{
			$num_matches = sizeof($match[0]);
			for($n=0;$n<$num_matches;$n++)
			{
				$width = 0;
				$html = $match[0][$n];
				$id = $match[1][$n];
				if(preg_match("/width=\"(\d+)\"/", $html, $width_match))
					$width = $width_match[1];

				$buffer[$n]["id"] = $id;
				$buffer[$n]["html"] = $html;
				$buffer[$n]["width"] = $width;
			}	
			return $buffer;
		}	
		else
		{
			return -1;
		}
	}	
	else
	{
			return -1;
	}
}

function strm_filter_rss_content($content)
{
	$videos = strm_extract_video_html($content);

	if($videos != -1)
	{
		$num_videos = sizeof($videos);
		for($v=0;$v<$num_videos;$v++)
		{
			if($videos[$v]["width"] > 0)
				$new_html  = "<a href=\"http://www.streamotor.com/iframe/". $videos[$v]["id"]."/". $videos[$v]["width"]."\" target='_new'>". $videos[$v]["html"]."</a>";
			else	
				$new_html  = "<a href=\"http://www.streamotor.com/iframe/". $videos[$v]["id"]."\" target='_new'>". $videos[$v]["html"]."</a>";
			
			$content = str_replace($videos[$v]["html"], $new_html, $content);
		}
	}
	return $content;
}

function strm_filter_content($content)
{
	$videos = strm_extract_video_html($content);

	if($videos != -1)
	{
		$num_videos = sizeof($videos);
		for($v=0;$v<$num_videos;$v++)
		{
			if($videos[$v]["width"] > 0)
				$js = "<script type=\"text/javascript\" src=\"http://www.streamotor.com/video/". $videos[$v]["id"] ."/". $videos[$v]["width"]."\"></script>";
			else	
				$js = "<script type=\"text/javascript\" src=\"http://www.streamotor.com/video/". $videos[$v]["id"] ."\"></script>";
			
			$content = str_replace($videos[$v]["html"], $js, $content);
		}
	}
	
	return $content;
}

function strm_filter_rss_excerpt($content)
{
	$videos = strm_extract_video_html($content);
	
	if($videos != -1)
	{
		$num_videos = sizeof($videos);
		for($v=0;$v<$num_videos;$v++)
		{
			$new_html  = preg_replace("/Overlay\=1/", "Overlay=1&Thumb=1", $videos[$v]["html"]);
			$new_html  = preg_replace("/width=\"\d+\"/", "width=\"120\"", $new_html);

			if($videos[$v]["width"] > 0)
				$final_html  = "<a href=\"http://www.streamotor.com/iframe/". $videos[$v]["id"]."/". $videos[$v]["width"]."\" target='_new'>". $new_html ."</a>";
			else	
				$final_html  = "<a href=\"http://www.streamotor.com/iframe/". $videos[$v]["id"]."\" target='_new'>". $new_html ."</a>";
			
			$content = str_replace($videos[$v]["html"], $final_html, $content);
		}
	}
	return $content;
}

/*************************************************************/
// hooks
/*************************************************************/
register_activation_hook( __FILE__,'strm_install' );
add_action( 'init', 'strm_wp_init' );
add_action( 'admin_menu', 'strm_pluginMenu' );
add_action( 'admin_head', 'strm_adminHeader' );
add_action( 'submitpost_box', 'strm_submit_page');
add_action( 'submitpage_box', 'strm_submit_page');
add_action( 'wp_ajax_strm_get_video', '_strm_ajax_getVideo' );
add_action( 'wp_ajax_strm_get_top_categories', '_strm_ajax_getTopCategories' );
add_action( 'wp_ajax_strm_save_settings', '_strm_ajax_saveSettings' );
add_action( 'wp_ajax_strm_get_category_info', '_strm_ajax_getCategoryInfo' );
add_action( 'wp_ajax_strm_get_category_pages', '_strm_ajax_getCategoryPages' );
add_action( 'wp_ajax_strm_get_videos', '_strm_ajax_getVideos' );
add_action( 'wp_ajax_strm_load_manager', '_strm_ajax_loadManager' );
add_action( 'wp_ajax_strm_load_sharing', '_strm_ajax_loadSharing' );
add_filter( 'the_excerpt_rss', 'strm_filter_rss_excerpt');
add_filter( 'the_content_rss', 'strm_filter_rss_content');
add_filter( 'the_content', 'strm_filter_content');
//add_filter( 'the_content', 'strm_filter_rss_excerpt');
