-- 1. Obtener los detalles completos de todos los usuarios, ordenados alfabéticamente.
SELECT * FROM `usuarios` ORDER BY apellido ASC;

-- 2. Obtener los detalles completos de todos los productos líquidos.
SELECT * FROM `productos` WHERE tipo = 'liquido';

-- 3. Obtener todas las compras en los cuales la cantidad esté entre 6 y 10 inclusive.
SELECT * FROM `ventas` WHERE cantidad >= 6 AND cantidad <= 10;

-- 4. Obtener la cantidad total de todos los productos vendidos.
SELECT SUM(cantidad) FROM `ventas`;

-- 5. Mostrar los primeros 3 números de productos que se han enviado.
SELECT * FROM `ventas` ORDER BY fecha_de_venta LIMIT 3;

-- 6. Mostrar los nombres del usuario y los nombres de los productos de cada venta.
SELECT v.id, p.nombre as nombre_producto, u.nombre as nombre_usuario, v.id_producto FROM ventas v 
JOIN productos p ON id_producto = p.id 
JOIN usuarios u ON id_usuario = u.id;

-- 7. Indicar el monto (cantidad * precio) por cada una de las ventas.
SELECT v.cantidad * p.precio as monto, p.nombre FROM ventas v JOIN productos p ON id_producto = p.id;

-- 8. Obtener la cantidad total del producto 1003 vendido por el usuario 104.
SELECT SUM(cantidad) as cantidad_total FROM ventas WHERE id_producto = 1003 AND id_usuario = 104;

-- 9. Obtener todos los números de los productos vendidos por algún usuario de 'Avellaneda'
SELECT p.id as id_p, u.id as id_u, u.nombre FROM ventas v 
JOIN productos p ON id_producto = p.id 
JOIN usuarios u ON id_usuario = u.id 
WHERE u.localidad = 'Avellaneda';

-- 10. Obtener los datos completos de los usuarios cuyos nombres contengan la letra ‘u’
SELECT * FROM usuarios WHERE nombre LIKE '%u%';

-- 11. Traer las ventas entre junio del 2020 y febrero 2021.
SELECT * FROM ventas WHERE fecha_de_venta >= '2020-06-01' AND fecha_de_venta < '2021-02-01';

-- 12. Obtener los usuarios registrados antes del 2021.
SELECT * FROM usuarios WHERE fecha_de_registro < '2021-01-01';

-- 13. Agregar el producto llamado ‘Chocolate’, de tipo Sólido y con un precio de 25,35.
INSERT INTO `productos` 
(`codigo_de_barra`, `nombre`, `tipo`, `stock`, `precio`, `fecha_de_creacion`, `fecha_de_modificacion`) 
VALUES 
('77900311','Chocolate','solido', 5, 25.35, '2022-08-10', '2022-08-10');

-- 14. Insertar un nuevo usuario.
INSERT INTO `usuarios` (`nombre`, `apellido`, `clave`, `mail`, `fecha_de_registro`, `localidad`) 
VALUES 
('John','Lennon','imagine','john@mail.com','2022-08-10','NYC');

-- 15. Cambiar los precios de los productos de tipo sólido a 66,60.
UPDATE productos SET precio = 66.60 WHERE tipo = "solido";

-- 16. Cambiar el stock a 0 de todos los productos cuyas cantidades de stock sean menores a 20 inclusive.
UPDATE productos SET stock = 0 WHERE stock <= 20;

-- 17. Eliminar el producto número 1010.
DELETE FROM productos WHERE id = 1010;
