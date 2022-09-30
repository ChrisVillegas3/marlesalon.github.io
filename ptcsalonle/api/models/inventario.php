<?php
/*
*	Clase para manejar la tabla productos de la base de datos.
*   Es clase hija de Validator.
*/
class Productos extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombre = null;
    private $cantidad = null;
    private $imagen = null;
    private $marcas = null;
    private $ruta = '../images/productos/';

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

     //validar cantidad
    public function setCantidad($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cantidad = $value;
            return true;
        } else {
            return false;
        }
    }
     //validar Imagen 
    public function setImagen($file)
    {
        if ($this->validateImageFile($file, 500, 500)) {
            $this->imagen = $this->getFileName();
            return true;
        } else {
            return false;
        }
    }
    //validar Marca
    public function setMarca($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->marcas = $value;
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
   
    //Obtener Cantidad
    public function getCantidad()
    {
        return $this->cantidad;
    }
    //Obtener Imagen
    public function getImagen()
    {
        return $this->imagen;
    }
    //Obtener Marca
    public function getMarca()
    {
        return $this->marcas;
    }
  
    //Obtener Ruta
    public function getRuta()
    {
        return $this->ruta;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, cantidad_producto, nombre_marcas
                FROM productos INNER JOIN marcas USING(id_marca)
                WHERE nombre_producto ILIKE ? OR nombre_marcas ILIKE ?
                ORDER BY nombre_producto';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
//Funcion para crear fila
    public function createRow()
    {
        $sql = 'INSERT INTO productos(nombre_producto, cantidad_producto, imagen_producto, id_marca)
                VALUES(?, ?, ?, ?)';
        $params = array($this->nombre, $this->cantidad, $this->imagen, $this->marcas);
        return Database::executeRow($sql, $params);
    }
//Funcion para leer todo
    public function readAll()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, cantidad_producto, nombre_marcas
                FROM productos INNER JOIN marcas USING(id_marca)
                ORDER BY nombre_producto';
        $params = null;
        return Database::getRows($sql, $params);
    }
//Funcion para leer una fila
    public function readOne()
    {
        $sql = 'SELECT id_producto, nombre_producto, cantidad_producto, imagen_producto, id_marca
                FROM productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
//Funcion para actualizar fila
    public function updateRow($current_image)
    {
        // Se verifica si existe una nueva imagen para borrar la actual, de lo contrario se mantiene la actual.
        ($this->imagen) ? $this->deleteFile($this->getRuta(), $current_image) : $this->imagen = $current_image;

        $sql = 'UPDATE productos
                SET imagen_producto = ?, nombre_producto = ?, cantidad_producto = ?, id_marca = ?
                WHERE id_producto = ?';
        $params = array($this->imagen, $this->nombre, $this->cantidad, $this->marcas, $this->id);
        return Database::executeRow($sql, $params);
    }
//Funcion para eliminar una fila
    public function deleteRow()
    {
        $sql = 'DELETE FROM productos
                WHERE id_producto = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
//Funcion para leer productos por marca
    public function readProductosMarca()
    {
        $sql = 'SELECT id_producto, imagen_producto, nombre_producto, cantidad_producto
                FROM productos INNER JOIN marcas USING(id_marca)
                WHERE id_marca = ?
                ORDER BY nombre_producto';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
 
     //Métodos para generar gráfica de pastel.
    

    public function porcentajeProductosMarca()
    {
        $sql = 'SELECT nombre_marcas, ROUND((COUNT(id_producto) * 100.0 / (SELECT COUNT(id_producto) FROM productos)), 2) porcentaje
        FROM productos INNER JOIN marcas USING(id_marca)
        GROUP BY nombre_marcas ORDER BY porcentaje DESC';
        $params = null;
        return Database::getRows($sql, $params);
    }
       /*
    *   Método para generar reporte de productos por marca.
    */
    public function productosMarca()
    {
        $sql = 'SELECT nombre_producto, cantidad_producto
                FROM productos INNER JOIN marcas USING(id_marca)
                WHERE id_marca = ?
                ORDER BY nombre_producto';
        $params = array($this->marcas);
        return Database::getRows($sql, $params);
    }
   
}
