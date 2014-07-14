<?php
/* options: */
$allowed_types = array(
	"image/jpeg", 
	"image/jpg", 
	"image/pjpeg", 
	"image/pjpg",
	"image/gif",
	"image/png"
);
$upload_url = "/usr/upload_cms/"; // absolute URL
/* END options; */
 
$output = array();
$root = realpath(str_replace($_SERVER['PHP_SELF'], "", __FILE__));
$upload_path = realpath($root.$upload_url)."/";

// upload
if (!is_uploaded_file($_FILES['upload']['tmp_name']))
	$output['error'] = "Error: No uploaded file found";

// check file-type
elseif (!in_array($_FILES['upload']['type'], $allowed_types))
	$output['error'] = "Error: Uploaded file type, "
		.$_FILES['upload']['type'].", not allowed";

// check file exists
elseif (file_exists($upload_path.$_FILES['upload']['name']))
	$output['error'] = "Error: File name already exists on the server,\n"
		."please rename your file and try again";

// check upload path
elseif (!is_dir($upload_path))
	$output['error'] = "Error: Upload destination path doesn't exist";

// process
else {
	if (!move_uploaded_file($_FILES['upload']['tmp_name'], $upload_path.$_FILES['upload']['name']))
		$output['error'] = "Error: Unknown error occurred while "
			."trying to move uploaded file";
	else {
		$output['url'] = $upload_url.$_FILES['upload']['name'];
	}
}

echo json_encode($output);
?>