<?php
header("Content-type: application/javascript");
$strm_http_plugin_path = str_replace("/js/sm.js.php", "", $_SERVER["SCRIPT_NAME"]);
?>
var strm_current_loaded_manager;
var strm_current_loaded_catid;
var strm_current_loaded_videoid;
var strm_current_manage_win;
var strm_cookies_ok = 0;
var strm_manager_timer = null;
var strm_social_open = 0;
			
function strm_showHideDiv(tagname, showhide)
{
    if (showhide == 1)
    {
		jQuery("#" + tagname).css("display","block");
    }
    else if (showhide == 0)
    {
		jQuery("#" + tagname).css("display","none");
    }
}

function strm_getTopCategories()
{
	var windowrand =  "strm_list_inner";
	jQuery("#strm_list").html("<div id='"+ windowrand+"'><img src='<?php echo $strm_http_plugin_path;?>/images/loader.gif' border='0'></div>");
	strm_showHideDiv('strm_loading_div',1);
	var data = {
		action: 'strm_get_top_categories', //wp_ajax_strm_get_top_categories
		rand: Math.floor(Math.random()*1000)
	};
	
	jQuery.getJSON(ajaxurl, data, function(res) {
		if(res["error"] != undefined && res["error"] != "")
		{
			if(res["run"] != undefined)
			{
				if(res["run"] == "strm_showSetup")
				{
					strm_showSetup();
				}
			}
			else
			{
				jQuery("#" + windowrand).html(res["error"]);
			}
		}
		else
		{
			var html = "";

			for(var i=0;i<res.length;i++)
			{
				html += "<h3 onclick=\"strm_fetchCategoryInfo('" + res[i]["CategoryID"] +"',0);\"><a href=\"#\" id='strmTitle"+ res[i]["CategoryID"] +"'>" + res[i]["Title"] + " ("+ res[i]["RecursiveTotalVideos"]+")</a></h3>";
				html += "<div id='" + res[i]["CategoryID"] + "'></div>";
			}

			jQuery("#" + windowrand).html(html);
		
			jQuery("#" + windowrand).accordion({
				collapsible: true,
				active: false,
				autoHeight: false,
				change: function (event, ui) {
					jQuery(".ui-accordion-header a").css({"visibility":"visible", "height":"15px"});
					jQuery(".ui-state-active a").css({"visibility":"hidden", "height":"3px"});
				}
			});
		}
		strm_showHideDiv('strm_loading_div',0);
	});	
}

