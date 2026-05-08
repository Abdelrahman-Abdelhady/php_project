<?php

class Upload
{
    private $file;
    private $allowedExts;
    private $maxSize;
    private $destination;
    private $fileUrl;

    public function __construct($file, $allowedExts = ['jpg', 'png'], $maxSize = 1024000, $destination = 'uploads/img/')
    {
        $this->file = $file;
        $this->allowedExts = $allowedExts;
        $this->maxSize = $maxSize;
        $this->destination = rtrim($destination, '/') . '/';
    }

    public function save()
    {
        if (!isset($this->file) || $this->file['error'] !== 0) {
            throw new Exception("No file uploaded or upload error.");
        }

        $filename = $this->file['name'];
        $filesize = $this->file['size'];
        $tmpName  = $this->file['tmp_name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $this->allowedExts)) {
            throw new Exception("Invalid file type. Allowed: " . implode(', ', $this->allowedExts));
        }

        if ($filesize > $this->maxSize) {
            throw new Exception("File size exceeds maximum allowed: " . $this->maxSize . " bytes");
        }

        $fullDest = __DIR__ . "/../../public/" . $this->destination;
        if (!file_exists($fullDest)) {
            mkdir($fullDest, 0777, true);
        }

        $newFilename = uniqid('user_') . '.' . $ext;
        $destination = $fullDest . $newFilename;

        if (!move_uploaded_file($tmpName, $destination)) {
            throw new Exception("Failed to move uploaded file.");
        }

        $this->fileUrl = $this->destination . $newFilename;
        return $this->fileUrl;
    }

    public function getFileUrl()
    {
        return $this->fileUrl;
    }
}