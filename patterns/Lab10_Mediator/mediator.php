<?php

/**
 * Інтерфейс Посередника (Mediator)
 */
interface Mediator {
    /**
     * Метод, який викликається компонентами при певній дії.
     */
    public function notify(object $sender, string $event): void;
}


/**
 * Абстрактний клас Компонента (BaseComponent)
 *
 * Містить посилання на посередника для забезпечення зв’язку з іншими компонентами.
 */
abstract class BaseComponent {
    protected Mediator $mediator;

    /**
     * Встановлення посередника для компонента.
     */
    public function setMediator(Mediator $mediator): void {
        $this->mediator = $mediator;
    }
}


/**
 * Клас компонента "Дата доставки" (DeliveryDate)
 *
 * Імітує вибір дати доставки. При зміні дати повідомляє посередника, щоб оновити список доступних часових проміжків.
 */
class DeliveryDate extends BaseComponent {
    public function selectDate(string $date): void {
        echo "Дата доставки обрана: $date  \n";
        $this->mediator->notify($this, "dateChanged");
    }
}


/**
 * Клас компонента "Проміжок часу" (DeliveryTime)
 *
 * Представляє список доступних часових проміжків для обраної дати.
 */
class DeliveryTime extends BaseComponent {
    public function updateAvailableSlots(array $slots): void {
        echo "Доступні часові проміжки: " . implode(", ", $slots) . "\n";
    }
}


/**
 * Клас компонента "Отримувач інша особа" (RecipientOption)
 *
 * Відповідає за керування станом чекбоксу, який визначає, чи потрібно заповнювати дані іншого отримувача.
 */
class RecipientOption extends BaseComponent {
    public bool $isOtherPerson = false;

    /**
     * Перемикає стан чекбоксу і повідомляє посередника.
     */
    public function toggleOption(bool $value): void {
        $this->isOtherPerson = $value;
        echo "Отримувач інша особа: " . ($value ? "так" : "ні") . "\n";
        $this->mediator->notify($this, "recipientChanged");
    }
}


/**
 * Клас компонента "Поля отримувача" (RecipientFields)
 *
 * Містить поля Ім’я та Телефон, які стають обов’язковими, якщо обрано “Отримувач інша особа”.
 */
class RecipientFields extends BaseComponent {
    public function setVisible(bool $visible): void {
        echo "Поля Ім’я та Телефон " . ($visible ? "відображено" : "приховано") . "\n";
    }
}


/**
 * Клас компонента "Самовивіз" (PickupOption)
 *
 * Визначає, чи клієнт бажає самостійно забрати букет з магазину.
 * Якщо так, елементи доставки стають неактивними.
 */
class PickupOption extends BaseComponent {
    public bool $isPickup = false;

    /**
     * Змінює стан опції самовивозу та повідомляє посередника.
     */
    public function togglePickup(bool $value): void {
        $this->isPickup = $value;
        echo "Самовивіз: " . ($value ? "так" : "ні") . "\n" ;
        $this->mediator->notify($this, "pickupChanged");
    }
}


/**
 * Конкретний Посередник (OrderFormMediator)
 *
 * Координує взаємодію між усіма компонентами форми замовлення.
 */
class OrderFormMediator implements Mediator {
    private DeliveryDate $date;
    private DeliveryTime $time;
    private RecipientOption $recipientOption;
    private RecipientFields $recipientFields;
    private PickupOption $pickupOption;

    public function __construct(
        DeliveryDate $date,
        DeliveryTime $time,
        RecipientOption $recipientOption,
        RecipientFields $recipientFields,
        PickupOption $pickupOption
    ) {
        $this->date = $date;
        $this->time = $time;
        $this->recipientOption = $recipientOption;
        $this->recipientFields = $recipientFields;
        $this->pickupOption = $pickupOption;

        // Встановлення посередника для кожного компонента
        $date->setMediator($this);
        $time->setMediator($this);
        $recipientOption->setMediator($this);
        $recipientFields->setMediator($this);
        $pickupOption->setMediator($this);
    }

    /**
     * Основний метод обробки подій між компонентами.
     */
    public function notify(object $sender, string $event): void {
        switch ($event) {
            case "dateChanged":
                // Зміна дати впливає на доступні часові проміжки
                $this->time->updateAvailableSlots(["10:00–12:00", "12:00–14:00", "16:00–18:00"]);
                break;

            case "recipientChanged":
                // Якщо отримувач інша особа, показати додаткові поля
                $this->recipientFields->setVisible($this->recipientOption->isOtherPerson);
                break;

            case "pickupChanged":
                // Якщо самовивіз, деактивуємо елементи доставки
                if ($this->pickupOption->isPickup) {
                    echo "Усі елементи доставки деактивовано. \n";
                } else {
                    echo "Елементи доставки знову активні. \n";
                }
                break;
        }
    }
}


/**
 * Демонстрація роботи
 *
 * вибір різних стратегій доставки та розрахунок їхньої вартості.
 */

$date = new DeliveryDate();
$time = new DeliveryTime();
$recipientOption = new RecipientOption();
$recipientFields = new RecipientFields();
$pickupOption = new PickupOption();

$mediator = new OrderFormMediator($date, $time, $recipientOption, $recipientFields, $pickupOption);

// Користувач обирає дату доставки
$date->selectDate("25.10.2025");

// Користувач зазначає, що отримувач — інша особа
$recipientOption->toggleOption(true);

// Користувач вирішує забрати букет самостійно
$pickupOption->togglePickup(true);
