<?php
include 'db.php'; // Connect to the database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $landmark = trim($_POST["landmark"]);

    // Handle image upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create uploads folder if not exists
    }

    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $image_name; // Unique file name
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Allowed file types
    $allowed_types = ["jpg", "jpeg", "png", "gif"];
    if (!in_array($imageFileType, $allowed_types)) {
        die("Invalid file format! Only JPG, JPEG, PNG & GIF allowed.");
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO drainage (landmark, image_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $landmark, $target_file);

        if ($stmt->execute()) {
            echo "<script>alert('Complaint submitted successfully!'); window.location.href='home.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();
?>


// Function to perform sentiment analysis on complaint description
function analyzeSentiment($text) {
    // Placeholder for AI sentiment analysis logic
    // This could be an API call to a sentiment analysis service
    // For now, we'll simulate with a simple keyword check
    $positiveWords = ['good', 'satisfied', 'happy'];
    $negativeWords = ['bad', 'unsatisfied', 'angry'];
    $score = 0;

    foreach ($positiveWords as $word) {
        if (stripos($text, $word) !== false) {
            $score++;
        }
    }

    foreach ($negativeWords as $word) {
        if (stripos($text, $word) !== false) {
            $score--;
        }
    }

    return $score > 0 ? 'positive' : ($score < 0 ? 'negative' : 'neutral');
}

// Analyze sentiment of the complaint
$sentiment = analyzeSentiment($landmark);

// Insert sentiment into database
$stmt = $conn->prepare("INSERT INTO drainage (landmark, image_path, sentiment) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $landmark, $target_file, $sentiment);

        if ($stmt->execute()) {
            echo "<script>alert('Complaint submitted successfully!'); window.location.href='home.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();
?>