function strm_box_search(searchterm,page, perpage, divid)
{
	if(divid == null || divid == undefined || divid == "")
		var divid = "strm_box_searchResults";

	strm_showHideDiv('strm_box_loader', 1);
	if(searchterm == "" || searchterm == "latest")
	{
		searchterm = "";
		jQuery("#strm_box_searchTerm").val("Supply a search term and hit 'Enter'...");
		jQuery("#strm_box_earchTitle").html("Latest Videos");
	}
	else
	{
		var title = "Search Results: ";
		title += jQuery("#strm_box_searchTerm").val();
		if(title.length > 30)
			title = title.substring(0,30) + "..."
		jQuery("#strm_box_searchTitle").html(title);
	}
	
	var data = {
		action: 'strm_get_videos', //wp_ajax_strm_get_videos
		search: searchterm,
		page: page,
		perpage: perpage,	
		rand: Math.floor(Math.random()*1000)
	};
	
	jQuery.getJSON(ajaxurl, data, function(res) {
		if(res["error"] != undefined && res["error"] != "")
		{
			if(searchterm != "")
				jQuery("#strm_box_searchResults").html("No videos matching the search '"+ searchterm +"'.");
			else	
				jQuery("#strm_box_searchResults").html("No results.");
		}
		else
		{
			
			var vhtml = "<ul>";
			var videoSize = res.length;
			var first = true;
			for(var v = 0; v < videoSize; v++) {
				vhtml += '<li id="strm_insert_video_'+ res[v]["VideoID"]+'"><img src="' + res[v].Thumb + '" width="120" border="0"/><div class="strm_video_info" style="display: none;">'+ res[v]["Title"] +'</div></li>';
			}	
			vhtml += "</ul>";

			if(page > 0)
				jQuery("#" + divid).append(vhtml);
			else	
				jQuery("#" + divid).html(vhtml);
			
			var html = "";
			var nextpage = page + 1;
			if(!jQuery("#strm_loadmoreDiv").length)
			{
				html += "<div id='strm_loadmoreDiv'>";
					html += "<div id='strm_loadmoreContent'></div>";
					html += "<div id='strm_loadmoreLink' class='strm_mainButton'>Load More</div>";
				html += "</div>";
				jQuery("#" + divid).append(html);
			}

			//update load more 
			if(res.length == perpage)
			{
				jQuery("#strm_loadmoreLink").css("display","block");
				jQuery("#strm_loadmoreLink").click( function () {
					strm_box_search(searchterm,nextpage,perpage,'strm_loadmoreContent');
				});
			}
			else
			{
				jQuery("#strm_loadmoreLink").css("display","none");
			}
		}

		jQuery("#strm_box_searchResults ul li img").click ( function () {
			var liObj = jQuery(this).parent();

			if(liObj.css("overflow") == "hidden")
			{
				liObj.addClass("strm_box_selected");
				jQuery(this).css({
					"width": "214px"
				});
				liObj.children(".strm_video_info").css({"display":"block"});

				var id = liObj.attr("id").replace("strm_insert_video_","");
				if(!liObj.children(".strm_video_info").find("a").length)
				{
					var str = "";
					str += "<div class='strm_close_video strm_close_video_box' onclick=\"strm_closeVideoBox('"+ id +"');\"></div>";
					str += "<div>";
						str += "<a class='strm_boxButton' href=\"javascript:void(null);\" onclick=\"strm_insertVideo('"+ id +"',640);\">640</a>";
						str += "<a class='strm_boxButton' href=\"javascript:void(null);\" onclick=\"strm_insertVideo('"+ id +"',480);\">480</a>";
						str += "<a class='strm_boxButton' href=\"javascript:void(null);\" onclick=\"strm_insertVideo('"+ id +"',320);\">320</a>";
						str += "<input type='text' id='strm_custom_size' style='width: 30px; float: right; margin: 0; height: 25px;' value='520'><a style='float: right;' class='strm_boxButton' href='javascript:void(null);' onclick=\"strm_insertVideoCustom('"+ id +"');\" alt='Enter a custom size and click this button.' title='Enter a custom size and click this button.'>&laquo;</a>&nbsp;&nbsp;";
					str += "</div><div class='clearfix'></div>";
					liObj.children(".strm_video_info").append(str);

				}
			}
		});
		strm_showHideDiv('strm_box_loader', 0);
	});
}

function strm_insertVideoCustom(id)
{
	var val = jQuery("#strm_custom_size").val();
	if(val != "" && val != undefined && val != null)
	{
		var ival = parseInt(val, 10);
		if(ival < 240)
			alert("You did not provide a valid width for your video.  Please specify a video greater than or equal to 240.");
		else
			strm_insertVideo(id, val);
	}
	else
	{
		alert("You did not provide a valid width for your video");
	}
}

function strm_closeVideoBox(li_id)
{
	var id = "#strm_insert_video_" + li_id;

	jQuery(id).removeClass("strm_box_selected");
	jQuery(id).children("img").css({
		"width": "120px"
	});
	jQuery(id).children(".strm_video_info").css({"display":"none"});
}

function strm_insertVideo(id, size)
{
	var code = "<img id=\"strm_video_embed_"+ id +"\" class=\"strm_video_embed\" width=\""+ size +"\" src=\"http://www.streamotor.com/image/"+ id +"?Overlay=1\" border=\"0\">";
	parent.tinyMCE.activeEditor.execCommand("mceInsertContent",false, code);
}

