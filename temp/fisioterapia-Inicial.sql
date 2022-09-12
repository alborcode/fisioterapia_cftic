-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 12-09-2022 a las 15:36:15
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `fisioterapia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `idcita` int(11) NOT NULL,
  `idfacultativo` int(11) NOT NULL,
  `idpaciente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas_disponibles`
--

CREATE TABLE `citas_disponibles` (
  `id` int(11) NOT NULL,
  `idfacultativo` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` int(11) NOT NULL,
  `disponible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220912130512', '2022-09-12 13:05:19', 601);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `idespecialidad` int(11) NOT NULL,
  `especialidad` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`idespecialidad`, `especialidad`) VALUES
(1, 'FISIOTERAPEUTA'),
(2, 'OSTEOPATA');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facultativos`
--

CREATE TABLE `facultativos` (
  `idfacultativo` int(11) NOT NULL,
  `especialidad` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido2` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `facultativos`
--

INSERT INTO `facultativos` (`idfacultativo`, `especialidad`, `idusuario`, `nombre`, `apellido1`, `apellido2`, `telefono`) VALUES
(1, 1, 2, 'Alberto', 'Rico', 'Fernández', '654789987'),
(2, 2, 3, 'Antonio', 'Bermejo', 'Caro', '612345678');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `informes`
--

CREATE TABLE `informes` (
  `idinforme` int(11) NOT NULL,
  `idfacultativo` int(11) NOT NULL,
  `idpaciente` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipoinforme` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalle` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `messenger_messages`
--

INSERT INTO `messenger_messages` (`id`, `body`, `headers`, `queue_name`, `created_at`, `available_at`, `delivered_at`) VALUES
(1, 'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:21:\\\"messenger.bus.default\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:51:\\\"Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\\":2:{s:60:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0message\\\";O:39:\\\"Symfony\\\\Bridge\\\\Twig\\\\Mime\\\\TemplatedEmail\\\":4:{i:0;s:41:\\\"registration/confirmation_email.html.twig\\\";i:1;N;i:2;a:3:{s:9:\\\"signedUrl\\\";s:176:\\\"http://localhost:8000/verify/email?expires=1662992310&id=1&signature=Gq2I6LdS82jXYUkpd5f0l22LxHo%2Fgbub4MGJReh%2FkLg%3D&token=IGLjNWBOZg%2BXoA58NMhhCmKvAHB4D%2FL7UbGeKhdosRk%3D\\\";s:19:\\\"expiresAtMessageKey\\\";s:26:\\\"%count% hour|%count% hours\\\";s:20:\\\"expiresAtMessageData\\\";a:1:{s:7:\\\"%count%\\\";i:1;}}i:3;a:6:{i:0;N;i:1;N;i:2;N;i:3;N;i:4;a:0:{}i:5;a:2:{i:0;O:37:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\\":2:{s:46:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0headers\\\";a:3:{s:4:\\\"from\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:4:\\\"From\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:22:\\\"admin@alborcode.org.es\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:25:\\\"Clinica Fisio Salud Admin\\\";}}}}s:2:\\\"to\\\";a:1:{i:0;O:47:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:2:\\\"To\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:58:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\MailboxListHeader\\0addresses\\\";a:1:{i:0;O:30:\\\"Symfony\\\\Component\\\\Mime\\\\Address\\\":2:{s:39:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0address\\\";s:26:\\\"albertorubiobote@gmail.com\\\";s:36:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Address\\0name\\\";s:0:\\\"\\\";}}}}s:7:\\\"subject\\\";a:1:{i:0;O:48:\\\"Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\\":5:{s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0name\\\";s:7:\\\"Subject\\\";s:56:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lineLength\\\";i:76;s:50:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0lang\\\";N;s:53:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\AbstractHeader\\0charset\\\";s:5:\\\"utf-8\\\";s:55:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\UnstructuredHeader\\0value\\\";s:27:\\\"Por favor confirma tu Email\\\";}}}s:49:\\\"\\0Symfony\\\\Component\\\\Mime\\\\Header\\\\Headers\\0lineLength\\\";i:76;}i:1;N;}}}s:61:\\\"\\0Symfony\\\\Component\\\\Mailer\\\\Messenger\\\\SendEmailMessage\\0envelope\\\";N;}}', '[]', 'default', '2022-09-12 13:18:30', '2022-09-12 13:18:30', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `idpaciente` int(11) NOT NULL,
  `provincia` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `nombre` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido1` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido2` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `direccion` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `codigopostal` int(11) DEFAULT NULL,
  `poblacion` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincias`
--

CREATE TABLE `provincias` (
  `idprovincia` int(11) NOT NULL,
  `provincia` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `provincias`
--

INSERT INTO `provincias` (`idprovincia`, `provincia`) VALUES
(1, 'Araba/Álava'),
(2, 'Albacete'),
(3, 'Alicante/Alacant'),
(4, 'Almería'),
(5, 'Ávila'),
(6, 'Badajoz'),
(7, 'Balears, Illes'),
(8, 'Barcelona'),
(9, 'Burgos'),
(10, 'Cáceres'),
(11, 'Cádiz'),
(12, 'Castellón/Castelló'),
(13, 'Ciudad Real'),
(14, 'Córdoba'),
(15, 'Coruña, A'),
(16, 'Cuenca'),
(17, 'Girona'),
(18, 'Granada'),
(19, 'Guadalajara'),
(20, 'Gipuzkoa'),
(21, 'Huelva'),
(22, 'Huesca'),
(23, 'Jaén'),
(24, 'León'),
(25, 'Lleida'),
(26, 'Rioja, La'),
(27, 'Lugo'),
(28, 'Madrid'),
(29, 'Málaga'),
(30, 'Murcia'),
(31, 'Navarra'),
(32, 'Ourense'),
(33, 'Asturias'),
(34, 'Palencia'),
(35, 'Palmas, Las'),
(36, 'Pontevedra'),
(37, 'Salamanca'),
(38, 'Santa Cruz de Tenerife'),
(39, 'Cantabria'),
(40, 'Segovia'),
(41, 'Sevilla'),
(42, 'Soria'),
(43, 'Tarragona'),
(44, 'Teruel'),
(45, 'Toledo'),
(46, 'Valencia/València'),
(47, 'Valladolid'),
(48, 'Bizkaia'),
(49, 'Zamora'),
(50, 'Zaragoza'),
(51, 'Ceuta'),
(52, 'Melilla');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reset_password_request`
--

CREATE TABLE `reset_password_request` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `selector` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hashed_token` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requested_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `expires_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `idturno` int(11) NOT NULL,
  `idfacultativo` int(11) NOT NULL,
  `diasemana` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `horainicio` int(11) NOT NULL,
  `horafin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `idusuario` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`idusuario`, `email`, `roles`, `password`, `is_verified`) VALUES
(1, 'albertorubiobote@gmail.com', '[\"ROLE_ADMINISTRATIVO\"]', '$2y$13$MqwS4OIf.jo10/M6ildIp.WrCs56dUBpZL5qZ8hohhgATiH0zGIu6', 1),
(2, 'arubiobote@gmail.com', '[\"ROLE_FACULTATIVO\"]', '$2y$13$D9iuP0qp4nNb570/CVB8JOprTmhBf6TgbCl7sORnkv7PDTPGKXr0a', 1),
(3, 'alborcode@gmail.com', '[\"ROLE_FACULTATIVO\"]', '$2y$13$A9s9586KrVbva9ZbtYpP9uqY7x8hzZK3Kkaolx/ksXhIfPkmxySwy', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vacaciones`
--

CREATE TABLE `vacaciones` (
  `idvacaciones` int(11) NOT NULL,
  `idfacultativo` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `dianotrabajado` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`idcita`),
  ADD KEY `IDX_B88CF8E5D5988285` (`idfacultativo`),
  ADD KEY `IDX_B88CF8E56C1EE153` (`idpaciente`);

--
-- Indices de la tabla `citas_disponibles`
--
ALTER TABLE `citas_disponibles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_AB4E2BFBD5988285` (`idfacultativo`);

--
-- Indices de la tabla `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`idespecialidad`);

--
-- Indices de la tabla `facultativos`
--
ALTER TABLE `facultativos`
  ADD PRIMARY KEY (`idfacultativo`),
  ADD UNIQUE KEY `UNIQ_C0A65F2FFD61E233` (`idusuario`),
  ADD KEY `IDX_C0A65F2FACB064F9` (`especialidad`);

--
-- Indices de la tabla `informes`
--
ALTER TABLE `informes`
  ADD PRIMARY KEY (`idinforme`),
  ADD KEY `IDX_E47FD09AD5988285` (`idfacultativo`),
  ADD KEY `IDX_E47FD09A6C1EE153` (`idpaciente`);

--
-- Indices de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`idpaciente`),
  ADD UNIQUE KEY `UNIQ_971B7851FD61E233` (`idusuario`),
  ADD KEY `IDX_971B7851D39AF213` (`provincia`);

