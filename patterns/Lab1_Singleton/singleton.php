<?php

/**
 * Interface for storage systems.
 * Defines common methods that any storage implementation must provide.
 */
interface StorageInterface
{
    public function connect(): bool;
    public function uploadFile(string $filePath): bool;
    public function downloadFile(string $fileName): string;
    public function deleteFile(string $fileName): bool;
    public function deleteAllFiles(): bool;
    public function listFiles(): array;
}

/**
 * LocalStorage class implements StorageInterface.
 * Simulates file operations on a local disk.
 */
class LocalStorage implements StorageInterface
{
    public function connect(): bool
    {
        echo "Connected to LocalStorage\n";
        return true;
    }

    public function uploadFile(string $filePath): bool
    {
        echo "Uploading file to LocalStorage\n";
        return true;
    }

    public function downloadFile(string $fileName): string
    {
        echo "Downloading file " . $fileName . " to LocalStorage\n";
        return "/local/path/{$fileName}";
    }

    public function deleteFile(string $fileName): bool
    {
        echo "Deleting file " . $fileName . " from LocalStorage\n";
        return true;
    }

    public function deleteAllFiles(): bool
    {
        echo "Deleting all files from LocalStorage\n";
        return true;
    }

    public function listFiles(): array
    {
        echo "Listing files from LocalStorage\n";
        return [];
    }
}

/**
 * AmazonS3Storage class implements StorageInterface.
 * Simulates file operations on Amazon S3 cloud storage.
 */
class AmazonS3Storage implements StorageInterface
{
    public function connect(): bool
    {
        echo "Connected to AmazonS3\n";
        return true;
    }

    public function uploadFile(string $filePath): bool
    {
        echo "Uploading file to AmazonS3\n";
        return true;
    }

    public function downloadFile(string $fileName): string
    {
        echo "Downloading file " . $fileName . " to AmazonS3\n";
        return "/local/path/{$fileName}";
    }

    public function deleteFile(string $fileName): bool
    {
        echo "Deleting file " . $fileName . " from AmazonS3\n";
        return true;
    }

    public function deleteAllFiles(): bool
    {
        echo "Deleting all files from AmazonS3\n";
        return true;
    }

    public function listFiles(): array
    {
        echo "Listing files from AmazonS3\n";
        return [];
    }
}

/**
 * Singleton StorageManager class.
 * Manages a single instance of a storage system.
 * Provides access to storage methods through the singleton instance.
 */
class StorageManager
{
    private static ?StorageManager $instance = null;
    private StorageInterface $storage;

    private function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    private function __clone() {}

    /**
     * Returns the singleton instance of StorageManager.
     * If instance does not exist, create it with the provided storage.
     */
    public static function getInstance(StorageInterface $storage): StorageManager
    {
        if (static::$instance == null) {
            static::$instance = new StorageManager($storage);
        }
        return self::$instance;
    }

    public function connect(): bool
    {
        return $this->storage->connect();
    }

    public function uploadFile(string $filePath): bool
    {
        return $this->storage->uploadFile($filePath);
    }

    public function downloadFile(string $fileName): string
    {
        return $this->storage->downloadFile($fileName);
    }

    public function deleteFile(string $fileName): bool
    {
        return $this->storage->deleteFile($fileName);
    }

    public function deleteAllFiles(): bool
    {
        return $this->storage->deleteAllFiles();
    }

    public function listFiles(): array
    {
        return $this->storage->listFiles();
    }
}

/**
 * Demonstration of StorageManager Singleton usage.
 * Shows that only one instance exists, regardless of storage type passed.
 */

// Using LocalStorage
$local = new LocalStorage();
$manager1 = StorageManager::getInstance($local);

echo "=== Working with LocalStorage ===\n";
$manager1->connect();
$manager1->uploadFile("report.docx");
$manager1->downloadFile("report.docx");
$manager1->listFiles();
$manager1->deleteFile("report.docx");
$manager1->deleteAllFiles();

echo "\n";

// use AmazonS3Storage
$amazon = new AmazonS3Storage();
$manager2 = StorageManager::getInstance($amazon);

echo "=== Working with AmazonS3 ===\n";
$manager2->connect();
$manager2->uploadFile("photo.png");
$manager2->downloadFile("photo.png");
$manager2->listFiles();
$manager2->deleteFile("photo.png");
$manager2->deleteAllFiles();

if ($manager1 === $manager2) {
    echo "\nStorageManager works as a Singleton (only one instance exists)\n";
} else {
    echo "\nSingleton failed — multiple instances exist\n";
}

?>