function strm_search(searchterm,page, perpage, divid)
{
	if(divid == null || divid == undefined || divid == "")
		divid = "strm_searchResults";

	strm_showHideDiv('strm_loading_div',1);
	if(searchterm == "" || searchterm == "latest")
	{
		searchterm = "";
		jQuery("#strm_searchTerm").val("Supply a search term and hit 'Enter'...");
		jQuery("#strm_searchTitle").html("Latest Videos");
	}
	else if(searchterm == "pending")
	{
		jQuery("#strm_searchTerm").val("Supply a search term and hit 'Enter'...");
		jQuery("#strm_searchTitle").html("Pending Videos");
	}
	else
	{
		var title = "Search Results: ";
		title += jQuery("#strm_searchTerm").val();
		if(title.length > 30)
			title = title.substring(0,30) + "..."
		jQuery("#strm_searchTitle").html(title);
	}
	
	var data = {
		action: 'strm_get_videos', //wp_ajax_strm_get_videos
		search: searchterm,
		page: page,
		perpage: perpage,	
		rand: Math.floor(Math.random()*1000)
	};
	
	jQuery.getJSON(ajaxurl, data, function(res) {
		if(res["error"] != undefined && res["error"] != "")
		{
			if(res["run"] != undefined)
			{
				if(res["run"] == "strm_showSetup")
				{
					strm_showSetup();
				}
			}
			else
			{
				if(searchterm == "pending")
					jQuery("#strm_searchResults").html("No pending videos.");
				else if(searchterm != "")
					jQuery("#strm_searchResults").html("No videos matching the search '"+ searchterm +"'.");
				else
					jQuery("#strm_searchResults").html(res["error"]);
			}
		}
		else
		{
			if(page == 0)
				strm_renderSearchResults(res, divid, 0);
			else	
				strm_renderSearchResults(res, divid, 1);
			
			var html = "";
			var nextpage = page + 1;
			if(!jQuery("#strm_loadmoreDiv").length)
			{
				html += "<div id='strm_loadmoreDiv'>";
					html += "<div id='strm_loadmoreContent'></div>";
					html += "<div id='strm_loadmoreLink' class='strm_mainButton'>Load More</div>";
				html += "</div>";
				jQuery("#" + divid).append(html);
			}

			//update load more 
			if(res.length == perpage)
			{
				jQuery("#strm_loadmoreLink").css("display","block");
				jQuery("#strm_loadmoreLink").click( function () {
					strm_search(searchterm,nextpage,perpage,'strm_loadmoreContent');
				});
			}
			else
			{
				jQuery("#strm_loadmoreLink").css("display","none");
			}
		}
		strm_showHideDiv('strm_loading_div',0);
	});
	
}

function strm_renderSearchResults(videos, divid, append_overwrite)
{
	var vhtml = "";
	var videoSize = videos.length;
	var first = true;
	for(var v = 0; v < videoSize; v++) {
		vhtml += strm_displayVideo('', videos[v], first);
		first = false;
	}	

	if(append_overwrite == 1)
		jQuery("#" + divid).append(vhtml);
	else	
		jQuery("#" + divid).html(vhtml);
}

function strm_detectManagerFinished()
{
	var iframe = document.getElementById('strm_video_iframe');
	var windowlocation = "";

	if(strm_cookies_ok == 1)
	{
		try { //try to read the iframe location
			//if you can read it and the location is complete then close then submit the data
			windowlocation = iframe.contentWindow.location.href;
			if(windowlocation == "about:blank")	
				windowlocation = "";
			else if(!windowlocation.match(/blank.html/))
				windowlocation = "";

		}
		catch (err)
		{
			windowlocation = "";
		}
	}
	else
	{
		try {
			windowlocation = strm_current_manage_win.location.href;
			if(windowlocation != undefined && windowlocation != null)
			{
				var wstr = new String(windowlocation);
				if(wstr.match(/blank.html/))
					windowlocation = wstr;
				else	
					windowlocation = "";
			}
			else
				windowlocation = "";
		}
		catch (err)
		{
			windowlocation = "";
		}	

			
	}

	if(windowlocation != "" && windowlocation != undefined && windowlocation != null)
	{
		if(strm_cookies_ok == 1)
			var url = new String(iframe.contentWindow.location.href);
		else		
			var url = windowlocation;

		if(url != undefined && url.match(/blank.html/))
		{
			if(strm_current_loaded_manager == "editCategory" || strm_current_loaded_manager == "addCategory")
			{
				if(strm_current_loaded_catid == "")
					strm_getTopCategories();
				else	
					strm_fetchCategoryInfo(strm_current_loaded_catid,0,1);
			}
			else if(strm_current_loaded_manager == "deleteCategory")
			{
				strm_getTopCategories();
			}
			else if(strm_current_loaded_manager == "deleteVideo")
			{
				if(jQuery("#video_" + strm_current_loaded_videoid).length)
					strm_showHideDiv("video_" + strm_current_loaded_videoid,0);
				
				if(jQuery("#" + strm_current_loaded_catid).length)
					strm_fetchCategoryInfo(strm_current_loaded_catid,0,1);
				
			}
			else if(strm_current_loaded_manager == "editVideo")
			{
				if(jQuery("#" + strm_current_loaded_catid).length)
					strm_fetchCategoryInfo(strm_current_loaded_catid,0,1);
				
				strm_showHideDiv('strm_loading_div',1);
				var data = {
					action: 'strm_get_video', //wp_ajax_strm_get_video
					id: strm_current_loaded_videoid,
					rand: Math.floor(Math.random()*1000)
				};
				
				jQuery.getJSON(ajaxurl, data, function(res) {
					if(res["error"] != undefined && res["error"] != "")
					{
						alert(res["error"]);
					}
					else
					{
						strm_showHideDiv('strm_loading_div',0);
						//reload on search results
						strm_updateVideo('', res, "video_" + res.VideoID)
					}
				});

			}
			else if(strm_current_loaded_manager == "uploadVideo")
			{
				strm_search('pending',0,10);
			}
			strm_closeVideo();
		}
	}
	else
	{

		if(jQuery('#strm_video_container').css("display") == "block")
		{
			setTimeout(strm_detectManagerFinished, 500);
		}
	}
}



