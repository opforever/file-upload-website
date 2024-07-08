<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload and Download</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            text-align: center;
            padding: 20px;
            margin: 0;
            overflow: hidden;
        }
        .container {
            background: rgba(30, 30, 30, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            max-width: 500px;
            margin: auto;
            position: relative;
            z-index: 1;
        }
        #progressBar {
            width: 100%;
            background-color: #333;
            margin-top: 20px;
            border-radius: 5px;
            overflow: hidden;
        }
        #progress {
            width: 0%;
            height: 30px;
            background-color: #4caf50;
            text-align: center;
            line-height: 30px;
            color: white;
            transition: width 0.5s;
        }
        .file-list {
            margin-top: 20px;
        }
        .file-item {
            background: #2e2e2e;
            padding: 10px;
            border-radius: 5px;
            margin: 5px 0;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .form-element {
            margin-bottom: 10px;
        }
        .file-actions {
            display: flex;
            gap: 5px;
        }
        .action-button {
            background-color: #4caf50;
            border: none;
            color: white;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('https://www.transparenttextures.com/patterns/dark-wall.png');
            z-index: 0;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <div class="background"></div>
    <div class="container">
        <h1>File Upload and Download</h1>
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="form-element">
                <label for="fileToUpload">Select files to upload:</label>
                <input type="file" name="fileToUpload[]" id="fileToUpload" multiple>
            </div>
            <div class="form-element">
                <input type="button" value="Upload Files" onclick="uploadFiles()">
            </div>
        </form>
        
        <div id="progressBar">
            <div id="progress">0%</div>
        </div>
        <p id="status"></p>
        <p id="estimatedTime"></p>
        <p id="uploadSpeed"></p>
        
        <h2>Uploaded Files</h2>
        <div class="file-list" id="fileList"></div>
    </div>

    <script>
        function uploadFiles() {
            const fileInput = document.getElementById('fileToUpload');
            const files = fileInput.files;
            const formData = new FormData();

            for (let i = 0; i < files.length; i++) {
                formData.append("fileToUpload[]", files[i]);
            }

            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', (event) => {
                if (event.lengthComputable) {
                    const percent = Math.round((event.loaded / event.total) * 100);
                    document.getElementById('progress').style.width = percent + '%';
                    document.getElementById('progress').innerText = percent + '%';
                    
                    const timeElapsed = (event.timeStamp / 1000); // in seconds
                    const uploadSpeed = event.loaded / timeElapsed; // bytes per second
                    const speed = formatSpeed(uploadSpeed);
                    const timeRemaining = (event.total - event.loaded) / uploadSpeed; // in seconds
                    
                    document.getElementById('estimatedTime').innerText = `Estimated time remaining: ${Math.round(timeRemaining)} seconds`;
                    document.getElementById('uploadSpeed').innerText = `Upload speed: ${speed}`;
                }
            });

            xhr.onload = function() {
                if (xhr.status == 200) {
                    document.getElementById('status').innerText = "Files uploaded successfully!";
                    refreshFileList();
                } else {
                    document.getElementById('status').innerText = "Error uploading files.";
                }
            };

            xhr.open('POST', 'upload.php', true);
            xhr.send(formData);
        }

        function formatSpeed(speed) {
            if (speed > 1024 * 1024) {
                return (speed / (1024 * 1024)).toFixed(2) + ' MB/s';
            } else if (speed > 1024) {
                return (speed / 1024).toFixed(2) + ' KB/s';
            } else {
                return speed.toFixed(2) + ' B/s';
            }
        }

        function refreshFileList() {
            const xhr = new XMLHttpRequest();
            xhr.onload = function() {
                if (xhr.status == 200) {
                    document.getElementById('fileList').innerHTML = xhr.responseText;
                } else {
                    document.getElementById('fileList').innerText = "Error loading file list.";
                }
            };
            xhr.open('GET', 'file_list.php', true);
            xhr.send();
        }

        function deleteFile(fileName) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'delete.php', true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status == 200) {
                    refreshFileList();
                } else {
                    alert("Error deleting file.");
                }
            };
            xhr.send("fileName=" + encodeURIComponent(fileName));
        }

        function renameFile(oldName) {
            const newName = prompt("Enter the new name for " + oldName + ":");
            if (newName) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'rename.php', true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onload = function() {
                    if (xhr.status == 200) {
                        refreshFileList();
                    } else {
                        alert("Error renaming file.");
                    }
                };
                xhr.send("oldName=" + encodeURIComponent(oldName) + "&newName=" + encodeURIComponent(newName));
            }
        }

        document.addEventListener('DOMContentLoaded', refreshFileList);

        document.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth) * 100;
            const y = (e.clientY / window.innerHeight) * 100;
            document.querySelector('.background').style.backgroundPosition = `${x}% ${y}%`;
        });
    </script>
</body>
</html>
