<?php

/**
 * 
 * @upload.php
 * 
 * Checks whether the user is specifying a custom name for the image, and swaps certain variables around depending on this.
 * If the user isn't logged in then boot them back to the homepage.
 * 
 */

require_once 'config.php';

// ADD filter_var() method to all inputs....

$targetDir = "/Users/mp/Sites/diss/uploads/";
$file = $_FILES['uploadImg'];
$fileName = $_FILES['uploadImg']['name'];
$user = $_SESSION['user'];
$filePath = $targetDir . $fileName;
$file_ext = end(explode(".", $_FILES['uploadImg']['name']));

if (isset($user)) {
	if (!empty($_POST['img_name'])) {
		$fileName = $_POST['img_name'];
		$filePath = $targetDir . $fileName . '.' . $file_ext;
	}

	$servPath = "/~mp/diss/uploads/" . $fileName;

	$insert_img = $conn->prepare("INSERT INTO Images VALUES (NULL, ?, ?, ?, ?, ?, ?)");
	if (!$insert_img) {
		echo 'err:' . $conn->error;
	} else {
		$insert_img->bind_param("ssssss", $filePath, $servPath, $fileName, $user, $_POST['long'], $_POST['lat']);
		if (!$insert_img->execute()) {
			echo "db error";
			print("<br>error: ");
			print_r($insert_img->error);
		} else {
			$fileMove = move_uploaded_file($_FILES['uploadImg']['tmp_name'], $filePath);
			if (!$fileMove) {
				echo "file error";
				print_r($conn->error);
			} else {
				echo "uploaded";
			}
		}
	}


} else {
	header('Location: ../www/index.php');
}
/*
$target_dir = "uploads/";
$file = $_FILES['uploadImg'];
$file_path = "";
$img_name = $_POST['img_name'];
$img_temp = $_FILES['uploadImg']['tmp_name'];

$file_ext = end(explode(".", $_FILES['uploadImg']['name']));
$user = $_SESSION['user'];

print('file: ');
print_r($file);


if (!isset($img_name)) {
  $img_name = uniqid("", true) . ".";
  //$img_name = end($img_name);
}

$file_path = $target_dir . $img_name . $file_ext;

$insert_img = $conn->prepare("INSERT INTO Images VALUES (NULL, ?, ?, ?)");
$insert_img->bind_param("sss", $file_path, $img_name, $user);

if ($insert_img->execute()) {
  $move_file = move_uploaded_file($img_name, $file_path);
  if (!$move_file) {
    echo $move_file->error;
    echo "upload err imgname: " . $img_name . " <br>filepath: " . $file_path;
  } else {
    echo "uploaded";
  }
} else {
  echo "<br>cantupload: " . $insert_img->error;
}

*/
