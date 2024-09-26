<?php

namespace Core;

use PDO;

class Database {
    public $connection;
    public $statement;
    private $table;
    private $selectClause = '*';
    private $joinClause = '';
    private $whereClause = '';
    private $groupClause = '';
    private $orderClause = '';
    private $limit = '';
    private $params = [];

    public function __construct($config, $username = 'root', $password = ''){
        $dsn = 'mysql:'.http_build_query($config, '', ';');
        $this->connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }

    public function beginTransaction(){
        return $this->connection->beginTransaction();
    }

    public function commit(){
        return $this->connection->commit();
    }

    public function rollBack(){
        return $this->connection->rollBack();
    }

    public function query($query, $params = []){
        $this->statement = $this->connection->prepare($query);
        $this->statement->execute($params);
        return $this;
    }

    public function find($id){
        $query = "SELECT * FROM $this->table WHERE id = ?";

        $this->query($query, [$id]);
        return $this->statement->fetch();
    }

    public function get(){
        $query = "SELECT $this->selectClause FROM $this->table $this->joinClause $this->whereClause $this->groupClause $this->orderClause $this->limit";
        return $this->query($query, $this->params)->statement->fetchAll(PDO::FETCH_OBJ);
    }

    public function first(){
        $query = "SELECT $this->selectClause FROM $this->table $this->joinClause $this->whereClause $this->groupClause $this->orderClause LIMIT 1";
        $result = $this->query($query, $this->params)->statement->fetch(PDO::FETCH_OBJ);
        $this->resetQuery();
        return $result;
    }

    public function findOrFail($id){
        $result = $this->find($id);
        if(!$result){
            abort();
        }
        return $result;
    }

    public function insert($table, $data){
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        $query = "INSERT INTO $table ($columns) VALUES ($values)";
        $params = array_values($data);

        $this->query($query, $params);

        return (object)['id' => $this->connection->lastInsertId()];
    }

    public function update($table, $data, $where){
        $setClause = implode(', ', array_map(function ($column){
            return "$column = ?";
        }, array_keys($data)));
    
        $whereClause = implode(' AND ', array_map(function ($column){
            return "$column = ?";
        }, array_keys($where)));
    
        $query = "UPDATE $table SET $setClause WHERE $whereClause";
        $params = array_merge(array_values($data), array_values($where));
    
        return $this->query($query, $params);
    }

    public function select($table, $columns = '*'){
        $this->resetQuery();

        $this->table = $table;

        if(is_array($columns) && !empty($columns)){
            $columns = implode(', ', $columns);
        }else{
            $columns = '*';
        }

        $this->selectClause = $columns;

        return $this;
    }

    public function where($conditions){
        $this->whereClause = ' WHERE ';
        $params = [];
        $firstCondition = true;

        foreach($conditions as $column => $value){
            if(!$firstCondition){
                $this->whereClause .= " AND ";
            }else{
                $firstCondition = false;
            }

            $this->whereClause .= "$column = ?";
            $params[] = $value;
        }

        $this->params = array_merge($this->params, $params);
        return $this;
    }

    public function whereNotEqual($column, $value){
        if($this->whereClause){
            $this->whereClause .= " AND $column != ?";
        }else{
            $this->whereClause = " WHERE $column != ?";
        }
    
        $this->params[] = $value;
        return $this;
    }

    public function orderBy($column, $direction = 'DESC'){
        $this->orderClause = " ORDER BY $column $direction";
        return $this;
    }

    public function delete($table, $where){
        $whereClause = implode(' AND ', array_map(function ($column){
            return "$column = ?";
        }, array_keys($where)));

        $query = "DELETE FROM $table WHERE $whereClause";
        $params = array_values($where);

        return $this->query($query, $params);
    }

    public function innerJoin($table, $first, $operator, $second){
        $this->joinClause .= " INNER JOIN $table ON $first $operator $second";
        return $this;
    }

    public function leftJoin($table, $first, $operator, $second){
        $this->joinClause .= " LEFT JOIN $table ON $first $operator $second";
        return $this;
    }

    public function groupBy($column){
        $this->groupClause = " GROUP BY $column";
        return $this;
    }

    public function truncate($table){
        $query = "TRUNCATE TABLE $table";
        return $this->query($query);
    }

    public function limit($number){
        $this->limit = " LIMIT $number";
        return $this;
    }

    public function count(){
        $query = "SELECT COUNT(*) AS total FROM $this->table $this->whereClause";
        $this->query($query, $this->params);
        $result = $this->statement->fetch(PDO::FETCH_ASSOC);
        $this->resetQuery();
        return $result['total'];
    }

    public function whereIn($column, $values){
        $placeholders = implode(',', array_fill(0, count($values), '?'));

        if($this->whereClause){
            $this->whereClause .= " AND $column IN ($placeholders)";
        }else{
            $this->whereClause = " WHERE $column IN ($placeholders)";
        }
        
        $this->params = array_merge($this->params, $values);
        return $this;
    }

    public function whereNotIn($column, $values){
        $placeholders = implode(',', array_fill(0, count($values), '?'));
    
        if($this->whereClause){
            $this->whereClause .= " AND $column NOT IN ($placeholders)";
        }else{
            $this->whereClause = " WHERE $column NOT IN ($placeholders)";
        }
    
        $this->params = array_merge($this->params, $values);
        return $this;
    }

    public function sum($column){
        $query = "SELECT SUM($column) AS total FROM $this->table $this->joinClause $this->whereClause";
        $this->query($query, $this->params);
        $result = $this->statement->fetch(PDO::FETCH_ASSOC);
        $this->resetQuery();
        return $result['total'] ?? 0;
    }

    public function whereGreater($column, $value){
        if($this->whereClause){
            $this->whereClause .= " AND $column > ?";
        }else{
            $this->whereClause = " WHERE $column > ?";
        }
    
        $this->params[] = $value;
        return $this;
    }

    public function whereGreaterOrEqual($column, $value){
        if($this->whereClause){
            $this->whereClause .= " AND $column >= ?";
        }else{
            $this->whereClause = " WHERE $column >= ?";
        }
    
        $this->params[] = $value;
        return $this;
    }

    public function whereSmallerOrEqual($column, $value){
        if($this->whereClause){
            $this->whereClause .= " AND $column <= ?";
        }else{
            $this->whereClause = " WHERE $column <= ?";
        }
    
        $this->params[] = $value;
        return $this;
    }

    public function whereSmaller($column, $value){
        if($this->whereClause){
            $this->whereClause .= " AND $column < ?";
        }else{
            $this->whereClause = " WHERE $column < ?";
        }
    
        $this->params[] = $value;
        return $this;
    }

    public function exists(){
        $query = "SELECT 1 FROM $this->table $this->joinClause $this->whereClause LIMIT 1";
        $result = $this->query($query, $this->params)->statement->fetch(PDO::FETCH_ASSOC);
        $this->resetQuery();
        return $result !== false;
    }

    /**
        *
        * @param string $column
        * @param mixed $start
        * @param mixed $end
        * @return $this
    */
    public function whereBetween($column, $start, $end){
        if($this->whereClause){
            $this->whereClause .= " AND $column BETWEEN ? AND ?";
        }else{
            $this->whereClause = " WHERE $column BETWEEN ? AND ?";
        }
        
        $this->params[] = $start;
        $this->params[] = $end;
        return $this;
    }

    private function resetQuery(){
        $this->selectClause = '*';
        $this->joinClause = '';
        $this->whereClause = '';
        $this->groupClause = '';
        $this->orderClause = '';
        $this->limit = '';
        $this->params = [];
    }
}