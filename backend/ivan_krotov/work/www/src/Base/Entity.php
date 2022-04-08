<?php

namespace root\Base;

use PDO;

abstract class Entity
{
    protected static string $table;
    protected static string $idName;

    public function __construct($data = null)
    {
        if (is_numeric($data) || is_int($data)) {
            $this->defineFromDb($data);
        }
        if (is_array($data)) {
            $this->defineFromArray($data);
        }
        if (is_object($data)) {
            $this->defineFromObject($data);
        }
    }

    protected function defineFromObject($data)
    {
        print_r($data);
        if (!empty($data) && is_object($data)) {
            foreach ($data as $key => $item) {
                if (property_exists($this, $key)) {
                    if(is_numeric($item)){
                        $item *= 1;
                    }
                    $this->$key = $item;
                }
            }
        }
    }

    protected function defineFromArray(array $array)
    {
        if (!empty($array)) {
            foreach ($array as $key => $item) {
                if (property_exists($this, $key)) {
                    if(is_numeric($item)){
                        $item *= 1;
                    }
                    $this->$key = $item;
                }
            }
        }
    }

    public function save(): int
    {
        $sql = Database::getInstance();
        $fields = $sql->getColumns(static::$table);
        $fieldsName = [];
        $fieldsValue = [];
        $toSave = [];

        foreach ($fields as $field) {
            $nm = $field;
            if (property_exists($this, $nm) && $this->$nm !== null) {
                if (in_array($field->type, ["int", "longint"])) {
                    $toSave[$nm] = intval($this->$nm);
                } else {
                    $toSave[$nm] = $sql->getConnection()->quote($this->$nm);
                }
                $fieldsName[] = $nm;
                $fieldsValue[] = $toSave[$nm];
            }
        }


        if (!$this->getId()) {
            $query = "INSERT INTO ".static::$table."(".implode(',', $fieldsName).") VALUES (".implode(',', $fieldsValue).")";
            $sql->getConnection()->exec($query);
            $this->setId($sql->getConnection()->lastInsertId());
        }else{
            $temp = [];

                foreach ($fieldsName as $i => $nm) {
                    $key = $nm;
                    $val = $fieldsValue[$i];
                    $temp[] = $key . "=" . $val;
                }

                $query = "UPDATE ".static::$table." SET ".implode(', ', $temp)." WHERE ".static::$idName."=".$this->getId();
                $sql->getConnection()->exec($query);

        }
        return $this->getId();
    }

    public abstract function getId(): ?int;

    protected abstract function setId(int $val): void;

    private function defineFromDb(float $id)
    {
        $sql = Database::getInstance();
        $query = sprintf('SELECT * FROM '.static::$table.' WHERE '.static::$idName.' = %d', $id);
        $res = $sql->getConnection()->query($query, PDO::FETCH_ASSOC);
        $res->execute();
        $this->defineFromArray($res->fetch(PDO::FETCH_ASSOC));
    }

}