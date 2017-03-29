<?php
//
// This PHP file is designed to render a report specification from the Data Cookbook.
//
// These first 2 methods of calling this require that the specification have at least
// one APPROVED version:
//
// pass in one parameter, the report ID:               lookup=999
//
// OR, if you want to use the name you can do :        lookup=Report Name&matchType=exact_text
//
// If the specification is not yet APPROVED:
// You can test this using the version id like this:   lookup=1234&matchType=version_id
//
// You can add &links=hide to the URL to suppress the display of links.
// You can add &button to the URL to make this appear as a simple button that will then show the full viewer
//
//
// Adjust these variables to suit your account
//
$subdomain = 'idatau.demo';
$un = 'guest';
$pw = 'cookbook99';

///////////////////////////////////////////////////////////////////////
//
// for alternate instances
//
$full_domain = 'https://' . $subdomain . '.datacookbook.com';


///////////////////////////////////////////////////////////////////////
//
// Modify this function to change what is displayed and how
//
function display_results($requestType, $full_domain, $result, $show_links) {
	$output = '';
	//
	// Parse the XML response and check for an error
	//
	$parsed_result = new SimpleXMLElement($result);
	$response_code = $parsed_result->ServiceResponse->ResponseStatus->ResponseCode;
	if ($response_code <> 0) {
		$output .= $parsed_result->ServiceResponse->ResponseStatus->ResponseMessage;
		$output .= '<hr><h2>Raw response</h2>';
		$output .= htmlentities($result) . '<hr>';
	} else {
		if ($requestType == 'report_lookup') {

			$output .= '<div class="report_section">';
			$result_list = $parsed_result->ServiceResponse->ReportList;
			if ($result_list->count() == 0) {
				$output .= 'Specification Not Found.';
			} else {
				foreach ($result_list->children() as $item) {
					$output .= '<div class="report_header">';
					$output .= '<div style="float:left; margin-top: 10px; min-width:160px; min-height:60px;"><img src="HTTP://www.datacookbook.com/wp-content/uploads/logo.gif" width="150"/></div>';
					$output .= '<div style="float:left; min-width:120px; max-height:60px; margin-top:40px;">Specification</div>';
					$output .= '<div style="float:left; min-width:100px; min-height:60px; margin-top:10px;" id="dcb_report_links" name="dcb_report_links" >';
					if ($show_links) {
						// spec links
						$output .= '<a target="_blank" href="' . $full_domain . '/institution/reports/' . $item->{'report-id'} . '">';
						$output .= 'view details</a><br>';
						$output .= '<a target="_blank" href="' . $full_domain . '/institution/reports/' . $item->{'report-id'} . '#new_comment">';
						$output .= 'comments</a><br>';
						$output .= '<a target="_blank" href="' . $full_domain . '/institution/reports/' . $item->{'report-id'} . '/changes/new">';
						$output .= 'request a change</a><br>';
					}
					$output .= '</div>';
					$output .= '</div>';

					if ($show_links) {
						$output .= '<a target="_blank" href="' . $full_domain . '/institution/reports/' . $item->{'report-id'} . '">';
					}
					$output .= '<div class="report_name">' . $item->name . '</div>';
					if ($show_links) {
						$output .= '</a>';
					}
					$output .= 'Purpose<div class="report_purpose">' . $item->purpose . '</div>';
					$output .= 'Description<div class="report_description">' . $item->description . '</div>';
					$output .= '<div class="report_owner"><b>Owner: </b>' . $item->owner . '</div>';
					$output .= '<div class="report_data_system"><b>Data System: </b>' . $item->{'data-system'} . '</div>';
					// Access Details
					// $output .= 'Access Details<div class="report_access_details">' . $item->{'access-details'} . '</div>';
				}
			}
			$output .= '</div>';
		} else {
			$output .= '<div class="term_section">Related Definitions';
			$result_list = $parsed_result->ServiceResponse->TermList;
			if ($result_list->count() == 0) {
				$output .= 'No Definitions Found For This Report.';
			} else {
				foreach ($result_list->children() as $item) {
					$output .= '<div class="term_container">';
					if ($show_links) {
						$output .= '<a target="_blank" href="' . $full_domain . '/institution/terms/' . $item->{'term-id'} . '">';
						$output .= '<div class="term_name">' . $item->name . '</div>';
						$output .= '</a>';
					} else {
						$output .= '<div class="term_name">' . $item->name . '</div>';
					}
					$output .= '<div class="term_functional_definition">' . $item->{'term-functional-definition'} . '</div>';
					$output .= '</div>';
				}
			}
			$output .= '</div>';
		}
	}
	return $output;
}

