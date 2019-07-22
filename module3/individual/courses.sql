mysql> show create table courses;
+---------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Table   | Create Table                                                                                                                                                                                                                                                                                                                                                                                                                                                       |
+---------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| courses | CREATE TABLE `courses` (
  `school_code` enum('L','B','A','F','E','T','I','W','S','U','M') NOT NULL DEFAULT 'A',
  `dept_id` tinyint(3) unsigned NOT NULL,
  `course_code` char(5) NOT NULL DEFAULT '',
  `name` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`school_code`,`dept_id`,`course_code`),
  CONSTRAINT `courses_ibfk_1` FOREIGN KEY (`school_code`, `dept_id`) REFERENCES `departments` (`school_code`, `dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 |
+---------+--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
1 row in set (0.00 sec)