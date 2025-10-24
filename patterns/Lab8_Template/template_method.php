<?php

/**
 * Абстрактний клас BaseEntityUpdater
 */
abstract class BaseEntityUpdater {

    /**
     * Визначає послідовність кроків оновлення.
     */
    public final function updateEntity(array $data): array {
        $entity = $this->getEntity($data);
        $this->beforeValidationHook($entity);

        if (!$this->validate($entity)) {
            $this->onValidationFail($entity);
            return $this->response(400, "Validation failed");
        }

        $this->prepareSaveRequest($entity);
        $this->afterSaveHook($entity);

        return $this->formResponse($entity);
    }

    /**
     * Отримує об’єкт сутності для оновлення.
     */
    protected abstract function getEntity(array $data);

    /**
     * Валідатор вихідних даних.
     */
    protected abstract function validate($entity): bool;

    /**
     * Формує запит на збереження інформації.
     */
    protected abstract function prepareSaveRequest($entity): void;

    /**
     * Формує стандартну відповідь API.
     */
    protected function formResponse($entity): array {
        return [
            "code" => 200,
            "status" => "OK"
        ];
    }

    /**
     * Викликається до перевірки даних.
     */
    protected function beforeValidationHook($entity): void {
    }

    /**
     * Викликається після успішного збереження.
     */
    protected function afterSaveHook($entity): void {
    }

    /**
     * Викликається у разі помилки валідації.
     */
    protected function onValidationFail($entity): void {
    }

    /**
     * Формує стандартну відповідь.
     */
    protected function response(int $code, string $status): array {
        return [
            "code" => $code,
            "status" => $status
        ];
    }
}


/**
 * Клас ProductUpdater
 *
 * Оновлення сутності «Товар».
 * Якщо валідація не проходить, надсилає сповіщення адміністратору.
 */
class ProductUpdater extends BaseEntityUpdater {

    protected function getEntity(array $data) {
        return (object)$data;
    }

    protected function validate($entity): bool {
        return isset($entity->name) && isset($entity->price) && $entity->price > 0;
    }

    protected function prepareSaveRequest($entity): void {
    }

    protected function onValidationFail($entity): void {
        echo "Адміністратору відправлено сповіщення про невдале оновлення товару: {$entity->name}\n";
    }
}


/**
 * Клас UserUpdater\
 *
 * Оновлення сутності «Користувач».
 * Не дозволяється змінювати поле email, навіть якщо воно пройшло валідацію.
 */
class UserUpdater extends BaseEntityUpdater {

    protected function getEntity(array $data) {
        return (object)$data;
    }

    protected function validate($entity): bool {
        return isset($entity->name) && isset($entity->email);
    }

    protected function prepareSaveRequest($entity): void {
        unset($entity->email);
    }
}


/**
 * Клас OrderUpdater
 *
 * Оновлення сутності «Замовлення».
 * У відповіді повертається JSON-подання замовлення.
 */
class OrderUpdater extends BaseEntityUpdater {

    protected function getEntity(array $data) {
        return (object)$data;
    }

    protected function validate($entity): bool {
        return isset($entity->orderId);
    }

    protected function prepareSaveRequest($entity): void {
    }

    protected function formResponse($entity): array {
        return [
            "code" => 200,
            "status" => "OK",
            "data" => json_encode($entity)
        ];
    }
}


/**
 * Демонстрація роботи
 *
 * використання шаблонного методу з різними сутностями.
 */

// Дані для тестів
$productData = ["name" => "Ноутбук", "price" => -15000];
$userData = ["name" => "Вася", "email" => "test@example.com"];
$orderData = ["orderId" => 42, "status" => "оплачено"];

echo "---- Оновлення товару -----\n";
$productUpdater = new ProductUpdater();
print_r($productUpdater->updateEntity($productData));

echo "\n---- Оновлення користувача ----\n";
$userUpdater = new UserUpdater();
print_r($userUpdater->updateEntity($userData));

echo "\n---- Оновлення замовлення ----\n";
$orderUpdater = new OrderUpdater();
print_r($orderUpdater->updateEntity($orderData));

?>
