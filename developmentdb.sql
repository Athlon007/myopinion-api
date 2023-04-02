-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: mysql
-- Generation Time: Apr 02, 2023 at 06:33 PM
-- Server version: 10.9.4-MariaDB-1:10.9.4+maria~ubu2204
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `developmentdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `Accounts`
--

CREATE TABLE `Accounts` (
  `id` int(11) NOT NULL,
  `username` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `passwordHash` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `accountType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Accounts`
--

INSERT INTO `Accounts` (`id`, `username`, `email`, `passwordHash`, `salt`, `accountType`) VALUES
(2, 'admin', 'aathlon@outlook.com', '$2y$11$yje0He/UBTkimlkgvrEG5e7ErX2LSTnRtksUX67fITqggveyOhEbC', 'l{M+kuE/we(@oi.Ob``b]%lxd*(yd*M`xS4,1dX`@r40@.Kqtm%6F3j)9P~?KDv!', 1),
(4, 'asdf', 'asdf1', '$2y$11$hFwrgaFZgbmjsCNYeRckIOO8eKUY8n0L2A4l16SdQmR7s.lcmdd3W', 'p&lt;aY*5K&amp;pCAV0fnM84CJW{l*ON1&quot;BmM}a2z]ScX6H[Ep?K?R7L&gt;a0_03Y}3r~&gt;&quot;9', 2);

-- --------------------------------------------------------

--
-- Table structure for table `AccountTypes`
--

CREATE TABLE `AccountTypes` (
  `id` int(11) NOT NULL,
  `name` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `AccountTypes`
--

INSERT INTO `AccountTypes` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Moderator');

-- --------------------------------------------------------

--
-- Table structure for table `Opinions`
--

CREATE TABLE `Opinions` (
  `id` int(11) NOT NULL,
  `title` varchar(32) NOT NULL,
  `content` varchar(512) NOT NULL,
  `topicID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Opinions`
--

INSERT INTO `Opinions` (`id`, `title`, `content`, `topicID`) VALUES
(11, 'An opinion', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam pulvinar a odio et pulvinar. Morbi tempus eu tellus id pretium. In dapibus aliquet ex non commodo. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Morbi vitae elit lectus. Vivamus varius, sem eu facilisis scelerisque, nibh quam porta lectus, a viverra neque lectus at justo. Suspendisse potenti. Maecenas tempor ex id neque sollicitudin pharetra.', 4),
(12, 'Cheese', 'Parmesan cheese slices paneer. Cut the cheese mozzarella macaroni cheese emmental cheesy grin chalk and cheese parmesan the big cheese. Smelly cheese paneer mascarpone boursin manchego stinking bishop halloumi cheese and wine. Cheese strings cut the cheese monterey jack cheese and biscuits danish fontina cream cheese everyone loves.', 4),
(13, 'iPear', 'no thoughts, only head empty', 4),
(14, 'yarr harr', 'Trysail Sail ho Corsair red ensign hulk smartly boom jib rum gangway. ', 4),
(15, 'Yarrr!', 'Prow scuttle parrel provost Sail ho shrouds spirits boom mizzenmast yardarm. Pinnace holystone mizzenmast quarter crow&#039;s nest nipperkin grog yardarm hempen halter furl. Swab barque interloper chantey doubloon starboard grog black jack gangway rutters.\r\nDeadlights jack lad schooner scallywag dance the hempen jig carouser broadside cable strike colors. Bring a spring upon her cable holystone blow the man down spanker Shiver me timbers to go on account lookout wherry doubloon chase. ', 4),
(16, 'I&#039;m a spam!', 'You can find me in the reports list', 4),
(21, 'Nice school!', 'it&#039;s nice, although the coffee from the machines sucks.', 5),
(23, 'CHEEEEESY 🧀', 'Cheese strings airedale caerphilly. Hard cheese mascarpone camembert de normandie boursin babybel st. agur blue cheese smelly cheese brie. ', 4),
(24, 'And now a short opinion', 'Pear Computers? I hardly knew her.', 4),
(25, 'Hipster time!', 'I&#039;m baby hoodie crucifix small batch flexitarian organic wolf wayfarers hot chicken shaman migas praxis synth disrupt poutine. ', 4),
(26, 'Another hipster', 'Gochujang af direct trade, asymmetrical williamsburg vinyl mustache raclette vape.', 4),
(27, 'I ran out of ideas', 'Idk what to type anymore...', 4),
(28, 'Web Development is fun', '10/10, would web develop again.', 5),
(29, 'beep boop', '🤖', 6),
(30, 'ooo crowbar', 'something something resonance cascade.', 8),
(31, 'The IT design subjects are awful', 'Please remove front-end design from the IT curriculum all together, thank you', 5),
(32, 'Are you hungry?', 'Then join Wolfhound', 5),
(34, 'Cats are better', 'than dogs ', 5),
(35, 'My opinion does not matter', 'So sorry, but you\'ve been h4x0r3d &lt;script&gt;alert(\'h4x0r3d\')&lt;/script&gt;â¤ï¸', 5),
(38, 'Test', '123', 10),
(39, 'Another one', 'w0w', 10);

-- --------------------------------------------------------

--
-- Table structure for table `ReactionEntities`
--

CREATE TABLE `ReactionEntities` (
  `id` int(4) NOT NULL,
  `htmlEntity` varchar(16) NOT NULL,
  `isNegative` bit(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ReactionEntities`
