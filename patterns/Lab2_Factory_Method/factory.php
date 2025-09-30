<?php

/**
 * Інтерфейс SocialNetworkConnector
 *
 * Визначає спільні методи для підключення до соціальної мережі.
 */
interface SocialNetworkConnector
{
    public function login(): void;

    public function logout(): void;

    public function createPost(string $content): void;
}

/**
 * Абстрактна "Фабрика" SocialNetworkPoster
 *
 * Визначає метод отримання конектора та публікації повідомлення.
 */
abstract class SocialNetworkPoster
{
    abstract public function getConnector(): SocialNetworkConnector;

    public function post(string $content): void
    {
        $connector = $this->getConnector();
        $connector->login();
        $connector->createPost($content);
        $connector->logout();
    }
}

/**
 * Конкретна фабрика для Facebook
 */
class FacebookPoster extends SocialNetworkPoster
{
    private string $login;

    private string $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function getConnector(): SocialNetworkConnector
    {
        return new FacebookConnector($this->login, $this->password);
    }
}

/**
 * Конкретна фабрика для LinkedIn
 */
class LinkedInPoster extends SocialNetworkPoster
{
    private string $email;
    private string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getConnector(): SocialNetworkConnector
    {
        return new LinkedInConnector($this->email, $this->password);
    }
}

/**
 * Конкретний продукт: конектор для Facebook
 */
class FacebookConnector implements SocialNetworkConnector
{
    private string $login;
    private string $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function login(): void
    {
        echo "Facebook: авторизація користувача {$this->login}\n";
    }

    public function logout(): void
    {
        echo "Facebook: вихід користувача {$this->login}\n";
    }

    public function createPost(string $content): void
    {
        echo "Facebook: публікація повідомлення: '{$content}'\n";
    }
}

/**
 * Конкретний продукт: конектор для LinkedIn
 */
class LinkedInConnector implements SocialNetworkConnector
{
    private string $email;
    private string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function login(): void
    {
        echo "LinkedIn: авторизація користувача {$this->email}\n";
    }

    public function logout(): void
    {
        echo "LinkedIn: вихід користувача {$this->email}\n";
    }

    public function createPost(string $content): void
    {
        echo "LinkedIn: публікація повідомлення: '{$content}'\n";
    }
}

/**
 * Демонстраційна функція клієнта
 *
 * Використовує фабричний метод для публікації посту.
 *
 * @param SocialNetworkPoster $poster Фабрика для соцмережі
 */
function clientCode(SocialNetworkPoster $poster)
{
    $poster->post("Пост через фабричний метод!");
}

echo "--- Facebook ---\n";
clientCode(new FacebookPoster("pupupu...", "12345"));

echo "\n--- LinkedIn ---\n";
clientCode(new LinkedInPoster("user@linkedin.com", "67890"));
