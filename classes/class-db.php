<?php

/**
 * Db.php
 * A database helper class for performing secure MySQL operations using MySQLi.
 * Assumes DB_HOST, DB_PORT, DB_USER, DB_PASS, and DB_NAME are defined as constants.
 */

namespace Pay;

class Db
{
    /**
     * @var mysqli
     */
    private $mysqli;

    /**
     * Constructor: Initializes the MySQLi connection using mysqli_real_connect.
     *
     * @throws Exception if the connection fails.
     */
    public function __construct()
    {
        $this->mysqli = mysqli_init();
        if (!$this->mysqli) {
            throw new Exception('MySQLi initialization failed.');
        }
        // Set connection options if needed here (e.g., timeouts)
        if (
            !mysqli_real_connect(
                $this->mysqli,
                DB_HOST,
                DB_USER,
                DB_PASS,
                DB_NAME,
                DB_PORT
            )
        ) {
            throw new Exception('Database connection failed: ' . mysqli_connect_error());
        }
        // Set charset to utf8mb4
        if (!$this->mysqli->set_charset('utf8mb4')) {
            throw new Exception('Error loading character set utf8mb4: ' . $this->mysqli->error);
        }
    }

    /**
     * Validates an SQL identifier (table or column name).
     *
     * @param string $name The identifier to validate.
     * @return string The validated identifier.
     * @throws Exception if the identifier is invalid.
     */
    private function validate_identifier($name)
    {
        if (!preg_match('/^[a-zA-Z0-9_]{1,64}$/', $name)) {
            throw new Exception("Invalid identifier: $name. Only alphanumeric characters and underscore are allowed.");
        }
        return $name;
    }

    /**
     * Validates column names.
     *
     * @param mixed $columns Array of column names or a string.
     * @return array Array of validated column names.
     */
    private function validate_columns($columns)
    {
        if (is_array($columns)) {
            $validated = [];
            foreach ($columns as $col) {
                if ($col === '*') {
                    $validated[] = $col;
                } else {
                    $validated[] = $this->validate_identifier($col);
                }
            }
            return $validated;
        }
        return ['*'];
    }

    /**
     * Formats a value for SQL queries (used in table definitions).
     *
     * @param mixed $value The value to format.
     * @return string The formatted value.
     */
    private function format_value($value)
    {
        if (is_null($value)) {
            return 'NULL';
        }
        if (is_array($value)) {
            $formatted = array_map([$this, 'format_value'], $value);
            return '(' . implode(',', $formatted) . ')';
        }
        if (is_numeric($value)) {
            return (string)$value;
        }
        return "'" . addslashes($value) . "'";
    }

    /**
     * Prepares the WHERE clause and corresponding values.
     *
     * @param array $where Associative array of conditions.
     * @return array Array with 'sql' (string) and 'values' (array).
     */
    private function prepare_where_conditions($where)
    {
        if (empty($where) || !is_array($where)) {
            return [
                    'sql'    => '',
                    'values' => [],
                   ];
        }
        $result = $this->build_condition($where);
        $sql = $result['sql'];
        $values = $result['values'];
        if ($sql !== '') {
            $sql = ' WHERE ' . $sql;
        }
        return [
                'sql'    => $sql,
                'values' => $values,
               ];
    }

    /**
     * Recursively builds a condition string from an array.
     *
     * @param array $conditions The conditions array.
     * @return array Array with 'sql' and 'values'.
     */
    private function build_condition($conditions)
    {
        $values = [];
        $parts = [];

        // Handle $or operator
        if (isset($conditions['$or']) && is_array($conditions['$or'])) {
            $or_parts = [];
            foreach ($conditions['$or'] as $cond) {
                $res = $this->build_condition($cond);
                $or_parts[] = '(' . $res['sql'] . ')';
                $values = array_merge($values, $res['values']);
            }
            $parts[] = '(' . implode(' OR ', $or_parts) . ')';
            unset($conditions['$or']);
        }

        // Handle $and operator
        if (isset($conditions['$and']) && is_array($conditions['$and'])) {
            $and_parts = [];
            foreach ($conditions['$and'] as $cond) {
                $res = $this->build_condition($cond);
                $and_parts[] = '(' . $res['sql'] . ')';
                $values = array_merge($values, $res['values']);
            }
            $parts[] = '(' . implode(' AND ', $and_parts) . ')';
            unset($conditions['$and']);
        }

        // Handle remaining conditions
        $res = $this->build_where_clause($conditions);
        if ($res['sql']) {
            $parts[] = $res['sql'];
            $values = array_merge($values, $res['values']);
        }

        return [
                'sql'    => implode(' AND ', $parts),
                'values' => $values,
               ];
    }

