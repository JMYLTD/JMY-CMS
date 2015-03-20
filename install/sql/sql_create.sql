DROP TABLE IF EXISTS `[prefix]attach`;
CREATE TABLE `[prefix]attach` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(55) NOT NULL,
  `url` varchar(255) NOT NULL,
  `pub_id` varchar(255) default NULL,
  `mod` varchar(55) NOT NULL,
  `downloads` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pub_id` (`pub_id`),
  KEY `url` (`url`),
  KEY `pub_id_2` (`pub_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]blocks_types`;
CREATE TABLE `[prefix]blocks_types` (
  `title` varchar(55) default NULL,
  `type` varchar(55) NOT NULL,
  PRIMARY KEY  (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]sitemap`;
CREATE TABLE `[prefix]sitemap` (
  `id` int(11) NOT NULL auto_increment, 
  `name` text NOT NULL,
  `url` varchar(200) NOT NULL,  
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]guestbook`;
CREATE TABLE `[prefix]guestbook` (
  `id` int(11) NOT NULL auto_increment,
  `date` varchar(55) default NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `website` varchar(75) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `comment` text NOT NULL,
  `reply` text NOT NULL, 
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]blog_posts`;
CREATE TABLE `[prefix]blog_posts` (
  `id` int(11) NOT NULL auto_increment,
  `bid` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `comments` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `ratingUsers` text NOT NULL,
  `tags` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `bid` (`bid`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]blog_readers`;
CREATE TABLE `[prefix]blog_readers` (
  `bid` int(11) NOT NULL,
  `uid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]blogs`;
CREATE TABLE `[prefix]blogs` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `altname` varchar(55) NOT NULL,
  `description` text NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `posts` int(11) NOT NULL,
  `readersNum` int(11) NOT NULL,
  `date` int(11) NOT NULL,
  `lastUpdate` int(11) NOT NULL,
  `admins` varchar(255) NOT NULL,
  `readers` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]board_forums`;
CREATE TABLE `[prefix]board_forums` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(55) NOT NULL,
  `description` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `type` varchar(3) NOT NULL,
  `active` smallint(1) NOT NULL,
  `open` smallint(1) NOT NULL,
  `threads` int(11) NOT NULL,
  `posts` int(11) NOT NULL,
  `lastPost` varchar(55) NOT NULL,
  `lastPoster` varchar(255) NOT NULL,
  `lastTid` int(11) NOT NULL,
  `lastSubject` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rulestitle` varchar(255) NOT NULL,
  `rules` text NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]board_permissions`;
CREATE TABLE `[prefix]board_permissions` (
  `id` int(11) NOT NULL auto_increment,
  `fid` int(11) NOT NULL,
  `gid` int(11) NOT NULL,
  `allowView` int(1) NOT NULL,
  `allowRead` int(1) NOT NULL,
  `allowCreate` int(1) NOT NULL,
  `allowReply` int(1) NOT NULL,
  `allowEdit` int(1) NOT NULL,
  `allowModer` int(1) NOT NULL,
  `allowAttach` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]board_posts`;
CREATE TABLE `[prefix]board_posts` (
  `id` int(11) NOT NULL auto_increment,
  `tid` int(11) NOT NULL,
  `message` text NOT NULL,
  `uid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL,
  `files` text NOT NULL,
  `visible` varchar(1) NOT NULL,
  `editUser` varchar(55) NOT NULL,
  `editReason` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 PACK_KEYS=0;


