-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: db
-- Generation Time: Feb 22, 2025 at 08:08 AM
-- Server version: 9.1.0
-- PHP Version: 8.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Gestion_Horarios`
--

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` int NOT NULL,
  `professor_id` int NOT NULL,
  `vocational_training_id` int NOT NULL,
  `module_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `name` varchar(50) NOT NULL,
  `course` enum('first','second') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `sessions_number` int NOT NULL,
  `color` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `time_Stamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `professor_id`, `vocational_training_id`, `module_code`, `name`, `course`, `sessions_number`, `color`, `time_Stamp`) VALUES
(3, 2, 1, 'MP0615', 'Diseño de Interfaces Web', 'first', 60, '#e91c93', '2025-01-29 06:57:16'),
(4, 2, 1, 'EXTRA', 'Lengua estranjera profesional II', 'second', 20, '#e91c93', '2025-01-29 06:57:16'),
(5, 2, 2, 'MP0618', 'Empresa e Iniciativa Emprendedora', 'second', 30, '#e91c93', '2025-01-29 06:57:16'),
(6, 6, 1, 'MP0156', 'Inglés profesional', 'first', 20, '#e91c93', '2025-01-29 06:57:16'),
(7, 2, 1, 'MP0618', 'Empresa e Iniciativa Emprendedora', 'first', 30, '#e91c93', '2025-01-29 06:57:16'),
(9, 2, 1, 'nuevo color', 'Manuel', 'second', 5, '#e91c93', '2025-02-19 18:33:03'),
(10, 3, 3, 'sgfh', 'sdfg', 'second', 44, '#ce4646', '2025-02-19 18:33:37');

-- --------------------------------------------------------

--
-- Table structure for table `modules_sessions`
--

CREATE TABLE `modules_sessions` (
  `module_id` int NOT NULL,
  `session_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `modules_sessions`
--

INSERT INTO `modules_sessions` (`module_id`, `session_id`) VALUES
(3, 1),
(6, 1),
(6, 2),
(6, 3),
(6, 8);

-- --------------------------------------------------------

--
-- Table structure for table `professors`
--

CREATE TABLE `professors` (
  `id` int NOT NULL,
  `name` varchar(50) NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `second_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `time_stamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `professors`
--

INSERT INTO `professors` (`id`, `name`, `first_name`, `second_name`, `email`, `time_stamp`) VALUES
(1, 'Ignacio', 'Sordo', 'Martínez', 'ignacio.sordo@example.com', '2025-01-28 19:05:38'),
(2, 'Ofelia', 'López', NULL, 'ofelia.lopez@example.com', '2025-01-28 19:05:38'),
(3, 'César', 'Rodríguez', 'Fernández', 'cesar.rodriguez@example.com', '2025-01-28 19:05:38'),
(4, 'Luis', 'González', 'Pérez', 'luis.gonzalez@example.com', '2025-01-28 19:05:38'),
(5, 'Xoana', 'Costa', NULL, 'xoana.costa@example.com', '2025-01-28 19:05:38'),
(6, 'Pedro', 'García', 'García', 'pedrogarcia@sanclemente.net', '2025-01-29 06:35:49');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `day` varchar(9) NOT NULL,
  `time_stamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `start_time`, `end_time`, `day`, `time_stamp`) VALUES