--
-- Indices de la tabla `provincias`
--
ALTER TABLE `provincias`
  ADD PRIMARY KEY (`idprovincia`);

--
-- Indices de la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_7CE748A8D93D649` (`user`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`idturno`),
  ADD KEY `IDX_B8555818D5988285` (`idfacultativo`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`idusuario`),
  ADD UNIQUE KEY `UNIQ_EF687F2E7927C74` (`email`);

--
-- Indices de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD PRIMARY KEY (`idvacaciones`),
  ADD KEY `IDX_CAA83E94D5988285` (`idfacultativo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `idcita` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `citas_disponibles`
--
ALTER TABLE `citas_disponibles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `facultativos`
--
ALTER TABLE `facultativos`
  MODIFY `idfacultativo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `informes`
--
ALTER TABLE `informes`
  MODIFY `idinforme` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `idpaciente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `idturno` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `idusuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  MODIFY `idvacaciones` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `FK_B88CF8E56C1EE153` FOREIGN KEY (`idpaciente`) REFERENCES `pacientes` (`idpaciente`),
  ADD CONSTRAINT `FK_B88CF8E5D5988285` FOREIGN KEY (`idfacultativo`) REFERENCES `facultativos` (`idfacultativo`);

--
-- Filtros para la tabla `citas_disponibles`
--
ALTER TABLE `citas_disponibles`
  ADD CONSTRAINT `FK_AB4E2BFBD5988285` FOREIGN KEY (`idfacultativo`) REFERENCES `facultativos` (`idfacultativo`);

