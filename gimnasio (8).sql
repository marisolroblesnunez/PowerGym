-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-07-2025 a las 12:17:06
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gimnasio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clases`
--

CREATE TABLE `clases` (
  `id` int(11) NOT NULL,
  `id_entrenador` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `dia_semana` varchar(15) DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `duracion_minutos` int(11) DEFAULT NULL,
  `cupo_maximo` int(11) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clases`
--

INSERT INTO `clases` (`id`, `id_entrenador`, `nombre`, `descripcion`, `dia_semana`, `hora`, `duracion_minutos`, `cupo_maximo`, `imagen_url`) VALUES
(9, 4, 'Bike', 'Clase de ciclismo indoor para mejorar resistencia cardiovascular', 'Lunes', '08:00:00', 45, 20, 'salaBikes.jpg'),
(10, 5, 'Body Pump', 'Entrenamiento de cuerpo completo con pesas y cardio', 'Martes', '19:00:00', 50, 18, 'salaBodydump.jpg'),
(11, 6, 'Yoga', 'Relajación y estiramiento con técnicas de respiración', 'Miércoles', '09:30:00', 60, 15, 'salaYoga.jpg'),
(12, 7, 'Zumba', 'Baile fitness al ritmo de música latina', 'Jueves', '18:30:00', 55, 25, 'salaZumba.jpg'),
(13, 8, 'Pilates', 'Fortalece el core, mejora tu postura y flexibilidad', 'Viernes', '10:00:00', 50, 12, 'salaPilates.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrenadores`
--

CREATE TABLE `entrenadores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `foto_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `entrenadores`
--

INSERT INTO `entrenadores` (`id`, `nombre`, `especialidad`, `email`, `foto_url`) VALUES
(4, 'Mario Sánchez', 'Bike', 'mario@bike.com', 'entrenadorBike.avif'),
(5, 'Lucía Torres', 'Body Pump', 'lucia@bodypump.com', 'entrenadorabodydump.png'),
(6, 'Paula Díaz', 'Yoga', 'paula@yoga.com', 'entrenadoraYoga.avif'),
(7, 'Jorge Ramos', 'Zumba', 'jorge@zumba.com', 'entrenadorZumba.jpg'),
(8, 'Natalia Fernández', 'Pilates', 'natalia@pilates.com', 'entrenadoraPilates.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inscripciones_clases`
--

CREATE TABLE `inscripciones_clases` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_clase` int(11) DEFAULT NULL,
  `fecha_inscripcion` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inscripciones_clases`
--

INSERT INTO `inscripciones_clases` (`id`, `id_usuario`, `id_clase`, `fecha_inscripcion`) VALUES
(1, 1, 9, '2025-07-07'),
(2, 2, 10, '2025-07-07'),
(3, 3, 11, '2025-07-08'),
(4, 4, 12, '2025-07-08'),
(5, 5, 13, '2025-07-08'),
(6, 6, 9, '2025-07-09'),
(7, 7, 10, '2025-07-09'),
(8, 8, 11, '2025-07-10'),
(9, 21, 9, '2025-07-16'),
(10, 23, 9, '2025-07-17'),
(11, 23, 11, '2025-07-17'),
(12, 23, 13, '2025-07-17'),
(13, 24, 12, '2025-07-17'),
(14, 30, 9, '2025-07-21'),
(15, 30, 12, '2025-07-21'),
(16, 33, 9, '2025-07-21'),
(17, 33, 11, '2025-07-21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `testimonios`
--

CREATE TABLE `testimonios` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `mensaje` text DEFAULT NULL,
  `fecha` date DEFAULT curdate(),
  `visible` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `testimonios`
--

INSERT INTO `testimonios` (`id`, `id_usuario`, `mensaje`, `fecha`, `visible`) VALUES
(3, 1, 'Las instalaciones son excelentes y el personal muy profesional.', '2025-07-18', 1),
(4, 3, 'Estoy muy contenta con las clases dirigidas, especialmente pilates.', '2025-07-18', 1),
(5, 4, 'Buen ambiente, pero deberían ampliar el horario los domingos.', '2025-07-18', 0),
(6, 5, 'Muy buen trato y limpieza. Lo recomiendo.', '2025-07-18', 1),
(7, 6, 'Las duchas están siempre limpias, y eso se agradece.', '2025-07-18', 1),
(8, 7, 'Hay mucha gente en hora punta, pero los entrenadores son atentos.', '2025-07-18', 0),
(9, 8, 'Me encantan las clases de spinning, son muy motivadoras.', '2025-07-18', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_registro` date NOT NULL DEFAULT current_timestamp(),
  `token` varchar(64) NOT NULL,
  `token_recuperacion` varchar(64) NOT NULL,
  `verificado` int(11) NOT NULL,
  `intentos_fallidos` int(11) NOT NULL,
  `bloqueado` tinyint(4) NOT NULL,
  `ultima_conexion` datetime DEFAULT NULL,
  `tipo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `email`, `password`, `fecha_registro`, `token`, `token_recuperacion`, `verificado`, `intentos_fallidos`, `bloqueado`, `ultima_conexion`, `tipo`) VALUES
(1, 'María', 'López', 'maria@gmail.com', '123456', '2025-07-05', '', '', 1, 0, 0, NULL, 0),
(2, 'Javier', 'Pérez', 'javier@gmail.com', '123456', '2025-07-05', '', '', 1, 0, 0, NULL, 0),
(3, 'marisol', 'robles', 'marisolroblesnunez@gmail.com', '$2y$10$cbApdcEMAYDaBnnjpku2SOBPhE1BWXMLWVL..2Tea24a7vakGE1RO', '2025-07-05', 'b9606fd8ab49f89b746d19c86868777615c41863ca5e97f758e43f3c9897f028', 'b901e4ff3687966ab2368b35350d5249b915e6de7493d59379749181961bf0b3', 0, 0, 0, NULL, 0),
(4, 'Lola', 'nuñez', 'lolita@gmail.com', '$2y$10$iWbR00VCFKWF8JeceBkOiuZEazwbQXM.WehFBG6hn7cbFDYDS0DaS', '2025-07-05', '44ed923f6bde099e1779227faf116080c5876f853d43993791d999c74e1bfbf9', '', 0, 0, 0, NULL, 0),
(5, 'Mariluz', 'ávila', 'chulita@gmail.com', '$2y$10$EsA6lyXMvRXW5Swz3CnN4eLnbCB50hBujy0ZwR3qv115oiErpuW6u', '2025-07-06', '7b22b16fe9c56c03b7ccc7bcfcbc62fed7a1790d7673e27a54ef9b206d2d3cde', '', 0, 0, 0, NULL, 0),
(6, 'sofia', 'ayllon', 'sofia@gmail.com', '$2y$10$FJQBdxEe4dTKg0jKPHU5MecmXQTCbzyXVZ7TXhilmGSqc/SubUviK', '2025-07-06', '2d9b33d7a0851c0853ddb3c0ea5180871e85de6172ce85bb9dda6db4db7a9251', '', 0, 0, 0, NULL, 0),
(7, 'Lorena', 'Sevilla', 'ss@gmail.com', '$2y$10$qgDi3qD2OSoktBD92zUx..EYIcHoTZcn16/8DSD0Nbz8A8lki.lBC', '2025-07-06', '', '', 1, 0, 0, '2025-07-06 00:18:06', 0),
(8, 'Alberto', 'Barranco', 'alberto@gmail.com', '$2y$10$s3aK4tf8F8YCerI0jwypseTlvhTytvoTFoWLfTWIy5Zhk8Ul8CzTC', '2025-07-06', '', '', 1, 0, 0, '2025-07-06 00:20:00', 0),
(11, '', '', 'jj@gmail.com', '$2y$10$h32f9Sijyzg3Qo5HcW7m1.p1Q6kFdCyvxhXyglm8rSNTVu5cXQD7K', '2025-07-10', 'd5f482f010c0b2a562f0e5f46d1400e0a62143a6669ef654b47609f257bbb593', '', 0, 0, 0, NULL, 0),
(12, '', '', 'ui@gmail.com', '$2y$10$p4vYWHTRlCGjNYII8Hq6neLBbFhUY92cvcqIaOz0ywMUbNXGYGJNK', '2025-07-10', '', '', 1, 0, 0, '2025-07-11 08:41:40', 0),
(13, '', '', 'uuu@gmail.com', '$2y$10$YttgW96lbwKyHGQMYVPzEuk5cD6OIjT4fTpmSVDV6gTKnzwQtvW9S', '2025-07-16', '', '', 1, 0, 0, '2025-07-16 01:33:24', 0),
(14, '', '', 'tt@gmail.com', '$2y$10$NqIzf8O6lOyHtuDr.NDIfezz4Oya2GCm2yax5nVuZ8Wm3daDeV3xm', '2025-07-16', '1607e77ae36b05f2c46459a27d72f8401a2528aac9e1be392e1f48c0891da5d3', '', 0, 0, 0, NULL, 0),
(15, '', '', 'pp@gmail.com', '$2y$10$UTGiqfD8pltG0NY/tijiWeH.lxzvgU9KKhliRGKnPHP927laD2qTG', '2025-07-16', '06b68b2ec05e485b47eb6662d1de13da162e30a75a7d6607aeadc5fb31209f5b', '', 0, 0, 0, NULL, 0),
(16, '', '', 'ww@gmail.com', '$2y$10$GlPqkWs1iyJC.0/lmK.lU.3jcSN0LRx3h7ylrEYlceGqq7uMxXr3a', '2025-07-16', '', '', 1, 0, 0, '2025-07-16 02:14:05', 0),
(17, '', '', 'puri@gmail.com', '$2y$10$fwfxhZ6lTVTspI0njdm6Pu.k1oDcwKZGoaWlhruhRy4jiVPpXNM2i', '2025-07-16', 'bf77735fe029bd3e8a137f605cd8f3a3e0d84967482aa5f81c0a44e1e8a3721f', '', 0, 0, 0, NULL, 0),
(18, '', '', 'y@gmail.com', '$2y$10$yWD8AJkCLtjqVa6vggwbzecK1pX/5/IK5iLdVr9VIcE17HB0j5EdS', '2025-07-16', '', '', 1, 0, 0, NULL, 0),
(19, '', '', 'o@gmail.com', '$2y$10$nE.9aKQIsFoTiv2w.NOOXOe4Gq3OvG5WxBgwrp8iUhN9qhzgsapfa', '2025-07-16', '0a2984c18ebcd8d615a4e32545196aec53736b32c90af935bb0d7c478affa5a4', '', 0, 0, 0, NULL, 0),
(20, '', '', 'di@gmail.com', '$2y$10$iFu61eLV5B.gnRxH41kTV./H.cku4RAp28YioI9CrLLk0AMx66lb2', '2025-07-16', '', '', 1, 0, 0, '2025-07-16 16:55:26', 0),
(21, '', '', 'uno@gmail.com', '$2y$10$4ocQSZm5KIEemNxg6FRFReFZvt8.uY5nrXdkGnFRPAjvqAE1pkY4.', '2025-07-16', '', '', 1, 0, 0, '2025-07-16 16:59:32', 0),
(22, '', '', 'll@gmail.com', '$2y$10$TYEGadte/qFlsKaO2IHHdOUQrSnDQqI7hr5o3oWToR0K6OKGq/NYS', '2025-07-17', 'b21de498a4e6e25f08b79f88e866efe86d09e1db7688cc2776c6387d11750874', '', 0, 0, 0, NULL, 0),
(23, '', '', 'vv@gmail.com', '$2y$10$HREgfgBnt7ocrCaLl09JBubU29FXSGsw4Vw8nvcj5cu2Ga2wUDYfq', '2025-07-17', '', '', 1, 0, 0, '2025-07-17 19:32:27', 0),
(24, '', '', 'kk@gmail.com', '$2y$10$ukpC7QmxJaqMixbx05Boy..vqtyMQZ6dvpPonbMWlnyiHOJQiyDjq', '2025-07-17', '', '', 1, 0, 0, '2025-07-17 19:26:50', 0),
(25, '', '', 'rr@gmail.com', '$2y$10$0Y4sifXpE92Bn7KInQQcAeSjUDHDx2e0Y6gr2uhN4TzoItqpN.QKq', '2025-07-18', '46604540a60045950f289f4b07259064d4b4dcb263d822604728221dc35ba51a', '', 0, 0, 0, NULL, 0),
(26, '', '', 'yu@gmail.com', '$2y$10$lZJgi8j/bgWZhfi0ba4J3OXUomBW38/wdkCioMDVMiZytt59NnTgi', '2025-07-18', '0cda69acecf074d5083f4e1eed18dd123bd3407ca5642b3507cda40d28bcc2d6', '', 0, 0, 0, NULL, 0),
(27, '', '', 'ti@gmail.com', '$2y$10$UwmJp8utcJxBeCJwb0LqdeobFBg0/7OqaOiTXJmxCGkapuZxLKDpS', '2025-07-18', '2032e54033f3d9ec82e622c2f08fcf6dfb140b20422caf1f8fbdced7ab20fd6c', '', 0, 0, 0, NULL, 0),
(28, '', '', '44@gmail.com', '$2y$10$u7tRP..CrAOeCGulfURGZuFgPeq8IwjO1xHcGFy3UKVL7oATS4bt.', '2025-07-21', '1cbb6d737c09bf167a4b2819e4c87528a24bb603cef98353cc6d266e3bb46043', '', 0, 0, 0, NULL, 0),
(29, '', '', 'ee@gmail.com', '$2y$10$975OxV48ms.udVwxdbgVzuWvS109Q/FdRE2fTfIl/AKkRQLV6keAC', '2025-07-21', '79e6ed1a15cef304934735896e91a2c55049b8e1282b540e340c2f7a08f42c7a', '', 0, 0, 0, NULL, 0),
(30, '', '', 'turu@gmail.com', '$2y$10$XOKPVRM4Ir22lXpCNW6bm.iYzfgpiPJCyL6BZqAFLjwBX9nsA.UKK', '2025-07-21', '', '', 1, 0, 0, '2025-07-21 09:27:05', 0),
(31, '', '', 'gorila@gmail.com', '$2y$10$GQ0qI.ee.rvHmkD/J5dCpu/RTWb4bHNp08xXBem0AOFOuB3pu1uBW', '2025-07-21', '', '', 1, 0, 0, NULL, 0),
(32, '', '', 'oo@gmail.com', '$2y$10$1Eh9YxSxAh7gLO2B6Q69F.hJMo8YKGjYaIikvmaS6NE3oAlVF89XO', '2025-07-21', '', '', 1, 0, 0, '2025-07-21 10:51:14', 0),
(33, '', '', 'una@gmail.com', '$2y$10$2GAKJwo.UfWg885eyBXEeOeDWYaJ0QcNkqf/rk7gK6xFXSDWt567O', '2025-07-21', '', '', 1, 0, 0, '2025-07-21 10:09:21', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clases`
--
ALTER TABLE `clases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_entrenador` (`id_entrenador`);

--
-- Indices de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `inscripciones_clases`
--
ALTER TABLE `inscripciones_clases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cliente` (`id_usuario`),
  ADD KEY `id_clase` (`id_clase`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clases`
--
ALTER TABLE `clases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `entrenadores`
--
ALTER TABLE `entrenadores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `inscripciones_clases`
--
ALTER TABLE `inscripciones_clases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `testimonios`
--
ALTER TABLE `testimonios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `inscripciones_clases`
--
ALTER TABLE `inscripciones_clases`
  ADD CONSTRAINT `inscripciones_clases_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `inscripciones_clases_ibfk_2` FOREIGN KEY (`id_clase`) REFERENCES `clases` (`id`);

--
-- Filtros para la tabla `testimonios`
--
ALTER TABLE `testimonios`
  ADD CONSTRAINT `testimonios_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
