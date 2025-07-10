-- Datos de ejemplo para Elige Donde

-- Insertar más agentes
INSERT INTO agents (first_name, last_name, email, phone, cell_phone, photo, license_number) VALUES
('Ana María', 'Gonzales Pérez', 'agonzales@remax-centralrealty.com', '01-2345681', '987123456', 'agent_ana.jpg', 'LIC-2023-001'),
('Roberto Carlos', 'Silva Martinez', 'rsilva@remax-centralrealty.com', '01-2345682', '976432109', 'agent_roberto.jpg', 'LIC-2023-002'),
('Patricia Elena', 'Ramirez Torres', 'pramirez@remax-centralrealty.com', '01-2345683', '965321987', 'agent_patricia.jpg', 'LIC-2023-003'),
('Jorge Luis', 'Morales Díaz', 'jmorales@remax-centralrealty.com', '01-2345684', '954210876', 'agent_jorge.jpg', 'LIC-2023-004');

-- Insertar propiedades de ejemplo
INSERT INTO properties (
    property_code, title, description, property_type_id, transaction_type,
    price_pen, price_usd, location_id, address, latitude, longitude,
    land_area, built_area, bedrooms, bathrooms, half_bathrooms,
    parking_spaces, floors, age_years, featured, published_at
) VALUES
(
    '20241101',
    '¡Venta de Exclusiva Casa en Condominio San Pedro!',
    'Venta de Casa de Lujo en Condominio al estilo Americano

Ubicado a solo 30 minutos de Lima y 10 minutos de las mejores playas del Sur, esta casa ofrece una excelente opción para aquellos que buscan un lugar cómodo y elegante.

¡Vive en un entorno de lujo y tranquilidad en este exclusivo condominio al estilo americano!

DETALLES DE LA CASA:
- Ubicación: Condominio San Pedro, cerca del ingreso con vista al Club House y espectacular vista a la Laguna del Condominio
- Área del terreno: 600 m2 rodeado de áreas verdes
- Área de construcción: 280 m2
- Distribución:

  PRIMER PISO:
  - Sala de estar amplia y luminosa
  - Comedor elegante para reuniones familiares
  - Cocina moderna y funcional
  - Dormitorio de servicio con baño completo
  - Medio baño para visitas
  - Lavandería independiente
  - Amplia terraza con vista a los jardines

  SEGUNDO PISO:
  - Dormitorio principal con walking closet y baño en suite
  - 2 dormitorios adicionales con closets empotrados
  - Baño completo compartido
  - Sala de TV/estudio
  - Balcón con vista panorámica

CARACTERÍSTICAS ADICIONALES:
- Casa de lujo con diseño moderno y funcional
- Amplias áreas verdes y vistas espectaculares
- Excelente seguridad y amenidades

¡No pierdas la oportunidad de vivir en esta hermosa casa! Contacta hoy mismo para obtener más información y programar una visita.',
    1, 'venta', 1486800.00, 420000.00, 8,
    'Condominio San Pedro, Lurín, Lima', -12.2856, -76.8714,
    600.00, 280.00, 5, 4, 1, 2, 2, 25, NOW()
),
(
    '20241102',
    'Casa Urbana en Venta - Miraflores',
    'Hermosa casa urbana en una de las mejores zonas de Miraflores. Ideal para familias que buscan comodidad y ubicación privilegiada.

    La propiedad cuenta con:
    - Sala amplia con vista a la calle
    - Comedor independiente
    - Cocina equipada
    - 3 dormitorios en segundo piso
    - 2 baños completos
    - Patio posterior
    - Estacionamiento para 1 vehículo

    Ubicada cerca de parques, centros comerciales y transporte público.',
    1, 'venta', 4071000.00, 1150000.00, 2,
    'Calle Los Pinos 456, Miraflores, Lima', -12.1196, -77.0279,
    337.00, 500.00, 3, 2, 0, 1, 2, 15, NOW()
),
(
    '20241103',
    'Casa de Playa en Condominio en Venta',
    'Espectacular casa de playa en condominio exclusivo frente al mar. Perfect para vacaciones o inversión.

    Características:
    - Vista directa al mar
    - Acceso privado a la playa
    - Sala comedor con terraza
    - 4 dormitorios
    - 3 baños completos
    - Piscina del condominio
    - Seguridad 24 horas
    - Áreas verdes
    - Club house',
    1, 'venta', 1486800.00, 420000.00, 8,
    'Condominio Playa del Sol, Lurín, Lima', -12.3056, -76.8514,
    625.00, 625.00, 4, 3, 0, 2, 2, 5, NOW()
),
(
    '20241104',
    'Casa Urbana en Venta - Santiago de Surco',
    'Moderna casa en zona residencial de Santiago de Surco. Excelente para familias.

    Incluye:
    - Sala de estar moderna
    - Comedor amplio
    - Cocina integral
    - 4 dormitorios
    - 3 baños
    - Jardín posterior
    - Garaje doble
    - Acabados de primera',
    1, 'venta', 1097400.00, 310000.00, 5,
    'Av. Principal 789, Santiago de Surco, Lima', -12.1489, -76.9981,
    267.00, 0.00, 4, 3, 0, 2, 2, 8, 0, NOW()
),
(
    '20241105',
    'Casa en Venta - Barranco',
    'Casa clásica en el corazón de Barranco, distrito bohemio y cultural de Lima.

    Características:
    - Arquitectura colonial restaurada
    - Sala principal con techos altos
    - Comedor elegante
    - Cocina remodelada
    - 3 dormitorios
    - 2 baños
    - Patio interior
    - Ubicación privilegiada',
    1, 'venta', 1840800.00, 520000.00, 4,
    'Jirón Unión 321, Barranco, Lima', -12.1467, -77.0208,
    363.46, 500.00, 3, 2, 0, 1, 2, 80, 0, NOW()
),
(
    '20241106',
    'Local Comercial en Venta',
    'Excelente local comercial en zona comercial de alta concurrencia.

    Detalles:
    - Área total: 337 m²
    - Frente amplio
    - Estacionamiento propio
    - Ideal para diversos negocios
    - Documentos en orden',
    5, 'venta', 4071000.00, 1150000.00, 2,
    'Av. Comercial 123, Miraflores, Lima', -12.1296, -77.0276,
    337.00, 500.00, 0, 2, 0, 3, 1, 20, 0, NOW()
),
(
    '20241107',
    'Casa de Playa en Venta',
    'Hermosa casa frente al mar, ideal para descanso y relajación.

    Incluye:
    - Vista panorámica al océano
    - Sala comedor con terraza
    - 3 dormitorios
    - 2 baños
    - Cocina equipada
    - Jardín con acceso directo a la playa
    - Zona de parrilla',
    1, 'venta', 1486800.00, 420000.00, 8,
    'Playa Los Delfines, Lurín, Lima', -12.3156, -76.8414,
    625.00, 625.00, 3, 2, 0, 2, 1, 10, 0, NOW()
),
(
    '20241108',
    'Terreno en Venta',
    'Amplio terreno en zona de expansión urbana, excelente para proyectos inmobiliarios.

    Características:
    - Área: 1200 m²
    - Zonificación residencial
    - Servicios básicos disponibles
    - Acceso pavimentado
    - Documentos en regla',
    4, 'venta', 1200000.00, 340000.00, 5,
    'Sector Los Pinos, Santiago de Surco, Lima', -12.1689, -76.9881,
    1200.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NOW()
);

