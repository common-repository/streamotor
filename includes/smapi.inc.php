<?php

class Streamotor 
{
	public $SMU;
	public $SMP;
	public $APIURL;
	public $AFFL;

	public function init($u, $p, $aff = 1, $url = "https://streamotor.imavex.com/api")
	{
		$this->SMU = $u;
		$this->SMP = $p;
		$this->AFFL = $aff;
		$this->APIURL = $url;
	}

	function getSMXml($url) {
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC) ;
		curl_setopt($curl, CURLOPT_USERPWD, $this->SMU . ":" . $this->SMP);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		curl_close($curl);

		return array(
			"Status" => $status,
			"XML" => $response
		);
	}	

	function videoObjectToArray($object) {
		$video = array(
			"VideoID" => trim($object->Attributes()->id),
			"Title" => trim($object->Title),
			"Subtitle" => trim($object->Subtitle),
			"Description" => trim($object->Description),
			"Width" => trim($object->Width),
			"Height" => trim($object->Height),
			"Image" => trim($object->Image),
			"Thumb" => trim($object->Thumb),
			"Length" => trim($object->Length),
			"Added" => trim($object->Added),
			"Embed" => trim($object->Embed),
			"Views" => trim($object->Views), 
			"Author" => trim($object->Author)
		);
		$video["Added"] = $this->getHowLongAgo($video["Added"]);

		return $video;
	}
	
	function getHowLongAgo($date) {
	   $return = "Moments";

	   $difference = time() - strtotime($date);
	   $intervals = array(
		   "Year" => 31536000,
		   "Month" => 2592000,
		   "Day" => 86400,
		   "Hour" => 3600,
		   "Minute" => 60,
	   );

	   foreach($intervals as $label => $seconds) {
		   if($difference >= $seconds) {
			   $number = floor($difference / $seconds);
			   $return = $number . " " . $label;
			   if($number > 1)
				   $return .= "s";
			   break;
		   }
	   }

	   return $return . " Ago";
	}

	function getSMGetSharing()
	{
		$url = $this->APIURL . "/sharing";
		
		$response = $this->getSMXml($url);
		if($response["Status"] == 200) {
			$xml = simplexml_load_string($response["XML"]);
			if($xml->Sharing)
			{
				$shares = explode(",", $xml->Sharing);
				$num_s = sizeof($shares);
				for($i=0;$i<$num_s;$i++)
					$shares[$i] = trim(strtolower($shares[$i]));

				return $shares;	
			}
		}

		return $this->processSMStatus($response["Status"]);
		
	}

	function getSMManageURL($urltype, $catid = "", $videoid = "", $redirect = "")
	{
		if(!empty($catid) && ($urltype == "editCategory" || $urltype == "addCategory" || $urltype == "deleteCategory"))
		{
			$url = $this->APIURL . "/url?type=$urltype&id=$catid";
		}	
		else if(!empty($videoid) && ($urltype == "editVideo" || $urltype == "deleteVideo"))
		{
			$url = $this->APIURL . "/url?type=$urltype&id=$videoid";
		}
		else	
			$url = $this->APIURL . "/url?type=$urltype";

		$url = $url . "&aff=" . $this->AFFL;

		$response = $this->getSMXml($url);
		if($response["Status"] == 200) {
			$xml = simplexml_load_string($response["XML"]);
			if($xml->Url)
			{
				$rurl = trim($xml->Url) . "?scheme=0";
				if(!empty($redirect))
					$rurl .= "&redirect=" . base64_encode($redirect);

				return $rurl;	
			}
		}

		return $this->processSMStatus($response["Status"]);
		
	}
	
	function getSMVideos($search = "", $page = "", $itemsPerPage = "") {
		$videos = array();

		$url = $this->APIURL . "/videos";
		if(!empty($search))
		{
			if($search == "pending")
				$url .= "?pending=1";
			else	
				$url .= "?q=" . urlencode($search);
		}	

		if(is_numeric($page))
		{
			if(empty($itemsPerPage))
				$itemsPerPage = 10;
			
			if(!empty($search))
				$url .= "&page=$page&itemsPerPage=$itemsPerPage";
			else	
				$url .= "?page=$page&itemsPerPage=$itemsPerPage";
		}

		if(strpos($url, "?") === false)
			$url = $url . "?aff=" . $this->AFFL;
		else	
			$url = $url . "&aff=" . $this->AFFL;

		$response = $this->getSMXml($url);
		if($response["Status"] == 200) {
			$xml = simplexml_load_string($response["XML"]);
			if($xml->Videos) {
				foreach($xml->Videos->Video as $video)
					$videos[] = $this->videoObjectToArray($video);
			}	
			return $videos;
		}

		return $this->processSMStatus($response["Status"]);

	}	

	function getSMVideo($id) {
		$video = array();
		$url = $this->APIURL . "/videos/$id";
		$url = $url . "?aff=" . $this->AFFL;
		$response = $this->getSMXml($url);
		if($response["Status"] == 200) {
			$xml = simplexml_load_string($response["XML"]);
			if($xml->Video)
				$video = $this->videoObjectToArray($xml->Video);
			return $video;
		}

		return $this->processSMStatus($response["Status"]);

	}

	function playlistObjectToArray($object) {
		$playlist = array(
			"PlaylistID" => trim($object->Attributes()->id),
			"Title" => trim($object->Title),
			"Description" => trim($object->Description),
			"Width" => trim($object->Width),
			"Height" => trim($object->Height),
			"Image" => trim($object->Image),
			"Added" => trim($object->Added),
			"Embed" => trim($object->Embed),
			"Videos" => array()
		);

		if($object->Videos) {
			foreach($object->Videos->Video as $video)
				$playlist["Videos"][] = $this->videoObjectToArray($video);
		}

		return $playlist;
	}

	function getSMPlaylists() {
		$playlists = array();
		$url = $this->APIURL . "/playlists";
		$url = $url . "?aff=" . $this->AFFL;
		$response = $this->getSMXml($url);
		if($response["Status"] == 200) {
			$xml = simplexml_load_string($response["XML"]);
			if($xml->Playlists) {
				foreach($xml->Playlists->Playlist as $playlist)
					$playlists[] = playlistObjectToArray($playlist);
			}	
			return $playlists;
		}

		return $this->processSMStatus($response["Status"]);

	}

	function getSMPlaylist($id) {
		$playlist = array();
		$url = $this->APIURL . "/playlists/$id";
		$url = $url . "?aff=" . $this->AFFL;
		$response = $this->getSMXml($url);
		if($response["Status"] == 200) {
			$xml = simplexml_load_string($response["XML"]);
			if($xml->Playlist)
				$playlist = playlistObjectToArray($xml->Playlist);
			
			return $playlist;
		}

		return $this->processSMStatus($response["Status"]);
	}

	function categoryObjectToArray($object) {
		$category = array(
			"CategoryID" => trim($object->Attributes()->id),
			"Title" => trim($object->Title),
			"TotalVideos" => trim($object->TotalVideos),
			"Page" => trim($object->Page),
			"ItemsPerPage" => trim($object->ItemsPerPage),
			"RecursiveTotalVideos" => trim($object->RecursiveTotalVideos),
			"TotalPages" => trim($object->TotalPages),
			"Added" => trim($object->Added),
			"Videos" => array(),
			"Subcategories" => array()
		);

		if($object->Videos) {
			foreach($object->Videos->Video as $video)
				$category["Videos"][] = $this->videoObjectToArray($video);
		}

		if($object->SubCategories) {
			foreach($object->SubCategories->SubCategory as $subcategory)
				$category["Subcategories"][] = $this->subcategoryObjectToArray($subcategory);
		}

		return $category;
	}

	function subcategoryObjectToArray($object) {
		return array(
			"CategoryID" => trim($object->Attributes()->id),
			"Title" => trim($object->Title),
			"TotalVideos" => trim($object->TotalVideos),
			"RecursiveTotalVideos" => trim($object->RecursiveTotalVideos)
		);
	}

	function getSMCategories() {
		$categories = array();
		$url = $this->APIURL . "/categories";
		$url = $url . "?aff=" . $this->AFFL;

		$response = $this->getSMXml($url);
		if($response["Status"] == 200) {
			$xml = simplexml_load_string($response["XML"]);
			if($xml->Categories) {
				foreach($xml->Categories->Category as $category) {
					$categories[] = $this->categoryObjectToArray($category);
				}	
			}	
			return $categories;
		}
	
		return $this->processSMStatus($response["Status"]);

	}

	function processSMStatus($st)
	{
		if($st == 200)
			return 1;
		else if($st == 201)
			return 1;
		else if($st == 204)
			return -1;
		else if($st == 401)
			return false;
		else if($st == 404)
			return false;
	}

	function getSMCategory($id, $page = "", $itemsPerPage = "") {
		$category = array();
		$url = $this->APIURL . "/categories/$id?page=$page&itemsPerPage=$itemsPerPage";
		$url = $url . "&aff=" . $this->AFFL;
		$response = $this->getSMXml($url);
		if($response["Status"] == 200) {
			$xml = simplexml_load_string($response["XML"]);
			if($xml->Category)
				$category = $this->categoryObjectToArray($xml->Category);
			return $category;
		}

		return $this->processSMStatus($response["Status"]);
	}

	function getSMVideosForm() {
		$return = array("" => "--Select Video--");
		$categories = $this->getSMCategories();
		if(!empty($categories)) {
			foreach($categories as $category)
				$this->appendSMVideosForm($category, $return);
		}
		else {
			$videos = $this->getSMCategories();
			if(!empty($videos)) {
				foreach($videos as $video)
					$return[$video["VideoID"]] = $video["Title"];
			}
		}
		return $return;
	}

	function appendSMVideosForm($category, &$return, $padding = 0) {
		$subCategories = $category["Subcategories"];
		$videos = $category["Videos"];

		if(!empty($subCategories) || !empty($videos)) {
			$return[":pfbc" . $category["CategoryID"]] = $this->applySMPadding("::" . $category["Title"] . "::", $padding);

			if(!empty($subCategories)) {
				foreach($subCategories as $subCategory)
					$this->appendSMVideosForm($this->getSMCategory($subCategory["CategoryID"]), &$return, $padding + 5);
			}

			if(!empty($videos)) {
				foreach($videos as $video)
					$return[$video["VideoID"]] = $this->applySMPadding($video["Title"], $padding + 5);
			}
		}
	}

	function applySMPadding($string, $padding) {
		for($p = 0; $p <= $padding; ++$p)
			$string = "&nbsp;" . $string;
		return $string;
	}

	function getSMCategorizedVideoCheckboxStructure($name, $parentid, $category, &$html, $selected = array(), $padding = 0, $isSortable = false) {
		$videos = $category["Videos"];
		$subCategories = $category["Subcategories"];

		if(!empty($subCategories) || !empty($videos)) {
			$padding += 10;
			$html .= '<strong class="category">' . $category["Title"] . '</strong><div style="padding-left: ' . $padding . 'px;">';

			if(!empty($subCategories)) {
				foreach($subCategories as $subCategory)
					$this->getSMCategorizedVideoCheckboxStructure($name, $parentid, $this->getSMCategory($subCategory["CategoryID"]), $html, $selected, $padding, $isSortable);
			}

			if(!empty($videos)) {
				foreach($videos as $video) {
					$title = $video["Title"];
					if(!empty($video["Subtitle"]))
						$title .= "<br/><small>" . $video["Subtitle"] . "</small>";
					$html .= '<div class="pfbc-checkbox"><table cellpadding="0" cellspacing="0"><tr><td valign="top"><input id="' . $video["VideoID"] . '" type="checkbox" value="' . $video["VideoID"] . '"';
					if(in_array($video["VideoID"], $selected))
						$html .= ' checked="checked"';
					if(!$isSortable)
						$html .= ' name="' . $name . '"';
					else
						$html .= ' onclick="updateChecksort(\'' . $name . '\', \'' . $parentid . '\', this, \'' . addslashes($title) . '\');"';
					$html .= '/></td><td valign="top"><label for="' . $video["VideoID"] . '">' . $title . '</label></td></tr></table></div>';
				}
			}

			$html .= '</div>';
		}
	}
}
?>