--

INSERT INTO `ReactionEntities` (`id`, `htmlEntity`, `isNegative`) VALUES
(1, '&#128077', b'0'),
(2, '&#128078', b'1'),
(3, '&#x1F602;', b'0'),
(5, '&#x1F62E;', b'0'),
(6, '&#x1F9C0;', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `Reactions`
--

CREATE TABLE `Reactions` (
  `id` int(11) NOT NULL,
  `reactionID` int(11) NOT NULL,
  `opinionID` int(11) NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Reactions`
--

INSERT INTO `Reactions` (`id`, `reactionID`, `opinionID`, `count`) VALUES
(22, 1, 11, 34),
(24, 3, 11, 14),
(26, 1, 21, 3),
(27, 3, 21, 1),
(28, 2, 11, 4),
(32, 1, 13, 1),
(33, 5, 13, 4),
(34, 2, 16, 2),
(35, 6, 23, 2),
(36, 5, 30, 21),
(37, 3, 28, 2),
(38, 6, 32, 13),
(39, 3, 31, 1),
(40, 1, 32, 1),
(41, 1, 28, 1),
(42, 2, 31, 1),
(43, 1, 34, 1),
(44, 2, 29, 2),
(51, 6, 30, 6),
(52, 1, 30, 3),
(59, 3, 30, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Reports`
--

CREATE TABLE `Reports` (
  `id` int(11) NOT NULL,
  `opinionID` int(11) NOT NULL,
  `reportType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Reports`
--

INSERT INTO `Reports` (`id`, `opinionID`, `reportType`) VALUES
(8, 16, 3),
(9, 16, 3),
(10, 16, 3),
(11, 31, 1),
(12, 16, 0),
(14, 29, 0),
(15, 29, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Settings`
--

CREATE TABLE `Settings` (
  `selectedNthTopic` int(11) NOT NULL,
  `dateLastTopicSelected` date NOT NULL,
  `hideOpinionsWithNReports` int(11) NOT NULL,
  `maxReactionsPerPage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Settings`
--

INSERT INTO `Settings` (`selectedNthTopic`, `dateLastTopicSelected`, `hideOpinionsWithNReports`, `maxReactionsPerPage`) VALUES
(4, '2023-03-27', 5, 5);

-- --------------------------------------------------------

--
-- Table structure for table `Topics`
--

CREATE TABLE `Topics` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Topics`
--

INSERT INTO `Topics` (`id`, `name`) VALUES
(4, 'Pear Computers'),
(5, 'OutNetherlands School'),
(6, 'Majorsoft'),
(7, 'Edison Cars'),
(8, 'Full-Life Game'),
(9, 'Some Topic'),
(10, 'Test Topic');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Accounts`
--
ALTER TABLE `Accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FK_AccountType` (`accountType`);

--
-- Indexes for table `AccountTypes`
--
ALTER TABLE `AccountTypes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Opinions`
--
ALTER TABLE `Opinions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_topic_id` (`topicID`);

--
-- Indexes for table `ReactionEntities`
--
ALTER TABLE `ReactionEntities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Reactions`
--
ALTER TABLE `Reactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_opinion_id` (`opinionID`),
  ADD KEY `fk_allowed_reaction_id` (`reactionID`);

--
-- Indexes for table `Reports`
--
ALTER TABLE `Reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_opinionID` (`opinionID`);

--
-- Indexes for table `Topics`
--
ALTER TABLE `Topics`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Accounts`
--
ALTER TABLE `Accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `AccountTypes`
--
ALTER TABLE `AccountTypes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `Opinions`
--
ALTER TABLE `Opinions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `ReactionEntities`
--
ALTER TABLE `ReactionEntities`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `Reactions`
--
ALTER TABLE `Reactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `Reports`
--
ALTER TABLE `Reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `Topics`
--
ALTER TABLE `Topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Accounts`
--
ALTER TABLE `Accounts`
  ADD CONSTRAINT `FK_AccountType` FOREIGN KEY (`accountType`) REFERENCES `AccountTypes` (`id`);

--
-- Constraints for table `Opinions`
--
ALTER TABLE `Opinions`
  ADD CONSTRAINT `fk_topic_id` FOREIGN KEY (`topicID`) REFERENCES `Topics` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Reactions`
--
ALTER TABLE `Reactions`
  ADD CONSTRAINT `fk_allowed_reaction_id` FOREIGN KEY (`reactionID`) REFERENCES `ReactionEntities` (`id`),
  ADD CONSTRAINT `fk_opinion_id` FOREIGN KEY (`opinionID`) REFERENCES `Opinions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Reports`
--
ALTER TABLE `Reports`
  ADD CONSTRAINT `FK_opinionID` FOREIGN KEY (`opinionID`) REFERENCES `Opinions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
