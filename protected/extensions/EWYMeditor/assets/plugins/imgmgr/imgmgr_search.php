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
$images_dir_path = "/usr/upload_cms/"; // absolute URL
$cache_dir_path = "/usr/cache/"; // absolute URL
$limit = 20; // results per page
$thumbnail_width = 25;
$thumbnail_height = 25;
/* END options; */

$total = 0;
$output = array();
$root = realpath(str_replace($_SERVER['PHP_SELF'], "", __FILE__));
$images_dir_path_root = realpath($root.$images_dir_path)."/";
$cache_dir_path_root = realpath($root.$cache_dir_path)."/";
$search = empty($_GET['search']) ? false : $_GET['search'];
$start = is_numeric($_GET['resultstart']) ? $_GET['resultstart'] : 1;
$end = 0;

function byte_convert($bytes) {
	$symbol = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');

	$exp = 0;
	$converted_value = 0;
	if( $bytes > 0 )
	{
	  $exp = floor( log($bytes)/log(1024) );
	  $converted_value = ( $bytes/pow(1024,floor($exp)) );
	}

	return sprintf( '%.2f '.$symbol[$exp], $converted_value );
}

// *** returns <img> tag for cached image, if the image doesn't exist - it is created/cached
// Author: Paulus Herewini
function generate_image() {
	
	global $cache_dir_path_root, $cache_dir_path;
	
	list($imagename,$width,$height,$alt,$quality,$style,$returntype,$watermark,$crop) = func_get_args();
	
	$cacheimagename = md5($imagename.$width.$height.$quality.$watermark.$crop).".jpg";
	
	$quality = ($quality > 0) ? $quality : 75;
	
	if (!file_exists($imagename) || !$imagename || !$height || !$width) {
	
		return "Error: Cannot find file (or you have not specified image-path, height and width)";
	
	}elseif (!file_exists($cache_dir_path_root.$cacheimagename)) {
		
		$imgsize = getimagesize($imagename);
		switch ($imgsize[2]) {
			case IMAGETYPE_JPEG :
				$srcimg = imagecreatefromjpeg($imagename);
				break;
			case IMAGETYPE_GIF :
				$srcimg = imagecreatefromgif($imagename);
				break;
		}
		
		if ($srcimg) {
			
			$oldimageheight = imagesy($srcimg);
			$oldimagewidth = imagesx($srcimg);
			
			if ($crop) {
				$dstw = $width;
				$dsth = $height;
				// resize proportionally by specified width
				$tempnewwidth = $width;
				$imageratio = ($width / $oldimagewidth);
				$tempnewheight = ($oldimageheight * $imageratio);
				// if height still too high, resize more
				if ($tempnewwidth < $dstw || $tempnewheight < $dsth) { 
					$imageratio = ($height / $oldimageheight);
					$newwidth = ($oldimagewidth * $imageratio);
					$newheight = $height;
				}else {
					$newwidth = $tempnewwidth;
					$newheight = $tempnewheight;
				}
				$dstx = $dstw < $newwidth ? 0 - (($newwidth - $dstw) / 2) : 0;
				$dsty = $dsth < $newheight ? 0 - (($newheight - $dsth) / 2) : 0;
			}else {
				// resize proportionally by specified width
				$newwidth = $width;
				$imageratio = ($width / $oldimagewidth);
				$newheight = ($oldimageheight * $imageratio);
				// if height still too high, resize more
				if ($newheight > $height) { 
					$imageratio = ($height / $oldimageheight);
					$newwidth = ($oldimagewidth * $imageratio);
					$newheight = $height;
				}
				$dstw = $newwidth;
				$dsth = $newheight;
				$dstx = 0;
				$dsty = 0;
			}
			
			$dstimg = imagecreatetruecolor($dstw, $dsth);
			
			imagecopyresampled($dstimg,$srcimg,$dstx,$dsty,0,0,$newwidth,$newheight,$oldimagewidth,$oldimageheight);
			imagedestroy($srcimg);
			
			if ($watermark) {
				$wm = imagecreatefrompng($watermark);
				imagealphablending($wm, false);
				imagesavealpha($wm, true);
				$wmheight = imagesy($wm);
				$wmwidth = imagesx($wm);
				$padding = 5;
				$opacity = 75;
				imagecopymerge($dstimg, $wm, ($dstw-($wmwidth+$padding)), ($dsth-($wmheight+$padding)), 0, 0, $wmwidth, $wmheight, $opacity);
				imagedestroy($wm);
			}
			
			imageinterlace($dstimg,1);
			imagejpeg($dstimg, $cache_dir_path_root.$cacheimagename, $quality);
			imagedestroy($dstimg);
		} else {
			return "Error: Cannot create image from file (\"".$imagename."\")";
		}
	}
	
	list($width, $height, $type, $attr) = getimagesize($cache_dir_path_root.$cacheimagename);
	
	switch ($returntype) {
		case"filepath":
			return $cache_dir_path_root.$cacheimagename;
			break;
			
		case"absolute-tag":
			return "<img src=\"http://".$_SERVER['HTTP_HOST'].$cache_dir_path.$cacheimagename."\""
				. " width=\"".$width."\""
				. " height=\"".$height."\""
				. " alt=\"".$alt."\""
				. " style=\"".$style."\" />";
			break;
			
		default:
			return "<img src=\"".$cache_dir_path.$cacheimagename."\""
				. " width=\"".$width."\""
				. " height=\"".$height."\""
				. " alt=\"".$alt."\""
				. " style=\"".$style."\" />";
	}
}

// check upload path
if (!is_dir($images_dir_path_root))
	$output['error'] = "Error: Image directory doesn't exist";

// process
else {
	$files = array();
	if ($handle = opendir($images_dir_path_root)) {
		while (false !== ($file = readdir($handle))) {
			if ($file != "." && $file != "..") {
				if ($search && stripos($file, $search) === false)
					continue;
				$files[] = $file;
			}
		}
		closedir($handle);
	}
	sort($files);
	foreach ($files as $file) {
		$total++;
		if ($total >= $start && count($output['images']) < $limit) {
			if ($imgsize = @getimagesize($images_dir_path_root.$file)) {
				$thumb = generate_image(
					$images_dir_path_root.$file,
					$thumbnail_width,
					$thumbnail_height,
					"",
					60,
					"",
					"default",
					false,
					true
				);
				$output['images'][] = array(
					'filename' => "<a href=\"".$images_dir_path.$file."\">".$file."</a>",
					'filesize' => byte_convert(filesize($images_dir_path_root.$file)),
					'thumbnail' => generate_image(
						$images_dir_path_root.$file,
						$thumbnail_width,
						$thumbnail_height,
						"",
						60,
						"",
						"default",
						false,
						true
					),
					'dimensions' => $imgsize[0]."&times;".$imgsize[1],
					'filetype' => $imgsize['mime']
				);
			}else {
				$output['images'][] = array(
					'filename' => $file,
					'filesize' => byte_convert(filesize($images_dir_path_root.$file)),
					'thumbnail' => "&nbsp;",
					'dimensions' => "&nbsp;",
					'filetype' => "&nbsp;"
				);
			}
			$end++;
		}
	}
}

$output['total'] = $total;
$output['start'] = $start;
$output['end'] = $end;

echo json_encode($output);
?>