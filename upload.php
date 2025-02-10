<?php
session_start();
require 'vendor/autoload.php'; // Load Smalot PDF Parser

// Database connection
$servername = "localhost";
$username = "root"; // Change if necessary
$password = "";
$dbname = "resume_guidance";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

// Ensure the 'uploads' directory exists
$targetDir = "uploads/";
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// File Upload and ATS Analysis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = $_POST['user_name'];
    $email = $_POST['email'];
    $fileName = basename($_FILES["resume"]["name"]);
    $fileName = preg_replace("/[^a-zA-Z0-9.-]/", "_", $fileName); // Sanitize filename
    $targetFilePath = $targetDir . $fileName;
    $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

    if ($fileType == "pdf") {
        if (move_uploaded_file($_FILES["resume"]["tmp_name"], $targetFilePath)) {
            // Extract text from PDF
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($targetFilePath);
            $text = strtolower($pdf->getText());

            // Keywords to check for ATS Compliance
            $requiredSections = ['experience', 'skills', 'education', 'projects', 'certifications'];
            $score = 0;
            $missingSections = [];

            foreach ($requiredSections as $section) {
                if (strpos($text, $section) !== false) {
                    $score += 20; // Increase score if section is found
                } else {
                    $missingSections[] = ucfirst($section);
                }
            }

            // Provide improvement suggestions
            $suggestions = (count($missingSections) > 0) 
                ? "Missing sections: " . implode(", ", $missingSections) 
                : "Your resume follows a good ATS format!";

            // Save ATS result to database
            $stmt = $conn->prepare("INSERT INTO resumes (user_name, email, file_name, ats_score, suggestions) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssis", $user_name, $email, $fileName, $score, $suggestions);
            if ($stmt->execute()) {
                echo "<h2>Resume uploaded and analyzed successfully!</h2>";
                echo "<p><strong>ATS Score:</strong> " . $score . "/100</p>";
                echo "<p><strong>Suggestions:</strong> " . $suggestions . "</p>";
            } else {
                echo "Database Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Error: File upload failed.";
        }
    } else {
        echo "Error: Only PDF files are allowed!";
    }
}

$conn->close();
?>
