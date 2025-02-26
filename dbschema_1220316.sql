-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 16, 2025 at 02:54 PM
-- Server version: 8.0.40
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `web1220316_WebTAPProject`
--
CREATE DATABASE IF NOT EXISTS `web1220316_WebTAPProject`;
USE `web1220316_WebTAPProject`;

-- --------------------------------------------------------

--
-- Table structure for table `ContactMessages`
--

CREATE TABLE `ContactMessages` (
  `messageId` int NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `subject` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `submittedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int NOT NULL,
  `prjId` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `prjTitle` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `prjDesc` text COLLATE utf8mb4_general_ci NOT NULL,
  `custName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `totalBudget` decimal(10,2) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `doc1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `doc2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `doc3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `teamLeaderID` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `docTitle1` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `docTitle2` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `docTitle3` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `prjId`, `prjTitle`, `prjDesc`, `custName`, `totalBudget`, `startDate`, `endDate`, `doc1`, `doc2`, `doc3`, `teamLeaderID`, `createdAt`, `docTitle1`, `docTitle2`, `docTitle3`) VALUES
(1, 'PROJ-12345', 'Test Project', 'This is a trial', 'Someone', 100200.00, '2023-06-16', '2024-06-16', 'uploads/678442e20efa3-diagram-export-12-01-2025-17_34_51.png', 'uploads/678442e20f84b-diagram-export-12-01-2025-18_09_41.png', 'uploads/678442e20f990-diagram-export-12-01-2025-18_10_49.png', NULL, '2025-01-12 22:32:02', NULL, NULL, NULL),
(2, 'PROJ-23456', 'TestTwo Project', 'This is the second trial', 'SomeOneTwo', 200100.00, '2022-02-13', '2023-04-14', NULL, NULL, NULL, NULL, '2025-01-13 10:34:40', NULL, NULL, NULL),
(3, 'PROJ-00123', 'Website Redesign', 'Redesign the company website for better user experience.', 'John Doe', 5000.00, '2023-01-01', '2023-06-01', 'doc1.pdf', 'doc2.docx', NULL, 'U002', '2023-01-01 10:00:00', NULL, NULL, NULL),
(4, 'PROJ-00456', 'Mobile App Development', 'Develop a mobile app for client services.', 'Jane Smith', 10000.00, '2023-03-15', '2023-09-15', NULL, 'app.png', NULL, '9045445416', '2023-03-15 12:30:00', NULL, NULL, NULL),
(5, 'PROJ-00789', 'Database Optimization', 'Optimize the company database for better performance.', 'ABC Corp', 2500.00, '2023-05-01', '2023-07-01', NULL, NULL, 'report.jpg', '1015', '2023-05-01 06:15:00', NULL, NULL, NULL),
(6, 'PROJ-01012', 'Marketing Campaign', 'Design and execute a marketing campaign.', 'XYZ Ltd', 7500.50, '2023-06-10', '2023-12-10', 'brief.pdf', 'analysis.docx', 'campaign.png', 'U002', '2023-06-10 07:45:00', NULL, NULL, NULL),
(7, 'PROJ-01543', 'Cloud Migration', 'Migrate the system to a cloud environment.', 'Tech Solutions', 15000.00, '2023-07-01', '2023-12-31', NULL, NULL, 'migration.docx', NULL, '2023-07-01 10:00:00', NULL, NULL, NULL),
(8, 'PROJ-10001', 'ShopEase Platform', 'E-commerce platform development for seamless shopping.', 'Acme Corp', 50000.00, '2025-01-01', '2025-06-30', 'uploads/alpha_requirements.pdf', NULL, NULL, '1001', '2025-01-16 11:09:01', 'Requirements Document', NULL, NULL),
(9, 'PROJ-10002', 'Beta CRM Upgrade', 'Comprehensive CRM system upgrade for Beta Ltd.', 'Beta Ltd', 30000.00, '2025-02-01', '2025-07-15', NULL, 'uploads/beta_specifications.pdf', NULL, '1002', '2025-01-16 11:09:01', NULL, 'Specifications Document', NULL),
(10, 'PROJ-10003', 'Gamma Cloud Migration', 'Migrating infrastructure to cloud for Gamma Inc.', 'Gamma Inc', 75000.00, '2025-03-01', '2025-12-31', NULL, NULL, 'uploads/gamma_plan.pdf', '1003', '2025-01-16 11:09:01', NULL, NULL, 'Migration Plan'),
(11, 'PROJ-10004', 'Delta Mobile App', 'Design and development of a mobile application for Delta Tech.', 'Delta Tech', 60000.00, '2025-04-01', '2025-09-30', 'uploads/delta_mockups.pdf', NULL, NULL, '1004', '2025-01-16 11:09:01', 'Mockups Document', NULL, NULL),
(12, 'PROJ-10005', 'Epsilon Data Analytics', 'Development of data analytics tools for Epsilon Analytics.', 'Epsilon Analytics', 45000.00, '2025-05-01', '2025-10-31', NULL, NULL, NULL, '1005', '2025-01-16 11:09:01', NULL, NULL, NULL),
(13, 'PROJ-10006', 'Zeta Security Audit', 'Comprehensive security audit for Zeta Corp.', 'Zeta Corp', 55000.00, '2025-01-15', '2025-05-30', 'uploads/zeta_audit.pdf', 'uploads/zeta_findings.pdf', NULL, '1006', '2025-01-16 11:09:01', 'Audit Document', 'Findings Document', NULL),
(14, 'PROJ-10007', 'Theta Chatbot AI', 'Development of an AI-powered chatbot for Theta Solutions.', 'Theta Solutions', 80000.00, '2025-06-01', '2025-12-31', NULL, 'uploads/theta_specifications.pdf', NULL, '1007', '2025-01-16 11:09:01', NULL, 'Specifications Document', NULL),
(15, 'PROJ-10008', 'Iota IoT Solution', 'IoT solution design and implementation for Iota Ltd.', 'Iota Ltd', 40000.00, '2025-03-10', '2025-08-31', NULL, NULL, NULL, '1008', '2025-01-16 11:09:01', NULL, NULL, NULL),
(16, 'PROJ-10009', 'Kappa Blockchain Project', 'Blockchain implementation for secure transactions at Kappa Blockchain.', 'Kappa Blockchain', 95000.00, '2025-04-15', '2025-11-30', NULL, NULL, NULL, '1009', '2025-01-16 11:09:01', NULL, NULL, NULL),
(17, 'PROJ-10010', 'Lambda Healthcare Upgrade', 'Upgrade of the healthcare system infrastructure for Lambda Health.', 'Lambda Health', 120000.00, '2025-02-20', '2025-12-15', 'uploads/lambda_specs.pdf', NULL, NULL, '1010', '2025-01-16 11:09:01', 'Specifications Document', NULL, NULL),
(18, 'PROJ-62738', 'Cloud Migration', 'Migrating infrastructure to a cloud environment.', 'Gamma INC', 75000.00, '2025-03-01', '2025-12-31', 'uploads/Sequence_Diagram.png', NULL, NULL, '9045445416', '2025-01-16 11:39:51', 'Sequence Diagram', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `taskId` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `taskName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `taskDescription` text COLLATE utf8mb4_general_ci NOT NULL,
  `projectId` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `effort` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `priority` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `progress` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`taskId`, `taskName`, `taskDescription`, `projectId`, `startDate`, `endDate`, `effort`, `status`, `priority`, `createdAt`, `progress`) VALUES
