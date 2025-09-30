<?php

/**
 * Інтерфейс StorageInterface
 *
 * Визначає спільні методи для будь-якої системи зберігання файлів.
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
 * Клас LocalStorage
 *
 * Реалізація StorageInterface для локального диску.
 * Симулює операції з файлами на локальному сховищі.
 */
class LocalStorage implements StorageInterface
{
    public function connect(): bool
    {
        echo "Підключено до LocalStorage\n";
        return true;
    }

    public function uploadFile(string $filePath): bool
    {
        echo "Завантаження файлу до LocalStorage\n";
        return true;
    }

    public function downloadFile(string $fileName): string
    {
        echo "Завантаження файлу {$fileName} з LocalStorage\n";
        return "/local/path/{$fileName}";
    }

    public function deleteFile(string $fileName): bool
    {
        echo "Видалення файлу {$fileName} з LocalStorage\n";
        return true;
    }

    public function deleteAllFiles(): bool
    {
        echo "Видалення всіх файлів з LocalStorage\n";
        return true;
    }

    public function listFiles(): array
    {
        echo "Отримання списку файлів з LocalStorage\n";
        return [];
    }
}

/**
 * Клас AmazonS3Storage
 *
 * Реалізація StorageInterface для Amazon S3.
 * Симулює операції з файлами у хмарному сховищі Amazon S3.
 */
class AmazonS3Storage implements StorageInterface
{
    public function connect(): bool
    {
        echo "Підключено до AmazonS3\n";
        return true;
    }

    public function uploadFile(string $filePath): bool
    {
        echo "Завантаження файлу до AmazonS3\n";
        return true;
    }

    public function downloadFile(string $fileName): string
    {
        echo "Завантаження файлу {$fileName} з AmazonS3\n";
        return "/local/path/{$fileName}";
    }

    public function deleteFile(string $fileName): bool
    {
        echo "Видалення файлу {$fileName} з AmazonS3\n";
        return true;
    }

    public function deleteAllFiles(): bool
    {
        echo "Видалення всіх файлів з AmazonS3\n";
        return true;
    }

    public function listFiles(): array
    {
        echo "Отримання списку файлів з AmazonS3\n";
        return [];
    }
}

/**
 * Клас StorageManager
 *
 * Сінглтон для керування системою зберігання файлів.
 * Забезпечує доступ до методів сховища через єдиний екземпляр.
 */
class StorageManager
{
    /** @var StorageManager|null Єдиний екземпляр StorageManager */
    private static ?StorageManager $instance = null;

    /** @var StorageInterface Обране сховище */
    private StorageInterface $storage;

    /**
     * Приватний конструктор для сінглтона
     *
     * @param StorageInterface $storage Сховище для керування
     */
    private function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    private function __clone()
    {
    }

    /**
     * Повертає єдиний екземпляр StorageManager.
     * Якщо екземпляр не існує, створює його з переданим сховищем.
     *
     * @param StorageInterface $storage Сховище для керування
     * @return StorageManager Єдиний екземпляр
     */
    public static function getInstance(StorageInterface $storage): StorageManager
    {
        if (static::$instance === null) {
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
 * Демонстрація роботи Singleton StorageManager.
 * Показує, що існує лише один екземпляр, незалежно від типу переданого сховища.
 */

// Використання LocalStorage
$local = new LocalStorage();
$manager1 = StorageManager::getInstance($local);

echo "=== Робота з LocalStorage ===\n";
$manager1->connect();
$manager1->uploadFile("report.docx");
$manager1->downloadFile("report.docx");
$manager1->listFiles();
$manager1->deleteFile("report.docx");
$manager1->deleteAllFiles();

echo "\n";

// Використання AmazonS3Storage
$amazon = new AmazonS3Storage();
$manager2 = StorageManager::getInstance($amazon);

echo "=== Робота з AmazonS3 ===\n";
$manager2->connect();
$manager2->uploadFile("photo.png");
$manager2->downloadFile("photo.png");
$manager2->listFiles();
$manager2->deleteFile("photo.png");
$manager2->deleteAllFiles();

if ($manager1 === $manager2) {
    echo "\nStorageManager працює як Singleton (існує лише один екземпляр)\n";
} else {
    echo "\nSingleton не спрацював — існують декілька екземплярів\n";
}

?>
