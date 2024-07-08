<?php
$targetDir = "uploads/";
foreach ($_FILES['fileToUpload']['name'] as $key => $name) {
    $targetFile = $targetDir . basename($name);
    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'][$key], $targetFile)) {
        echo "The file ". htmlspecialchars( basename($name)). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>
