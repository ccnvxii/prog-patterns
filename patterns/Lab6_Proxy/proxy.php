<?php

/**
 * Інтерфейс Downloader
 */
interface Downloader {

    /**
     * Завантажує файл із заданої адреси.
     */
    public function download(string $url): string;
}


/**
 * Клас SimpleDownloader
 *
 * Виконує завантаження файлів без кешування.
 */
class SimpleDownloader implements Downloader {

    /**
     * Метод завантаження файлу.
     * Імітує процес отримання даних із вказаної адреси.
     */
    public function download(string $url): string {
        echo "Завантаження файлу з {$url}...\n";
        return "Дані, отримані з {$url}";
    }
}


/**
 * Клас CachingDownloader (Замісник)
 *
 * Додає до базового завантажувача механізм кешування.
 * Замість повторного завантаження тих самих даних повертає результат із кешу.
 *
 */
class CachingDownloader implements Downloader {
    private SimpleDownloader $simpleDownloader;
    private array $cache = [];

    public function __construct(SimpleDownloader $simpleDownloader) {
        $this->simpleDownloader = $simpleDownloader;
    }

    /**
     * Метод завантаження із кешуванням.
     * Якщо дані для вказаного URL уже є в кеші, повертає їх без нового завантаження.
     */
    public function download(string $url): string {
        if (!isset($this->cache[$url])) {
            echo "Завантаження нового файлу: {$url}\n";
            $this->cache[$url] = $this->simpleDownloader->download($url);
        } else {
            echo "Отримано з кешу: {$url}\n";
        }
        return $this->cache[$url];
    }
}


/**
 * Демонстрація роботи
 */

/**
 * Функція clientCode()
 *
 * Демонструє роботу клієнтського коду, який не знає, із яким саме об’єктом працює:
 * з реальним завантажувачем чи з його замісником (Proxy).
 *
 */
function clientCode(Downloader $downloader): void
{
    echo $downloader->download("https://example.com/file1") . "\n";
    echo $downloader->download("https://example.com/file2") . "\n";
    echo $downloader->download("https://example.com/file1") . "\n"; // повторне звернення
}

echo "---- Без кешування -----\n";
$simple = new SimpleDownloader();
clientCode($simple);

echo "\n---- З кешуванням (Proxy) ---\n";
$cached = new CachingDownloader(new SimpleDownloader());
clientCode($cached);

?>
