-- Esquema completo PlayCard Manager
SET NAMES utf8mb4;
CREATE TABLE IF NOT EXISTS tbl_usuarios (
  id_usuario INT AUTO_INCREMENT PRIMARY KEY,
  str_nombreusuario VARCHAR(50) UNIQUE NOT NULL,
  str_password VARCHAR(255) NOT NULL,
  enum_tipousuario ENUM('admin','normal') NOT NULL DEFAULT 'normal',
  datetime_fechacreacion DATETIME NOT NULL,
  bool_activo BOOLEAN NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tbl_clientes (
  id_cliente INT AUTO_INCREMENT PRIMARY KEY,
  str_nombre VARCHAR(100),
  str_apellido VARCHAR(100),
  str_telefono VARCHAR(20),
  str_email VARCHAR(100),
  str_codigosecreto VARCHAR(6),
  bool_anonimo BOOLEAN DEFAULT 0,
  datetime_fecharegistro DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  bool_activo BOOLEAN NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tbl_tarjetaclientes (
  id_tarjeta INT AUTO_INCREMENT PRIMARY KEY,
  str_coditotarjeta VARCHAR(50) UNIQUE NOT NULL,
  id_cliente INT NULL,
  num_saldo DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  enum_estado ENUM('activa','bloqueada') NOT NULL DEFAULT 'activa',
  datetime_fechaasignacion DATETIME NULL,
  CONSTRAINT fk_tarjeta_cliente FOREIGN KEY (id_cliente) REFERENCES tbl_clientes(id_cliente) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tbl_juegos (
  id_juego INT AUTO_INCREMENT PRIMARY KEY,
  str_nombrejuego VARCHAR(100) NOT NULL,
  num_costoporuso DECIMAL(8,2) NOT NULL,
  bool_activo BOOLEAN NOT NULL DEFAULT 1,
  datetime_fechacreacion DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tbl_transacciones (
  id_transaccion INT AUTO_INCREMENT PRIMARY KEY,
  id_tarjeta INT NOT NULL,
  enum_tipotransaccion ENUM('carga','consumo','premio') NOT NULL,
  id_juego INT NULL,
  num_monto DECIMAL(10,2) NOT NULL,
  num_saldoanterior DECIMAL(10,2) NOT NULL,
  num_saldonuevo DECIMAL(10,2) NOT NULL,
  str_descripcion TEXT,
  datetime_fechatransaccion DATETIME NOT NULL,
  CONSTRAINT fk_trans_tarjeta FOREIGN KEY (id_tarjeta) REFERENCES tbl_tarjetaclientes(id_tarjeta),
  CONSTRAINT fk_trans_juego FOREIGN KEY (id_juego) REFERENCES tbl_juegos(id_juego)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS tbl_premios (
  id_premio INT AUTO_INCREMENT PRIMARY KEY,
  str_nombrepremio VARCHAR(100) NOT NULL,
  num_puntosrequeridos DECIMAL(8,2) NOT NULL,
  bool_disponible BOOLEAN NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