-- Insertar imágenes de propiedades
INSERT INTO property_images (property_id, image_url, alt_text, is_main, order_index) VALUES
-- Propiedad 1 - Casa en Condominio
(1, 'property_1_main.jpg', 'Vista principal de la casa en condominio', 1, 1),
(1, 'property_1_living.jpg', 'Sala de estar amplia', 0, 2),
(1, 'property_1_kitchen.jpg', 'Cocina moderna', 0, 3),
(1, 'property_1_bedroom.jpg', 'Dormitorio principal', 0, 4),
(1, 'property_1_garden.jpg', 'Jardín y áreas verdes', 0, 5),

-- Propiedad 2 - Casa Urbana Miraflores
(2, 'property_2_main.jpg', 'Fachada de casa en Miraflores', 1, 1),
(2, 'property_2_interior.jpg', 'Interior moderno', 0, 2),
(2, 'property_2_room.jpg', 'Dormitorio', 0, 3),

-- Propiedad 3 - Casa de Playa
(3, 'property_3_main.jpg', 'Casa frente al mar', 1, 1),
(3, 'property_3_ocean.jpg', 'Vista al océano', 0, 2),
(3, 'property_3_pool.jpg', 'Piscina del condominio', 0, 3),

-- Propiedad 4 - Casa Surco
(4, 'property_4_main.jpg', 'Casa moderna en Surco', 1, 1),
(4, 'property_4_garden.jpg', 'Jardín posterior', 0, 2),

