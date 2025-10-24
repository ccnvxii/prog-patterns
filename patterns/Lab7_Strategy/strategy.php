<?php

/**
 * Інтерфейс DeliveryStrategy
 */
interface DeliveryStrategy {

    /**
     * Обчислює вартість доставки замовлення.
     */
    public function calculateCost(float $distance, float $weight): float;
}


/**
 * Клас PickupStrategy (Стратегія самовивозу)
 *
 * Реалізує доставку, коли клієнт сам забирає замовлення.
 * Вартість завжди дорівнює 0.
 */
class PickupStrategy implements DeliveryStrategy {

    public function calculateCost(float $distance, float $weight): float {
        // для самовивозу доставка безкоштовна
        return 0.0;
    }
}


/**
 * Клас ExternalDeliveryStrategy (Зовнішня служба доставки)
 *
 * Імітує стратегію, де доставка виконується сторонньою компанією.
 * Вартість залежить від ваги та відстані, із додатковим коефіцієнтом.
 */
class ExternalDeliveryStrategy implements DeliveryStrategy {

    public function calculateCost(float $distance, float $weight): float {
        return ($distance * 5) + ($weight * 3);
    }
}


/**
 * Клас InternalDeliveryStrategy (Власна служба доставки)
 *
 * Реалізує доставку компанією-власником додатку.
 * Має нижчу ціну за кілометр, але фіксовану базову оплату.
 */
class InternalDeliveryStrategy implements DeliveryStrategy {

    public function calculateCost(float $distance, float $weight): float {
        $baseFee = 20; // базова оплата
        return $baseFee + ($distance * 3) + ($weight * 2);
    }
}


/**
 * Клас DeliveryContext (Контекст)
 *
 * Керує вибором та використанням стратегії доставки.
 * Клієнт може динамічно змінювати спосіб доставки без зміни логіки програми.
 */
class DeliveryContext {
    private DeliveryStrategy $strategy;

    /**
     * Встановлює обрану користувачем стратегію доставки.
     */
    public function setStrategy(DeliveryStrategy $strategy): void {
        $this->strategy = $strategy;
    }

    /**
     * Виконує розрахунок вартості доставки згідно з обраною стратегією.
     */
    public function calculateDeliveryCost(float $distance, float $weight): float {
        return $this->strategy->calculateCost($distance, $weight);
    }
}


/**
 * Демонстрація роботи
 *
 * вибір різних стратегій доставки та розрахунок їхньої вартості.
 */

// контекст
$context = new DeliveryContext();

// параметри замовлення
$distance = 12.5;
$weight = 3.2;

echo "--- Розрахунок вартості доставки ----\n\n";

// самовивіз
$context->setStrategy(new PickupStrategy());
echo "Самовивіз: " . $context->calculateDeliveryCost($distance, $weight) . " грн\n";

// зовнішня служба доставки
$context->setStrategy(new ExternalDeliveryStrategy());
echo "Зовнішня служба доставки: " . $context->calculateDeliveryCost($distance, $weight) . " грн\n";

// власна служба доставки
$context->setStrategy(new InternalDeliveryStrategy());
echo "Власна служба доставки: " . $context->calculateDeliveryCost($distance, $weight) . " грн\n";

?>