function strm_loadSocial()
{
	strm_social_open = 1;
	var data = {
		action: 'strm_load_sharing', //wp_ajax_strm_load_sharing
		rand: Math.floor(Math.random()*1000)
	};

	strm_showHideDiv('strm_social',1);

	jQuery.getJSON(ajaxurl, data, function(res) {
		if(res["error"] != undefined && res["error"] != "")
		{
			if(res["run"] != undefined)
			{
				if(res["run"] == "strm_showSetup")
				{
					strm_showSetup();
				}
			}
			else
				alert(res["run"]);

		}
		else
		{
			var id = "";
			if(res != -1)
			{
				for(var j=0;j<res.length;j++)
				{
					id = "#strm_social_" + res[j];	
					if(jQuery(id).length > 0)
					{
						jQuery(id).addClass("strm_social_active");
						jQuery(id + " img").attr("src","<?php echo $strm_http_plugin_path;?>/images/" + res[j] + "_icon.png");
						jQuery(id + " span").html("Active");
					}
				}

			}
		}

	});	
}

function strm_closeSocial()
{
	if(strm_social_open == 0)
	{
		strm_showHideDiv('strm_social',0);
	}
}

jQuery(document).ready( function () {
	jQuery("#strm_social").mouseenter( function () {
		strm_social_open = 1;
	});
	
	jQuery("#strm_social").mouseleave( function () {
		strm_social_open = 0;
		setTimeout(strm_closeSocial, 1000); //close in 2 seconds
	});
});

function strm_loadManager(type, catid, videoid)
{
	if(type == "deleteVideo")
	{
		if(!confirm("Are you sure you want to remove this video?"))
		{
			return;	
		}
	}
	else if(type == "deleteCategory")
	{
		if(!confirm("Are you sure you want to remove this category? NOTE:  they will be added to the 'Uncategorized' category."))
		{
			return;	
		}
	}
	strm_current_loaded_manager =  type;
	strm_current_loaded_catid = catid;
	strm_current_loaded_videoid = videoid

	if(catid == undefined || catid == null || catid == "")
		catid = "";
	if(videoid == undefined || videoid == null || videoid == "")
		videoid = "";

	var data = {
		action: 'strm_load_manager', //wp_ajax_strm_load_manager
		type: type,
		catid: catid,
		videoid: videoid,
		rand: Math.floor(Math.random()*1000)
	};

	jQuery.getJSON(ajaxurl, data, function(res) {
		if(res["error"] != undefined && res["error"] != "")
		{
			if(res["run"] != undefined)
			{
				if(res["run"] == "strm_showSetup")
				{
					strm_showSetup();
				}
			}
			else
				alert(res["run"]);

		}
		else
		{
			var url = "<?php echo $strm_http_plugin_path?>/includes/loader.php?URL=" + strm_base64_encode(res["URL"]);
			var h = 500;
			var w  = 600;
			if(type == "reports")
				w = 700;
			else if(type == "uploadVideo")
				w = 440;
			else if(type == "deleteCategory" || type == "deleteVideo")
			{
				w = 200;
				h = 200;
			}	

			//only show the interface if the user interacts
			if(strm_cookies_ok == 1)
			{
				//open url in foreground for editing
				if(type == "deleteCategory" || type == "deleteVideo")
					strm_showHideDiv('strm_frame_loader', 1);
				else	
					strm_showHideDiv('strm_frame_loader', 0);
				jQuery('#strm_video_container iframe').attr("src",url); 
				strm_showHideDiv('strm_video_cover',1);
				jQuery('#strm_video_container iframe').css({
					"background-color": "#ffffff",
					"height": h + "px", 
					"width": w + "px", 
					"display": "block"
				});
				w+= 40;
				h+= 40;
				var ml = Math.floor(w/2);
				jQuery('#strm_video_container').css({
					"height": h + "px", 
					"margin-left": "-" + ml + "px",
					"width": w + "px", 
					"display": "block"
				});
				jQuery('#strm_video_content').css({
					"background-color": "#ffffff",
					"height": h + "px", 
					"width": w + "px"
				});
			}	
			else
			{
				//open window as alternate for browsers that don't have 3rd party cookies enabled
				strm_current_manage_win = window.open(url,'wp_strm_manager','width=800,height=600,toolbar=0,resizable=0,titlebar=0,status=0,scrollbars=1,location=0,fullscreen=0,directories=0,channelmode=0');

				if (strm_current_manage_win == null || typeof(strm_current_manage_win )=='undefined') 
				{
					alert("You need to enabled pop up windows OR enabled 3rd party cookies to connect to Streamotor.com via your browser.");	
				}
				else
				{
					if(jQuery("#strm_mc_popup").length)
					{
						strm_showHideDiv('strm_mc_popup',0);
					}
				}
			}

			setTimeout(strm_detectManagerFinished, 1000);
		}
	});	
}