////////////////////////////////////////////////////////////////////////////////
//
// Modify the styles below to suit
//
////////////////////////////////////////////////////////////////////////////////
?>
<html>
<head>
<style type="text/css">

	.message {
		font-size: 14px;
		font-style: italic;
		padding: 10px;
	}
	a {
		color: #993333;
		text-decoration: none;
	}
	.api_container {
		font-family: arial;
		font-size: 24px;
		width: 400px;
		background-color: #eeeeee;
		border: solid;

	}
	.report_section {
		margin: 5px;
	}
	.report_header {
		font-size: 12px;
	}

	.report_name {
		font-weight: bold;
	}
	.report_owner {
		padding: 10px;
		font-size: 12px;
		color: #522;
	}
	.report_data_system {
		padding: 10px;
		font-size: 12px;
		color: #522;
	}
	.report_purpose, .report_description {
		font-size: 14px;
		margin-left: 15px;
		color: #777;
	}
	.report_access_details {
		width: 95%;
		overflow-x: scroll;
		font-size: 10px;
		margin-left: 15px;
	}
	.report_display_delimiter {}
	.report_additional_details, .report_display_header, .report_display_layout, .report_display_footer {
		font-size: 12px;
		font-family: courier;
	}

	.term_section {
		padding-top:15px;
		border-top: solid;
		margin: 5px;
	}
	.term_container {
	}
	.term_name {
		font-size: 18px;
		margin: 5px;
	}
	.term_functional_definition {
		font-size: 12px;
		color: #777;
		margin-left: 15px;
	}


	a.button {
		text-decoration: none;
		}
	div.button
	{
		width: 150px;
		border: solid;
		border-radius: 10px;
		border-color: #273691;
		background-color: white;
		color: black;
		text-align: center;
		font-weight: bold;
		padding: 5px;
	}
	div.button:hover
	{
		background-color: #C3D751;
	}

</style>

<script language="JavaScript" type="text/javascript">
	function get_url_params(u){
		var theURL = u;
		var JS_GET = new Object();
		var splitURL = theURL.split('?');

		if(splitURL.length>1) {
			var splitVars = splitURL[1].split('&');
			for(i=0; i< splitVars.length; i++){
				splitPair = splitVars[i].split('=');
				JS_GET[splitPair[0]] = splitPair[1];
			}//end for
			return JS_GET;
		} else {
			return false;
		}
	}

	function open_dcb_spec_summary() {
		var url_parms = get_url_params(location.href);
		var url = 'dcb_spec_summary.php?lookup=';
		if (url_parms.lookup!==undefined) {
			url = url + url_parms.lookup;
		}
		if (url_parms.matchType!==undefined) {
			url = url + '&matchType='+url_parms.matchType;
		}
		if (url_parms.links=='hide') {
			url = url + '&links=hide';
		}
		/*
		if (url_parms.lookup!==undefined) {
			if (url_parms.matchType!==undefined) {
				window.open('dcb_spec_summary.php?lookup='+url_parms.lookup+'&matchType='+url_parms.matchType,'datacookbook','scrollbars=yes,center,height=600,width=450');
			} else {
				window.open('dcb_spec_summary.php?lookup='+url_parms.lookup,'datacookbook','scrollbars=yes,center,height=600,width=450');
			}
		}
		*/
		window.open(url,'datacookbook','scrollbars=yes,center,height=600,width=450');

	}
