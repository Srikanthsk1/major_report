-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 22, 2025 at 04:01 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_barcode_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `catid` int(11) NOT NULL,
  `category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`catid`, `category`) VALUES
(1, 'Soap'),
(2, 'Mobile'),
(3, 'Watches'),
(4, 'Health Care'),
(9, 'Grocery'),
(10, 'Fashion'),
(11, 'Electronics'),
(12, 'Baby & Kids'),
(13, 'Beverages'),
(14, 'Cosmetics'),
(15, 'Hardware'),
(16, 'Laptop'),
(17, 'Veg'),
(18, 'Non Veg'),
(19, 'Books'),
(20, 'Vegetables'),
(21, 'Spices'),
(22, 'Body Lotions'),
(23, 'Medicines');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_customer`
--

CREATE TABLE `tbl_customer` (
  `customer_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `dob` date NOT NULL,
  `age` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `order_date` date NOT NULL,
  `city` varchar(100) NOT NULL,
  `zipcode` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_customer`
--

INSERT INTO `tbl_customer` (`customer_id`, `name`, `gender`, `dob`, `age`, `phone`, `order_date`, `city`, `zipcode`) VALUES
(1, 'John Doe', 'Male', '1980-01-01', 45, '1234567890', '2025-01-22', 'New York', '10001'),
(2, 'Jane Smith', 'Female', '1985-02-02', 40, '0987654321', '2025-02-22', 'Los Angeles', '90001'),
(3, 'Alice Johnson', 'Female', '1990-03-03', 35, '1231231234', '2025-03-22', 'Chicago', '60601'),
(4, 'Bob Brown', 'Male', '1995-04-04', 30, '3213214321', '2025-04-22', 'Houston', '77001'),
(5, 'Charlie Davis', 'Male', '2000-05-05', 25, '4564564567', '2025-05-22', 'Phoenix', '85001'),
(6, 'Diana Evans', 'Female', '1975-06-06', 50, '6546546543', '2025-06-22', 'Philadelphia', '19101'),
(7, 'Eve Foster', 'Female', '1982-07-07', 43, '7897897890', '2025-07-22', 'San Antonio', '78201'),
(8, 'Frank Green', 'Male', '1988-08-08', 37, '9879879876', '2025-08-22', 'San Diego', '92101'),
(9, 'Grace Harris', 'Female', '1993-09-09', 32, '1112223334', '2025-09-22', 'Dallas', '75201'),
(10, 'Henry Jackson', 'Male', '1998-10-10', 27, '4445556667', '2025-10-22', 'San Jose', '95101'),
(11, 'Ivy King', 'Female', '1970-11-11', 55, '7778889990', '2025-11-22', 'Austin', '73301'),
(12, 'Jack Lee', 'Male', '1983-12-12', 42, '0001112223', '2025-12-22', 'Jacksonville', '32099'),
(13, 'Karen Miller', 'Female', '1977-01-13', 48, '3334445556', '2025-01-22', 'Fort Worth', '76101'),
(14, 'Larry Nelson', 'Male', '1981-02-14', 44, '6667778889', '2025-02-22', 'Columbus', '43085'),
(15, 'Mona Owens', 'Female', '1992-03-15', 33, '9990001112', '2025-03-22', 'Charlotte', '28201'),
(16, 'Nina Parker', 'Female', '1984-04-16', 41, '2223334445', '2025-04-22', 'San Francisco', '94101'),
(17, 'Oscar Quinn', 'Male', '1991-05-17', 34, '5556667778', '2025-05-22', 'Indianapolis', '46201'),
(18, 'Paul Roberts', 'Male', '1987-06-18', 38, '8889990001', '2025-06-22', 'Seattle', '98101'),
(19, 'Quincy Scott', 'Male', '1996-07-19', 29, '1234567891', '2025-07-22', 'Denver', '80201'),
(20, 'Rachel Taylor', 'Female', '1994-08-20', 31, '2345678902', '2025-08-22', 'Washington', '20001'),
(21, 'Steve Adams', 'Male', '1980-09-21', 45, '3456789012', '2025-09-22', 'Boston', '02101'),
(22, 'Tina Baker', 'Female', '1985-10-22', 40, '4567890123', '2025-10-22', 'Nashville', '37201'),
(23, 'Uma Carter', 'Female', '1990-11-23', 35, '5678901234', '2025-11-22', 'Baltimore', '21201'),
(24, 'Victor Davis', 'Male', '1995-12-24', 30, '6789012345', '2025-12-22', 'Louisville', '40201'),
(25, 'Wendy Evans', 'Female', '2000-01-25', 25, '7890123456', '2025-01-22', 'Milwaukee', '53201'),
(26, 'Xander Foster', 'Male', '1975-02-26', 50, '8901234567', '2025-02-22', 'Albuquerque', '87101'),
(27, 'Yara Green', 'Female', '1982-03-27', 43, '9012345678', '2025-03-22', 'Tucson', '85701'),
(28, 'Zack Harris', 'Male', '1988-04-28', 37, '0123456789', '2025-04-22', 'Fresno', '93701'),
(29, 'Amy Johnson', 'Female', '1993-05-29', 32, '1234567892', '2025-05-22', 'Sacramento', '94203'),
(30, 'Brian King', 'Male', '1998-06-30', 27, '2345678903', '2025-06-22', 'Kansas City', '64101'),
(31, 'Cathy Lee', 'Female', '1970-07-31', 55, '3456789013', '2025-07-22', 'Mesa', '85201'),
(32, 'David Miller', 'Male', '1983-08-01', 42, '4567890124', '2025-08-22', 'Atlanta', '30301'),
(33, 'Ella Nelson', 'Female', '1977-09-02', 48, '5678901235', '2025-09-22', 'Omaha', '68101'),
(34, 'Frank Owens', 'Male', '1981-10-03', 44, '6789012346', '2025-10-22', 'Raleigh', '27601'),
(35, 'Grace Parker', 'Female', '1992-11-04', 33, '7890123457', '2025-11-22', 'Miami', '33101'),
(36, 'Harry Quinn', 'Male', '1984-12-05', 41, '8901234568', '2025-12-22', 'Oakland', '94601'),
(37, 'Ivy Roberts', 'Female', '1991-01-06', 34, '9012345679', '2025-01-22', 'Minneapolis', '55401'),
(38, 'Jack Scott', 'Male', '1987-02-07', 38, '0123456780', '2025-02-22', 'Tulsa', '74101'),
(39, 'Karen Taylor', 'Female', '1996-03-08', 29, '1234567893', '2025-03-22', 'Arlington', '76001'),
(40, 'Larry Adams', 'Male', '1994-04-09', 31, '2345678904', '2025-04-22', 'New Orleans', '70112'),
(41, 'Mona Baker', 'Female', '1980-05-10', 45, '3456789014', '2025-05-22', 'Cleveland', '44101'),
(42, 'Nina Carter', 'Female', '1985-06-11', 40, '4567890125', '2025-06-22', 'Anaheim', '92801'),
(43, 'Oscar Davis', 'Male', '1990-07-12', 35, '5678901236', '2025-07-22', 'Aurora', '80010'),
(44, 'Paul Evans', 'Male', '1995-08-13', 30, '6789012347', '2025-08-22', 'Santa Ana', '92701'),
(45, 'Quincy Foster', 'Male', '2000-09-14', 25, '7890123458', '2025-09-22', 'St. Louis', '63101'),
(46, 'Rachel Green', 'Female', '1975-10-15', 50, '8901234569', '2025-10-22', 'Pittsburgh', '15201'),
(47, 'Steve Harris', 'Male', '1982-11-16', 43, '9012345670', '2025-11-22', 'Cincinnati', '45201'),
(48, 'Tina Johnson', 'Female', '1988-12-17', 37, '0123456781', '2025-12-22', 'Orlando', '32801'),
(49, 'Uma King', 'Female', '1993-01-18', 32, '1234567894', '2025-01-22', 'Henderson', '89002'),
(50, 'Victor Lee', 'Male', '1998-02-19', 27, '2345678905', '2025-02-22', 'Chandler', '85224');

--
-- Table structure for table `tbl_invoice`
CREATE TABLE sales (
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    phone VARCHAR(15) NOT NULL,
    paid DECIMAL(10, 2) NOT NULL,
    repay DECIMAL(10, 2) NOT NULL,
    due DECIMAL(10, 2) NOT NULL,
    customer_id INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO sales (id, phone, paid, repay, due, customer_id)
VALUES
(1, '9876543201', 100.0, 5.0, 2.5, 2),
(2, '9876543202', 200.0, 10.0, 5.0, 5),
(3, '9876543203', 300.0, 15.0, 7.5, 7),
(4, '9876543204', 400.0, 20.0, 10.0, 10),
(5, '9876543205', 500.0, 25.0, 12.5, 12),
(6, '9876543206', 600.0, 30.0, 15.0, 15),
(7, '9876543207', 700.0, 35.0, 17.5, 17),
(8, '9876543208', 800.0, 40.0, 20.0, 20),
(9, '9876543209', 900.0, 45.0, 22.5, 22),
(10, '9876543210', 1000.0, 50.0, 25.0, 25),
(11, '9876543211', 1100.0, 55.0, 27.5, 27),
(12, '9876543212', 1200.0, 60.0, 30.0, 30),
(13, '9876543213', 1300.0, 65.0, 32.5, 32),
(14, '9876543214', 1400.0, 70.0, 35.0, 35),
(15, '9876543215', 1500.0, 75.0, 37.5, 37),
(16, '9876543216', 1600.0, 80.0, 40.0, 40),
(17, '9876543217', 1700.0, 85.0, 42.5, 42),
(18, '9876543218', 1800.0, 90.0, 45.0, 45),
(19, '9876543219', 1900.0, 95.0, 47.5, 47),
(20, '9876543220', 2000.0, 100.0, 50.0, 50),
(21, '9876543221', 2100.0, 105.0, 52.5, 52),
(22, '9876543222', 2200.0, 110.0, 55.0, 55),
(23, '9876543223', 2300.0, 115.0, 57.5, 57),
(24, '9876543224', 2400.0, 120.0, 60.0, 60),
(25, '9876543225', 2500.0, 125.0, 62.5, 62),
(26, '9876543226', 2600.0, 130.0, 65.0, 65),
(27, '9876543227', 2700.0, 135.0, 67.5, 67),
(28, '9876543228', 2800.0, 140.0, 70.0, 70),
(29, '9876543229', 2900.0, 145.0, 72.5, 72),
(30, '9876543230', 3000.0, 150.0, 75.0, 75),
(31, '9876543231', 3100.0, 155.0, 77.5, 77),
(32, '9876543232', 3200.0, 160.0, 80.0, 80),
(33, '9876543233', 3300.0, 165.0, 82.5, 82),
(34, '9876543234', 3400.0, 170.0, 85.0, 85),
(35, '9876543235', 3500.0, 175.0, 87.5, 87),
(36, '9876543236', 3600.0, 180.0, 90.0, 90),
(37, '9876543237', 3700.0, 185.0, 92.5, 92),
(38, '9876543238', 3800.0, 190.0, 95.0, 95),
(39, '9876543239', 3900.0, 195.0, 97.5, 97),
(40, '9876543240', 4000.0, 200.0, 100.0, 100),
(41, '9876543241', 4100.0, 205.0, 102.5, 102),
(42, '9876543242', 4200.0, 210.0, 105.0, 105),
(43, '9876543243', 4300.0, 215.0, 107.5, 107),
(44, '9876543244', 4400.0, 220.0, 110.0, 110),
(45, '9876543245', 4500.0, 225.0, 112.5, 112),
(46, '9876543246', 4600.0, 230.0, 115.0, 115),
(47, '9876543247', 4700.0, 235.0, 117.5, 117),
(48, '9876543248', 4800.0, 240.0, 120.0, 120),
(49, '9876543249', 4900.0, 245.0, 122.5, 122),
(50, '9876543250', 5000.0, 250.0, 125.0, 125);


--

-- Table structure for table `tbl_invoice`
CREATE TABLE `tbl_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `subtotal` double NOT NULL,
  `discount` double NOT NULL,
  `sgst` float NOT NULL,
  `cgst` float NOT NULL,
  `total` double NOT NULL,
  `payment_type` tinytext NOT NULL,
  `due` double NOT NULL,
  `paid` double NOT NULL,
  `repay` double NOT NULL DEFAULT 0,
  `phone` varchar(15) NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_invoice`
--
INSERT INTO `tbl_invoice` (`invoice_id`, `order_date`, `customer_id`, `subtotal`, `discount`, `sgst`, `cgst`, `total`, `payment_type`, `due`, `paid`, `repay`, `phone`) VALUES
(1, '2025-01-22', 1, 100.0, 5.0, 2.5, 2.5, 95.0, 'Cash', 0.0, 95.0, 0.0, '1234500001'),
(2, '2025-02-22', 2, 200.0, 10.0, 5.0, 5.0, 190.0, 'Card', 0.0, 190.0, 0.0, '1234500002'),
(3, '2025-03-22', 3, 300.0, 15.0, 7.5, 7.5, 285.0, 'Cash', 0.0, 285.0, 0.0, '1234500003'),
(4, '2025-04-22', 4, 400.0, 20.0, 10.0, 10.0, 380.0, 'Card', 0.0, 380.0, 0.0, '1234500004'),
(5, '2025-05-22', 5, 500.0, 25.0, 12.5, 12.5, 475.0, 'Cash', 0.0, 475.0, 0.0, '1234500005'),
(6, '2025-06-22', 6, 600.0, 30.0, 15.0, 15.0, 570.0, 'Card', 0.0, 570.0, 0.0, '1234500006'),
(7, '2025-07-22', 7, 700.0, 35.0, 17.5, 17.5, 665.0, 'Cash', 0.0, 665.0, 0.0, '1234500007'),
(8, '2025-08-22', 8, 800.0, 40.0, 20.0, 20.0, 760.0, 'Card', 0.0, 760.0, 0.0, '1234500008'),
(9, '2025-09-22', 9, 900.0, 45.0, 22.5, 22.5, 855.0, 'Cash', 0.0, 855.0, 0.0, '1234500009'),
(10, '2025-10-22', 10, 1000.0, 50.0, 25.0, 25.0, 950.0, 'Card', 0.0, 950.0, 0.0, '1234500010'),
(11, '2025-11-22', 11, 1100.0, 55.0, 27.5, 27.5, 1045.0, 'Cash', 0.0, 1045.0, 0.0, '1234500011'),
(12, '2025-12-22', 12, 1200.0, 60.0, 30.0, 30.0, 1140.0, 'Card', 0.0, 1140.0, 0.0, '1234500012'),
(13, '2025-01-22', 13, 1300.0, 65.0, 32.5, 32.5, 1235.0, 'Cash', 0.0, 1235.0, 0.0, '1234500013'),
(14, '2025-02-22', 14, 1400.0, 70.0, 35.0, 35.0, 1330.0, 'Card', 0.0, 1330.0, 0.0, '1234500014'),
(15, '2025-03-22', 15, 1500.0, 75.0, 37.5, 37.5, 1425.0, 'Cash', 0.0, 1425.0, 0.0, '1234500015'),
(16, '2025-04-22', 16, 1600.0, 80.0, 40.0, 40.0, 1520.0, 'Card', 0.0, 1520.0, 0.0, '1234500016'),
(17, '2025-05-22', 17, 1700.0, 85.0, 42.5, 42.5, 1615.0, 'Cash', 0.0, 1615.0, 0.0, '1234500017'),
(18, '2025-06-22', 18, 1800.0, 90.0, 45.0, 45.0, 1710.0, 'Card', 0.0, 1710.0, 0.0, '1234500018'),
(19, '2025-07-22', 19, 1900.0, 95.0, 47.5, 47.5, 1805.0, 'Cash', 0.0, 1805.0, 0.0, '1234500019'),
(20, '2025-08-22', 20, 2000.0, 100.0, 50.0, 50.0, 1900.0, 'Card', 0.0, 1900.0, 0.0, '1234500020'),
(21, '2025-09-22', 21, 2100.0, 105.0, 52.5, 52.5, 1995.0, 'Cash', 0.0, 1995.0, 0.0, '1234500021'),
(22, '2025-10-22', 22, 2200.0, 110.0, 55.0, 55.0, 2090.0, 'Card', 0.0, 2090.0, 0.0, '1234500022'),
(23, '2025-11-22', 23, 2300.0, 115.0, 57.5, 57.5, 2185.0, 'Cash', 0.0, 2185.0, 0.0, '1234500023'),
(24, '2025-12-22', 24, 2400.0, 120.0, 60.0, 60.0, 2280.0, 'Card', 0.0, 2280.0, 0.0, '1234500024'),
(25, '2025-01-22', 25, 2500.0, 125.0, 62.5, 62.5, 2375.0, 'Cash', 0.0, 2375.0, 0.0, '1234500025'),
(26, '2025-02-22', 26, 2600.0, 130.0, 65.0, 65.0, 2470.0, 'Card', 0.0, 2470.0, 0.0, '1234500026'),
(27, '2025-03-22', 27, 2700.0, 135.0, 67.5, 67.5, 2565.0, 'Cash', 0.0, 2565.0, 0.0, '1234500027'),
(28, '2025-04-22', 28, 2800.0, 140.0, 70.0, 70.0, 2660.0, 'Card', 0.0, 2660.0, 0.0, '1234500028'),
(29, '2025-05-22', 29, 2900.0, 145.0, 72.5, 72.5, 2755.0, 'Cash', 0.0, 2755.0, 0.0, '1234500029'),
(30, '2025-06-22', 30, 3000.0, 150.0, 75.0, 75.0, 2850.0, 'Card', 0.0, 2850.0, 0.0, '1234500030'),
(31, '2025-07-22', 31, 3100.0, 155.0, 77.5, 77.5, 2945.0, 'Cash', 0.0, 2945.0, 0.0, '1234500031'),
(32, '2025-08-22', 32, 3200.0, 160.0, 80.0, 80.0, 3040.0, 'Card', 0.0, 3040.0, 0.0, '1234500032'),
(33, '2025-09-22', 33, 3300.0, 165.0, 82.5, 82.5, 3135.0, 'Cash', 0.0, 3135.0, 0.0, '1234500033'),
(34, '2025-10-22', 34, 3400.0, 170.0, 85.0, 85.0, 3230.0, 'Card', 0.0, 3230.0, 0.0, '1234500034'),
(35, '2025-11-22', 35, 3500.0, 175.0, 87.5, 87.5, 3325.0, 'Cash', 0.0, 3325.0, 0.0, '1234500035'),
(36, '2025-12-22', 36, 3600.0, 180.0, 90.0, 90.0, 3420.0, 'Card', 0.0, 3420.0, 0.0, '1234500036'),
(37, '2025-01-22', 37, 3700.0, 185.0, 92.5, 92.5, 3515.0, 'Cash', 0.0, 3515.0, 0.0, '1234500037'),
(38, '2025-02-22', 38, 3800.0, 190.0, 95.0, 95.0, 3610.0, 'Card', 0.0, 3610.0, 0.0, '1234500038'),
(39, '2025-03-22', 39, 3900.0, 195.0, 97.5, 97.5, 3705.0, 'Cash', 0.0, 3705.0, 0.0, '1234500039'),
(40, '2025-04-22', 40, 4000.0, 200.0, 100.0, 100.0, 3800.0, 'Card', 0.0, 3800.0, 0.0, '1234500040'),
(41, '2025-05-22', 41, 4100.0, 205.0, 102.5, 102.5, 3895.0, 'Cash', 0.0, 3895.0, 0.0, '1234500041'),
(42, '2025-06-22', 42, 4200.0, 210.0, 105.0, 105.0, 3990.0, 'Card', 0.0, 3990.0, 0.0, '1234500042'),
(43, '2025-07-22', 43, 4300.0, 215.0, 107.5, 107.5, 4085.0, 'Cash', 0.0, 4085.0, 0.0, '1234500043'),
(44, '2025-08-22', 44, 4400.0, 220.0, 110.0, 110.0, 4180.0, 'Card', 0.0, 4180.0, 0.0, '1234500044'),
(45, '2025-09-22', 45, 4500.0, 225.0, 112.5, 112.5, 4275.0, 'Cash', 0.0, 4275.0, 0.0, '1234500045'),
(46, '2025-10-22', 46, 4600.0, 230.0, 115.0, 115.0, 4370.0, 'Card', 0.0, 4370.0, 0.0, '1234500046'),
(47, '2025-11-22', 47, 4700.0, 235.0, 117.5, 117.5, 4465.0, 'Cash', 0.0, 4465.0, 0.0, '1234500047'),
(48, '2025-12-22', 48, 4800.0, 240.0, 120.0, 120.0, 4560.0, 'Card', 0.0, 4560.0, 0.0, '1234500048'),
(49, '2025-01-22', 49, 4900.0, 245.0, 122.5, 122.5, 4655.0, 'Cash', 0.0, 4655.0, 0.0, '1234500049'),
(50, '2025-02-22', 50, 5000.0, 250.0, 125.0, 125.0, 4750.0, 'Card', 0.0, 4750.0, 0.0, '1234500050');

CREATE TABLE `tbl_invoice_history` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `subtotal` double NOT NULL,
  `discount` double NOT NULL,
  `sgst` float NOT NULL,
  `cgst` float NOT NULL,
  `total` double NOT NULL,
  `payment_type` tinytext NOT NULL,
  `due` double NOT NULL,
  `paid` double NOT NULL,
  `repay` double NOT NULL DEFAULT 0,
  `phone` varchar(15) NOT NULL,
  PRIMARY KEY (`invoice_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_invoice`
--
INSERT INTO `tbl_invoice_history` (`invoice_id`, `order_date`, `customer_id`, `subtotal`, `discount`, `sgst`, `cgst`, `total`, `payment_type`, `due`, `paid`, `repay`, `phone`) VALUES
(1, '2025-01-22', 1, 100.0, 5.0, 2.5, 2.5, 95.0, 'Cash', 0.0, 95.0, 0.0, '1234500001'),
(2, '2025-02-22', 2, 200.0, 10.0, 5.0, 5.0, 190.0, 'Card', 0.0, 190.0, 0.0, '1234500002'),
(3, '2025-03-22', 3, 300.0, 15.0, 7.5, 7.5, 285.0, 'Cash', 0.0, 285.0, 0.0, '1234500003'),
(4, '2025-04-22', 4, 400.0, 20.0, 10.0, 10.0, 380.0, 'Card', 0.0, 380.0, 0.0, '1234500004'),
(5, '2025-05-22', 5, 500.0, 25.0, 12.5, 12.5, 475.0, 'Cash', 0.0, 475.0, 0.0, '1234500005'),
(6, '2025-06-22', 6, 600.0, 30.0, 15.0, 15.0, 570.0, 'Card', 0.0, 570.0, 0.0, '1234500006'),
(7, '2025-07-22', 7, 700.0, 35.0, 17.5, 17.5, 665.0, 'Cash', 0.0, 665.0, 0.0, '1234500007'),
(8, '2025-08-22', 8, 800.0, 40.0, 20.0, 20.0, 760.0, 'Card', 0.0, 760.0, 0.0, '1234500008'),
(9, '2025-09-22', 9, 900.0, 45.0, 22.5, 22.5, 855.0, 'Cash', 0.0, 855.0, 0.0, '1234500009'),
(10, '2025-10-22', 10, 1000.0, 50.0, 25.0, 25.0, 950.0, 'Card', 0.0, 950.0, 0.0, '1234500010'),
(11, '2025-11-22', 11, 1100.0, 55.0, 27.5, 27.5, 1045.0, 'Cash', 0.0, 1045.0, 0.0, '1234500011'),
(12, '2025-12-22', 12, 1200.0, 60.0, 30.0, 30.0, 1140.0, 'Card', 0.0, 1140.0, 0.0, '1234500012'),
(13, '2025-01-22', 13, 1300.0, 65.0, 32.5, 32.5, 1235.0, 'Cash', 0.0, 1235.0, 0.0, '1234500013'),
(14, '2025-02-22', 14, 1400.0, 70.0, 35.0, 35.0, 1330.0, 'Card', 0.0, 1330.0, 0.0, '1234500014'),
(15, '2025-03-22', 15, 1500.0, 75.0, 37.5, 37.5, 1425.0, 'Cash', 0.0, 1425.0, 0.0, '1234500015'),
(16, '2025-04-22', 16, 1600.0, 80.0, 40.0, 40.0, 1520.0, 'Card', 0.0, 1520.0, 0.0, '1234500016'),
(17, '2025-05-22', 17, 1700.0, 85.0, 42.5, 42.5, 1615.0, 'Cash', 0.0, 1615.0, 0.0, '1234500017'),
(18, '2025-06-22', 18, 1800.0, 90.0, 45.0, 45.0, 1710.0, 'Card', 0.0, 1710.0, 0.0, '1234500018'),
(19, '2025-07-22', 19, 1900.0, 95.0, 47.5, 47.5, 1805.0, 'Cash', 0.0, 1805.0, 0.0, '1234500019'),
(20, '2025-08-22', 20, 2000.0, 100.0, 50.0, 50.0, 1900.0, 'Card', 0.0, 1900.0, 0.0, '1234500020'),
(21, '2025-09-22', 21, 2100.0, 105.0, 52.5, 52.5, 1995.0, 'Cash', 0.0, 1995.0, 0.0, '1234500021'),
(22, '2025-10-22', 22, 2200.0, 110.0, 55.0, 55.0, 2090.0, 'Card', 0.0, 2090.0, 0.0, '1234500022'),
(23, '2025-11-22', 23, 2300.0, 115.0, 57.5, 57.5, 2185.0, 'Cash', 0.0, 2185.0, 0.0, '1234500023'),
(24, '2025-12-22', 24, 2400.0, 120.0, 60.0, 60.0, 2280.0, 'Card', 0.0, 2280.0, 0.0, '1234500024'),
(25, '2025-01-22', 25, 2500.0, 125.0, 62.5, 62.5, 2375.0, 'Cash', 0.0, 2375.0, 0.0, '1234500025'),
(26, '2025-02-22', 26, 2600.0, 130.0, 65.0, 65.0, 2470.0, 'Card', 0.0, 2470.0, 0.0, '1234500026'),
(27, '2025-03-22', 27, 2700.0, 135.0, 67.5, 67.5, 2565.0, 'Cash', 0.0, 2565.0, 0.0, '1234500027'),
(28, '2025-04-22', 28, 2800.0, 140.0, 70.0, 70.0, 2660.0, 'Card', 0.0, 2660.0, 0.0, '1234500028'),
(29, '2025-05-22', 29, 2900.0, 145.0, 72.5, 72.5, 2755.0, 'Cash', 0.0, 2755.0, 0.0, '1234500029'),
(30, '2025-06-22', 30, 3000.0, 150.0, 75.0, 75.0, 2850.0, 'Card', 0.0, 2850.0, 0.0, '1234500030'),
(31, '2025-07-22', 31, 3100.0, 155.0, 77.5, 77.5, 2945.0, 'Cash', 0.0, 2945.0, 0.0, '1234500031'),
(32, '2025-08-22', 32, 3200.0, 160.0, 80.0, 80.0, 3040.0, 'Card', 0.0, 3040.0, 0.0, '1234500032'),
(33, '2025-09-22', 33, 3300.0, 165.0, 82.5, 82.5, 3135.0, 'Cash', 0.0, 3135.0, 0.0, '1234500033'),
(34, '2025-10-22', 34, 3400.0, 170.0, 85.0, 85.0, 3230.0, 'Card', 0.0, 3230.0, 0.0, '1234500034'),
(35, '2025-11-22', 35, 3500.0, 175.0, 87.5, 87.5, 3325.0, 'Cash', 0.0, 3325.0, 0.0, '1234500035'),
(36, '2025-12-22', 36, 3600.0, 180.0, 90.0, 90.0, 3420.0, 'Card', 0.0, 3420.0, 0.0, '1234500036'),
(37, '2025-01-22', 37, 3700.0, 185.0, 92.5, 92.5, 3515.0, 'Cash', 0.0, 3515.0, 0.0, '1234500037'),
(38, '2025-02-22', 38, 3800.0, 190.0, 95.0, 95.0, 3610.0, 'Card', 0.0, 3610.0, 0.0, '1234500038'),
(39, '2025-03-22', 39, 3900.0, 195.0, 97.5, 97.5, 3705.0, 'Cash', 0.0, 3705.0, 0.0, '1234500039'),
(40, '2025-04-22', 40, 4000.0, 200.0, 100.0, 100.0, 3800.0, 'Card', 0.0, 3800.0, 0.0, '1234500040'),
(41, '2025-05-22', 41, 4100.0, 205.0, 102.5, 102.5, 3895.0, 'Cash', 0.0, 3895.0, 0.0, '1234500041'),
(42, '2025-06-22', 42, 4200.0, 210.0, 105.0, 105.0, 3990.0, 'Card', 0.0, 3990.0, 0.0, '1234500042'),
(43, '2025-07-22', 43, 4300.0, 215.0, 107.5, 107.5, 4085.0, 'Cash', 0.0, 4085.0, 0.0, '1234500043'),
(44, '2025-08-22', 44, 4400.0, 220.0, 110.0, 110.0, 4180.0, 'Card', 0.0, 4180.0, 0.0, '1234500044'),
(45, '2025-09-22', 45, 4500.0, 225.0, 112.5, 112.5, 4275.0, 'Cash', 0.0, 4275.0, 0.0, '1234500045'),
(46, '2025-10-22', 46, 4600.0, 230.0, 115.0, 115.0, 4370.0, 'Card', 0.0, 4370.0, 0.0, '1234500046'),
(47, '2025-11-22', 47, 4700.0, 235.0, 117.5, 117.5, 4465.0, 'Cash', 0.0, 4465.0, 0.0, '1234500047'),
(48, '2025-12-22', 48, 4800.0, 240.0, 120.0, 120.0, 4560.0, 'Card', 0.0, 4560.0, 0.0, '1234500048'),
(49, '2025-01-22', 49, 4900.0, 245.0, 122.5, 122.5, 4655.0, 'Cash', 0.0, 4655.0, 0.0, '1234500049'),
(50, '2025-02-22', 50, 5000.0, 250.0, 125.0, 125.0, 4750.0, 'Card', 0.0, 4750.0, 0.0, '1234500050');


-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoice_details`
--

CREATE TABLE `tbl_invoice_details` (
  `id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `barcode` varchar(200) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `qty` int(11) NOT NULL,
  `rate` double NOT NULL,
  `saleprice` double NOT NULL,
  `order_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_invoice_details`
--

INSERT INTO `tbl_invoice_details` (`id`, `invoice_id`, `customer_id`, `barcode`, `product_id`, `product_name`, `qty`, `rate`, `saleprice`, `order_date`) VALUES
(1, 1, 1, '8901057510028', 1, 'Kangaro Stapler Pins', 10, 30.0, 50.0, '2025-01-22'),
(2, 2, 2, '8901057310062', 2, 'Kangaro Stapler', 5, 150.0, 200.0, '2025-02-22'),
(3, 3, 3, '8901030865237', 3, 'kissan tomato katchup', 15, 110.0, 160.0, '2025-03-22'),
(4, 4, 4, '7121434', 4, 'lenovo charger', 8, 450.0, 800.0, '2025-04-22'),
(5, 5, 5, '5120819', 5, 'Veg Burger', 20, 50.0, 80.0, '2025-05-22'),
(6, 6, 6, '6121422', 6, 'Suger Packet 5 KG', 12, 120.0, 200.0, '2025-06-22'),
(7, 7, 7, '8901030843891', 8, 'Pepsodent Toothpaste', 30, 80.0, 140.0, '2025-07-22'),
(8, 8, 8, '8809461562230', 9, 'Modelling Comb', 18, 130.0, 250.0, '2025-08-22'),
(9, 9, 9, '8906105612730', 10, 'Wow Omega 3 Capsules', 22, 380.0, 500.0, '2025-09-22'),
(10, 10, 10, '12114804', 12, 'Mix Spices 500gm', 10, 180.0, 240.0, '2025-10-22'),
(11, 11, 11, '22072445', 22, 'Tshirt', 7, 500.0, 750.0, '2025-11-22'),
(12, 12, 12, '8901057510028', 1, 'Kangaro Stapler Pins', 5, 30.0, 50.0, '2025-12-22'),
(13, 13, 13, '8901057310062', 2, 'Kangaro Stapler', 3, 150.0, 200.0, '2025-01-22'),
(14, 14, 14, '8901030865237', 3, 'kissan tomato katchup', 8, 110.0, 160.0, '2025-02-22'),
(15, 15, 15, '7121434', 4, 'lenovo charger', 6, 450.0, 800.0, '2025-03-22'),
(16, 16, 16, '5120819', 5, 'Veg Burger', 10, 50.0, 80.0, '2025-04-22'),
(17, 17, 17, '6121422', 6, 'Suger Packet 5 KG', 15, 120.0, 200.0, '2025-05-22'),
(18, 18, 18, '8901030843891', 8, 'Pepsodent Toothpaste', 20, 80.0, 140.0, '2025-06-22'),
(19, 19, 19, '8809461562230', 9, 'Modelling Comb', 12, 130.0, 250.0, '2025-07-22'),
(20, 20, 20, '8906105612730', 10, 'Wow Omega 3 Capsules', 18, 380.0, 500.0, '2025-08-22'),
(21, 21, 21, '12114804', 12, 'Mix Spices 500gm', 12, 180.0, 240.0, '2025-09-22'),
(22, 22, 22, '22072445', 22, 'Tshirt', 10, 500.0, 750.0, '2025-10-22'),
(23, 23, 23, '8901057510028', 1, 'Kangaro Stapler Pins', 8, 30.0, 50.0, '2025-11-22'),
(24, 24, 24, '8901057310062', 2, 'Kangaro Stapler', 6, 150.0, 200.0, '2025-12-22'),
(25, 25, 25, '8901030865237', 3, 'kissan tomato katchup', 10, 110.0, 160.0, '2025-01-22'),
(26, 26, 26, '7121434', 4, 'lenovo charger', 7, 450.0, 800.0, '2025-02-22'),
(27, 27, 27, '5120819', 5, 'Veg Burger', 12, 50.0, 80.0, '2025-03-22'),
(28, 28, 28, '6121422', 6, 'Suger Packet 5 KG', 18, 120.0, 200.0, '2025-04-22'),
(29, 29, 29, '8901030843891', 8, 'Pepsodent Toothpaste', 25, 80.0, 140.0, '2025-05-22'),
(30, 30, 30, '8809461562230', 9, 'Modelling Comb', 15, 130.0, 250.0, '2025-06-22'),
(31, 31, 31, '8906105612730', 10, 'Wow Omega 3 Capsules', 20, 380.0, 500.0, '2025-07-22'),
(32, 32, 32, '12114804', 12, 'Mix Spices 500gm', 14, 180.0, 240.0, '2025-08-22'),
(33, 33, 33, '22072445', 22, 'Tshirt', 12, 500.0, 750.0, '2025-09-22'),
(34, 34, 34, '8901057510028', 1, 'Kangaro Stapler Pins', 10, 30.0, 50.0, '2025-10-22'),
(35, 35, 35, '8901057310062', 2, 'Kangaro Stapler', 8, 150.0, 200.0, '2025-11-22'),
(36, 36, 36, '8901030865237', 3, 'kissan tomato katchup', 12, 110.0, 160.0, '2025-12-22'),
(37, 37, 37, '7121434', 4, 'lenovo charger', 9, 450.0, 800.0, '2025-01-22'),
(38, 38, 38, '5120819', 5, 'Veg Burger', 15, 50.0, 80.0, '2025-02-22'),
(39, 39, 39, '6121422', 6, 'Suger Packet 5 KG', 20, 120.0, 200.0, '2025-03-22'),
(40, 40, 40, '8901030843891', 8, 'Pepsodent Toothpaste', 30, 80.0, 140.0, '2025-04-22'),
(41, 41, 41, '8809461562230', 9, 'Modelling Comb', 18, 130.0, 250.0, '2025-05-22'),
(42, 42, 42, '8906105612730', 10, 'Wow Omega 3 Capsules', 22, 380.0, 500.0, '2025-06-22'),
(43, 43, 43, '12114804', 12, 'Mix Spices 500gm', 16, 180.0, 240.0, '2025-07-22'),
(44, 44, 44, '22072445', 22, 'Tshirt', 15, 500.0, 750.0, '2025-08-22'),
(45, 45, 45, '8901057510028', 1, 'Kangaro Stapler Pins', 12, 30.0, 50.0, '2025-09-22'),
(46, 46, 46, '8901057310062', 2, 'Kangaro Stapler', 10, 150.0, 200.0, '2025-10-22'),
(47, 47, 47, '8901030865237', 3, 'kissan tomato katchup', 14, 110.0, 160.0, '2025-11-22'),
(48, 48, 48, '7121434', 4, 'lenovo charger', 11, 450.0, 800.0, '2025-12-22'),
(49, 49, 49, '5120819', 5, 'Veg Burger', 18, 50.0, 80.0, '2025-01-22'),
(50, 50, 50, '6121422', 6, 'Suger Packet 5 KG', 25, 120.0, 200.0, '2025-02-22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product`
--

CREATE TABLE `tbl_product` (
  `pid` int(11) NOT NULL,
  `barcode` varchar(1000) NOT NULL,
  `product` varchar(200) NOT NULL,
  `category` varchar(200) NOT NULL,
  `description` varchar(200) NOT NULL,
  `stock` int(11) NOT NULL,
  `purchaseprice` float NOT NULL,
  `saleprice` float NOT NULL,
  `image` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_product`
--

INSERT INTO `tbl_product` (`pid`, `barcode`, `product`, `category`, `description`, `stock`, `purchaseprice`, `saleprice`, `image`) VALUES
(1, '8901057510028', 'Kangaro Stapler Pins', 'Hardware', 'no 10 mm', 456, 30, 50, '639a07f9bd5d4.jpg'),
(2, '8901057310062', 'Kangaro Stapler', 'Hardware', 'no 10', 0, 150, 200, '639a088ba5aa8.jpg'),
(3, '8901030865237', 'kissan tomato katchup', 'Grocery', '1.2kg', 164, 110, 160, '639a0eea4ae36.jpg'),
(4, '7121434', 'lenovo charger', 'Laptop', '12v', 93, 450, 800, '639a0f563d3b6.jpg'),
(5, '5120819', 'Veg Burger', 'Veg', 'small p', 859, 50, 80, '63b95327e78f0.png'),
(6, '6121422', 'Suger Packet 5 KG', 'Grocery', '5 KG Suger Packet ', 493, 120, 200, '639a19867b32a.jpg'),
(7, '8904000952210', 'Dyna Soap', 'Soap', 'Dyna Premium Beauty Sandal and Saffron Soap', 0, 15, 25, '63a54421a2087.jpg'),
(8, '8901030843891', 'Pepsodent Toothpaste', 'Grocery', 'Pepsodent GERMI CHECK JUMBO\r\nADVANCED GERMI CHECK FORMULA  ', 334, 80, 140, '63b9a7bc9175e.jpg'),
(9, '8809461562230', 'Modelling Comb', 'Electronics', 'Modelling Comb Electronic Comb for men .', 114, 130, 250, '63a5461349496.jpg'),
(10, '8906105612730', 'Wow Omega 3 Capsules', 'Medicines', 'Wow Omega 3 60N softgel Capsules  ', 106, 380, 500, '63a546b584e3b.jpg'),
(11, '6971914086630', 'Real me XT', 'Mobile', 'Model : RMX1921', 145, 18000, 21500, '63a547ca0e89a.jpg'),
(12, '12114804', 'Mix Spices 500gm', 'Grocery', 'Mix Spices Pack 500gm', 296, 180, 240, '63a5481c962ff.jpg'),
(22, '22072445', 'Tshirt', 'Fashion', 'Black Tshirt', 100, 500, 750, '662efde515daa.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_taxdis`
--

CREATE TABLE `tbl_taxdis` (
  `taxdis_id` int(11) NOT NULL,
  `sgst` float NOT NULL,
  `cgst` float NOT NULL,
  `discount` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_taxdis`
--

INSERT INTO `tbl_taxdis` (`taxdis_id`, `sgst`, `cgst`, `discount`) VALUES
(1, 2.5, 2.5, 2),
(2, 5, 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `useremail` varchar(200) NOT NULL,
  `userpassword` varchar(200) NOT NULL,
  `role` varchar(50) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `company_website` varchar(255) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`userid`, `username`, `useremail`, `userpassword`, `role`, `company_name`, `company_website`, `phone`, `address`) VALUES
(2, 'user', 'user@gmail.com', '123', 'User', 'User Company', 'https://usercompany.com', '1234567890', 'User Address'),
(15, 'Admin_2', 'admin2@gmail.com', '12345', 'Admin', NULL, NULL, NULL, NULL),
(16, 'Admin', 'admin@gmail.com', '12345', 'Admin', NULL, NULL, NULL, NULL),
(17, 'Sarath', 'sarath@gmail.com', '12345', 'User', 'Sarath Company', 'https://sarathcompany.com', '9876543210', 'Sarath Address'),
(19, 'Barath', 'b@gmail.com', '12345', 'Admin', NULL, NULL, NULL, NULL);
--
-- Indexes for dumped tables
CREATE TABLE `customer_ids` (
  `invoice_id` INT NOT NULL,
  `due` DOUBLE NOT NULL,
  `phone` VARCHAR(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customer_ids` (`invoice_id`, `due`, `phone`) VALUES
(1, 0.0, '1234567890'),
(2, 0.0, '0987654321'),
(3, 0.0, '1231231234'),
(4, 0.0, '3213214321'),
(5, 0.0, '4564564567'),
(6, 0.0, '6546546543'),
(7, 0.0, '7897897890'),
(8, 0.0, '9879879876'),
(9, 0.0, '1112223334'),
(10, 0.0, '4445556667'),
(11, 0.0, '7778889990'),
(12, 0.0, '0001112223'),
(13, 0.0, '3334445556'),
(14, 0.0, '6667778889'),
(15, 0.0, '9990001112'),
(16, 0.0, '2223334445'),
(17, 0.0, '5556667778'),
(18, 0.0, '8889990001'),
(19, 0.0, '1234567891'),
(20, 0.0, '2345678902');

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `tbl_invoice`
--
-- ALTER TABLE `tbl_invoice`
--   ADD PRIMARY KEY (`invoice_id`);

--
-- Indexes for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_product`
--
ALTER TABLE `tbl_product`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `tbl_taxdis`
--
ALTER TABLE `tbl_taxdis`
  ADD PRIMARY KEY (`taxdis_id`);

--
-- Indexes for table `tbl_user`
--
-- ALTER TABLE `tbl_user`
--   ADD PRIMARY KEY (`userid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `tbl_customer`
--
ALTER TABLE `tbl_customer`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `tbl_invoice`
--
ALTER TABLE `tbl_invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_invoice_details`
--
ALTER TABLE `tbl_invoice_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;

--
-- AUTO_INCREMENT for table `tbl_product`
--
ALTER TABLE `tbl_product`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tbl_taxdis`
--
ALTER TABLE `tbl_taxdis`
  MODIFY `taxdis_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_user`
--
-- ALTER TABLE `tbl_user`
--   MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
-- COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