function strm_fetchCategoryInfo(id, page, force_reload) 
{
	if(force_reload == undefined || force_reload == null || force_reload == "")
		force_reload = 0;
	if(force_reload == 1)
	{
		var loading_html = jQuery("#strm_loading_div").html(); 
		jQuery("#" + id + " .Pages #smPage" + page).html(loading_html);
		jQuery("#" + id + " .Categories").html("");
		jQuery("#" + id + " .strm_Pageinate").html("");
	}

	if(!jQuery("#" + id + " .smBtns").length) 
	{
		var html = "";
		html += '<div class="smBtns clearfix">';
		if(id != "uncategorized")
		{
				html += "<span id='strm_2nd_title_"+ id +"'>" + jQuery("#strmTitle" + id).html() + " </span> &nbsp;"; 
				jQuery("#strmTitle" + id).css({"visibility":"hidden", "height":"2px"});
				html += '<a href="javascript:void(null);" onclick="strm_loadManager(\'addCategory\',\''+ id +'\');">Add Sub-Category</a> | ';
				html += '<a href="javascript:void(null);" onclick="strm_loadManager(\'editCategory\',\''+ id +'\');">Edit</a> | ';
				html += '<a href="javascript:void(null);" onclick="strm_loadManager(\'deleteCategory\',\''+ id +'\');">Delete</a> ';
		}	
		else
		{
				html += jQuery("#strmTitle" + id).html() + " &nbsp;"; 
				jQuery("#strmTitle" + id).css({"visibility":"hidden", "height":"2px"});
		}
		html += '<a class="strm_link_button" style="float: right;" href="javascript:void(null);" onclick="strm_fetchCategoryInfo(\''+ id +'\',0,1);"><img src="<?php echo $strm_http_plugin_path;?>/images/arrow_rotate_clockwise.png"></a><div class="clearfix"></div>';
		html += '</div>';
		html += '<div class="Categories"></div>';
		html += '<div class="strm_Pageinate"></div>';
		html += '<div class="Pages"></div>';
		jQuery("#" + id).append(html);
	}
	
	if(force_reload == 1 || !jQuery("#" + id + " .Pages #smPage" + page).length) 
	{
		if(force_reload != 1)
			strm_showHideDiv('strm_loading_div',1);
		var datac = {
			action: 'strm_get_category_info', //wp_ajax_strm_get_category_info
			CategoryID: id,
			Page: page,
			rand: Math.floor(Math.random()*1000)
		}

		jQuery.getJSON(ajaxurl, datac, function(response) {
			var categories = response.Subcategories;
			var catid = response.CategoryID;
			var categorySize = categories.length;
			var videos = response.Videos;
			var videoSize = videos.length;
			var page = response.Page;
			var title = response.Title;
			var perpage = response.ItemsPerPage;
			var total_videos = response.TotalVideos; 
			var total_pages = response.TotalPages; 
			var rec_total_videos = response.RecursizeTotalVideos;
			var cur_page;

		
			jQuery("#strmTitle" + catid).html(title + " (" + total_videos + ")");

			if(jQuery("#strm_2nd_title_" + catid).length) 
			{
				jQuery("#strm_2nd_title_" + catid).html(title + " (" + total_videos + ")");
			}

			if(total_pages > 1)
			{
				var phtml = ""
				for(i=0;i<total_pages;i++)
				{

					cur_page = i + 1;	
					if(i == page)
						phtml += "<a class='page-highlight' href='javascript:void(null);' onClick=\"strm_fetchCategoryInfo('" + id + "', "+ i +");\">" + cur_page + "</a>";
					else	
						phtml += "<a class='' href='javascript:void(null);' onClick=\"strm_fetchCategoryInfo('" + id + "', "+ i +");\">" + cur_page + "</a>";
				}
				phtml += "<div class='clearfix'></div>";
				jQuery("#" + id + " .strm_Pageinate").html(phtml);
			}

			var chtml = "";
			if(categorySize > 0) {
				chtml += '<div id="subCats' + id + '"';
				if(videoSize > 0)
					chtml += ' style="padding-bottom: 10px;"';
				chtml += '>';
				for(var c = 0; c < categorySize; c++)
					chtml += '<h3 onclick="strm_fetchCategoryInfo(\'' + categories[c].CategoryID +'\', 0);"><a href="#" id="strmTitle'+ categories[c].CategoryID+'">' + categories[c].Title + ' (' + categories[c].TotalVideos + ')</a></h3><div id="' + categories[c].CategoryID + '"></div>';
				chtml += '</div>';
			}
			jQuery("#" + id + " .Categories").html(chtml);

			var vhtml = '<div id="smPage' + page +'">';
			var first = true;
			for(var v = 0; v < videoSize; v++) {
				vhtml += strm_displayVideo(catid,videos[v], first);
				first = false;
			}	
			
			if(categorySize == 0 && videoSize == 0) {
				vhtml += 'This category is currently empty.';
			}
			vhtml += '</div>';

			jQuery("#" + id + " .Pages").html(vhtml);

			if(categorySize > 0) {
				jQuery("#subCats" + id).accordion({ 
					collapsible: true, 
					active: false, 
					autoHeight: false, 
					change: function (event, ui) {
						jQuery(".ui-accordion-header a").css({"visibility":"visible", "height":"15px"});
						jQuery(".ui-state-active a").css({"visibility":"hidden", "height":"3px"});
					}
				});
			}	

			strm_showHideDiv('strm_loading_div',0);
		});
	}

}

