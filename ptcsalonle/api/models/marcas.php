<?php
/*
*	Clase para manejar la tabla marcas de la base de datos.
*   Es clase hija de Validator.
*/
class Marcas extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombre = null;

    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
    public function setId($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->id = $value;
            return true;
        } else {
            return false;
        }
    }
    //validar Nombre
    public function setNombre($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->nombre = $value;
            return true;
        } else {
            return false;
        }
    }
   

    /*
    *   Métodos para obtener valores de los atributos.
    */

    //Obtener ID
    public function getId()
    {
        return $this->id;
    }
    //Obtener Nombre
    public function getNombre()
    {
        return $this->nombre;
    }
   
    /*
    *   Métodos para realizar la operacion de leer todo
    */
   
    public function readAll()
    {
        $sql = 'SELECT id_marca, nombre_marcas
                FROM marcas
                ORDER BY nombre_marcas';
        $params = null;
        return Database::getRows($sql, $params);
    }

  
}