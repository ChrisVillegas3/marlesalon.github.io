<?php
/*
*	Clase para manejar la tabla usuarios de la base de datos.
*   Es clase hija de Validator.
*/
class Usuarios extends Validator
{
    // Declaración de atributos (propiedades).
    private $id = null;
    private $nombres = null;
    private $apellidos = null;
    private $correo = null;
    private $alias = null;
    private $clave = null;
    private $intentos = null;
    private $fechapwd = null;
    private $autenticacion = null;

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
    //SET AL INTENTOS
    public function setIntentos($value)
    {
        if ($this->validateNaturalNumber($value) || $value == 0) {
            $this->intentos = $value;
            return true;
        } else {
            return false;
        }
    }
    //Funcion para nombres
    public function setNombres($value)
    {
        if ($this->validateAlphabetic($value, 1, 50)) {
            $this->nombres = $value;
            return true;
        } else {
            return false;
        }
    }
    //Funcion para apellidos
    public function setApellidos($value)
    {
        if ($this->validateAlphabetic($value, 1, 50)) {
            $this->apellidos = $value;
            return true;
        } else {
            return false;
        }
    }
     //Funcion para correo
    public function setCorreo($value)
    {
        if ($this->validateEmail($value)) {
            $this->correo = $value;
            return true;
        } else {
            return false;
        }
    }
    //Funcion para alias
    public function setAlias($value)
    {
        if ($this->validateAlphanumeric($value, 1, 50)) {
            $this->alias = $value;
            return true;
        } else {
            return false;
        }
    }
    //Funcion para clave
    public function setClave($value)
    {
        if ($this->validatePassword($value)) {
            $this->clave = password_hash($value, PASSWORD_DEFAULT);
            return true;
        } else {
            return false;
        }
    }
    //Funcion para fecha de la contraseña
    public function setFechaPwd($value)
    {
        if ($this->validateDate($value)) {
            $this->fechapwd = $value;
            return true;
        } else {
            return false;
        }
    }
    //Funcion para la autenticacion
    public function setAutenticacion($value)
    {
        if ($this->validateBoolean($value)) {
            $this->autenticacion = $value;
            return true;
        } else {
            return false;
        }
    }

    /*
    *   Métodos para obtener valores de los atributos.
    */
    public function getId()
    {
        return $this->id;
    }

    public function getIntentos()
    {
        return $this->intentos;
    }