(1, '08:45:00', '09:35:00', 'Monday', '2025-01-28 18:14:11'),
(2, '09:35:00', '10:25:00', 'Monday', '2025-01-28 18:14:11'),
(3, '10:25:00', '11:15:00', 'Monday', '2025-01-28 18:14:11'),
(4, '11:15:00', '12:05:00', 'Monday', '2025-01-28 18:14:11'),
(5, '12:05:00', '12:55:00', 'Monday', '2025-01-28 18:14:11'),
(6, '12:55:00', '13:45:00', 'Monday', '2025-01-28 18:14:11'),
(7, '13:45:00', '14:35:00', 'Monday', '2025-01-28 18:14:11'),
(8, '16:00:00', '16:50:00', 'Monday', '2025-01-28 18:14:11'),
(9, '16:50:00', '17:40:00', 'Monday', '2025-01-28 18:14:11'),
(10, '17:40:00', '18:30:00', 'Monday', '2025-01-28 18:14:11'),
(11, '18:30:00', '19:20:00', 'Monday', '2025-01-28 18:14:11'),
(12, '08:45:00', '09:35:00', 'Tuesday', '2025-01-28 18:14:11'),
(13, '09:35:00', '10:25:00', 'Tuesday', '2025-01-28 18:14:11'),
(14, '10:25:00', '11:15:00', 'Tuesday', '2025-01-28 18:14:11'),
(15, '11:15:00', '12:05:00', 'Tuesday', '2025-01-28 18:14:11'),
(16, '12:05:00', '12:55:00', 'Tuesday', '2025-01-28 18:14:11'),
(17, '12:55:00', '13:45:00', 'Tuesday', '2025-01-28 18:14:11'),
(18, '13:45:00', '14:35:00', 'Tuesday', '2025-01-28 18:14:11'),
(19, '16:00:00', '16:50:00', 'Tuesday', '2025-01-28 18:14:11'),
(20, '16:50:00', '17:40:00', 'Tuesday', '2025-01-28 18:14:11'),
(21, '17:40:00', '18:30:00', 'Tuesday', '2025-01-28 18:14:11'),
(22, '18:30:00', '19:20:00', 'Tuesday', '2025-01-28 18:14:11'),
(23, '08:45:00', '09:35:00', 'Wednesday', '2025-01-28 18:14:11'),
(24, '09:35:00', '10:25:00', 'Wednesday', '2025-01-28 18:14:11'),
(25, '10:25:00', '11:15:00', 'Wednesday', '2025-01-28 18:14:11'),
(26, '11:15:00', '12:05:00', 'Wednesday', '2025-01-28 18:14:11'),
(27, '12:05:00', '12:55:00', 'Wednesday', '2025-01-28 18:14:11'),
(28, '12:55:00', '13:45:00', 'Wednesday', '2025-01-28 18:14:11'),
(29, '13:45:00', '14:35:00', 'Wednesday', '2025-01-28 18:14:11'),
(30, '16:00:00', '16:50:00', 'Wednesday', '2025-01-28 18:14:11'),
(31, '16:50:00', '17:40:00', 'Wednesday', '2025-01-28 18:14:11'),
(32, '17:40:00', '18:30:00', 'Wednesday', '2025-01-28 18:14:11'),
(33, '18:30:00', '19:20:00', 'Wednesday', '2025-01-28 18:14:11'),
(34, '08:45:00', '09:35:00', 'Thursday', '2025-01-28 18:14:11'),
(35, '09:35:00', '10:25:00', 'Thursday', '2025-01-28 18:14:11'),
(36, '10:25:00', '11:15:00', 'Thursday', '2025-01-28 18:14:11'),
(37, '11:15:00', '12:05:00', 'Thursday', '2025-01-28 18:14:11'),
(38, '12:05:00', '12:55:00', 'Thursday', '2025-01-28 18:14:11'),
(39, '12:55:00', '13:45:00', 'Thursday', '2025-01-28 18:14:11'),
(40, '13:45:00', '14:35:00', 'Thursday', '2025-01-28 18:14:11'),
(41, '16:00:00', '16:50:00', 'Thursday', '2025-01-28 18:14:11'),
(42, '16:50:00', '17:40:00', 'Thursday', '2025-01-28 18:14:11'),
(43, '17:40:00', '18:30:00', 'Thursday', '2025-01-28 18:14:11'),
(44, '18:30:00', '19:20:00', 'Thursday', '2025-01-28 18:14:11'),
(45, '08:45:00', '09:35:00', 'Friday', '2025-01-28 18:14:11'),
(46, '09:35:00', '10:25:00', 'Friday', '2025-01-28 18:14:11'),
(47, '10:25:00', '11:15:00', 'Friday', '2025-01-28 18:14:11'),
(48, '11:15:00', '12:05:00', 'Friday', '2025-01-28 18:14:11'),
(49, '12:05:00', '12:55:00', 'Friday', '2025-01-28 18:14:11'),
(50, '12:55:00', '13:45:00', 'Friday', '2025-01-28 18:14:11'),
(51, '13:45:00', '14:35:00', 'Friday', '2025-01-28 18:14:11'),
(52, '16:00:00', '16:50:00', 'Friday', '2025-01-28 18:14:11'),
(53, '16:50:00', '17:40:00', 'Friday', '2025-01-28 18:14:11'),
(54, '17:40:00', '18:30:00', 'Friday', '2025-01-28 18:14:11'),
(55, '18:30:00', '19:20:00', 'Friday', '2025-01-28 18:14:11'),
(56, '08:45:00', '09:35:00', 'Thursday', '2025-01-28 18:14:36'),
(57, '08:45:00', '09:35:00', 'Friday', '2025-01-28 18:14:36'),
(58, '09:35:00', '10:25:00', 'Thursday', '2025-01-28 18:14:36'),
(59, '09:35:00', '10:25:00', 'Friday', '2025-01-28 18:14:36'),
(60, '10:25:00', '11:15:00', 'Thursday', '2025-01-28 18:14:36'),
(61, '10:25:00', '11:15:00', 'Friday', '2025-01-28 18:14:36'),
(62, '11:15:00', '12:05:00', 'Thursday', '2025-01-28 18:14:36'),
(63, '11:15:00', '12:05:00', 'Friday', '2025-01-28 18:14:36'),
(64, '12:05:00', '12:55:00', 'Thursday', '2025-01-28 18:14:36'),
(65, '12:05:00', '12:55:00', 'Friday', '2025-01-28 18:14:36'),
(66, '12:55:00', '13:45:00', 'Thursday', '2025-01-28 18:14:36'),
(67, '12:55:00', '13:45:00', 'Friday', '2025-01-28 18:14:36'),
(68, '13:45:00', '14:35:00', 'Thursday', '2025-01-28 18:14:36'),
(69, '13:45:00', '14:35:00', 'Friday', '2025-01-28 18:14:36'),
(70, '16:00:00', '16:50:00', 'Thursday', '2025-01-28 18:14:36'),
(71, '16:50:00', '17:40:00', 'Thursday', '2025-01-28 18:14:36'),
(72, '17:40:00', '18:30:00', 'Thursday', '2025-01-28 18:14:36'),
(73, '18:30:00', '19:20:00', 'Thursday', '2025-01-28 18:14:36'),
(74, '08:45:00', '09:35:00', 'Thursday', '2025-01-28 18:15:15'),
(75, '08:45:00', '09:35:00', 'Friday', '2025-01-28 18:15:15'),
(76, '09:35:00', '10:25:00', 'Thursday', '2025-01-28 18:15:15'),
(77, '09:35:00', '10:25:00', 'Friday', '2025-01-28 18:15:15'),
(78, '10:25:00', '11:15:00', 'Thursday', '2025-01-28 18:15:15'),
(79, '10:25:00', '11:15:00', 'Friday', '2025-01-28 18:15:15'),
(80, '11:15:00', '12:05:00', 'Thursday', '2025-01-28 18:15:15'),
(81, '11:15:00', '12:05:00', 'Friday', '2025-01-28 18:15:15'),
(82, '12:05:00', '12:55:00', 'Thursday', '2025-01-28 18:15:15'),
(83, '12:05:00', '12:55:00', 'Friday', '2025-01-28 18:15:15'),
(84, '12:55:00', '13:45:00', 'Thursday', '2025-01-28 18:15:15'),
(85, '12:55:00', '13:45:00', 'Friday', '2025-01-28 18:15:15'),
(86, '13:45:00', '14:35:00', 'Thursday', '2025-01-28 18:15:15'),
(87, '13:45:00', '14:35:00', 'Friday', '2025-01-28 18:15:15'),
(88, '16:00:00', '16:50:00', 'Thursday', '2025-01-28 18:15:15'),
(89, '16:50:00', '17:40:00', 'Thursday', '2025-01-28 18:15:15'),
(90, '17:40:00', '18:30:00', 'Thursday', '2025-01-28 18:15:15'),
(91, '18:30:00', '19:20:00', 'Thursday', '2025-01-28 18:15:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_reset` tinyint(1) NOT NULL,
  `rol` enum('admin','student') NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `first_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `second_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `telephone` varchar(15) NOT NULL,
  `dni` varchar(9) NOT NULL,
  `time_stamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `password_reset`, `rol`, `name`, `first_name`, `second_name`, `telephone`, `dni`, `time_stamp`) VALUES
(1, 'admin@admin.com', 'admin', 0, 'admin', 'admin ', 'admin', '', '', 'dddd', '2025-01-28 17:55:37'),
(2, 'ana.rodriguez@example.com', '$2y$10$wlCxJUnym8D7wF0/cmMt0ePaYFMZXmgL/MObop8y1IuDJ/zhUHFxK', 1, 'admin', 'ana', 'Rodríguez  ', 'Fernández', '', '87654321B', '2025-01-28 17:55:37'),
(3, 'carlos.martin@example.com', 'carlospass', 0, 'student', 'Carlos', 'Martín', 'Fernández', '600555666', '11223344C', '2025-01-28 17:55:37'),
(4, 'maria.lopez@example.com', 'mariapass', 0, 'student', 'María', 'López d', 'Sánchez', '', '44556677D', '2025-01-28 17:55:37'),
(7, 'paco@pago.com', '$2y$10$XDM8rMmTX062vNxJNzof1esXHJthbEu4LL1fcS/dp5CQ1Ts/NU3xG', 1, 'student', 'Ana mod', 'Rodríguez mod', 'adsfdsf', '45452554', 'fadsfdsf', '2025-02-11 20:05:01'),
(8, 'bea@bea', '$2y$10$l5Y90/6KQxCpnLwXsjssReEHBA7SzwcI/EdkmNYOO3zNW6YyNElia', 1, 'student', 'Ana mod', 'Rodríguez mod', 'adsfdsf', '45452554', 'fadsfdsf', '2025-02-11 20:05:19');

-- --------------------------------------------------------

--
-- Table structure for table `users_modules`
--

CREATE TABLE `users_modules` (
  `user_id` int NOT NULL,
  `module_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users_vocational_trainings`
--

CREATE TABLE `users_vocational_trainings` (
  `user_id` int NOT NULL,
  `vocational_training_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users_vocational_trainings`
--

INSERT INTO `users_vocational_trainings` (`user_id`, `vocational_training_id`) VALUES
(2, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `vocational_trainings`
--

CREATE TABLE `vocational_trainings` (
  `id` int NOT NULL,
  `course_code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `acronym` varchar(5) NOT NULL,
  `course_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `modality` enum('ordinary','dual','modular','') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `type` enum('medium','higher') NOT NULL,
  `time_stamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `vocational_trainings`
--

INSERT INTO `vocational_trainings` (`id`, `course_code`, `acronym`, `course_name`, `modality`, `type`, `time_stamp`) VALUES
(1, 'ASIR_ORDd', 'mod', 'Administración de Sistemas Informáticos en Red', 'ordinary', 'higher', '2025-01-28 18:09:03'),
(2, 'DAW_MOD', '', 'Desarrollo de Aplicaciones Web', 'modular', 'higher', '2025-01-28 18:09:03'),
(3, 'DAM_DUAL', '', 'Desarrollo de Aplicaciones Multiplataforma', 'dual', 'higher', '2025-01-28 18:09:03'),
(4, 'SMR_ORD', '', 'Sistemas Microinformáticos y Redes', 'ordinary', 'medium', '2025-01-28 18:09:03'),
(5, 'asdf', 'sadf', 'asdfadf', 'ordinary', 'medium', '2025-02-18 16:21:45'),
(6, 'nuevo', 'nuevo', 'nuevo', 'ordinary', 'medium', '2025-02-18 17:50:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_professor_modules` (`professor_id`),
  ADD KEY `fk_vocational_training_modules` (`vocational_training_id`);

--
-- Indexes for table `modules_sessions`
--
ALTER TABLE `modules_sessions`
  ADD PRIMARY KEY (`module_id`,`session_id`),
  ADD KEY `fk_time_tables` (`session_id`);

--
-- Indexes for table `professors`
--
ALTER TABLE `professors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users_modules`
--
ALTER TABLE `users_modules`
  ADD PRIMARY KEY (`user_id`,`module_id`),
  ADD KEY `fk_module_id` (`module_id`);

--
-- Indexes for table `users_vocational_trainings`
--
ALTER TABLE `users_vocational_trainings`
  ADD PRIMARY KEY (`user_id`,`vocational_training_id`),
  ADD KEY `fk_vocational_training` (`vocational_training_id`);

--
-- Indexes for table `vocational_trainings`
--
ALTER TABLE `vocational_trainings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `professors`
--
ALTER TABLE `professors`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `vocational_trainings`
--
ALTER TABLE `vocational_trainings`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `fk_professor_modules` FOREIGN KEY (`professor_id`) REFERENCES `professors` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_vocational_training_modules` FOREIGN KEY (`vocational_training_id`) REFERENCES `vocational_trainings` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `modules_sessions`
--
ALTER TABLE `modules_sessions`
  ADD CONSTRAINT `fk_modules` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_time_tables` FOREIGN KEY (`session_id`) REFERENCES `sessions` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `users_modules`
--
ALTER TABLE `users_modules`
  ADD CONSTRAINT `fk_module_id` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `users_vocational_trainings`
--
ALTER TABLE `users_vocational_trainings`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `fk_vocational_training` FOREIGN KEY (`vocational_training_id`) REFERENCES `vocational_trainings` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
