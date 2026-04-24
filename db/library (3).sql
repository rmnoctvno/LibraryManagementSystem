-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 21, 2024 at 01:54 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookcart`
--

CREATE TABLE `bookcart` (
  `id` int(11) NOT NULL,
  `rfidnum` varchar(255) NOT NULL,
  `book` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `duration` int(11) NOT NULL,
  `duedate` date NOT NULL,
  `borrowed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Borrowed',
  `bookserial` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `genre` varchar(45) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `published_date` date NOT NULL,
  `category` varchar(45) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `title`, `genre`, `author`, `isbn`, `published_date`, `category`, `quantity`) VALUES
(14, 'Teaching in a Digital Age', 'Textbook', 'By A.W. (Tony) Bates', '9780995269255', '2022-08-18', 'Trending Books', 3),
(15, 'Mathematics in the Modern World: Outcomes-Based Module', 'Mathematics', 'Richard T. Earnhart, Edgar M. Adina', '9789719818106', '2018-03-05', 'Mathematics', 5),
(16, 'A Short History of Nearly Everything', 'Science', 'Bill Bryson', '0767908171', '2003-12-15', 'Science', 4),
(17, 'A Brief History of Time', 'Science', 'Stephen Hawking', '9783644008618', '1988-05-11', 'Science', 3),
(18, 'The Selfish Gene', 'Science', 'Richard Dawkins', '9780191537554', '1976-11-27', 'Science', 5),
(19, 'A City On Mars', 'Science', 'Kelly and Zach Weinersmith', '9781984881724 ', '2023-01-03', 'Science', 4),
(20, 'Why We Die', 'Science', 'Venki Ramakrishnan', '9781529369267', '2024-03-19', 'Science', 4),
(21, 'The Sacred Well Murders', 'Fiction', 'Susan Rowland', '9781685030056', '2022-02-01', 'Fiction', 3),
(22, 'A Little Life', 'Novel', 'Hanya Yanagihara', '9783844915853', '2015-08-13', 'Trending Books', 3),
(23, 'The Seven Husbands of Evelyn Hugo', 'Romance', 'Taylor Jenkins Reid', '9782811235949', '2017-06-13', 'Trending Books', 3),
(24, 'Happy Place', 'Novel', 'Emily Henry', '9780593638446', '2023-04-25', 'Fiction', 3),
(25, 'Lies and Weddings: A Novel', 'Novel', 'Kevin Kwan', '9780385546294', '2024-05-21', 'Trending Books', 3),
(26, 'The Code Book', 'Educational', 'Simon Singh', '9780007635740', '1999-03-01', 'Trending Books', 3),
(27, ' Linear algebra done right', 'Educational', 'Sheldon Axler', '9780387225951', '1995-03-24', 'Educational', 5),
(28, 'The Pragmatic Programmer', 'Educational', 'Andy Hunt and Dave Thomas', '9780132119177', '1999-10-10', 'Educational', 3),
(29, 'The Pragmatic Programmer', 'Educational', 'Andy Hunt and Dave Thomas', '9780132119177', '1999-10-10', 'Educational', 3),
(30, 'The Midnight Library', 'Fiction', 'Matt Haig', '9783060366460', '2023-08-13', 'Fiction', 4),
(31, 'To Kill a Mockingbird', 'Novel', 'Harper Lee', '9782253115847', '1960-07-11', 'Fiction', 3),
(32, 'The Secret History', 'Novel', 'Donna Tartt', '9780241982884', '1992-09-26', 'Fiction', 4);

-- --------------------------------------------------------

--
-- Table structure for table `borrowedbooks`
--

CREATE TABLE `borrowedbooks` (
  `id` int(11) NOT NULL,
  `rfidnum` varchar(255) NOT NULL,
  `book` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `duration` int(11) NOT NULL,
  `duedate` date NOT NULL,
  `borrowed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Borrowed',
  `bookserial` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `borrowhistory`
--

CREATE TABLE `borrowhistory` (
  `id` int(11) NOT NULL,
  `rfidnum` varchar(255) NOT NULL,
  `book` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `duration` int(11) NOT NULL,
  `duedate` date NOT NULL,
  `borrowed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'Borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pendingreturn`
--

CREATE TABLE `pendingreturn` (
  `id` int(11) NOT NULL,
  `rfidnum` varchar(50) NOT NULL,
  `username` varchar(100) NOT NULL,
  `book` varchar(255) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `author` varchar(150) NOT NULL,
  `isbn` varchar(45) NOT NULL,
  `duration` int(11) NOT NULL,
  `duedate` date NOT NULL,
  `borrowed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved') NOT NULL DEFAULT 'Pending',
  `bookserial` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `idusers` int(11) NOT NULL,
  `rfidnum` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `yearAndSection` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `program` varchar(45) NOT NULL,
  `userType` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `penalty` tinyint(1) DEFAULT 0,
  `penaltynow` date DEFAULT NULL,
  `penaltydue` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`idusers`, `rfidnum`, `username`, `yearAndSection`, `email`, `program`, `userType`, `password`, `penalty`, `penaltynow`, `penaltydue`) VALUES
(0, 1103878740, 'ADMIN', '', '', '', 'ADMIN', 'admin', 0, NULL, NULL),
(2, 424134370, 'paul', 'BSCS 3A', 'bryan@gmail.com', 'College Of Liberal Arts and Sciences', 'STUDENT', 'user', 0, NULL, NULL),
(3, 2054455812, 'bryan', 'BSIT 4A', 'paulsimon@gmail.com', 'College Of Liberal Arts and Sciences', 'STUDENT', 'user', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookcart`
--
ALTER TABLE `bookcart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowhistory`
--
ALTER TABLE `borrowhistory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pendingreturn`
--
ALTER TABLE `pendingreturn`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`idusers`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookcart`
--
ALTER TABLE `bookcart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `borrowedbooks`
--
ALTER TABLE `borrowedbooks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `borrowhistory`
--
ALTER TABLE `borrowhistory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pendingreturn`
--
ALTER TABLE `pendingreturn`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `idusers` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