function strm_updateVideo(catid, video, divid)
{
	if(jQuery("#" + divid).length)
	{
		var html = "";
		html += '<table cellpadding="0" cellspacing="0"><tr><td><div class="image" onclick=""><a href="javascript:strm_showVideo(\'' + video.VideoID + '\', '+ video.Width+', '+ video.Height+')"><img src="' + video.Thumb + '" border="0"/></a><div class="overlay">' + video.Length + '</div></div></td><td><h2><a href="javascript:strm_showVideo(\'' + video.VideoID + '\', '+ video.Width+', '+ video.Height+')">' + video.Title + '</a></h2>';
		if(video.Subtitle != "")
			html += video.Subtitle + '<br/>';
		html += 'by ' + video.Author + '<br>' + video.Added + ' | ' + video.Views + " Views<br>";
		html += "<a href='javascript:void(null);' onClick=\"strm_loadManager('editVideo','"+ catid +"', '"+ video.VideoID +"');\">Edit</a> | ";
		html += "<a href='javascript:void(null);' onClick=\"strm_loadManager('deleteVideo','"+ catid +"', '"+ video.VideoID +"');\">Delete</a>";
		html += '</td></tr></table></div>';

		jQuery("#" + divid).html(html);
	}
}

function strm_box_displayVideo(catid, video) 
{
	var html = '';
	
	return html
}