    /**
     * Builds a WHERE clause for simple conditions.
     *
     * @param array $conditions Conditions array.
     * @return array Array with 'sql' and 'values'.
     */
    private function build_where_clause($conditions)
    {
        $parts = [];
        $values = [];
        foreach ($conditions as $key => $value) {
            if (strpos($key, '$') === 0) {
                continue;
            }
            $this->validate_identifier($key);
            if (is_array($value)) {
                // Check if associative (operators) or simple (IN clause)
                if (array_keys($value) !== range(0, count($value) - 1)) {
                    $operators = [
                                  '$gt'      => '>',
                                  '$gte'     => '>=',
                                  '$lt'      => '<',
                                  '$lte'     => '<=',
                                  '$ne'      => '!=',
                                  '$like'    => 'LIKE',
                                  '$notLike' => 'NOT LIKE',
                                  '$in'      => 'IN',
                                  '$notIn'   => 'NOT IN',
                                 ];
                    foreach ($value as $op => $operand) {
                        if (!isset($operators[$op])) {
                            continue;
                        }
                        $operator = $operators[$op];
                        if ($operator === 'IN' || $operator === 'NOT IN') {
                            $operand_array = is_array($operand) ? $operand : [$operand];
                            $placeholders = implode(', ', array_fill(0, count($operand_array), '?'));
                            $parts[] = "$key $operator ($placeholders)";
                            $values = array_merge($values, $operand_array);
                        } else {
                            $parts[] = "$key $operator ?";
                            $values[] = $operand;
                        }
                    }
                } else {
                    $placeholders = implode(', ', array_fill(0, count($value), '?'));
                    $parts[] = "$key IN ($placeholders)";
                    $values = array_merge($values, $value);
                }
            } elseif (is_null($value)) {
                $parts[] = "$key IS NULL";
            } else {
                $parts[] = "$key = ?";
                $values[] = $value;
            }
        }
        return [
                'sql'    => implode(' AND ', $parts),
                'values' => $values,
               ];
    }

    /**
     * Prepares and executes a query with bound parameters.
     *
     * @param string $sql    The SQL query.
     * @param array  $values Array of values to bind.
     * @return mysqli_stmt The executed statement.
     * @throws Exception if preparation or execution fails.
     */
    private function prepare_and_execute($sql, $values = [])
    {
        $stmt = $this->mysqli->prepare($sql);
        if (!$stmt) {
            throw new Exception('Prepare failed: ' . $this->mysqli->error);
        }
        if (!empty($values)) {
            $types = '';
            $refs = [];
            foreach ($values as $value) {
                if (is_int($value)) {
                    $types .= 'i';
                } elseif (is_float($value)) {
                    $types .= 'd';
                } else {
                    $types .= 's';
                }
                $refs[] = $value;
            }
            $bind_params = [];
            $bind_params[] = $types;
            // Use references
            foreach ($refs as $key => $ref) {
                $bind_params[] = &$refs[$key];
            }
            if (!call_user_func_array([$stmt, 'bind_param'], $bind_params)) {
                throw new Exception('Binding parameters failed: ' . $stmt->error);
            }
        }
        if (!$stmt->execute()) {
            throw new Exception('Execute failed: ' . $stmt->error);
        }
        return $stmt;
    }

