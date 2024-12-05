-- Crear la base de datos si no existe
CREATE DATABASE IF NOT EXISTS `cafeteria_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `cafeteria_db`;

-- --------------------------------------------------------

-- Estructura de la tabla `products`
CREATE TABLE `products` (
  `idProducto` INT(11) NOT NULL AUTO_INCREMENT,  -- Asegurando que sea autoincrementable
  `nombreProducto` VARCHAR(100) NOT NULL,         -- Nombre del producto
  `precioProducto` DECIMAL(10,2) NOT NULL,        -- Precio del producto (dos decimales)
  `cantidadProducto` INT(11) NOT NULL,            -- Cantidad disponible
  PRIMARY KEY (`idProducto`)                       -- Establecer `idProducto` como clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Estructura de la tabla `users`
CREATE TABLE `users` (
  `idUsuario` INT(11) NOT NULL AUTO_INCREMENT,    -- Asegurando que sea autoincrementable
  `nombreUsuario` VARCHAR(100) NOT NULL,           -- Nombre del usuario
  `correoUsuario` VARCHAR(100) NOT NULL UNIQUE,    -- Correo del usuario, con restricción de unicidad
  `contraseñaUsuario` TEXT NOT NULL,               -- Contraseña hasheada, usando `TEXT` para mayor seguridad
  PRIMARY KEY (`idUsuario`)                        -- Establecer `idUsuario` como clave primaria
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