    public function getNombres()
    {
        return $this->nombres;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getCorreo()
    {
        return $this->correo;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getClave()
    {
        return $this->clave;
    }

    public function getFechaPwd()
    {
        return $this->fechapwd;
    }

    /*
    *   Métodos para gestionar la cuenta del usuario.
    */
    public function checkUser($alias)
    {
        $sql = 'SELECT id_usuario FROM usuarios WHERE alias_usuario = ?';
        $params = array($alias);
        if ($data = Database::getRow($sql, $params)) {
            $this->id = $data['id_usuario'];
            $this->alias = $alias;
            return true;
        } else {
            return false;
        }
    }
   //Verificar contraseña
   public function checkPassword($password)
    {
        $sql = 'SELECT clave_usuario FROM usuarios WHERE id_usuario = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        // Se verifica contraseñas.
        if (password_verify($password, $data['clave_usuario'])) {
            return true;
        } else {
            return false;
        }
    }
   // Cambiar contraseña para el recuperacion.
    public function changePassword()
    {
         // Se establece la zona horaria local para obtener la fecha del servidor.
         date_default_timezone_set('America/El_Salvador');
         // Se obtiene la fecha actual para actualizar el campo de fecha_pwd
         $fecha = date('Y-m-d');
         if (isset($_SESSION['id_usuario'])) {
             $hash = password_hash($this->clave, PASSWORD_DEFAULT);
             $sql = 'UPDATE usuarios SET "clave_usuario" = ?, fecha_pwd = ? WHERE id_usuario = ?';
             $params = array($hash, $fecha, $_SESSION['id_usuario']);
             return Database::executeRow($sql, $params);
         } else {
             $hash = ($this->clave);
             $sql = 'UPDATE usuarios SET "clave_usuario" = ?, fecha_pwd = ? WHERE id_usuario = ?';
             $params = array($hash, $fecha, $this->id);
             return Database::executeRow($sql, $params);
         }
    }

   // Cambiar contraseña dentro del sistema.
    public function changePasswordsistem()
    {
        $sql = 'UPDATE usuarios SET clave_usuario = ? WHERE id_usuario = ?';
        $params = array($this->clave, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }
   //Op
   //Opciones de perfil del usuario.
    public function readProfile()
    {
        $sql = 'SELECT id_usuario, nombres_usuario, apellidos_usuario, correo_usuario, alias_usuario
                FROM usuarios
                WHERE id_usuario = ?';
        $params = array($_SESSION['id_usuario']);
        return Database::getRow($sql, $params);
    }

   
   //Editar perfil.
    public function editProfile()
    {
        $sql = 'UPDATE usuarios
                SET nombres_usuario = ?, apellidos_usuario = ?, correo_usuario = ?
                WHERE id_usuario = ?';
        $params = array($this->nombres, $this->apellidos, $this->correo, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }

    /*
    *   Métodos para realizar las operaciones SCRUD (search, create, read, update, delete).
    */
    public function searchRows($value)
    {
        $sql = 'SELECT id_usuario, nombres_usuario, apellidos_usuario, correo_usuario, alias_usuario
                FROM usuarios
                WHERE apellidos_usuario ILIKE ? OR nombres_usuario ILIKE ?
                ORDER BY apellidos_usuario';
        $params = array("%$value%", "%$value%");
        return Database::getRows($sql, $params);
    }
   //Create.
   public function createRow()
    {
        $sql = 'INSERT INTO usuarios(nombres_usuario, apellidos_usuario, correo_usuario, alias_usuario, clave_usuario)
                VALUES(?, ?, ?, ?, ?)';
        $params = array($this->nombres, $this->apellidos, $this->correo, $this->alias, $this->clave);
        return Database::executeRow($sql, $params);
    }
   //Leer los datos.
    public function readAll()
    {
        $sql = 'SELECT id_usuario, nombres_usuario, apellidos_usuario, correo_usuario, alias_usuario
                FROM usuarios
                ORDER BY apellidos_usuario';
        $params = null;
        return Database::getRows($sql, $params);
    }
   //leer solo uno.
    public function readOne()
    {
        $sql = 'SELECT id_usuario, nombres_usuario, apellidos_usuario, correo_usuario, alias_usuario
                FROM usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::getRow($sql, $params);
    }
    //Actualiza una fila del perfil
    public function updateRowMiPerfil()
    {
        // Se establece la zona horaria local para obtener la fecha del servidor.
        date_default_timezone_set('America/El_Salvador');
        // Se obtiene la fecha actual para actualizar el campo de fecha_pwd
        $fecha = date('Y-m-d');
        $sql = 'UPDATE usuarios
        SET usuario=?, fecha_pwd = ?, autenticacion = ?
        WHERE id_usuario = ?';

        $params = array($this->usuario, $fecha, $this->autenticacion, $_SESSION['id_usuario']);
        return Database::executeRow($sql, $params);
    }
   //Updates.
    public function updateRow()
    {
        // Se establece la zona horaria local para obtener la fecha del servidor.
        date_default_timezone_set('America/El_Salvador');
        // Se obtiene la fecha actual para actualizar el campo de fecha_pwd
        $fecha = date('Y-m-d');
        $sql = 'UPDATE usuarios 
                SET nombres_usuario = ?, apellidos_usuario = ?, correo_usuario = ?, fecha_pwd = ?
                WHERE id_usuario = ?';
        $params = array($this->nombres, $this->apellidos, $this->correo, $fecha, $this->id);
        return Database::executeRow($sql, $params);
    }
   //Delete.
    public function deleteRow()
    {
        $sql = 'DELETE FROM usuarios
                WHERE id_usuario = ?';
        $params = array($this->id);
        return Database::executeRow($sql, $params);
    }
    // Se obtiene el correo del usuario a recuperar
    public function obtenerCorreo()
    {
        $sql = 'SELECT id_usuario, correo_usuario 
        FROM usuarios
        WHERE alias_usuario = ?';

        $params = array($this->alias);
        return Database::getRow($sql, $params);
    }

    // Guardar el código en la base para reiniciar la contraseña, se guarda encriptado
    public function saveCode($value)
    {
        $hash = password_hash($value, PASSWORD_DEFAULT);
        $sql = 'UPDATE usuarios 
                SET codigo = ?
                WHERE id_usuario = ?';
        $params = array($hash, $this->id);
        return Database::executeRow($sql, $params);
    }
    
    // Se verifica que el código sea el mismo al generado y guardado en la base
    public function checkCode($code)
    {
        $sql = 'SELECT codigo FROM usuarios WHERE id_usuario = ?';
        $params = array($this->id);
        $data = Database::getRow($sql, $params);
        if (password_verify($code, $data['codigo'])) {
            return true;
        } else {
            return false;
        }
    }
    // Se cambia el código generado por un número aleatorio
    public function resetCode()
    {
        $hash = password_hash(rand(0000, 9999), PASSWORD_DEFAULT);
        $sql = 'UPDATE usuarios 
            SET codigo = ?
            WHERE id_usuario = ?';
        $params = array($hash, $this->id);
        return Database::executeRow($sql, $params);
}
  //PARA LOS INTENTOS DE USUARIO, SELECCIONA LOS INTENTOS DE CADA USUARIO
  public function selectIntentos()
  {
      $sql = 'SELECT  intentos 
          FROM "usuarios" 
          WHERE usuario = ?';
      $params = array($this->usuario);
      return Database::executeRow($sql, $params);
  }

  //PARA LOS INTENTOS DE USUARIO, ACTUALIZA LOS INTENTOS DE CADA USUARIO
  public function intentos()
  {
      $sql = 'UPDATE "usuarios" 
              SET intentos = ?
              WHERE "id_usuario" = ?';
      $params = array($this->intentos, $this->id);
      return Database::executeRow($sql, $params);
  }
  
    
}