<?php
	$q_dump = "CREATE TABLE IF NOT EXISTS `litres_data` (
		`hub_id` mediumint(8) unsigned NOT NULL,
		`litres_url` varchar(300) NOT NULL COMMENT '��� �����',
		`litres_id` mediumint(8) unsigned DEFAULT NULL COMMENT 'id ����� �� ������� (�� ���� ��������� ������� � �������)',
		`hub_author_id` mediumint(8) unsigned NOT NULL,
		`litres_a_url` varchar(100) DEFAULT NULL COMMENT '��� ������',
		`local_book_id` int(10) unsigned DEFAULT NULL,
		`local_book_id_litres_catalog` int(10) unsigned DEFAULT NULL COMMENT 'id ��������� ����� �� ���������� ���������� �������� �� �����',
		`global_book_id` varchar(200) NOT NULL,
		`mybook_book_url` varchar(200) DEFAULT NULL COMMENT '��� ����� �� mybook',
		`author_name` varchar(200) NOT NULL,
		`author_sname` varchar(200) NOT NULL,
		`litres_a_rod` varchar(300) DEFAULT NULL COMMENT '��� ����� ������',
		`second_author_name` varchar(200) DEFAULT NULL,
		`second_author_sname` varchar(200) DEFAULT NULL,
		`book_title` varchar(300) NOT NULL,
		`genre` char(100) NOT NULL,
		`options` smallint(5) unsigned NOT NULL,
		`price` decimal(10,2) unsigned NOT NULL,
		`type` smallint(5) unsigned NOT NULL COMMENT '0-�����, 1-����������',
		`annotation` varchar(1000) NOT NULL,
		`cover_ext` char(4) DEFAULT NULL,
		`publisher` varchar(100) NOT NULL,
		`publ_year` smallint(6) NOT NULL,
		`updated` datetime NOT NULL COMMENT '���� updated �� xml ������, ��� ����������� ������ ������ ������',
		PRIMARY KEY (`hub_id`),
		KEY `author_name` (`author_name`),
		KEY `author_sname` (`author_sname`),
		KEY `book_title` (`book_title`),
		KEY `genre` (`genre`),
		KEY `global_book_id` (`global_book_id`),
		KEY `second_author_name` (`second_author_name`),
		KEY `second_author_sname` (`second_author_sname`)
		) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
?>