DROP TABLE IF EXISTS `[prefix]board_threads`;
CREATE TABLE `[prefix]board_threads` (
  `id` int(11) NOT NULL auto_increment,
  `forum` int(11) NOT NULL,
  `title` varchar(55) NOT NULL,
  `poster` int(11) NOT NULL,
  `startTime` varchar(55) NOT NULL,
  `lastTime` varchar(55) NOT NULL,
  `lastPoster` varchar(55) NOT NULL,
  `views` int(11) NOT NULL,
  `replies` int(11) NOT NULL,
  `important` int(1) NOT NULL default '0',
  `closed` int(1) NOT NULL default '0',
  `score` float(6,3) NOT NULL,
  `votes` smallint(5) NOT NULL,
  `icon` varchar(44) NOT NULL,
  `closetime` varchar(55) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 PACK_KEYS=0;

DROP TABLE IF EXISTS `[prefix]board_users`;
CREATE TABLE `[prefix]board_users` (
  `uid` int(11) NOT NULL,
  `thanks` int(11) NOT NULL,
  `messages` int(11) NOT NULL,
  `specStatus` varchar(255) default ' ',
  `lastUpdate` int(11) NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]categories`;
CREATE TABLE `[prefix]categories` (
  `id` smallint(5) NOT NULL auto_increment,
  `name` varchar(55) NOT NULL,
  `altname` varchar(55) NOT NULL,
  `description` varchar(200) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `module` varchar(55) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `position` smallint(5) NOT NULL,
  `parent_id` smallint(5) NOT NULL,
  PRIMARY KEY  (`id`,`altname`),
  KEY `altname` (`altname`),
  KEY `parent_id` (`parent_id`),
  KEY `module` (`module`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]com_subscribe`;
CREATE TABLE `[prefix]com_subscribe` (
  `id` int(11) NOT NULL,
  `module` varchar(55) NOT NULL,
  `uid` int(11) NOT NULL,
  UNIQUE KEY `id` (`id`,`module`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]comments`;
CREATE TABLE `[prefix]comments` (
  `id` smallint(5) NOT NULL auto_increment,
  `uid` smallint(5) NOT NULL,
  `post_id` smallint(5) NOT NULL,
  `module` varchar(55) NOT NULL,
  `text` text,
  `date` varchar(44) NOT NULL,
  `gemail` varchar(55) NOT NULL,
  `gname` varchar(55) NOT NULL,
  `gurl` varchar(55) NOT NULL,
  `parent` int(11) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `module` (`module`),
  KEY `post_id` (`post_id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]content`;
CREATE TABLE `[prefix]content` (
  `id` int(11) NOT NULL auto_increment,
  `translate` varchar(255) NOT NULL,
  `cat` varchar(200) NOT NULL,
  `keywords` varchar(55) NOT NULL,
  `active` int(1) NOT NULL,
  `date` varchar(55) default NULL,
  `comments` int(11) NOT NULL,
  `theme` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]gallery_albums`;
CREATE TABLE `[prefix]gallery_albums` (
  `album_id` int(11) NOT NULL auto_increment,
  `title` varchar(250) NOT NULL,
  `trans` varchar(250) NOT NULL,
  `description` text NOT NULL,
  `views` int(11) NOT NULL,
  `nums` int(11) NOT NULL,
  `last_update` varchar(250) NOT NULL,
  `last_author` varchar(250) NOT NULL,
  `last_image` varchar(250) NOT NULL,
  `watermark` int(1) default NULL,
  `sizes` text NOT NULL,
  `gropups_allow` int(11) NOT NULL,
  `dir` varchar(255) NOT NULL,
  PRIMARY KEY  (`album_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]gallery_photos`;
CREATE TABLE `[prefix]gallery_photos` (
  `photo_id` int(11) NOT NULL auto_increment,
  `cat` int(11) NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` varchar(250) NOT NULL,
  `author` varchar(250) NOT NULL,
  `add_date` varchar(250) NOT NULL,
  `photo_date` varchar(250) NOT NULL,
  `photos` text NOT NULL,
  `tech` text NOT NULL,
  `views` int(11) NOT NULL,
  `gets` int(11) NOT NULL,
  `comments` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `ratings` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `groups_allow` int(11) NOT NULL,
  PRIMARY KEY  (`photo_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]groups`;
CREATE TABLE `[prefix]groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `guest` int(1) NOT NULL,
  `user` int(1) NOT NULL,
  `moderator` int(1) NOT NULL,
  `admin` int(1) NOT NULL,
  `banned` int(1) NOT NULL,
  `showHide` int(1) NOT NULL,
  `showAttach` int(1) NOT NULL,
  `loadAttach` int(1) NOT NULL,
  `addPost` int(1) NOT NULL,
  `addComment` int(1) NOT NULL,
  `allowRating` int(1) NOT NULL,
  `maxWidth` int(11) NOT NULL,
  `maxPms` int(11) NOT NULL,
  `control` text NOT NULL,
  `icon` varchar(255) NOT NULL,
  `color` varchar(55) NOT NULL,
  `points` int(11) NOT NULL,
  `protect` int(1) NOT NULL,
  `special` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]langs`;
CREATE TABLE `[prefix]langs` (
  `_id` int(11) NOT NULL auto_increment,
  `postId` varchar(255) default NULL,
  `module` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `short` text,
  `full` text,
  `lang` varchar(255) NOT NULL,
  PRIMARY KEY  (`_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]logs`;
CREATE TABLE `[prefix]logs` (
  `time` int(5) NOT NULL,
  `ip` varchar(255) NOT NULL,
  `uid` int(5) NOT NULL,
  `history` varchar(255) NOT NULL,
  `level` smallint(1) NOT NULL,
  PRIMARY KEY  (`time`),
  KEY `level` (`level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]news`;
CREATE TABLE `[prefix]news` (
  `id` smallint(5) NOT NULL auto_increment,
  `author` varchar(55) NOT NULL,
  `date` int(11) default NULL,
  `tags` varchar(255) NOT NULL,
  `cat` varchar(200) default NULL,
  `altname` varchar(55) NOT NULL,
  `keywords` text,
  `description` text,
  `allow_comments` int(1) NOT NULL,
  `allow_rating` int(1) NOT NULL,
  `allow_index` int(1) NOT NULL,
  `score` float(6,3) default NULL,
  `votes` smallint(5) NOT NULL,
  `views` smallint(5) NOT NULL,
  `comments` smallint(5) NOT NULL,
  `fields` text NOT NULL,
  `groups` varchar(55) NOT NULL,
  `fixed` int(1) NOT NULL,
  `active` int(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `altname` (`altname`),
  KEY `active` (`active`),
  KEY `date` (`date`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]online`;
