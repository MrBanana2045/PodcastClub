<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['user'], $_POST['desc'], $_POST['title'], $_FILES['audio'], $_FILES['image'], $_POST['channel'])) {

    $user = htmlspecialchars($_POST['user']);
    $desc = htmlspecialchars($_POST['desc']);
    $title = htmlspecialchars($_POST['title']);
    $channel = htmlspecialchars($_POST['channel']);

    $uploadDir = 'podcast/';
    $upimg = 'image/';

    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0777, true)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory Podcast.']);
        exit;
    }
    
    if (!is_dir($upimg) && !mkdir($upimg, 0777, true)) {
        echo json_encode(['status' => 'error', 'message' => 'Failed to create upload directory image.']);
        exit;
    }

    $targetFile = $uploadDir . basename($_FILES["audio"]["name"]);
    $audioFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $image = $upimg . basename($_FILES["image"]["name"]);
    $imageup = strtolower(pathinfo($image, PATHINFO_EXTENSION));

    $allowedTypes = array("mp3", "wav", "ogg", "m4a");
    
    if (!in_array($audioFileType, $allowedTypes)) {
        echo json_encode(['status' => 'error', 'message' => 'Only audio files (mp3, wav, ogg, m4a) are allowed.']);
        exit;
    }

    if (file_exists($targetFile)) {
        echo json_encode(['status' => 'error', 'message' => 'File already exists.']);
        exit;
    }
    if (file_exists(filename: $image)) {
        echo json_encode(['status' => 'error', 'message' => 'File already exists image.']);
        exit;
    }

    if ($_FILES["audio"]["size"] > 10000000) {
        echo json_encode(['status' => 'error', 'message' => 'File size must be less than 10 MB.']);
        exit;
    }
    if ($_FILES["audio"]["error"] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading audio file. Error code: ' . $_FILES["audio"]["error"]]);
        exit;
    }
    
    if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading image file. Error code: ' . $_FILES["image"]["error"]]);
        exit;
    }

    if (move_uploaded_file($_FILES["audio"]["tmp_name"], $targetFile)) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image)){
        $url = 'data.json';
        $data = file_exists($url) ? json_decode(file_get_contents($url), true) : [];
        if (!isset($data[$channel])) {
            $data[$channel] = ['podcast' => [], 'image' => [], 'desc' => [], 'user' => [], 'title' => [], 'like' => [], 'dislike' => []];
        }

        $data[$channel]['podcast'][] = $targetFile;
        $data[$channel]['image'][] = $image;
        $data[$channel]['desc'][] = $desc;
        $data[$channel]['user'][] = $user;
        $data[$channel]['title'][] = $title;
        $data[$channel]['like'][] = 0;
        $data[$channel]['dislike'][] = 0;

        if (file_put_contents($url, json_encode($data, JSON_PRETTY_PRINT)) === false) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save data to JSON file.']);
            exit;
        }

        header("location: http://127.0.0.1:8080/podcast.php");
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error uploading the image file: ' . $_FILES["image"]["error"]]);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error uploading the file.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
}
?>
