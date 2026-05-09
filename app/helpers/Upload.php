<?php

class Upload
{
    private $file; // The uploaded file array from $_FILES
    private $allowedExts; // Allowed file extensions
    private $maxSize; // Maximum file size in bytes
    private $destination; // Destination folder for uploads to be saved
    private $fileUrl; // URL of the saved file after successful upload

    public function __construct($file, $allowedExts = ['jpg', 'png', 'jpeg', 'gif'], $maxSize = 2097152, $destination = 'uploads/img/')
    {
        $this->file = $file;
        $this->allowedExts = $allowedExts;
        $this->maxSize = $maxSize;
        $this->destination = rtrim($destination, '/') . '/'; // Ensure destination ends with a slash e.g. "uploads/img/"
    }

    public function save()
    {
        // 1. Validate the uploaded file
        if (!isset($this->file) || $this->file['error'] !== 0) {
            throw new Exception("No file uploaded or upload error.");
        }

        // 2. catch file details and validate extension and size
        $filename = $this->file['name'];
        $filesize = $this->file['size'];
        $tmpName  = $this->file['tmp_name']; // Temporary file path
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Get file extension

        // Validate file extension
        if (!in_array($ext, $this->allowedExts)) {
            throw new Exception("Invalid file type. Allowed: " . implode(', ', $this->allowedExts)); // Example: "Invalid file type. Allowed: jpg, png, jpeg, gif"
        }

        // Validate file size
        if ($filesize > $this->maxSize) {
            throw new Exception("File size exceeds maximum allowed: " . $this->maxSize . " bytes");
        }

        // 3. Save the file to the destination folder
        // __DIR__ gives the directory of the current file e.g. "C:/xampp/htdocs/php_project/app/helpers/"
        $fullDest = $_SERVER['DOCUMENT_ROOT'] . "/php_project/public/" . $this->destination; // Full path to the destination folder e.g. "C:/xampp/htdocs/php_project/public/uploads/img/"
        
        if (!file_exists($fullDest)) {
            mkdir($fullDest, 0777, true); // Create the destination folder if it doesn't exist, with permissions 0777 and recursive creation enabled
        }

        $newFilename = uniqid('user_') . '.' . $ext; // e.g. "user_5f2c9e8a7b3a1.jpg" - Generate a unique filename to avoid collisions
        $destination = $fullDest . $newFilename; // Full path to save the file e.g. "C:/xampp/htdocs/php_project/public/uploads/img/user_5f2c9e8a7b3a1.jpg"

        // Move the uploaded file from the temporary location to the destination folder
        if (!move_uploaded_file($tmpName, $destination)) {
            throw new Exception("Failed to move uploaded file.");
        }

        // 4. Return the URL of the saved file (e.g. "/php_project/public/uploads/img/user_5f2c9e8a7b3a1.jpg")
        $this->fileUrl = "/php_project/public/" . $this->destination . $newFilename;
        return $this->fileUrl;
    }

    public function getFileUrl()
    {
        return $this->fileUrl;
    }
}