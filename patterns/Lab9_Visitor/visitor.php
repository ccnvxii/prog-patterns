<?php


/**
 * Інтерфейс Element
 */

interface Element
{
    /**
     * Метод прийняття відвідувача.
     */
    public function accept(Visitor $visitor): void;
}


/**
 * Інтерфейс Visitor
 * */
interface Visitor
{
    /**
     * Обробка компанії
     */
    public function visitCompany(Company $company): void;

    /**
     * Обробка департаменту
     */
    public function visitDepartment(Department $department): void;

    /**
     * Обробка співробітника
     */
    public function visitEmployee(Employee $employee): void;
}

/**
 * Клас Співробітник
 */
class Employee implements Element
{
    private string $position;
    private float $salary;

    public function __construct(string $position, float $salary)
    {
        $this->position = $position;
        $this->salary = $salary;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getSalary(): float
    {
        return $this->salary;
    }

    public function accept(Visitor $visitor): void
    {
        $visitor->visitEmployee($this);
    }
}

/**
 * Клас Департамент
 * містить список співробітників.
 */
class Department implements Element
{
    private string $name;
    private array $employees;

    public function __construct(string $name, array $employees)
    {
        $this->name = $name;
        $this->employees = $employees;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmployees(): array
    {
        return $this->employees;
    }

    public function accept(Visitor $visitor): void
    {
        $visitor->visitDepartment($this);
        foreach ($this->employees as $employee) {
            $employee->accept($visitor);
        }
    }
}

/**
 * Клас Компанія
 * містить департаменти.
 */
class Company implements Element
{
    private string $name;
    private array $departments;

    public function __construct(string $name, array $departments)
    {
        $this->name = $name;
        $this->departments = $departments;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDepartments(): array
    {
        return $this->departments;
    }

    public function accept(Visitor $visitor): void
    {
        $visitor->visitCompany($this);
        foreach ($this->departments as $department) {
            $department->accept($visitor);
        }
    }
}

/**
 * Відвідувач для формування "Зарплатної відомості".
 */
class SalaryReportVisitor implements Visitor
{
    private float $totalSalary = 0;

    public function visitCompany(Company $company): void
    {
        echo "Зарплатна відомість компанії: {$company->getName()}\n";
    }

    public function visitDepartment(Department $department): void
    {
        echo "\n--- Департамент: {$department->getName()} ---\n";
    }

    public function visitEmployee(Employee $employee): void
    {
        echo "Посада: {$employee->getPosition()}, Зарплата: {$employee->getSalary()} грн\n";
        $this->totalSalary += $employee->getSalary();
    }

    public function getTotalSalary(): float
    {
        return $this->totalSalary;
    }
}


/**
 * Демонстрація роботи
 */

// співробітники
$devs = [
    new Employee("Junior Developer", 25000),
    new Employee("Middle Developer", 40000),
    new Employee("Senior Developer", 60000)
];

$hr = [
    new Employee("HR Manager", 30000),
    new Employee("Recruiter", 27000)
];

// департаменти
$itDepartment = new Department("IT", $devs);
$hrDepartment = new Department("HR", $hr);

// компанія
$company = new Company("TechCorp", [$itDepartment, $hrDepartment]);

// відвідувач для звіту
$reportVisitor = new SalaryReportVisitor();

// звіт для всієї компанії
$company->accept($reportVisitor);

echo "\nЗагальна сума зарплат у компанії: {$reportVisitor->getTotalSalary()} грн\n";

// Отримуємо звіт лише для департаменту IT
echo "\n\n Звіт тільки для департаменту IT: \n";
$itDepartment->accept(new SalaryReportVisitor());
?>