--
-- Filtros para la tabla `facultativos`
--
ALTER TABLE `facultativos`
  ADD CONSTRAINT `FK_C0A65F2FACB064F9` FOREIGN KEY (`especialidad`) REFERENCES `especialidades` (`idespecialidad`),
  ADD CONSTRAINT `FK_C0A65F2FFD61E233` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`);

--
-- Filtros para la tabla `informes`
--
ALTER TABLE `informes`
  ADD CONSTRAINT `FK_E47FD09A6C1EE153` FOREIGN KEY (`idpaciente`) REFERENCES `pacientes` (`idpaciente`),
  ADD CONSTRAINT `FK_E47FD09AD5988285` FOREIGN KEY (`idfacultativo`) REFERENCES `facultativos` (`idfacultativo`);

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `FK_971B7851D39AF213` FOREIGN KEY (`provincia`) REFERENCES `provincias` (`idprovincia`),
  ADD CONSTRAINT `FK_971B7851FD61E233` FOREIGN KEY (`idusuario`) REFERENCES `usuarios` (`idusuario`);

--
-- Filtros para la tabla `reset_password_request`
--
ALTER TABLE `reset_password_request`
  ADD CONSTRAINT `FK_7CE748A8D93D649` FOREIGN KEY (`user`) REFERENCES `usuarios` (`idusuario`);

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `FK_B8555818D5988285` FOREIGN KEY (`idfacultativo`) REFERENCES `facultativos` (`idfacultativo`);

--
-- Filtros para la tabla `vacaciones`
--
ALTER TABLE `vacaciones`
  ADD CONSTRAINT `FK_CAA83E94D5988285` FOREIGN KEY (`idfacultativo`) REFERENCES `facultativos` (`idfacultativo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