('100001', 'Design UI', 'Create UI for Project Alpha.', 'PROJ-10001', '2025-01-10', '2025-01-20', 5.00, 'Completed', 'High', '2025-01-16 11:09:30', 100),
('100002', 'Setup Backend', 'Develop backend for Alpha.', 'PROJ-10001', '2025-01-25', '2025-02-10', 7.50, 'In Progress', 'Medium', '2025-01-16 11:09:30', 50),
('100003', 'CRM Testing', 'Test CRM features for Beta.', 'PROJ-10002', '2025-02-15', '2025-03-01', 3.00, 'Pending', 'Low', '2025-01-16 11:09:30', 0),
('100004', 'Server Setup', 'Set up cloud servers for Gamma.', 'PROJ-10003', '2025-03-20', '2025-04-10', 6.00, 'Completed', 'High', '2025-01-16 11:09:30', 100),
('100005', 'API Integration', 'Develop APIs for Delta app.', 'PROJ-10004', '2025-04-15', '2025-05-05', 4.00, 'In Progress', 'High', '2025-01-16 11:09:30', 25),
('100006', 'Analytics Design', 'Design analytics dashboards.', 'PROJ-10005', '2025-05-10', '2025-06-10', 3.50, 'Completed', 'Medium', '2025-01-16 11:09:30', 100),
('100007', 'Security Analysis', 'Analyze Zeta’s security gaps.', 'PROJ-10006', '2025-01-20', '2025-02-15', 4.50, 'Completed', 'High', '2025-01-16 11:09:30', 100),
('100008', 'Chatbot AI Training', 'Train AI models for Theta.', 'PROJ-10007', '2025-06-20', '2025-08-01', 8.00, 'Pending', 'High', '2025-01-16 11:09:30', 0),
('100009', 'IoT Prototyping', 'Develop IoT prototype.', 'PROJ-10008', '2025-03-15', '2025-04-15', 5.50, 'In Progress', 'Medium', '2025-01-16 11:09:30', 60),
('100010', 'Blockchain Deployment', 'Deploy blockchain solution.', 'PROJ-10009', '2025-04-25', '2025-06-10', 9.00, 'Completed', 'High', '2025-01-16 11:09:30', 100),
('100011', 'Healthcare DB Upgrade', 'Upgrade database for Lambda.', 'PROJ-10010', '2025-02-25', '2025-03-20', 6.00, 'In Progress', 'Medium', '2025-01-16 11:09:30', 50),
('1020', 'Dashboard Features', 'Adding new features to the dashboard page, such as charts and statistical info.', 'PROJ-00123', '2023-01-15', '2023-03-15', 3.00, 'Pending', 'Medium', '2025-01-14 10:26:25', 0),
('17235', 'Rounds in the city', 'For this task, rounds should be made in the city in order to advertise our product and earn more popularity.', 'PROJ-01012', '2023-06-16', '2023-08-16', 4.00, 'In Progress', 'High', '2025-01-16 10:46:52', 1),
('2030', 'Usable GUI', 'Designing a usable GUI based on the customer requirements.', 'PROJ-01012', '2023-07-10', '2023-08-10', 2.00, 'In Progress', 'High', '2025-01-15 14:12:29', 20),
('318893', 'Posters', 'For this task, we want to design huge posters to hang them in the centers and in public places.', 'PROJ-01012', '2023-06-15', '2023-11-20', 5.00, 'Completed', 'Low', '2025-01-15 23:34:57', 100),
('773456', 'Cloud Infrastructure Setup', 'Set up the required cloud infrastructure, including servers and storage.', 'PROJ-62738', '2025-03-01', '2025-05-15', 120.00, 'In Progress', 'High', '2025-01-16 11:43:30', 20);

