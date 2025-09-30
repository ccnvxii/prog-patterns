<?php

/**
 * Interface QueryBuilderInterface
 *
 * Спільний інтерфейс для всіх будівельників SQL-запитів.
 * Визначає методи для формування SELECT-запитів, додавання умов, обмежень та отримання готового SQL.
 */
interface QueryBuilderInterface
{
    public function select(string $table, array $fields): self;

    public function where(string $condition): self;

    public function limit(int $count): self;

    public function getSQL(): string;
}

/**
 * Class PostgresQueryBuilder
 *
 * Будівельник SQL-запитів для PostgreSQL.
 * Реалізує інтерфейс QueryBuilderInterface.
 */
class PostgresQueryBuilder implements QueryBuilderInterface
{
    private string $table = '';
    private array $fields = [];
    private string $condition = '';
    private int $limitCount = 0;

    public function select(string $table, array $fields): self
    {
        $this->table = $table;
        $this->fields = $fields;
        return $this;
    }

    public function where(string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    public function limit(int $count): self
    {
        $this->limitCount = $count;
        return $this;
    }

    public function getSQL(): string
    {
        $sql = "SELECT " . implode(", ", $this->fields) . " FROM {$this->table}";
        if ($this->condition) {
            $sql .= " WHERE {$this->condition}";
        }
        if ($this->limitCount > 0) {
            $sql .= " LIMIT {$this->limitCount}";
        }
        return $sql . "; -- PostgreSQL";
    }
}

/**
 * Class MySQLQueryBuilder
 *
 * Будівельник SQL-запитів для MySQL.
 * Реалізує інтерфейс QueryBuilderInterface.
 */
class MySQLQueryBuilder implements QueryBuilderInterface
{
    private string $table = '';
    private array $fields = [];
    private string $condition = '';
    private int $limitCount = 0;

    public function select(string $table, array $fields): self
    {
        $this->table = $table;
        $this->fields = $fields;
        return $this;
    }

    public function where(string $condition): self
    {
        $this->condition = $condition;
        return $this;
    }

    public function limit(int $count): self
    {
        $this->limitCount = $count;
        return $this;
    }

    public function getSQL(): string
    {
        $sql = "SELECT " . implode(", ", $this->fields) . " FROM {$this->table}";
        if ($this->condition) {
            $sql .= " WHERE {$this->condition}";
        }
        if ($this->limitCount > 0) {
            $sql .= " LIMIT {$this->limitCount}";
        }
        return $sql . "; -- MySQL";
    }
}

/**
 * Class SQLDirector
 *
 * Директор, який відповідає за порядок виклику методів будівельника.
 * Використовується для побудови стандартних запитів без необхідності
 * звертатися до методів select/where/limit напряму у клієнтському коді.
 */
class SQLDirector
{
    /**
     * @var QueryBuilderInterface Будівельник SQL-запитів
     */
    private QueryBuilderInterface $builder;

    /**
     * SQLDirector constructor.
     *
     * @param QueryBuilderInterface $builder Будівельник, який буде використовуватися директором
     */
    public function __construct(QueryBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    /**
     * Створює типовий запит для вибірки користувачів.
     *
     * @return string Готовий SQL-запит
     */
    public function buildSimpleUserQuery(): string
    {
        return $this->builder
            ->select("users", ["id", "name", "email"])
            ->where("id > 10")
            ->limit(5)
            ->getSQL();
    }
}

/**
 * Демонстрація роботи
 */
$pgBuilder = new PostgresQueryBuilder();
// Директор для PostgreSQL
$pgDirector = new SQLDirector($pgBuilder);
echo $pgDirector->buildSimpleUserQuery() . PHP_EOL;

$mySQLBuilder = new MySQLQueryBuilder();
// Директор для MySQL
$mySQLDirector = new SQLDirector($mySQLBuilder);
echo $mySQLDirector->buildSimpleUserQuery() . PHP_EOL;