-- Propiedad 5 - Casa Barranco
(5, 'property_5_main.jpg', 'Casa colonial en Barranco', 1, 1),
(5, 'property_5_interior.jpg', 'Interior elegante', 0, 2),

-- Propiedad 6 - Local Comercial
(6, 'property_6_main.jpg', 'Local comercial', 1, 1),
(6, 'property_6_interior.jpg', 'Interior del local', 0, 2),

-- Propiedad 7 - Casa de Playa 2
(7, 'property_7_main.jpg', 'Casa frente al mar', 1, 1),
(7, 'property_7_terrace.jpg', 'Terraza con vista al mar', 0, 2),

-- Propiedad 8 - Terreno
(8, 'property_8_main.jpg', 'Terreno disponible', 1, 1);

-- Insertar características a las propiedades
INSERT INTO property_feature_pivot (property_id, feature_id, value) VALUES
-- Casa en Condominio (ID: 1)
(1, 1, 'Municipal'), -- Agua
(1, 2, 'Empresa Privada'), -- Energía
(1, 3, 'No Tiene'), -- Gas
(1, 4, 'Municipal'), -- Desagüe
(1, 6, '24 horas'), -- Seguridad
(1, 7, 'Sí'), -- Piscina
(1, 9, 'Áreas verdes'), -- Jardín
(1, 10, 'Terraza amplia'), -- Terraza
(1, 11, '2 Paralelo Techado'), -- Cochera

-- Casa Urbana Miraflores (ID: 2)
(2, 1, 'Municipal'),
(2, 2, 'Empresa Privada'),
(2, 4, 'Municipal'),
(2, 5, 'Internet/Cable'),
(2, 11, '1 Paralelo Techado'),

-- Casa de Playa (ID: 3)
(3, 1, 'Municipal'),
(3, 2, 'Empresa Privada'),
(3, 4, 'Municipal'),
(3, 6, '24 horas'),
(3, 7, 'Sí'),
(3, 9, 'Vista al mar'),
(3, 11, '2 Paralelo Techado'),

-- Casa Surco (ID: 4)
(4, 1, 'Municipal'),
(4, 2, 'Empresa Privada'),
(4, 3, 'Sí'),
(4, 4, 'Municipal'),
(4, 5, 'Internet/Cable'),
(4, 9, 'Jardín posterior'),
(4, 11, '2 Paralelo Techado'),

-- Casa Barranco (ID: 5)
(5, 1, 'Municipal'),
(5, 2, 'Empresa Privada'),
(5, 4, 'Municipal'),
(5, 5, 'Internet/Cable'),
(5, 11, '1 En fila'),

-- Local Comercial (ID: 6)
(6, 1, 'Municipal'),
(6, 2, 'Empresa Privada'),
(6, 4, 'Municipal'),
(6, 5, 'Internet/Cable'),
(6, 11, '3 Paralelo Techado'),

