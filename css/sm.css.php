<?php
header("Content-type: text/css");

$color_1 = "#59BAE3"; 
$color_2 = "#0E7ABF";
$hd_color_1 = "#999999"; 
$hd_color_2 = "#cccccc";
?>

#strm td, div, span, a {
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif !important;
	font-size: 12px;
	color: #666;
	line-height: 16px;
}	
#strm h1, h2, h3, h4 {
	color: #E99447 !important;
	font-weight: bold;
}
#strm_setup {
	z-index: 999999;
	width: 964px;
	margin-left: -482px;
	height: 500px;
	padding: 10px;
}
#strm_signup_left {
	width: 400px;
	float: left;
}

#strm_pricing_iframe {
	width: 400px;
	height: 273px;
	border: none;
}
#strm_signup_right {
	width: 550px;
	float: right;
}

a.strm_signup:link span, a.strm_signup:visited span {
    color: #ffffff;
	text-decoration: none;
    display: block;
    font: bold 22px/22px "Helvetica Neue",Helvetica,Arial,san-serif;
    margin: 0 auto;
    padding-top: 12px;
    position: relative;
    text-align: center;
    text-shadow: 0 1px 0 #70C3DE;
}

a.strm_signup:link, a.strm_signup:visited {
	background: url("../images/sample-btn-bg.png") no-repeat scroll 0 0 transparent;
	text-decoration: none !important;
	display: block;
	height: 49px;
	margin: 0 auto;
	padding: 0;
	position: relative;
	text-decoration: none;
	width: 224px;
}

#strm_setup h2 {
	color: #E99447;
}

#strm_signup_iframe {
	width: 534px;
	height: 440px;
	border: none;
	background-color: white;
}
#strm {	
	width: 1032px;
	position: relative;
	background: #04001D url(../images/ban-bg3.gif) 0 0 repeat-x;
	padding: 10px;
	margin: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
}

#strm h1 {
	font-size: 20px;
}
#strm h2 {
	font-size: 16px !important;
}
#strm h1, h3 {
	margin: 3px 0; 
	color: #ffffff !important;
}
#strm h3 a {
	font-size: 14px; 
	text-decoration: none;
}

#strm a {
	text-decoration: underline;
}

#strm a:hover {
	text-decoration: none;
}

#strm #strm_list_header {
	margin-bottom: 2px;
}
#strm #strm_list_search {
}

#strm #strm_searchTerm {
	width: 357px;
	margin-top: 3px;
}

#strm_box_container #strm_loadmoreLink {
	width: 212px;
	margin-top: 8px;
}

#strm_box_loader {
	position: absolute;
	right: 15px;
	top: 3px;
}

#strm_box_searchContainer {
	padding: 10px;
}
#strm_box_searchTerm {
	width: 237px;
}
#strm_box_searchResults {
	padding: 0 10px;
}

#strm_box_searchResults ul {
	list-style-type: none;
	margin: 0;
	padding: 0;
}

#strm_box_searchResults ul li {
	float: left;
	width: 114px; 
	height: 70px; 
	overflow:hidden; 
	border: 1px #cccccc solid; 
	margin: 2px;
	cursor: pointer;
}

.strm_box_selected {
	background-color: #CCCCCC;
    clear: both;
    height: 220px !important;
    overflow: visible !important;
    padding: 10px;
    width: 214px !important;
	position: relative;
}

#strm_social {
	color: white;
	position: absolute;
	background-color: <?php echo $color_2?>;
	background: -moz-linear-gradient(100% 50% 90deg, <?php echo $color_2; ?> , <?php echo $color_1; ?> ) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $color_1; ?>), to(<?php echo $color_2; ?> ));
	background: -webkit-linear-gradient(<?php echo $color_1; ?>, <?php echo $color_2; ?>);
	z-index: 9999;
	width: 200px;
	left: 458px;
	top: 123px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	-moz-box-shadow: 0px 3px 35px #666;
	-webkit-box-shadow: 0px 3px 35px #666;
	box-shadow: 0px 3px 35px #666;
	padding: 10px;
}


