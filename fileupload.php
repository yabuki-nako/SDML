<?php
require_once "config.php";
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the file was uploaded without errors
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
        // Retrieve appointment ID from the form
        $appointmentID = $_POST["appointmentID"];

        // Prepare SQL statement
        $sql = "INSERT INTO pdf_files (appointment_ID, file_name, file_path) VALUES (?, ?, ?)";
        
        // Prepare the statement
        $stmt = $mysqli->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("iss", $appointmentID, $filename, $filepath);

            // Get file details
            $filename = basename($_FILES["fileToUpload"]["name"]);
            $filedata = file_get_contents($_FILES["fileToUpload"]["tmp_name"]); // Read the file data
            $target_dir = "uploads/"; // Directory where the file will be saved
            $filepath = $target_dir . $filename; // File path
            
            // Move uploaded file to the target directory
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $filepath)) {
                // Execute the statement
                if ($stmt->execute()) {
                    echo "<script type='text/javascript'>alert('OTP has expired. Please request a new one.');</script>";
 
                } else {
                    echo "Error inserting file data into database.";
                }
            } else {
                echo "Error moving uploaded file.";
            }
        } else {
            echo "Error preparing SQL statement.";
        }
    } else {
        echo "No file uploaded or an error occurred during upload.";
    }
} 

?>