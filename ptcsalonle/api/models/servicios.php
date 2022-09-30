<?php
/*
*	Clase para manejar la tabla servicios de la base de datos.
*   Es clase hija de Validator.
*/
class Servicios extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombre = null;
    private $precio = null;


    /*
    *   Métodos para validar y asignar valores de los atributos.
    */
     //validar ID
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
     
     //validar Precio
    public function setPrecio($value)
    {
        if ($this->validateMoney($value)) {
            $this->precio = $value;
            return true;
        } else {
            return false;
        }
    }
    

    /*
    *   Métodos para obtener valores de los atributos.
    */

    //Obtener Id
    public function getId()
    {
        return $this->id;
    }
    //Obtener Nombre
    public function getNombre()
    {
        return $this->nombre;
    }
    
    //Obtener Precio 
    public function getPrecio()
    {
        return $this->precio;
    }
    
    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_servicio, nombre_servicio, precio_servicio
                FROM servicio 
                WHERE nombre_servicio ILIKE ? 
                ORDER BY nombre_servicio';
        $params = array("%$value%");
        return Database::getRows($sql, $params);
    }
    //Funcion creada para crear una nueva fila
    public function createRow()
    {
        $sql = 'INSERT INTO servicio(nombre_servicio, precio_servicio)
                VALUES(?, ?)';
        $params = array($this->nombre, $this->precio);
        return Database::executeRow($sql, $params);
    }
    //Funcion creada para leer todo
    public function readAll()
    {
        $sql = 'SELECT id_servicio, nombre_servicio, precio_servicio
                FROM servicio
                ORDER BY nombre_servicio';
        $params = null;
        return Database::getRows($sql, $params);
    }
    //Funcion creada para leer una fila
    public function readOne()
    {
        $sql = 'SELECT id_servicio, nombre_servicio, precio_servicio
                FROM servicio
                WHERE id_servicio = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    //Funcion creada para editar una fila
    public function updateRow()
    {
        $sql = 'UPDATE servicio
                SET nombre_servicio = ?, precio_servicio = ?
                WHERE id_servicio = ?';
        $params = array($this->nombre, $this->precio, $this->id);
        return Database::executeRow($sql, $params);
    }
    //Funcion creada para eliminar una fila
    public function deleteRow()
    {
        $sql = 'DELETE FROM servicio
                WHERE id_servicio = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
         /*
    *   Método para generar reporte de productos por marca.
    */
    public function allServicios()
    {
        $sql = 'SELECT nombre_servicio, precio_servicio
        FROM servicio
        ORDER BY precio_servicio';
        $params = null;
        return Database::getRows($sql, $params);
    }

   
}