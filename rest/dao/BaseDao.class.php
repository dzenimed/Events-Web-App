<?php

require_once dirname(__FILE__)."/../config.php";
require_once dirname(__FILE__) . "/../Database.class.php";

/*
* The main class for interaction with database
*
*  All other DAO classes should inherit this class.
*
* @author Dzenana Medjedovic
*/
class BaseDao
{
    protected $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    public function parse_order($order)
    {
        switch (substr($order, 0, 1)) {
      case '-': $order_direction = 'ASC'; break;
      case '+': $order_direction = 'DESC'; break;
      default: throw new Exception("Invalid order format. First character should be either '-' or '+' "); break;
    }

        //Filter SQL injection attacks on column name
        $order_column = trim(Database::getInstance()->quote(substr($order, 1)), "'");

        return [$order_column, $order_direction];
    }

    public function beginTransaction()
    {
      Database::getInstance()->beginTransaction();
    }
    public function commit()
    {
       Database::getInstance()->commit();
    }
    public function rollBack()
    {
       Database::getInstance()->rollBack();
    }

    protected function insert($table, $entity)
    {
        $query = "INSERT INTO ${table} (";
        foreach ($entity as $column => $value) {
            $query .=$column.", ";
        }
        $query =substr($query, 0, -2);
        $query .=") VALUES (";
        foreach ($entity as $column => $value) {
            $query .= ":".$column.", ";
        }
        $query = substr($query, 0, -2);
        $query .=")";
        echo $query;
        $stmt=  Database::getInstance()->prepare($query);
        $stmt->execute($entity); //sql injection prevention
        $entity['id'] = Database::getInstance()->lastInsertId();
        return $entity;
    }

    // Full or incremental update in any table
    protected function executeUpdate($table, $id, $entity, $id_column="id")
    {
        $query = "UPDATE ${table} SET ";
        foreach ($entity as $name => $value) {
            $query .= $name ."= :". $name. ", ";
        }
        $query = substr($query, 0, -2);
        $query .= " WHERE ${id_column} = :id";

        $stmt= Database::getInstance()->prepare($query);
        $entity['id'] = $id;
        $stmt->execute($entity);
    }

    protected function query($query, $params)
    {
        $stmt = Database::getInstance()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function query_unique($query, $params)
    {
        $results = $this->query($query, $params);
        return reset($results);
    }

    public function add($entity)
    {
        return $this->insert($this->table, $entity);
    }

    public function update($id, $entity)
    {
        $this->executeUpdate($this->table, $id, $entity);
    }

    public function delete($id){
      $query = "DELETE FROM ".$this->table_name." WHERE id=:id";
      $stmt->bindParam(':id', $id); // SQL injection prevention
      $stmt->execute();
    }

    public function get_by_id($id)
    {
        return $this->query_unique("SELECT * FROM ".$this->table." WHERE id=:id", ["id" => $id]);
    }

    public function get_all($offset = 0, $limit = 5, $order = "-id", $total=false)
    {
        list($order_column, $order_direction) = self::parse_order($order);

        if ($total) {
            return $this->query_unique("SELECT COUNT(*) AS total FROM ".$this->table, []);
        } else {
            return $this->query("SELECT * FROM ".$this->table."
                          ORDER BY ${order_column} ${order_direction}
                          LIMIT ${limit} OFFSET ${offset}", []);
        }
    }
}
