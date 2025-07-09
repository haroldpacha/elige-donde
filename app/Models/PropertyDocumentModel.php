<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyDocumentModel extends Model
{
    protected $table = 'property_documents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;

    protected $allowedFields = [
        'property_id', 'document_type', 'file_name', 'file_path', 'file_size', 'uploaded_by'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'property_id' => 'required|is_natural_no_zero',
        'document_type' => 'permit_empty|in_list[pdf,contract,deed,plan,other]',
        'file_name' => 'required|max_length[255]',
        'file_path' => 'required|max_length[500]',
        'uploaded_by' => 'permit_empty|is_natural_no_zero'
    ];

    // Get documents for a property
    public function getPropertyDocuments($propertyId)
    {
        return $this->select('
                property_documents.*,
                admin_users.first_name as uploader_first_name,
                admin_users.last_name as uploader_last_name
            ')
            ->join('admin_users', 'admin_users.id = property_documents.uploaded_by', 'left')
            ->where('property_documents.property_id', $propertyId)
            ->orderBy('property_documents.created_at', 'DESC')
            ->findAll();
    }

    // Upload document
    public function uploadDocument($data)
    {
        return $this->insert($data);
    }

    // Delete document and file
    public function deleteDocument($id)
    {
        $document = $this->find($id);

        if ($document) {
            // Delete physical file
            $filePath = FCPATH . $document['file_path'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete database record
            return $this->delete($id);
        }

        return false;
    }

    // Get document by ID with property info
    public function getDocumentWithProperty($id)
    {
        return $this->select('
                property_documents.*,
                properties.property_code,
                properties.title as property_title,
                admin_users.first_name as uploader_first_name,
                admin_users.last_name as uploader_last_name
            ')
            ->join('properties', 'properties.id = property_documents.property_id')
            ->join('admin_users', 'admin_users.id = property_documents.uploaded_by', 'left')
            ->find($id);
    }

    // Get all documents with property info
    public function getAllDocumentsWithProperty($limit = 50)
    {
        return $this->select('
                property_documents.*,
                properties.property_code,
                properties.title as property_title,
                admin_users.first_name as uploader_first_name,
                admin_users.last_name as uploader_last_name
            ')
            ->join('properties', 'properties.id = property_documents.property_id')
            ->join('admin_users', 'admin_users.id = property_documents.uploaded_by', 'left')
            ->orderBy('property_documents.created_at', 'DESC')
            ->findAll($limit);
    }

    // Get documents by type
    public function getDocumentsByType($type, $limit = 20)
    {
        return $this->select('
                property_documents.*,
                properties.property_code,
                properties.title as property_title
            ')
            ->join('properties', 'properties.id = property_documents.property_id')
            ->where('property_documents.document_type', $type)
            ->orderBy('property_documents.created_at', 'DESC')
            ->findAll($limit);
    }

    // Generate file path for property document
    public function generateFilePath($propertyCode, $fileName)
    {
        $year = date('Y');
        $month = date('m');

        $directory = "uploads/properties/{$year}/{$month}/{$propertyCode}/";

        // Create directory if not exists
        $fullPath = FCPATH . $directory;
        if (!is_dir($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        return $directory . $fileName;
    }

    // Get file extension
    public function getFileExtension($fileName)
    {
        return strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    }

    // Get formatted file size
    public function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    // Check if file is PDF
    public function isPDF($fileName)
    {
        return $this->getFileExtension($fileName) === 'pdf';
    }

    // Get document statistics
    public function getDocumentStats()
    {
        $stats = [
            'total_documents' => $this->countAllResults(),
            'pdf_documents' => $this->where('document_type', 'pdf')->countAllResults(),
            'contract_documents' => $this->where('document_type', 'contract')->countAllResults(),
            'deed_documents' => $this->where('document_type', 'deed')->countAllResults(),
            'plan_documents' => $this->where('document_type', 'plan')->countAllResults(),
            'other_documents' => $this->where('document_type', 'other')->countAllResults(),
            'total_size' => $this->selectSum('file_size')->first()['file_size'] ?? 0,
            'recent_uploads' => $this->select('
                    property_documents.*,
                    properties.property_code,
                    properties.title as property_title
                ')
                ->join('properties', 'properties.id = property_documents.property_id')
                ->orderBy('property_documents.created_at', 'DESC')
                ->findAll(5)
        ];

        return $stats;
    }

    // Clean orphaned files (files without database records)
    public function cleanOrphanedFiles()
    {
        $uploadsPath = FCPATH . 'uploads/properties/';
        $cleanedFiles = [];

        if (is_dir($uploadsPath)) {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($uploadsPath)
            );

            foreach ($iterator as $file) {
                if ($file->isFile()) {
                    $relativePath = str_replace(FCPATH, '', $file->getPathname());

                    // Check if file exists in database
                    $exists = $this->where('file_path', $relativePath)->first();

                    if (!$exists) {
                        unlink($file->getPathname());
                        $cleanedFiles[] = $relativePath;
                    }
                }
            }
        }

        return $cleanedFiles;
    }
}
