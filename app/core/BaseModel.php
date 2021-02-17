<?php
/**
 * Módelo base para las clases de models.
 */
abstract class BaseModel
{
    /**
     * Tabla asociada al modelo.
     *
     * @var string
     */
    protected $table;
    protected $db;

    /**
     * Se conecta a la base de datos.
     */
    public function __construct()
    {
        $this->db = DB::getInstance()->getConnection();
    }

    /**
     * Método genérico para obtener todos los registros de la tabla $table.
     *
     * @return void
     */
    public function getAll()
    {
        $resultSet = null;

        $query = $this->db->query("SELECT * FROM $this->table WHERE deleted_at is null ORDER BY id DESC");

        //Devolvemos el resultset en forma de array de objetos
        while ($row = $query->fetchObject()) {
            $resultSet[] = $row;
        }

        return $resultSet;
    }

    /**
     * Método genérico para obtener todos los registros de la tabla $table por el id que se le pasa.
     *
     * @param int $id
     *
     * @return void
     */
    public function getById($id)
    {
        $resultSet = null;

        $query = $this->db->query("SELECT * FROM $this->table WHERE id = $id");

        if ($row = $query->fetchObject()) {
            $resultSet = $row;
        }

        return $resultSet;
    }

    /**
     * Método genérico para obtener todos los registros de la tabla $table por la columna y el valor que se le pasa.
     *
     * @param int $column
     * @param int $value
     *
     * @return void
     */
    public function getBy($column, $value)
    {
        $resultSet = null;

        $query = $this->db->query("SELECT * FROM $this->table WHERE $column = '$value'");

        while ($row = $query->fetchObject()) {
            $resultSet[] = $row;
        }

        return $resultSet;
    }

    /**
     * Método genérico para borrar por el id pasado por parametro.
     *
     * @param int $id
     *
     * @return void
     */
    public function deleteById($id)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE id = $id");
        // $query = $this->db->query("UPDATE $this->table SET deleted_at = NOW() WHERE id = $id");
        return $query;
    }

    /**
     * Método genérico para borrar los registros de la tabla $table por la columna y el valor que se le pasa.
     *
     * @param int $column
     * @param int $value
     *
     * @return void
     */
    public function deleteBy($column, $value)
    {
        $query = $this->db->query("DELETE FROM $this->table WHERE $column = '$value'");

        return $query;
    }

    // public function setLog($user_id, $action, $description)
    // {
    //     $this->db->query("CALL log($user_id, '$action', '$description')");
    // }
}