#strm_social ul {
	list-style-type: none;
	background-color: white; 
	margin: 0;
	padding: 5px;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	
}

#strm_social ul li {
	background-color: <?php echo $hd_color_2?>;
	background: -moz-linear-gradient(100% 50% 90deg, <?php echo $hd_color_2; ?> , <?php echo $hd_color_1; ?> ) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $hd_color_1; ?>), to(<?php echo $hd_color_2; ?> ));
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	padding: 5px;
}


#strm_social ul li.strm_social_active {
	color: white;
	background-color: <?php echo $color_2?>;
	background: -moz-linear-gradient(100% 50% 90deg, <?php echo $color_2; ?> , <?php echo $color_1; ?> ) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $color_1; ?>), to(<?php echo $color_2; ?> ));
	background: -webkit-linear-gradient(<?php echo $color_1; ?>, <?php echo $color_2; ?>);
}

#strm_social ul li.strm_social_active span {
	color: white !important;
}

#strm_social a {
	color: white !important;
}

#strm_social ul li span {
	color: black;
	margin-left: 5px;
}

#strm_social ul li img {
	background-color: white; 
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	padding: 2px;
}


#strm_box_container {
	height: 300px;	
	overflow: auto;
}

#strm #strm_searchContainer {
	color: white;
	position: absolute;
	right: 11px;
	top: 77px;
	width: 359px;
	text-align: right;
}

#strm #strm_searchContainer a {
	color: white;
}
#strm .smBtns {
	font-size: 14px;
	padding: 6px 6px 4px;
	margin-bottom: 4px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	background-color: <?php echo $color_2?>;
	background: -moz-linear-gradient(100% 50% 90deg, <?php echo $color_2; ?> , <?php echo $color_1; ?> ) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $color_1; ?>), to(<?php echo $color_2; ?> ));
	background: -webkit-linear-gradient(<?php echo $color_1; ?>, <?php echo $color_2; ?>);
	color: white;
}
#strm .smBtns span {
    color: white;
    display: block;
    float: left;
    font-size: 18px;
    margin: 0 10px 0 2px;
}
#strm .smBtns a {
	color: white;
}

#strm .smBtns a:hover {
	color: white;
}

#strm_frame_loader {
	position: absolute;
	left: 38px;
	top: 38px; 
}

#strm_container {
	background-color: <?php echo $color_2?>;
	background: -moz-linear-gradient(100% 10% 90deg, <?php echo $color_2; ?> , <?php echo $color_1; ?> ) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $color_1; ?>), to(<?php echo $color_2; ?> ));
	background: -webkit-linear-gradient(<?php echo $color_1; ?>, <?php echo $color_2; ?>);
	position: relative;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	padding: 20px;
	width: 990px;
	border: 1px <?php echo $color_1; ?> solid;
}

#strm_search {
	margin-left: 10px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	height: 500px;
	width: 300px;
	padding: 20px;
	overflow: auto;
	background-color: #ffffff;
	float: left;
}

#strm_search #strm_searchTerm {
	width: 285px;
}

#strm_list {
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	height: 500px;
	width: 600px;
	padding: 20px;
	overflow: auto;
	background-color: #ffffff;
	float: left;
}

.strm_window {
	position: absolute;
	top: 100px;
	left: 50%;
	margin-left: -208px;
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
	-moz-box-shadow: 0px 3px 35px #666;
	-webkit-box-shadow: 0px 3px 35px #666;
	box-shadow: 0px 3px 35px #666;
	background-color: #eeeeee;
	border: 1px #cccccc solid;
	padding: 10px;
	width: 415px;
}

#strm_manageListing {
	width: 850px;
	margin-left: -425px;
	display: none;
}

