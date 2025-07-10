-- Base de datos Elige Donde
CREATE DATABASE IF NOT EXISTS remax_peru;
USE remax_peru;

-- Tabla de ubicaciones/distritos
CREATE TABLE locations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(50) NOT NULL,
    province VARCHAR(50) NOT NULL,
    district VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de agentes inmobiliarios
CREATE TABLE agents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    cell_phone VARCHAR(20),
    photo VARCHAR(255),
    company VARCHAR(100) DEFAULT 'Elige Donde CENTRAL REALTY',
    license_number VARCHAR(50),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de tipos de propiedad
CREATE TABLE property_types (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla principal de propiedades
CREATE TABLE properties (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_code VARCHAR(20) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    property_type_id INT,
    transaction_type ENUM('venta', 'alquiler', 'anticresis') NOT NULL,
    price_pen DECIMAL(12,2),
    price_usd DECIMAL(12,2),
    location_id INT,
    address TEXT,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    land_area DECIMAL(8,2),
    built_area DECIMAL(8,2),
    bedrooms INT DEFAULT 0,
    bathrooms INT DEFAULT 0,
    half_bathrooms INT DEFAULT 0,
    parking_spaces INT DEFAULT 0,
    floors INT DEFAULT 0,
    age_years INT DEFAULT 0,
    featured BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    published_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (property_type_id) REFERENCES property_types(id),
    FOREIGN KEY (location_id) REFERENCES locations(id)
);

-- Tabla pivot para relación muchos-a-muchos entre propiedades y agentes
CREATE TABLE property_agent (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NOT NULL,
    agent_id INT NOT NULL,
    is_primary BOOLEAN DEFAULT FALSE,
    role ENUM('principal', 'co-agente', 'colaborador') DEFAULT 'principal',
    commission_percentage DECIMAL(5,2) DEFAULT 0.00,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (agent_id) REFERENCES agents(id) ON DELETE CASCADE,
    UNIQUE KEY unique_property_agent (property_id, agent_id)
);

-- Tabla de características de propiedades
CREATE TABLE property_features (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    icon VARCHAR(50),
    category VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Relación muchos a muchos: propiedades con características
CREATE TABLE property_feature_pivot (
    property_id INT,
    feature_id INT,
    value VARCHAR(100),
    PRIMARY KEY (property_id, feature_id),
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (feature_id) REFERENCES property_features(id) ON DELETE CASCADE
);

-- Tabla de imágenes de propiedades
CREATE TABLE property_images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT,
    image_url VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255),
    is_main BOOLEAN DEFAULT FALSE,
    order_index INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
);

-- Tabla de consultas/contactos
CREATE TABLE inquiries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT,
    agent_id INT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    message TEXT,
    inquiry_type ENUM('info', 'visit', 'call', 'email') DEFAULT 'info',
    status ENUM('new', 'contacted', 'closed') DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id),
    FOREIGN KEY (agent_id) REFERENCES agents(id)
);

-- Insertar datos de muestra
INSERT INTO locations (name, department, province, district) VALUES
('Lima Centro', 'Lima', 'Lima', 'Cercado de Lima'),
('Miraflores', 'Lima', 'Lima', 'Miraflores'),
('San Isidro', 'Lima', 'Lima', 'San Isidro'),
('Barranco', 'Lima', 'Lima', 'Barranco'),
('Surco', 'Lima', 'Lima', 'Santiago de Surco'),
('La Molina', 'Lima', 'Lima', 'La Molina'),
('San Borja', 'Lima', 'Lima', 'San Borja'),
('Lurín', 'Lima', 'Lima', 'Lurín');

INSERT INTO property_types (name, slug) VALUES
('Casa', 'casa'),
('Departamento', 'departamento'),
('Oficina', 'oficina'),
('Terreno', 'terreno'),
('Local Comercial', 'local'),
('Condominio', 'condominio'),
('Hotel', 'hotel'),
('Edificio', 'edificio');

INSERT INTO agents (first_name, last_name, email, phone, cell_phone, photo) VALUES
('Catherine Martha Alice', 'Iturbe Willis', 'citurbe@remax-centralrealty.com', '01-2345678', '994013127', 'agent_catherine.jpg'),
('María Elena', 'Rodriguez Santos', 'mrodriguez@remax-centralrealty.com', '01-2345679', '987654321', 'agent_maria.jpg'),
('Carlos Alberto', 'Mendoza Vargas', 'cmendoza@remax-centralrealty.com', '01-2345680', '976543210', 'agent_carlos.jpg');

INSERT INTO property_features (name, icon, category) VALUES
('Agua Municipal', 'water', 'servicios'),
('Energía Eléctrica', 'electricity', 'servicios'),
('Gas Natural', 'gas', 'servicios'),
('Desagüe Municipal', 'drain', 'servicios'),
('Internet/Cable', 'wifi', 'servicios'),
('Seguridad 24h', 'security', 'amenidades'),
('Piscina', 'pool', 'amenidades'),
('Gimnasio', 'gym', 'amenidades'),
('Jardín', 'garden', 'amenidades'),
('Terraza', 'terrace', 'amenidades'),
('Cochera', 'garage', 'espacios'),
('Depósito', 'storage', 'espacios'),
('Lavandería', 'laundry', 'espacios');

-- Tabla de usuarios administrativos
CREATE TABLE admin_users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'manager', 'agent') DEFAULT 'admin',
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabla de archivos de propiedades (PDFs, documentos)
CREATE TABLE property_documents (
    id INT PRIMARY KEY AUTO_INCREMENT,
    property_id INT NOT NULL,
    document_type ENUM('pdf', 'contract', 'deed', 'plan', 'other') DEFAULT 'pdf',
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size INT DEFAULT 0,
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES admin_users(id)
);

-- Insertar usuario administrador por defecto
INSERT INTO admin_users (first_name, last_name, email, password, role) VALUES
('Admin', 'Elige Donde', 'admin@remax-peru.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');
-- Contraseña: password
