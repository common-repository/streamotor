<?php
/*************************************************************/
// utilities functions 
/*************************************************************/
function strm_extractData($postget, $fields)
{
	$fields = explode(" ", trim($fields));

	$num_fields = sizeof($fields);
	for($f=0;$f<$num_fields;$f++)
	{
		if(preg_match("/(\w+)\[(\w+)\]/", $fields[$f], $match))
		{
			print_r($match);
			$fieldname = trim($match[1]);
			$data[$fieldname] = $postget[$fieldname];
			if($match[2] == "d")
				$type[] = "%d";
				
		}
		else if(preg_match("/(\w+)/", $fields[$f], $match))
		{
			$fieldname = trim($match[1]);
			$data[$fieldname] = $postget[$fieldname];
			$type[] = "%s";
		}
		
	}

	$ret["Data"]= $data;
	$ret["Types"] = $type;

	return $ret;
}