#strm_manageListing .strm_form_cont{
	width: 425px;
	float: left;
}

.strm_form_field {
	margin-bottom: 5px;
	width: 400px;
}

.strm_form_field .button {
	width: 100px;
}

.strm_form_field_large {
	width: 580px !important;
}

.strm_form_field input {
	width: 392px;
	border: 1px #cccccc solid;
}
.strm_form_field select {
	width: 400px;
	border: 1px #cccccc solid;
}
.strm_form_field textarea {
	width: 400px;
	border: 1px #cccccc solid;
}

#strm_list table th {
	margin: 5px;
}

#strm_list table th {
	text-align: left;
	padding: 5px;
	font-size: 14px;
	color: white;
	border: 2px #cccccc double;
	background-color: <?php echo $hd_color_2?>;
	background: -moz-linear-gradient(100% 100% 90deg, <?php echo $hd_color_2; ?> , <?php echo $hd_color_1; ?> ) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $hd_color_1; ?>), to(<?php echo $hd_color_2; ?> ));
	background: -webkit-linear-gradient(<?php echo $hd_color_1; ?>, <?php echo $hd_color_2; ?>);
}

.content {
}

.contentgrey {
	background-color: #efefef;
}

#strm_loading_div {
	left: 50%;
	top: 200px;
	margin-left: -150px;
	width: 300px;
	position: absolute;
	font-size: 14px;
	font-weight: bold;
	z-index: 9999999;
}

#strm_processing_div {
	left: 50%;
	top: 200px;
	margin-left: -150px;
	width: 300px;
	position: absolute;
	font-size: 14px;
	font-weight: bold;
	z-index: 1000000;
}

div.smEntry { border-top: 1px solid #eee; padding-top: 5px; margin-top: 5px; }
div.smEntry.first { padding-top: 0; margin-top: 0; border-top: none; }
div.smEntry h2 { padding-bottom: 5px; margin: 0; }
div.smEntry h2 a { color: #666 !important; font-size: 16px; text-decoration: none !important; }
div.smEntry td { text-align: left; vertical-align: top; padding: 2px; }
div.smEntry .image { position: relative; width: 112px; cursor: pointer; margin-right: 10px; }
div.smEntry .image img { width: 100px; padding: 5px; border: 1px solid #eee; }
div.smEntry .image .overlay { font-size: 10px; position: absolute; bottom: 10px; right: 6px; color: #fff; background-color: #000; padding: 2px 5px; text-align: center; opacity: 0.75; filter: alpha(opacity=75); }


#strm_video_cover {
	background-color: black;
	height: 100%;
	left: 0;
	position: fixed;
	top: 0;
	width: 100%;
	z-index: 9999;
	opacity: 0.5;
    filter: alpha(opacity=50);
}
#strm_video_container {
	background: transparent;
    height: 520px;
    left: 50%;
	margin-top: 60px;
    margin-left: -320px;
    position: fixed;
    top: 20px;
    width: 680px;
    z-index: 99999;
}
#strm_video_content {
    background-color: #000000;
	-moz-border-radius: 20px;
	-webkit-border-radius: 20px;
	border-radius: 20px;
    -moz-box-shadow: 0px 3px 25px #666;
    -webkit-box-shadow: 0px 3px 25px #666;
    box-shadow: 0px 3px 25px #666;
    padding: 10px;
}
#strm_video_content iframe {
    background-color: black;
    border: 0 none;
    height:520px;
    margin: 20px;
    width: 640px;
}

.strm_close_video_box {
	right: -10px !important;
}

.strm_close_video:hover {
    background: url("../images/closeBTN.png") no-repeat scroll 0 100% transparent;
}
.strm_close_video {
    background: url("../images/closeBTN.png") no-repeat scroll 0 0 transparent;
    color: #FFFFFF;
    font: bold 10px/10px "Cabin",Arial,sans-serif;
    height: 10px;
    margin: 0;
    padding: 5px;
    position: absolute;
    right: -25px;
    text-align: center;
    text-decoration: none;
    top: -5px;
    width: 10px;
    z-index: 10;
	cursor: pointer;
}

