<?php

/**
 * Інтерфейс Notification
 *
 * Визначає метод для відправки сповіщень.
 */
interface Notification
{
    public function send(string $title, string $message);
}

/**
 * Клас EmailNotification
 *
 * Відповідає за відправку email-повідомлень
 */
class EmailNotification implements Notification
{
    private string $adminEmail;

    public function __construct(string $adminEmail)
    {
        $this->adminEmail = $adminEmail;
    }

    public function send(string $title, string $message): void
    {
        // Симуляція відправки email
        // mail($this->adminEmail, $title, $message);
        echo "Email sent to '{$this->adminEmail}' with title '{$title}' and message '{$message}'\n";
    }
}

/**
 * Клас SlackSender
 *
 * Відповідає за роботу з Slack API
 */
class SlackSender
{
    private string $login;
    private string $apiKey;
    private string $chatId;

    public function __construct(string $login, string $apiKey, string $chatId)
    {
        $this->login = $login;
        $this->apiKey = $apiKey;
        $this->chatId = $chatId;
    }

    public function sendMessage(string $title, string $message): void
    {
        // Логіка відправки повідомлення у Slack
        echo "Slack message sent to chat '{$this->chatId}' with title '{$title}' and message '{$message}'\n";
    }
}

/**
 * Адаптер SlackNotification
 *
 * Адаптує SlackSender під інтерфейс Notification
 */
class SlackNotificationAdapter implements Notification
{
    private SlackSender $slackSender;

    public function __construct(SlackSender $slackSender)
    {
        $this->slackSender = $slackSender;
    }

    public function send(string $title, string $message)
    {
        $this->slackSender->sendMessage($title, $message);
    }
}

/**
 * Клас SMSSender
 *
 * Відправляє SMS-повідомлення
 */
class SMSSender
{
    private string $phone;
    private string $sender;

    public function __construct(string $phone, string $sender)
    {
        $this->phone = $phone;
        $this->sender = $sender;
    }

    public function sendSMS(string $title, string $message): void
    {
        // Логіка відправки SMS
        echo "SMS sent to '{$this->phone}' from '{$this->sender}' with title '{$title}' and message '{$message}'\n";
    }
}

/**
 * Адаптер SMSNotificationAdapter
 *
 * Адаптує SMSSender під інтерфейс Notification
 */
class SMSNotificationAdapter implements Notification
{
    private SMSSender $smsSender;

    public function __construct(SMSSender $smsSender)
    {
        $this->smsSender = $smsSender;
    }

    public function send(string $title, string $message)
    {
        $this->smsSender->sendSMS($title, $message);
    }
}

/**
 * Демонстрація роботи
 */

// Email
$emailNotification = new EmailNotification("admin@example.com");
$emailNotification->send("Важливе повідомлення", "Тестовий email");

// Slack
$slackSender = new SlackSender("user_login", "apikey4U4U", "chat123");
$slackNotification = new SlackNotificationAdapter($slackSender);
$slackNotification->send("Повідомлення", "Повідомлення у Slack");

// SMS
$smsSender = new SMSSender("+380123456789", "MyApp");
$smsNotification = new SMSNotificationAdapter($smsSender);
$smsNotification->send("НадВажливе повідомлення", "Тестове SMS");