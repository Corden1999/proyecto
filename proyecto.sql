CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(100) NOT NULL,
    telefono VARCHAR(15),
    direccion VARCHAR(200),
    codigo_postal VARCHAR(10),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    tipo_usuario varchar(50) NOT NULL
);

CREATE TABLE Pisos (
    id_piso INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    direccion VARCHAR(200) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    descripcion TEXT,
    disponible char(2),
    tipo varchar(20),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Habitaciones (
    id_habitacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    direccion VARCHAR(200) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    descripcion TEXT,
    disponible char(2),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
    
);

CREATE TABLE Empleos (
    id_empleo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    codigo_postal VARCHAR(10) NOT NULL,
    salario DECIMAL(10, 2),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Transaccion_piso_venta (
    id_transaccion_piso_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_vendedor INT,
    id_usuario_comprador INT,
    id_piso INT,
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_usuario_vendedor) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_usuario_comprador) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_piso) REFERENCES Pisos(id_piso)  
);

CREATE TABLE Transaccion_piso_alquiler (
    id_transaccion_piso_alquiler INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_casero INT,
    id_usuario_arrendatario INT,
    id_piso INT,
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_usuario_casero) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_usuario_arrendatario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_piso) REFERENCES Pisos(id_piso)
);

CREATE TABLE Transaccion_habitacion_alquiler (
    id_transaccion_habitacion_alquiler INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_habitacion_casero INT,
    id_usuario_habitacion_arrendatario INT,
    id_habitacion INT,
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_usuario_habitacion_casero) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_usuario_habitacion_arrendatario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_habitacion) REFERENCES Habitaciones(id_habitacion)
);

CREATE TABLE Cuenta (
    id_cuenta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    saldo DECIMAL(10, 2) DEFAULT 0.00,
    fecha_apertura TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Gastos (
    id_gasto INT AUTO_INCREMENT PRIMARY KEY,
    id_cuenta INT,
    descripcion TEXT,
    monto DECIMAL(10, 2) NOT NULL,
    fecha_gasto TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_cuenta) REFERENCES Cuenta (id_cuenta)
);

CREATE TABLE Recomendaciones (
    id_recomendacion INT AUTO_INCREMENT PRIMARY KEY,
    id_empleo INT,
    id_piso INT,
    fecha_recomendacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_empleo) REFERENCES Empleos(id_empleo),
    FOREIGN KEY (id_piso) REFERENCES Pisos(id_piso)
);