</script>
</head>
<?php
/////////////////////////////////////////////////////////////////////////////////
//  MAIN PROGRAM
/////////////////////////////////////////////////////////////////////////////////
//
// Are we suppressing links? (for use when presenting to public viewers)
//
$show_links = true;
if ($_GET['links'] == 'hide') {
	$show_links = false;
}
//
// Are we showing the button? or providing the full summary?
//
if (isset($_GET['button'])) {
	echo '<body style="background-color: white;"><div class="buttonPage"><a class="button" onclick="open_dcb_spec_summary()"><div class="button"><img src="logo-small.png" width="150"/></div></a></div>';
} else {

	echo '<body style="background-color: #005599;"><div class="api_container">';
	//echo '<span class="message">';
	//
	// Base arguments
	//
	$base_args = '?un=' . $un . '&pw=' . $pw . '&outputFormat=xml';
	//
	// Gather General Specification Information
	//
	$requestType = 'report_lookup';
	//echo 'contacting Data Cookbook...';
	$url = build_request_url($requestType, $base_args, $full_domain);
	$result = call_api($url);
	//echo '<br>processing report information...';
	$report_output = display_results($requestType, $full_domain, $result, $show_links);
	//
	// Gather All Related Definitions
	//
	$requestType = 'report_termlist';
	//echo '<br>getting definitions...';
	$url = build_request_url($requestType, $base_args, $full_domain);
	$result = call_api($url);
	//echo '<br>processing term information...';
	//echo '</span>';


	$term_output = display_results($requestType, $full_domain, $result, $show_links);
	echo $report_output;
	echo $term_output;
	echo '</div>';

}


//////////////////////////////////////////////////////////////////////////////////
//
// Leave these routines alone
//////////////////////////////////////////////////////////////////////////////////

function build_request_url($requestType, $base_args, $full_domain) {

	$required_args = array();
	$optional_args = array();

	switch ($requestType) {
		case 'report_lookup':
			//
			// Report Lookup
			//
			$api_path = '/institution/reports/lookup';
			$required_args['lookup'] = '';
			$optional_args['matchType'] = 'report_id'; // report_id, version_id, exact_text
			break;
		case 'report_termlist':
			//
			// Report Termlist
			//
			$api_path = '/institution/reports/lookup';
			$required_args['lookup'] = '';
			$optional_args['matchType'] = 'report_id'; // report_id, version_id, exact_text
			break;
	}

	//
	// parse parameters from URL
	//
	foreach ($required_args as $name=>$value) {
		if (isset($_GET[$name])) {
			$required_args[$name] = $_GET[$name];
		}
	}
	foreach ($optional_args as $name=>$value) {
		if (isset($_GET[$name])) {
			$optional_args[$name] = $_GET[$name];
		}
	}
	/////////////////////////
	// Build the API Request
	/////////////////////////
	//
	// Set basic URL
	//
	$url = $full_domain . $api_path . $base_args . '&requestType=' . $requestType;
	//
	// Add Required arguments
	//
	foreach ($required_args as $name => $value) {
		if ($value <> '') {
			$url .= '&' . $name . '=' . $value;
		} else {
			echo 'ERROR: Failed call to API - missing the required argument: {' . $name . '}<br>';
		}
	}
	//
	// Add Optional arguments
	//
	foreach ($optional_args as $name => $value) {
		if ($value <> '') {
			$url .= '&' . $name . '=' . $value;
		}
	}
	return $url;
}

function call_api($url) {
	//
	// Now invoke the API and receive results
	//
	$url_split = explode('?',$url);
	$base_url = $url_split[0];
	$post_args = $url_split[1];
	$ch = curl_init();
	// Will return the response, if false it print the response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// Set the url
	curl_setopt($ch, CURLOPT_URL, $base_url);
	// Add POST arguments for extra security
	curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_args);
	// Execute
	$result=curl_exec($ch);
	curl_close($ch);
	return $result;
}

echo '</body></html>';


?>