CREATE TABLE `[prefix]online` (
  `uid` int(11) NOT NULL,
  `time` int(10) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `group` int(11) NOT NULL,
  `url` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]plugins`;
CREATE TABLE `[prefix]plugins` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(55) NOT NULL,
  `content` text NOT NULL,
  `file` varchar(55) NOT NULL,
  `priority` tinyint(2) unsigned NOT NULL,
  `type` varchar(55) default NULL,
  `service` varchar(44) NOT NULL,
  `showin` varchar(255) NOT NULL,
  `unshow` varchar(255) NOT NULL,
  `groups` varchar(255) NOT NULL,
  `free` tinyint(1) unsigned NOT NULL,
  `template` text,
  `active` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `active` (`active`),
  KEY `priority` (`priority`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]pm`;
CREATE TABLE `[prefix]pm` (
  `id` int(11) NOT NULL auto_increment,
  `toid` int(11) NOT NULL,
  `fromid` int(11) NOT NULL,
  `message` text,
  `time` varchar(55) NOT NULL,
  `status` smallint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]poll_questions`;
CREATE TABLE `[prefix]poll_questions` (
  `id` int(11) NOT NULL auto_increment,
  `pid` int(11) NOT NULL,
  `variant` varchar(55) NOT NULL,
  `position` smallint(5) NOT NULL,
  `vote` int(11) default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]poll_voting`;
CREATE TABLE `[prefix]poll_voting` (
  `id` int(11) NOT NULL auto_increment,
  `uid` int(11) NOT NULL,
  `pid` int(11) NOT NULL,
  `ip` varchar(55) NOT NULL,
  `time` varchar(55) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]polls`;
CREATE TABLE `[prefix]polls` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `votes` int(5) NOT NULL,
  `max` int(2) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]ratings`;
CREATE TABLE `[prefix]ratings` (
  `_` int(11) NOT NULL auto_increment,
  `id` smallint(5) NOT NULL,
  `uid` smallint(5) NOT NULL,
  `mod` varchar(55) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(55) NOT NULL,
  PRIMARY KEY  (`_`),
  KEY `mod` (`mod`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]tags`;
CREATE TABLE `[prefix]tags` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(55) NOT NULL,
  `module` varchar(55) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `module` (`module`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]user_carma`;
CREATE TABLE `[prefix]user_carma` (
  `_id` int(11) NOT NULL auto_increment,
  `from` int(11) NOT NULL,
  `to` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `do` varchar(5) default NULL,
  `time` int(11) NOT NULL,
  PRIMARY KEY  (`_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]user_friends`;
CREATE TABLE `[prefix]user_friends` (
  `who_invite` int(9) NOT NULL,
  `whom_invite` int(9) NOT NULL,
  `confirmed` int(1) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]user_visitors`;
CREATE TABLE `[prefix]user_visitors` (
  `id` int(9) NOT NULL,
  `visitor` int(9) NOT NULL,
  `time` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `[prefix]users`;
CREATE TABLE `[prefix]users` (
  `id` int(11) NOT NULL auto_increment,
  `nick` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `tail` varchar(55) NOT NULL,
  `email` varchar(255) NOT NULL,
  `status` varchar(255) default NULL,
  `icq` varchar(55) NOT NULL,
  `skype` varchar(55) NOT NULL,
  `surname` varchar(55) NOT NULL,
  `name` varchar(55) NOT NULL,
  `ochestvo` varchar(55) NOT NULL,
  `place` varchar(255) NOT NULL,
  `age` int(3) NOT NULL,
  `sex` int(1) NOT NULL,
  `birthday` varchar(55) NOT NULL,
  `hobby` varchar(255) NOT NULL,
  `signature` text,
  `points` int(11) default '0',
  `carma` int(11) NOT NULL,
  `user_comments` int(11) NOT NULL,
  `user_news` int(11) NOT NULL,
  `group` int(11) NOT NULL,
  `exgroup` int(3) NOT NULL,
  `last_visit` int(11) NOT NULL,
  `regdate` int(11) NOT NULL,
  `active` int(1) NOT NULL,
  `ip` varchar(55) NOT NULL,
  `fields` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `nick_2` (`nick`),
  KEY `nick` (`nick`),
  KEY `ip` (`ip`),
  KEY `active` (`active`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `[prefix]xfields`;
CREATE TABLE `[prefix]xfields` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `type` smallint(1) NOT NULL,
  `content` text NOT NULL,
  `to_user` int(1) NOT NULL,
  `module` varchar(55) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
        