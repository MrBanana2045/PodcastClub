<?php
$jsonFilePath = 'data.json';
$jsonData = file_get_contents($jsonFilePath);
$data = json_decode($jsonData, true);
$channels = array_keys($data);

$selectedChannel = $_GET['channel'] ?? null;

if ($selectedChannel && array_key_exists($selectedChannel, $data)) {
    $podcast = $data[$selectedChannel]['podcast'] ?? [];
    $image = $data[$selectedChannel]['image'] ?? [];
    $desc = $data[$selectedChannel]['desc'] ?? [];
    $user = $data[$selectedChannel]['user'] ?? [];
    $title = $data[$selectedChannel]['title'] ?? [];
    $like = $data[$selectedChannel]['like'] ?? [];
    $dislike = $data[$selectedChannel]['dislike'] ?? [];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $podcastIndex = intval($_POST['podcast_index']);
        if (isset($_POST['like'])) {
            $like[$podcastIndex] = (isset($like[$podcastIndex]) ? $like[$podcastIndex] : 0) + 1;
        } elseif (isset($_POST['dislike'])) {
            $dislike[$podcastIndex] = (isset($dislike[$podcastIndex]) ? $dislike[$podcastIndex] : 0) + 1;
        }
        $data[$selectedChannel]['like'] = $like;
        $data[$selectedChannel]['dislike'] = $dislike;
        file_put_contents($jsonFilePath, json_encode($data, JSON_PRETTY_PRINT));
    }
} else {
    $selectedChannel = $channels[0] ?? null;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Podcast CLUB</title>
    <style>
        body{
            background: black;
            color:white;
        }
        button[type="submit"] {
            font-size: 30px;
            background: none;
            outline: none;
            border: none;
        }
        .pod {
            margin-bottom: 20px;
            border: 1px solid indigo;
            padding:10px;
            border-radius: 20px;
            height:420px;
        }
        audio {
            color-scheme: dark;
            background: rgba(0, 0, 0, 1);
            border-radius: 0px 0px 30px 30px;
            width: 400px;
            background: indigo;
            margin-top: -10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
            audio:hover {
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
}
audio::-webkit-media-controls-panel {
  background-color: indigo;
  border-radius: 8px;
  padding: 5px;
}

audio::-webkit-media-controls-play-button:hover,
audio::-webkit-media-controls-volume-slider:hover,
audio::-webkit-media-controls-mute-button:hover,
audio::-webkit-media-controls-seek-back-button:hover,
audio::-webkit-media-controls-seek-forward-button:hover {
  background-color: black;
  border-radius:10px;
}
        img {
            max-width: 400px;
            border-radius: 20px 20px 0px 0px;
            margin-top:10px;
        }
        ::-webkit-scrollbar {
  width: 5px;
}

::-webkit-scrollbar-track {
  background: black; 
}
 
::-webkit-scrollbar-thumb {
  background: indigo; 
}
        .tab {
            display: inline-block;
            padding: 10px;
            cursor: pointer;
            border: 1px solid black;
            border-radius: 10px 10px 0 0;
            background-color: indigo;
            color:white;
            border: 1px solid #9370DB;
            font-size: 20px;
            font-weight: 200;
        }
        .tab-content {
            border: 1px solid #9370DB;
            border-top: none;
            padding: 10px;
            display: none;
            text-align: center;
            margin-bottom: 20px;
        }
        .active {
            display: block;
        }
        .send{
            position: fixed;
            bottom:0px;
            left:0px;
            right:0px;
            padding: 10px;
            outline: none;
            background: indigo;
            color:none;
            font-weight: 200;
            color: white;
            font-size: 20px;
            border:none;
            border-radius: 20px 20px 0px 0px;
        }
        .tab:hover{
            background: red;
        }
    </style>
</head>
<body>
    <code>
<h1 style="text-align:center; color:#9370DB; font-weight: 900; font-size:35px;">Podcast CLUB</h1>
<div>
    <?php foreach ($channels as $channel): ?>
        <div class="tab" onclick="openTab('<?= htmlspecialchars($channel) ?>')"><a href="?channel=<?= htmlspecialchars($channel) ?>" style="color:white; text-decoration: none;"><?= htmlspecialchars($channel) ?></a></div>
    <?php endforeach; ?>
</div>

<?php foreach ($channels as $channel): ?>
    <div id="<?= htmlspecialchars($channel) ?>" class="tab-content <?= $selectedChannel === $channel ? 'active' : '' ?>">
        <?php
            $podcast = $data[$channel]['podcast'] ?? [];
            $image = $data[$channel]['image'] ?? [];
            $desc = $data[$channel]['desc'] ?? [];
            $user = $data[$channel]['user'] ?? [];
            $title = $data[$channel]['title'] ?? [];
            $like = $data[$channel]['like'] ?? [];
            $dislike = $data[$channel]['dislike'] ?? [];
        ?>
        <?php foreach ($podcast as $index => $pod): ?>
            <?php
                $currentTitle = $title[$index] ?? 'Unknown Title';
                $currentDesc = $desc[$index] ?? 'No Description';
                $currentUser = $user[$index] ?? 'Unknown User';
                $currentLike = $like[$index] ?? 0;
                $currentDislike = $dislike[$index] ?? 0;
                $currentImg = $image[$index] ?? '';
            ?>
            <div class="pod">
                <p style="font-size:25px; font-weight: 900; margin-top:10px; margin-bottom:1px;"><?php echo htmlspecialchars($currentTitle); ?></p>
                <?php if ($currentImg): ?>
                    <img src="<?php echo htmlspecialchars($currentImg); ?>" alt="<?php echo htmlspecialchars($currentTitle); ?>"><br>
                <?php endif; ?>
                <audio controls>
                    <source src="<?php echo htmlspecialchars($pod); ?>" type="audio/mpeg">
                    Your browser does not support the audio element.
                </audio>
                <p style="text-align:left; font-size:15px;"><?php echo htmlspecialchars($currentDesc); ?></p>
                
                <form method="POST" style="padding:5px; border-radius: 0px 0px 10px 10px; height:25px; position:absulote; margin-top:30px; color:white; margin-left:-11px; margin-right:-11px; background-color: indigo;">
                    <input type="hidden" name="podcast_index" value="<?php echo $index; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 512" height="20" width="20" style="float:left; margin-left:10px;"><path fill="#9370DB" fill-rule="nonzero" d="M256 0c68 0 132.89 26.95 180.96 75.04C485.05 122.99 512 188.11 512 256c0 68-26.95 132.89-75.04 180.96-23.49 23.56-51.72 42.58-83.15 55.6C323.59 505.08 290.54 512 256 512c-34.55 0-67.6-6.92-97.83-19.44l-.07-.03c-31.25-12.93-59.42-31.93-83.02-55.54l-.07-.07C26.9 388.82 0 324.03 0 256 0 116.78 112.74 0 256 0zm-52.73 332.87a67.668 67.668 0 01-5.6-6.74c-10.84-14.83-20.55-31.61-30.32-47.22-7.06-10.41-10.78-19.71-10.78-27.14 0-7.95 4.22-17.23 12.64-19.34-1.11-15.99-1.49-31.77-.74-48.88.37-4.08 1.12-8.17 2.23-12.27 4.84-17.1 16.73-30.86 31.61-40.15 5.2-3.35 10.78-5.94 17.1-8.18 10.78-4.09 5.57-20.45 17.48-20.82 27.88-.74 73.61 23.06 91.46 42.38 10.41 11.16 17.1 26.03 18.22 45.74l-1.12 44.03c5.2 1.49 8.55 4.84 10.04 10.04 1.49 5.95 0 14.13-5.2 25.67 0 .36-.38.36-.38.74-11.47 18.91-23.39 40.77-36.57 58.33-6.63 8.83-12.07 7.26-6.42 15.74 26.88 36.96 79.9 31.82 112.61 56.44 35.73-40.16 55.15-91.48 55.15-145.24 0-58.34-22.8-113.35-64.07-154.61v-.08C369.44 60.1 314.23 37.32 256 37.32 134.4 37.32 37.32 135.83 37.32 256c0 53.85 19.41 105.03 55.15 145.24 32.72-24.62 85.73-19.48 112.61-56.44 4.68-7.01 3.48-6.33-1.81-11.93z"/></svg><p style="float:left; margin-left:10px; margin-top:3px;"><?php echo htmlspecialchars($currentUser); ?></p><button type="submit" name="like" style="font-size:15px; float:right; margin-right: 30px; color:white;">👍<?php echo $currentLike; ?></button><button type="submit" name="dislike" style="font-size:15px; float:right; margin-right: -5px; color:white;">👎<?php echo $currentDislike; ?></button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
<button class="send"><a href="http://127.0.0.1:8080" style="text-decoration: none; color:white;"><svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 379.661" width="30" height="30"><path fill-rule="nonzero" fill="#fff" d="M153.764 151.353c-7.838-.333-13.409-2.935-16.619-7.822-8.724-13.076 3.18-25.997 11.443-35.099 23.441-25.725 80.888-87.554 92.454-101.162 8.768-9.693 21.25-9.693 30.017 0 11.948 13.959 72.287 78.604 94.569 103.628 7.731 8.705 17.292 20.579 9.239 32.633-3.287 4.887-8.798 7.489-16.636 7.822H310.65v96.177c0 12.558-10.304 22.868-22.871 22.868h-63.544c-12.572 0-22.871-10.294-22.871-22.868v-96.177h-47.6zm-153 97.863c-2.622-10.841 1.793-19.33 8.852-24.342a24.767 24.767 0 018.47-3.838c3.039-.738 6.211-.912 9.258-.476 8.585 1.232 16.409 6.775 19.028 17.616a668.81 668.81 0 014.56 20.165 1259.68 1259.68 0 013.611 17.72c4.696 23.707 8.168 38.569 16.924 45.976 9.269 7.844 26.798 10.55 60.388 10.55h254.297c31.012 0 47.192-2.965 55.706-10.662 8.206-7.418 11.414-21.903 15.564-44.131a1212.782 1212.782 0 013.628-18.807c1.371-6.789 2.877-13.766 4.586-20.811 2.619-10.838 10.438-16.376 19.023-17.616 3.02-.434 6.173-.256 9.212.474 3.071.738 5.998 2.041 8.519 3.837 7.05 5.007 11.457 13.474 8.855 24.294l-.011.046a517.834 517.834 0 00-4.181 18.988c-1.063 5.281-2.289 11.852-3.464 18.144l-.008.047c-6.124 32.802-11.141 55.308-27.956 71.112-16.565 15.572-42.513 22.159-89.473 22.159H131.857c-49.096 0-76.074-5.911-93.429-21.279-17.783-15.75-23.173-38.615-30.047-73.314-1.39-7.029-2.728-13.738-3.638-18.091-1.281-6.11-2.6-12.081-3.979-17.761z"/></svg></a></button>
<script>
    function openTab(channel) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";  
        }
        document.getElementById(channel).style.display = "block";  
    }
</script>
</code>
</body>
</html>
