<?php
/**
 * Upload.php - File Upload Helper
 * Handles file uploads with validation
 */

class Upload
{
    private $errors = [];

    /**
     * Upload single file
     * @param array $file - $_FILES array element
     * @param string $destination - Destination folder (relative to uploads/)
     * @param array $allowedTypes - Allowed MIME types
     * @param int $maxSize - Maximum file size in bytes
     * @param string|null $newName - Optional new filename (without extension)
     * @return string|false - Uploaded file path or false on failure
     */
    public function uploadFile($file, $destination, $allowedTypes = [], $maxSize = MAX_FILE_SIZE, $newName = null)
    {
        // Check if file was uploaded
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            $this->errors[] = "Tidak ada file yang diupload";
            return false;
        }

        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->getUploadError($file['error']);
            return false;
        }

        // Validate file size
        if ($file['size'] > $maxSize) {
            $maxMB = $maxSize / (1024 * 1024);
            $this->errors[] = "Ukuran file maksimal " . number_format($maxMB, 1) . " MB";
            return false;
        }

        // Validate file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!empty($allowedTypes) && !in_array($mimeType, $allowedTypes)) {
            $this->errors[] = "Tipe file tidak diizinkan";
            return false;
        }

        // Get file extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Generate filename
        if ($newName) {
            $filename = $newName . '.' . $extension;
        } else {
            $filename = $this->generateUniqueFilename($file['name']);
        }

        // Create destination directory if not exists
        $uploadPath = UPLOAD_PATH . '/' . trim($destination, '/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Full path
        $fullPath = $uploadPath . '/' . $filename;

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            // Return relative path from uploads directory
            return trim($destination, '/') . '/' . $filename;
        } else {
            $this->errors[] = "Gagal memindahkan file";
            return false;
        }
    }

    /**
     * Upload multiple files
     * @param array $files - $_FILES array element with multiple files
     * @param string $destination
     * @param array $allowedTypes
     * @param int $maxSize
     * @return array - Array of uploaded file paths
     */
    public function uploadMultiple($files, $destination, $allowedTypes = [], $maxSize = MAX_FILE_SIZE)
    {
        $uploaded = [];

        // Reorganize files array
        $filesArray = $this->reorganizeFilesArray($files);

        foreach ($filesArray as $file) {
            $result = $this->uploadFile($file, $destination, $allowedTypes, $maxSize);
            if ($result) {
                $uploaded[] = $result;
            }
        }

        return $uploaded;
    }

    /**
     * Upload image with resize option
     * @param array $file
     * @param string $destination
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     * @return string|false
     */
    public function uploadImage($file, $destination, $maxWidth = 1200, $maxHeight = 1200, $quality = 85)
    {
        $result = $this->uploadFile($file, $destination, ALLOWED_IMAGE_TYPES);
        
        if ($result) {
            $fullPath = UPLOAD_PATH . '/' . $result;
            $this->resizeImage($fullPath, $maxWidth, $maxHeight, $quality);
        }
        
        return $result;
    }

    /**
     * Delete uploaded file
     * @param string $filePath - Relative path from uploads directory
     * @return bool
     */
    public function deleteFile($filePath)
    {
        $fullPath = UPLOAD_PATH . '/' . $filePath;
        
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        
        return false;
    }

    /**
     * Generate unique filename
     * @param string $originalName
     * @return string
     */
    private function generateUniqueFilename($originalName)
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $basename = pathinfo($originalName, PATHINFO_FILENAME);
        $basename = preg_replace('/[^a-zA-Z0-9_-]/', '', $basename);
        $basename = substr($basename, 0, 50);
        
        return $basename . '_' . time() . '_' . uniqid() . '.' . $extension;
    }

    /**
     * Reorganize $_FILES array for multiple uploads
     * @param array $files
     * @return array
     */
    private function reorganizeFilesArray($files)
    {
        $result = [];
        
        foreach ($files as $key => $values) {
            foreach ($values as $index => $value) {
                $result[$index][$key] = $value;
            }
        }
        
        return $result;
    }

    /**
     * Resize image
     * @param string $filePath
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $quality
     */
    public function resizeImage($filePath, $maxWidth, $maxHeight = null, $quality = 85)
    {
        $imageInfo = getimagesize($filePath);
        
        if (!$imageInfo) {
            return;
        }
        
        list($width, $height, $type) = $imageInfo;
        
        // Check if resize is needed
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return;
        }
        
        // Calculate new dimensions
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);
        
        // Create image from file
        switch ($type) {
            case IMAGETYPE_JPEG:
                $source = imagecreatefromjpeg($filePath);
                break;
            case IMAGETYPE_PNG:
                $source = imagecreatefrompng($filePath);
                break;
            default:
                return;
        }
        
        // Create new image
        $thumb = imagecreatetruecolor($newWidth, $newHeight);
        
        // Preserve transparency for PNG
        if ($type === IMAGETYPE_PNG) {
            imagealphablending($thumb, false);
            imagesavealpha($thumb, true);
        }
        
        // Resize
        imagecopyresampled($thumb, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        
        // Save
        switch ($type) {
            case IMAGETYPE_JPEG:
                imagejpeg($thumb, $filePath, $quality);
                break;
            case IMAGETYPE_PNG:
                imagepng($thumb, $filePath, 9);
                break;
        }
        
        // Clean up
        imagedestroy($source);
        imagedestroy($thumb);
    }

    /**
     * Get upload error message
     * @param int $errorCode
     * @return string
     */
    private function getUploadError($errorCode)
    {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return "File terlalu besar";
            case UPLOAD_ERR_PARTIAL:
                return "File hanya terupload sebagian";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Folder temporary tidak ditemukan";
            case UPLOAD_ERR_CANT_WRITE:
                return "Gagal menulis file ke disk";
            default:
                return "Error upload file";
        }
    }

    /**
     * Get errors
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get first error
     * @return string|null
     */
    public function getFirstError()
    {
        return !empty($this->errors) ? $this->errors[0] : null;
    }
}
