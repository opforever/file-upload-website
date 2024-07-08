<?php
if (isset($_POST['oldName']) && isset($_POST['newName'])) {
    $dir = 'uploads/';
    $oldName = $dir . basename($_POST['oldName']);
    $newName = $dir . basename($_POST['newName']);

    if (file_exists($oldName)) {
        if (rename($oldName, $newName)) {
            echo "File renamed successfully.";
        } else {
            echo "Error renaming file.";
        }
    } else {
        echo "File does not exist.";
    }
} else {
    echo "Invalid request.";
}
?>
