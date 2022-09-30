<?php
/*
*	Clase para manejar la tabla productos de la base de datos.
*   Es clase hija de Validator.
*/
class Citas extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $fecha = null;
    private $cliente = null;
    private $servicio = null;
    private $hora = null;
    private $usuario = null;
    private $estado = null;
    private $fechainicio = null;
    private $fechafin= null;


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
 
    
    //validar cliente
    public function setCliente($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->cliente = $value;
            return true;
        } else {
            return false;
        }
    }


    //valida servicio
    public function setServicio($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->servicio= $value;
            return true;
        } else {
            return false;
        }
    }

    //valida fecha
    public function setFecha($value)
    {
        if ($this->validateDate($value)) {
            $this->fecha = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setFechaInicio($value)
    {
        if ($this->validateDate($value)) {
            $this->fechainicio = $value;
            return true;
        } else {
            return false;
        }
    }
    public function setFechaFin($value)
    {
        if ($this->validateDate($value)) {
            $this->fechafin = $value;
            return true;
        } else {
            return false;
        }
    }
    //valida hora
    public function setHora($value)
    {
        if ($this->validateString($value, 1, 250)) {
            $this->hora = $value;
            return true;
        } else {
            return false;
        }
    }
    //valida usuario
    public function setUsuario($value)
    {
        if ($this->validateNaturalNumber($value)) {
            $this->usuario= $value;
            return true;
        } else {
            return false;
        }
    }
    //valida el estado
    public function setEstado($value)
    {
        if ($this->validateBoolean($value)) {
            $this->estado = $value;
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

    //Obtiene cliente
    public function getCliente()
    {
        return $this->cliente;
    }
    //Obtiene servicio
    public function getServicio()
    {
        return $this->servicio;
    }
    //Obtiene fecha
    public function getFecha()
    {
        return $this->fecha;
    }
 //Obtiene hora
    public function getHora()
    {
        return $this->hora;
    }
    //Obtiene usuario
    public function getUsuario()
    {
        return $this->usuario;
    }
    //Obtiene estado
    public function getEstado()
    {
        return $this->estado;
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    //Busca una fila
    public function searchRows($value)
    {
        $sql = 'SELECT id_cita, fecha, nombre_servicio, nombres_cliente,  estado_cita, hora, nombres_usuario  
    FROM citas  INNER JOIN servicio USING(id_servicio)
                INNER JOIN clientes using(id_cliente)
                INNER JOIN usuarios using(id_usuario)
                WHERE nombres_cliente ILIKE ? OR nombre_servicio ILIKE ?
                ORDER BY fecha';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
    //Funcion para crear una fila
    public function createRow()
    {
        $sql = 'INSERT INTO citas(fecha, id_servicio, id_cliente, estado_cita, hora, id_usuario)
                VALUES(?, ?, ?, ?, ?, ?)';
        $params = array($this->fecha, $this->servicio, $this->cliente, $this->estado, $this->hora, $this->usuario);
        return Database::executeRow($sql, $params);
    }

    //Lee toda la tabla.
    public function readAll()
    {
        $sql = 'SELECT id_cita, fecha, nombre_servicio, nombres_cliente,  estado_cita, hora, nombres_usuario  
                FROM citas  
                INNER JOIN servicio USING(id_servicio)
                INNER JOIN clientes using(id_cliente)
                INNER JOIN usuarios using(id_usuario)
                ORDER BY fecha DESC';
        $params = null;
        return Database::getRows($sql, $params);
    }
    //Lee una fila en especifico
    public function readOne()
    {
        $sql = 'SELECT id_cita, fecha, id_servicio, id_cliente, estado_cita, hora, id_usuario
                FROM citas
                WHERE id_cita= ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    //Actualiza una fila
    public function updateRow()
    {
        $sql = 'UPDATE citas
                SET fecha = ?, id_servicio = ?, id_cliente = ?, estado_cita = ?, hora = ?, id_usuario = ?
                WHERE id_cita = ?';
        $params = array($this->fecha, $this->servicio, $this->cliente, $this->estado, $this->hora, $this->usuario, $this->id);
        return Database::executeRow($sql, $params);
    }
    //Elimina una fila
    public function deleteRow()
    {
        $sql = 'DELETE FROM citas
                WHERE id_cita = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
    // Funcion leer citas por servicios
    public function readCitasServicios()
    {
        $sql = 'SELECT id_cita, fecha, hora
                FROM citas INNER JOIN servicio USING(id_servicio)
                WHERE id_servicio = ? AND estado_cita = true
                ORDER BY nombre_cliente';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
    // Funcion leer citas por clientes
    public function readCitasClientes()
    {
        $sql = 'SELECT id_cita, fecha, hora
                FROM citas INNER JOIN clientes USING(id_cliente)
                WHERE id_categoria = ? AND estado_cita = true
                ORDER BY nombre_cliente';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }
   // Funcion leer citas por usuarios
    public function readCitasUsuarios()
    {
        $sql = 'SELECT id_cita, fecha, hora
                FROM citas INNER JOIN usuarios USING(id_usuario)
                WHERE id_usuario = ? AND estado_cita = true
                ORDER BY nombre_cliente';
        $params = array($this->id);
        return Database::getRows($sql, $params);
    }

      // Reporte parametrizado de clientes para ver sus citas.
      public function cantidadClienteCitas()
      {
          $sql = 'SELECT id_cita, id_servicio, id_cliente, fecha, estado_cita, nombres_cliente, 
          nombre_servicio, precio_servicio
          FROM citas INNER JOIN servicio USING(id_servicio)
          INNER JOIN clientes USING(id_cliente)
          WHERE id_cliente= ?
          ORDER BY fecha';
          $params =  array($this->cliente);
        return Database::getRows($sql, $params);
      }

          // Reporte parametrizado Citas y detallles.
          public function citasDetalle()
          {
              $sql = 'SELECT id_cita, fecha, nombre_servicio, precio_servicio, nombres_cliente, 
              apellidos_cliente, estado_cita, hora, nombres_usuario  
              FROM citas  
              INNER JOIN servicio USING(id_servicio)
              INNER JOIN clientes using(id_cliente)
              INNER JOIN usuarios using(id_usuario)
              ORDER BY fecha
              WHERE id_cita= ?
              ORDER BY fecha';
              $params =  array($this->id);
            return Database::getRows($sql, $params);
          }
    
      // Reporte parametrizado Usarios peticion estilistas.
      public function estilistasPeticion()
      {
          $sql = 'SELECT id_cita, id_servicio, id_usuario, apellidos_usuario, id_cliente, fecha, 
          estado_cita, nombres_cliente, apellidos_cliente, nombre_servicio, precio_servicio
          FROM citas INNER JOIN servicio USING(id_servicio)
          INNER JOIN clientes USING(id_cliente)
          INNER JOIN usuarios USING(id_usuario)
          WHERE id_usuario= ?
          ORDER BY fecha';
          $params =  array($this->usuario);
        return Database::getRows($sql, $params);
      }

//Top 5 estilistas mas solicitadosa.
public function TopEstilistas()
{  
    $sql = 'SELECT nombres_usuario, COUNT (id_cita)cantidad
    FROM citas INNER JOIN usuarios USING(id_usuario)
    GROUP BY nombres_usuario ORDER BY cantidad DESC
    LIMIT 5';
    $params = null;
    return Database::getRows($sql, $params);
}

//Citas en los ultimos dias.
public function CitasMeses()
{  
    $sql = 'SELECT fecha, COUNT (id_cita)cantidad
    FROM citas  
    GROUP BY fecha ORDER BY fecha DESC
    LIMIT 6';
    $params = null;
    return Database::getRows($sql, $params);
}
//Top 5 servicios mas solicitados.
public function TopServicios()
{  
    $sql = 'SELECT nombre_servicio, COUNT (id_cita)cantidad
    FROM citas INNER JOIN servicio USING(id_servicio)
    GROUP BY nombre_servicio ORDER BY cantidad DESC
    LIMIT 5';
    $params = null;
    return Database::getRows($sql, $params);
}

//Top 5 clientes con más citas.
public function TopClientes()
{  
    $sql = 'SELECT nombres_cliente, COUNT (id_cita)cantidad
    FROM citas INNER JOIN clientes USING(id_cliente)
    GROUP BY nombres_cliente ORDER BY cantidad DESC
    LIMIT 5';
    $params = null;
    return Database::getRows($sql, $params);
}
    
     //Reporte de clientes
     public function cantidadCitasClientes()
     {
         $sql = 'SELECT  fecha, nombre_servicio, hora, nombres_usuario, estado_cita
         FROM citas 
         INNER JOIN servicio using(id_servicio) 
         INNER JOIN usuarios using(id_usuario) 
         INNER JOIN clientes USING(id_cliente) WHERE id_cliente= ?
         ORDER BY fecha DESC';
         $params =  array($this->cliente);
       return Database::getRows($sql, $params);
     }
     // Reporte reservaciones
         public function citasRealizadas()
         {  
             $sql = 'SELECT fecha, nombre_servicio, precio_servicio, nombres_cliente, hora, nombres_usuario, estado_cita
             FROM citas 
             INNER JOIN usuarios using(id_usuario)
             INNER JOIN clientes using(id_cliente) 
             INNER JOIN servicio using(id_servicio) 
             ORDER BY fecha DESC';
             $params = null;
           return Database::getRows($sql, $params);
         }

         //Reporte parametrizado de reservaciones
     public function reservacionCliente()
     {
         $sql = 'SELECT fecha, nombre_servicio, precio_servicio, nombres_cliente, hora, nombres_usuario, estado_cita
         FROM citas 
         INNER JOIN usuarios using(id_usuario)
         INNER JOIN clientes using(id_cliente) 
         INNER JOIN servicio using(id_servicio) WHERE id_cita= ?
         ORDER BY fecha DESC';
         $params =  array($this->id);
       return Database::getRows($sql, $params);
     }

      //Reporte parametrizado de servicios
     public function serviciosporCita()
     {
         $sql = 'SELECT id_cita, id_servicio, id_cliente, fecha, 
         estado_cita, nombres_cliente, apellidos_cliente, nombre_servicio, precio_servicio
         FROM citas INNER JOIN servicio USING(id_servicio)
         INNER JOIN clientes USING(id_cliente)
         INNER JOIN usuarios USING(id_usuario)
         WHERE id_servicio= ?
         ORDER BY fecha';
         $params =  array($this->servicio);
       return Database::getRows($sql, $params);
     }

      //Reporte parametrizado de servicios
      public function citasRangoFechas()
      {
          $sql = "SELECT extract(month from fecha) orden, to_char(fecha, 'TMMonth') mes, COUNT (id_cita) cantidad
          FROM citas  
          where fecha between ? and ?
          GROUP BY orden, mes ORDER BY orden ASC";
          $params =  array($this->fechainicio, $this->fechafin);
        return Database::getRows($sql, $params);
      }

}