-- =========================================================
-- GraCitas - Reservas de canchas de gras sintetico
-- MySQL 8.0+ / InnoDB / utf8mb4
-- Zona horaria de la aplicacion: America/Lima
-- =========================================================

CREATE DATABASE IF NOT EXISTS gracitas
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gracitas;

-- 1. Usuarios: admin, propietario y cliente
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    email_verified_at TIMESTAMP NULL DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    telefono VARCHAR(20) NULL,
    rol ENUM('admin','propietario','cliente') NOT NULL DEFAULT 'cliente',
    activo TINYINT(1) NOT NULL DEFAULT 1,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email),
    KEY users_rol_activo_index (rol, activo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Establecimientos: cada propietario tiene un solo local (1 a 1)
CREATE TABLE IF NOT EXISTS establecimientos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    propietario_id BIGINT UNSIGNED NOT NULL,
    nombre VARCHAR(150) NOT NULL,
    descripcion TEXT NULL,
    direccion VARCHAR(255) NOT NULL,
    referencia VARCHAR(255) NULL,
    telefono VARCHAR(20) NULL,
    latitud DECIMAL(10,7) NULL,
    longitud DECIMAL(10,7) NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY establecimientos_activo_nombre_index (activo, nombre),
    UNIQUE KEY establecimientos_propietario_unique (propietario_id),
    KEY establecimientos_direccion_index (direccion),
    CONSTRAINT establecimientos_propietario_fk FOREIGN KEY (propietario_id)
      REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT establecimientos_latitud_chk CHECK (latitud IS NULL OR latitud BETWEEN -90 AND 90),
    CONSTRAINT establecimientos_longitud_chk CHECK (longitud IS NULL OR longitud BETWEEN -180 AND 180)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Canchas
CREATE TABLE IF NOT EXISTS canchas (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    establecimiento_id BIGINT UNSIGNED NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT NULL,
    capacidad SMALLINT UNSIGNED NOT NULL DEFAULT 10,
    precio_hora DECIMAL(10,2) NOT NULL,
    duracion_reserva_min SMALLINT UNSIGNED NOT NULL DEFAULT 60,
    activa TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY canchas_establecimiento_activa_index (establecimiento_id, activa),
    CONSTRAINT canchas_establecimiento_fk FOREIGN KEY (establecimiento_id)
      REFERENCES establecimientos(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT canchas_precio_chk CHECK (precio_hora >= 0),
    CONSTRAINT canchas_duracion_chk CHECK (duracion_reserva_min > 0),
    CONSTRAINT canchas_capacidad_chk CHECK (capacidad > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Horarios regulares: 1=lunes ... 7=domingo; un intervalo por dia
CREATE TABLE IF NOT EXISTS horarios_atencion (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    cancha_id BIGINT UNSIGNED NOT NULL,
    dia_semana TINYINT UNSIGNED NOT NULL,
    hora_apertura TIME NOT NULL,
    hora_cierre TIME NOT NULL,
    activo TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY horarios_cancha_dia_unique (cancha_id, dia_semana),
    CONSTRAINT horarios_cancha_fk FOREIGN KEY (cancha_id)
      REFERENCES canchas(id) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT horarios_dia_chk CHECK (dia_semana BETWEEN 1 AND 7),
    CONSTRAINT horarios_intervalo_chk CHECK (hora_apertura < hora_cierre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Bloqueos: mantenimiento, eventos u otras causas
CREATE TABLE IF NOT EXISTS bloqueos_horarios (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    cancha_id BIGINT UNSIGNED NOT NULL,
    inicio DATETIME NOT NULL,
    fin DATETIME NOT NULL,
    motivo VARCHAR(255) NULL,
    creado_por BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    KEY bloqueos_cancha_intervalo_index (cancha_id, inicio, fin),
    KEY bloqueos_creado_por_index (creado_por),
    CONSTRAINT bloqueos_cancha_fk FOREIGN KEY (cancha_id)
      REFERENCES canchas(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT bloqueos_usuario_fk FOREIGN KEY (creado_por)
      REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT bloqueos_intervalo_chk CHECK (inicio < fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Reservas: se confirman automaticamente tras validar disponibilidad
CREATE TABLE IF NOT EXISTS reservas (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    codigo VARCHAR(36) NOT NULL,
    cliente_id BIGINT UNSIGNED NOT NULL,
    cancha_id BIGINT UNSIGNED NOT NULL,
    inicio DATETIME NOT NULL,
    fin DATETIME NOT NULL,
    precio_hora DECIMAL(10,2) NOT NULL,
    precio_total DECIMAL(10,2) NOT NULL,
    estado ENUM('confirmada','cancelada','completada','no_asistio') NOT NULL DEFAULT 'confirmada',
    observaciones VARCHAR(500) NULL,
    cancelada_en DATETIME NULL,
    motivo_cancelacion VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY reservas_codigo_unique (codigo),
    KEY reservas_cancha_intervalo_estado_index (cancha_id, inicio, fin, estado),
    KEY reservas_cliente_estado_index (cliente_id, estado),
    CONSTRAINT reservas_cliente_fk FOREIGN KEY (cliente_id)
      REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT reservas_cancha_fk FOREIGN KEY (cancha_id)
      REFERENCES canchas(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT reservas_intervalo_chk CHECK (inicio < fin),
    CONSTRAINT reservas_precio_hora_chk CHECK (precio_hora >= 0),
    CONSTRAINT reservas_precio_total_chk CHECK (precio_total >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Pagos: el propietario registra el pago presencial
-- Este diseño inicial permite un registro de pago por reserva.
CREATE TABLE IF NOT EXISTS pagos (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    reserva_id BIGINT UNSIGNED NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    metodo ENUM('efectivo','yape','plin','otro') NOT NULL DEFAULT 'efectivo',
    estado ENUM('pendiente','pagado','anulado') NOT NULL DEFAULT 'pendiente',
    pagado_en DATETIME NULL,
    registrado_por BIGINT UNSIGNED NULL,
    observacion VARCHAR(255) NULL,
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY pagos_reserva_unique (reserva_id),
    KEY pagos_estado_fecha_index (estado, pagado_en),
    KEY pagos_registrado_por_index (registrado_por),
    CONSTRAINT pagos_reserva_fk FOREIGN KEY (reserva_id)
      REFERENCES reservas(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT pagos_usuario_fk FOREIGN KEY (registrado_por)
      REFERENCES users(id) ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT pagos_monto_chk CHECK (monto >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comprobar las tablas creadas
SHOW TABLES;
