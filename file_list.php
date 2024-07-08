<?php
$dir = 'uploads/';
$files = array_diff(scandir($dir), array('.', '..'));
foreach ($files as $file) {
    $fileSize = filesize($dir . $file);
    $fileSizeFormatted = formatSizeUnits($fileSize);
    echo "<div class='file-item'><span><a href='$dir$file' download>$file</a> - $fileSizeFormatted</span><div class='file-actions'><button onclick=\"renameFile('$file')\">Rename</button><button onclick=\"deleteFile('$file')\">Delete</button></div></div>";
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }

    return $bytes;
}
?>
