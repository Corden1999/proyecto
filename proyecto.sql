CREATE TABLE Usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    contraseña VARCHAR(100) NOT NULL,
    telefono VARCHAR(15),
    direccion VARCHAR(200),
    codigo_postal VARCHAR(10),
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    tipo_usuario VARCHAR(50) NOT NULL
);

CREATE TABLE Pisos (
    id_piso INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    direccion VARCHAR(200) NOT NULL,
    localidad VARCHAR(200) NOT NULL,
    provincia VARCHAR(200) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    descripcion TEXT,
    disponible CHAR(2),
    tipo VARCHAR(20),
    foto VARCHAR(255),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Habitaciones (
    id_habitacion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    direccion VARCHAR(200) NOT NULL,
    localidad VARCHAR(200) NOT NULL,
    provincia VARCHAR(200) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    descripcion TEXT,
    foto varchar(255),
    disponible CHAR(2),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Locales (
    id_local INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    direccion VARCHAR(200) NOT NULL,
    localidad VARCHAR(200) NOT NULL,
    provincia VARCHAR(200) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    descripcion TEXT,
    disponible CHAR(2),
    tipo VARCHAR(20),
    foto VARCHAR(255),
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Empleos (
    id_empleo INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    titulo VARCHAR(200) NOT NULL,
    descripcion TEXT,
    direccion VARCHAR(200) NOT NULL,
    codigo_postal VARCHAR(10) NOT NULL,
    localidad VARCHAR(200) NOT NULL,
    provincia VARCHAR(200) NOT NULL,
    tipo_contrato VARCHAR(50) NOT NULL,
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

CREATE TABLE Transaccion_local_venta (
    id_transaccion_local_venta INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_vendedor INT,
    id_usuario_comprador INT,
    id_local INT,
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_usuario_vendedor) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_usuario_comprador) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_local) REFERENCES Locales(id_local)
);

CREATE TABLE Transaccion_local_alquiler (
    id_transaccion_local_alquiler INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario_casero INT,
    id_usuario_arrendatario INT,
    id_local INT,
    fecha_transaccion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    monto DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (id_usuario_casero) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_usuario_arrendatario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_local) REFERENCES Locales(id_local)
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
    FOREIGN KEY (id_cuenta) REFERENCES Cuenta(id_cuenta)
);

CREATE TABLE Curriculum (
    id_curriculum INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    telefono VARCHAR(15),
    direccion VARCHAR(200),
    codigo_postal VARCHAR(10),
    experiencia TEXT,
    formacion TEXT,
    habilidades TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE InscripcionesEmpleo (
    id_inscripcion INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_empleo INT,
    fecha_inscripcion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_empleo) REFERENCES Empleos(id_empleo)
);

CREATE TABLE Chat (
    id_mensaje INT AUTO_INCREMENT PRIMARY KEY,
    id_remitente INT,
    id_destinatario INT,
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_remitente) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_destinatario) REFERENCES Usuarios(id_usuario)
);

CREATE TABLE Empleados (
    id_empleado INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT,
    id_usuario_contratante INT,
    id_local INT,
    cargo VARCHAR(100) NOT NULL,
    salario DECIMAL(10, 2) NOT NULL,
    fecha_contratacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_usuario_contratante) REFERENCES Usuarios(id_usuario),
    FOREIGN KEY (id_local) REFERENCES Locales(id_local)
);