function strm_displayVideo(catid, video, first) 
{
	var html = '';
	if(first)
		html += '<div class="smEntry first" id="video'+ catid+'_'+ video.VideoID +'">';
	else	
		html += '<div class="smEntry" id="video'+ catid+'_'+ video.VideoID +'">';
	
	html += '<table cellpadding="0" cellspacing="0"><tr><td><div class="image" onclick=""><a href="javascript:strm_showVideo(\'' + video.VideoID + '\', '+ video.Width+', '+ video.Height+')"><img src="' + video.Thumb + '" border="0"/></a><div class="overlay">' + video.Length + '</div></div></td><td><h2><a href="javascript:strm_showVideo(\'' + video.VideoID + '\', '+ video.Width+', '+ video.Height+')">' + video.Title + '</a></h2>';
	if(video.Subtitle != "")
		html += video.Subtitle + '<br/>';
	html += 'by ' + video.Author + '<br>' + video.Added + ' | ' + video.Views + " Views<br>";
	html += "<a href='javascript:void(null);' onClick=\"strm_loadManager('editVideo','"+ catid +"', '"+ video.VideoID +"');\">Edit</a> | ";
	html += "<a href='javascript:void(null);' onClick=\"strm_loadManager('deleteVideo','"+ catid +"', '"+ video.VideoID +"');\">Delete</a>";
	html += '</td></tr></table></div>';
	return html
}

function strm_buildPopupWindow()
{
	var dyn_cover = document.createElement("div");
	dyn_cover.setAttribute("id","strm_video_cover");
	dyn_cover.setAttribute("style","display: none");
	document.body.appendChild(dyn_cover);

	var dyn_cont = document.createElement("div");
	dyn_cont.setAttribute("id","strm_video_container");
	dyn_cont.setAttribute("style","display: none");

	var dyn_content = document.createElement("div");
	dyn_content.setAttribute("id","strm_video_content");

	var html = "";
	html += "<div class='strm_close_video' onClick='strm_closeVideo();'></div><div id='strm_frame_loader' style='display:none;'><img src='<?php echo $strm_http_plugin_path ?>/images/loader.gif'></div>";
	html += "<iframe id='strm_video_iframe' scroll='auto' src=''></iframe>";
	dyn_content.innerHTML = html;

	dyn_cont.appendChild(dyn_content);

	document.body.appendChild(dyn_cont);
}

function strm_showVideo(id, w, h)
{
	strm_current_loaded_videoid = id;
	strm_showHideDiv('strm_video_cover',1);
	jQuery('#strm_video_container iframe').css({
		"background-color": "#000000",
		"height": h + "px", 
		"width": w + "px", 
		"display": "block"
	});
	jQuery('#strm_video_container iframe').attr("src","http://www.streamotor.com/iframe/"+ id +"/" + w); 
	w+= 40;
	h+= 40;
	ml = Math.floor(w/2);
	jQuery('#strm_video_container').css({
		"height": h + "px", 
		"margin-left": "-" + ml + "px",
		"width": w + "px", 
		"display": "block"
	});
	jQuery('#strm_video_content').css({
		"background-color": "#000000",
		"height": h + "px", 
		"width": w + "px"
	});
}

function strm_closeVideo()
{
	strm_showHideDiv('strm_video_cover',0);
	strm_showHideDiv('strm_video_container',0);
	jQuery('#strm_video_container iframe').attr("src",null); 

	if(strm_cookies_ok  == 0)
	{
		if(strm_current_manage_win != undefined)
		{
			strm_current_manage_win.close();
		}
	}
}

function strm_saveSettings(from)
{
	var data = jQuery("#strm_setupForm").serialize();
	strm_showHideDiv('strm_processing_div',1);

	jQuery.post(ajaxurl, data , function(res)  {
		
		strm_showHideDiv('strm_processing_div',0);
		if(res["error"] != undefined && res["error"] != "")
		{
			alert(res["error"]);
		}
		else
		{
			jQuery("#strm_credentials span").html(document.getElementById('strm_data_u').value);
			strm_showHideDiv('strm_setup',0);	
			strm_showHideDiv('strm_video_cover',0);
			strm_getTopCategories();
			strm_search("",0,10);
			document.getElementById('strm_data_u').value = "";
			document.getElementById('strm_data_p').value = "";
		}
	});

	if(from == 0)
		return false;
}

function strm_hideSetup()
{
	strm_showHideDiv('strm_video_cover',0);
	strm_showHideDiv('strm_setup',0);
}

