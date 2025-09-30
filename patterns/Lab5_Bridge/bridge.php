<?php

/**
 * Інтерфейс Renderer
 *
 * Визначає метод renderPage для різних типів подання сторінки.
 */
interface Renderer
{
    public function renderPage(array $data): string;
}

/**
 * HTML Renderer
 */
class HTMLRenderer implements Renderer
{
    public function renderPage(array $data): string
    {
        // Симуляція рендерингу у HTML
        return "<html><body>" . implode("<br>", $data) . "</body></html>";
    }
}

/**
 * JSON Renderer
 */
class JsonRenderer implements Renderer
{
    public function renderPage(array $data): string
    {
        return json_encode($data);
    }
}

/**
 * XML Renderer
 */
class XmlRenderer implements Renderer
{
    public function renderPage(array $data): string
    {
        // Проста симуляція XML
        $xml = "<page>";
        foreach ($data as $key => $value) {
            $xml .= "<$key>$value</$key>";
        }
        $xml .= "</page>";
        return $xml;
    }
}

/**
 * Абстрактна сторінка Page
 *
 * Використовує Renderer для рендерингу.
 */
abstract class Page
{
    protected Renderer $renderer;

    public function __construct(Renderer $renderer)
    {
        $this->renderer = $renderer;
    }

    abstract public function getData(): array;

    public function render(): string
    {
        return $this->renderer->renderPage($this->getData());
    }
}

/**
 * Проста сторінка
 */
class SimplePage extends Page
{
    private string $title;
    private string $content;

    public function __construct(string $title, string $content, Renderer $renderer)
    {
        parent::__construct($renderer);
        $this->title = $title;
        $this->content = $content;
    }

    public function getData(): array
    {
        return [
            'title' => $this->title,
            'content' => $this->content
        ];
    }
}

/**
 * Клас Product для ProductPage
 */
class Product
{
    public string $id;
    public string $name;
    public string $description;
    public string $image;

    public function __construct(string $id, string $name, string $description, string $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->image = $image;
    }
}

/**
 * Сторінка товару
 */
class ProductPage extends Page
{
    private Product $product;

    public function __construct(Product $product, Renderer $renderer)
    {
        parent::__construct($renderer);
        $this->product = $product;
    }

    public function getData(): array
    {
        return [
            'id' => $this->product->id,
            'name' => $this->product->name,
            'description' => $this->product->description,
            'image' => $this->product->image
        ];
    }
}


/**
 * Демонстрація роботи
 */

// Створимо рендерери
$htmlRenderer = new HTMLRenderer();
$jsonRenderer = new JsonRenderer();
$xmlRenderer = new XmlRenderer();

// Проста сторінка
$simplePage = new SimplePage("Головна сторінка", "Ласкаво просимо на наш сайт!", $htmlRenderer);
echo "HTML SimplePage:\n";
echo $simplePage->render() . "\n\n";

$simplePageJson = new SimplePage("Головна сторінка", "Ласкаво просимо на наш сайт!", $jsonRenderer);
echo "JSON SimplePage:\n";
echo $simplePageJson->render() . "\n\n";

// Сторінка товару
$product = new Product("1", "Ноутбук", "Потужний ноутбук", "laptop.png");
$productPage = new ProductPage($product, $htmlRenderer);
echo "HTML ProductPage:\n";
echo $productPage->render() . "\n\n";

$productPageXml = new ProductPage($product, $xmlRenderer);
echo "XML ProductPage:\n";
echo $productPageXml->render() . "\n\n";

$productPageJson = new ProductPage($product, $jsonRenderer);
echo "JSON ProductPage:\n";
echo $productPageJson->render() . "\n\n";
