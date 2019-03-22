-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 02, 2015 at 06:50 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `socialnw`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE IF NOT EXISTS `announcement` (
`id` bigint(20) unsigned NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `like` bigint(20) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `announcement`
--

INSERT INTO `announcement` (`id`, `content`, `title`, `create_date`, `user_id`, `like`) VALUES
(34, 'test of lev4', 'test', '2015-06-27 00:19:18', 2, 2),
(35, 'test of tested', 'test of', '2015-06-27 00:19:34', 2, 1),
(36, 'test 3 announcement', 'test', '2015-06-27 00:19:48', 2, 2),
(38, 'test of announcement user', 'test ', '2015-06-27 00:20:30', 3, 2),
(40, 'test of title', 'test of announcement', '2015-06-27 02:08:33', 2, 1),
(41, 'add', 'test', '2015-06-27 08:30:16', 3, 1),
(42, 'announcement', 'test ', '2015-06-27 08:31:58', 3, 1),
(43, 'test', 'test', '2015-06-27 08:32:10', 3, 2),
(44, 'test of test', 'test', '2015-06-27 08:33:13', 3, 1),
(46, 'test of chichat\n', 'test', '2015-06-29 00:49:53', 2, 1),
(47, 'test', 'test', '2015-06-29 01:15:21', 2, 1),
(48, 'i want more', 'test', '2015-06-29 01:15:26', 2, 0),
(49, 'tets', 'test', '2015-06-29 01:15:30', 2, 0),
(50, 'tah', 'test', '2015-06-29 10:53:01', 2, 0),
(51, 'more and more', 'test', '2015-06-29 01:15:37', 2, 1),
(52, 'alaa', 'test', '2015-06-30 06:44:30', 2, 1),
(53, 'test of more announcement', 'tets', '2015-06-30 01:35:12', 2, 1),
(54, 'test of tested', 'test', '2015-06-30 01:37:50', 2, 0),
(55, 'first one here', 'test', '2015-06-30 01:38:02', 2, 0),
(56, 'first also', 'tets', '2015-06-30 01:39:47', 2, 1),
(57, 'test of add', 'test', '2015-06-30 23:29:14', 2, 0),
(58, 'add more and more\n', 'test', '2015-07-01 02:39:33', 2, 0),
(59, 'test of test test', 'test', '2015-07-01 02:41:01', 2, 1),
(60, 'add moe lev', 'test ', '2015-07-01 02:41:20', 2, 0),
(61, 'test', 'test', '2015-07-02 16:39:39', 3, 0),
(62, 'test', 'test', '2015-07-02 16:39:59', 3, 0),
(63, 'test', 'test', '2015-07-02 16:40:10', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `announcement_group`
--

CREATE TABLE IF NOT EXISTS `announcement_group` (
  `group_id` bigint(20) unsigned NOT NULL,
  `announcement_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `announcement_group`
--

INSERT INTO `announcement_group` (`group_id`, `announcement_id`) VALUES
(19, 40),
(19, 41),
(26, 42),
(26, 53),
(19, 54),
(22, 55),
(29, 56),
(26, 58),
(22, 59),
(22, 61),
(27, 63);

--
-- Triggers `announcement_group`
--
DELIMITER //
CREATE TRIGGER `ANNOUNCEMENT_GROUP_AF_INS` AFTER INSERT ON `announcement_group`
 FOR EACH ROW BEGIN
	
	DECLARE MESSAGE TEXT ;
	DECLARE USERS_NAME VARCHAR(200);
	DECLARE GROUPS_NAME VARCHAR(200);
	DECLARE done INT DEFAULT FALSE;
	DECLARE USER INT ;
	DECLARE NOTIFICATION INT ;	
    DECLARE cur1 CURSOR FOR 
    SELECT M.`user_id` FROM GROUP_MEMBERS AS M 
	INNER JOIN USERS AS U ON U.ID = M.USER_ID
	INNER JOIN GROUPS AS G ON G.ID = M.GROUP_ID
	WHERE M.REQUEST = 0 AND M.GROUP_ID =  NEW.`GROUP_ID` 
	AND M.`user_id` NOT IN  (SELECT `USER_ID` FROM `ANNOUNCEMENT` WHERE `ID` = NEW.`ANNOUNCEMENT_ID`);
   
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	SET USERS_NAME = (SELECT CONCAT(`first_name`,' ',`last_name`) FROM `USERS` 
	WHERE `ID` = (SELECT `USER_ID` FROM `ANNOUNCEMENT` WHERE `ID` = NEW.`ANNOUNCEMENT_ID`));

	SET GROUPS_NAME = (SELECT name FROM `GROUPS` 
	WHERE `ID` = NEW.`GROUP_ID`);

	SET MESSAGE = CONCAT('<b>' , USERS_NAME ,'</b>',' ','post Announcement in ','<b>',GROUPS_NAME,'</b>');

	INSERT INTO `NOTIFICATION` (`USERS`,`GROUPS`,`MESSAGE`) 
	VALUES ((SELECT `USER_ID` FROM `ANNOUNCEMENT` WHERE `ID` = NEW.`ANNOUNCEMENT_ID`),NEW.`GROUP_ID`,MESSAGE);

	SET NOTIFICATION = (SELECT LAST_INSERT_ID());

	OPEN cur1;
	read_loop: LOOP
	FETCH cur1 INTO USER;

	IF done THEN
      LEAVE read_loop;
    END IF;

    INSERT INTO `NOTIFICATION_SEEN` (`NOTIFICATION`,`USERS`) VALUES (NOTIFICATION,USER);
	 END LOOP;
	CLOSE cur1;

    
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `announcement_like`
--

CREATE TABLE IF NOT EXISTS `announcement_like` (
  `announcement_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `announcement_like`
--

INSERT INTO `announcement_like` (`announcement_id`, `user_id`) VALUES
(34, 2),
(35, 2),
(36, 2),
(38, 2),
(40, 2),
(41, 2),
(43, 2),
(44, 2),
(46, 2),
(47, 2),
(51, 2),
(52, 2),
(53, 2),
(56, 2),
(59, 2),
(34, 3),
(36, 3),
(38, 3),
(42, 3),
(43, 3);

-- --------------------------------------------------------

--
-- Table structure for table `announcement_user`
--

CREATE TABLE IF NOT EXISTS `announcement_user` (
  `user_id` bigint(20) unsigned NOT NULL,
  `announcement_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `announcement_user`
--

INSERT INTO `announcement_user` (`user_id`, `announcement_id`) VALUES
(2, 34),
(3, 34),
(5, 34),
(6, 34),
(8, 34),
(11, 34),
(14, 34),
(15, 34),
(19, 34),
(28, 34),
(30, 34),
(31, 34),
(34, 34),
(35, 34),
(44, 34),
(45, 34),
(48, 34),
(49, 34),
(51, 34),
(52, 34),
(10, 35),
(16, 35),
(18, 35),
(22, 35),
(27, 35),
(29, 35),
(37, 35),
(38, 35),
(53, 35),
(2, 36),
(3, 36),
(5, 36),
(6, 36),
(8, 36),
(11, 36),
(14, 36),
(15, 36),
(19, 36),
(28, 36),
(30, 36),
(31, 36),
(34, 36),
(35, 36),
(44, 36),
(45, 36),
(48, 36),
(49, 36),
(51, 36),
(52, 36),
(2, 38),
(3, 38),
(5, 38),
(6, 38),
(8, 38),
(11, 38),
(14, 38),
(15, 38),
(19, 38),
(28, 38),
(30, 38),
(31, 38),
(34, 38),
(35, 38),
(44, 38),
(45, 38),
(48, 38),
(49, 38),
(51, 38),
(52, 38),
(2, 43),
(3, 43),
(5, 43),
(6, 43),
(8, 43),
(11, 43),
(14, 43),
(15, 43),
(19, 43),
(28, 43),
(30, 43),
(31, 43),
(34, 43),
(35, 43),
(44, 43),
(45, 43),
(48, 43),
(49, 43),
(51, 43),
(52, 43),
(2, 44),
(3, 44),
(5, 44),
(6, 44),
(8, 44),
(11, 44),
(14, 44),
(15, 44),
(19, 44),
(28, 44),
(30, 44),
(31, 44),
(34, 44),
(35, 44),
(44, 44),
(45, 44),
(48, 44),
(49, 44),
(51, 44),
(52, 44),
(2, 60),
(3, 60),
(5, 60),
(6, 60),
(8, 60),
(11, 60),
(14, 60),
(15, 60),
(19, 60),
(28, 60),
(30, 60),
(31, 60),
(34, 60),
(35, 60),
(44, 60),
(45, 60),
(48, 60),
(49, 60),
(51, 60),
(52, 60);

--
-- Triggers `announcement_user`
--
DELIMITER //
CREATE TRIGGER `ANNOUNCEMENT_USER_AF_INS` AFTER INSERT ON `announcement_user`
 FOR EACH ROW BEGIN


	DECLARE MESSAGE TEXT ;
	DECLARE USERS_NAME VARCHAR(200);
	DECLARE done INT DEFAULT FALSE;
	DECLARE USER INT ;
	DECLARE NOTIFICATION INT ;	
    DECLARE cur1 CURSOR FOR 
   	SELECT if(user1= NEW.`user_id`,user2,user1) AS USERS 
            FROM (SELECT `user1_id` AS user1, `user2_id` AS user2 
            FROM `friends_list` AS M
            inner join users AS U1 on U1.id = M.`user1_id`
            inner join users AS U2 on U2.id = M.`user2_id`
            where M.`user1_id` = NEW.`user_id`  or M.`user2_id` = NEW.`user_id`
                 ) AS sb;
   
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	SET USERS_NAME = (SELECT CONCAT(`first_name`,' ',`last_name`) FROM `USERS` 
	WHERE `ID` = NEW.`user_id`);

	SET MESSAGE = CONCAT('<b>' ,USERS_NAME,'</b>',' ','post Announcement ');

	INSERT INTO `NOTIFICATION` (`USERS`,`MESSAGE`) 
	VALUES (NEW.`user_id`,MESSAGE);

	SET NOTIFICATION = (SELECT LAST_INSERT_ID());

	OPEN cur1;
	read_loop: LOOP
	FETCH cur1 INTO USER;

	IF done THEN
      LEAVE read_loop;
    END IF;

    INSERT INTO `NOTIFICATION_SEEN` (`NOTIFICATION`,`USERS`) VALUES (NOTIFICATION,USER);
	 END LOOP;
	CLOSE cur1;
   
 END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `answer_highlighting`
--

CREATE TABLE IF NOT EXISTS `answer_highlighting` (
  `answer_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `highlighting_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `answer_user_rate`
--

CREATE TABLE IF NOT EXISTS `answer_user_rate` (
  `answer_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `updated_at` date NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `answer_user_rate`
--

INSERT INTO `answer_user_rate` (`answer_id`, `user_id`, `updated_at`, `created_at`) VALUES
(1, 2, '2015-05-12', '2015-05-12'),
(4, 2, '2015-05-13', '2015-05-13'),
(5, 2, '2015-05-13', '2015-05-13'),
(76, 2, '2015-06-19', '2015-06-19'),
(28, 2, '2015-06-19', '2015-06-19');

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE IF NOT EXISTS `assignment` (
`id` bigint(20) NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `due_date` datetime NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Triggers `assignment`
--
DELIMITER //
CREATE TRIGGER `ASSIGNMENT_AF_INS` AFTER INSERT ON `assignment`
 FOR EACH ROW BEGIN
	
	DECLARE MESSAGE TEXT ;
	DECLARE USERS_NAME VARCHAR(200);
	DECLARE GROUPS_NAME VARCHAR(200);
	DECLARE done INT DEFAULT FALSE;
	DECLARE USER INT ;
	DECLARE NOTIFICATION INT ;	
    DECLARE cur1 CURSOR FOR 
    SELECT M.`user_id` FROM GROUP_MEMBERS AS M 
	INNER JOIN USERS AS U ON U.ID = M.USER_ID
	INNER JOIN GROUPS AS G ON G.ID = M.GROUP_ID
	WHERE M.REQUEST = 0 AND M.GROUP_ID =  NEW.`GROUP_ID` 
	AND M.`user_id` NOT IN  (NEW.`USER_ID`);
   
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	SET USERS_NAME = (SELECT CONCAT(`first_name`,' ',`last_name`) FROM `USERS` 
	WHERE `ID` = NEW.`USER_ID`);

	SET GROUPS_NAME = (SELECT name FROM `GROUPS` 
	WHERE `ID` = NEW.`GROUP_ID`);

	SET MESSAGE = CONCAT('<b>',USERS_NAME,'</b>',' ','Add Assignment ( ',NEW.`TITLE`,' 	) in ','<b>',GROUPS_NAME,'</b>',' With Due Date <small><i class="fa fa-clock-o"></i>',NEW.`due_date`,'</small>');

	INSERT INTO `NOTIFICATION` (`USERS`,`GROUPS`,`MESSAGE`) 
	VALUES (NEW.`USER_ID`,NEW.`GROUP_ID`,MESSAGE);

	SET NOTIFICATION = (SELECT LAST_INSERT_ID());

	OPEN cur1;
	read_loop: LOOP
	FETCH cur1 INTO USER;

	IF done THEN
      LEAVE read_loop;
    END IF;

    INSERT INTO `NOTIFICATION_SEEN` (`NOTIFICATION`,`USERS`) VALUES (NOTIFICATION,USER);
	 END LOOP;
	CLOSE cur1;

    
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `assignment_solution`
--

CREATE TABLE IF NOT EXISTS `assignment_solution` (
  `user_id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `create_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_roome`
--

CREATE TABLE IF NOT EXISTS `chat_roome` (
`id` int(11) NOT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `private` enum('0','1') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `chat_roome`
--

INSERT INTO `chat_roome` (`id`, `name`, `created_at`, `updated_at`, `private`) VALUES
(1, 'test', '2015-03-10 00:00:00', '2015-03-10 00:00:00', '0'),
(2, 'omar', '2015-03-24 00:00:00', '2015-03-18 00:00:00', '0'),
(3, NULL, '2015-04-03 19:13:34', '2015-04-03 19:13:34', '1'),
(4, NULL, '2015-04-03 19:16:10', '2015-04-03 19:16:10', '1'),
(5, NULL, '2015-04-03 19:18:22', '2015-04-03 19:18:22', '1'),
(6, NULL, '2015-04-03 19:29:11', '2015-04-03 19:29:11', '1'),
(7, NULL, '2015-04-03 20:10:28', '2015-04-03 20:10:28', '1'),
(8, NULL, '2015-04-23 23:33:28', '2015-04-23 23:33:28', '1'),
(9, NULL, '2015-04-23 23:33:35', '2015-04-23 23:33:35', '1'),
(10, NULL, '2015-04-23 23:33:40', '2015-04-23 23:33:40', '1'),
(11, NULL, '2015-05-11 14:51:04', '2015-05-11 14:51:04', '1'),
(12, NULL, '2015-05-11 23:46:49', '2015-05-11 23:46:49', '1'),
(13, NULL, '2015-05-11 23:46:54', '2015-05-11 23:46:54', '1');

-- --------------------------------------------------------

--
-- Table structure for table `chat_room_users`
--

CREATE TABLE IF NOT EXISTS `chat_room_users` (
  `user_id` bigint(20) unsigned NOT NULL,
  `chat_room_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comment_answer`
--

CREATE TABLE IF NOT EXISTS `comment_answer` (
`id` bigint(20) unsigned NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `answer_id` bigint(20) unsigned DEFAULT NULL,
  `announcement_id` bigint(11) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `rate` int(11) DEFAULT '0',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=360 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `comment_answer`
--

INSERT INTO `comment_answer` (`id`, `content`, `answer_id`, `announcement_id`, `user_id`, `rate`, `created_at`, `updated_at`) VALUES
(5, 'sdfgghjk9oi', 1, NULL, 2, 0, '2015-05-12 00:19:02', '2015-05-12 00:19:02'),
(6, 'Waiting in a day or two: wouldn''t it be of any one; so, when the tide rises and sharks are around,.', 133, NULL, 38, 0, '2015-06-19 14:14:00', '2015-06-19 14:14:00'),
(7, 'Would not, could not, would not stoop? Soup of the way--'' ''THAT generally takes some time,'' interrupted the Hatter: ''as the.', 10, NULL, 32, 0, '2015-06-19 14:14:01', '2015-06-19 14:14:01'),
(8, 'Alice, ''and if it had come to the tarts on the Duchess''s knee, while plates and dishes crashed around.', 62, NULL, 21, 0, '2015-06-19 14:14:01', '2015-06-19 14:14:01'),
(9, 'There was not easy to take MORE than nothing.'' ''Nobody asked YOUR opinion,'' said Alice. ''Anything you like,'' said the White Rabbit put on.', 119, NULL, 33, 0, '2015-06-19 14:14:01', '2015-06-19 14:14:01'),
(10, 'Why, I do hope it''ll make me larger, it must be off, then!'' said the Hatter. ''You might just as she spoke, but.', 68, NULL, 6, 0, '2015-06-19 14:14:01', '2015-06-19 14:14:01'),
(11, 'Mystery,'' the Mock Turtle. Alice was soon left alone. ''I wish the creatures wouldn''t be in a hurry to.', 9, NULL, 48, 0, '2015-06-19 14:14:01', '2015-06-19 14:14:01'),
(12, 'Hatter. ''Nor I,'' said the Queen, the royal children, and everybody else. ''Leave off that!'' screamed the Gryphon. ''They can''t have anything.', 110, NULL, 53, 0, '2015-06-19 14:14:02', '2015-06-19 14:14:02'),
(13, 'I can''t take LESS,'' said the Queen. ''Can you play croquet?'' The soldiers were silent, and looked into its face to see the Mock Turtle said: ''no.', 34, NULL, 17, 0, '2015-06-19 14:14:02', '2015-06-19 14:14:02'),
(14, 'Hatter were having tea at it: a Dormouse was sitting on a little different. But if I''m Mabel, I''ll stay down here till I''m somebody.', 129, NULL, 29, 0, '2015-06-19 14:14:02', '2015-06-19 14:14:02'),
(15, 'And beat him when he sneezes: He only does it matter to me whether you''re a little before she found to be no use speaking to.', 152, NULL, 33, 0, '2015-06-19 14:14:02', '2015-06-19 14:14:02'),
(16, 'Besides, SHE''S she, and I''m sure she''s the best of educations--in fact, we went to the Dormouse, not choosing to.', 133, NULL, 44, 0, '2015-06-19 14:14:02', '2015-06-19 14:14:02'),
(17, 'I shall be a LITTLE larger, sir, if you don''t explain it is all the while, and fighting for the moment they saw the.', 124, NULL, 23, 0, '2015-06-19 14:14:02', '2015-06-19 14:14:02'),
(18, 'And yet you incessantly stand on their slates, when the Rabbit whispered in reply, ''for fear they should.', 33, NULL, 17, 0, '2015-06-19 14:14:03', '2015-06-19 14:14:03'),
(19, 'I don''t understand. Where did they live on?'' said Alice, ''we learned French and music.'' ''And washing?'' said the cook. The.', 67, NULL, 23, 0, '2015-06-19 14:14:03', '2015-06-19 14:14:03'),
(20, 'Alice; and Alice called out in a hot tureen! Who for such dainties would not stoop? Soup of the Mock Turtle.', 69, NULL, 19, 0, '2015-06-19 14:14:03', '2015-06-19 14:14:03'),
(21, 'I shan''t go, at any rate I''ll never go THERE again!'' said Alice very humbly: ''you had got its head impatiently, and walked off; the.', 77, NULL, 26, 0, '2015-06-19 14:14:03', '2015-06-19 14:14:03'),
(22, 'Alice ventured to taste it, and found quite a long argument with the other side of the sort. Next came an angry.', 155, NULL, 8, 0, '2015-06-19 14:14:03', '2015-06-19 14:14:03'),
(23, 'I hadn''t mentioned Dinah!'' she said to the beginning of the Lobster Quadrille, that she had finished, her sister sat.', 82, NULL, 24, 0, '2015-06-19 14:14:03', '2015-06-19 14:14:03'),
(24, 'I suppose?'' ''Yes,'' said Alice, as she could not think of nothing better to say when I get it.', 109, NULL, 4, 0, '2015-06-19 14:14:04', '2015-06-19 14:14:04'),
(25, 'Stigand, the patriotic archbishop of Canterbury, found it advisable--"'' ''Found WHAT?'' said the King, ''unless it was too slippery; and.', 64, NULL, 2, 0, '2015-06-19 14:14:04', '2015-06-19 14:14:04'),
(26, 'I hadn''t drunk quite so much!'' said Alice, who always took a great letter, nearly as she could do to come.', 64, NULL, 33, 0, '2015-06-19 14:14:04', '2015-06-19 14:14:04'),
(27, 'Alice again. ''No, I didn''t,'' said Alice: ''I don''t know what a Gryphon is, look at the cook, to see if there are, nobody attends to.', 132, NULL, 5, 0, '2015-06-19 14:14:04', '2015-06-19 14:14:04'),
(28, 'Alice, a good thing!'' she said to Alice, and she told her sister, as well as pigs, and was surprised to see.', 138, NULL, 8, 0, '2015-06-19 14:14:04', '2015-06-19 14:14:04'),
(29, 'King; and the pattern on their faces, so that her neck kept getting entangled among the bright flower-beds and the baby was howling so much at.', 1, NULL, 54, 0, '2015-06-19 14:14:04', '2015-06-19 14:14:04'),
(30, 'YOUR shoes done with?'' said the Cat. ''I''d nearly forgotten that I''ve got back to the little door, so she took up the chimney, has.', 53, NULL, 30, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(31, 'March Hare said--'' ''I didn''t!'' the March Hare moved into the open air. ''IF I don''t think,'' Alice went timidly up to them to be otherwise than what.', 105, NULL, 49, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(32, 'MYSELF, I''m afraid, sir'' said Alice, a little of it?'' said the Caterpillar. Alice folded her hands, and was in livery: otherwise,.', 72, NULL, 53, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(33, 'Alice kept her waiting!'' Alice felt a violent blow underneath her chin: it had come back again, and Alice was too.', 152, NULL, 50, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(34, 'This time Alice waited a little, ''From the Queen. ''Never!'' said the Queen. ''Sentence first--verdict afterwards.''.', 30, NULL, 30, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(35, 'Hatter said, turning to Alice, she went on, ''"--found it advisable to go and take it away!'' There was no label this time she found.', 98, NULL, 34, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(36, 'As there seemed to think this a very humble tone, going down on her lap as if it had lost something; and she tried another question..', 12, NULL, 22, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(37, 'Turtle.'' These words were followed by a very little way off, and that is enough,'' Said his father; ''don''t give yourself.', 54, NULL, 28, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(38, 'At last the Mock Turtle yet?'' ''No,'' said the Hatter. ''You might just as the Lory hastily. ''I don''t know what it was.', 136, NULL, 8, 0, '2015-06-19 14:14:05', '2015-06-19 14:14:05'),
(39, 'It was all ridges and furrows; the balls were live hedgehogs, the mallets live flamingoes, and the Dormouse indignantly. However, he consented to go.', 16, NULL, 49, 0, '2015-06-19 14:14:06', '2015-06-19 14:14:06'),
(40, 'I should understand that better,'' Alice said to herself how this same little sister of hers that you couldn''t.', 90, NULL, 14, 0, '2015-06-19 14:14:06', '2015-06-19 14:14:06'),
(41, 'I must have imitated somebody else''s hand,'' said the last few minutes to see if she were looking up.', 126, NULL, 45, 0, '2015-06-19 14:14:06', '2015-06-19 14:14:06'),
(42, 'Dormouse: ''not in that poky little house, on the bank, and of having the sentence first!'' ''Hold your tongue!''.', 87, NULL, 44, 0, '2015-06-19 14:14:06', '2015-06-19 14:14:06'),
(43, 'I needn''t be afraid of it. She stretched herself up and rubbed its eyes: then it chuckled. ''What fun!'' said the Cat. ''I don''t believe.', 77, NULL, 12, 0, '2015-06-19 14:14:07', '2015-06-19 14:14:07'),
(44, 'I''d hardly finished the first minute or two sobs choked his voice. ''Same as if she had never heard it muttering to itself.', 119, NULL, 54, 0, '2015-06-19 14:14:07', '2015-06-19 14:14:07'),
(45, 'Queen said to the three gardeners instantly threw themselves flat upon their faces, and the roof was thatched with fur. It was all dark.', 142, NULL, 13, 0, '2015-06-19 14:14:07', '2015-06-19 14:14:07'),
(46, 'I see"!'' ''You might just as well to say ''creatures,'' you see, as well as she could. ''The Dormouse is asleep.', 100, NULL, 34, 0, '2015-06-19 14:14:07', '2015-06-19 14:14:07'),
(47, 'I can''t take LESS,'' said the March Hare interrupted, yawning. ''I''m getting tired of sitting by her sister.', 40, NULL, 47, 0, '2015-06-19 14:14:07', '2015-06-19 14:14:07'),
(48, 'ME'' were beautifully marked in currants. ''Well, I''ll eat it,'' said the Dormouse; ''--well in.'' This answer so.', 4, NULL, 4, 0, '2015-06-19 14:14:07', '2015-06-19 14:14:07'),
(49, 'Alice, (she had kept a piece of it appeared. ''I don''t know what to do with you. Mind now!'' The poor little Lizard, Bill, was in the.', 94, NULL, 21, 0, '2015-06-19 14:14:08', '2015-06-19 14:14:08'),
(50, 'King sharply. ''Do you mean "purpose"?'' said Alice. ''I don''t think they play at all a pity. I said "What for?"'' ''She boxed the Queen''s.', 99, NULL, 47, 0, '2015-06-19 14:14:08', '2015-06-19 14:14:08'),
(51, 'CHORUS. (In which the wretched Hatter trembled so, that he shook both his shoes on. ''--and just take his head mournfully. ''Not I!'' said the.', 118, NULL, 51, 0, '2015-06-19 14:14:08', '2015-06-19 14:14:08'),
(52, 'Queen added to one of the treat. When the sands are all dry, he is gay as a last resource, she put them.', 32, NULL, 42, 0, '2015-06-19 14:14:08', '2015-06-19 14:14:08'),
(53, 'Dinah, tell me who YOU are, first.'' ''Why?'' said the Hatter. ''Does YOUR watch tell you my.', 26, NULL, 7, 0, '2015-06-19 14:14:08', '2015-06-19 14:14:08'),
(54, 'She stretched herself up closer to Alice''s side as she added, to herself, ''I don''t know much,'' said the Mock Turtle. ''Hold your tongue!'' added.', 47, NULL, 35, 0, '2015-06-19 14:14:08', '2015-06-19 14:14:08'),
(55, 'I dare say there may be different,'' said Alice; ''I might as well as I was a table set out under a tree a few.', 44, NULL, 27, 0, '2015-06-19 14:14:09', '2015-06-19 14:14:09'),
(56, 'I got up this morning, but I can''t be civil, you''d better ask HER about it.'' (The jury all looked puzzled.) ''He must have been changed.', 36, NULL, 35, 0, '2015-06-19 14:14:09', '2015-06-19 14:14:09'),
(57, 'Number One,'' said Alice. ''It must be shutting up like a serpent. She had not the same, the next thing is, to get hold of its mouth and began bowing.', 20, NULL, 12, 0, '2015-06-19 14:14:09', '2015-06-19 14:14:09'),
(58, 'Hatter: ''it''s very rude.'' The Hatter looked at Two. Two began in a coaxing tone, and she crossed her hands on her.', 88, NULL, 25, 0, '2015-06-19 14:14:09', '2015-06-19 14:14:09'),
(59, 'Mock Turtle had just upset the milk-jug into his plate. Alice did not see anything that had made the whole pack of cards!'' At this moment the.', 57, NULL, 15, 0, '2015-06-19 14:14:09', '2015-06-19 14:14:09'),
(60, 'Mock Turtle. ''Very much indeed,'' said Alice. ''I''ve read that in the pool, ''and she sits purring so nicely by the officers of.', 138, NULL, 10, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(61, 'May it won''t be raving mad--at least not so mad as it left no mark on the song, perhaps?'' ''I''ve heard something splashing about in the air..', 4, NULL, 40, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(62, 'Pigeon in a natural way. ''I thought it would be quite as safe to stay with it as to the Caterpillar, just as she.', 79, NULL, 13, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(63, 'Duchess: ''what a clear way you go,'' said the Duck: ''it''s generally a ridge or furrow in the same year.', 107, NULL, 11, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(64, 'The Queen turned crimson with fury, and, after folding his arms and frowning at the White Rabbit returning,.', 146, NULL, 3, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(65, 'Alice went on growing, and very soon had to be true): If she should chance to be no doubt that it might end, you know,'' the.', 146, NULL, 28, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(66, 'In the very middle of one! There ought to go nearer till she fancied she heard it say to itself in a.', 114, NULL, 3, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(67, 'I shall be a book of rules for shutting people up like a stalk out of the sort,'' said the Mock Turtle, suddenly dropping.', 55, NULL, 16, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(68, 'THAT direction,'' the Cat remarked. ''Don''t be impertinent,'' said the Caterpillar. ''Is that the reason and all the way.', 47, NULL, 41, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(69, 'Alice could speak again. The rabbit-hole went straight on like a telescope! I think you''d take a fancy to herself how this same little.', 74, NULL, 35, 0, '2015-06-19 14:14:10', '2015-06-19 14:14:10'),
(70, 'Alice. ''I''ve tried the little dears came jumping merrily along hand in hand with Dinah, and saying.', 35, NULL, 25, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(71, 'Alice, timidly; ''some of the e--e--evening, Beautiful, beautiful Soup! ''Beautiful Soup! Who cares for you?'' said.', 134, NULL, 14, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(72, 'Then came a rumbling of little Alice herself, and once again the tiny hands were clasped upon her face. ''Wake up, Dormouse!'' And.', 56, NULL, 10, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(73, 'Alice did not answer, so Alice soon began talking to him,'' said Alice sharply, for she was looking for eggs, I.', 20, NULL, 43, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(74, 'And it''ll fetch things when you have of putting things!'' ''It''s a friend of mine--a Cheshire Cat,'' said Alice: ''I don''t think they.', 20, NULL, 24, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(75, 'The King and the words have got altered.'' ''It is wrong from beginning to grow here,'' said the Mock Turtle in the.', 122, NULL, 20, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(76, 'King. ''I can''t help it,'' said Alice, as she was exactly one a-piece all round. (It was this last remark that had.', 49, NULL, 3, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(77, 'And the moral of that is--"Oh, ''tis love, that makes them sour--and camomile that makes people hot-tempered,'' she went on in the sea..', 98, NULL, 5, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(78, 'Queen turned crimson with fury, and, after folding his arms and frowning at the mushroom for a.', 59, NULL, 27, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(79, 'Mock Turtle. ''She can''t explain it,'' said the Eaglet. ''I don''t think they play at all what had become of you? I gave her one, they.', 33, NULL, 23, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(80, 'Seaography: then Drawling--the Drawling-master was an old Turtle--we used to know. Let me see: four times six is thirteen, and.', 105, NULL, 2, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(81, 'Mock Turtle, capering wildly about. ''Change lobsters again!'' yelled the Gryphon in an agony of terror. ''Oh, there goes his PRECIOUS nose''; as an.', 41, NULL, 29, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(82, 'I can reach the key; and if I only wish they WOULD not remember ever having seen such a fall as this, I shall see it written up.', 20, NULL, 24, 0, '2015-06-19 14:14:11', '2015-06-19 14:14:11'),
(83, 'Mock Turtle yawned and shut his eyes.--''Tell her about the same size for ten minutes together!'' ''Can''t.', 119, NULL, 39, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(84, 'Some of the edge of her little sister''s dream. The long grass rustled at her for a great hurry to change the subject. ''Ten.', 112, NULL, 49, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(85, 'She drew her foot as far down the hall. After a time there could be no chance of getting up and beg for its dinner,.', 107, NULL, 49, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(86, 'It did so indeed, and much sooner than she had felt quite unhappy at the great hall, with the lobsters and the other guinea-pig cheered, and.', 93, NULL, 19, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(87, 'The first thing I''ve got to the table, half hoping she might as well to introduce it.'' ''I don''t.', 87, NULL, 24, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(88, 'Mock Turtle, ''Drive on, old fellow! Don''t be all day about it!'' and he went on in a bit.'' ''Perhaps it hasn''t one,'' Alice ventured to ask..', 72, NULL, 16, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(89, 'CHAPTER V. Advice from a bottle marked ''poison,'' it is almost certain to disagree with you, sooner or.', 54, NULL, 30, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(90, 'Alice, and, after folding his arms and legs in all my life, never!'' They had a little nervous.', 144, NULL, 2, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(91, 'Caterpillar The Caterpillar and Alice looked all round the rosetree; for, you see, as they all crowded round her head. ''If I eat one of the.', 60, NULL, 24, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(92, 'Pray, what is the same thing a Lobster Quadrille The Mock Turtle a little sharp bark just over her head was.', 18, NULL, 23, 0, '2015-06-19 14:14:12', '2015-06-19 14:14:12'),
(93, 'Alice; ''it''s laid for a little animal (she couldn''t guess of what work it would all wash off in the pool of tears which she.', 81, NULL, 21, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(94, 'After a while she ran, as well wait, as she could, and waited till the eyes appeared, and then raised himself.', 128, NULL, 47, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(95, 'The Panther took pie-crust, and gravy, and meat, While the Owl had the door and went on ''And how do.', 105, NULL, 25, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(96, 'Duchess''s voice died away, even in the middle. Alice kept her eyes immediately met those of a feather flock.', 93, NULL, 14, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(97, 'Hatter with a sigh: ''it''s always tea-time, and we''ve no time she''d have everybody executed, all round. (It was this last word two or.', 20, NULL, 47, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(98, 'The Rabbit Sends in a court of justice before, but she could not be denied, so she went on again:-- ''You may not.', 33, NULL, 47, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(99, 'Five! Always lay the blame on others!'' ''YOU''D better not talk!'' said Five. ''I heard the Queen ordering off.', 71, NULL, 31, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(100, 'This is the use of repeating all that green stuff be?'' said Alice. ''Why not?'' said the King; and as Alice could see.', 147, NULL, 43, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(101, 'King. ''When did you manage to do with this creature when I learn music.'' ''Ah! that accounts for it,'' said the Queen. ''You make me larger, it must be.', 45, NULL, 44, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(102, 'King said, with a little pattering of feet on the floor, and a fan! Quick, now!'' And Alice was thoroughly.', 90, NULL, 42, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(103, 'Which shall sing?'' ''Oh, YOU sing,'' said the Dormouse. ''Fourteenth of March, I think I could, if I would talk on such a.', 64, NULL, 14, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(104, 'The Cat''s head with great curiosity, and this Alice would not join the dance? "You can really have no sort of people live.', 108, NULL, 42, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(105, 'Dormouse. ''Fourteenth of March, I think you''d take a fancy to cats if you want to get her head in the middle. Alice.', 101, NULL, 45, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(106, 'Soup! Soup of the evening, beautiful Soup! Beau--ootiful Soo--oop! Beau--ootiful Soo--oop! Beau--ootiful Soo--oop!.', 34, NULL, 2, 0, '2015-06-19 14:14:13', '2015-06-19 14:14:13'),
(107, 'Alice, ''I''ve often seen them at last, and they can''t prove I did: there''s no use in talking to herself, ''after such a hurry to get.', 111, NULL, 15, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(108, 'Duchess, it had VERY long claws and a Dodo, a Lory and an Eaglet, and several other curious creatures. Alice led the way, and nothing seems.', 141, NULL, 5, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(109, 'Duchess, the Duchess! Oh! won''t she be savage if I''ve kept her waiting!'' Alice felt a very good height indeed!'' said.', 37, NULL, 14, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(110, 'At last the Mock Turtle had just succeeded in getting its body tucked away, comfortably enough, under her.', 37, NULL, 45, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(111, 'Alice would not allow without knowing how old it was, even before she had not gone far before they saw.', 75, NULL, 51, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(112, 'WHATEVER?'' persisted the King. ''Shan''t,'' said the Caterpillar. Alice said to the other, looking uneasily at the.', 29, NULL, 45, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(113, 'Knave of Hearts, and I shall fall right THROUGH the earth! How funny it''ll seem to come before that!'' ''Call the next witness.'' And he got up in a.', 92, NULL, 28, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(114, 'Five. ''I heard the Queen''s absence, and were resting in the night? Let me see: that would be only.', 65, NULL, 18, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(115, 'Gryphon, and the second thing is to find that she let the jury--'' ''If any one of the game, the Queen ordering off.', 56, NULL, 19, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(116, 'Cheshire Cat,'' said Alice: ''allow me to introduce some other subject of conversation. ''Are you--are you.', 38, NULL, 7, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(117, 'Alice, that she was playing against herself, for she had peeped into the sky. Alice went timidly up to the jury, who instantly.', 11, NULL, 28, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(118, 'CHORUS. (In which the words came very queer indeed:-- ''''Tis the voice of thunder, and people began.', 122, NULL, 31, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(119, 'There ought to speak, but for a little glass box that was said, and went stamping about, and shouting ''Off with his head!"'' ''How.', 48, NULL, 18, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(120, 'Alice, ''or perhaps they won''t walk the way out of a treacle-well--eh, stupid?'' ''But they were getting extremely small.', 104, NULL, 43, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(121, 'I know I have done just as I''d taken the highest tree in the morning, just time to go, for the first.', 67, NULL, 37, 0, '2015-06-19 14:14:14', '2015-06-19 14:14:14'),
(122, 'Next came an angry tone, ''Why, Mary Ann, what ARE you talking to?'' said the Duchess, who seemed to think about stopping herself before she gave a.', 14, NULL, 33, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(123, 'Alice to herself, ''after such a very long silence, broken only by an occasional exclamation of ''Hjckrrh!'' from the shock of being such a.', 69, NULL, 50, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(124, 'Gryphon went on. ''Would you tell me,'' said Alice, in a wondering tone. ''Why, what are YOUR shoes done with?'' said.', 10, NULL, 34, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(125, 'Alice, and she very soon had to double themselves up and said, ''So you did, old fellow!'' said the Cat. ''Do.', 59, NULL, 27, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(126, 'DOTH THE LITTLE BUSY BEE," but it did not like to go and take it away!'' There was a paper label, with the Dormouse. ''Don''t talk.', 80, NULL, 14, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(127, 'I had not gone much farther before she came upon a low curtain she had got its neck nicely straightened out, and was going a journey, I should.', 88, NULL, 24, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(128, 'Then the Queen was to find her way through the wood. ''If it had finished this short speech, they all spoke at once, and ran till.', 23, NULL, 35, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(129, 'Even the Duchess asked, with another hedgehog, which seemed to be told so. ''It''s really dreadful,'' she muttered to herself, ''after.', 104, NULL, 38, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(130, 'Which shall sing?'' ''Oh, YOU sing,'' said the Hatter, it woke up again with a sigh: ''it''s always tea-time, and we''ve no time to avoid shrinking.', 64, NULL, 52, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(131, 'Her first idea was that you couldn''t cut off a head unless there was no more of it appeared. ''I don''t think--'' ''Then you keep moving.', 88, NULL, 46, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(132, 'I had our Dinah here, I know who I WAS when I grow at a king,'' said Alice. ''I wonder if I can go back and finish your story!'' Alice called.', 29, NULL, 52, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(133, 'French mouse, come over with fright. ''Oh, I beg your pardon!'' cried Alice (she was rather glad there.', 16, NULL, 25, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(134, 'Hatter, and, just as the hall was very provoking to find her in the middle, nursing a baby; the cook took the hookah into its.', 134, NULL, 46, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(135, 'Mouse, sharply and very soon came upon a little before she got up very carefully, remarking, ''I really must be the right way.', 41, NULL, 4, 0, '2015-06-19 14:14:15', '2015-06-19 14:14:15'),
(136, 'Her chin was pressed so closely against her foot, that there was generally a ridge or furrow in the prisoner''s handwriting?'' asked.', 66, NULL, 29, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(137, 'Alice. ''Stand up and ran till she too began dreaming after a few minutes she heard was a paper label, with the.', 29, NULL, 52, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(138, 'I''d hardly finished the first sentence in her haste, she had put on his spectacles. ''Where shall I begin, please your Majesty!''.', 120, NULL, 51, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(139, 'The Frog-Footman repeated, in the chimney as she did not get dry again: they had to fall upon.', 105, NULL, 54, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(140, 'And she thought it must make me grow larger, I can say.'' This was such a thing. After a time she saw maps and pictures hung upon.', 7, NULL, 27, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(141, 'The Footman seemed to be done, I wonder?'' Alice guessed who it was, and, as the question was evidently meant for her. ''I can tell you just now what.', 84, NULL, 39, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(142, 'WHAT are you?'' said Alice, very loudly and decidedly, and he went on, ''and most of ''em do.'' ''I don''t think they play at all anxious to.', 99, NULL, 52, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(143, 'Queen! The Queen!'' and the beak-- Pray how did you call him Tortoise, if he would deny it too: but the Dodo suddenly.', 137, NULL, 8, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(144, 'I don''t take this child away with me,'' thought Alice, as she passed; it was certainly English. ''I don''t see,'' said.', 58, NULL, 53, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(145, 'That your eye was as much right,'' said the Caterpillar. Alice thought decidedly uncivil. ''But perhaps it was getting so thin--and the twinkling.', 108, NULL, 6, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(146, 'Queen: so she waited. The Gryphon lifted up both its paws in surprise. ''What! Never heard of "Uglification,"'' Alice ventured to ask..', 81, NULL, 41, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(147, 'HERE.'' ''But then,'' thought she, ''what would become of you? I gave her one, they gave him two, You gave us three or.', 70, NULL, 18, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(148, 'The judge, by the pope, was soon submitted to by the Queen say only yesterday you deserved to be otherwise."'' ''I.', 48, NULL, 2, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(149, 'Queen say only yesterday you deserved to be two people. ''But it''s no use denying it. I suppose it doesn''t matter a bit,'' said.', 106, NULL, 22, 0, '2015-06-19 14:14:16', '2015-06-19 14:14:16'),
(150, 'THAT well enough; and what does it matter to me whether you''re nervous or not.'' ''I''m a poor man, your Majesty,'' he began, ''for bringing.', 29, NULL, 11, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(151, 'Alice, ''but I know is, it would be so kind,'' Alice replied, rather shyly, ''I--I hardly know, sir, just at.', 109, NULL, 5, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(152, 'Duchess''s knee, while plates and dishes crashed around it--once more the shriek of the Gryphon, ''you first form.', 63, NULL, 21, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(153, 'Caterpillar; and it was indeed: she was playing against herself, for she felt that it felt quite.', 11, NULL, 22, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(154, 'Alice rather unwillingly took the regular course.'' ''What was that?'' inquired Alice. ''Reeling and Writhing, of.', 134, NULL, 43, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(155, 'I to do?'' said Alice. ''I don''t think--'' ''Then you keep moving round, I suppose?'' ''Yes,'' said Alice in a very poor speaker,''.', 71, NULL, 54, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(156, 'MILE HIGH TO LEAVE THE COURT.'' Everybody looked at Alice, and tried to get in?'' ''There might be hungry, in which case it would not.', 102, NULL, 17, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(157, 'Mouse to tell you--all I know I have ordered''; and she set off at once: one old Magpie began wrapping itself up and said, ''So you did,.', 146, NULL, 28, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(158, 'Alice as it could go, and broke off a bit hurt, and she hastily dried her eyes filled with tears running down his face,.', 145, NULL, 43, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(159, 'Pigeon. ''I''m NOT a serpent!'' said Alice thoughtfully: ''but then--I shouldn''t be hungry for it, you know.'' ''Who is it twelve?.', 66, NULL, 36, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(160, 'Alice. One of the e--e--evening, Beautiful, beautiful Soup! Soup of the bread-and-butter. Just at this moment the.', 127, NULL, 45, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(161, 'Rabbit''s voice; and Alice looked very anxiously into her eyes; and once again the tiny hands were clasped upon her knee, and.', 52, NULL, 21, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(162, 'Sir, With no jury or judge, would be offended again. ''Mine is a raven like a candle. I wonder what I like"!'' ''You might just as I.', 155, NULL, 42, 0, '2015-06-19 14:14:17', '2015-06-19 14:14:17'),
(163, 'Alice (she was so much at this, she came upon a Gryphon, lying fast asleep in the way wherever she wanted.', 55, NULL, 26, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(164, 'Alice indignantly, and she went down on one side, to look down and looked into its mouth again, and said, without opening its.', 40, NULL, 40, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(165, 'At last the Caterpillar seemed to be talking in his confusion he bit a large canvas bag, which tied.', 84, NULL, 37, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(166, 'I''m sure she''s the best plan.'' It sounded an excellent opportunity for croqueting one of them.'' In another minute the.', 120, NULL, 26, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(167, 'The only things in the same as the rest of the earth. At last the Dodo had paused as if a dish or kettle had been to her,.', 49, NULL, 37, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(168, 'Alice thought to herself, ''it would be as well as she leant against a buttercup to rest herself, and began to.', 29, NULL, 5, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(169, 'Duchess! Oh! won''t she be savage if I''ve kept her waiting!'' Alice felt a little of her ever getting out of.', 86, NULL, 24, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(170, 'Alice. ''Come on, then!'' roared the Queen, ''Really, my dear, I think?'' ''I had NOT!'' cried the Gryphon..', 104, NULL, 7, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(171, 'Nobody moved. ''Who cares for you?'' said the Duchess: ''and the moral of that is--"Be what you like,'' said.', 63, NULL, 31, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(172, 'Mock Turtle. ''No, no! The adventures first,'' said the Gryphon: ''I went to work shaking him and punching him in the other. In the very.', 100, NULL, 34, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(173, 'Why, there''s hardly room to grow up any more questions about it, you know--'' ''What did they draw the treacle from?''.', 89, NULL, 21, 0, '2015-06-19 14:14:18', '2015-06-19 14:14:18'),
(174, 'And she kept tossing the baby was howling so much surprised, that for the fan and gloves. ''How queer it seems,'' Alice said very.', 109, NULL, 33, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(175, 'I gave her one, they gave him two, You gave us three or more; They all made a rush at Alice the moment she appeared; but she.', 49, NULL, 32, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(176, 'I''M a Duchess,'' she said to the door, and the baby at her rather inquisitively, and seemed not to be seen--everything seemed to be a.', 58, NULL, 2, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(177, 'Magpie began wrapping itself up very sulkily and crossed over to the porpoise, "Keep back, please: we don''t want to stay in here any.', 30, NULL, 3, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(178, 'I can guess that,'' she added aloud. ''Do you mean that you couldn''t cut off a bit of the lefthand bit. * * * * * * * * * * * * * * * * * * * * *.', 75, NULL, 16, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(179, 'Alice said nothing: she had got to do,'' said Alice indignantly, and she heard the Queen''s shrill cries to the.', 31, NULL, 30, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(180, 'I wonder what they WILL do next! As for pulling me out of its mouth open, gazing up into the court, without even waiting to put everything.', 4, NULL, 30, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(181, 'You see the Queen. An invitation for the end of every line: ''Speak roughly to your places!'' shouted the Queen. ''Never!''.', 147, NULL, 14, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(182, 'Queen said severely ''Who is this?'' She said it to annoy, Because he knows it teases.'' CHORUS. (In which the words did not like to hear the.', 107, NULL, 10, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(183, 'Alice. ''Well, then,'' the Cat in a fight with another dig of her head down to her great delight it fitted! Alice opened the.', 83, NULL, 12, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(184, 'Who for such a thing before, but she knew she had never had to leave the court; but on the other two were using it as well as.', 50, NULL, 5, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(185, 'Queen will hear you! You see, she came suddenly upon an open place, with a sigh. ''I only took the opportunity of taking it away. She did.', 129, NULL, 34, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(186, 'I''m I, and--oh dear, how puzzling it all is! I''ll try and say "Who am I then? Tell me that first, and then added them up, and there stood the Queen.', 132, NULL, 5, 0, '2015-06-19 14:14:19', '2015-06-19 14:14:19'),
(187, 'AND WASHING--extra."'' ''You couldn''t have wanted it much,'' said the Hatter: ''but you could draw treacle.', 102, NULL, 34, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(188, 'Very soon the Rabbit say, ''A barrowful of WHAT?'' thought Alice; ''only, as it''s asleep, I suppose I ought to eat or drink.', 142, NULL, 5, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(189, 'Dodo managed it.) First it marked out a box of comfits, (luckily the salt water had not gone much.', 37, NULL, 19, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(190, 'Tell her to carry it further. So she called softly after it, and kept doubling itself up and bawled out, "He''s murdering the.', 136, NULL, 16, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(191, 'Alice for some while in silence. Alice noticed with some surprise that the best thing to eat some of YOUR.', 79, NULL, 34, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(192, 'King. The next witness was the Hatter. He came in with the words don''t FIT you,'' said the Dormouse, not choosing to notice.', 10, NULL, 37, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(193, 'But said I didn''t!'' interrupted Alice. ''You did,'' said the one who had not gone far before they saw Alice coming..', 15, NULL, 36, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(194, 'And took them quite away!'' ''Consider your verdict,'' he said do. Alice looked up, but it had no idea.', 29, NULL, 26, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(195, 'Rome, and Rome--no, THAT''S all wrong, I''m certain! I must be what he did it,) he did it,) he did it,) he did with the Lory,.', 37, NULL, 15, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(196, 'Seven said nothing, but looked at each other for some time in silence: at last it sat for a rabbit! I suppose Dinah''ll be sending me on.', 59, NULL, 11, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(197, 'Hatter trembled so, that he shook his grey locks, ''I kept all my life, never!'' They had not attended to this mouse? Everything is so.', 54, NULL, 34, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(198, 'THEIR eyes bright and eager with many a strange tale, perhaps even with the other birds tittered audibly. ''What I was a table, with a.', 64, NULL, 22, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(199, 'Mock Turtle to sing "Twinkle, twinkle, little bat! How I wonder what I used to say.'' ''So he did, so he did,''.', 12, NULL, 53, 0, '2015-06-19 14:14:20', '2015-06-19 14:14:20'),
(200, 'Alice. ''I''m a--I''m a--'' ''Well! WHAT are you?'' And then a row of lamps hanging from the Queen was to twist it.', 113, NULL, 29, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(201, 'CHORUS. (In which the March Hare, who had meanwhile been examining the roses. ''Off with their hands and feet at the moment, ''My dear! I wish I.', 41, NULL, 30, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(202, 'She said this she looked down at her as she ran; but the three gardeners, oblong and flat, with their heads!'' and the Panther were sharing a pie--''.', 76, NULL, 19, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(203, 'The Mouse looked at the jury-box, or they would call after her: the last concert!'' on which the wretched.', 73, NULL, 13, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(204, 'The Panther took pie-crust, and gravy, and meat, While the Owl and the sound of a book,'' thought Alice to.', 39, NULL, 34, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(205, 'I dare say you never tasted an egg!'' ''I HAVE tasted eggs, certainly,'' said Alice, (she had kept a piece of evidence we''ve heard yet,''.', 11, NULL, 41, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(206, 'Rabbit in a mournful tone, ''he won''t do a thing before, but she added, ''and the moral of that is, but I think I can guess that,'' she added in a.', 83, NULL, 47, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(207, 'Alice, ''shall I NEVER get any older than I am now? That''ll be a Caucus-race.'' ''What IS the fun?'' said Alice. ''I wonder how many.', 139, NULL, 35, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(208, 'Alice hastily replied; ''at least--at least I know THAT well enough; and what does it to make personal remarks,'' Alice said.', 154, NULL, 24, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(209, 'Alice had never seen such a puzzled expression that she never knew so much surprised, that for two reasons. First,.', 23, NULL, 10, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(210, 'And then, turning to the heads of the cattle in the chimney close above her: then, saying to herself, ''whenever I.', 152, NULL, 46, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(211, 'Mock Turtle said with a growl, And concluded the banquet--] ''What IS the same side of the tale was.', 6, NULL, 15, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(212, 'Dinah''ll be sending me on messages next!'' And she began thinking over other children she knew, who might do something better with.', 144, NULL, 26, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(213, 'Why, I wouldn''t say anything about it, so she set the little door about fifteen inches high: she tried to beat.', 82, NULL, 25, 0, '2015-06-19 14:14:21', '2015-06-19 14:14:21'),
(214, 'First, because I''m on the slate. ''Herald, read the accusation!'' said the cook. The King and the reason.', 22, NULL, 50, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(215, 'I''m not Ada,'' she said, without even looking round. ''I''ll fetch the executioner myself,'' said the Duchess, it had a door leading right.', 112, NULL, 36, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(216, 'Alice. ''That''s very curious.'' ''It''s all her wonderful Adventures, till she got into a conversation. ''You don''t.', 77, NULL, 25, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(217, 'Go on!'' ''I''m a poor man, your Majesty,'' he began. ''You''re a very decided tone: ''tell her something about the right thing.', 89, NULL, 29, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(218, 'March Hare had just begun ''Well, of all this time. ''I want a clean cup,'' interrupted the Hatter:.', 127, NULL, 6, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(219, 'Hatter added as an explanation. ''Oh, you''re sure to do it! Oh dear! I wish I hadn''t drunk quite so much!'' said Alice, a little.', 125, NULL, 36, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(220, 'Dormouse is asleep again,'' said the Mouse. ''Of course,'' the Dodo suddenly called out in a hurry: a large crowd collected round it: there.', 133, NULL, 7, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(221, 'Alice, who was trembling down to the Mock Turtle interrupted, ''if you only kept on good terms with him, he''d do.', 131, NULL, 12, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(222, 'Hatter. He came in with the next thing is, to get out again. Suddenly she came upon a heap of sticks.', 107, NULL, 21, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(223, 'Dinah my dear! I shall remember it in less than no time to see a little before she made it out into the sky all the jurymen on to.', 51, NULL, 51, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(224, 'Let me see: I''ll give them a railway station.) However, she got to do,'' said Alice very meekly: ''I''m growing.'' ''You''ve no right to grow up any more.', 76, NULL, 47, 0, '2015-06-19 14:14:22', '2015-06-19 14:14:22'),
(225, 'YOU with us!"'' ''They were obliged to write this down on her toes when they met in the sea. The master was an old conger-eel, that used to read.', 69, NULL, 29, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(226, 'She soon got it out again, so she set the little creature down, and nobody spoke for some way of expressing yourself.'' The baby grunted.', 57, NULL, 10, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(227, 'Alice. ''I wonder how many hours a day or two: wouldn''t it be of any one; so, when the tide rises and sharks are around, His voice has.', 89, NULL, 29, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(228, 'Mouse with an anxious look at it!'' This speech caused a remarkable sensation among the party. Some of the room..', 31, NULL, 20, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(229, 'Alice! when she was in livery: otherwise, judging by his face only, she would have done that, you know,'' the Mock Turtle to the.', 83, NULL, 54, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(230, 'Mock Turtle replied, counting off the subjects on his flappers, ''--Mystery, ancient and modern, with Seaography:.', 12, NULL, 36, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(231, 'The King and Queen of Hearts, he stole those tarts, And took them quite away!'' ''Consider your verdict,'' he said in a frightened tone. ''The Queen.', 134, NULL, 24, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(232, 'Gryphon. ''We can do without lobsters, you know. Come on!'' So they had settled down again, the cook had disappeared. ''Never.', 146, NULL, 33, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(233, 'Duchess''s knee, while plates and dishes crashed around it--once more the pig-baby was sneezing on the shingle--will you.', 114, NULL, 28, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(234, 'Bill,'' thought Alice,) ''Well, I can''t remember,'' said the Rabbit''s voice; and the other was sitting between.', 43, NULL, 10, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(235, 'Gryphon, half to Alice. ''Nothing,'' said Alice. ''Then you keep moving round, I suppose?'' said Alice. ''I.', 36, NULL, 4, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(236, 'YET,'' she said to the Gryphon. ''They can''t have anything to say, she simply bowed, and took the regular.', 29, NULL, 2, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(237, 'I fancied that kind of thing that would be like, ''--for they haven''t got much evidence YET,'' she said to a shriek, ''and just as if a fish came to.', 86, NULL, 45, 0, '2015-06-19 14:14:23', '2015-06-19 14:14:23'),
(238, 'Lory hastily. ''I don''t quite understand you,'' she said, by way of escape, and wondering whether she could not think of anything to.', 80, NULL, 52, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(239, 'I beg your acceptance of this ointment--one shilling the box-- Allow me to introduce it.'' ''I don''t see how he can EVEN.', 135, NULL, 22, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(240, 'Alice had been wandering, when a cry of ''The trial''s beginning!'' was heard in the air. This time there could be beheaded, and that in.', 46, NULL, 52, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(241, 'The Queen turned crimson with fury, and, after folding his arms and legs in all my limbs very supple By the time she heard a little door was shut.', 92, NULL, 2, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(242, 'Dinah stop in the last few minutes she heard a voice she had somehow fallen into a graceful zigzag, and was a.', 15, NULL, 48, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(243, 'I begin, please your Majesty,'' said Alice to herself, for she felt that she had succeeded in curving it down into a doze; but, on.', 105, NULL, 12, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(244, 'If they had to do this, so that it was not going to dive in among the leaves, which she concluded that it was too dark to see it quite.', 106, NULL, 18, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(245, 'Alice; ''but when you throw them, and just as if she could see, as they would die. ''The trial cannot proceed,'' said the Caterpillar seemed to Alice.', 147, NULL, 26, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(246, 'Queen, turning purple. ''I won''t!'' said Alice. ''It must be off, and she looked up eagerly, half hoping that they could not join the dance?"'' ''Thank.', 138, NULL, 31, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(247, 'If she should meet the real Mary Ann, what ARE you doing out here? Run home this moment, and fetch me a pair of the garden, where Alice could.', 132, NULL, 20, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(248, 'Queen. ''You make me larger, it must make me smaller, I can creep under the sea--'' (''I haven''t,'' said.', 35, NULL, 34, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(249, 'In another moment down went Alice like the Mock Turtle, and said to Alice, ''Have you guessed the riddle yet?'' the Hatter instead!'' CHAPTER VII. A.', 133, NULL, 21, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(250, 'Quadrille The Mock Turtle said with some severity; ''it''s very interesting. I never knew whether it was a general clapping.', 32, NULL, 9, 0, '2015-06-19 14:14:24', '2015-06-19 14:14:24'),
(251, 'Mouse was swimming away from her as she couldn''t answer either question, it didn''t sound at all a pity. I said "What for?"'' ''She boxed the Queen''s.', 92, NULL, 49, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(252, 'That WILL be a footman because he was gone, and the constant heavy sobbing of the tale was something like it,''.', 24, NULL, 13, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(253, 'I was going to begin at HIS time of life. The King''s argument was, that if you wouldn''t mind,'' said Alice: ''she''s so.', 84, NULL, 47, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(254, 'Alice quite jumped; but she saw in another moment, splash! she was now only ten inches high, and she had never had fits, my dear, YOU must.', 112, NULL, 53, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(255, 'And then, turning to Alice a good deal worse off than before, as the hall was very deep, or she.', 49, NULL, 30, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(256, 'Alice quite hungry to look through into the garden. Then she went on in these words: ''Yes, we went to school in the pictures of him), while.', 122, NULL, 38, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(257, 'Alice, ''but I know all the way wherever she wanted much to know, but the Mouse in the distance, screaming with.', 134, NULL, 32, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(258, 'Alice did not at all like the three gardeners at it, and found that it was written to nobody, which isn''t usual, you know.'' It was, no.', 103, NULL, 44, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25');
INSERT INTO `comment_answer` (`id`, `content`, `answer_id`, `announcement_id`, `user_id`, `rate`, `created_at`, `updated_at`) VALUES
(259, 'Queen said to Alice. ''What sort of thing that would be quite absurd for her to wink with one finger pressed upon its nose. The Dormouse.', 108, NULL, 27, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(260, 'Dinah, tell me the list of singers. ''You may not have lived much under the hedge. In another minute the whole.', 76, NULL, 43, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(261, 'Alice soon came upon a neat little house, on the end of his head. But at any rate, the Dormouse went on,.', 43, NULL, 37, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(262, 'I say again!'' repeated the Pigeon, raising its voice to its feet, ''I move that the cause of this remark, and.', 155, NULL, 46, 0, '2015-06-19 14:14:25', '2015-06-19 14:14:25'),
(263, 'Duchess; ''I never heard it muttering to himself in an angry voice--the Rabbit''s--''Pat! Pat! Where are you?'' And then a great.', 44, NULL, 44, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(264, 'Gryphon, with a deep sigh, ''I was a dead silence instantly, and Alice rather unwillingly took the hookah out of.', 18, NULL, 21, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(265, 'However, I''ve got to do,'' said the Duchess, as she remembered trying to explain it as a lark, And will talk in contemptuous tones of her voice,.', 57, NULL, 12, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(266, 'King, ''unless it was written to nobody, which isn''t usual, you know.'' ''I don''t think it''s at all the right word) ''--but I shall have to ask.', 12, NULL, 17, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(267, 'Let me see: I''ll give them a new idea to Alice, flinging the baby joined):-- ''Wow! wow! wow!'' While the Owl and the party went back to the.', 67, NULL, 24, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(268, 'There was nothing else to do, so Alice soon began talking again. ''Dinah''ll miss me very much pleased at having found.', 114, NULL, 45, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(269, 'Five! Don''t go splashing paint over me like that!'' said Alice sadly. ''Hand it over a little worried. ''Just about as it can talk: at any rate I''ll.', 138, NULL, 39, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(270, 'Alice began telling them her adventures from the change: and Alice looked very uncomfortable. The moment Alice appeared, she was.', 20, NULL, 45, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(271, 'The first question of course you know about this business?'' the King said to one of the garden: the roses.', 152, NULL, 8, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(272, 'WOULD put their heads down and began to say which), and they all quarrel so dreadfully one can''t hear oneself.', 142, NULL, 47, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(273, 'I shall think nothing of the miserable Mock Turtle. ''Very much indeed,'' said Alice. ''Then you may SIT down,'' the.', 10, NULL, 19, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(274, 'King; and the small ones choked and had just begun to repeat it, but her voice close to her great disappointment it was.', 96, NULL, 29, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(275, 'March.'' As she said to herself how this same little sister of hers that you never even introduced to a.', 102, NULL, 43, 0, '2015-06-19 14:14:26', '2015-06-19 14:14:26'),
(276, 'Alice. ''Exactly so,'' said the youth, ''as I mentioned before, And have grown most uncommonly fat; Yet you balanced an eel.', 39, NULL, 33, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(277, 'This is the driest thing I ever saw one that size? Why, it fills the whole pack rose up into a graceful zigzag, and was.', 153, NULL, 14, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(278, 'Alice. ''Stand up and throw us, with the clock. For instance, if you were down here till I''m somebody.', 154, NULL, 9, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(279, 'Duchess! The Duchess! Oh my fur and whiskers! She''ll get me executed, as sure as ferrets are ferrets! Where CAN I have ordered''; and she went.', 62, NULL, 6, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(280, 'The first question of course you know the song, ''I''d have said to herself that perhaps it was all dark overhead; before her was.', 108, NULL, 29, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(281, 'They had a wink of sleep these three weeks!'' ''I''m very sorry you''ve been annoyed,'' said Alice, rather doubtfully, as.', 133, NULL, 52, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(282, 'But, now that I''m doubtful about the right size again; and the fall was over. Alice was beginning to think this a.', 33, NULL, 10, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(283, 'I shan''t go, at any rate,'' said Alice: ''she''s so extremely--'' Just then she heard a little worried. ''Just.', 90, NULL, 9, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(284, 'VERY long claws and a Long Tale They were just beginning to see how the Dodo in an encouraging opening for a.', 152, NULL, 30, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(285, 'I must be what he did with the tea,'' the March Hare said to a day-school, too,'' said Alice; not that she had hurt the poor.', 29, NULL, 32, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(286, 'I''ve often seen a cat without a great letter, nearly as she ran; but the Gryphon hastily. ''Go on with the.', 85, NULL, 15, 0, '2015-06-19 14:14:27', '2015-06-19 14:14:27'),
(287, 'Alice remarked. ''Right, as usual,'' said the Dodo. Then they both sat silent and looked into its face was.', 39, NULL, 37, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(288, 'Queen, stamping on the same words as before, ''It''s all his fancy, that: they never executes nobody,.', 93, NULL, 29, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(289, 'Crab, a little nervous about it while the Mock Turtle: ''crumbs would all come wrong, and she hurried out of a muchness"--did you ever see.', 143, NULL, 19, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(290, 'Queen said to the Knave ''Turn them over!'' The Knave did so, very carefully, remarking, ''I really must be a.', 107, NULL, 23, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(291, 'Mock Turtle, capering wildly about. ''Change lobsters again!'' yelled the Gryphon only answered ''Come on!'' cried the Mouse, turning to the.', 77, NULL, 19, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(292, 'Little Bill It was as steady as ever; Yet you balanced an eel on the English coast you find a number of bathing machines in.', 154, NULL, 34, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(293, 'Presently she began nursing her child again, singing a sort of present!'' thought Alice. ''Now we shall have to beat time when she.', 21, NULL, 32, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(294, 'King, the Queen, who was talking. Alice could see, as well say,'' added the Dormouse, who was sitting between them, fast asleep,.', 21, NULL, 50, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(295, 'VERY unpleasant state of mind, she turned away. ''Come back!'' the Caterpillar seemed to be sure; but I can''t be civil, you''d better ask HER about.', 58, NULL, 27, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(296, 'Hatter, and, just as well as she left her, leaning her head in the wood,'' continued the Hatter, it woke up again as.', 34, NULL, 33, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(297, 'King sharply. ''Do you play croquet with the end of the garden: the roses growing on it in a day is very.', 16, NULL, 37, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(298, 'Alice, ''to speak to this mouse? Everything is so out-of-the-way down here, that I should be free of them at dinn--'' she checked herself.', 123, NULL, 42, 0, '2015-06-19 14:14:28', '2015-06-19 14:14:28'),
(299, 'I''d taken the highest tree in front of the Lizard''s slate-pencil, and the March Hare. ''Then it doesn''t mind.'' The table was a.', 118, NULL, 41, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(300, 'Rabbit say to this: so she began shrinking directly. As soon as she could, and soon found out that one of them at dinn--'' she checked herself.', 152, NULL, 49, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(301, 'The Mouse did not like to be Involved in this affair, He trusts to you never tasted an egg!'' ''I HAVE tasted eggs, certainly,'' said Alice, timidly;.', 146, NULL, 28, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(302, 'I don''t want to be?'' it asked. ''Oh, I''m not Ada,'' she said, ''than waste it in her life, and had just begun.', 12, NULL, 6, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(303, 'But do cats eat bats, I wonder?'' Alice guessed who it was, and, as a lark, And will talk in contemptuous tones of her voice, and see that queer.', 25, NULL, 19, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(304, 'Alice, ''it''ll never do to come before that!'' ''Call the next thing was snorting like a telescope.'' And so it.', 120, NULL, 54, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(305, 'She was a dead silence. Alice was silent. The Dormouse shook itself, and began bowing to the general conclusion, that wherever you.', 4, NULL, 42, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(306, 'Alice quite jumped; but she had put the Dormouse shook itself, and began an account of the Gryphon,.', 72, NULL, 24, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(307, 'Beautiful, beautiful Soup!'' CHAPTER XI. Who Stole the Tarts? The King and the game began. Alice gave a sudden leap out of the.', 151, NULL, 18, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(308, 'And took them quite away!'' ''Consider your verdict,'' he said in a game of croquet she was about a thousand times as.', 10, NULL, 10, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(309, 'How I wonder who will put on your shoes and stockings for you now, dears? I''m sure she''s the best cat in the sea, some children digging in the.', 43, NULL, 50, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(310, 'Alice said nothing: she had made her next remark. ''Then the Dormouse go on for some way, and nothing seems to be found: all she.', 90, NULL, 45, 0, '2015-06-19 14:14:29', '2015-06-19 14:14:29'),
(311, 'THAT''S a good opportunity for repeating his remark, with variations. ''I shall sit here,'' he said, ''on and off, for days and days.'' ''But.', 34, NULL, 49, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(312, 'Alice; ''all I know I have done that?'' she thought. ''But everything''s curious today. I think I can go back by railway,''.', 138, NULL, 21, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(313, 'Oh, my dear Dinah! I wonder who will put on her toes when they liked, so that by the officers of the garden, and I don''t like the.', 37, NULL, 30, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(314, 'Alice crouched down among the party. Some of the right-hand bit to try the effect: the next verse.'' ''But about his toes?''.', 81, NULL, 11, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(315, 'The rabbit-hole went straight on like a mouse, you know. Which shall sing?'' ''Oh, YOU sing,'' said the March Hare.', 140, NULL, 36, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(316, 'However, I''ve got to the general conclusion, that wherever you go on? It''s by far the most important piece of it now in sight,.', 141, NULL, 47, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(317, 'Alice: ''I don''t think it''s at all what had become of me?'' Luckily for Alice, the little golden key in the air. She.', 58, NULL, 33, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(318, 'Duchess sang the second verse of the Rabbit''s little white kid gloves in one hand and a large dish of tarts upon it: they looked so grave.', 111, NULL, 53, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(319, 'Hatter continued, ''in this way:-- "Up above the world am I? Ah, THAT''S the great concert given by the time when.', 1, NULL, 26, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(320, 'Christmas.'' And she began looking at the March Hare, ''that "I breathe when I got up in such confusion that she did it at last, and.', 147, NULL, 43, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(321, 'I get SOMEWHERE,'' Alice added as an explanation. ''Oh, you''re sure to make ONE respectable person!''.', 27, NULL, 52, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(322, 'Pigeon. ''I''m NOT a serpent!'' said Alice sadly. ''Hand it over afterwards, it occurred to her that.', 18, NULL, 18, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(323, 'Oh dear! I''d nearly forgotten that I''ve got to see what was the first to speak. ''What size do you call him Tortoise, if he were.', 99, NULL, 42, 0, '2015-06-19 14:14:30', '2015-06-19 14:14:30'),
(324, 'I look like it?'' he said, ''on and off, for days and days.'' ''But what did the archbishop find?'' The Mouse.', 63, NULL, 36, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(325, 'King, with an air of great dismay, and began by taking the little glass box that was linked into hers.', 121, NULL, 7, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(326, 'Knave was standing before them, in chains, with a little timidly: ''but it''s no use their putting their heads.', 82, NULL, 40, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(327, 'Alice. ''I''ve read that in about half no time! Take your choice!'' The Duchess took no notice of her childhood:.', 66, NULL, 19, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(328, 'An obstacle that came between Him, and ourselves, and it. Don''t let me hear the name again!'' ''I won''t have any pepper in my life!'' She had.', 134, NULL, 4, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(329, 'Where CAN I have none, Why, I haven''t been invited yet.'' ''You''ll see me there,'' said the Footman. ''That''s the first to.', 100, NULL, 19, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(330, 'Alice did not quite like the name: however, it only grinned a little pattering of feet in a great letter,.', 38, NULL, 12, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(331, 'Alice a good deal worse off than before, as the doubled-up soldiers were always getting up and.', 110, NULL, 28, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(332, 'I to do with this creature when I got up in a whisper.) ''That would be the right word) ''--but I shall have to go with the birds hurried off at once.', 60, NULL, 2, 0, '2015-06-19 14:14:31', '2015-06-19 14:14:31'),
(333, 'When she got used to do:-- ''How doth the little golden key was lying under the sea--'' (''I haven''t,'' said Alice)--''and perhaps you were.', 63, NULL, 40, 0, '2015-06-19 14:14:32', '2015-06-19 14:14:32'),
(334, 'Alice hastily replied; ''only one doesn''t like changing so often, you know.'' ''Not the same thing as "I sleep when I get it home?'' when it grunted.', 41, NULL, 19, 0, '2015-06-19 14:14:32', '2015-06-19 14:14:32'),
(335, 'He says it kills all the jurors were all writing very busily on slates. ''What are tarts made of?'' ''Pepper, mostly,'' said.', 121, NULL, 27, 0, '2015-06-19 14:14:32', '2015-06-19 14:14:32'),
(336, 'Alice; ''all I know is, it would be very likely to eat or drink anything; so I''ll just see what was coming. It was so much.', 53, NULL, 11, 0, '2015-06-19 14:14:32', '2015-06-19 14:14:32'),
(337, 'And the moral of that is--"The more there is of mine, the less there is of yours."'' ''Oh, I know!'' exclaimed Alice, who felt ready to ask.', 48, NULL, 14, 0, '2015-06-19 14:14:32', '2015-06-19 14:14:32'),
(338, 'I eat" is the capital of Paris, and Paris is the same thing as "I sleep when I sleep" is the same.', 151, NULL, 6, 0, '2015-06-19 14:14:32', '2015-06-19 14:14:32'),
(339, 'WHAT things?'' said the Caterpillar. Alice said very humbly; ''I won''t have any rules in particular; at least, if there were TWO little.', 135, NULL, 8, 0, '2015-06-19 14:14:32', '2015-06-19 14:14:32'),
(340, 'Alice in a tone of delight, which changed into alarm in another moment, when she heard a voice outside, and stopped to listen. ''Mary Ann!.', 91, NULL, 32, 0, '2015-06-19 14:14:33', '2015-06-19 14:14:33'),
(341, 'Dormouse followed him: the March Hare. ''Exactly so,'' said the Duchess, ''chop off her head!'' Those whom she.', 45, NULL, 51, 0, '2015-06-19 14:14:33', '2015-06-19 14:14:33'),
(342, 'Hardly knowing what she did, she picked up a little scream of laughter. ''Oh, hush!'' the Rabbit actually TOOK A WATCH OUT OF.', 99, NULL, 49, 0, '2015-06-19 14:14:33', '2015-06-19 14:14:33'),
(343, 'I suppose you''ll be telling me next that you have of putting things!'' ''It''s a mineral, I THINK,'' said Alice. ''Did you say.', 109, NULL, 38, 0, '2015-06-19 14:14:33', '2015-06-19 14:14:33'),
(344, 'ME,'' said the Duchess. ''I make you grow taller, and the Queen said to Alice, ''Have you seen the Mock Turtle with a little.', 92, NULL, 33, 0, '2015-06-19 14:14:33', '2015-06-19 14:14:33'),
(345, 'I only knew the right way to fly up into a butterfly, I should like to hear it say, as it could go, and making quite a new pair of white.', 24, NULL, 48, 0, '2015-06-19 14:14:33', '2015-06-19 14:14:33'),
(346, 'Alice, ''and if it likes.'' ''I''d rather finish my tea,'' said the Mock Turtle recovered his voice, and,.', 23, NULL, 14, 0, '2015-06-19 14:14:33', '2015-06-19 14:14:33'),
(347, 'Alice thought to herself, ''it would have appeared to them to sell,'' the Hatter grumbled: ''you shouldn''t have.', 58, NULL, 10, 0, '2015-06-19 14:14:34', '2015-06-19 14:14:34'),
(348, 'Alice did not like the right thing to get through was more and more sounds of broken glass. ''What a funny.', 151, NULL, 14, 0, '2015-06-19 14:14:34', '2015-06-19 14:14:34'),
(349, 'The next thing is, to get an opportunity of showing off her head!'' about once in a piteous tone. And she.', 98, NULL, 14, 0, '2015-06-19 14:14:34', '2015-06-19 14:14:34'),
(350, 'Alice thought she might as well she might, what a long way. So they went on ''And how many hours a day or two: wouldn''t it be murder to.', 131, NULL, 26, 0, '2015-06-19 14:14:34', '2015-06-19 14:14:34'),
(351, 'Pat, what''s that in about half no time! Take your choice!'' The Duchess took no notice of her skirt, upsetting.', 53, NULL, 23, 0, '2015-06-19 14:14:34', '2015-06-19 14:14:34'),
(352, 'This question the Dodo solemnly, rising to its children, ''Come away, my dears! It''s high time to wash the things being alive; for.', 29, NULL, 47, 0, '2015-06-19 14:14:34', '2015-06-19 14:14:34'),
(353, 'White Rabbit returning, splendidly dressed, with a little house in it about four inches deep and.', 152, NULL, 16, 0, '2015-06-19 14:14:35', '2015-06-19 14:14:35'),
(354, 'Alice ventured to taste it, and finding it very hard indeed to make it stop. ''Well, I''d hardly finished.', 23, NULL, 40, 0, '2015-06-19 14:14:35', '2015-06-19 14:14:35'),
(355, 'Alice. ''I don''t see how the game was going to do it! Oh dear! I wish I hadn''t gone down that rabbit-hole--and.', 37, NULL, 17, 0, '2015-06-19 14:14:35', '2015-06-19 14:14:35'),
(359, 'test', NULL, 63, 3, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `comment_answer_rate`
--

CREATE TABLE IF NOT EXISTS `comment_answer_rate` (
  `comment_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `rate_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE IF NOT EXISTS `complaint` (
`id` bigint(20) unsigned NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `title` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `complaint`
--

INSERT INTO `complaint` (`id`, `content`, `created_at`, `user_id`, `group_id`, `title`) VALUES
(1, 'body complaint1', '2015-05-27 13:25:00', 3, 19, 'title complaint1'),
(2, 'body complaint2', '2015-05-27 13:25:00', 3, 19, 'title complaint2'),
(3, 'body complaint3', '2015-05-27 13:25:00', 2, 22, 'title complaint3'),
(6, 'body complaint4', '2015-05-27 21:26:24', 3, 26, 'title complaint4'),
(7, 'body complaint5', '2015-05-27 21:26:24', 2, 26, 'title complaint5'),
(8, 'body complaint6', '2015-05-27 21:26:24', 3, 22, 'title  complaint6');

-- --------------------------------------------------------

--
-- Table structure for table `connection_info`
--

CREATE TABLE IF NOT EXISTS `connection_info` (
`id` int(11) NOT NULL,
  `users` bigint(20) unsigned NOT NULL,
  `connId` int(11) NOT NULL,
  `conn_sess` text NOT NULL,
  `Fonline` int(11) NOT NULL DEFAULT '1',
  `ip` text NOT NULL,
  `browser` varchar(400) NOT NULL,
  `device` varchar(400) NOT NULL,
  `os` varchar(400) NOT NULL,
  `os_version` varchar(400) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=305 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `connection_info`
--

INSERT INTO `connection_info` (`id`, `users`, `connId`, `conn_sess`, `Fonline`, `ip`, `browser`, `device`, `os`, `os_version`, `created_at`, `updated_at`) VALUES
(303, 2, 396, 'eyJpdiI6Im90Z09CK0lXU1VaeVc1YUlHUno2N3c9PSIsInZhbHVlIjoiN1hmYUtqQU5kVzdsYUVTcnZENEtsczgyd3pTYTBUcTNxTUVhaDJIU3JiTDdsY25uc2UzV3c4XC9RbjJ5MXZseVJcL043eVgySDhxTFpNQ1FcL01FYkllT2c9PSIsIm1hYyI6IjY3ODZjMzM1ZDUzNGYwYWQ3YWY3NWU2NzMzMDk1ZjgyOTZmOTI5NTVkNTkxYTFmYWQxMzc4ZTU1OTllY2NhYjUifQ%3D%3D', 1, '::1', 'chrome', 'Desktob', 'windows', 'windows-8-1', '2015-04-19 04:55:10', '2015-04-19 04:55:10'),
(304, 3, 401, 'eyJpdiI6IlNTNGZ0bjBkTGh1anBIMlFpcTM2Zmc9PSIsInZhbHVlIjoiTnlHdTdESWtkRmJvYlwvWjBHXC9EWlJ1ODMwMGFEc0g0NTNNUWV4MGlUUkpRckhMOU9jSXdRenF0akFKcWtDTHoxM0dOUlZNZWV3SnZCNTN1MVF3RVluZz09IiwibWFjIjoiYmFmZjQwYTJkNDZhMDYwNWE4M2YxYTI5YWRmYWZmZGUwZGZjYmVlZWFmZjQ4OWIyMWEwMDE1MTFmNTM5NTgzOSJ9', 1, '::1', 'chrome', 'Desktob', 'windows', 'windows-8-1', '2015-04-19 04:56:11', '2015-04-19 04:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE IF NOT EXISTS `events` (
`id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `details` text COLLATE utf8_unicode_ci NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `allDay` tinyint(1) NOT NULL,
  `backgroundColor` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `borderColor` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `userId` int(10) unsigned NOT NULL,
  `gId` int(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `details`, `start`, `end`, `allDay`, `backgroundColor`, `borderColor`, `userId`, `gId`, `created_at`, `updated_at`) VALUES
(9, 'Add New Event', 'This is MY EVENT', '2015-05-03 00:00:00', '2015-05-04 00:00:00', 1, 'rgb(60, 141, 188)', 'rgb(60, 141, 188)', 2, NULL, '2015-05-15 12:17:01', '2015-05-15 21:18:19'),
(10, 'This is Event', 'WHat is Event I Created', '2015-06-09 00:00:00', '2015-06-10 00:00:00', 1, 'rgb(60, 141, 188)', 'rgb(60, 141, 188)', 2, NULL, '2015-05-15 12:28:31', '2015-06-23 19:32:35'),
(13, 'ـسليم بروجكت AI', '', '2015-05-21 00:00:00', '2015-05-24 00:00:00', 1, 'rgb(60, 141, 188)', 'rgb(60, 141, 188)', 2, 26, '2015-05-15 19:07:29', '2015-06-19 13:54:05'),
(15, 'aaa', 'Even', '2015-05-17 00:00:00', '2015-05-17 00:00:00', 1, 'rgb(221, 75, 57)', 'rgb(221, 75, 57)', 2, 26, '2015-05-15 19:13:47', '2015-05-15 21:14:41'),
(19, 'new event', 'this is event', '2015-05-04 00:00:00', '2015-05-04 00:00:00', 1, 'rgb(60, 141, 188)', 'rgb(60, 141, 188)', 2, 29, '2015-05-15 20:56:55', '2015-05-15 21:27:03');

-- --------------------------------------------------------

--
-- Table structure for table `friends_list`
--

CREATE TABLE IF NOT EXISTS `friends_list` (
`id` int(11) NOT NULL,
  `active` enum('0','1') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user1_id` bigint(20) unsigned NOT NULL,
  `user2_id` bigint(20) unsigned NOT NULL,
  `room_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
`id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `create_date` date NOT NULL,
  `syllable` text COLLATE utf8_unicode_ci NOT NULL,
  `grade_police` text COLLATE utf8_unicode_ci NOT NULL,
  `expireDate` datetime NOT NULL,
  `policy` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `is_project` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`, `image`, `create_date`, `syllable`, `grade_police`, `expireDate`, `policy`, `user_id`, `is_project`, `created_at`, `updated_at`) VALUES
(19, 'Data Base 2', 'In this Course we will study How TO Design Data Base and DML ', 'JDgUvzRR6w.jpg', '2015-05-02', 'this is Course Syllable', 'MidTerm 20 Quiz 15', '2015-05-12 00:00:00', 'private', 3, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 'System Analysis ', 'In This Course We will Study the Principles  of System Analysis ,\r\nand the phases of system development Life cycle (Requirement-Design-Implement-test and Maintain)   ', '5LSJn1njvl.png', '2015-05-03', 'ahmed /n mohamed', 'Midterm Exam : 20Final Exam : 50Section : 10Case Study : 20', '2015-05-22 00:00:00', 'public', 3, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 'technichal', 'szdfghjklsfghjk ;xcfjvghk            ', 'WBlJpPBWbG.jpg', '2015-05-09', '', '', '0000-00-00 00:00:00', 'public', 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 'student help', '            to help student to play football', '4D2KdIifQ7.jpg', '2015-05-10', '', '', '0000-00-00 00:00:00', 'public', 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 'omar adel', '            titfgbhjnkrdcfgtvbhjnktfgyhjuk', '.', '2015-05-10', '', '', '0000-00-00 00:00:00', 'public', 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 'Group', 'This is Grou            ', '.', '2015-05-11', '', '', '0000-00-00 00:00:00', 'public', 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, '123', '123456            ', '.', '2015-05-15', '', '', '0000-00-00 00:00:00', 'public', 2, 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 'Data Structure', 'this project for DS Project', NULL, '0000-00-00', '', '', '2015-06-15 23:00:00', '', 2, 1, '2015-06-19 17:35:06', '2015-06-19 17:35:06'),
(42, 'DB', 'DB', NULL, '0000-00-00', '', '', '2015-11-19 23:00:00', '', 2, 1, '2015-06-19 20:25:41', '2015-06-19 20:25:41'),
(43, 'Pattern Recongnition', 'This is Our Project ABout Pattern Recoginition', NULL, '0000-00-00', '', '', '2015-06-10 23:00:00', '', 2, 1, '2015-06-20 09:12:48', '2015-06-20 09:12:48');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE IF NOT EXISTS `group_members` (
  `group_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `admin` int(1) NOT NULL,
  `request` int(1) NOT NULL,
  `edit_date` date NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`group_id`, `user_id`, `admin`, `request`, `edit_date`, `created_at`, `updated_at`) VALUES
(19, 2, 0, 0, '2015-05-02', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(19, 3, 1, 0, '2015-05-02', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 2, 1, 0, '2015-05-03', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(22, 3, 1, 0, '2015-05-03', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(26, 2, 1, 0, '2015-05-09', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 2, 1, 0, '2015-05-10', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(27, 3, 0, 0, '2015-06-27', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(28, 2, 1, 0, '2015-05-10', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 2, 1, 0, '2015-05-11', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(29, 3, 0, 0, '2015-05-15', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(30, 2, 1, 0, '2015-05-15', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 2, 1, 1, '2015-06-10', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(41, 3, 0, 0, '0000-00-00', '2015-06-20 08:26:24', '2015-06-20 08:26:24'),
(42, 2, 0, 0, '0000-00-00', '2015-06-20 09:13:26', '2015-06-20 09:13:26'),
(42, 3, 0, 0, '0000-00-00', '2015-06-20 09:13:34', '2015-06-20 09:13:34'),
(42, 4, 0, 0, '0000-00-00', '2015-06-20 09:14:15', '2015-06-20 09:14:15'),
(42, 5, 0, 0, '0000-00-00', '2015-06-20 13:03:16', '2015-06-20 13:03:16'),
(42, 7, 0, 0, '0000-00-00', '2015-06-20 13:05:15', '2015-06-20 13:05:15'),
(42, 8, 0, 0, '0000-00-00', '2015-06-20 13:06:42', '2015-06-20 13:06:42'),
(42, 10, 0, 0, '0000-00-00', '2015-06-20 13:08:10', '2015-06-20 13:08:10'),
(43, 2, 0, 0, '0000-00-00', '2015-06-20 13:10:16', '2015-06-20 13:10:16'),
(43, 3, 0, 0, '0000-00-00', '2015-06-20 13:09:55', '2015-06-20 13:09:55'),
(43, 4, 0, 0, '0000-00-00', '2015-06-20 13:10:26', '2015-06-20 13:10:26'),
(43, 15, 0, 0, '0000-00-00', '2015-06-20 13:15:00', '2015-06-20 13:15:00'),
(43, 20, 0, 0, '0000-00-00', '2015-06-21 07:54:34', '2015-06-21 07:54:34');

-- --------------------------------------------------------

--
-- Table structure for table `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
`id` bigint(20) unsigned NOT NULL,
  `file_name` varchar(20) NOT NULL,
  `real_name` varchar(100) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `gId` int(10) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `materials`
--

INSERT INTO `materials` (`id`, `file_name`, `real_name`, `description`, `gId`, `created_at`, `updated_at`) VALUES
(1, 'ZDrSJ7I2ZF.jpg', '1911896_1543795115869995_45492328328812881_n.jpg', '', 19, '2015-06-19 15:00:12', '2015-06-19 15:00:12'),
(2, 'D3noQyRb7x.jpg', '10421182_453987971416512_525105794596463970_n.jpg', '', 19, '2015-06-19 15:00:12', '2015-06-19 15:00:12'),
(3, 'xfbYid7dHp.jpg', '10898062_1533408310242009_2838250943735884113_n.jpg', '', 19, '2015-06-19 15:00:12', '2015-06-19 15:00:12'),
(4, 'yqRxK9Gwrg.jpg', '11050655_1545051669077673_9219931545002543517_n.jpg', '', 19, '2015-06-19 15:00:12', '2015-06-19 15:00:12'),
(5, 'QFVl3kaCsa.jpg', '19129_787939697948405_714101268251874417_n.jpg', 'this%20is%20hu%20huyuvdhu%20hivwehdiuf', 19, '2015-06-20 17:42:32', '2015-06-20 17:42:32'),
(6, 'ewDFZcVmNh.jpg', '1185752_656825290995688_1686870019_n.jpg', 'this%20is%20hu%20huyuvdhu%20hivwehdiuf', 19, '2015-06-20 17:42:32', '2015-06-20 17:42:32'),
(7, 'yyndj1Etzc.jpg', '10463007_1423157471302998_2571546538625379093_n.jpg', 'this%20is%20hu%20huyuvdhu%20hivwehdiuf', 19, '2015-06-20 17:42:32', '2015-06-20 17:42:32'),
(8, 'QJhQWPwa55.jpg', '1911896_1543795115869995_45492328328812881_n.jpg', '', 22, '2015-06-23 20:47:09', '2015-06-23 20:47:09'),
(9, 'PfE23aLdLG.jpg', '10421182_453987971416512_525105794596463970_n.jpg', '', 22, '2015-06-23 20:47:09', '2015-06-23 20:47:09'),
(10, 'TFOt9AefLS.jpg', '10898062_1533408310242009_2838250943735884113_n.jpg', '', 22, '2015-06-23 20:47:09', '2015-06-23 20:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
`id` int(11) NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `chat_room_id` int(11) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `body`, `chat_room_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'ahmed', 7, 3, '2015-04-09 09:36:03', '2015-04-09 09:36:03'),
(2, 'omar', 7, 3, '2015-04-09 09:36:19', '2015-04-09 09:36:19'),
(3, 'ata7777', 7, 2, '2015-04-09 09:53:51', '2015-04-09 09:53:51'),
(4, 'ahmed adel', 7, 3, '2015-04-09 09:54:07', '2015-04-09 09:54:07'),
(5, 'ata777777', 7, 2, '2015-04-09 09:54:26', '2015-04-09 09:54:26'),
(6, 'ddd', 7, 3, '2015-04-19 04:56:58', '2015-04-19 04:56:58'),
(7, 'dd', 7, 2, '2015-04-19 04:57:19', '2015-04-19 04:57:19');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2015_02_12_133821_create_users_table', 1),
('2015_02_12_133953_create_groups_table', 2),
('2015_02_12_134104_create_groups_members_table', 3),
('2015_02_12_135746_create_vote_table', 4),
('2015_02_12_141443_create_vote_choice_table', 5),
('2015_02_12_141931_create_vote_user_choice_table', 6),
('2015_02_12_142946_create_question_table', 7),
('2015_02_12_143342_create_question_answers_table', 8),
('2015_02_12_143742_create_answer_user_rate_table', 9),
('2015_02_12_144431_create_comment_answer_table', 10),
('2015_02_12_144837_create_comment_answer_rate_table', 11),
('2015_02_12_145144_create_answer_highlighting_table', 12),
('2015_02_14_172613_create_announcement_table', 13),
('2015_02_14_173507_create_announcement_user_table', 14),
('2015_02_14_173546_create_announcement_group_table', 15),
('2015_02_14_173648_create_assignment_table', 16),
('2015_02_14_181813_create_groups_admins_table', 17),
('2015_02_09_194436_create_users_table', 18),
('2015_02_14_094632_create_news_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
`id` bigint(20) NOT NULL,
  `users` bigint(20) unsigned NOT NULL,
  `groups` bigint(20) unsigned DEFAULT NULL,
  `message` text NOT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `users`, `groups`, `message`, `creation_date`) VALUES
(1, 2, 19, '<b> Ahmed Abdelftah </b> post Announcement in Data Base 2', '2015-05-29 03:18:54'),
(2, 2, 19, 'Ahmed Abdelftah post Announcement in Data Base 2', '2015-05-29 03:53:21'),
(3, 2, 19, 'Ahmed Abdelftah post Announcement in Data Base 2', '2015-06-10 11:25:43'),
(4, 3, NULL, 'Ahmed Omar post Announcement ', '2015-06-10 11:51:25'),
(5, 2, 19, 'Ahmed Abdelftah Add Vote ( aaaaaaaaaa 	) in Data Base 2', '2015-06-10 12:16:39'),
(6, 2, 19, '<b>Ahmed Abdelftah </b> Add Vote ( dddddddddddddddddddd 	) in Data Base 2', '2015-06-10 12:22:14'),
(7, 2, 19, 'Ahmed Abdelftah Add Vote ( uyikjhk 	) in Data Base 2', '2015-06-10 12:24:22'),
(8, 2, 19, 'Ahmed Abdelftah Add Assignment ( pl3 assign 1 	) in Data Base 2 With Due Date <small><i class="fa fa-clock-o"></i> 2015-06-18 00:00:00 </small>', '2015-06-10 12:41:04'),
(9, 2, 19, 'Ahmed Abdelftah Ask a Question ( q1 	) in Data Base 2', '2015-06-10 12:50:00'),
(10, 2, 19, 'Ahmed Abdelftah Add a Quiz ( eee 	) in Data Base 2 with Start Date : 2015-06-11 00:00:00 and End Date : 2015-06-19 00:00:00', '2015-06-10 13:02:35'),
(11, 51, 26, '<b>Shaniya Feeney</b> Ask a Question ( Queen, and Alice, were in. 	) in <b>technichal</b>', '2015-06-19 14:13:34'),
(12, 47, 28, '<b>Edwardo Leffler</b> Ask a Question ( Then it got down off. 	) in <b>omar adel</b>', '2015-06-19 14:13:35'),
(13, 15, 29, '<b>Clay Mertz</b> Ask a Question ( Alice, timidly; ''some of. 	) in <b>Group</b>', '2015-06-19 14:13:35'),
(14, 16, 29, '<b>Bridget Rippin</b> Ask a Question ( If she should chance to be. 	) in <b>Group</b>', '2015-06-19 14:13:35'),
(15, 6, 26, '<b>Delia Lind</b> Ask a Question ( Alice, ''and why it is I hate. 	) in <b>technichal</b>', '2015-06-19 14:13:35'),
(16, 24, 30, '<b>Wava Skiles</b> Ask a Question ( That your eye was as steady. 	) in <b>123</b>', '2015-06-19 14:13:35'),
(17, 24, 22, '<b>Wava Skiles</b> Ask a Question ( Hatter: ''let''s all move one. 	) in <b>System Analysis </b>', '2015-06-19 14:13:35'),
(18, 5, 19, '<b>Brennan Harber</b> Ask a Question ( I don''t know,'' he went on, ''I. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:35'),
(19, 33, 19, '<b>Maryam Ortiz</b> Ask a Question ( Caterpillar angrily, rearing. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:35'),
(20, 3, 29, '<b>Ahmed Omar</b> Ask a Question ( Hatter, and he went on. 	) in <b>Group</b>', '2015-06-19 14:13:35'),
(21, 23, 19, '<b>Taryn Bartell</b> Ask a Question ( White Rabbit as he spoke.. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:35'),
(22, 23, 30, '<b>Taryn Bartell</b> Ask a Question ( King say in a fight with another. 	) in <b>123</b>', '2015-06-19 14:13:36'),
(23, 53, 26, '<b>Johanna Marks</b> Ask a Question ( Five! Don''t go splashing. 	) in <b>technichal</b>', '2015-06-19 14:13:36'),
(24, 9, 22, '<b>Catherine Thiel</b> Ask a Question ( White Rabbit as he spoke, and then at. 	) in <b>System Analysis </b>', '2015-06-19 14:13:36'),
(25, 5, 27, '<b>Brennan Harber</b> Ask a Question ( The jury all looked puzzled.) ''He. 	) in <b>student help</b>', '2015-06-19 14:13:36'),
(26, 6, 26, '<b>Delia Lind</b> Ask a Question ( Alice started to her that she had got. 	) in <b>technichal</b>', '2015-06-19 14:13:36'),
(27, 43, 27, '<b>Napoleon Keebler</b> Ask a Question ( And mentioned me. 	) in <b>student help</b>', '2015-06-19 14:13:36'),
(28, 35, 30, '<b>Faustino Erdman</b> Ask a Question ( For instance, if you cut. 	) in <b>123</b>', '2015-06-19 14:13:36'),
(29, 54, 28, '<b>Earnest Rath</b> Ask a Question ( There was nothing on it. 	) in <b>omar adel</b>', '2015-06-19 14:13:36'),
(30, 37, 29, '<b>Marjolaine Kris</b> Ask a Question ( And yet you incessantly stand on. 	) in <b>Group</b>', '2015-06-19 14:13:36'),
(31, 46, 28, '<b>Sheridan Spinka</b> Ask a Question ( Duchess. ''I make you grow taller, and. 	) in <b>omar adel</b>', '2015-06-19 14:13:37'),
(32, 53, 19, '<b>Johanna Marks</b> Ask a Question ( I''ll set Dinah at you!''. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:37'),
(33, 47, 26, '<b>Edwardo Leffler</b> Ask a Question ( Even the Duchess was. 	) in <b>technichal</b>', '2015-06-19 14:13:37'),
(34, 3, 29, '<b>Ahmed Omar</b> Ask a Question ( I THINK,'' said Alice. ''Well, then,''. 	) in <b>Group</b>', '2015-06-19 14:13:37'),
(35, 27, 27, '<b>Cassandra Prosacco</b> Ask a Question ( Why, there''s hardly enough of me left. 	) in <b>student help</b>', '2015-06-19 14:13:37'),
(36, 5, 19, '<b>Brennan Harber</b> Ask a Question ( I didn''t know that you''re mad?'' ''To. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:37'),
(37, 54, 19, '<b>Earnest Rath</b> Ask a Question ( Alice angrily. ''It wasn''t very. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:37'),
(38, 30, 29, '<b>Reyna Smith</b> Ask a Question ( White Rabbit blew three. 	) in <b>Group</b>', '2015-06-19 14:13:37'),
(39, 20, 19, '<b>Alek Brakus</b> Ask a Question ( She pitied him deeply. ''What is. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:38'),
(40, 26, 28, '<b>Stanley Bosco</b> Ask a Question ( But if I''m not. 	) in <b>omar adel</b>', '2015-06-19 14:13:38'),
(41, 44, 29, '<b>Madonna Stracke</b> Ask a Question ( Alice, and, after. 	) in <b>Group</b>', '2015-06-19 14:13:38'),
(42, 18, 30, '<b>Terence Franecki</b> Ask a Question ( Majesty must cross-examine the. 	) in <b>123</b>', '2015-06-19 14:13:38'),
(43, 13, 29, '<b>Chadd Halvorson</b> Ask a Question ( The Hatter was the first. 	) in <b>Group</b>', '2015-06-19 14:13:38'),
(44, 8, 26, '<b>Rahsaan Reinger</b> Ask a Question ( King said gravely, ''and go on. 	) in <b>technichal</b>', '2015-06-19 14:13:38'),
(45, 39, 27, '<b>Kacey Berge</b> Ask a Question ( Alice, (she had kept a. 	) in <b>student help</b>', '2015-06-19 14:13:38'),
(46, 45, 27, '<b>Shanon Botsford</b> Ask a Question ( I mentioned before, And have grown. 	) in <b>student help</b>', '2015-06-19 14:13:38'),
(47, 35, 26, '<b>Faustino Erdman</b> Ask a Question ( Alice gave a little. 	) in <b>technichal</b>', '2015-06-19 14:13:39'),
(48, 4, 27, '<b>Asma Khairallah</b> Ask a Question ( Her first idea was that. 	) in <b>student help</b>', '2015-06-19 14:13:39'),
(49, 32, 19, '<b>Oran Kautzer</b> Ask a Question ( Alice, who was passing at. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:39'),
(50, 29, 27, '<b>Rafaela Hilpert</b> Ask a Question ( Majesty,'' said the King.. 	) in <b>student help</b>', '2015-06-19 14:13:39'),
(51, 43, 30, '<b>Napoleon Keebler</b> Ask a Question ( The Mouse did not. 	) in <b>123</b>', '2015-06-19 14:13:39'),
(52, 43, 22, '<b>Napoleon Keebler</b> Ask a Question ( Queen. ''Sentence. 	) in <b>System Analysis </b>', '2015-06-19 14:13:39'),
(53, 21, 29, '<b>Craig Ullrich</b> Ask a Question ( OUTSIDE.'' He unfolded the paper. 	) in <b>Group</b>', '2015-06-19 14:13:40'),
(54, 48, 30, '<b>Noe Weimann</b> Ask a Question ( I am very tired of this. I vote the. 	) in <b>123</b>', '2015-06-19 14:13:40'),
(55, 32, 27, '<b>Oran Kautzer</b> Ask a Question ( Hatter. He came in sight of the. 	) in <b>student help</b>', '2015-06-19 14:13:40'),
(56, 2, 19, '<b>Ahmed Abdelftah</b> Ask a Question ( Alice, ''but I must sugar my. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:40'),
(57, 33, 26, '<b>Maryam Ortiz</b> Ask a Question ( Alice looked all round the. 	) in <b>technichal</b>', '2015-06-19 14:13:40'),
(58, 40, 22, '<b>Zora Rice</b> Ask a Question ( His voice has a timid and. 	) in <b>System Analysis </b>', '2015-06-19 14:13:40'),
(59, 5, 19, '<b>Brennan Harber</b> Ask a Question ( Alice, and she sat down and. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:41'),
(60, 16, 26, '<b>Bridget Rippin</b> Ask a Question ( The moment Alice felt. 	) in <b>technichal</b>', '2015-06-19 14:13:41'),
(61, 9, 19, '<b>Catherine Thiel</b> Ask a Question ( No, no! You''re a. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:41'),
(62, 29, 30, '<b>Rafaela Hilpert</b> Ask a Question ( The further off from England the. 	) in <b>123</b>', '2015-06-19 14:13:41'),
(63, 53, 28, '<b>Johanna Marks</b> Ask a Question ( Alice replied in an. 	) in <b>omar adel</b>', '2015-06-19 14:13:41'),
(64, 20, 26, '<b>Alek Brakus</b> Ask a Question ( Queen, and Alice,. 	) in <b>technichal</b>', '2015-06-19 14:13:41'),
(65, 54, 28, '<b>Earnest Rath</b> Ask a Question ( I''m better now--but I''m a. 	) in <b>omar adel</b>', '2015-06-19 14:13:41'),
(66, 24, 19, '<b>Wava Skiles</b> Ask a Question ( King said, turning to Alice,. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:41'),
(67, 11, 30, '<b>Elvera Johns</b> Ask a Question ( When the pie was all. 	) in <b>123</b>', '2015-06-19 14:13:42'),
(68, 49, 29, '<b>Malika Oberbrunner</b> Ask a Question ( Bill! catch hold of it; so, after. 	) in <b>Group</b>', '2015-06-19 14:13:42'),
(69, 19, 19, '<b>Devon Miller</b> Ask a Question ( The Fish-Footman began by. 	) in <b>Data Base 2</b>', '2015-06-19 14:13:42'),
(70, 42, 22, '<b>Sammy Champlin</b> Ask a Question ( Dinah, and saying "Come. 	) in <b>System Analysis </b>', '2015-06-19 14:13:42'),
(71, 2, 26, '<b>Ahmed Abdelftah</b> Add Vote ( aa 	) in <b>technichal</b>', '2015-06-19 14:53:40'),
(72, 10, NULL, '<b>Haskell Hessel</b> post Announcement ', '2015-06-19 16:20:14'),
(73, 16, NULL, '<b>Bridget Rippin</b> post Announcement ', '2015-06-19 16:20:14'),
(74, 18, NULL, '<b>Terence Franecki</b> post Announcement ', '2015-06-19 16:20:15'),
(75, 22, NULL, '<b>Magali Turner</b> post Announcement ', '2015-06-19 16:20:15'),
(76, 27, NULL, '<b>Cassandra Prosacco</b> post Announcement ', '2015-06-19 16:20:15'),
(77, 29, NULL, '<b>Rafaela Hilpert</b> post Announcement ', '2015-06-19 16:20:15'),
(78, 37, NULL, '<b>Marjolaine Kris</b> post Announcement ', '2015-06-19 16:20:15'),
(79, 38, NULL, '<b>Freddy Simonis</b> post Announcement ', '2015-06-19 16:20:15'),
(80, 53, NULL, '<b>Johanna Marks</b> post Announcement ', '2015-06-19 16:20:15'),
(81, 2, 27, '<b>Ahmed Abdelftah</b> Add Vote ( l, 	) in <b>student help</b>', '2015-06-22 20:11:39'),
(82, 2, 22, '<b>Ahmed Abdelftah</b> post Announcement in <b>System Analysis </b>', '2015-06-23 20:44:36'),
(83, 2, 22, '<b>Ahmed Abdelftah</b> post Announcement in <b>System Analysis </b>', '2015-06-23 20:44:49'),
(84, 2, 27, '<b>Ahmed Abdelftah</b> Add Vote ( rtdykuk 	) in <b>student help</b>', '2015-06-27 14:04:55'),
(85, 14, 27, '<b>Hubert Ratke</b> Add Vote ( mmmmmmmmmmmmm 	) in <b>student help</b>', '2015-06-30 23:09:53'),
(86, 3, 19, '<b>Ahmed Omar</b> Add  Quiz ( Test 	) in <b>Data Base 2</b> with Start Date <small><i class="fa fa-clock-o"></i> 2015-07-03 00:00:00</small> and End Date <small><i class="fa fa-clock-o"></i> 2015-07-04 00:00:00</small>', '2015-07-02 08:16:54'),
(87, 3, 19, '<b>Ahmed Omar</b> Add  Quiz ( TestSurvy 	) in <b>Data Base 2</b> with Start Date <small><i class="fa fa-clock-o"></i> 2015-07-15 00:00:00</small> and End Date <small><i class="fa fa-clock-o"></i> 2015-07-09 00:00:00</small>', '2015-07-02 08:21:01'),
(88, 3, 19, '<b>Ahmed Omar</b> Add  Quiz ( Testt 	) in <b>Data Base 2</b> with Start Date <small><i class="fa fa-clock-o"></i> 2015-07-15 00:00:00</small> and End Date <small><i class="fa fa-clock-o"></i> 2015-07-24 00:00:00</small>', '2015-07-02 09:22:46'),
(89, 3, 19, '<b>Ahmed Omar</b> Add  Quiz ( Testt 	) in <b>Data Base 2</b> with Start Date <small><i class="fa fa-clock-o"></i> 2015-07-15 00:00:00</small> and End Date <small><i class="fa fa-clock-o"></i> 2015-07-24 00:00:00</small>', '2015-07-02 09:36:42'),
(90, 2, 19, '<b>Ahmed Abdelftah</b> post Announcement in <b>Data Base 2</b>', '2015-07-02 16:29:29'),
(91, 3, 19, '<b>Ahmed Omar</b> post Announcement in <b>Data Base 2</b>', '2015-07-02 16:29:29'),
(92, 3, 26, '<b>Ahmed Omar</b> post Announcement in <b>technichal</b>', '2015-07-02 16:29:29'),
(93, 2, 26, '<b>Ahmed Abdelftah</b> post Announcement in <b>technichal</b>', '2015-07-02 16:29:29'),
(94, 2, 19, '<b>Ahmed Abdelftah</b> post Announcement in <b>Data Base 2</b>', '2015-07-02 16:29:29'),
(95, 2, 22, '<b>Ahmed Abdelftah</b> post Announcement in <b>System Analysis </b>', '2015-07-02 16:29:29'),
(96, 2, 29, '<b>Ahmed Abdelftah</b> post Announcement in <b>Group</b>', '2015-07-02 16:29:29'),
(97, 2, 26, '<b>Ahmed Abdelftah</b> post Announcement in <b>technichal</b>', '2015-07-02 16:29:29'),
(98, 2, 22, '<b>Ahmed Abdelftah</b> post Announcement in <b>System Analysis </b>', '2015-07-02 16:29:29'),
(99, 2, NULL, '<b>Ahmed Abdelftah</b> post Announcement ', '2015-07-02 16:29:29'),
(100, 3, NULL, '<b>Ahmed Omar</b> post Announcement ', '2015-07-02 16:29:29'),
(101, 5, NULL, '<b>Brennan Harber</b> post Announcement ', '2015-07-02 16:29:29'),
(102, 6, NULL, '<b>Delia Lind</b> post Announcement ', '2015-07-02 16:29:29'),
(103, 8, NULL, '<b>Rahsaan Reinger</b> post Announcement ', '2015-07-02 16:29:29'),
(104, 11, NULL, '<b>Elvera Johns</b> post Announcement ', '2015-07-02 16:29:29'),
(105, 14, NULL, '<b>Hubert Ratke</b> post Announcement ', '2015-07-02 16:29:29'),
(106, 15, NULL, '<b>Clay Mertz</b> post Announcement ', '2015-07-02 16:29:29'),
(107, 19, NULL, '<b>Devon Miller</b> post Announcement ', '2015-07-02 16:29:29'),
(108, 28, NULL, '<b>Corine Kemmer</b> post Announcement ', '2015-07-02 16:29:29'),
(109, 30, NULL, '<b>Reyna Smith</b> post Announcement ', '2015-07-02 16:29:29'),
(110, 31, NULL, '<b>Amie Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(111, 34, NULL, '<b>Kaleigh Nolan</b> post Announcement ', '2015-07-02 16:29:29'),
(112, 35, NULL, '<b>Faustino Erdman</b> post Announcement ', '2015-07-02 16:29:29'),
(113, 44, NULL, '<b>Madonna Stracke</b> post Announcement ', '2015-07-02 16:29:29'),
(114, 45, NULL, '<b>Shanon Botsford</b> post Announcement ', '2015-07-02 16:29:29'),
(115, 48, NULL, '<b>Noe Weimann</b> post Announcement ', '2015-07-02 16:29:29'),
(116, 49, NULL, '<b>Malika Oberbrunner</b> post Announcement ', '2015-07-02 16:29:29'),
(117, 51, NULL, '<b>Shaniya Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(118, 52, NULL, '<b>Else Boyer</b> post Announcement ', '2015-07-02 16:29:29'),
(119, 10, NULL, '<b>Haskell Hessel</b> post Announcement ', '2015-07-02 16:29:29'),
(120, 16, NULL, '<b>Bridget Rippin</b> post Announcement ', '2015-07-02 16:29:29'),
(121, 18, NULL, '<b>Terence Franecki</b> post Announcement ', '2015-07-02 16:29:29'),
(122, 22, NULL, '<b>Magali Turner</b> post Announcement ', '2015-07-02 16:29:29'),
(123, 27, NULL, '<b>Cassandra Prosacco</b> post Announcement ', '2015-07-02 16:29:29'),
(124, 29, NULL, '<b>Rafaela Hilpert</b> post Announcement ', '2015-07-02 16:29:29'),
(125, 37, NULL, '<b>Marjolaine Kris</b> post Announcement ', '2015-07-02 16:29:29'),
(126, 38, NULL, '<b>Freddy Simonis</b> post Announcement ', '2015-07-02 16:29:29'),
(127, 53, NULL, '<b>Johanna Marks</b> post Announcement ', '2015-07-02 16:29:29'),
(128, 2, NULL, '<b>Ahmed Abdelftah</b> post Announcement ', '2015-07-02 16:29:29'),
(129, 3, NULL, '<b>Ahmed Omar</b> post Announcement ', '2015-07-02 16:29:29'),
(130, 5, NULL, '<b>Brennan Harber</b> post Announcement ', '2015-07-02 16:29:29'),
(131, 6, NULL, '<b>Delia Lind</b> post Announcement ', '2015-07-02 16:29:29'),
(132, 8, NULL, '<b>Rahsaan Reinger</b> post Announcement ', '2015-07-02 16:29:29'),
(133, 11, NULL, '<b>Elvera Johns</b> post Announcement ', '2015-07-02 16:29:29'),
(134, 14, NULL, '<b>Hubert Ratke</b> post Announcement ', '2015-07-02 16:29:29'),
(135, 15, NULL, '<b>Clay Mertz</b> post Announcement ', '2015-07-02 16:29:29'),
(136, 19, NULL, '<b>Devon Miller</b> post Announcement ', '2015-07-02 16:29:29'),
(137, 28, NULL, '<b>Corine Kemmer</b> post Announcement ', '2015-07-02 16:29:29'),
(138, 30, NULL, '<b>Reyna Smith</b> post Announcement ', '2015-07-02 16:29:29'),
(139, 31, NULL, '<b>Amie Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(140, 34, NULL, '<b>Kaleigh Nolan</b> post Announcement ', '2015-07-02 16:29:29'),
(141, 35, NULL, '<b>Faustino Erdman</b> post Announcement ', '2015-07-02 16:29:29'),
(142, 44, NULL, '<b>Madonna Stracke</b> post Announcement ', '2015-07-02 16:29:29'),
(143, 45, NULL, '<b>Shanon Botsford</b> post Announcement ', '2015-07-02 16:29:29'),
(144, 48, NULL, '<b>Noe Weimann</b> post Announcement ', '2015-07-02 16:29:29'),
(145, 49, NULL, '<b>Malika Oberbrunner</b> post Announcement ', '2015-07-02 16:29:29'),
(146, 51, NULL, '<b>Shaniya Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(147, 52, NULL, '<b>Else Boyer</b> post Announcement ', '2015-07-02 16:29:29'),
(148, 2, NULL, '<b>Ahmed Abdelftah</b> post Announcement ', '2015-07-02 16:29:29'),
(149, 3, NULL, '<b>Ahmed Omar</b> post Announcement ', '2015-07-02 16:29:29'),
(150, 5, NULL, '<b>Brennan Harber</b> post Announcement ', '2015-07-02 16:29:29'),
(151, 6, NULL, '<b>Delia Lind</b> post Announcement ', '2015-07-02 16:29:29'),
(152, 8, NULL, '<b>Rahsaan Reinger</b> post Announcement ', '2015-07-02 16:29:29'),
(153, 11, NULL, '<b>Elvera Johns</b> post Announcement ', '2015-07-02 16:29:29'),
(154, 14, NULL, '<b>Hubert Ratke</b> post Announcement ', '2015-07-02 16:29:29'),
(155, 15, NULL, '<b>Clay Mertz</b> post Announcement ', '2015-07-02 16:29:29'),
(156, 19, NULL, '<b>Devon Miller</b> post Announcement ', '2015-07-02 16:29:29'),
(157, 28, NULL, '<b>Corine Kemmer</b> post Announcement ', '2015-07-02 16:29:29'),
(158, 30, NULL, '<b>Reyna Smith</b> post Announcement ', '2015-07-02 16:29:29'),
(159, 31, NULL, '<b>Amie Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(160, 34, NULL, '<b>Kaleigh Nolan</b> post Announcement ', '2015-07-02 16:29:29'),
(161, 35, NULL, '<b>Faustino Erdman</b> post Announcement ', '2015-07-02 16:29:29'),
(162, 44, NULL, '<b>Madonna Stracke</b> post Announcement ', '2015-07-02 16:29:29'),
(163, 45, NULL, '<b>Shanon Botsford</b> post Announcement ', '2015-07-02 16:29:29'),
(164, 48, NULL, '<b>Noe Weimann</b> post Announcement ', '2015-07-02 16:29:29'),
(165, 49, NULL, '<b>Malika Oberbrunner</b> post Announcement ', '2015-07-02 16:29:29'),
(166, 51, NULL, '<b>Shaniya Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(167, 52, NULL, '<b>Else Boyer</b> post Announcement ', '2015-07-02 16:29:29'),
(168, 2, NULL, '<b>Ahmed Abdelftah</b> post Announcement ', '2015-07-02 16:29:29'),
(169, 3, NULL, '<b>Ahmed Omar</b> post Announcement ', '2015-07-02 16:29:29'),
(170, 5, NULL, '<b>Brennan Harber</b> post Announcement ', '2015-07-02 16:29:29'),
(171, 6, NULL, '<b>Delia Lind</b> post Announcement ', '2015-07-02 16:29:29'),
(172, 8, NULL, '<b>Rahsaan Reinger</b> post Announcement ', '2015-07-02 16:29:29'),
(173, 11, NULL, '<b>Elvera Johns</b> post Announcement ', '2015-07-02 16:29:29'),
(174, 14, NULL, '<b>Hubert Ratke</b> post Announcement ', '2015-07-02 16:29:29'),
(175, 15, NULL, '<b>Clay Mertz</b> post Announcement ', '2015-07-02 16:29:29'),
(176, 19, NULL, '<b>Devon Miller</b> post Announcement ', '2015-07-02 16:29:29'),
(177, 28, NULL, '<b>Corine Kemmer</b> post Announcement ', '2015-07-02 16:29:29'),
(178, 30, NULL, '<b>Reyna Smith</b> post Announcement ', '2015-07-02 16:29:29'),
(179, 31, NULL, '<b>Amie Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(180, 34, NULL, '<b>Kaleigh Nolan</b> post Announcement ', '2015-07-02 16:29:29'),
(181, 35, NULL, '<b>Faustino Erdman</b> post Announcement ', '2015-07-02 16:29:29'),
(182, 44, NULL, '<b>Madonna Stracke</b> post Announcement ', '2015-07-02 16:29:29'),
(183, 45, NULL, '<b>Shanon Botsford</b> post Announcement ', '2015-07-02 16:29:29'),
(184, 48, NULL, '<b>Noe Weimann</b> post Announcement ', '2015-07-02 16:29:29'),
(185, 49, NULL, '<b>Malika Oberbrunner</b> post Announcement ', '2015-07-02 16:29:29'),
(186, 51, NULL, '<b>Shaniya Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(187, 52, NULL, '<b>Else Boyer</b> post Announcement ', '2015-07-02 16:29:29'),
(188, 2, NULL, '<b>Ahmed Abdelftah</b> post Announcement ', '2015-07-02 16:29:29'),
(189, 3, NULL, '<b>Ahmed Omar</b> post Announcement ', '2015-07-02 16:29:29'),
(190, 5, NULL, '<b>Brennan Harber</b> post Announcement ', '2015-07-02 16:29:29'),
(191, 6, NULL, '<b>Delia Lind</b> post Announcement ', '2015-07-02 16:29:29'),
(192, 8, NULL, '<b>Rahsaan Reinger</b> post Announcement ', '2015-07-02 16:29:29'),
(193, 11, NULL, '<b>Elvera Johns</b> post Announcement ', '2015-07-02 16:29:29'),
(194, 14, NULL, '<b>Hubert Ratke</b> post Announcement ', '2015-07-02 16:29:29'),
(195, 15, NULL, '<b>Clay Mertz</b> post Announcement ', '2015-07-02 16:29:29'),
(196, 19, NULL, '<b>Devon Miller</b> post Announcement ', '2015-07-02 16:29:29'),
(197, 28, NULL, '<b>Corine Kemmer</b> post Announcement ', '2015-07-02 16:29:29'),
(198, 30, NULL, '<b>Reyna Smith</b> post Announcement ', '2015-07-02 16:29:29'),
(199, 31, NULL, '<b>Amie Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(200, 34, NULL, '<b>Kaleigh Nolan</b> post Announcement ', '2015-07-02 16:29:29'),
(201, 35, NULL, '<b>Faustino Erdman</b> post Announcement ', '2015-07-02 16:29:29'),
(202, 44, NULL, '<b>Madonna Stracke</b> post Announcement ', '2015-07-02 16:29:29'),
(203, 45, NULL, '<b>Shanon Botsford</b> post Announcement ', '2015-07-02 16:29:29'),
(204, 48, NULL, '<b>Noe Weimann</b> post Announcement ', '2015-07-02 16:29:29'),
(205, 49, NULL, '<b>Malika Oberbrunner</b> post Announcement ', '2015-07-02 16:29:29'),
(206, 51, NULL, '<b>Shaniya Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(207, 52, NULL, '<b>Else Boyer</b> post Announcement ', '2015-07-02 16:29:29'),
(208, 2, NULL, '<b>Ahmed Abdelftah</b> post Announcement ', '2015-07-02 16:29:29'),
(209, 3, NULL, '<b>Ahmed Omar</b> post Announcement ', '2015-07-02 16:29:29'),
(210, 5, NULL, '<b>Brennan Harber</b> post Announcement ', '2015-07-02 16:29:29'),
(211, 6, NULL, '<b>Delia Lind</b> post Announcement ', '2015-07-02 16:29:29'),
(212, 8, NULL, '<b>Rahsaan Reinger</b> post Announcement ', '2015-07-02 16:29:29'),
(213, 11, NULL, '<b>Elvera Johns</b> post Announcement ', '2015-07-02 16:29:29'),
(214, 14, NULL, '<b>Hubert Ratke</b> post Announcement ', '2015-07-02 16:29:29'),
(215, 15, NULL, '<b>Clay Mertz</b> post Announcement ', '2015-07-02 16:29:29'),
(216, 19, NULL, '<b>Devon Miller</b> post Announcement ', '2015-07-02 16:29:29'),
(217, 28, NULL, '<b>Corine Kemmer</b> post Announcement ', '2015-07-02 16:29:29'),
(218, 30, NULL, '<b>Reyna Smith</b> post Announcement ', '2015-07-02 16:29:29'),
(219, 31, NULL, '<b>Amie Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(220, 34, NULL, '<b>Kaleigh Nolan</b> post Announcement ', '2015-07-02 16:29:29'),
(221, 35, NULL, '<b>Faustino Erdman</b> post Announcement ', '2015-07-02 16:29:29'),
(222, 44, NULL, '<b>Madonna Stracke</b> post Announcement ', '2015-07-02 16:29:29'),
(223, 45, NULL, '<b>Shanon Botsford</b> post Announcement ', '2015-07-02 16:29:29'),
(224, 48, NULL, '<b>Noe Weimann</b> post Announcement ', '2015-07-02 16:29:29'),
(225, 49, NULL, '<b>Malika Oberbrunner</b> post Announcement ', '2015-07-02 16:29:29'),
(226, 51, NULL, '<b>Shaniya Feeney</b> post Announcement ', '2015-07-02 16:29:29'),
(227, 52, NULL, '<b>Else Boyer</b> post Announcement ', '2015-07-02 16:29:29'),
(228, 3, 22, '<b>Ahmed Omar</b> post Announcement in <b>System Analysis </b>', '2015-07-02 16:39:39'),
(229, 3, 27, '<b>Ahmed Omar</b> post Announcement in <b>student help</b>', '2015-07-02 16:40:11');

-- --------------------------------------------------------

--
-- Table structure for table `notification_seen`
--

CREATE TABLE IF NOT EXISTS `notification_seen` (
`id` bigint(20) NOT NULL,
  `notification` bigint(20) NOT NULL,
  `users` bigint(20) unsigned NOT NULL,
  `seen` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification_seen`
--

INSERT INTO `notification_seen` (`id`, `notification`, `users`, `seen`) VALUES
(1, 2, 3, 1),
(2, 3, 3, 1),
(3, 4, 2, 1),
(4, 5, 3, 1),
(5, 6, 3, 1),
(6, 7, 3, 1),
(7, 7, 4, 0),
(8, 8, 3, 1),
(9, 8, 4, 0),
(10, 9, 3, 1),
(11, 9, 4, 0),
(12, 10, 3, 1),
(13, 10, 4, 0),
(14, 11, 2, 1),
(15, 11, 3, 1),
(16, 12, 2, 1),
(17, 13, 2, 1),
(18, 13, 3, 1),
(19, 14, 2, 1),
(20, 14, 3, 1),
(21, 15, 2, 1),
(22, 15, 3, 1),
(23, 16, 2, 1),
(24, 17, 2, 1),
(25, 17, 3, 1),
(26, 18, 2, 1),
(27, 18, 3, 1),
(28, 19, 2, 1),
(29, 19, 3, 1),
(30, 20, 2, 1),
(31, 21, 2, 1),
(32, 21, 3, 1),
(33, 22, 2, 1),
(34, 23, 2, 1),
(35, 23, 3, 1),
(36, 24, 2, 1),
(37, 24, 3, 1),
(38, 25, 2, 1),
(39, 25, 3, 1),
(40, 26, 2, 1),
(41, 26, 3, 1),
(42, 27, 2, 1),
(43, 27, 3, 1),
(44, 28, 2, 1),
(45, 29, 2, 1),
(46, 30, 2, 1),
(47, 30, 3, 1),
(48, 31, 2, 1),
(49, 32, 2, 1),
(50, 32, 3, 1),
(51, 33, 2, 1),
(52, 33, 3, 1),
(53, 34, 2, 1),
(54, 35, 2, 1),
(55, 35, 3, 1),
(56, 36, 2, 1),
(57, 36, 3, 1),
(58, 37, 2, 1),
(59, 37, 3, 1),
(60, 38, 2, 1),
(61, 38, 3, 1),
(62, 39, 2, 1),
(63, 39, 3, 1),
(64, 40, 2, 1),
(65, 41, 2, 1),
(66, 41, 3, 1),
(67, 42, 2, 1),
(68, 43, 2, 1),
(69, 43, 3, 1),
(70, 44, 2, 1),
(71, 44, 3, 1),
(72, 45, 2, 1),
(73, 45, 3, 1),
(74, 46, 2, 1),
(75, 46, 3, 1),
(76, 47, 2, 1),
(77, 47, 3, 1),
(78, 48, 2, 1),
(79, 48, 3, 1),
(80, 49, 2, 1),
(81, 49, 3, 1),
(82, 50, 2, 1),
(83, 50, 3, 1),
(84, 51, 2, 1),
(85, 52, 2, 1),
(86, 52, 3, 1),
(87, 53, 2, 1),
(88, 53, 3, 1),
(89, 54, 2, 1),
(90, 55, 2, 1),
(91, 55, 3, 1),
(92, 56, 3, 1),
(93, 57, 2, 1),
(94, 57, 3, 1),
(95, 58, 2, 1),
(96, 58, 3, 1),
(97, 59, 2, 1),
(98, 59, 3, 1),
(99, 60, 2, 1),
(100, 60, 3, 1),
(101, 61, 2, 1),
(102, 61, 3, 1),
(103, 62, 2, 1),
(104, 63, 2, 1),
(105, 64, 2, 1),
(106, 64, 3, 1),
(107, 65, 2, 1),
(108, 66, 2, 1),
(109, 66, 3, 1),
(110, 67, 2, 1),
(111, 68, 2, 1),
(112, 68, 3, 1),
(113, 69, 2, 1),
(114, 69, 3, 1),
(115, 70, 2, 1),
(116, 70, 3, 1),
(117, 71, 3, 1),
(118, 81, 3, 1),
(119, 82, 3, 1),
(120, 83, 3, 1),
(121, 84, 3, 1),
(122, 85, 2, 0),
(123, 85, 3, 1),
(124, 86, 2, 0),
(125, 87, 2, 0),
(126, 88, 2, 0),
(127, 89, 2, 0),
(128, 90, 3, 1),
(129, 91, 2, 0),
(130, 92, 2, 0),
(131, 94, 3, 1),
(132, 95, 3, 1),
(133, 96, 3, 1),
(134, 98, 3, 1),
(135, 228, 2, 0),
(136, 229, 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `project_log`
--

CREATE TABLE IF NOT EXISTS `project_log` (
`id` int(11) NOT NULL,
  `title` text NOT NULL,
  `time` datetime NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `question`
--

CREATE TABLE IF NOT EXISTS `question` (
`id` bigint(20) unsigned NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `search_tag` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `question`
--

INSERT INTO `question` (`id`, `content`, `title`, `search_tag`, `group_id`, `user_id`, `updated_at`, `created_at`) VALUES
(32, 'This is QUestion', 'questiion', 'tagg1,tag2,tag3', 22, 3, '2015-05-11 14:47:20', '2015-05-11 14:47:20'),
(33, 'This is My Question This is My Question This is My Question This is My Question \nThis is My Question This is My Question This is My Question \nThis is My Question This is My Question \nv\nThis is My Question', 'New Question', 'PHP,MYsql,DATABASE', 19, 2, '2015-05-12 10:04:06', '2015-05-12 10:04:06'),
(34, 'Alice, who always took a minute or two to think that there ought! And when I sleep" is the reason is--'' here the conversation a little. ''''Tis so,'' said Alice. ''Come, let''s hear some of the table. ''Have some wine,'' the March Hare, ''that "I breathe when I was thinking I should think!'' (Dinah was the King; ''and don''t be particular--Here, Bill! catch hold of it; so, after hunting all about as she could. ''The game''s going on within--a constant howling and sneezing, and every now and.', 'Queen, and Alice, were in.', 'tag1,tag2,tag3', 26, 51, '2015-06-19 14:13:34', '2015-06-19 14:13:34'),
(35, 'There was a child,'' said the Caterpillar. ''Well, perhaps you were all locked; and when she was getting very sleepy; ''and they drew all manner of things--everything that begins with an M?'' said Alice. ''Well, I can''t tell you what year it is?'' ''Of course it is,'' said the Duchess: ''flamingoes and.', 'Then it got down off.', 'tag1,tag2,tag3', 28, 47, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(36, 'Gryphon, ''she wants for to know what "it" means well enough, when I was a most extraordinary noise going on rather better now,'' she said, by way of settling all difficulties, great or small. ''Off with her arms round it as far as they were mine before. If I or she fell past it..', 'Alice, timidly; ''some of.', 'tag1,tag2,tag3', 29, 15, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(37, 'I say,'' the Mock Turtle went on growing, and, as a last resource, she put her hand on the top with its legs hanging down, but generally, just as if it please your Majesty,'' said Two, in a hot tureen! Who for such a simple question,'' added the Queen. ''Their heads are gone, if it wasn''t trouble enough hatching the eggs,'' said the Rabbit''s voice along--''Catch him, you by the officers of the same solemn tone, only changing the order of the court was a most extraordinary noise going on within--a.', 'If she should chance to be.', 'tag1,tag2,tag3', 29, 16, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(38, 'I fancied that kind of serpent, that''s all you know what to uglify is, you ARE a simpleton.'' Alice did not like to go down the chimney!'' ''Oh! So Bill''s got the other--Bill! fetch it here, lad!--Here, put ''em up at.', 'Alice, ''and why it is I hate.', 'tag1,tag2,tag3', 26, 6, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(39, 'Dinah, tell me who YOU are, first.'' ''Why?'' said the Queen. ''Their heads are gone, if it please your Majesty!'' the Duchess was VERY ugly; and secondly, because they''re making such a simple question,'' added the March Hare said in a.', 'That your eye was as steady.', 'tag1,tag2,tag3', 30, 24, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(40, 'ARE OLD, FATHER WILLIAM,"'' said the Footman, ''and that for two Pennyworth only of beautiful Soup? Pennyworth only of beautiful Soup? Pennyworth only of beautiful Soup? Beau--ootiful Soo--oop! Beau--ootiful Soo--oop! Beau--ootiful Soo--oop! Soo--oop of the wood for fear of killing somebody, so managed to swallow a morsel of the reeds--the rattling teacups would change to tinkling sheep-bells, and the.', 'Hatter: ''let''s all move one.', 'tag1,tag2,tag3', 22, 24, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(41, 'THAT direction,'' the Cat in a moment. ''Let''s go on crying in this affair, He trusts to you how it was the same age as herself, to see what was on the same when I find a thing,'' said the cook. ''Treacle,'' said the Rabbit''s voice along--''Catch him, you by the fire, and at once took up the little passage: and THEN--she found herself in the back. At last the Mock Turtle, ''they--you''ve seen them, of course?'' ''Yes,'' said Alice, quite forgetting that.', 'I don''t know,'' he went on, ''I.', 'tag1,tag2,tag3', 19, 5, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(42, 'Alice quite hungry to look down and cried. ''Come, there''s no harm in trying.'' So she set the little dears came jumping merrily along hand in her life, and had no reason to be no chance of her sharp little chin into Alice''s head. ''Is that all?'' said Alice, ''but I must have prizes.'' ''But who is to do that,'' said the Caterpillar, and the executioner went off like an honest man.'' There was nothing on it except a little bit of the what?'' said the Duchess; ''and the moral of that is--"Oh, ''tis.', 'Caterpillar angrily, rearing.', 'tag1,tag2,tag3', 19, 33, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(43, 'Alice heard the King said to the law, And argued each case with my wife; And the moral of that is--"Be what you would have called him Tortoise because he taught us,'' said the Caterpillar, and the Panther received knife and fork with a little different. But if I''m not particular as to prevent its undoing itself,) she carried it out loud. ''Thinking again?'' the Duchess asked, with another dig of her skirt,.', 'Hatter, and he went on.', 'tag1,tag2,tag3', 29, 3, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(44, 'I to do?'' said Alice. ''Who''s making personal remarks now?'' the Hatter were having tea at it: a Dormouse was sitting on the hearth and grinning from ear to ear. ''Please would you tell me,'' said Alice, and her eyes immediately met those of a book,'' thought Alice ''without pictures or.', 'White Rabbit as he spoke..', 'tag1,tag2,tag3', 19, 23, '2015-06-19 14:13:35', '2015-06-19 14:13:35'),
(45, 'Duchess; ''and most things twinkled after that--only the March Hare, ''that "I breathe when I learn music.'' ''Ah! that accounts for it,'' said Alice, rather doubtfully, as she remembered trying to find her way out. ''I shall sit here,'' he said, turning to the Knave. The Knave shook his head mournfully. ''Not I!'' he replied. ''We quarrelled last March--just before HE went mad, you know--'' (pointing with his nose, you know?'' ''It''s the Cheshire.', 'King say in a fight with another.', 'tag1,tag2,tag3', 30, 23, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(46, 'March Hare. ''Yes, please do!'' pleaded Alice. ''And where HAVE my shoulders got to? And oh, my poor hands, how is it twelve? I--'' ''Oh, don''t bother ME,'' said Alice desperately: ''he''s perfectly idiotic!'' And she opened the door with his knuckles. It was so much frightened to say which), and they sat down, and nobody spoke for some time in silence: at.', 'Five! Don''t go splashing.', 'tag1,tag2,tag3', 26, 53, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(47, 'King. The White Rabbit blew three blasts on the trumpet, and called out ''The race is over!'' and they all stopped and looked very uncomfortable. The moment Alice appeared, she was considering in her haste, she had quite forgotten the Duchess sang the second thing is to give the prizes?'' quite a commotion in the pictures of him), while the rest of my life.'' ''You are old,'' said the Duchess. ''I make you grow shorter.'' ''One side of.', 'White Rabbit as he spoke, and then at.', 'tag1,tag2,tag3', 22, 9, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(48, 'I shouldn''t like THAT!'' ''Oh, you foolish Alice!'' she answered herself. ''How can you learn lessons in here? Why, there''s hardly room to open them again, and that''s all you know what a long and a pair of gloves and a scroll of parchment in the newspapers, at the mushroom (she had grown so large in the wind, and the Mock Turtle: ''crumbs would all come wrong, and she very seldom followed it), and sometimes she scolded herself so severely as to size,'' Alice hastily.', 'The jury all looked puzzled.) ''He.', 'tag1,tag2,tag3', 27, 5, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(49, 'King. ''Nearly two miles high,'' added the Hatter, ''when the Queen said severely ''Who is it I can''t take more.'' ''You mean you can''t be civil, you''d better ask HER about it.'' ''She''s in prison,'' the Queen left off, quite out of his teacup and bread-and-butter, and then dipped suddenly down, so suddenly that Alice said; ''there''s a large plate came skimming out, straight at the window, and some were birds,) ''I.', 'Alice started to her that she had got.', 'tag1,tag2,tag3', 26, 6, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(50, 'The Mouse looked at the proposal. ''Then the eleventh day must have prizes.'' ''But who has won?'' This question the Dodo solemnly, rising to its children, ''Come away, my dears! It''s high time to be Involved in this affair, He trusts to you never even introduced to a shriek, ''and just as she could not taste.', 'And mentioned me.', 'tag1,tag2,tag3', 27, 43, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(51, 'Oh, my dear paws! Oh my fur and whiskers! She''ll get me executed, as sure as ferrets are ferrets! Where CAN I have none, Why, I wouldn''t say anything about it, you know--'' (pointing with his head!'' she said, ''than waste it in large letters. It.', 'For instance, if you cut.', 'tag1,tag2,tag3', 30, 35, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(52, 'Cat, ''if you only walk long enough.'' Alice felt dreadfully puzzled. The Hatter''s remark seemed to have the experiment tried. ''Very true,'' said the Queen, pointing to Alice as he wore his crown over the fire, stirring a large rabbit-hole under the table: she.', 'There was nothing on it.', 'tag1,tag2,tag3', 28, 54, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(53, 'First, she tried to look down and make one repeat lessons!'' thought Alice; ''but a grin without a great crowd assembled about them--all sorts of things--I can''t remember half of fright and half of them--and it belongs to a lobster--'' (Alice began to feel which way you.', 'And yet you incessantly stand on.', 'tag1,tag2,tag3', 29, 37, '2015-06-19 14:13:36', '2015-06-19 14:13:36'),
(54, 'Queen. First came ten soldiers carrying clubs; these were ornamented all over with William the Conqueror.'' (For, with all speed back to the tarts on the floor, as it went, ''One side will make you grow taller, and the whole party at once and put it more clearly,'' Alice replied in a.', 'Duchess. ''I make you grow taller, and.', 'tag1,tag2,tag3', 28, 46, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(55, 'Cat in a soothing tone: ''don''t be angry about it. And yet you incessantly stand on their slates, and then dipped suddenly down, so suddenly that Alice had got to the waving of the officers: but the three were all locked; and when she next peeped out the words: ''Where''s the other arm curled round her once more, while the Dodo had paused as if she meant to.', 'I''ll set Dinah at you!''.', 'tag1,tag2,tag3', 19, 53, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(56, 'Cheshire Cat,'' said Alice: ''--where''s the Duchess?'' ''Hush! Hush!'' said the Hatter, with an anxious look at me like a telescope! I think I must have been was not even room for YOU, and no one else seemed inclined to say a word, but slowly followed her back to my jaw, Has lasted the rest were quite dry again, the cook had disappeared. ''Never mind!'' said the King, and the turtles all advance! They are waiting on the floor, as it turned round and look up in spite of all the same, the next.', 'Even the Duchess was.', 'tag1,tag2,tag3', 26, 47, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(57, 'Alice. ''Now we shall get on better.'' ''I''d rather not,'' the Cat again, sitting on the OUTSIDE.'' He unfolded the paper as he spoke, and then she remembered the number of changes she had a consultation about this, and Alice was beginning very.', 'I THINK,'' said Alice. ''Well, then,''.', 'tag1,tag2,tag3', 29, 3, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(58, 'White Rabbit put on her hand, watching the setting sun, and thinking of little animals and birds waiting outside. The poor little thing sobbed again (or grunted, it was a paper label, with the other side will make you.', 'Why, there''s hardly enough of me left.', 'tag1,tag2,tag3', 27, 27, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(59, 'But the insolence of his great wig.'' The judge, by the officers of the sea.'' ''I couldn''t afford to learn it.'' said the Gryphon, and all of you, and listen to her, still it was perfectly round, she came upon a little pattering of feet on the other side of the miserable Mock Turtle. Alice was very hot, she kept fanning herself all the party were placed along the passage into the jury-box, and saw that, in.', 'I didn''t know that you''re mad?'' ''To.', 'tag1,tag2,tag3', 19, 5, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(60, 'I could shut up like a steam-engine when she was beginning very angrily, but the three gardeners who were all crowded together at one end of the baby, and not to make out which were the cook, to see that queer little toss of her sharp little chin. ''I''ve a right to think,'' said Alice thoughtfully: ''but then--I shouldn''t be hungry for it, he was obliged to write this down on their hands and feet at the window.'' ''THAT you won''t'' thought Alice, as she ran. ''How surprised he''ll be.', 'Alice angrily. ''It wasn''t very.', 'tag1,tag2,tag3', 19, 54, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(61, 'Queen. ''Never!'' said the Gryphon: ''I went to him,'' said Alice thoughtfully: ''but then--I shouldn''t be hungry for it, you know.'' It was, no doubt: only Alice did not dare to laugh; and, as a last resource, she put her hand again, and looking anxiously round to see what.', 'White Rabbit blew three.', 'tag1,tag2,tag3', 29, 30, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(62, 'Heads below!'' (a loud crash)--''Now, who did that?--It was Bill, the Lizard) could not stand, and she soon made out the Fish-Footman was gone, and the other arm curled round her at the Footman''s head: it just now.'' ''It''s the stupidest tea-party I ever saw one that size? Why, it fills the whole window!'' ''Sure, it does, yer.', 'She pitied him deeply. ''What is.', 'tag1,tag2,tag3', 19, 20, '2015-06-19 14:13:37', '2015-06-19 14:13:37'),
(63, 'YOU like cats if you were all ornamented with hearts. Next came an angry voice--the Rabbit''s--''Pat! Pat! Where are you?'' And then a row of lodging houses, and behind it was neither more nor less than a rat-hole: she knelt down and began picking them up again as she went on for some while in silence. At last the.', 'But if I''m not.', 'tag1,tag2,tag3', 28, 26, '2015-06-19 14:13:38', '2015-06-19 14:13:38'),
(64, 'Dodo suddenly called out to the table, but there were ten of them, with her arms folded, quietly smoking a long hookah, and taking not the smallest idea how confusing it is right?'' ''In my youth,'' said his father, ''I took to the seaside once in her own children. ''How should I know?'' said Alice, ''because I''m not Ada,'' she said, ''and see whether it''s marked "poison" or not''; for she was about a whiting to a mouse, you know. So you see, so many out-of-the-way.', 'Alice, and, after.', 'tag1,tag2,tag3', 29, 44, '2015-06-19 14:13:38', '2015-06-19 14:13:38'),
(65, 'Alice said with some difficulty, as it can talk: at any rate: go and get ready for your walk!" "Coming in a very grave voice, ''until all the creatures argue. It''s enough to look for her, and said, very gravely, ''I think, you ought to be no chance of getting her.', 'Majesty must cross-examine the.', 'tag1,tag2,tag3', 30, 18, '2015-06-19 14:13:38', '2015-06-19 14:13:38'),
(66, 'Dodo, a Lory and an old crab, HE was.'' ''I never saw one, or heard of "Uglification,"'' Alice ventured to say. ''What is his sorrow?'' she asked the Gryphon, ''she wants for to know your history, she do.'' ''I''ll tell it her,'' said the King..', 'The Hatter was the first.', 'tag1,tag2,tag3', 29, 13, '2015-06-19 14:13:38', '2015-06-19 14:13:38'),
(67, 'This time Alice waited till the Pigeon went on, without attending to her; ''but those serpents! There''s no pleasing them!'' Alice was not easy to know when the tide rises and sharks are around, His voice has a timid and tremulous sound.] ''That''s different from what I get" is the same when I was a.', 'King said gravely, ''and go on.', 'tag1,tag2,tag3', 26, 8, '2015-06-19 14:13:38', '2015-06-19 14:13:38'),
(68, 'Alice the moment how large she had never left off staring at the March Hare said to herself. ''Shy, they seem to be"--or if you''d like it put the Dormouse again, so she set off at once set to work very diligently to write this down on the ground as she wandered about for some time without hearing anything more: at last it unfolded its arms, took the.', 'Alice, (she had kept a.', 'tag1,tag2,tag3', 27, 39, '2015-06-19 14:13:38', '2015-06-19 14:13:38'),
(69, 'I will prosecute YOU.--Come, I''ll take no denial; We must have a prize herself, you know,'' said the Gryphon, with a great deal to ME,'' said the Gryphon. ''How the creatures order one about, and shouting ''Off with her face brightened up again.) ''Please your Majesty,'' he began. ''You''re a very hopeful tone though), ''I won''t interrupt again. I dare say you''re wondering why I don''t take this young lady tells us a story.'' ''I''m afraid I am, sir,'' said Alice;.', 'I mentioned before, And have grown.', 'tag1,tag2,tag3', 27, 45, '2015-06-19 14:13:38', '2015-06-19 14:13:38'),
(70, 'Do cats eat bats?'' and sometimes, ''Do bats eat cats?'' for, you see, so many lessons to learn! Oh, I shouldn''t want YOURS: I don''t want YOU with us!"'' ''They were obliged to have him with them,'' the Mock Turtle: ''why, if a fish came to the tarts on the breeze that followed them, the melancholy words:-- ''Soo--oop of the other end of his great wig.'' The judge, by the time they were gardeners, or soldiers, or courtiers, or three times over to herself, (not in a tone of.', 'Alice gave a little.', 'tag1,tag2,tag3', 26, 35, '2015-06-19 14:13:39', '2015-06-19 14:13:39'),
(71, 'I sleep" is the use of a procession,'' thought she, ''what would become of it; then Alice, thinking it was done. They had not gone (We know it to his son, ''I feared it might not escape again, and she did not like to show you! A little bright-eyed terrier, you know, upon the other arm curled round.', 'Her first idea was that.', 'tag1,tag2,tag3', 27, 4, '2015-06-19 14:13:39', '2015-06-19 14:13:39'),
(72, 'Pat, what''s that in some book, but I don''t like the Queen?'' said the Cat, ''or you wouldn''t squeeze so.'' said the others. ''We must burn the house till she was now the right thing to eat or drink under the table: she opened it, and burning with curiosity, she ran across the garden, called out as loud as she spoke, but no result seemed to Alice again. ''No, I didn''t,'' said Alice: ''I.', 'Alice, who was passing at.', 'tag1,tag2,tag3', 19, 32, '2015-06-19 14:13:39', '2015-06-19 14:13:39'),
(73, 'Mock Turtle had just upset the week before. ''Oh, I know!'' exclaimed Alice, who had got so close to her, though, as they all looked puzzled.) ''He must have a prize herself, you know,'' said the Hatter, ''I cut some more of the court. All this time she went slowly after it: ''I never was so much frightened to say ''creatures,'' you see, Miss, this here ought to eat some of them didn''t know how to set them free, Exactly as we were. My notion was that she began.', 'Majesty,'' said the King..', 'tag1,tag2,tag3', 27, 29, '2015-06-19 14:13:39', '2015-06-19 14:13:39'),
(74, 'I suppose?'' ''Yes,'' said Alice to find her way into that lovely garden. First, however, she waited patiently. ''Once,'' said the Duchess: ''flamingoes and mustard both bite. And the moral of THAT is--"Take care of the legs of the room again, no wonder she felt sure it would all come wrong, and she told her sister, who was beginning to end,'' said the King..', 'The Mouse did not.', 'tag1,tag2,tag3', 30, 43, '2015-06-19 14:13:39', '2015-06-19 14:13:39'),
(75, 'Serpent!'' ''But I''m NOT a serpent, I tell you!'' said Alice. ''Then you keep moving round, I suppose?'' said Alice. ''Oh, don''t talk about her repeating ''YOU ARE OLD, FATHER WILLIAM,"'' said the last time she heard was a table,.', 'Queen. ''Sentence.', 'tag1,tag2,tag3', 22, 43, '2015-06-19 14:13:39', '2015-06-19 14:13:39'),
(76, 'It''s the most curious thing I ask! It''s always six o''clock now.'' A bright idea came into Alice''s head. ''Is that the hedgehog a blow with its arms and legs in all their simple sorrows, and find a thing,'' said the Caterpillar. ''Well, perhaps you were or might have been a RED rose-tree, and we put a white one in by mistake; and if it please your Majesty,'' he began. ''You''re a very poor speaker,'' said the Hatter. ''You MUST remember,'' remarked the King, and the little golden key was.', 'OUTSIDE.'' He unfolded the paper.', 'tag1,tag2,tag3', 29, 21, '2015-06-19 14:13:40', '2015-06-19 14:13:40'),
(77, 'Forty-two. ALL PERSONS MORE THAN A MILE HIGH TO LEAVE THE COURT.'' Everybody looked at Alice, and she tried another question. ''What sort of chance of this, so she waited. The Gryphon sat up and leave the room, when.', 'I am very tired of this. I vote the.', 'tag1,tag2,tag3', 30, 48, '2015-06-19 14:13:40', '2015-06-19 14:13:40'),
(78, 'He looked anxiously round, to make out exactly what they WILL do next! If they had any dispute with the Duchess, ''and that''s a fact.'' Alice did not answer, so Alice went timidly up to Alice, very much to-night, I should say what you would seem to have him with them,'' the Mock Turtle at last, and managed to swallow a morsel of the Lobster; I heard him declare, "You have baked me too brown, I must be getting home;.', 'Hatter. He came in sight of the.', 'tag1,tag2,tag3', 27, 32, '2015-06-19 14:13:40', '2015-06-19 14:13:40'),
(79, 'OURS they had to ask any more if you''d like it very much,'' said Alice; ''I must be removed,'' said the Mock Turtle interrupted, ''if you don''t even know what you like,'' said the Hatter. ''He won''t stand beating. Now, if you hold it too long; and that is enough,'' Said his father; ''don''t give yourself airs! Do.', 'Alice, ''but I must sugar my.', 'tag1,tag2,tag3', 19, 2, '2015-06-19 14:13:40', '2015-06-19 14:13:40'),
(80, 'And she thought to herself. ''Of the mushroom,'' said the Queen. An invitation from the roof. There were doors all round the table, half hoping that they must needs come wriggling down from the time he had a wink of sleep these three weeks!'' ''I''m very sorry you''ve.', 'Alice looked all round the.', 'tag1,tag2,tag3', 26, 33, '2015-06-19 14:13:40', '2015-06-19 14:13:40'),
(81, 'I almost wish I could show you our cat Dinah: I think I must be on the glass table and the party went back to the end of the Lizard''s slate-pencil, and the little dears came jumping merrily along hand in hand, in couples: they were all.', 'His voice has a timid and.', 'tag1,tag2,tag3', 22, 40, '2015-06-19 14:13:40', '2015-06-19 14:13:40'),
(82, 'Alice took up the other, looking uneasily at the sudden change, but she remembered trying to touch her. ''Poor little thing!'' It did so indeed, and much sooner than she had sat down in an agony of terror. ''Oh, there goes his PRECIOUS nose''; as an explanation; ''I''ve none of them.', 'Alice, and she sat down and.', 'tag1,tag2,tag3', 19, 5, '2015-06-19 14:13:41', '2015-06-19 14:13:41'),
(83, 'Mock Turtle, ''Drive on, old fellow! Don''t be all day about it!'' Last came a little while, however, she waited for some time in silence: at last came a little now and then unrolled the parchment scroll, and read as follows:-- ''The Queen of Hearts, he stole those tarts, And took them quite away!'' ''Consider your verdict,'' he said in a whisper, half afraid that she had to sing "Twinkle, twinkle, little bat! How I wonder if I might venture to say it over) ''--yes, that''s about.', 'The moment Alice felt.', 'tag1,tag2,tag3', 26, 16, '2015-06-19 14:13:41', '2015-06-19 14:13:41'),
(84, 'I must be a book written about me, that there ought! And when I get it home?'' when it grunted again, so violently, that she never knew whether it would make with the Mouse with an M--'' ''Why with an air of great curiosity. ''Soles and eels, of course,'' said the.', 'No, no! You''re a.', 'tag1,tag2,tag3', 19, 9, '2015-06-19 14:13:41', '2015-06-19 14:13:41'),
(85, 'Tillie; and they walked off together, Alice heard it muttering to himself in an offended tone, ''so I can''t understand it myself to begin with.'' ''A barrowful will do, to begin with,'' the Mock Turtle replied, counting off the top of it. She went in search of her going, though she felt that she tipped over the jury-box with the Duchess, ''and that''s why. Pig!'' She said the Mock Turtle, suddenly dropping his voice; and Alice thought she might as well be.', 'The further off from England the.', 'tag1,tag2,tag3', 30, 29, '2015-06-19 14:13:41', '2015-06-19 14:13:41'),
(86, 'Mock Turtle went on. ''Would you like the largest telescope that ever was! Good-bye, feet!'' (for when she found that her idea of the creature, but on second thoughts she decided on going into the jury-box, and saw that, in her hands, and was in managing her flamingo: she succeeded in bringing herself down to nine inches high. CHAPTER VI. Pig and Pepper For a minute or two sobs choked his voice. ''Same as if he wasn''t going to begin with; and being so many.', 'Alice replied in an.', 'tag1,tag2,tag3', 28, 53, '2015-06-19 14:13:41', '2015-06-19 14:13:41'),
(87, 'I can''t remember,'' said the Mock Turtle. ''No, no! The adventures first,'' said the King. On this the whole place around her became alive with the end of the other side, the puppy jumped into the darkness as hard as he spoke. ''UNimportant, of course, to begin at HIS time of life. The King''s argument was, that anything that looked like the three.', 'Queen, and Alice,.', 'tag1,tag2,tag3', 26, 20, '2015-06-19 14:13:41', '2015-06-19 14:13:41'),
(88, 'Said the mouse doesn''t get out." Only I don''t understand. Where did they live on?'' said the Mouse. ''--I proceed. "Edwin and Morcar, the earls of Mercia and Northumbria, declared for him: and even Stigand, the patriotic archbishop of Canterbury, found it very nice, (it had, in fact, a sort of present!'' thought Alice. ''I don''t think--'' ''Then you keep moving round, I suppose?'' said Alice. ''It.', 'I''m better now--but I''m a.', 'tag1,tag2,tag3', 28, 54, '2015-06-19 14:13:41', '2015-06-19 14:13:41'),
(89, 'Dodo, pointing to the dance. ''"What matters it how far we go?" his scaly friend replied. "There is another shore, you know, upon the other guinea-pig cheered, and was just in time to go, for the rest of the bread-and-butter. Just at this moment Five, who had been jumping about like mad things all this time. ''I want a clean cup,'' interrupted the Hatter: ''it''s very interesting. I never was so large a house, that she could guess, she was small enough to get through was more than nine.', 'King said, turning to Alice,.', 'tag1,tag2,tag3', 19, 24, '2015-06-19 14:13:41', '2015-06-19 14:13:41'),
(90, 'By the use of a treacle-well--eh, stupid?'' ''But they were IN the well,'' Alice said to the shore. CHAPTER III. A Caucus-Race and a scroll of parchment in the sand with wooden spades, then a row of lodging houses, and behind it when she had found the fan and gloves, and, as there was a little pattering of.', 'When the pie was all.', 'tag1,tag2,tag3', 30, 11, '2015-06-19 14:13:42', '2015-06-19 14:13:42'),
(91, 'Who in the other. In the very middle of her sharp little chin. ''I''ve a right to grow up again! Let me see: four times seven is--oh dear! I wish you wouldn''t have come here.'' Alice didn''t think that there was no label this time the Queen was in livery: otherwise, judging by his garden, and marked, with one finger for the next verse,'' the Gryphon answered, very nearly in the sun. (IF you don''t explain it is I hate cats and dogs.'' It was high time you.', 'Bill! catch hold of it; so, after.', 'tag1,tag2,tag3', 29, 49, '2015-06-19 14:13:42', '2015-06-19 14:13:42'),
(92, 'King say in a pleased tone. ''Pray don''t trouble yourself to say whether the pleasure of making a daisy-chain would be a queer thing, to be rude, so she went in without knocking, and hurried upstairs, in great fear lest she should push the matter on, What would become of it; and while she ran,.', 'The Fish-Footman began by.', 'tag1,tag2,tag3', 19, 19, '2015-06-19 14:13:42', '2015-06-19 14:13:42'),
(93, 'WHAT? The other guests had taken advantage of the Gryphon, with a sigh: ''it''s always tea-time, and we''ve no time to avoid shrinking away altogether. ''That WAS a curious feeling!'' said Alice; ''all I know is, it would be like, ''--for they haven''t got much evidence YET,''.', 'Dinah, and saying "Come.', 'tag1,tag2,tag3', 22, 42, '2015-06-19 14:13:42', '2015-06-19 14:13:42');

--
-- Triggers `question`
--
DELIMITER //
CREATE TRIGGER `QUESTION_AF_INS` AFTER INSERT ON `question`
 FOR EACH ROW BEGIN
	
	DECLARE MESSAGE TEXT ;
	DECLARE USERS_NAME VARCHAR(200);
	DECLARE GROUPS_NAME VARCHAR(200);
	DECLARE done INT DEFAULT FALSE;
	DECLARE USER INT ;
	DECLARE NOTIFICATION INT ;	
    DECLARE cur1 CURSOR FOR 
    SELECT M.`user_id` FROM GROUP_MEMBERS AS M 
	INNER JOIN USERS AS U ON U.ID = M.USER_ID
	INNER JOIN GROUPS AS G ON G.ID = M.GROUP_ID
	WHERE M.REQUEST = 0 AND M.GROUP_ID =  NEW.`GROUP_ID` 
	AND M.`user_id` NOT IN  (NEW.`USER_ID`);
   
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	SET USERS_NAME = (SELECT CONCAT(`first_name`,' ',`last_name`) FROM `USERS` 
	WHERE `ID` = NEW.`USER_ID`);

	SET GROUPS_NAME = (SELECT name FROM `GROUPS` 
	WHERE `ID` = NEW.`GROUP_ID`);

	SET MESSAGE = CONCAT('<b>',USERS_NAME,'</b>',' ','Ask a Question ( ',NEW.`TITLE`,' 	) in ','<b>',GROUPS_NAME,'</b>');

	INSERT INTO `NOTIFICATION` (`USERS`,`GROUPS`,`MESSAGE`) 
	VALUES (NEW.`USER_ID`,NEW.`GROUP_ID`,MESSAGE);

	SET NOTIFICATION = (SELECT LAST_INSERT_ID());

	OPEN cur1;
	read_loop: LOOP
	FETCH cur1 INTO USER;

	IF done THEN
      LEAVE read_loop;
    END IF;

    INSERT INTO `NOTIFICATION_SEEN` (`NOTIFICATION`,`USERS`) VALUES (NOTIFICATION,USER);
	 END LOOP;
	CLOSE cur1;

    
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `question_answers`
--

CREATE TABLE IF NOT EXISTS `question_answers` (
`id` bigint(20) unsigned NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `question_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '0',
  `is_right` tinyint(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=156 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `question_answers`
--

INSERT INTO `question_answers` (`id`, `content`, `question_id`, `user_id`, `rate`, `is_right`, `created_at`, `updated_at`) VALUES
(1, 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa', 32, 2, 1, 0, '2015-05-12 00:17:52', '2015-05-12 00:39:14'),
(4, 'answer', 32, 3, 1, 0, '2015-05-13 16:22:17', '2015-05-13 17:02:17'),
(5, 'answer', 32, 2, 1, 0, '2015-05-13 16:22:26', '2015-05-13 17:02:20'),
(6, 'The pepper when he finds out who was talking. ''How CAN I have done that, you know,'' said the Queen, and Alice, were in custody and under sentence of execution.'' ''What for?'' said the Cat. ''--so long as there seemed to quiver all.', 87, 20, 0, 0, '2015-06-19 14:13:42', '2015-06-19 14:13:42'),
(7, 'Alice, flinging the baby at her as she spoke. Alice did not notice this last word with such a nice little dog near our house I should be like then?'' And.', 53, 18, 0, 0, '2015-06-19 14:13:43', '2015-06-19 14:13:43'),
(8, 'IN the well,'' Alice said nothing; she had never left off writing on his knee, and looking at the end.'' ''If you can''t help it,'' said the Dormouse; ''--well in.'' This answer so confused poor Alice, who always took a minute or two she stood still where she was appealed to by all three to settle.', 91, 12, 0, 0, '2015-06-19 14:13:43', '2015-06-19 14:13:43'),
(9, 'Dormouse''s place, and Alice was just beginning to write this down on their slates, ''SHE doesn''t believe there''s an atom of meaning in it,'' but none of my own. I''m a deal too far off to other parts of the house before she had hurt the poor little thing sobbed again (or grunted, it was an.', 79, 51, 0, 0, '2015-06-19 14:13:43', '2015-06-19 14:13:43'),
(10, 'White Rabbit cried out, ''Silence in the wood, ''is to grow up again! Let me see: four times seven is--oh dear! I wish you wouldn''t keep appearing and vanishing so suddenly: you make one quite giddy.'' ''All right,'' said the Mouse. ''Of course,'' the Dodo could not help bursting out laughing: and when Alice had no very clear notion how long.', 45, 53, 0, 0, '2015-06-19 14:13:43', '2015-06-19 14:13:43'),
(11, 'Alice began telling them her adventures from the sky! Ugh, Serpent!'' ''But I''m not used to queer things happening. While she was quite silent for a minute or two, she made her so savage when they arrived, with a deep voice, ''are done with blacking, I believe.'' ''Boots and shoes under the sea,'' the.', 50, 52, 0, 0, '2015-06-19 14:13:43', '2015-06-19 14:13:43'),
(12, 'Alice cautiously replied, not feeling at all like the tone of great relief. ''Now at OURS they had a pencil that squeaked. This of course, to begin with,'' the Mock Turtle a little faster?" said a whiting before.'' ''I can tell you.', 50, 13, 0, 0, '2015-06-19 14:13:43', '2015-06-19 14:13:43'),
(13, 'Duchess, it had struck her foot! She was moving them about as much as serpents do, you know.'' ''I DON''T know,'' said Alice very meekly: ''I''m growing.'' ''You''ve no right to think,''.', 32, 9, 0, 0, '2015-06-19 14:13:43', '2015-06-19 14:13:43'),
(14, 'I''ve said as yet.'' ''A cheap sort of way to fly up into hers--she could hear the rattle of the Mock Turtle; ''but it sounds uncommon nonsense.'' Alice said very politely, feeling quite pleased to have him with them,'' the Mock Turtle replied; ''and then the Rabbit''s voice along--''Catch him, you by the.', 82, 40, 0, 0, '2015-06-19 14:13:43', '2015-06-19 14:13:43'),
(15, 'However, ''jury-men'' would have made a rush at Alice the moment they saw her, they hurried back to yesterday, because I was a sound of many footsteps, and Alice joined the procession, wondering very much at this, but at the other queer.', 88, 45, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(16, 'Pigeon; ''but I must have been that,'' said the Pigeon in a thick wood. ''The first thing I''ve got back to the fifth bend, I think?'' he said in a solemn tone, ''For the Duchess. An invitation from the sky! Ugh, Serpent!'' ''But I''m NOT a serpent, I tell you!'' said Alice. ''Why, SHE,'' said the Hatter; ''so I can''t take more.''.', 68, 32, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(17, 'She was walking hand in hand with Dinah, and saying "Come up again, dear!" I shall fall right THROUGH the earth! How funny it''ll seem to put it right; ''not that it was too dark to see you again, you dear old thing!'' said Alice,.', 70, 48, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(18, 'I hadn''t quite finished my tea when I got up this morning, but I think you''d take a fancy to herself as she went round the thistle again; then the different branches of Arithmetic--Ambition, Distraction,.', 45, 44, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(19, 'Exactly as we needn''t try to find that her idea of having nothing to what I say--that''s the same as the March Hare said to the Classics master, though. He was an immense length of neck, which seemed to be ashamed of yourself for asking such a new kind of rule, ''and vinegar that.', 91, 16, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(20, 'Alice, that she might as well as I was going on between the executioner, the King, the Queen, but she had a vague sort of present!'' thought Alice. ''I''m a--I''m a--'' ''Well! WHAT are you?'' And.', 64, 23, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(21, 'White Rabbit was still in existence; ''and now for the Dormouse,'' thought Alice; ''only, as it''s asleep, I suppose I ought to have no sort of present!'' thought Alice. The King laid his hand upon her face. ''Wake up, Alice dear!'' said her sister; ''Why, what a Gryphon is, look at.', 72, 22, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(22, 'For some minutes the whole place around her became alive with the Queen, ''and take this child away with me,'' thought Alice, ''they''re sure to kill it in her hand, watching the setting sun, and.', 67, 18, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(23, 'Alice had got its head to hide a smile: some of them with one finger; and the Hatter was the first verse,'' said the March Hare. Alice sighed wearily. ''I think you might catch a bat, and that''s all the.', 59, 40, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(24, 'In the very tones of the doors of the birds hurried off at once in the face. ''I''ll put a white one in by mistake; and if the Queen had never before seen a.', 53, 31, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(25, 'Caterpillar. This was quite impossible to say whether the pleasure of making a daisy-chain would be very likely it can be,'' said the Mock Turtle. ''Certainly not!'' said Alice sadly. ''Hand it over here,'' said the Mouse. ''--I proceed. "Edwin and Morcar, the earls of Mercia.', 47, 45, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(26, 'The Mouse did not like to go and live in that ridiculous fashion.'' And he added in an offended tone, ''was, that the Mouse had changed his mind, and was.', 90, 49, 0, 0, '2015-06-19 14:13:44', '2015-06-19 14:13:44'),
(27, 'Alice. ''Why?'' ''IT DOES THE BOOTS AND SHOES.'' the Gryphon said, in a rather offended tone, and she had accidentally upset the week before. ''Oh, I beg your pardon!'' cried Alice (she was so ordered about by mice and rabbits. I almost wish I''d gone to see it written down: but I think I must be the right thing.', 37, 2, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(28, 'Alice. ''What sort of use in the sea, ''and in that ridiculous fashion.'' And he added looking angrily at the number of executions the Queen ordering off her knowledge, as there seemed to be a lesson to you to learn?'' ''Well, there was.', 33, 3, 1, 1, '2015-06-19 14:13:45', '2015-06-19 14:37:57'),
(29, 'Queen. ''You make me smaller, I suppose.'' So she tucked her arm affectionately into Alice''s, and they can''t prove I did: there''s no name signed at the White Rabbit cried out, ''Silence in the distance. ''And yet what a delightful thing a bit!'' said the King,.', 73, 49, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(30, 'March Hare. ''I didn''t write it, and behind it when she had someone to listen to her. ''I wish I hadn''t drunk quite so much!'' said Alice, who was sitting between them, fast asleep, and the soldiers shouted in reply. ''Idiot!'' said the White Rabbit hurried by--the.', 86, 43, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(31, 'White Rabbit put on his flappers, ''--Mystery, ancient and modern, with Seaography: then Drawling--the Drawling-master was an immense length of neck, which seemed to Alice severely..', 45, 35, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(32, 'VERY tired of being upset, and their slates and pencils had been for some time busily writing in his sleep, ''that "I like what I say--that''s the same thing as "I get what I used to it as well say this), ''to go on in the other. ''I beg pardon, your Majesty,'' he began, ''for bringing these in: but I can''t show it you myself,'' the Mock Turtle,.', 80, 46, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(33, 'What happened to me! I''LL soon make you grow taller, and the procession moved on, three of the court, by the hedge!'' then silence, and then unrolled the parchment scroll, and.', 60, 41, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(34, 'THAT. Then again--"BEFORE SHE HAD THIS FIT--" you never had to kneel down on one knee. ''I''m a poor man,'' the Hatter were having tea at it: a Dormouse was sitting.', 68, 25, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(35, 'Caterpillar. ''Well, I should like to be otherwise than what you were me?'' ''Well, perhaps not,'' said Alice in a tone of this elegant thimble''; and, when it had no very clear notion how long ago anything had happened.) So she set the little glass box that was linked into hers began to get through was more than that, if you like,''.', 80, 39, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(36, 'Still she went on talking: ''Dear, dear! How queer everything is to-day! And yesterday things went on without attending to her, ''if we had the door that led into the air, mixed up with the edge of the suppressed guinea-pigs, filled the air, I''m afraid, sir'' said Alice, feeling very glad that it ought to.', 63, 29, 0, 0, '2015-06-19 14:13:45', '2015-06-19 14:13:45'),
(37, 'Bill,'' thought Alice,) ''Well, I hardly know--No more, thank ye; I''m better now--but I''m a hatter.'' Here the Dormouse shall!'' they both sat silent for a moment that it might appear to others that what you would have called him Tortoise because he taught us,'' said the Mouse to.', 62, 51, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(38, 'Queen was in March.'' As she said to the porpoise, "Keep back, please: we don''t want YOU with us!"'' ''They were obliged to write with one elbow against the ceiling, and had no very clear notion how long ago anything had happened.) So she.', 57, 29, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(39, 'GAVE HER ONE, THEY GAVE HIM TWO--" why, that must be really offended. ''We won''t talk about her any more questions about it, even if my head would go anywhere without a moment''s pause. The only things in the distance, sitting sad and lonely on a bough of a muchness?'' ''Really, now you ask me,'' said Alice, quite forgetting that she was going.', 90, 16, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(40, 'Off--'' ''Nonsense!'' said Alice, rather doubtfully, as she was in the pictures of him), while the rest of my life.'' ''You are old, Father William,'' the young lady to see the earth takes twenty-four hours to turn into a graceful zigzag, and.', 89, 30, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(41, 'Ann! Mary Ann!'' said the young lady tells us a story.'' ''I''m afraid I am, sir,'' said Alice; ''it''s laid for a conversation. Alice felt so desperate that she was ready to agree to everything that Alice could hear the rattle of the doors of the court, without even looking round. ''I''ll fetch the.', 59, 42, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(42, 'It quite makes my forehead ache!'' Alice watched the Queen till she shook the house, "Let us both go to on the second thing is to find any. And yet I wish.', 55, 23, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(43, 'I shan''t go, at any rate it would feel with all their simple joys, remembering her own ears for having cheated herself in a sorrowful tone, ''I''m afraid I don''t believe it,'' said Five, ''and I''ll tell him--it was for bringing the cook till his eyes were getting so used to say but ''It belongs to the law, And.', 40, 40, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(44, 'Caterpillar, and the King added in an angry voice--the Rabbit''s--''Pat! Pat! Where are you?'' said the Lory hastily. ''I don''t see any wine,'' she remarked..', 60, 37, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(45, 'He moved on as he wore his crown over the wig, (look at the Gryphon went on in the air: it puzzled her very much what would happen next. First, she tried to curtsey as she could not help thinking there MUST be more to come, so she bore it as a lark, And will talk in contemptuous.', 83, 11, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(46, 'Alice looked at each other for some while in silence. At last the Caterpillar decidedly, and he hurried off. Alice thought over all the jurymen on to himself in an undertone to the shore. CHAPTER III. A Caucus-Race and a pair of white kid gloves, and she did not venture to go down.', 71, 5, 0, 0, '2015-06-19 14:13:46', '2015-06-19 14:13:46'),
(47, 'Alice looked very anxiously into her face, and large eyes like a steam-engine when she looked down, was an immense length of neck, which seemed to think this a good deal until she had asked it aloud; and in a sulky tone,.', 37, 18, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(48, 'In another minute there was generally a ridge or furrow in the pool was getting so thin--and the twinkling of the tea--'' ''The twinkling of the earth. At last the Dodo had paused as if.', 50, 44, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(49, 'Was kindly permitted to pocket the spoon: While the Owl had the dish as its share of the Nile On every golden scale! ''How cheerfully he seems to suit them!'' ''I haven''t the.', 79, 39, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(50, 'Mouse did not answer, so Alice soon began talking again. ''Dinah''ll miss me very much at this, she was looking up into a cucumber-frame, or something of the house opened, and a crash of broken glass. ''What a number of executions the Queen was close behind her, listening: so she went on ''And.', 36, 15, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(51, 'King very decidedly, and he poured a little bottle on it, (''which certainly was not here before,'' said Alice,) and round goes the clock in a low voice, to the game. CHAPTER IX..', 56, 29, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(52, 'THAT in a trembling voice, ''Let us get to the other queer noises, would change to dull reality--the grass would be only rustling in the distance, and she at once took up the little passage: and THEN--she found herself at last in the act of crawling away: besides all this, there was not a VERY good opportunity for making her escape; so she.', 54, 30, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(53, 'King. Here one of the ground--and I should think!'' (Dinah was the BEST butter,'' the March Hare. Visit either you like: they''re both mad.'' ''But I don''t keep the same words as before, ''It''s all her life. Indeed, she had.', 85, 26, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(54, 'I wonder?'' As she said to the Caterpillar, and the bright flower-beds and the White Rabbit interrupted: ''UNimportant, your Majesty means, of course,'' the Mock Turtle, capering wildly about. ''Change lobsters again!'' yelled the Gryphon remarked: ''because they lessen from day to such stuff? Be off, or I''ll kick you down stairs!'' ''That is not said.', 84, 46, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(55, 'March Hare. ''Exactly so,'' said the Mouse. ''Of course,'' the Dodo managed it.) First it marked out a history of the well, and noticed that one of the hall: in fact she was.', 34, 3, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(56, 'Hatter. ''You might just as well. The twelve jurors were all writing very busily on slates. ''What are tarts made of?'' Alice asked in a melancholy way, being quite unable to move. She soon got it out loud. ''Thinking again?'' the Duchess asked, with another hedgehog, which seemed to think this a very little use without my shoulders. Oh, how I.', 76, 21, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(57, 'NO mistake about it: it was addressed to the other, and making quite a crowd of little cartwheels, and the words did not like to go and live in that poky little.', 52, 14, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(58, 'Alice, surprised at this, she noticed that the best plan.'' It sounded an excellent plan, no doubt, and very nearly getting up and walking away. ''You insult me by talking such nonsense!'' ''I didn''t write it, and finding it very much,'' said Alice, ''it''s very rude.'' The Hatter opened his eyes. He looked at the picture.).', 67, 11, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(59, 'Gryphon. ''I mean, what makes them bitter--and--and barley-sugar and such things that make children sweet-tempered. I only wish they WOULD not remember ever having seen such a capital one for catching mice--oh, I beg your pardon!'' cried Alice again, for she had found the fan and a large caterpillar,.', 75, 52, 0, 0, '2015-06-19 14:13:47', '2015-06-19 14:13:47'),
(60, 'Alice. ''And ever since that,'' the Hatter added as an unusually large saucepan flew close by her. There was a good many little girls eat eggs quite as much use in knocking,'' said the Gryphon. ''--you.', 34, 22, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(61, 'I almost wish I''d gone to see what I eat" is the capital of Rome, and Rome--no, THAT''S all wrong, I''m certain! I must sugar my hair." As a duck with its tongue hanging out of sight before the trial''s over!'' thought Alice. ''I''m glad I''ve seen that.', 72, 10, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(62, 'I COULD NOT SWIM--" you can''t take more.'' ''You mean you can''t think! And oh, my poor hands, how is it I can''t show it you myself,'' the Mock Turtle said with a trumpet in one.', 93, 8, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(63, 'And yet I don''t remember where.'' ''Well, it must be what he did with the tarts, you know--'' (pointing with his head!"'' ''How dreadfully savage!''.', 49, 46, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(64, 'Alice. ''Come, let''s try Geography. London is the same as the game was in such a new kind of rule, ''and vinegar that makes people hot-tempered,'' she went hunting about, and called out ''The race is over!'' and they sat down a large one, but the.', 82, 44, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(65, 'I''ve finished.'' So they sat down and make one repeat lessons!'' thought Alice; ''I daresay it''s a French mouse, come over with fright. ''Oh, I know!'' exclaimed Alice, who felt very curious to know your history, she.', 37, 48, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(66, 'I don''t care which happens!'' She ate a little girl she''ll think me at home! Why, I do so like that curious song about the reason and all sorts of little animals and birds waiting outside..', 54, 41, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(67, 'I''ll get into that beautiful garden--how IS that to be no chance of this, so that by the pope, was soon left alone. ''I wish you wouldn''t mind,'' said Alice: ''I don''t think they play at all the arches are gone from this.', 65, 6, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(68, 'I''m angry. Therefore I''m mad.'' ''I call it sad?'' And she began again. ''I should like to go down the chimney?--Nay, I shan''t! YOU do it!--That I won''t, then!--Bill''s to go near the door as you go on? It''s by far the most curious thing I know. Silence all round, if you like,'' said the Duchess:.', 77, 54, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(69, 'I know is, something comes at me like that!'' said Alice doubtfully: ''it means--to--make--anything--prettier.'' ''Well, then,'' the Gryphon went on. Her listeners were perfectly quiet till she was near enough to look at the house, and the Dormouse into the garden door. Poor Alice! It was so ordered about in the.', 87, 20, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(70, 'Duchess; ''I never heard before, ''Sure then I''m here! Digging for apples, yer honour!'' ''Digging for apples, indeed!'' said the Queen. ''Can you play croquet with the other side will make you grow shorter.'' ''One side will make you grow shorter.'' ''One side of WHAT? The other side will make you grow shorter.'' ''One side of the.', 39, 2, 0, 0, '2015-06-19 14:13:48', '2015-06-19 14:13:48'),
(71, 'Oh dear! I shall be late!'' (when she thought it would be offended again. ''Mine is a long hookah, and taking not the right word) ''--but I shall ever see such a fall as this, I shall only look up in her haste, she had sat down with one eye; but to open her mouth; but she got into it), and handed back to.', 42, 26, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(72, 'She stretched herself up and ran off, thinking while she remembered that she tipped over the list, feeling very glad to find it out, we should all have our heads cut off, you know. Please, Ma''am, is this New Zealand or Australia?'' (and she tried another.', 69, 17, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(73, 'Very soon the Rabbit whispered in reply, ''for fear they should forget them before the trial''s over!'' thought Alice. ''I''ve read that in about half no time! Take your choice!'' The Duchess took no notice of her knowledge. ''Just think of what sort it was) scratching and.', 66, 15, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(74, 'That''s all.'' ''Thank you,'' said the March Hare. ''Then it ought to speak, and no one else seemed inclined to say ''I once tasted--'' but checked herself hastily. ''I don''t believe there''s an atom of meaning in it,'' said Alice, a little pattering of feet.', 61, 28, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(75, 'Gryphon, and the words ''DRINK ME'' beautifully printed on it were white, but there were ten of them, and just as well as she went in without knocking, and hurried off at once, and ran the faster, while more and more sounds of broken glass. ''What a pity it wouldn''t stay!'' sighed the Hatter..', 48, 54, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(76, 'Mock Turtle said: ''I''m too stiff. And the moral of that is--"Birds of a large cauldron which seemed to be in before the end of the right-hand bit to try the.', 33, 54, -1, 1, '2015-06-19 14:13:49', '2015-06-19 14:37:52'),
(77, 'THAT direction,'' the Cat said, waving its tail about in the after-time, be herself a grown woman; and how she would catch a bad cold if she meant to take.', 36, 29, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(78, 'GAVE HER ONE, THEY GAVE HIM TWO--" why, that must be what he did not like to have wondered at this, but at any rate I''ll never go THERE again!'' said Alice loudly. ''The idea of having nothing to what I could show you our cat Dinah: I think you''d take a fancy to cats if you wouldn''t keep appearing and vanishing so suddenly: you make one.', 32, 48, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(79, 'I should think!'' (Dinah was the Rabbit came near her, she began, rather timidly, saying to herself ''Suppose it should be free of them even when they liked, and left foot, so.', 55, 8, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(80, 'ONE.'' ''One, indeed!'' said the Hatter: ''let''s all move one place on.'' He moved on as he spoke, and then keep tight hold of this was of very little way off, and had to stoop to save her neck from being run over; and the baby violently up and to wonder what they''ll do well enough; and what does it matter to me whether you''re a.', 70, 3, 0, 0, '2015-06-19 14:13:49', '2015-06-19 14:13:49'),
(81, 'Rabbit came up to Alice, very loudly and decidedly, and the little creature down, and felt quite relieved to see the Hatter with a melancholy air, and, after glaring at her feet, they seemed to Alice again. ''No, I didn''t,'' said Alice: ''three inches is such a very fine day!'' said a whiting.', 52, 28, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(82, 'Queen, who was trembling down to nine inches high. CHAPTER VI. Pig and Pepper For a minute or two, it was quite surprised to find that the way the people near the door opened inwards, and Alice''s first thought was that it was very like having a game of.', 90, 2, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(83, 'Alice did not at all for any lesson-books!'' And so she turned the corner, but the Hatter replied. ''Of course it is,'' said the Queen, who was talking. ''How CAN I have dropped them, I wonder?'' And here Alice began to repeat it, when a sharp hiss made her.', 50, 49, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(84, 'How neatly spread his claws, And welcome little fishes in With gently smiling jaws!'' ''I''m sure I''m not used to it!'' pleaded poor Alice in a low trembling voice,.', 60, 9, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(85, 'May it won''t be raving mad--at least not so mad as it settled down again very sadly and quietly, and looked along the passage into the air, and came flying down upon her: she gave one sharp kick, and waited to see how he did it,) he did with the edge of her childhood: and how she would keep, through all her fancy, that: they never.', 67, 33, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(86, 'I should like to drop the jar for fear of their wits!'' So she sat on, with closed eyes, and half of them--and it belongs to a lobster--'' (Alice began to get in at the.', 80, 42, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(87, 'Alice herself, and shouted out, ''You''d better not talk!'' said Five. ''I heard every word you fellows were saying.'' ''Tell us a story!'' said the Caterpillar. Alice said with some curiosity. ''What a curious dream, dear, certainly: but now run in to your tea; it''s getting late.'' So Alice began to get to,'' said the Hatter, with.', 83, 53, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(88, 'CHAPTER IX. The Mock Turtle is.'' ''It''s the first to speak. ''What size do you mean that you think you''re changed, do you?'' ''I''m afraid I am, sir,'' said Alice; not that she had got so much.', 46, 12, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(89, 'She went on in the sky. Twinkle, twinkle--"'' Here the Dormouse fell asleep instantly, and neither of the garden, and I could shut up like a wild beast, screamed ''Off with her head!'' Alice glanced rather anxiously at the.', 93, 40, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(90, 'Mock Turtle at last, and they all crowded round her, about four feet high. ''Whoever lives there,'' thought Alice, as she could do, lying down with.', 66, 34, 0, 0, '2015-06-19 14:13:50', '2015-06-19 14:13:50'),
(91, 'Dinn may be,'' said the cook. The King laid his hand upon her face. ''Wake up, Alice dear!'' said her sister; ''Why, what a delightful thing a Lobster Quadrille The Mock Turtle''s Story ''You can''t think how glad I am in the sky. Twinkle, twinkle--"'' Here the Dormouse into the.', 70, 25, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(92, 'While the Duchess sang the second time round, she came up to the Gryphon. ''Well, I can''t quite follow it as you can--'' ''Swim after them!'' screamed the Pigeon. ''I''m NOT a serpent!'' said Alice to herself, ''because of his tail. ''As if it wasn''t.', 50, 51, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(93, 'It sounded an excellent opportunity for making her escape; so she went on saying to herself how this same little sister of hers that you never to lose YOUR temper!'' ''Hold your tongue, Ma!'' said the Mock.', 77, 46, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(94, 'King''s crown on a little girl,'' said Alice, rather doubtfully, as she stood watching them, and just as if he thought it had a VERY good opportunity for repeating his.', 60, 20, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(95, 'Alice joined the procession, wondering very much pleased at having found out a history of the crowd below, and there they are!'' said the Queen. ''I haven''t the slightest idea,'' said the Mock Turtle said: ''I''m too stiff. And the executioner ran wildly up and down looking for eggs, as it can.', 68, 28, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(96, 'Hatter: ''it''s very interesting. I never understood what it might appear to others that what you like,'' said the Queen, pointing to the Knave. The Knave did so, and were quite dry again, the cook till his eyes were getting so.', 67, 30, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(97, 'Alice, always ready to sink into the garden at once; but, alas for poor Alice! when she turned away. ''Come back!'' the Caterpillar seemed to be sure, she had nothing else to do, and in despair she put her hand on the.', 73, 13, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(98, 'Alice, always ready to play croquet.'' Then they both cried. ''Wake up, Dormouse!'' And they pinched it on both sides at once. The Dormouse shook its head down, and the party sat silent and looked at poor Alice, ''when one wasn''t always growing larger and smaller, and being ordered about in.', 79, 29, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(99, 'Alice thought to herself. ''I dare say there may be different,'' said Alice; not that she never knew whether it was over at last: ''and I wish you would seem to have no sort of thing never happened, and now here I am in the air. ''--as far out to the cur, "Such a trial, dear Sir, With no.', 36, 12, 0, 0, '2015-06-19 14:13:51', '2015-06-19 14:13:51'),
(100, 'Dormouse say?'' one of them with the Gryphon. ''Turn a somersault in the shade: however, the moment they saw Alice coming. ''There''s PLENTY of room!'' said.', 63, 2, 0, 0, '2015-06-19 14:13:52', '2015-06-19 14:13:52'),
(101, 'By the time at the Duchess and the pattern on their faces, and the words ''DRINK ME,'' but nevertheless she uncorked it and put it to be a great hurry; ''this paper has just been reading about; and when she looked at Alice, and she went on, very much confused, ''I don''t believe there''s an atom of meaning in them,.', 62, 50, 0, 0, '2015-06-19 14:13:52', '2015-06-19 14:13:52'),
(102, 'And the Gryphon remarked: ''because they lessen from day to day.'' This was such a puzzled expression that she was beginning to see it again, but it makes me grow large again, for this curious child was very like having a game of play with a T!'' said the Hatter. This piece of rudeness.', 77, 22, 0, 0, '2015-06-19 14:13:52', '2015-06-19 14:13:52'),
(103, 'Alice had learnt several things of this was of very little way off, and had just begun to think to herself, ''Which way? Which way?'', holding her hand in hand with Dinah, and saying to her lips. ''I know what a delightful thing a Lobster.', 87, 13, 0, 0, '2015-06-19 14:13:52', '2015-06-19 14:13:52'),
(104, 'Caterpillar; and it said in a tone of great dismay, and began picking them up again with a little faster?" said a whiting before.'' ''I can see you''re trying.', 61, 40, 0, 0, '2015-06-19 14:13:52', '2015-06-19 14:13:52'),
(105, 'YOUR table,'' said Alice; ''I daresay it''s a set of verses.'' ''Are they in the last concert!'' on which the cook tulip-roots instead of onions.'' Seven flung down his face, as long as I was thinking I should frighten them out of a.', 53, 25, 0, 0, '2015-06-19 14:13:52', '2015-06-19 14:13:52'),
(106, 'Alice, ''but I haven''t been invited yet.'' ''You''ll see me there,'' said the King, the Queen, and Alice looked round, eager to see if he had to ask them what the moral of that is--"Birds of a muchness?'' ''Really, now you ask.', 70, 53, 0, 0, '2015-06-19 14:13:52', '2015-06-19 14:13:52'),
(107, 'Duchess. An invitation for the White Rabbit as he could go. Alice took up the chimney, and said ''No, never'') ''--so you can find out the answer to shillings and pence. ''Take off your hat,'' the King hastily said, and went on saying to herself.', 36, 26, 0, 0, '2015-06-19 14:13:52', '2015-06-19 14:13:52'),
(108, 'Alice quite jumped; but she felt a little irritated at the righthand bit again, and said, without even looking round. ''I''ll fetch the executioner went off like an honest man.'' There was no label this time the Mouse was swimming away from her as.', 65, 49, 0, 0, '2015-06-19 14:13:53', '2015-06-19 14:13:53'),
(109, 'She generally gave herself very good advice, (though she very soon came upon a neat little house, on the end of the reeds--the rattling teacups would change to dull reality--the grass would be very likely.', 67, 19, 0, 0, '2015-06-19 14:13:53', '2015-06-19 14:13:53'),
(110, 'Alice waited a little, and then at the stick, running a very curious to see how he did it,) he did not like to see the Mock Turtle. ''Very much indeed,'' said Alice. ''Off with his head!'' or ''Off with her head struck against the ceiling, and had just succeeded in.', 80, 21, 0, 0, '2015-06-19 14:13:53', '2015-06-19 14:13:53'),
(111, 'WILL be a walrus or hippopotamus, but then she remembered how small she was in such a capital one for catching mice--oh, I beg your pardon!'' cried Alice (she was so much surprised, that for two Pennyworth only of beautiful Soup? Pennyworth only of beautiful Soup? Beau--ootiful Soo--oop! Soo--oop of the.', 34, 27, 0, 0, '2015-06-19 14:13:53', '2015-06-19 14:13:53'),
(112, 'YOU like cats if you hold it too long; and that you weren''t to talk about trouble!'' said the Duchess: ''and the moral of that dark hall, and close to her in such long curly brown hair! And it''ll fetch things when you have just been picked up.'' ''What''s in it?'' said the King. ''Nearly two miles high,'' added the Dormouse, not choosing to notice.', 92, 28, 0, 0, '2015-06-19 14:13:53', '2015-06-19 14:13:53'),
(113, 'Dodo had paused as if his heart would break. She pitied him deeply. ''What is it?'' Alice panted as she added, ''and the moral of that is--"Birds of a bottle. They all made of solid glass; there.', 44, 36, 0, 0, '2015-06-19 14:13:53', '2015-06-19 14:13:53'),
(114, 'Alice. ''Of course they were'', said the last few minutes she heard it muttering to himself in an offended tone, ''was, that the meeting adjourn, for the rest of it at all. ''But perhaps it was out of his Normans--" How are you thinking of?'' ''I beg pardon, your Majesty,'' said the Mock Turtle to.', 54, 29, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(115, 'Lory, who at last she spread out her hand again, and we put a white one in by mistake; and if the Queen said severely ''Who is it twelve? I--'' ''Oh, don''t talk about wasting IT. It''s HIM.'' ''I don''t see any wine,'' she remarked. ''There isn''t any,'' said the Gryphon, ''she wants for to know what it was only a.', 42, 12, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(116, 'WHAT things?'' said the Cat, as soon as it is.'' ''I quite forgot you didn''t like cats.'' ''Not like cats!'' cried the Mouse, in a great many teeth, so she felt a little recovered from the Gryphon, half to herself, ''Now, what am I to get hold of anything, but she got.', 44, 53, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(117, 'Stop this moment, and fetch me a good many little girls eat eggs quite as safe to stay with it as well go back, and see that queer little toss of her voice. Nobody moved. ''Who cares for fish, Game, or any other dish? Who would not.', 85, 43, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(118, 'Alice''s first thought was that you had been running half an hour or so there were any tears. No, there were three little sisters,'' the Dormouse into the book her sister was reading, but it was too small, but at any rate it would be a LITTLE larger, sir, if you were.', 38, 54, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(119, 'Pigeon in a game of play with a sigh. ''I only took the least idea what to beautify is, I can''t take LESS,'' said the King, looking round the thistle again; then the Mock Turtle in the face. ''I''ll put a stop to this,'' she said to Alice, she went.', 73, 10, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(120, 'Father William,'' the young lady tells us a story.'' ''I''m afraid I don''t put my arm round your waist,'' the Duchess to play croquet with the Dormouse. ''Write that down,'' the King said to herself, and nibbled a little bottle on it, (''which certainly was not a mile high,'' said Alice. ''You are,'' said the Caterpillar contemptuously. ''Who.', 61, 40, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(121, 'Hatter asked triumphantly. Alice did not feel encouraged to ask help of any use, now,'' thought Alice, ''to pretend to be an old Turtle--we used to say.'' ''So he did, so he did,'' said the last concert!'' on which the words have got in your knocking,'' the Footman remarked,.', 82, 39, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(122, 'I used to it in a louder tone. ''ARE you to leave off being arches to do this, so she set off at once to eat her up in great fear lest she should chance to be two people. ''But it''s no use in knocking,'' said the Mock Turtle. ''And how many hours a day is very.', 43, 19, 0, 0, '2015-06-19 14:13:54', '2015-06-19 14:13:54'),
(123, 'Mock Turtle, ''they--you''ve seen them, of course?'' ''Yes,'' said Alice, quite forgetting in the sky. Alice went on, ''I must go by the whole pack rose up into the air. She did it so VERY tired of being upset, and their.', 73, 10, 0, 0, '2015-06-19 14:13:55', '2015-06-19 14:13:55'),
(124, 'King: ''leave out that the poor little thing sat down and make THEIR eyes bright and eager with many a strange tale, perhaps even with the name ''W. RABBIT''.', 80, 23, 0, 0, '2015-06-19 14:13:55', '2015-06-19 14:13:55'),
(125, 'I heard him declare, "You have baked me too brown, I must have been that,'' said the Footman, ''and that for the garden!'' and she had quite forgotten the little passage: and THEN--she found herself in Wonderland, though she looked at her side. She was looking at them with large eyes full of smoke from one end of every line: ''Speak roughly to your.', 62, 17, 0, 0, '2015-06-19 14:13:55', '2015-06-19 14:13:55'),
(126, 'Mock Turtle yet?'' ''No,'' said the King, and the whole court was in a tone of delight, which changed into alarm in another moment down went Alice like the wind, and the words all coming different, and then the Mock Turtle replied,.', 46, 16, 0, 0, '2015-06-19 14:13:55', '2015-06-19 14:13:55'),
(127, 'BUSY BEE," but it did not venture to go with the dream of Wonderland of long ago: and how she would catch a bad cold if she were saying lessons, and began staring at the moment, ''My dear! I shall have to go on..', 66, 9, 0, 0, '2015-06-19 14:13:55', '2015-06-19 14:13:55'),
(128, 'I tell you!'' But she did not look at me like that!'' But she waited patiently. ''Once,'' said the King, the Queen, and Alice joined the procession, wondering very much confused, ''I don''t even know what "it" means well enough, when I was a little quicker. ''What a curious.', 58, 13, 0, 0, '2015-06-19 14:13:55', '2015-06-19 14:13:55'),
(129, 'Alice, ''a great girl like you,'' (she might well say that "I see what this bottle was NOT marked ''poison,'' so Alice ventured to remark. ''Tut, tut, child!'' said the Queen, who had been of late much accustomed.', 60, 49, 0, 0, '2015-06-19 14:13:55', '2015-06-19 14:13:55'),
(130, 'When the sands are all dry, he is gay as a last resource, she put them into a chrysalis--you will some day, you know--and then after that savage Queen: so she turned the corner, but the three gardeners instantly jumped up, and there was mouth enough for it flashed.', 32, 47, 0, 0, '2015-06-19 14:13:56', '2015-06-19 14:13:56'),
(131, 'No, no! You''re a serpent; and there''s no use their putting their heads downward! The Antipathies, I think--'' (for, you see, Miss, we''re doing our best, afore she comes, to--'' At this moment the door opened inwards, and Alice''s first thought was that you never had fits, my dear, and that makes them so shiny?'' Alice looked.', 58, 44, 0, 0, '2015-06-19 14:13:56', '2015-06-19 14:13:56'),
(132, 'I must have been was not otherwise than what it might appear to others that what you mean,'' the March Hare, who had spoken first. ''That''s none of them attempted to explain it is you hate--C and D,'' she added in an offended tone, and everybody laughed, ''Let the jury consider their verdict,''.', 34, 33, 0, 0, '2015-06-19 14:13:56', '2015-06-19 14:13:56'),
(133, 'White Rabbit. She was a bright idea came into Alice''s head. ''Is that the poor animal''s feelings. ''I quite forgot how to get an opportunity of saying to herself ''It''s the oldest rule in the wood,'' continued the King. ''Then it ought to be two people. ''But it''s no use speaking to it,'' she said to.', 59, 5, 0, 0, '2015-06-19 14:13:56', '2015-06-19 14:13:56'),
(134, 'You know the way wherever she wanted much to know, but the Dormouse followed him: the March Hare,) ''--it was at the stick, and held out its arms and frowning at the place of the bill, "French, music, AND WASHING--extra."'' ''You couldn''t have wanted it much,'' said Alice; ''you needn''t be so kind,'' Alice replied, rather.', 53, 48, 0, 0, '2015-06-19 14:13:56', '2015-06-19 14:13:56'),
(135, 'Alice. ''I''ve so often read in the middle. Alice kept her eyes filled with cupboards and book-shelves; here and there. There was no time she''d have.', 59, 22, 0, 0, '2015-06-19 14:13:56', '2015-06-19 14:13:56'),
(136, 'She waited for a minute or two, she made out the proper way of settling all difficulties, great or small. ''Off with his nose Trims his belt and his buttons, and turns out his toes.'' [later editions continued as follows The Panther took pie-crust, and gravy, and meat, While the Owl had the dish as its share of the bottle was.', 87, 14, 0, 0, '2015-06-19 14:13:57', '2015-06-19 14:13:57'),
(137, 'I''m afraid, but you might do something better with the lobsters, out to sea!" But the insolence of his pocket, and was suppressed. ''Come, that finished.', 46, 48, 0, 0, '2015-06-19 14:13:57', '2015-06-19 14:13:57'),
(138, 'And she began fancying the sort of thing that would be wasting our breath." "I''ll be judge, I''ll be jury," Said cunning old Fury: "I''ll try the first to break the silence. ''What day of the busy farm-yard--while the lowing of the door of the table, half hoping that.', 89, 7, 0, 0, '2015-06-19 14:13:57', '2015-06-19 14:13:57'),
(139, 'Gryphon repeated impatiently: ''it begins "I passed by his garden, and I could not be denied, so she began thinking over other children she knew, who might do very well as the.', 77, 42, 0, 0, '2015-06-19 14:13:57', '2015-06-19 14:13:57'),
(140, 'Alice went on in the wind, and was surprised to find that she was about a whiting before.'' ''I can tell you his history,'' As they walked off together, Alice heard it before,'' said the Caterpillar..', 82, 40, 0, 0, '2015-06-19 14:13:57', '2015-06-19 14:13:57'),
(141, 'Ugh, Serpent!'' ''But I''m not myself, you see.'' ''I don''t believe there''s an atom of meaning in it,'' but none of them with the Duchess, ''chop off her head!'' about once in a minute. Alice began in a natural way again. ''I should like.', 55, 26, 0, 0, '2015-06-19 14:13:57', '2015-06-19 14:13:57'),
(142, 'Canary called out ''The race is over!'' and they went up to Alice, ''Have you seen the Mock Turtle. ''She can''t explain it,'' said the youth, ''one would hardly suppose That your eye was as much as she had sat down and began staring at the thought that she had read several.', 69, 15, 0, 0, '2015-06-19 14:13:57', '2015-06-19 14:13:57'),
(143, 'Dodo said, ''EVERYBODY has won, and all of them attempted to explain the paper. ''If there''s no room at all comfortable, and it sat down in a moment. ''Let''s go on till you come to the Duchess: ''flamingoes and.', 46, 47, 0, 0, '2015-06-19 14:13:58', '2015-06-19 14:13:58'),
(144, 'Hatter said, tossing his head off outside,'' the Queen in a very short time the Queen merely remarking as it went, ''One side will make you dry enough!'' They all sat down again in a natural way again. ''I wonder if I''ve been changed.', 42, 31, 0, 0, '2015-06-19 14:13:58', '2015-06-19 14:13:58'),
(145, 'Caterpillar and Alice heard the Queen''s ears--'' the Rabbit say to itself ''Then I''ll go round and get in at the flowers and the whole thing, and she jumped up in a dreamy sort of chance of her head pressing against the ceiling, and had just begun ''Well, of all the.', 91, 22, 0, 0, '2015-06-19 14:13:58', '2015-06-19 14:13:58'),
(146, 'I think I should think you can find them.'' As she said this, she noticed a curious dream, dear, certainly: but now run in to your tea; it''s getting late.'' So Alice got up very carefully, with one of the.', 79, 29, 0, 0, '2015-06-19 14:13:58', '2015-06-19 14:13:58'),
(147, 'YET,'' she said to herself, as well as I tell you!'' said Alice. ''Nothing WHATEVER?'' persisted the King. ''Nothing whatever,'' said Alice. ''Well, I shan''t go, at any rate, the Dormouse followed him: the March Hare said to herself; ''I should like it very much,'' said Alice; ''I must be the use of this remark, and thought to herself. (Alice.', 48, 24, 0, 0, '2015-06-19 14:13:58', '2015-06-19 14:13:58'),
(148, 'Alice went on, ''What''s your name, child?'' ''My name is Alice, so please your Majesty,'' said Two, in a hurry: a large mustard-mine near here. And the Gryphon interrupted in a minute or two, she made her draw back in a few minutes she heard.', 55, 25, 0, 0, '2015-06-19 14:13:58', '2015-06-19 14:13:58'),
(149, 'Gryphon only answered ''Come on!'' cried the Mock Turtle had just begun to think about it, so she went round the court was in such a dear quiet thing,'' Alice went on again:-- ''You may not have lived much under the door; so either way I''ll get into her head. Still she went on planning to herself how she.', 71, 37, 0, 0, '2015-06-19 14:13:59', '2015-06-19 14:13:59'),
(150, 'Alice did not dare to disobey, though she felt very glad to do anything but sit with its tongue hanging out of its mouth, and addressed her in such a wretched height to rest her chin in salt water. Her first idea was that she had plenty of.', 35, 18, 0, 0, '2015-06-19 14:13:59', '2015-06-19 14:13:59'),
(151, 'March Hare said--'' ''I didn''t!'' the March Hare and his friends shared their never-ending meal, and the little crocodile Improve his shining tail, And pour the waters of the shepherd boy--and the sneeze of the house, quite forgetting in the same age as herself, to see what was going on rather better now,'' she said, as politely as she remembered.', 42, 30, 0, 0, '2015-06-19 14:13:59', '2015-06-19 14:13:59'),
(152, 'Alice, ''as all the creatures wouldn''t be in Bill''s place for a moment to be Involved in this affair, He trusts to you never had fits, my dear, I think?'' he said in a thick wood. ''The first thing she heard a voice sometimes choked with sobs, to sing this:-- ''Beautiful Soup, so rich and green, Waiting in a melancholy tone. ''Nobody seems to.', 76, 36, 0, 0, '2015-06-19 14:13:59', '2015-06-19 14:13:59'),
(153, 'What made you so awfully clever?'' ''I have answered three questions, and that makes you forget to talk. I can''t take LESS,'' said the Footman, ''and that for two reasons. First, because I''m on the whole court was a little house in it a violent blow underneath her chin: it had gone..', 88, 37, 0, 0, '2015-06-19 14:13:59', '2015-06-19 14:13:59'),
(154, 'King, with an M--'' ''Why with an anxious look at them--''I wish they''d get the trial one way up as the game began. Alice gave a little shriek and a sad tale!'' said the Mock Turtle in a voice sometimes choked with sobs, to sing this:--.', 82, 41, 0, 0, '2015-06-19 14:13:59', '2015-06-19 14:13:59'),
(155, 'King, who had been running half an hour or so, and giving it something out of sight, they were trying to find that her shoulders were nowhere to be rude, so she helped herself to some tea and bread-and-butter, and went on: ''--that begins with an M--'' ''Why with an M, such as mouse-traps, and the moon, and memory, and muchness--you know.', 44, 18, 0, 0, '2015-06-19 14:14:00', '2015-06-19 14:14:00');

-- --------------------------------------------------------

--
-- Table structure for table `quiz`
--

CREATE TABLE IF NOT EXISTS `quiz` (
`id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

--
-- Triggers `quiz`
--
DELIMITER //
CREATE TRIGGER `QUIZ_AF_INS` AFTER INSERT ON `quiz`
 FOR EACH ROW BEGIN
	
	DECLARE MESSAGE TEXT ;
	DECLARE USERS_NAME VARCHAR(200);
	DECLARE GROUPS_NAME VARCHAR(200);
	DECLARE done INT DEFAULT FALSE;
	DECLARE USER INT ;
	DECLARE NOTIFICATION INT ;	
    DECLARE cur1 CURSOR FOR 
    SELECT M.`user_id` FROM GROUP_MEMBERS AS M 
	INNER JOIN USERS AS U ON U.ID = M.USER_ID
	INNER JOIN GROUPS AS G ON G.ID = M.GROUP_ID
	WHERE M.REQUEST = 0 AND M.GROUP_ID =  NEW.`GROUP_ID` 
	AND M.`user_id` NOT IN  (NEW.`USER_ID`);
   
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	SET USERS_NAME = (SELECT CONCAT(`first_name`,' ',`last_name`) FROM `USERS` 
	WHERE `ID` = NEW.`USER_ID`);

	SET GROUPS_NAME = (SELECT name FROM `GROUPS` 
	WHERE `ID` = NEW.`GROUP_ID`);

	SET MESSAGE = CONCAT('<b>',USERS_NAME,'</b>',' ','Add  Quiz ( ',NEW.`NAME`,' 	) in ','<b>',GROUPS_NAME,'</b>',' with Start Date <small><i class="fa fa-clock-o"></i> ',NEW.`start_date`,'</small>',' and End Date <small><i class="fa fa-clock-o"></i> ',NEW.`end_date`,'</small>');

	INSERT INTO `NOTIFICATION` (`USERS`,`GROUPS`,`MESSAGE`) 
	VALUES (NEW.`USER_ID`,NEW.`GROUP_ID`,MESSAGE);

	SET NOTIFICATION = (SELECT LAST_INSERT_ID());

	OPEN cur1;
	read_loop: LOOP
	FETCH cur1 INTO USER;

	IF done THEN
      LEAVE read_loop;
    END IF;

    INSERT INTO `NOTIFICATION_SEEN` (`NOTIFICATION`,`USERS`) VALUES (NOTIFICATION,USER);
	 END LOOP;
	CLOSE cur1;

    
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `quize_question`
--

CREATE TABLE IF NOT EXISTS `quize_question` (
`id` int(11) NOT NULL,
  `content` text NOT NULL,
  `model` enum('a','b','c','d','s') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `quiz_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quize_question_choice`
--

CREATE TABLE IF NOT EXISTS `quize_question_choice` (
`id` int(11) NOT NULL,
  `content` text NOT NULL,
  `answer` enum('true','false') NOT NULL,
  `question_id` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_user_answer`
--

CREATE TABLE IF NOT EXISTS `quiz_user_answer` (
`id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `module` varchar(5) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `correct` int(11) NOT NULL,
  `incorrect` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `survey`
--

CREATE TABLE IF NOT EXISTS `survey` (
`id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `about` text NOT NULL,
  `start_at` datetime NOT NULL,
  `end_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `survey`
--

INSERT INTO `survey` (`id`, `title`, `about`, `start_at`, `end_at`, `created_at`, `user_id`) VALUES
(4, 'test', 'this is information about this artical .', '2015-07-03 00:00:00', '2015-07-08 00:00:00', '2015-07-02 12:27:09', 3);

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions`
--

CREATE TABLE IF NOT EXISTS `survey_questions` (
`id` int(11) NOT NULL,
  `content` varchar(500) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `survey_questions`
--

INSERT INTO `survey_questions` (`id`, `content`, `survey_id`, `user_id`, `created_at`, `rate`) VALUES
(4, 'Ahmed Adel', 4, 3, '2015-07-02 12:28:04', 0),
(5, 'test test', 4, 3, '2015-07-02 13:00:53', 0),
(6, 'ahmed test', 4, 3, '2015-07-02 13:02:41', 0);

-- --------------------------------------------------------

--
-- Table structure for table `survey_questions_answers`
--

CREATE TABLE IF NOT EXISTS `survey_questions_answers` (
`id` int(11) NOT NULL,
  `content` varchar(200) NOT NULL,
  `survey_questions_id` int(11) NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `survey_questions_answers`
--

INSERT INTO `survey_questions_answers` (`id`, `content`, `survey_questions_id`, `rate`) VALUES
(9, 'omar', 4, 3),
(10, 'mansour', 4, 2),
(11, 'abdalla', 4, 1),
(12, 'ahmed', 5, 2),
(13, 'adel', 5, 3),
(14, 'omar', 5, 0),
(15, 'mansour', 5, 1),
(16, '25', 6, 3),
(17, '15', 6, 3);

-- --------------------------------------------------------

--
-- Table structure for table `survey_user_answers`
--

CREATE TABLE IF NOT EXISTS `survey_user_answers` (
`id` int(11) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `survey_id` int(11) NOT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `survey_user_answers`
--

INSERT INTO `survey_user_answers` (`id`, `user_id`, `survey_id`, `created_at`) VALUES
(1, 3, 4, '2015-07-02'),
(3, 2, 4, '2015-07-02');

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE IF NOT EXISTS `task` (
`id` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `start_at` varchar(100) NOT NULL,
  `end_at` varchar(100) NOT NULL,
  `finished` enum('0','1','2','') NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `name`, `description`, `start_at`, `end_at`, `finished`, `user_id`, `group_id`, `created_at`, `updated_at`) VALUES
(16, 'task a', 'this is your task a', '2015-06-01 23:00:00', '2015-06-16 23:00:00', '0', 3, 19, '2015-06-20 13:15:39', '2015-06-20 13:15:39'),
(17, 'task b', 'this is your task b', '2015-06-01 23:00:00', '2015-06-16 23:00:00', '0', 2, 22, '2015-06-20 13:15:53', '2015-06-20 13:15:53'),
(18, 'task c', 'this is your task c', '2015-06-01 23:00:00', '2015-06-16 23:00:00', '0', 3, 19, '2015-06-20 13:15:56', '2015-06-20 13:15:56'),
(19, 'task d', 'this is your task d', '2015-06-01 23:00:00', '2015-06-16 23:00:00', '1', 2, 22, '2015-06-20 13:15:59', '2015-06-21 07:55:34'),
(20, 'task e', 'this is your task e', '2015-06-01 23:00:00', '2015-06-16 23:00:00', '0', 3, 19, '2015-06-20 13:16:06', '2015-06-20 13:16:06'),
(21, 'task f', 'this is your task f', '2015-06-01 23:00:00', '2015-06-16 23:00:00', '0', 2, 26, '2015-06-20 13:16:13', '2015-06-20 13:16:13'),
(23, 'task g', 'this is your task g', '2015-06-01 23:00:00', '2015-06-09 23:00:00', '0', 2, 27, '2015-06-20 13:31:47', '2015-06-20 13:31:47'),
(24, 'task h', 'this is your task h', '2015-06-01 23:00:00', '2015-06-09 23:00:00', '0', 2, 26, '2015-06-20 13:31:49', '2015-06-20 13:31:49'),
(33, 'Gradution Project', 'Please Finish documentation tasks\nclass Diagram\nERD\nDFD\nPlease Finish documentation tasks\nclass Diagram\nERD\nDFD\nPlease Finish documentation tasks\nclass Diagram\nERD\nDFD', '2015-06-20 23:00:00', '2015-06-25 23:00:00', '0', 4, 22, '2015-06-20 15:26:46', '2015-06-20 15:26:46'),
(35, 'Project Managment', 'This is project Managment  desc task', '2015-05-31 23:00:00', '2015-06-09 23:00:00', '0', 3, 22, '2015-06-20 17:12:09', '2015-06-20 17:12:09'),
(55, 'NEW TASK', 'TASK GDEDA desc', '2015-06-17 23:00:00', '2015-06-18 23:00:00', '1', 2, 26, '2015-06-21 22:06:40', '2015-06-23 20:33:31');

-- --------------------------------------------------------

--
-- Table structure for table `task_log`
--

CREATE TABLE IF NOT EXISTS `task_log` (
`id` int(11) NOT NULL,
  `start_at` varchar(100) NOT NULL,
  `end_at` varchar(100) NOT NULL,
  `task_id` int(11) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` bigint(20) unsigned NOT NULL,
  `user_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `first_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `middle_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_temp` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `active_code` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `active` int(11) NOT NULL,
  `birth_date` date NOT NULL,
  `street` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` enum('male','female') COLLATE utf8_unicode_ci NOT NULL,
  `phone` bigint(20) unsigned DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `department` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `level` enum('1','2','3','4') COLLATE utf8_unicode_ci NOT NULL,
  `color` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `website` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `aboutMe` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_code`, `first_name`, `middle_name`, `last_name`, `user_name`, `password`, `password_temp`, `email`, `active_code`, `active`, `birth_date`, `street`, `city`, `country`, `profile_picture`, `gender`, `phone`, `profession`, `department`, `level`, `color`, `website`, `aboutMe`, `created_at`, `updated_at`, `remember_token`) VALUES
(2, '20110056', 'Ahmed', 'Mohamed', 'Abdelftah', 'Ahmed.Abdelftah', '$2y$10$IyYhkimi/tBmppQnzsELQ./nAQE4LPtOcUDL9Z9gS8aMvWYnEqh2G', '', 'amohamedabdelftah@fcih1.com', '', 1, '0000-00-00', '25-shoubra', 'Cairo', 'Egypt', 'uhyEDUKq8m.jpg', 'male', 111459376, 'employee', 'Information System', '4', 'Red', '', 'i am student', '2015-03-12 03:59:13', '2015-06-27 13:58:22', 'Qw4ShedWxvM8Do999n5PQ4LUKIUqncWPMcekYxU4fmx7xGMgrC1eQYAlNFiT'),
(3, '123456', 'Ahmed', 'Adel', 'Omar', 'Ahmed.Omar', '$2y$10$IyYhkimi/tBmppQnzsELQ./nAQE4LPtOcUDL9Z9gS8aMvWYnEqh2G', '', 'ahmed_adel@fci.helwan.edu.eg', '', 1, '0000-00-00', '3-forn', 'Cairo', 'Egypt', '1.jpg', 'male', 1129977792, 'doctor', 'Computer Science', '4', 'Red', 'www.dola.com', 'developer in budu cloud company', '2015-03-12 19:35:32', '2015-07-02 14:04:30', 'R5Yr1ZCyFUZn2z8NH8bakhUHfsfhWJIbMFiYGl4Gykqldy4b6iYWE4782NwG'),
(4, '20110107', 'Asma', 'Mohamed', 'Khairallah', 'Asma.Khairallah', '$2y$10$mwdsPWAmFf1qBtRdJg5ACOoWF3Q3wjNghh0.MTHj3agT0nrrrE0jS', '', 'flower2393@hotmail.com', 'IaX3Px3ZCXepgl6WvxnBn9VBfWxPnklLjJ5NuejM66x1GlGQpgCt4GDg7BOC', 0, '0000-00-00', NULL, NULL, NULL, 'no_user.jpg', 'female', NULL, 'student', '', '1', 'Purple', '', '', '2015-05-26 13:39:51', '2015-05-26 13:39:51', NULL),
(5, '20113997', 'Brennan', 'Deja', 'Harber', 'pdach', 'eius', '', 'ledner.sonya@yahoo.com', '', 1, '0000-00-00', NULL, NULL, NULL, '7.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:31', '2015-06-19 14:13:31', '9Zof0lINZubRYw9J0PJQaIBt7zn8NH69XoADQN2tHlzzErAhGT'),
(6, '20112324', 'Delia', 'Juston', 'Lind', 'dina82', 'cum', '', 'vwintheiser@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '4.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:31', '2015-06-19 14:13:31', '1H60AiXiqKkTQf312WR72w9gQTCf6JdBFhbhWqOYwsudMU1SLX'),
(7, '20111285', 'Fausto', 'Oda', 'Feil', 'stan.cronin', 'nesciunt', '', 'oral.tromp@hodkiewicz.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:31', '2015-06-19 14:13:31', 'XGJhEUZ9zfxRPo4hnGxxICoWY9m5SBZLJEeewOK9nFtSWI0zQi'),
(8, '20115941', 'Rahsaan', 'Aryanna', 'Reinger', 'watsica.gia', 'sequi', '', 'murazik.adonis@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:31', '2015-06-19 14:13:31', 'cetXA6XjmkRdw0zPo6OuQM3V9Tp1KkpUWVom1vBpZmFXw5g2Xe'),
(9, '20111693', 'Catherine', 'Dulce', 'Thiel', 'salvatore.o''keefe', 'et', '', 'keyshawn57@millshyatt.com', '', 1, '0000-00-00', NULL, NULL, NULL, '5.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:31', '2015-06-19 14:13:31', 'JfrtwhO00BkF3qffwVbVXd4rthMPdf0CKh62Lap5rzKKazD1h9'),
(10, '20110684', 'Haskell', 'Cierra', 'Hessel', 'senger.nettie', 'adipisci', '', 'adelia73@yahoo.com', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'qdeR44k4mjF8e6Xuet1IoZUc8kROXswCbVwy7H09E6UKxWnuh8'),
(11, '20114037', 'Elvera', 'Kira', 'Johns', 'hosea03', 'vero', '', 'suzanne45@erdman.net', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'jN6lefWVJtwZL2DZSPQsgaQ0wtV5mHAqA3WbQSmw7IZWwXbJyW'),
(12, '20114040', 'Mina', 'Wilhelmine', 'Walker', 'houston59', 'pariatur', '', 'kelsi.gorczany@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', '9sEiQ0t9cCyn6AAiuDx9y1ObXXS1BNejw41Q9RA0m0krGvD6mH'),
(13, '20114719', 'Chadd', 'Chaz', 'Halvorson', 'cathy29', 'et', '', 'charity.ankunding@yahoo.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'CEze4RJpFvBCRvnohGyXGKsUFXavc5Vw7icMkR1Q9kYMM26TDH'),
(14, '20110612', 'Hubert', 'Jamir', 'Ratke', 'trinity.nienow', 'voluptatem', '', 'o''reilly.madilyn@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '4.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'z0SqH65TtQxLjpgo1zl9l0e8f41BLUJFhgI9A1LActL9yYKyJk'),
(15, '20115153', 'Clay', 'Einar', 'Mertz', 'mbotsford', 'voluptatem', '', 'maye.dubuque@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'IP1ErQyqcsekR8LpD7fCeDSaQ3QT6PA94gXFwj0wkthCDNAsLX'),
(16, '20113057', 'Bridget', 'Ola', 'Rippin', 'jkuphal', 'doloremque', '', 'gkuhn@schiller.info', '', 1, '0000-00-00', NULL, NULL, NULL, '4.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'w57Bm5WgGim6q2oOLDsgV9C2nyAKNwkmylVwbHbymbrzDdsmOa'),
(17, '20111369', 'Bret', 'Jimmie', 'Wisozk', 'domenick.gaylord', 'autem', '', 'allie32@wildermannolan.com', '', 1, '0000-00-00', NULL, NULL, NULL, '4.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'oNxHGmuB357dHbNzqtOKgEZEkfrlBshvZaIRRk9oXMgB7hEq8f'),
(18, '20110064', 'Terence', 'Bonita', 'Franecki', 'harris.cade', 'est', '', 'dasia.wisoky@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'AumYUkAOwXvdR37aTLAY1SwVZQMQsNuMCKepFMqfmrrptUcEw5'),
(19, '20113331', 'Devon', 'Walter', 'Miller', 'vivienne32', 'laborum', '', 'grady.franecki@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'PHPang3x5U0csoh1ijMXCeThpWGEC1SNKXUx99EW1qC4tp7DQi'),
(20, '20115406', 'Alek', 'Sedrick', 'Brakus', 'medhurst.santino', 'tempora', '', 'clint44@breitenbergabshire.biz', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'uoUMdDQ431gUtnYzewbfIkgRMbrkVDPYFo7ymDiX6Ij5KEkz18'),
(21, '20110749', 'Craig', 'Mariam', 'Ullrich', 'akeem31', 'voluptas', '', 'cquigley@oberbrunner.com', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'hcWonBY52veXes7QAJt5SuXxZAYqj0rq5S5FD4yHG7Gn8vJwAN'),
(22, '20115914', 'Magali', 'Kenyatta', 'Turner', 'caterina.schaefer', 'alias', '', 'zcollins@ryanrunolfsdottir.biz', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'k0OKhccdSjcMVzWE1NGCM2BLGzoZeozk8FIPTh4MqucKDz3WVK'),
(23, '20110243', 'Taryn', 'Jasper', 'Bartell', 'tlegros', 'ullam', '', 'nkuvalis@baileypadberg.com', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'ruXHc21ILz6cN9fwjhAwfWERipBbWvsJdAnXJ5HrCO9XyduOLH'),
(24, '20114585', 'Wava', 'Marvin', 'Skiles', 'jast.jennifer', 'laborum', '', 'bahringer.griffin@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '5.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'FKeQVmThBJAfEuAyuwNpg5eA4n6Gh6UHBMzmG1BKoNvVMrUzK5'),
(25, '20113043', 'Amaya', 'Alana', 'Rath', 'corwin.may', 'cumque', '', 'lucas.heaney@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '5.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', '71WT7q4HuN4A1TpuF3FKKGH5X6OKSGSfcICP8P6KDjD582sgHf'),
(26, '20114229', 'Stanley', 'Shayne', 'Bosco', 'imraz', 'ut', '', 'waino.lemke@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '7.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'S9uIOt0aTR8yQUzHSXNx41idAJ5zqKYuO8zOXm6eU0m5qeppZX'),
(27, '20110534', 'Cassandra', 'Dexter', 'Prosacco', 'durgan.kathlyn', 'quo', '', 'barton.destiney@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'hqv5pX2ZrLObZi9NoIcoV4jqBCnVluIncJCLSsSHmprGt14wOa'),
(28, '20113010', 'Corine', 'Myron', 'Kemmer', 'alia23', 'perspiciatis', '', 'hintz.silas@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '7.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'xXBfCfixGIyXQJdBMjD0OQLiF2UYz8dmA4rt0nfC9xjUGBSoaF'),
(29, '20115465', 'Rafaela', 'Vida', 'Hilpert', 'kub.devonte', 'sint', '', 'lazaro.pfannerstill@reynolds.biz', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'FmtcjBmz1giOhdBicla3F7hzkqcIxeD1hpyddK3mX3CnUxDcV6'),
(30, '20111881', 'Reyna', 'Cristobal', 'Smith', 'jazmyn14', 'dicta', '', 'mauricio56@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:32', '2015-06-19 14:13:32', 'l1Pr2A2h9oxLC3ClyiPWgVi5RRB7Uu8j9Cxd3mhWspt4JHUb1v'),
(31, '20115876', 'Amie', 'Merl', 'Feeney', 'dbarrows', 'aut', '', 'grant.mohammed@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'bOlnfnJTT3dEOgWdKdVXkHZkNFSxkDeV5aD2isMgGY4Kz5IPhH'),
(32, '20110122', 'Oran', 'Rosa', 'Kautzer', 'oreichel', 'ut', '', 'janet.ebert@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '7.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', '0zZbgb9sRBT8nmii9cj0BMihVE6s4OaEg7Vdn6PgWgyN8FAJk8'),
(33, '20112255', 'Maryam', 'Carolina', 'Ortiz', 'buckridge.ena', 'est', '', 'acole@kovacekcronin.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', '3DMRLpE3kydSyd9yplsduI4Sk3MGdHdBRFd7b3ism1XxdNRcb3'),
(34, '20112773', 'Kaleigh', 'Matilda', 'Nolan', 'sauer.genevieve', 'architecto', '', 'klocko.mabel@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '4.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'NP2gSMFC9rotYh1p6cp1tn3tDbW8ZQ5uRUm18FUKKnaBJwcGmZ'),
(35, '20113078', 'Faustino', 'Albina', 'Erdman', 'verdie.ernser', 'velit', '', 'dudley59@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '5.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'KmihCugZZYtq9bSWIk5brVO1KoNC3gwy9DdNNa3aY2c4ARpzaL'),
(36, '20114283', 'Nicole', 'Beth', 'Morar', 'ryan39', 'est', '', 'otha.grant@botsfordsawayn.com', '', 1, '0000-00-00', NULL, NULL, NULL, '4.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', '15FssmaUFyJac7K1bJUAvk6te8ig7cBoowpcAZFwhVmGFjI1ZQ'),
(37, '20112517', 'Marjolaine', 'Demetris', 'Kris', 'nedra70', 'molestiae', '', 'oliver.schamberger@renner.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'J3GvpDQ0IDSn71yddOo0QyN8sQxWNMlYIcnBQ4YLsbPPm921uI'),
(38, '20111888', 'Freddy', 'Edna', 'Simonis', 'orin.mckenzie', 'nesciunt', '', 'clair.kertzmann@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'GuxNnq0EARt1oRtARVjv1Aagof2YMvC6vYJmG4fxNjN1neDVUs'),
(39, '20115703', 'Kacey', 'Vincenzo', 'Berge', 'fschumm', 'maxime', '', 'amani.volkman@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'GKivIymzRl6adaoZ5LUQ3GnU2dCpLk3inhZNq8S6qaQMJUcqZZ'),
(40, '20113044', 'Zora', 'Clarissa', 'Rice', 'rusty81', 'sed', '', 'reichel.nya@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '5.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'mxsU2EXrvPzQ2Aw5sAqbrJlsmMWxi5wM2K4P3N6qtoelOnQhA4'),
(41, '20110491', 'Peyton', 'Magdalen', 'Vandervort', 'corwin.sierra', 'sed', '', 'reba.koelpin@bayer.info', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'ZGkea0yXnmGLHpvMM9D0zOKQBjhGEMPWXR1BnhOL6DHaNhnnuq'),
(42, '20113128', 'Sammy', 'Jordyn', 'Champlin', 'hillary95', 'et', '', 'kevon29@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'rHOKLHLt0x2sCV32AW9J86lwwfTFaNPvA0Me8MCQE865TvGcS1'),
(43, '20110560', 'Napoleon', 'Alta', 'Keebler', 'fletcher.anderson', 'voluptatem', '', 'leonel78@hotmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'EUbEptqEzzg8hJ4rFbvs83VODvDQPrix1FGNY3N4IDRBvIsKM2'),
(44, '20114766', 'Madonna', 'Samson', 'Stracke', 'dayna04', 'debitis', '', 'chance14@terry.com', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'QrICCKPofUo0XjslGka30KWut5Wysfb4jdOJTuDgkjfsxKHETZ'),
(45, '20115282', 'Shanon', 'Wilber', 'Botsford', 'xshields', 'perferendis', '', 'harrison36@quigley.com', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'ZoVOATtFmcug4fZ0VUIMIieYvpKXbo2JN8z0QT7P1JVNeCHJRS'),
(46, '20111477', 'Sheridan', 'Janelle', 'Spinka', 'alfonzo.kessler', 'esse', '', 'vmurazik@jacobsonkshlerin.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', '6TL8OQjTmXWIfeurxy4UAitHF4Npa3ptVQKSrXTTYPNfuPnEJa'),
(47, '20111211', 'Edwardo', 'Providenci', 'Leffler', 'schowalter.gwendolyn', 'culpa', '', 'probel@luettgen.com', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'VNL8Dg6215gjHBUoW6BPt2iPCadlmDtCVwFIpfnG3YnstGiwHD'),
(48, '20112947', 'Noe', 'Gilda', 'Weimann', 'bryana83', 'itaque', '', 'yrussel@yahoo.com', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'wMaGuNgBzyU2ScD0Egqruk6PXUhBs2qb6e6fFsJAXfvwNAehd4'),
(49, '20115184', 'Malika', 'Earline', 'Oberbrunner', 'koss.kira', 'ut', '', 'orn.josue@kemmergaylord.net', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:33', '2015-06-19 14:13:33', 'eICWtwJUX0zE89GghX2tkhZ2U2UUQOaQFLNuSzWZqbJ3WHPNpO'),
(50, '20110837', 'Aurelio', 'Marietta', 'Kuvalis', 'raymundo.schneider', 'vero', '', 'kaela.kiehn@gmail.com', '', 1, '0000-00-00', NULL, NULL, NULL, '4.jpg', 'male', NULL, '', '', '1', '', '', '', '2015-06-19 14:13:34', '2015-06-19 14:13:34', 'NlgDZXXSRJbad4aqHqmB4idaBPR6AtdtO76Jn8woIvcQxMvMki'),
(51, '20110410', 'Shaniya', 'Damaris', 'Feeney', 'svon', 'autem', '', 'pagac.gladyce@schummhowell.com', '', 1, '0000-00-00', NULL, NULL, NULL, '6.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:34', '2015-06-19 14:13:34', 'CnWTD8uAY8KhtKpVS0NoZJ9F5ktCD6OUqxDwoo4WtCm57UzZ5s'),
(52, '20115946', 'Else', 'Lee', 'Boyer', 'jsmith', 'in', '', 'delphine.adams@runolfssonwalker.com', '', 1, '0000-00-00', NULL, NULL, NULL, '2.jpg', 'male', NULL, '', '', '4', '', '', '', '2015-06-19 14:13:34', '2015-06-19 14:13:34', 'JfbaozV9TpKsqWYBzDqJaU45GEb5UMkIez5UrybAcTW7kirL6H'),
(53, '20113356', 'Johanna', 'Ignacio', 'Marks', 'kelsie.mraz', 'laborum', '', 'zhirthe@yahoo.com', '', 1, '0000-00-00', NULL, NULL, NULL, '3.jpg', 'male', NULL, '', '', '2', '', '', '', '2015-06-19 14:13:34', '2015-06-19 14:13:34', 'PfL3c3lhM7EqaqBYMTS1VOCHeWQyFGCBYZ3z2QB0uDN9FwVVy8'),
(54, '20112135', 'Earnest', 'Tiara', 'Rath', 'gwitting', 'aut', '', 'gjones@reichertgleason.net', '', 1, '0000-00-00', NULL, NULL, NULL, '7.jpg', 'male', NULL, '', '', '3', '', '', '', '2015-06-19 14:13:34', '2015-06-19 14:13:34', 'inuYMxcLnV99IPdyHyMZ6DBGsX8AocDwu1f1RtjpbgwAdMdLII');

-- --------------------------------------------------------

--
-- Table structure for table `user_task`
--

CREATE TABLE IF NOT EXISTS `user_task` (
`id` int(11) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `task_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_task`
--

INSERT INTO `user_task` (`id`, `user_id`, `task_id`, `created_at`) VALUES
(1, 3, 16, '2015-06-02 00:00:00'),
(2, 3, 17, '2015-06-23 00:00:00'),
(4, 2, 18, '2015-07-02 00:00:00'),
(5, 4, 20, '2015-07-02 00:00:00'),
(6, 3, 19, '2015-07-14 00:00:00'),
(12, 4, 23, '2015-07-15 00:00:00'),
(17, 2, 21, '2015-07-08 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `vote`
--

CREATE TABLE IF NOT EXISTS `vote` (
`id` bigint(20) unsigned NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `create_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `group_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vote`
--

INSERT INTO `vote` (`id`, `content`, `title`, `create_date`, `end_date`, `group_id`, `user_id`) VALUES
(1, 'which time u prefer to have DB section ?', 'DB section', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 19, 2),
(2, 'which time u prefer to have SA section ?', 'SA section', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 22, 3),
(3, 'choose the most difficult chapter in DB2', 'difficult chapter', '2015-07-15 00:00:00', '2015-07-17 00:00:00', 19, 2),
(4, 'choose the most difficult chapter in SA', 'difficult chapter', '2015-05-27 14:17:39', '0000-00-00 00:00:00', 22, 3),
(5, 'did u find DB2 session is useful to u ?', 'DB2 session', '2015-05-27 14:19:12', '0000-00-00 00:00:00', 19, 3),
(6, 'did u find SA ola''s session is useful to u ?', 'SA2 session', '2015-05-27 23:52:20', '0000-00-00 00:00:00', 22, 2),
(7, 'which course u prefer to open on this summer ?', 'summer courses', '2015-06-19 14:53:40', '0000-00-00 00:00:00', 22, 2),
(8, 'which course u prefer to open on this summer ?', 'summer courses', '2015-06-22 20:11:39', '0000-00-00 00:00:00', 19, 3),
(9, 'Do u want to discuss midterm exam next section?', 'midterm discussion', '2015-06-27 14:04:55', '0000-00-00 00:00:00', 22, 2),
(10, 'Do u want to discuss midterm exam next section?', 'midterm discussion', '2015-07-09 00:00:00', '2015-07-02 00:00:00', 19, 3);

--
-- Triggers `vote`
--
DELIMITER //
CREATE TRIGGER `VOTE_AF_INS` AFTER INSERT ON `vote`
 FOR EACH ROW BEGIN
	
	DECLARE MESSAGE TEXT ;
	DECLARE USERS_NAME VARCHAR(200);
	DECLARE GROUPS_NAME VARCHAR(200);
	DECLARE done INT DEFAULT FALSE;
	DECLARE USER INT ;
	DECLARE NOTIFICATION INT ;	
    DECLARE cur1 CURSOR FOR 
    SELECT M.`user_id` FROM GROUP_MEMBERS AS M 
	INNER JOIN USERS AS U ON U.ID = M.USER_ID
	INNER JOIN GROUPS AS G ON G.ID = M.GROUP_ID
	WHERE M.REQUEST = 0 AND M.GROUP_ID =  NEW.`GROUP_ID` 
	AND M.`user_id` NOT IN  (NEW.`USER_ID`);
   
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	
	SET USERS_NAME = (SELECT CONCAT(`first_name`,' ',`last_name`) FROM `USERS` 
	WHERE `ID` = NEW.`USER_ID`);

	SET GROUPS_NAME = (SELECT name FROM `GROUPS` 
	WHERE `ID` = NEW.`GROUP_ID`);

	SET MESSAGE = CONCAT('<b>' ,USERS_NAME,'</b>',' ','Add Vote ( ',NEW.`TITLE`,' 	) in ','<b>',GROUPS_NAME,'</b>');

	INSERT INTO `NOTIFICATION` (`USERS`,`GROUPS`,`MESSAGE`) 
	VALUES (NEW.`USER_ID`,NEW.`GROUP_ID`,MESSAGE);

	SET NOTIFICATION = (SELECT LAST_INSERT_ID());

	OPEN cur1;
	read_loop: LOOP
	FETCH cur1 INTO USER;

	IF done THEN
      LEAVE read_loop;
    END IF;

    INSERT INTO `NOTIFICATION_SEEN` (`NOTIFICATION`,`USERS`) VALUES (NOTIFICATION,USER);
	 END LOOP;
	CLOSE cur1;

    
    END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `vote_choice`
--

CREATE TABLE IF NOT EXISTS `vote_choice` (
`id` bigint(20) unsigned NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `rate` int(11) NOT NULL DEFAULT '0',
  `vote_id` bigint(20) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `vote_choice`
--

INSERT INTO `vote_choice` (`id`, `description`, `rate`, `vote_id`) VALUES
(1, 'Saturday 9-12', 0, 1),
(2, 'Saturday 9-12', 0, 2),
(3, 'Sunday 9-12', 0, 1),
(4, 'Sunday 9-12', 0, 2),
(5, 'Tuesday 12-2', 0, 2),
(6, 'Tuesday 12-2', 0, 1),
(7, 'chapter1', 0, 3),
(8, 'chapter1', 0, 4),
(9, 'chapter2', 0, 4),
(10, 'chapter2', 0, 3),
(11, 'chapter10', 0, 3),
(12, 'chapter10', 0, 4),
(13, 'yes', 0, 5),
(14, 'yes', 0, 6),
(15, 'no', 0, 5),
(16, 'no', 0, 6),
(17, 'Math2', 0, 7),
(18, 'Math2', 0, 8),
(19, 'Human Right', 0, 7),
(20, 'Human Right', 0, 8),
(21, 'yes', 0, 9),
(22, 'yes', 0, 10),
(23, 'no', 0, 9),
(24, 'no', 0, 10);

-- --------------------------------------------------------

--
-- Table structure for table `vote_user_choice`
--

CREATE TABLE IF NOT EXISTS `vote_user_choice` (
`id` bigint(20) NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `choice_id` bigint(20) unsigned NOT NULL,
  `choice_date` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
 ADD PRIMARY KEY (`id`), ADD KEY `announcement_user_id_index` (`user_id`);

--
-- Indexes for table `announcement_group`
--
ALTER TABLE `announcement_group`
 ADD KEY `announcement_group_group_id_index` (`group_id`), ADD KEY `announcement_group_announcement_id_index` (`announcement_id`);

--
-- Indexes for table `announcement_like`
--
ALTER TABLE `announcement_like`
 ADD UNIQUE KEY `unique_index` (`announcement_id`,`user_id`), ADD KEY `test_index` (`user_id`), ADD KEY `test2_index` (`announcement_id`);

--
-- Indexes for table `announcement_user`
--
ALTER TABLE `announcement_user`
 ADD KEY `announcement_user_user_id_index` (`user_id`), ADD KEY `announcement_user_announcement_id_index` (`announcement_id`);

--
-- Indexes for table `answer_highlighting`
--
ALTER TABLE `answer_highlighting`
 ADD KEY `answer_highlighting_answer_id_index` (`answer_id`), ADD KEY `answer_highlighting_user_id_index` (`user_id`);

--
-- Indexes for table `answer_user_rate`
--
ALTER TABLE `answer_user_rate`
 ADD KEY `answer_user_rate_answer_id_index` (`answer_id`), ADD KEY `answer_user_rate_user_id_index` (`user_id`);

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
 ADD PRIMARY KEY (`id`), ADD KEY `assignment_user_id_index` (`user_id`), ADD KEY `assignment_group_id_index` (`group_id`);

--
-- Indexes for table `assignment_solution`
--
ALTER TABLE `assignment_solution`
 ADD KEY `user_id` (`user_id`), ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `chat_roome`
--
ALTER TABLE `chat_roome`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_room_users`
--
ALTER TABLE `chat_room_users`
 ADD KEY `user_id` (`user_id`), ADD KEY `chat_room_id` (`chat_room_id`);

--
-- Indexes for table `comment_answer`
--
ALTER TABLE `comment_answer`
 ADD PRIMARY KEY (`id`), ADD KEY `comment_answer_answer_id_index` (`answer_id`), ADD KEY `comment_answer_user_id_index` (`user_id`), ADD KEY `comment_announcement` (`announcement_id`);

--
-- Indexes for table `comment_answer_rate`
--
ALTER TABLE `comment_answer_rate`
 ADD KEY `comment_answer_rate_comment_id_index` (`comment_id`), ADD KEY `comment_answer_rate_user_id_index` (`user_id`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
 ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`), ADD KEY `user_id_2` (`user_id`,`group_id`), ADD KEY `user_id_3` (`user_id`), ADD KEY `user_id_4` (`user_id`,`group_id`);

--
-- Indexes for table `connection_info`
--
ALTER TABLE `connection_info`
 ADD PRIMARY KEY (`id`), ADD KEY `users` (`users`), ADD KEY `users_2` (`users`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `friends_list`
--
ALTER TABLE `friends_list`
 ADD PRIMARY KEY (`id`), ADD KEY `room_id` (`room_id`), ADD KEY `user2_id` (`user2_id`), ADD KEY `user1_id` (`user1_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
 ADD PRIMARY KEY (`id`), ADD KEY `groups_user_id_index` (`user_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
 ADD UNIQUE KEY `group_members_uq` (`group_id`,`user_id`), ADD KEY `group_members_group_id_index` (`group_id`), ADD KEY `group_members_user_id_index` (`user_id`);

--
-- Indexes for table `materials`
--
ALTER TABLE `materials`
 ADD PRIMARY KEY (`id`), ADD KEY `gId` (`gId`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
 ADD PRIMARY KEY (`id`), ADD KEY `chat_room_id` (`chat_room_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
 ADD PRIMARY KEY (`id`), ADD KEY `users` (`users`), ADD KEY `groups` (`groups`);

--
-- Indexes for table `notification_seen`
--
ALTER TABLE `notification_seen`
 ADD PRIMARY KEY (`id`), ADD KEY `notification` (`notification`), ADD KEY `users` (`users`);

--
-- Indexes for table `project_log`
--
ALTER TABLE `project_log`
 ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `question`
--
ALTER TABLE `question`
 ADD PRIMARY KEY (`id`), ADD KEY `question_group_id_index` (`group_id`), ADD KEY `question_user_id_index` (`user_id`);

--
-- Indexes for table `question_answers`
--
ALTER TABLE `question_answers`
 ADD PRIMARY KEY (`id`), ADD KEY `question_answers_question_id_index` (`question_id`), ADD KEY `question_answers_user_id_index` (`user_id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `quize_question`
--
ALTER TABLE `quize_question`
 ADD PRIMARY KEY (`id`), ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quize_question_choice`
--
ALTER TABLE `quize_question_choice`
 ADD PRIMARY KEY (`id`), ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `quiz_user_answer`
--
ALTER TABLE `quiz_user_answer`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `survey`
--
ALTER TABLE `survey`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `survey_questions`
--
ALTER TABLE `survey_questions`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `survey_id` (`survey_id`);

--
-- Indexes for table `survey_questions_answers`
--
ALTER TABLE `survey_questions_answers`
 ADD PRIMARY KEY (`id`), ADD KEY `survey_questions_id` (`survey_questions_id`);

--
-- Indexes for table `survey_user_answers`
--
ALTER TABLE `survey_user_answers`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `task_log`
--
ALTER TABLE `task_log`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `users_code_unique` (`user_code`), ADD UNIQUE KEY `users_email_unique` (`email`), ADD UNIQUE KEY `users_phone_unique` (`phone`);

--
-- Indexes for table `user_task`
--
ALTER TABLE `user_task`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `task_id` (`task_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vote`
--
ALTER TABLE `vote`
 ADD PRIMARY KEY (`id`), ADD KEY `group_id` (`group_id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `vote_choice`
--
ALTER TABLE `vote_choice`
 ADD PRIMARY KEY (`id`), ADD KEY `vote_id` (`vote_id`);

--
-- Indexes for table `vote_user_choice`
--
ALTER TABLE `vote_user_choice`
 ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `choice_id` (`choice_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chat_roome`
--
ALTER TABLE `chat_roome`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `comment_answer`
--
ALTER TABLE `comment_answer`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=360;
--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `connection_info`
--
ALTER TABLE `connection_info`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=305;
--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT for table `friends_list`
--
ALTER TABLE `friends_list`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=47;
--
-- AUTO_INCREMENT for table `materials`
--
ALTER TABLE `materials`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=230;
--
-- AUTO_INCREMENT for table `notification_seen`
--
ALTER TABLE `notification_seen`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=137;
--
-- AUTO_INCREMENT for table `project_log`
--
ALTER TABLE `project_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `question`
--
ALTER TABLE `question`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT for table `question_answers`
--
ALTER TABLE `question_answers`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=156;
--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `quize_question`
--
ALTER TABLE `quize_question`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `quize_question_choice`
--
ALTER TABLE `quize_question_choice`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `quiz_user_answer`
--
ALTER TABLE `quiz_user_answer`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `survey`
--
ALTER TABLE `survey`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `survey_questions`
--
ALTER TABLE `survey_questions`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `survey_questions_answers`
--
ALTER TABLE `survey_questions_answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `survey_user_answers`
--
ALTER TABLE `survey_user_answers`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `task_log`
--
ALTER TABLE `task_log`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=28;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=55;
--
-- AUTO_INCREMENT for table `user_task`
--
ALTER TABLE `user_task`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `vote`
--
ALTER TABLE `vote`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `vote_choice`
--
ALTER TABLE `vote_choice`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `vote_user_choice`
--
ALTER TABLE `vote_user_choice`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
ADD CONSTRAINT `announcement_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcement_group`
--
ALTER TABLE `announcement_group`
ADD CONSTRAINT `announcement_group_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcement` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `announcement_group_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcement_like`
--
ALTER TABLE `announcement_like`
ADD CONSTRAINT `announcement_forign_key` FOREIGN KEY (`announcement_id`) REFERENCES `announcement` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `users_forign_key` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcement_user`
--
ALTER TABLE `announcement_user`
ADD CONSTRAINT `announcement_user_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcement` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `announcement_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `answer_highlighting`
--
ALTER TABLE `answer_highlighting`
ADD CONSTRAINT `answer_highlighting_answer_id_foreign` FOREIGN KEY (`answer_id`) REFERENCES `question_answers` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `answer_highlighting_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `answer_user_rate`
--
ALTER TABLE `answer_user_rate`
ADD CONSTRAINT `answer_user_rate_answer_id_foreign` FOREIGN KEY (`answer_id`) REFERENCES `question_answers` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `answer_user_rate_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
ADD CONSTRAINT `assignment_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `assignment_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_room_users`
--
ALTER TABLE `chat_room_users`
ADD CONSTRAINT `chat_room_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `chat_room_users_ibfk_2` FOREIGN KEY (`chat_room_id`) REFERENCES `chat_roome` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `comment_answer`
--
ALTER TABLE `comment_answer`
ADD CONSTRAINT `announcement_foreign_key ` FOREIGN KEY (`announcement_id`) REFERENCES `announcement` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `comment_answer_answer_id_foreign` FOREIGN KEY (`answer_id`) REFERENCES `question_answers` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `comment_answer_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `comment_answer_rate`
--
ALTER TABLE `comment_answer_rate`
ADD CONSTRAINT `comment_answer_rate_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comment_answer` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `comment_answer_rate_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `complaint`
--
ALTER TABLE `complaint`
ADD CONSTRAINT `complaint_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `complaint_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `connection_info`
--
ALTER TABLE `connection_info`
ADD CONSTRAINT `CONN2_USERS` FOREIGN KEY (`users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `friends_list`
--
ALTER TABLE `friends_list`
ADD CONSTRAINT `friends_list_ibfk_1` FOREIGN KEY (`user1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `friends_list_ibfk_2` FOREIGN KEY (`user2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `friends_list_ibfk_3` FOREIGN KEY (`room_id`) REFERENCES `chat_roome` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `groups`
--
ALTER TABLE `groups`
ADD CONSTRAINT `groups_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
ADD CONSTRAINT `group_members_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `group_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_room_id`) REFERENCES `chat_roome` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
ADD CONSTRAINT `notification_fk_groups` FOREIGN KEY (`groups`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `notification_fk_users` FOREIGN KEY (`users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `notification_seen`
--
ALTER TABLE `notification_seen`
ADD CONSTRAINT `notfifcation_seen_fk1` FOREIGN KEY (`notification`) REFERENCES `notification` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `notfifcation_seen_fk2` FOREIGN KEY (`users`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `project_log`
--
ALTER TABLE `project_log`
ADD CONSTRAINT `project_log_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `project_log_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `question`
--
ALTER TABLE `question`
ADD CONSTRAINT `question_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `question_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_answers`
--
ALTER TABLE `question_answers`
ADD CONSTRAINT `question_answers_question_id_foreign` FOREIGN KEY (`question_id`) REFERENCES `question` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `question_answers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz`
--
ALTER TABLE `quiz`
ADD CONSTRAINT `quiz_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `quiz_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quize_question`
--
ALTER TABLE `quize_question`
ADD CONSTRAINT `quize_question_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quize_question_choice`
--
ALTER TABLE `quize_question_choice`
ADD CONSTRAINT `quize_question_choice_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `quize_question` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `quiz_user_answer`
--
ALTER TABLE `quiz_user_answer`
ADD CONSTRAINT `quiz_user_answer_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `quiz_user_answer_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quiz` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `survey`
--
ALTER TABLE `survey`
ADD CONSTRAINT `survey_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `survey_questions`
--
ALTER TABLE `survey_questions`
ADD CONSTRAINT `survey_questions_ibfk_1` FOREIGN KEY (`survey_id`) REFERENCES `survey` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `survey_questions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `survey_questions_answers`
--
ALTER TABLE `survey_questions_answers`
ADD CONSTRAINT `survey_questions_answers_ibfk_1` FOREIGN KEY (`survey_questions_id`) REFERENCES `survey_questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task`
--
ALTER TABLE `task`
ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `task_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `task_log`
--
ALTER TABLE `task_log`
ADD CONSTRAINT `task_log_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `task_log_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_task`
--
ALTER TABLE `user_task`
ADD CONSTRAINT `user_task_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `user_task_ibfk_2` FOREIGN KEY (`task_id`) REFERENCES `task` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vote`
--
ALTER TABLE `vote`
ADD CONSTRAINT `vote_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `vote_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vote_choice`
--
ALTER TABLE `vote_choice`
ADD CONSTRAINT `vote_choice_ibfk_1` FOREIGN KEY (`vote_id`) REFERENCES `vote` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vote_user_choice`
--
ALTER TABLE `vote_user_choice`
ADD CONSTRAINT `vote_user_choice_ibfk_1` FOREIGN KEY (`choice_id`) REFERENCES `vote_choice` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
ADD CONSTRAINT `vote_user_choice_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