function strm_showSetup()
{
	strm_showHideDiv('strm_video_cover',1);
	strm_showHideDiv('strm_setup',1);
	jQuery('#strm_signup_iframe').attr("src","http://www.streamotor.com/wp/signup_billboard.php"); 
	jQuery('#strm_pricing_iframe').attr("src","http://www.streamotor.com/wp/pricing_billboard.php"); 
}

function strm_postManageListing()
{
	strm_showHideDiv("strm_manageVideo", 0);
	strm_showHideDiv('strm_processing_div',1);
	var frm = jQuery("#strm_manageVideoForm")[0];

	var data = jQuery("#strm_manageVideoForm").serialize();

	jQuery.post(ajaxurl, data , function(res)  {
		if(res["error"] != undefined && res["error"] != "")
		{
			alert(res["error"]);
		}
		else
		{
			strm_showHideDiv('strm_processing_div',0);
			frm.reset();
			strm_getTopCategories();
		}
	});
}

function strm_showEditListing(id)
{
	strm_showHideDiv('strm_loading_div',1);
	var data = {
		action: 'strm_get_video', //wp_ajax_strm_get_video
		id: id,
		rand: Math.floor(Math.random()*1000)
	};

	jQuery.getJSON(ajaxurl, data, function(res) {
		if(res["error"] != undefined && res["error"] != "")
		{
			alert(res["error"]);
		}
		else
		{
			strm_showHideDiv('strm_loading_div',0);
			var frm = jQuery("#strm_manageVideoForm")[0];
			frm.cmd.value = "strm_edit_video";
			frm.id.value = "1";
		}
	});

}

function strm_showUploadVideo()
{
	var frm = jQuery("#strm_manageVideoForm")[0];
	frm.cmd.value = "strm_add_video";
	jQuery("#manageButton").attr("value", "Create");
	strm_showHideDiv("strm_manageVideo", 1);
}

function strm_hideManageForm()
{
	jQuery("#strm_manageVideoForm")[0].reset();
	strm_showHideDiv("strm_manageVideo", 0);
}


function strm_runApplicationTest()
{
	var windowlocation = "";
	var iframe = document.getElementById('strm_application_test_iframe');

	try { 

		windowlocation = iframe.contentWindow.location.href;
		if(windowlocation != undefined && windowlocation != null && windowlocation != "about:blank")
		{
			var wstr = new String(windowlocation);
			if(wstr.match(/blank.html/))
				windowlocation = wstr;
			else	
				windowlocation = "";

		}
		else
			windowlocation = "";
		
	}
	catch (err)
	{
		windowlocation = "";
	}

	if(windowlocation != "")
	{
		var url = new String(iframe.contentWindow.location.href);
		if(url != undefined && url.match(/status\=1/))
		{
			strm_cookies_ok = 1;
		}
		else
		{
			strm_cookies_ok = 0;
			jQuery("#strm_messageCenter ul").append("<li>For a better user experience, please enable 3rd party cookies.</li>");
			strm_showHideDiv("strm_messageCenter",1);

			var windowName = 'userConsole'; 
			var popUp = window.open('<?php echo $strm_http_plugin_path;?>/includes/blank.html','blanktest','width=100,height=100,left=0,top=0,scrollbars=0,resizable=0');
			if (popUp == null || typeof(popUp)=='undefined') {   
				jQuery("#strm_messageCenter ul").append("<li id='strm_mc_popup'>Please allow pop up windows for this site.</li>");
			} 
			else {   
				popUp.close();
			}
		}
	}
	else
	{
		setTimeout(strm_runApplicationTest, 1000);
	}
}

function strm_base64_encode(input)
{
    var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    var output = "";
    var chr1, chr2, chr3;
    var enc1, enc2, enc3, enc4;
    var i = 0;

    do {
    chr1 = input.charCodeAt(i++);
    chr2 = input.charCodeAt(i++);
    chr3 = input.charCodeAt(i++);

    enc1 = chr1 >> 2;
    enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
    enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
    enc4 = chr3 & 63;

    if (isNaN(chr2)) {
    enc3 = enc4 = 64;
    } else if (isNaN(chr3)) {
    enc4 = 64;
    }

    output = output + keyStr.charAt(enc1) + keyStr.charAt(enc2) +
    keyStr.charAt(enc3) + keyStr.charAt(enc4);
    } while (i < input.length);

    return output;
}
