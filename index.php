<?php
function watermark($oldimage, $new_image){
    $image_path = "logo.png";
    list($owidth,$oheight) = getimagesize($oldimage);
    $width = $height = 300;    
    $im = imagecreatetruecolor($owidth, $oheight);
    $img_src = imagecreatefromjpeg($oldimage);
    imagecopyresampled($im, $img_src, 0, 0, 0, 0, $owidth, $oheight, $owidth, $oheight);
    $watermark = imagecreatefrompng($image_path);
    list($w_width, $w_height) = getimagesize($image_path);        
    $pos_x = $owidth - $w_width; 
    $pos_y = $oheight - $w_height;
    imagecopy($im, $watermark, $pos_x, $pos_y, 0, 0, $w_width, $w_height);
    imagejpeg($im, $new_image, 100);
    imagedestroy($im);
    unlink($oldimage);
    return true;
}

$show_image= " ";

if(isset($_POST['process'])){
    $folder = "uploads/";
    $filetype = array("jpg","bmp","jpeg");
	$name = $_FILES['images']['name'];
	if(strlen($name)){
		list($txt, $ext) = explode(".", $name);
		if(in_array($ext,$filetype)){
			$upload_status = move_uploaded_file($_FILES['images']['tmp_name'], $folder.$_FILES['images']['name']);
			$show_image = $folder.md5(time()).".jpg";
			watermark($folder.$_FILES['images']['name'], $show_image);
		}
	}else{
		$error="Something Wrong!";
	}
}
?>
<html>
    <head>
        <title>
			Simple Watermark Image
        </title>
    </head>
    <body>
		<h1>Simple Watermark Image</h1>
        <form action="" method="post" enctype="multipart/form-data">
			Gambar : <input type="file" name="images"/><br />
			<input type="submit" name="process" value="Submit" />
        </form>
		<?php
			if(!empty($show_image)){
				echo "<br/><center><img src=".$show_image."></center>";
			}else{
				echo "<h3>".$error."</h3>";
			}
		?>
    </body>
</html>
