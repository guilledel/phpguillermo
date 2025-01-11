-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `guillermo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `guillermo`;

-- Crear usuario si no existe
CREATE USER IF NOT EXISTS 'guillermo'@'%' IDENTIFIED BY 'guillermo';
-- --------------------------------------------------------

-- Otorgar todos los permisos al usuario 'guillermo'
GRANT ALL PRIVILEGES ON `guillermo`.* TO 'guillermo'@'%';

-- Aplicar los cambios de privilegios
FLUSH PRIVILEGES;

-- Estructura de la tabla `products`
CREATE TABLE IF NOT EXISTS `products` (
  `idProducto` INT(11) NOT NULL AUTO_INCREMENT,  -- Asegurando que sea autoincrementable
  `nombreProducto` VARCHAR(100) NOT NULL,         -- Nombre del producto
  `precioProducto` DECIMAL(10,2) NOT NULL,        -- Precio del producto (dos decimales)
  `cantidadProducto` INT(11) NOT NULL,            -- Cantidad disponible
  `descripcionProducto` TEXT,                     -- Descripción del producto
  PRIMARY KEY (`idProducto`)                      -- Establecer `idProducto` como clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------


-- Estructura de la tabla `users`
CREATE TABLE IF NOT EXISTS `users` (
  `idUsuario` INT(11) NOT NULL AUTO_INCREMENT,    -- Asegurando que sea autoincrementable
  `nombreUsuario` VARCHAR(100) NOT NULL,           -- Nombre del usuario
  `correoUsuario` VARCHAR(100) NOT NULL UNIQUE,    -- Correo del usuario, con restricción de unicidad
  `contrasenaUsuario` TEXT NOT NULL,               -- Contraseña hasheada, usando `TEXT` para mayor seguridad
  PRIMARY KEY (`idUsuario`)                        -- Establecer `idUsuario` como clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

INSERT INTO `users` (`nombreUsuario`, `correoUsuario`, `contrasenaUsuario`) VALUES
('hola', 'hola@gmail.com', 'hola');
