<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcast Uploader</title>
    <script>
        function getFile(inputId) {
            document.getElementById(inputId).click();
        }

        function sub(obj) {
            const file = obj.value.split("\\").pop();
            const fileType = obj.name;

            document.getElementById(fileType + "Btn").innerHTML = file;
            document.myForm.submit();
            event.preventDefault();
        }

        async function loadChannels() {
            try {
                const response = await fetch('data.json');
                const data = await response.json();

                const channelSelect = document.getElementById('channel');
                channelSelect.innerHTML = '';

                for (const channel in data) {
                    const option = document.createElement('option');
                    option.value = channel;
                    option.textContent = channel;
                    channelSelect.appendChild(option);
                }
            } catch (error) {
                console.error('Error loading channels:', error);
            }
        }

        window.onload = loadChannels;
    </script>
    <style>
        body {
            color: black;
        }
        form {
            border: 2px solid indigo;
            padding: 10px;
            border-radius: 20px;
            width: 350px;
            margin: 0 auto;
            height: 560px;
            margin-top: 20px;
        }
        label {
            font-weight: 900;
            font-size: 25px;
        }
        input[type="text"], textarea {
            outline: none;
            border: 2px solid black;
            padding: 10px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: 300;
            width: 100%;
            box-sizing: border-box;
        }
        input[type="submit"] {
            outline: none;
            border: 2px solid black;
            padding: 10px;
            border-radius: 10px;
            font-size: 20px;
            font-weight: 900;
            background: indigo;
            color: white;
            width: 100%;
            margin-top: 15px;
        }
        .file-btn {
            font-family: calibri;
            width: 94%;
            padding: 10px;
            margin-top: 10px;
            border: 1px dashed black;
            border-radius: 10px;
            text-align: center;
            background-color: #DDD;
            cursor: pointer;
            margin-bottom:10px;
        }
        select {
            background: #DDD;
            padding: 10px;
            border-radius: 10px;
            width: 100%;
            margin-top: 10px;
        }
        h1 {
            text-align: center;
            font-size: 35px;
            color: indigo;
        }
    </style>
</head>
<body>
    <code>
    <form name="myForm" action="back.php" method="POST" enctype="multipart/form-data">
        <h1>Podcast CLUB</h1>
        <label for="title">Title</label><br>
        <input type="text" name="title" id="title" required><br>
        <label for="user">Username</label><br>
        <input type="text" name="user" id="user" required><br>
        <label for="desc">Description</label><br>
        <textarea name="desc" id="desc" required></textarea><br>
        
        <div id="audioBtn" class="file-btn" onclick="getFile('audioFile')">Select Podcast</div>
        <input id="audioFile" type="file" name="audio" accept="audio/*" onchange="sub(this)" style="display: none;" required>
        
        <div id="imageBtn" class="file-btn" onclick="getFile('imageFile')">Select Image</div>
        <input id="imageFile" type="file" name="image" accept="image/*" onchange="sub(this)" style="display: none;">
        
        <label for="channel">Select Channel</label><br>
        <select name="channel" id="channel" required>
            <option value="">--Select a Channel--</option>
        </select><br>
        <input type="submit" value="Send">
    </form>
    </code>
</body>
</html>
