
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `email` tinytext NOT NULL,
  `applications` tinytext default NULL,
  `created` datetime default NULL,
  PRIMARY KEY  (`id`)
);