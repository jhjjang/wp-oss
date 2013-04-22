<?
class OSS {

	function showPageList($url, $curPage, $maxPage, $listSize, $onclick="", $anchor="") {
			$body = " <TABLE class=\"paging_table\"> <TR>
			";
			$maxPage = ceil($maxPage);
			$begin = $listSize * floor(($curPage-1) / $listSize) + 1;
			$end = $begin + $listSize < $maxPage ? $begin + $listSize : $maxPage+1;
			$first = $begin>1 ? 1 : 0;
			$last = $end < $maxPage ? $maxPage : 0;
			$prev = $begin > 2 ? $begin - 1 : 0;
			$next = $last > 0 && $end < $last ? $end : 0;

			if (!empty($onclick))
					$script = "onclick=\"$onclick($first)\"";
			if ($first)
					$body .= "<TD class=\"paging_td\"> <a href=\"$url".($first)."\" class=\"paging_link\" $script> << </a></TD> ";
			if (!empty($onclick))
					$script = "onclick=\"$onclick($prev)\"";
			if ($prev)
					$body .= "<TD class=\"paging_td\"> <a href=\"$url".($prev)."\" class=\"paging_link\" $script> < </a></TD> ";
			for ($i=$begin; $i<$end; $i++) {
					if (!empty($onclick))
							$script = "onclick=\"$onclick($i)\"";
					if ($i==$curPage)
							$body .= "<TD class=\"cur_paging_td navi_num\"><b>$i</b></TD>";
					else
							$body .= "<TD class=\"paging_td navi_num\"><a href=\"$url".($i)."\" class=\"paging_link\" $script>$i</a></TD> ";
			}
			if (!empty($onclick))
					$script = "onclick=\"$onclick($next)\"";
			if ($next)
					$body .= "<TD class=\"paging_td\"> <a href=\"$url".($next)."\" class=\"paging_link\" $script> > </a></TD> ";
			if (!empty($onclick))
					$script = "onclick=\"$onclick($last)\"";
			if ($last)
					$body .= "<TD class=\"paging_td\"> <a href=\"$url".($last)."\" class=\"paging_link\" $script> >> </a></TD> ";

			$body .= " </TR>
			</TABLE>";
			return $body;
	}


	function curl_fetch($Url){
		// is cURL installed yet?
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}
		 // OK cool - then let's create a new cURL resource handle
		$ch = curl_init();
		 // Now set some options (most are optional)
		 // Set URL to download
		curl_setopt($ch, CURLOPT_URL, $Url);
		 // Set a referer
	//   curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
		 // User agent
	//   curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		 // Include header in result? (0 = yes, 1 = no)
		curl_setopt($ch, CURLOPT_HEADER, 0);
		 // Should cURL return or print out the data? (true = return, false = print)
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		 // Timeout in seconds
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		 // Download the given URL, and return output
		$output = curl_exec($ch);
		 // Close the cURL resource, and free system resources
		curl_close($ch);
		 return $output;
	}

	function convertAddressToLocation($address){
		$addy = stripslashes($address);
		 
		$apicall = "http://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($addy)."&sensor=false";
		 
		$ret = $this->curl_fetch($apicall);
		 
		$decoded = json_decode($ret,true);
		 
		$lat = $decoded['results'][0]['geometry']['location']['lat'];
		$lng = $decoded['results'][0]['geometry']['location']['lng'];

		$info['latitude'] = $lat;
		$info['longitude'] = $lng;

		return $info;
	}
}
$OSS = new OSS;
?>