-- --------------------------------------------------------

--
-- Table structure for table `teamAssignments`
--

CREATE TABLE `teamAssignments` (
  `assignmentId` int NOT NULL,
  `taskId` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `userId` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contribution` decimal(5,2) NOT NULL,
  `assignmentStatus` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pending',
  `assignedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teamAssignments`
--

INSERT INTO `teamAssignments` (`assignmentId`, `taskId`, `userId`, `role`, `contribution`, `assignmentStatus`, `assignedAt`) VALUES
(1, '1020', 'U003', 'Developer', 30.00, 'Accepted', '2025-01-14 12:16:09'),
(2, '1020', 'U004', 'Tester', 30.00, 'Pending', '2025-01-14 13:00:30'),
(6, '1020', 'U014', 'Designer', 30.00, 'Pending', '2025-01-14 14:03:58'),
(8, '1020', 'U009', 'Analyst', 10.00, 'Pending', '2025-01-14 14:09:28'),
(10, '2030', 'U008', 'Developer', 30.00, 'Pending', '2025-01-15 18:10:51'),
(11, '318893', 'U010', 'Designer', 25.00, 'Pending', '2025-01-15 23:40:06'),
(12, '318893', 'U003', 'Support', 30.00, 'Accepted', '2025-01-15 23:43:52'),
(28, '773456', 'U003', 'Developer', 30.00, 'Accepted', '2025-01-16 11:45:11'),
(30, '17235', 'U003', 'Support', 25.00, 'Pending', '2025-01-16 11:57:13');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `userId` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `fullName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `flatNo` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `street` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `city` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `country` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `dob` date NOT NULL,
  `idNumber` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `telephone` varchar(15) COLLATE utf8mb4_general_ci NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `qualification` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `skills` text COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(13) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `registrationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `userId`, `fullName`, `flatNo`, `street`, `city`, `country`, `dob`, `idNumber`, `email`, `telephone`, `role`, `qualification`, `skills`, `username`, `password`, `registrationDate`) VALUES
(1, '1234567890', 'John Doe', '12B', 'Main Street', 'London', 'United Kingdom', '1995-05-15', 'ID1234567', 'john@example.com', '+972569776361', 'Manager', 'Bachelor of Science', 'Project Management, Coding', 'johndoe95', '$2y$10$5vzZy7y', '2025-01-08 22:23:01'),
(2, 'U001', 'Alice Manager', '10', 'Main St', 'New York', 'USA', '1980-01-01', '123456789', 'alice.manager@example.com', '1234567890', 'Manager', 'MBA', 'Leadership, Management', 'alice', 'password123', '2025-01-12 10:14:45'),
(3, 'U002', 'Bob Leader', '20', 'Second St', 'Chicago', 'USA', '1985-05-05', '987654321', 'bob.leader@example.com', '0987654321', 'Project Leader', 'BSc Computer Science', 'Project Management, Agile', 'bob', 'password123', '2025-01-12 10:14:45'),
(4, 'U003', 'Charlie Member', '30', 'Third Ave', 'San Francisco', 'USA', '1990-12-12', '567890123', 'charlie.member@example.com', '5678901234', 'Team Member', 'BSc IT', 'Coding, Testing', 'charlie', 'password123', '2025-01-12 10:14:45'),
(5, 'U004', 'Dana Member', '40', 'Fourth Blvd', 'Los Angeles', 'USA', '1992-08-15', '789012345', 'dana.member@example.com', '6789012345', 'Team Member', 'BSc Software Engineering', 'Development, QA', 'dana', 'password123', '2025-01-12 10:14:45'),
(6, 'U005', 'Eve Member', '50', 'Fifth Ln', 'Houston', 'USA', '1995-03-20', '345678901', 'eve.member@example.com', '3456789012', 'Team Member', 'BSc Computer Science', 'Design, Support', 'eve', 'password123', '2025-01-12 10:14:45'),
(7, 'U006', 'Frank Manager', '101', 'Main Blvd', 'New York', 'USA', '1985-03-10', '123450001', 'frank.manager@example.com', '+12345678901', 'Manager', 'MBA', 'Leadership, Strategic Planning', 'frank', 'frank123', '2025-01-14 09:30:47'),
(8, 'U007', 'Grace Leader', '202', 'Elm Street', 'Chicago', 'USA', '1990-06-25', '987650002', 'grace.leader@example.com', '+12345678902', 'Project Leader', 'BSc Software Engineering', 'Agile Development, Team Management', 'grace', 'grace123', '2025-01-14 09:30:47'),
(9, 'U008', 'Hannah Member', '303', 'Oak Lane', 'San Francisco', 'USA', '1995-11-05', '567890003', 'hannah.member@example.com', '+12345678903', 'Team Member', 'BSc IT', 'Testing, Development', 'hannah', 'hannah123', '2025-01-14 09:30:47'),
(10, 'U009', 'Isaac Developer', '404', 'Pine Avenue', 'Los Angeles', 'USA', '1988-04-15', '345670004', 'isaac.dev@example.com', '+12345678904', 'Team Member', 'BSc Computer Science', 'Development, QA', 'isaac', 'isaac123', '2025-01-14 09:30:47'),
(11, 'U010', 'Jack Tester', '505', 'Cedar Blvd', 'Houston', 'USA', '1992-09-30', '102030405', 'jack.tester@example.com', '+12345678905', 'Team Member', 'BSc Software Testing', 'Testing, Documentation', 'jack', 'jack123', '2025-01-14 09:30:47'),
(12, 'U011', 'Kathy QA', '606', 'Birch Road', 'Seattle', 'USA', '1985-12-20', '567123450', 'kathy.qa@example.com', '+12345678906', 'Team Member', 'BSc QA Engineering', 'QA, Agile Practices', 'kathy', 'kathy123', '2025-01-14 09:30:47'),
(13, 'U012', 'Liam Support', '707', 'Ash Lane', 'Denver', 'USA', '1993-07-15', '123450003', 'liam.support@example.com', '+12345678907', 'Team Member', 'BSc IT Support', 'Support, Troubleshooting', 'liam', 'liam123', '2025-01-14 09:30:47'),
(14, 'U013', 'Mia Design', '808', 'Maple Blvd', 'Boston', 'USA', '1991-02-20', '234567890', 'mia.design@example.com', '+12345678908', 'Team Member', 'BSc Graphic Design', 'UI/UX, Graphics', 'mia', 'mia123', '2025-01-14 09:30:47'),
(15, 'U014', 'Noah Analyst', '909', 'Palm Avenue', 'Austin', 'USA', '1994-10-10', '304050607', 'noah.analyst@example.com', '+12345678909', 'Team Member', 'BSc Data Science', 'Data Analysis, Visualization', 'noah', 'noah123', '2025-01-14 09:30:47'),
(16, 'U015', 'Olivia Planner', '1010', 'Cherry Lane', 'Dallas', 'USA', '1996-06-18', '456789012', 'olivia.planner@example.com', '+12345678910', 'Team Member', 'BSc Project Management', 'Planning, Coordination', 'olivia', 'olivia123', '2025-01-14 09:30:47'),
(17, 'U016', 'Paul Coordinator', '1111', 'Willow Drive', 'Phoenix', 'USA', '1987-08-25', '506070809', 'paul.coord@example.com', '+12345678911', 'Team Member', 'BSc Coordination', 'Team Coordination, Communication', 'paul', 'paul123', '2025-01-14 09:30:47'),
(18, 'U017', 'Quinn Strategist', '1212', 'Magnolia Ave', 'Las Vegas', 'USA', '1989-03-30', '678901234', 'quinn.strategy@example.com', '+12345678912', 'Manager', 'MBA', 'Strategic Planning, Leadership', 'quinn', 'quinn123', '2025-01-14 09:30:47'),
(19, 'U018', 'Ruby Consultant', '1313', 'Cypress Road', 'Orlando', 'USA', '1992-11-01', '708090109', 'ruby.consult@example.com', '+12345678913', 'Team Member', 'BSc Consultancy', 'Consultation, Advisory', 'ruby', 'ruby123', '2025-01-14 09:30:47'),
(20, 'U019', 'Sophia Architect', '1414', 'Spruce Blvd', 'Atlanta', 'USA', '1993-12-14', '890123456', 'sophia.arch@example.com', '+12345678914', 'Project Leader', 'BSc Architecture', 'System Design, Planning', 'sophia', 'sophia123', '2025-01-14 09:30:47'),
(21, 'U020', 'Tom Developer', '1515', 'Fir Lane', 'Detroit', 'USA', '1995-05-06', '901234567', 'tom.dev@example.com', '+12345678915', 'Team Member', 'BSc Computer Science', 'Development, Integration', 'tom', 'tom123', '2025-01-14 09:30:47'),
(22, '8004528899', 'Sarah Adams', '12', 'Pine Ave', 'Boston', 'USA', '1992-09-05', '201020304', 'sarah.adams@example.com', '12765678901', 'Manager', 'MBA', 'Leadership, Planning', 'sarah1', 'sarah123', '2025-01-15 15:18:09'),
(23, '9045445416', 'Jessica Davis', '19', 'Birch Blvd', 'Austin', 'USA', '1994-07-27', '627006528', 'jessica.davis@example.com', '23456789054', 'Project Leader', 'BSc Computer Engineering', 'Agile Development, Testing', 'jessica27', 'jessica123', '2025-01-15 15:30:00'),
(24, '1001', 'Sofia Smith', '12A', 'Maple Street', 'Springfield', 'USA', '1990-05-20', 'ID1001', 'sofia.smith@example.com', '1234567890', 'Team Leader', 'B.Sc. Computer Science', 'Leadership, Coding', 'sofia123', 'password1', '2025-01-16 11:07:55'),
(25, '1002', 'Brian Davis', '45B', 'Oak Avenue', 'Springfield', 'USA', '1988-06-15', 'ID1002', 'brian.d@example.com', '2234567891', 'Project Leader', 'M.Sc. Management', 'Planning, Organizing', 'briand', 'password2', '2025-01-16 11:07:55'),
(26, '1003', 'Catherine Green', '56C', 'Pine Lane', 'Metropolis', 'USA', '1995-02-12', 'ID1003', 'catherine.g@example.com', '3234567892', 'Team Leader', 'M.Sc. Software Engineering', 'Testing, Development', 'cathyg', 'password3', '2025-01-16 11:07:55'),
(27, '1004', 'Daniel Carter', '78D', 'Cedar Road', 'Metropolis', 'USA', '1992-03-18', 'ID1004', 'daniel.c@example.com', '4234567893', 'Developer', 'B.Tech IT', 'Frontend, Backend', 'danielc', 'password4', '2025-01-16 11:07:55'),
(28, '1005', 'Eliza Brown', '90E', 'Birch Drive', 'Gotham', 'USA', '1993-10-10', 'ID1005', 'eliza.b@example.com', '5234567894', 'Project Leader', 'MBA', 'Communication, Leadership', 'elizab', 'password5', '2025-01-16 11:07:55'),
(29, '1006', 'Frank White', '33A', 'Sunset Blvd', 'Gotham', 'USA', '1990-01-01', 'ID1006', 'frank.w@example.com', '6234567895', 'Developer', 'B.Sc. Information Systems', 'Database, Security', 'frankw', 'password6', '2025-01-16 11:07:55'),
(30, '1007', 'Grace Black', '21B', 'Ocean Drive', 'Metropolis', 'USA', '1991-07-07', 'ID1007', 'grace.b@example.com', '7234567896', 'Tester', 'B.Sc. Computer Science', 'Automation, Testing', 'graceb', 'password7', '2025-01-16 11:07:55'),
(31, '1008', 'Henry Green', '47C', 'Main Street', 'Springfield', 'USA', '1985-09-09', 'ID1008', 'henry.g@example.com', '8234567897', 'Team Leader', 'M.Sc. Software Engineering', 'Agile, Scrum', 'henryg', 'password8', '2025-01-16 11:07:55'),
(32, '1009', 'Isla Miller', '29A', 'Forest Lane', 'Gotham', 'USA', '1989-03-05', 'ID1009', 'isla.m@example.com', '9234567898', 'Developer', 'B.Tech IT', 'API, Backend', 'islam', 'password9', '2025-01-16 11:07:55'),
(33, '1010', 'Khader Wilson', '10D', 'Hilltop View', 'Springfield', 'USA', '1994-12-20', 'ID1010', 'khader.w@example.com', '0234567899', 'Tester', 'B.Sc. Computer Science', 'Performance, Functional Testing', 'khaderw', 'password10', '2025-01-16 11:07:55'),
(34, '1011', 'Karen Adams', '13A', 'Riverside Blvd', 'Gotham', 'USA', '1987-04-10', 'ID1011', 'karen.a@example.com', '1123456789', 'Team Leader', 'M.Sc. Project Management', 'Teamwork, Planning', 'karen123', 'password11', '2025-01-16 11:07:55'),
(35, '1012', 'Leo Parker', '88C', 'Elm Street', 'Springfield', 'USA', '1996-06-18', 'ID1012', 'leo.p@example.com', '3323456780', 'Developer', 'B.Tech IT', 'Backend, Security', 'leop', 'password12', '2025-01-16 11:07:55'),
(36, '1013', 'Mia Collins', '71D', 'Holly Road', 'Metropolis', 'USA', '1989-08-28', 'ID1013', 'mia.c@example.com', '4434567891', 'Tester', 'B.Sc. Computer Science', 'QA, Automation', 'miac', 'password13', '2025-01-16 11:07:55'),
(37, '1014', 'Nathan Reed', '52B', 'Broadway', 'Metropolis', 'USA', '1991-01-19', 'ID1014', 'nathan.r@example.com', '5534567892', 'Project Leader', 'MBA', 'Strategy, Leadership', 'nathanr', 'password14', '2025-01-16 11:07:55'),
(38, '1015', 'Perla Scott', '67A', 'Haven Street', 'Gotham', 'USA', '1990-12-30', 'ID1015', 'perla.s@example.com', '6634567893', 'Team Leader', 'M.Sc. Software Engineering', 'Agile, DevOps', 'perlas', 'password15', '2025-01-16 11:07:55'),
(39, '9467801107', 'Jesline', '22', 'Some Street', 'Parisss', 'France', '2000-11-22', '816253627', 'jesline@example.com', '5263772839', 'Manager', 'Masters in Computer Science ', 'Project Management, Coding', 'jesline22', 'jesline1234', '2025-01-16 11:32:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ContactMessages`
--
ALTER TABLE `ContactMessages`
  ADD PRIMARY KEY (`messageId`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `prjId` (`prjId`),
  ADD KEY `fk_teamLeaderID` (`teamLeaderID`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`taskId`),
  ADD KEY `projectId` (`projectId`);

--
-- Indexes for table `teamAssignments`
--
ALTER TABLE `teamAssignments`
  ADD PRIMARY KEY (`assignmentId`),
  ADD KEY `taskId` (`taskId`),
  ADD KEY `userId` (`userId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `userId` (`userId`),
  ADD UNIQUE KEY `idNumber` (`idNumber`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ContactMessages`
--
ALTER TABLE `ContactMessages`
  MODIFY `messageId` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `teamAssignments`
--
ALTER TABLE `teamAssignments`
  MODIFY `assignmentId` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `fk_teamLeaderID` FOREIGN KEY (`teamLeaderID`) REFERENCES `users` (`userId`);

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`projectId`) REFERENCES `projects` (`prjId`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `teamAssignments`
--
ALTER TABLE `teamAssignments`
  ADD CONSTRAINT `teamassignments_ibfk_1` FOREIGN KEY (`taskId`) REFERENCES `tasks` (`taskId`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teamassignments_ibfk_2` FOREIGN KEY (`userId`) REFERENCES `users` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
