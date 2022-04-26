<?php

namespace xDeanBoy\Models;

use xDeanBoy\Db\Db;

abstract class ActiveRecordEntity
{
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value): void
    {
        $nameToCamelCase = $this->underscoreToCamelCase($name);

        $this->$nameToCamelCase = $value;
    }

    /**
     * @return string
     */
    abstract protected static function getTableName(): string;

    /**
     * @param string $source
     * @return string
     */
    private function camelCaseToUnderscore(string $source): string
    {
        //camelCase => camel_case
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    /**
     * @param string $source
     * @return string
     */
    private function underscoreToCamelCase(string $source): string
    {
        //camel_case => camelCase
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    /**
     * @return array
     */
    private function mapToDbProperties(): array
    {
        $reflection = new \ReflectionObject($this);
        $reflectionProperties = $reflection->getProperties();

        $mappedProperties = [];
        foreach ($reflectionProperties as $property) {
            $propertyName = $property->getName();
            $propertyNameToDb = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyNameToDb] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    /**
     * @return void
     */
    public function save(): void
    {
        $mappedProperties = $this->mapToDbProperties();

        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    /**
     * @param array $mappedProperties
     * @return void
     */
    private function insert(array $mappedProperties): void
    {
        // INSERT INTO tableName (col1, col2) VALUES (:par1, :par2);
        //[:par1=>val1]

        //properties without created_at and id
        $filteredProperties = array_filter($mappedProperties);

        $columnsToDb = [];
        $paramsToDb = [];
        $paramsToValue = [];
        $index = 1;

        foreach ($filteredProperties as $columns => $value) {
            $param = ':param' . $index++;
            $paramsToDb[] = $param;
            $columnsToDb[] = $columns;
            $paramsToValue[$param] = $value;
        }

        $columnsForSql = implode(', ', $columnsToDb);
        $paramsForSql = implode(', ', $paramsToDb);

        $db = Db::getInstance();
        $sql = 'INSERT INTO ' . static::getTableName() . ' ( ' . $columnsForSql . ' ) VALUES ( ' . $paramsForSql . ' );';

        $db->query($sql, $paramsToValue, static::class);
        $this->id = $db->getLastInsertId();
    }

    /**
     * @param array $mappedProperties
     * @return void
     */
    private function update(array $mappedProperties): void
    {
        // UPDATE tableName SET (col1 = val1, col2 = val2);
        //[val1=>:col1]

        $params = [];
        $paramsForSet = [];

        foreach ($mappedProperties as $columnName => $value) {
            $param = ':' . $columnName;
            $params[$param] = $value;
            $paramsForSet[] = $columnName . ' = ' . $param;
        }

        $paramsForSql = implode(', ', $paramsForSet);

        $db = Db::getInstance();
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . $paramsForSql . ' WHERE id = :id;';
        $db->query($sql, $params, static::class);
    }

    /**
     * @return array|null
     */
    public static function findAll(): ?array
    {
        $db = Db::getInstance();

        $result = $db->query('SELECT * FROM `' . static::getTableName() . '`;',
            [],
            static::class);

        if ($result === []) {
            return null;
        }

        return $result;
    }

    /**
     * @param int $id
     * @return self|null
     */
    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();

        $result = $db->query('SELECT * FROM `' . static::getTableName() . '` WHERE id = :id;',
            [':id' => $id],
            static::class);

        return $result ? $result[0] : null;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return self|null
     */
    public static function findOneByColumn(string $columnName, $value): ?self
    {
        $db = Db::getInstance();

        $sql = 'SELECT * FROM ' . static::getTableName() . ' WHERE ' . $columnName . ' = :value LIMIT 1;';

        $result = $db->query($sql,
            [':value' => $value],
            static::class);

        return $result ? $result[0] : null;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return array|null
     */
    public static function findAllByColumn(string $columnName, $value): ?array
    {
        $db = Db::getInstance();

        $sql = 'SELECT * FROM ' . static::getTableName() . ' WHERE ' . $columnName . ' = :value;';
        $result = $db->query($sql, [':value' => $value], static::class);

        if (empty($result)) {
            return null;
        }

        return $result;
    }

    /**
     * @return void
     */
    public function delete(): void
    {
        $db = Db::getInstance();

        $sql = 'DELETE FROM ' . static::getTableName() . ' WHERE id = :id;';
        $db->query($sql,
            [':id' => $this->id],
        static::class);
    }

    /**
     * @param string $columnByOrder
     * @return array|null
     */
    public static function findAllByOrderArrayDesc(array $columnsByOrder): ?array
    {
        $db = Db::getInstance();

        $orderByForSql = implode(' DESC , ', $columnsByOrder);

        $sql = 'SELECT * FROM ' . static::getTableName() . ' ORDER BY ' . $orderByForSql . ' DESC ;';

        $result = $db->query($sql, [], static::class);

        if ($result === []) {
            return null;
        }

        return $result;
    }
}