-- Casa de Playa 2 (ID: 7)
(7, 1, 'Municipal'),
(7, 2, 'Empresa Privada'),
(7, 4, 'Municipal'),
(7, 9, 'Frente al mar'),
(7, 10, 'Vista al océano'),
(7, 11, '2 Paralelo Techado'),

-- Terreno (ID: 8)
(8, 1, 'Disponible'),
(8, 2, 'Disponible'),
(8, 4, 'Disponible');

-- Insertar relaciones entre propiedades y agentes
INSERT INTO property_agent (property_id, agent_id, is_primary, role, commission_percentage, assigned_at) VALUES
-- Propiedad 1: Catherine como principal, Ana como colaboradora
(1, 1, 1, 'principal', 70.00, NOW()),
(1, 4, 0, 'colaborador', 30.00, NOW()),

-- Propiedad 2: María Elena como principal
(2, 2, 1, 'principal', 100.00, NOW()),

-- Propiedad 3: Carlos como principal, Roberto como co-agente
(3, 3, 1, 'principal', 60.00, NOW()),
(3, 4, 0, 'co-agente', 40.00, NOW()),

-- Propiedad 4: Jorge como principal
(4, 5, 1, 'principal', 100.00, NOW()),

-- Propiedad 5: Patricia como principal, Catherine como colaboradora
(5, 6, 1, 'principal', 75.00, NOW()),
(5, 1, 0, 'colaborador', 25.00, NOW()),

-- Propiedad 6: María Elena como principal, Carlos como colaborador
(6, 2, 1, 'principal', 80.00, NOW()),
(6, 3, 0, 'colaborador', 20.00, NOW()),

-- Propiedad 7: Roberto como principal
(7, 4, 1, 'principal', 100.00, NOW()),

-- Propiedad 8: Jorge como principal, Patricia como co-agente
(8, 5, 1, 'principal', 50.00, NOW()),
(8, 6, 0, 'co-agente', 50.00, NOW());

-- Insertar algunas consultas de ejemplo (actualizadas con agentes correctos)
INSERT INTO inquiries (property_id, agent_id, name, email, phone, message, inquiry_type, status) VALUES
(1, 1, 'María José Fernández', 'maria.fernandez@email.com', '987654321', 'Estoy interesada en conocer más detalles sobre esta propiedad. ¿Podrían contactarme?', 'info', 'new'),
(1, 1, 'Carlos Mendoza', 'carlos.mendoza@email.com', '976543210', 'Me gustaría agendar una visita para el fin de semana', 'visit', 'new'),
(2, 2, 'Ana García', 'ana.garcia@email.com', '965432109', '¿Está disponible para visita esta semana?', 'visit', 'contacted'),
(3, 3, 'Roberto Silva', 'roberto.silva@email.com', '954321098', 'Información sobre financiamiento disponible', 'info', 'new'),
(4, 5, 'Patricia Ramos', 'patricia.ramos@email.com', '943210987', '¿El precio es negociable?', 'call', 'new'),
(5, 6, 'Luis Martínez', 'luis.martinez@email.com', '932109876', 'Me interesa esta propiedad histórica', 'info', 'new'),
(6, 2, 'Carmen López', 'carmen.lopez@email.com', '921098765', 'Consulta sobre el local comercial', 'call', 'contacted'),
(7, 4, 'Diego Herrera', 'diego.herrera@email.com', '910987654', 'Información sobre casa de playa', 'visit', 'new');

-- Actualizar algunos campos faltantes en propiedades
UPDATE properties SET featured = 1 WHERE id IN (1, 3, 5, 7);

-- Verificar los datos insertados
SELECT 'Agentes insertados:' as info, COUNT(*) as total FROM agents;
SELECT 'Propiedades insertadas:' as info, COUNT(*) as total FROM properties;
SELECT 'Imágenes insertadas:' as info, COUNT(*) as total FROM property_images;
SELECT 'Características asignadas:' as info, COUNT(*) as total FROM property_feature_pivot;
SELECT 'Consultas insertadas:' as info, COUNT(*) as total FROM inquiries;
