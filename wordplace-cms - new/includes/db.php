<?php
/**
 * Database connection and helper functions
 */

/**
 * Get database connection
 */
function getDbConnection() {
    static $conn;
    
    if ($conn === null) {
        try {
            $conn = new PDO(
                'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
                DB_USER,
                DB_PASS,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    
    return $conn;
}

/**
 * Execute a query and return the result
 */
function executeQuery($sql, $params = []) {
    try {
        $conn = getDbConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch (PDOException $e) {
        die('Query execution failed: ' . $e->getMessage());
    }
}

/**
 * Fetch all rows from a query
 */
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Fetch a single row from a query
 */
function fetchOne($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch();
}

/**
 * Insert a record and return the last insert ID
 */
function insert($table, $data) {
    $keys = array_keys($data);
    $fields = implode(', ', $keys);
    $placeholders = implode(', ', array_fill(0, count($keys), '?'));
    
    $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
    
    try {
        $conn = getDbConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_values($data));
        return $conn->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Update a record
 */
function update($table, $data, $where, $whereParams = []) {
    $sets = [];
    $params = [];
    
    foreach ($data as $key => $value) {
        $sets[] = "$key = ?";
        $params[] = $value;
    }
    
    $setClause = implode(', ', $sets);
    $sql = "UPDATE $table SET $setClause WHERE $where";
    
    try {
        $conn = getDbConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute(array_merge($params, $whereParams));
        return $stmt->rowCount();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Delete a record
 */
function delete($table, $where, $params = []) {
    $sql = "DELETE FROM $table WHERE $where";
    
    try {
        $stmt = executeQuery($sql, $params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        return false;
    }
}

