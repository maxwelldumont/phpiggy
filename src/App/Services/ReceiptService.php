<?php

declare(strict_types=1);

namespace App\Services;

use App\Config\Paths;
use Framework\Database;
use Framework\Exceptions\ValidationException;

class ReceiptService
{
    public function __construct(private Database $db)
    {
    }

    public function validateFile(?array $file) //'?' allows for null values for $file as users may submit without uploading a file
    {
        //check that file is present and uploaded without any error like partial uploads
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new ValidationException([
                'receipt' => ['Failed to upload file']
            ]);
        }

        //validate file size:
        $maxFileSizeBytes = 3  * 1024 * 1024;

        if ($file['size'] > $maxFileSizeBytes) {
            throw new ValidationException([
                'receipt' => ['File upload exceeds size limit of 3MB']
            ]);
        }

        //validate file name:
        $originalFileName = $file['name'];

        if (!preg_match('/^[A-Za-z0-9\s._-]+$/', $originalFileName)) {
            throw new ValidationException([
                'receipt' => ['Invalid filename']
            ]);
        }

        //validate file type:
        $clientMimeType = $file['type'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf', 'text/plain'];

        if (!in_array($clientMimeType, $allowedMimeTypes)) {
            throw new ValidationException([
                'receipt' => ['Invalid file type']
            ]);
        }
    }

    public function upload(array $file, string $transactionId)
    {
        //generate a new file name
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = bin2hex(random_bytes(16)) . "." . $fileExtension;
        $uploadPath = Paths::STORAGE_UPLOADS . "/" . $newFileName;

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            throw new ValidationException([
                'receipt' => ['Failed to upload file']
            ]);
        }

        $this->db->query(
            "INSERT INTO receipts(original_filename, storage_filename, media_type, transaction_id)
          VALUES(:original_filename, :storage_filename, :media_type, :transaction_id);",
            [
                'original_filename' => $file['name'],
                'storage_filename' => $newFileName,
                'media_type' => $file['type'],
                'transaction_id' => $transactionId
            ]
        );
    }

    public function getReceipt(string $id)
    {
        $receipt = $this->db->query(
            "SELECT * FROM receipts WHERE id = :id",
            ['id' => $id]
        )->find();

        return $receipt;
    }

    public function getReceipts(string $id)
    {
        $receipt = $this->db->query(
            "SELECT * FROM receipts WHERE transaction_id = :id",
            ['id' => $id]
        )->find();

        return $receipt;
    }

    public function read(array $receipt)
    {
        //get file path
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];

        //check if file exists

        if (!file_exists($filePath)) {
            redirectTo('/');
        }

        //update the html headers 'Content-Disposition' and 'Content-Type'
        header("Content-Disposition: inline;filename={$receipt['original_filename']}");
        header("Content-Type: {$receipt['media_type']}");

        //pass the path to readfile()
        readfile($filePath);
    }

    public function delete(array $receipt)
    {
        //delete file from storage
        $filePath = Paths::STORAGE_UPLOADS . '/' . $receipt['storage_filename'];
        unlink($filePath);

        //remove file record from database

        $this->db->query(
            "DELETE FROM receipts WHERE id = :id;",
            [
                'id' => $receipt['id']
            ]
        );
    }
}
