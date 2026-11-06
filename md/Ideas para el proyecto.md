<!-- tengo diferentes modems de telmex e izi en 3 edificios quisera hacer un sistemas de reporte de lnsidetets de internet por linead de telmex y de izzi tomando en consideracion el conyrato o linea, la ubicacion, el numero de telefono y normalmente el un numero de reporte puede tener mas de un subreporte debido a que la atencion puede prolongarse, ayudame a generar una estructura de las base de datos para mysql tomando en consideracion lo ya espuesto  -->


# revisar la bd Reportes de mysql

CREATE DATABASE IF NOT EXISTS gestion_internet;
USE gestion_internet;

-- 1. Tabla de Edificios / Ubicaciones
CREATE TABLE edificios (
    id_edificio INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,            -- Ej: 'Edificio Norte', 'Sede Central'
    direccion TEXT NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Tabla de Líneas / Módems de Internet
CREATE TABLE lineas_internet (
    id_linea INT AUTO_INCREMENT PRIMARY KEY,
    id_edificio INT NOT NULL,
    proveedor ENUM('Telmex', 'Izzi', 'Otro') NOT NULL,
    numero_contrato VARCHAR(50) NOT NULL,
    numero_telefono VARCHAR(20) NULL,        -- Número de línea (muy común en Telmex)
    ubicacion_especifica VARCHAR(150) NULL,  -- Ej: 'Piso 2 - Site Principal', 'Oficina 301'
    modelo_modem VARCHAR(100) NULL,          -- Identificación del equipo
    ip_publica VARCHAR(45) NULL,             -- IPv4 o IPv6 si aplica
    estatus_linea ENUM('Activa', 'Inactiva', 'En_revision') DEFAULT 'Activa',
    observaciones TEXT NULL,
    FOREIGN KEY (id_edificio) REFERENCES edificios(id_edificio) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 3. Tabla Principal de Reportes de Incidentes (Tickets)
CREATE TABLE reportes_incidentes (
    id_reporte INT AUTO_INCREMENT PRIMARY KEY,
    id_linea INT NOT NULL,
    folio_ticket_proveedor VARCHAR(50) NULL, -- Número de folio oficial proporcionado por Telmex/Izzi
    tipo_falla ENUM('Sin_servicio', 'Intermitencia', 'Lentitud', 'Falla_hardware', 'Otro') NOT NULL,
    descripcion_problema TEXT NOT NULL,
    prioridad ENUM('Baja', 'Media', 'Alta', 'Critica') DEFAULT 'Media',
    estatus ENUM('Abierto', 'En_proceso', 'Escalado', 'Resuelto', 'Cerrado') DEFAULT 'Abierto',
    fecha_apertura DATETIME DEFAULT CURRENT_TIMESTAMP,
    fecha_cierre DATETIME NULL,
    FOREIGN KEY (id_linea) REFERENCES lineas_internet(id_linea) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB;

-- 4. Tabla de Subreportes / Seguimiento Continuo
CREATE TABLE seguimiento_subreportes (
    id_subreporte INT AUTO_INCREMENT PRIMARY KEY,
    id_reporte INT NOT NULL,
    folio_subreporte_proveedor VARCHAR(50) NULL, -- Folio o número de orden secundario asignado por el ISP
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    comentarios TEXT NOT NULL,                   -- Detalles del avance, llamada o visita técnica
    nombre_tecnico_proveedor VARCHAR(100) NULL,  -- Nombre o ID del técnico de Telmex/Izzi que atendió
    proxima_accion VARCHAR(255) NULL,            -- Ej: 'Esperando visita técnica mañana a las 10:00'
    FOREIGN KEY (id_reporte) REFERENCES reportes_incidentes(id_reporte) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