    /**
     * Executes a SELECT query.
     *
     * @param string $table   Table name.
     * @param mixed  $columns Array of columns or "*" (default: ["*"]).
     * @param array  $where   Associative array of WHERE conditions.
     * @param array  $options Options like 'limit', 'offset', 'order_by', and 'order'.
     * @return array Query result as an array of records.
     * @throws Exception if the query fails.
     */
    public function select_data($table, $columns = ['*'], $where = [], $options = [])
    {
        $this->validate_identifier($table);
        $validated_columns = $this->validate_columns($columns);
        $cols = implode(', ', $validated_columns);
        $where_result = $this->prepare_where_conditions($where);
        $sql = "SELECT $cols FROM $table" . $where_result['sql'];
        $values = $where_result['values'];

        if (isset($options['order_by'])) {
            $this->validate_identifier($options['order_by']);
            $order = (isset($options['order']) && strtoupper($options['order']) === 'DESC') ? 'DESC' : 'ASC';
            $sql .= ' ORDER BY ' . $options['order_by'] . ' ' . $order;
        }

        if (isset($options['limit'])) {
            $sql .= ' LIMIT ?';
            $values[] = $options['limit'];
            if (isset($options['offset'])) {
                $sql .= ' OFFSET ?';
                $values[] = $options['offset'];
            }
        }

        $stmt = $this->prepare_and_execute($sql, $values);
        $result = $stmt->get_result();
        if (!$result) {
            throw new Exception('Getting result set failed: ' . $stmt->error);
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Executes an INSERT query (supports single or multiple records).
     *
     * @param string $table Table name.
     * @param mixed  $data  Associative array or an array of associative arrays.
     * @return array Array with 'insert_id' and 'affected_rows'.
     * @throws Exception if the query fails.
     */
    public function insert_data($table, $data)
    {
        $this->validate_identifier($table);
        $records = [];
        if (isset($data[0]) && is_array($data[0])) {
            $records = $data;
        } else {
            $records[] = $data;
        }
        if (count($records) === 0) {
            return [
                    'insert_id'     => null,
                    'affected_rows' => 0,
                   ];
        }
        $columns = array_keys($records[0]);
        foreach ($columns as $col) {
            $this->validate_identifier($col);
        }
        $placeholders = '(' . implode(', ', array_fill(0, count($columns), '?')) . ')';
        $values_list = implode(', ', array_fill(0, count($records), $placeholders));
        $sql = "INSERT INTO $table (" . implode(', ', $columns) . ') VALUES ' . $values_list;
        $flat_values = [];
        foreach ($records as $record) {
            foreach ($columns as $col) {
                $flat_values[] = $record[$col];
            }
        }
        $stmt = $this->prepare_and_execute($sql, $flat_values);
        return [
                'insert_id'     => $this->mysqli->insert_id,
                'affected_rows' => $stmt->affected_rows,
               ];
    }

    /**
     * Executes an UPDATE query.
     *
     * @param string $table Table name.
     * @param array  $data  Associative array of column-value pairs.
     * @param array  $where Associative array of WHERE conditions.
     * @return array Array with 'affected_rows'.
     * @throws Exception if the query fails.
     */
    public function update_data($table, $data, $where = [])
    {
        $this->validate_identifier($table);
        $updates = [];
        $values = [];
        foreach ($data as $key => $value) {
            $this->validate_identifier($key);
            $updates[] = "$key = ?";
            $values[] = $value;
        }
        $where_result = $this->prepare_where_conditions($where);
        $values = array_merge($values, $where_result['values']);
        $sql = "UPDATE $table SET " . implode(', ', $updates) . $where_result['sql'];
        $stmt = $this->prepare_and_execute($sql, $values);
        return ['affected_rows' => $stmt->affected_rows];
    }

    /**
     * Executes a DELETE query.
     *
     * @param string $table   Table name.
     * @param array  $where   Associative array of WHERE conditions.
     * @param array  $options Options like 'limit'.
     * @return array Array with 'affected_rows'.
     * @throws Exception if the query fails or no WHERE conditions are provided.
     */
    public function delete_data($table, $where = [], $options = [])
    {
        $this->validate_identifier($table);
        $where_result = $this->prepare_where_conditions($where);
        if (empty($where_result['sql'])) {
            throw new Exception('DELETE operation requires WHERE conditions');
        }
        $sql = "DELETE FROM $table" . $where_result['sql'];
        $values = $where_result['values'];
        if (isset($options['limit'])) {
            $sql .= ' LIMIT ?';
            $values[] = $options['limit'];
        }
        $stmt = $this->prepare_and_execute($sql, $values);
        return ['affected_rows' => $stmt->affected_rows];
    }

    /**
     * Executes an UPSERT operation (INSERT ... ON DUPLICATE KEY UPDATE).
     *
     * @param string $table       Table name.
     * @param array  $data        Associative array of column-value pairs.
     * @param array  $unique_keys Array of unique key column names.
     * @return array Array with 'affected_rows'.
     * @throws Exception if the query fails.
     */
    public function upsert_data($table, $data, $unique_keys = [])
    {
        $this->validate_identifier($table);
        $columns = array_keys($data);
        foreach ($columns as $col) {
            $this->validate_identifier($col);
        }
        foreach ($unique_keys as $key) {
            $this->validate_identifier($key);
        }
        $placeholders = implode(', ', array_fill(0, count($columns), '?'));
        $update_clauses = [];
        foreach ($columns as $col) {
            if (!in_array($col, $unique_keys)) {
                $update_clauses[] = "$col = VALUES($col)";
            }
        }
        $sql = "INSERT INTO $table (" . implode(', ', $columns) . ") VALUES ($placeholders) ON DUPLICATE KEY UPDATE " . implode(', ', $update_clauses);
        $stmt = $this->prepare_and_execute($sql, array_values($data));
        return ['affected_rows' => $stmt->affected_rows];
    }

    /**
     * Formats a column definition for CREATE/ALTER TABLE queries.
     *
     * @param array $column Column definition options.
     * @return string The formatted column definition.
     */
    private function format_column_definition($column)
    {
        $type = strtoupper($column['type']);
        $parts = [];
        $parts[] = $type;
        if (isset($column['length'])) {
            $parts[] = '(' . $column['length'] . ')';
        }
        if (!empty($column['unsigned'])) {
            $parts[] = 'UNSIGNED';
        }
        if (!empty($column['zerofill'])) {
            $parts[] = 'ZEROFILL';
        }
        if (!empty($column['binary'])) {
            $parts[] = 'BINARY';
        }
        if (!empty($column['charset'])) {
            $parts[] = 'CHARACTER SET ' . $column['charset'];
        }
        if (!empty($column['collate'])) {
            $parts[] = 'COLLATE ' . $column['collate'];
        }
        if (isset($column['nullable']) && $column['nullable'] === false) {
            $parts[] = 'NOT NULL';
        }
        if (array_key_exists('default', $column)) {
            if ($column['default'] === null) {
                $parts[] = 'DEFAULT NULL';
            } elseif ($column['default'] === CURRENT_TIMESTAMP || $column['default'] === 'CURRENT_TIMESTAMP') {
                // Handle timestamp default without quotes
                $parts[] = 'DEFAULT CURRENT_TIMESTAMP';
            } elseif (is_string($column['default']) && (
                strpos($column['default'], 'CURRENT_TIMESTAMP') !== false ||
                strpos($column['default'], '()') !== false
            )) {
                // Handle function calls like CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP without quotes
                $parts[] = 'DEFAULT ' . $column['default'];
            } else {
                $parts[] = 'DEFAULT ' . $this->format_value($column['default']);
            }
        }
        if (!empty($column['autoIncrement'])) {
            $parts[] = 'AUTO_INCREMENT';
        }
        if (!empty($column['unique'])) {
            $parts[] = 'UNIQUE';
        }
        if (!empty($column['primary'])) {
            $parts[] = 'PRIMARY KEY';
        }
        if (!empty($column['comment'])) {
            $parts[] = 'COMMENT ' . $this->format_value($column['comment']);
        }
        return implode(' ', $parts);
    }

    /**
     * Creates a new database table.
     *
     * @param string $table_name Table name.
     * @param array  $options    Table creation options including 'columns', 'indexes', 'foreignKeys', 'engine', 'charset', 'collate', 'comment', and 'ifNotExists'.
     * @return array Array indicating success.
     * @throws Exception if the query fails.
     */
    public function create_new_table($table_name, $options)
    {
        $this->validate_identifier($table_name);
        $columns      = isset($options['columns']) ? $options['columns'] : [];
        $indexes      = isset($options['indexes']) ? $options['indexes'] : [];
        $foreign_keys = isset($options['foreignKeys']) ? $options['foreignKeys'] : [];
        $engine       = isset($options['engine']) ? $options['engine'] : 'InnoDB';
        $charset      = isset($options['charset']) ? $options['charset'] : 'utf8mb4';
        $collate      = isset($options['collate']) ? $options['collate'] : 'utf8mb4_unicode_ci';
        $comment      = isset($options['comment']) ? $options['comment'] : '';
        $if_not_exists = isset($options['ifNotExists']) ? $options['ifNotExists'] : true;

        foreach ($columns as $col_name => $def) {
            $this->validate_identifier($col_name);
        }
        foreach ($indexes as $idx => $def) {
            $this->validate_identifier($idx);
        }
        foreach ($foreign_keys as $fk => $def) {
            $this->validate_identifier($fk);
        }

        $column_defs = [];
        foreach ($columns as $name => $def) {
            $column_defs[] = "$name " . $this->format_column_definition($def);
        }

        $index_defs = [];
        foreach ($indexes as $name => $def) {
            $type = isset($def['type']) ? strtoupper($def['type']) : '';
            $idx_columns = is_array($def['columns']) ? $def['columns'] : [$def['columns']];
            $index_defs[] = ($type ? $type . ' ' : '') . "INDEX $name (" . implode(', ', $idx_columns) . ')';
        }

        $fk_defs = [];
        foreach ($foreign_keys as $name => $def) {
            $fk_columns  = is_array($def['columns']) ? $def['columns'] : [$def['columns']];
            $ref_columns = is_array($def['references']['columns']) ? $def['references']['columns'] : [$def['references']['columns']];
            $fk = "CONSTRAINT $name FOREIGN KEY (" . implode(', ', $fk_columns) . ') REFERENCES ' . $def['references']['table'] . ' (' . implode(', ', $ref_columns) . ')';
            if (isset($def['onDelete'])) {
                $fk .= ' ON DELETE ' . $def['onDelete'];
            }
            if (isset($def['onUpdate'])) {
                $fk .= ' ON UPDATE ' . $def['onUpdate'];
            }
            $fk_defs[] = $fk;
        }

        $all_defs = array_merge($column_defs, $index_defs, $fk_defs);
        $query = 'CREATE TABLE ' . ($if_not_exists ? 'IF NOT EXISTS ' : '') . "$table_name (\n" . implode(",\n", $all_defs) . "\n) ENGINE = $engine DEFAULT CHARSET = $charset COLLATE = $collate";
        if ($comment !== '') {
            $query .= ' COMMENT = ' . $this->format_value($comment);
        }
        $stmt = $this->prepare_and_execute($query);
        return ['result' => true];
    }

    /**
     * Alters an existing database table.
     *
     * @param string $table_name Table name.
     * @param array  $options    Options for alteration including 'addColumns', 'modifyColumns', 'dropColumns', 'addIndexes', 'dropIndexes', 'addForeignKeys', 'dropForeignKeys', 'engine', 'charset', 'collate', and 'comment'.
     * @return array Array indicating success.
     * @throws Exception if the query fails.
     */
    public function alter_existing_table($table_name, $options)
    {
        $this->validate_identifier($table_name);
        $add_columns       = isset($options['addColumns']) ? $options['addColumns'] : [];
        $modify_columns    = isset($options['modifyColumns']) ? $options['modifyColumns'] : [];
        $drop_columns      = isset($options['dropColumns']) ? $options['dropColumns'] : [];
        $add_indexes       = isset($options['addIndexes']) ? $options['addIndexes'] : [];
        $drop_indexes      = isset($options['dropIndexes']) ? $options['dropIndexes'] : [];
        $add_foreign_keys  = isset($options['addForeignKeys']) ? $options['addForeignKeys'] : [];
        $drop_foreign_keys = isset($options['dropForeignKeys']) ? $options['dropForeignKeys'] : [];
        $engine            = isset($options['engine']) ? $options['engine'] : null;
        $charset           = isset($options['charset']) ? $options['charset'] : null;
        $collate           = isset($options['collate']) ? $options['collate'] : null;
        $comment           = isset($options['comment']) ? $options['comment'] : null;

        foreach ($add_columns as $col => $def) {
            $this->validate_identifier($col);
        }
        foreach ($modify_columns as $col => $def) {
            $this->validate_identifier($col);
        }
        foreach ($drop_columns as $col) {
            $this->validate_identifier($col);
        }

        $alter_clauses = [];

        foreach ($add_columns as $name => $def) {
            $alter_clauses[] = "ADD COLUMN $name " . $this->format_column_definition($def);
        }
        foreach ($modify_columns as $name => $def) {
            $alter_clauses[] = "MODIFY COLUMN $name " . $this->format_column_definition($def);
        }
        foreach ($drop_columns as $column) {
            $alter_clauses[] = "DROP COLUMN $column";
        }
        foreach ($add_indexes as $name => $def) {
            $type = isset($def['type']) ? strtoupper($def['type']) : '';
            $idx_columns = is_array($def['columns']) ? $def['columns'] : [$def['columns']];
            $alter_clauses[] = 'ADD ' . ($type ? $type . ' ' : '') . "INDEX $name (" . implode(', ', $idx_columns) . ')';
        }
        foreach ($drop_indexes as $index) {
            $alter_clauses[] = "DROP INDEX $index";
        }
        foreach ($add_foreign_keys as $name => $def) {
            $fk_columns  = is_array($def['columns']) ? $def['columns'] : [$def['columns']];
            $ref_columns = is_array($def['references']['columns']) ? $def['references']['columns'] : [$def['references']['columns']];
            $fk = "ADD CONSTRAINT $name FOREIGN KEY (" . implode(', ', $fk_columns) . ') REFERENCES ' . $def['references']['table'] . ' (' . implode(', ', $ref_columns) . ')';
            if (isset($def['onDelete'])) {
                $fk .= ' ON DELETE ' . $def['onDelete'];
            }
            if (isset($def['onUpdate'])) {
                $fk .= ' ON UPDATE ' . $def['onUpdate'];
            }
            $alter_clauses[] = $fk;
        }
        foreach ($drop_foreign_keys as $fk) {
            $alter_clauses[] = "DROP FOREIGN KEY $fk";
        }
        if ($engine) {
            $alter_clauses[] = "ENGINE = $engine";
        }
        if ($charset) {
            $alter_clauses[] = "DEFAULT CHARSET = $charset";
        }
        if ($collate) {
            $alter_clauses[] = "COLLATE = $collate";
        }
        if ($comment) {
            $alter_clauses[] = 'COMMENT = ' . $this->format_value($comment);
        }
        $sql = "ALTER TABLE $table_name " . implode(",\n", $alter_clauses);
        $stmt = $this->prepare_and_execute($sql);
        return ['result' => true];
    }

    /**
     * Drops a database table.
     *
     * @param string $table_name Table name.
     * @param array  $options    Options including 'ifExists', 'temporary', and 'cascade'.
     * @return array Array indicating success.
     * @throws Exception if the query fails.
     */
    public function drop_existing_table($table_name, $options = [])
    {
        $this->validate_identifier($table_name);
        $if_exists = isset($options['ifExists']) ? $options['ifExists'] : true;
        $temporary = isset($options['temporary']) ? $options['temporary'] : false;
        $cascade   = isset($options['cascade']) ? $options['cascade'] : false;
        $sql = 'DROP ' . ($temporary ? 'TEMPORARY ' : '') . 'TABLE ' . ($if_exists ? 'IF EXISTS ' : '') . "$table_name" . ($cascade ? ' CASCADE' : '');
        $stmt = $this->prepare_and_execute($sql);
        return ['result' => true];
    }

    /**
     * Sets the timezone for the current database connection.
     *
     * @param string $timezone The timezone (e.g., '+00:00' for UTC).
     * @return array Array indicating success.
     * @throws Exception if the query fails.
     */
    public function set_db_time_zone($timezone)
    {
        $sql = 'SET time_zone = ?';
        $stmt = $this->prepare_and_execute($sql, [$timezone]);
        return ['result' => true];
    }

    /**
     * Processes batch operations in chunks.
     *
     * @param callable $operation  A function that performs the operation, accepting batch size.
     * @param integer  $batch_size Size of each batch (default: 200).
     * @return array Array with total 'affected_rows'.
     */
    public function batch_process($operation, $batch_size = 200)
    {
        $total_affected_rows = 0;
        while (true) {
            $result = call_user_func($operation, $batch_size);
            if (!isset($result['affected_rows']) || $result['affected_rows'] == 0) {
                break;
            }
            $total_affected_rows += $result['affected_rows'];
        }
        return ['affected_rows' => $total_affected_rows];
    }
}