.strm_link_button a {
	background-color: white;
}
.strm_link_button {
    background-color: #EFEFEF;
    border: 1px solid #0E7ABF;
    display: block;
    padding: 3px 2px 1px;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	text-align: center;
}

#strm #strm_loadmoreLink {
	width: 267px;
	margin-top: 8px;
}

#strm_application_test {
	width: 1px; height: 1px; border: 0;
}
#strm_application_test iframe {
	width: 1px; height: 1px; border: 0;
	background-color: white;
}

#strm_messageCenter {
	position: absolute;
	width: 500px;
	height: 47px;
	left: 310px;
	top: 9px;
	padding: 10px;
	background-color: #efefef;
	border: 1px #cccccc solid;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	font-size: 16px;
	color: black;
	font-weight: bold;
}
#strm_messageCenter ul {
	margin:0 0 0 14px;
	padding:0;
	list-style-type: disc;
}

#strm_messageCenter li {
	padding: 0;
	margin: 0 0 5px 0;
}

#strm_credentials a{
	color: white;
}
#strm_credentials span {
	color: #E99447;
}
#strm_credentials {
	position: absolute;
	top: 10px;
	right: 11px;
	text-align: right;
	color: white;
	width: 300px;
}

.strm_Pageinate {
	margin-bottom: 5px;
}

.strm_mainButton p {
	margin: 4px 0 0 0;
	padding: 0 0 0 0;
	float: left;
}
.strm_mainButton div {
	margin-right: 4px;
	background-color: #efefef;
	border: 1px solid #0E7ABF;
	-moz-border-radius: 3px;
	-webkit-border-radius: 3px;
	border-radius: 3px;
	padding: 3px;
	width: 16px;
	height: 16px;
	display: block;
	float: left;
}

.strm_mainButton:hover, .strm_Pageinate a:hover, .strm_boxButton:hover  {
	text-decoration: none;
	color: #efefef;
	border: 1px #efefef solid;
	background: #4BAEDC !important;
}

.strm_mainButton, .strm_mainButton:active, .strm_mainButton:visited, .strm_mainButton:focus, .strm_Pageinate a, .strm_boxButton, .strm_boxButton:active, .strm_boxButton:visited, .strm_boxButton:focus {
	border: 1px #025F93 solid;
	text-decoration: underline;
	text-align: center;
	cursor: pointer;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	border-radius: 5px;
	padding: 3px 12px 3px 5px; 
	color: white;
	font-size: 14px;
	text-decoration: none !important;
	background-color: <?php echo $color_2?>;
	background: -moz-linear-gradient(100% 50% 90deg, <?php echo $color_2; ?> , <?php echo $color_1; ?> ) repeat scroll 0 0 transparent;
	background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(<?php echo $color_1; ?>), to(<?php echo $color_2; ?> ));
	background: -webkit-linear-gradient(<?php echo $color_1; ?>, <?php echo $color_2; ?>);
	float: left;
	display: block;
	margin-right: 2px;
}

.strm_Pageinate .page-highlight, .strm_Pageinate a:hover {
	background: #4BAEDC !important;
}
.strm_Pageinate a, .strm_boxButton, .strm_boxButton:active, .strm_boxButton:visited, .strm_boxButton:focus  {
	padding: 3px 5px;
}

.ui-accordion-content {
	padding: 10px !important;
}
/*----- Content Float Fix -----*/
.clearfix:after {
	content: ".";
	display: block;
	height: 0;
	clear: both;
	visibility: hidden;
}

.clearfix {display: inline-block;}

/* Hides from IE-mac \*/
* html .clearfix {height: 1%;}
.clearfix {display: block;}
/* End hide from IE-mac */
