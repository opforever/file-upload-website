<?php
if (isset($_POST['fileName'])) {
    $file = 'uploads/' . basename($_POST['fileName']);

    if (file_exists($file)) {
        if (unlink($file)) {
            echo "File deleted successfully.";
        } else {
            echo "Error deleting file.";
        }
    } else {
        echo "File does not exist.";
    }
} else {
    echo "Invalid request.";
}
?>
