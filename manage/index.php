<?php
if($_POST) {
    echo "Strated post.";
    $filename = $_FILES["zip_file"]["name"];
    $source = $_FILES["zip_file"]["tmp_name"];
    $type = $_FILES["zip_file"]["type"];

    $name = explode(".", $filename);
    $accepted_types = array('application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed');
    foreach($accepted_types as $mime_type) {
        if($mime_type == $type) {
            $okay = true;
            break;
        }
    }

    $continue = strtolower($name[1]) == 'zip' ? true : false;
    if(!$continue) {
        $message = "The file you are trying to upload is not a .zip file. Please try again.";
    }

    $target_path = "../site/".$filename;  // change this to the correct site path
    if(move_uploaded_file($source, $target_path)) {
        $zip = new ZipArchive();
        $x = $zip->open($target_path);
        if ($x === true) {
            $zip->extractTo("../site"); // change this to the correct site path
            $zip->close();

            unlink($target_path);
        }

        $copy_path = preg_replace('/\\.[^.\\s]{3,4}$/', '', $target_path)."/vendor";
        echo $copy_path;
        recurse_copy('../vendor', $copy_path);

        $message = "Your .zip file was uploaded, unpacked and copied vendor.";

    } else {
        $message = "There was a problem with the upload. Please try again.";
    }
}

function recurse_copy($src,$dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($src . '/' . $file) ) {
                recurse_copy($src . '/' . $file,$dst . '/' . $file);
            }
            else {
                copy($src . '/' . $file,$dst . '/' . $file);
            }
        }
    }
    closedir($dir);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Untitled Document</title>
</head>

<body>
<?php if(isset($message)) echo "<p>$message</p>"; ?>
<form enctype="multipart/form-data" method="post" action="">
    <label>Choose a zip file to upload: <input type="file" name="zip_file" /></label>
    <br />
    <input type="submit" name="submit" value="Upload" />
</form>
</body>
</html>