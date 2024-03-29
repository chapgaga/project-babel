--
-- Project Babel Database Structure
--
-- $Id$
--

--
-- UTF8
--

SET NAMES utf8;
SET CHARACTER SET utf8;
SET COLLATION_CONNECTION='utf8_general_ci';

-- 
-- Database: `babel`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_add_url`
-- 

CREATE TABLE `babel_add_url` (
  `url_id` int(10) unsigned NOT NULL auto_increment,
  `url_uid` int(10) unsigned NOT NULL default '0',
  `url_url` varchar(240) NOT NULL,
  `url_title` varchar(200) NOT NULL,
  `url_notes` varchar(200) default NULL,
  `url_hash` char(32) NOT NULL,
  `url_created` int(10) unsigned NOT NULL default '0',
  `url_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`url_id`),
  KEY `INDEX_UID` (`url_uid`),
  KEY `INDEX_HASH` (`url_hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Add URL';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_bit_torrent`
-- 

CREATE TABLE `babel_bit_torrent` (
  `bit_id` int(10) unsigned NOT NULL,
  `bit_uid` int(10) unsigned NOT NULL default '0',
  `bit_cid` int(10) unsigned NOT NULL default '0',
  `bit_title` varchar(100) NOT NULL,
  `bit_description` text NOT NULL,
  `bit_torrent` binary(1) NOT NULL,
  `bit_downloads` int(10) unsigned NOT NULL default '0',
  `bit_rating` float unsigned NOT NULL default '0',
  `bit_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_channel`
-- 

CREATE TABLE `babel_channel` (
  `chl_id` int(10) unsigned NOT NULL auto_increment,
  `chl_pid` int(10) unsigned NOT NULL default '0',
  `chl_title` varchar(200) NOT NULL default '',
  `chl_url` varchar(200) NOT NULL default '',
  `chl_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`chl_id`),
  KEY `INDEX_PID` (`chl_pid`),
  KEY `INDEX_TITLE` (`chl_title`),
  KEY `INDEX_URL` (`chl_url`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 COMMENT='Babel Channel Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_dry_item`
-- 

CREATE TABLE `babel_dry_item` (
  `itm_id` int(10) unsigned NOT NULL auto_increment,
  `itm_uid` int(10) unsigned NOT NULL default '0',
  `itm_name` varchar(100) NOT NULL default '',
  `itm_title` varchar(100) default NULL,
  `itm_substance` text,
  `itm_revisions` int(10) unsigned NOT NULL default '0',
  `itm_password` varchar(32) default NULL,
  `itm_type` varchar(32) default NULL,
  `itm_created` int(10) unsigned NOT NULL default '0',
  `itm_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`itm_id`),
  KEY `INDEX_UID` (`itm_uid`),
  KEY `INDEX_NAME` (`itm_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Babel Dry Item';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_expense`
-- 

CREATE TABLE `babel_expense` (
  `exp_id` int(10) unsigned NOT NULL auto_increment,
  `exp_uid` int(10) unsigned NOT NULL default '0',
  `exp_amount` double NOT NULL default '0',
  `exp_type` int(10) unsigned NOT NULL default '0',
  `exp_memo` text,
  `exp_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`exp_id`),
  KEY `INDEX_UID` (`exp_uid`),
  KEY `INDEX_TYPE` (`exp_type`),
  KEY `INDEX_CREATED` (`exp_created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Expense Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_favorite`
-- 

CREATE TABLE `babel_favorite` (
  `fav_id` int(10) unsigned NOT NULL auto_increment,
  `fav_uid` int(10) unsigned NOT NULL default '0',
  `fav_title` varchar(200) NOT NULL default '',
  `fav_author` varchar(100) NOT NULL default '',
  `fav_res` varchar(200) NOT NULL default '',
  `fav_brief` text,
  `fav_type` int(10) unsigned NOT NULL default '0',
  `fav_created` int(10) unsigned NOT NULL default '0',
  `fav_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fav_id`),
  KEY `INDEX_UID` (`fav_uid`),
  KEY `INDEX_RES` (`fav_res`),
  KEY `INDEX_TYPE` (`fav_type`),
  KEY `INDEX_CREATED` (`fav_created`),
  KEY `INDEX_LASTUPDATED` (`fav_lastupdated`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Favorite Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_foundation`
-- 

CREATE TABLE `babel_foundation` (
  `fdt_id` int(10) unsigned NOT NULL auto_increment,
  `fdt_uid` int(10) unsigned NOT NULL default '0',
  `fdt_title` varchar(40) NOT NULL default 'Untitled foundation',
  `fdt_money` int(11) NOT NULL default '0',
  `fdt_type` int(10) unsigned NOT NULL default '0',
  `fdt_brief` text,
  `fdt_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`fdt_id`),
  KEY `INDEX_UID` (`fdt_uid`),
  KEY `INDEX_TYPE` (`fdt_type`),
  KEY `INDEX_CREATED` (`fdt_created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Babel Foundation Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_friend`
-- 

CREATE TABLE `babel_friend` (
  `frd_id` int(10) unsigned NOT NULL auto_increment,
  `frd_uid` int(10) unsigned NOT NULL default '0',
  `frd_fid` int(10) unsigned NOT NULL default '0',
  `frd_description` varchar(200) NOT NULL default '',
  `frd_created` int(10) unsigned NOT NULL default '0',
  `frd_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`frd_id`),
  KEY `INDEX_UID` (`frd_uid`),
  KEY `INDEX_FID` (`frd_fid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 PACK_KEYS=0 COMMENT='Babel Friend Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_geo_been`
-- 

CREATE TABLE `babel_geo_been` (
  `gbn_id` int(10) unsigned NOT NULL auto_increment,
  `gbn_uid` int(10) unsigned NOT NULL default '0',
  `gbn_geo` varchar(100) NOT NULL default '',
  `gbn_impression` text,
  `gbn_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`gbn_id`),
  KEY `INDEX_UID` (`gbn_uid`),
  KEY `INDEX_GEO` (`gbn_geo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Geo Been';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_geo_going`
-- 

CREATE TABLE `babel_geo_going` (
  `ggg_id` int(10) unsigned NOT NULL auto_increment,
  `ggg_uid` int(10) unsigned NOT NULL default '0',
  `ggg_geo` varchar(100) NOT NULL default '',
  `ggg_impression` text,
  `ggg_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ggg_id`),
  KEY `INDEX_UID` (`ggg_uid`),
  KEY `INDEX_GEO` (`ggg_geo`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Geo Going';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_geo_usage_simple`
-- 

CREATE TABLE `babel_geo_usage_simple` (
  `gus_geo` varchar(100) NOT NULL default 'earth',
  `gus_name_cn` varchar(100) NOT NULL default '地球',
  `gus_name_en` varchar(100) NOT NULL default 'Earth',
  `gus_hits` int(10) unsigned NOT NULL default '0',
  `gus_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`gus_geo`),
  UNIQUE KEY `INDEX_NAME_CN` (`gus_name_cn`),
  UNIQUE KEY `INDEX_NAME_EN` (`gus_name_en`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Macau Geo Usage';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_group`
-- 

CREATE TABLE `babel_group` (
  `grp_id` int(10) unsigned NOT NULL auto_increment,
  `grp_oid` int(10) unsigned NOT NULL default '0',
  `grp_nick` varchar(40) NOT NULL default '',
  `grp_brief` longtext,
  `grp_created` int(10) unsigned NOT NULL default '0',
  `grp_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`grp_id`),
  KEY `INDEX_OID` (`grp_oid`),
  KEY `INDEX_NICK` (`grp_nick`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Group Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_ing_update`
-- 

CREATE TABLE `babel_ing_update` (
  `ing_id` int(10) unsigned NOT NULL auto_increment,
  `ing_uid` int(10) unsigned NOT NULL default '0',
  `ing_doing` varchar(150) default NULL,
  `ing_source` int(10) unsigned NOT NULL default '0',
  `ing_favs` int(10) unsigned NOT NULL default '0',
  `ing_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ing_id`),
  KEY `INDEX_UID` (`ing_uid`),
  KEY `INDEX_CREATED` (`ing_created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel ING Updates';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_log_email_sent`
-- 

CREATE TABLE `babel_log_email_sent` (
  `log_id` int(10) unsigned NOT NULL auto_increment,
  `log_uid` int(10) unsigned NOT NULL default '0',
  `log_email` varchar(100) default NULL,
  `log_email_type` int(10) unsigned NOT NULL default '0',
  `log_email_sent` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Babel Log E-mail Sent';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_message`
-- 

CREATE TABLE `babel_message` (
  `msg_id` int(10) unsigned NOT NULL auto_increment,
  `msg_sid` int(10) unsigned NOT NULL default '0',
  `msg_rid` int(10) unsigned NOT NULL default '0',
  `msg_body` text,
  `msg_draft` int(10) unsigned NOT NULL default '0',
  `msg_hits` int(10) unsigned NOT NULL default '0',
  `msg_created` int(10) unsigned NOT NULL default '0',
  `msg_sent` int(10) unsigned NOT NULL default '0',
  `msg_opened` int(10) unsigned NOT NULL default '0',
  `msg_sdeleted` int(10) unsigned NOT NULL default '0',
  `msg_rdeleted` int(10) unsigned NOT NULL default '0',
  `msg_lastaccessed` int(10) unsigned NOT NULL default '0',
  `msg_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`msg_id`),
  KEY `INDEX_SID` (`msg_sid`),
  KEY `INDEX_RID` (`msg_rid`),
  KEY `INDEX_DRAFT` (`msg_draft`),
  KEY `INDEX_CREATED` (`msg_created`),
  KEY `INDEX_SENT` (`msg_sent`),
  KEY `INDEX_SDELETED` (`msg_sdeleted`),
  KEY `INDEX_RDELETED` (`msg_rdeleted`),
  KEY `INDEX_OPENED` (`msg_opened`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Message Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_mobile_data`
-- 

CREATE TABLE `babel_mobile_data` (
  `mob_no` int(10) unsigned NOT NULL default '0',
  `mob_area` varchar(20) NOT NULL default '',
  `mob_subarea` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`mob_no`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Babel Mobile Data Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_node`
-- 

CREATE TABLE `babel_node` (
  `nod_id` int(10) unsigned NOT NULL auto_increment,
  `nod_pid` int(10) unsigned NOT NULL default '5',
  `nod_uid` int(10) unsigned NOT NULL default '1',
  `nod_sid` int(10) unsigned NOT NULL default '5',
  `nod_level` int(10) unsigned NOT NULL default '2',
  `nod_name` varchar(100) NOT NULL default 'node',
  `nod_title` varchar(100) NOT NULL default 'Untitled node',
  `nod_title_en_us` varchar(100) default NULL,
  `nod_title_zh_cn` varchar(100) default NULL,
  `nod_title_de_de` varchar(100) default NULL,
  `nod_description` text,
  `nod_header` text,
  `nod_footer` text,
  `nod_portrait` varchar(40) default NULL,
  `nod_topics` int(10) unsigned NOT NULL default '0',
  `nod_favs` int(10) unsigned NOT NULL default '0',
  `nod_weight` int(11) NOT NULL default '0',
  `nod_created` int(10) unsigned NOT NULL default '0',
  `nod_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`nod_id`),
  KEY `INDEX_PID` (`nod_pid`),
  KEY `INDEX_UID` (`nod_uid`),
  KEY `INDEX_SID` (`nod_sid`),
  KEY `INDEX_TOPICS` (`nod_topics`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Node Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_online`
-- 

CREATE TABLE `babel_online` (
  `onl_hash` varchar(32) NOT NULL default '',
  `onl_nick` varchar(40) default NULL,
  `onl_ua` varchar(200) default NULL,
  `onl_ip` varchar(15) default '0.0.0.0',
  `onl_uri` varchar(200) default '/',
  `onl_ref` varchar(200) default '/',
  `onl_created` int(10) unsigned NOT NULL default '0',
  `onl_lastmoved` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`onl_hash`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8 COMMENT='Babel Online Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_passwd`
-- 

CREATE TABLE `babel_passwd` (
  `pwd_id` int(10) unsigned NOT NULL auto_increment,
  `pwd_uid` int(10) unsigned NOT NULL default '0',
  `pwd_hash` varchar(100) default NULL,
  `pwd_ip` varchar(15) NOT NULL default '0.0.0.0',
  `pwd_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pwd_id`),
  KEY `INDEX_UID` (`pwd_uid`),
  KEY `INDEX_HASH` (`pwd_hash`),
  KEY `INDEX_CREATED` (`pwd_created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Passwd Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_post`
-- 

CREATE TABLE `babel_post` (
  `pst_id` int(10) unsigned NOT NULL auto_increment,
  `pst_tid` int(10) unsigned NOT NULL default '5',
  `pst_uid` int(10) unsigned NOT NULL default '0',
  `pst_title` varchar(100) default 'Untitled reply',
  `pst_content` text,
  `pst_created` int(10) unsigned NOT NULL default '0',
  `pst_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`pst_id`),
  KEY `INDEX_TID` (`pst_tid`),
  KEY `INDEX_UID` (`pst_uid`),
  KEY `INDEX_CREATED` (`pst_created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Post Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_related`
-- 

CREATE TABLE `babel_related` (
  `rlt_id` int(10) unsigned NOT NULL auto_increment,
  `rlt_pid` int(10) unsigned NOT NULL default '0',
  `rlt_title` varchar(200) NOT NULL default '',
  `rlt_url` varchar(200) NOT NULL default '',
  `rlt_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`rlt_id`),
  KEY `INDEX_PID` (`rlt_pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Related Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_savepoint`
-- 

CREATE TABLE `babel_savepoint` (
  `svp_id` int(10) unsigned NOT NULL auto_increment,
  `svp_uid` int(10) unsigned NOT NULL default '0',
  `svp_url` text NOT NULL,
  `svp_rank` int(10) unsigned NOT NULL default '0',
  `svp_created` int(10) unsigned NOT NULL default '0',
  `svp_lastupdated` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`svp_id`),
  KEY `INDEX_UID` (`svp_uid`),
  KEY `INDEX_URL` (`svp_url`(333))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Savepoint Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_storage_simple`
-- 

CREATE TABLE `babel_storage_simple` (
  `ssp_id` int(10) unsigned NOT NULL auto_increment,
  `ssp_owner` int(10) unsigned NOT NULL default '0',
  `ssp_name` varchar(128) NOT NULL,
  `ssp_content` mediumblob,
  `ssp_hash` char(32) NOT NULL,
  `ssp_saved` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ssp_id`),
  UNIQUE KEY `INDEX_NAME` (`ssp_name`),
  KEY `INDEX_OWNER` (`ssp_owner`),
  KEY `INDEX_HASH` (`ssp_hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Simple Storage';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_surprise`
-- 

CREATE TABLE `babel_surprise` (
  `srp_id` int(10) unsigned NOT NULL auto_increment,
  `srp_uid` int(10) unsigned NOT NULL default '0',
  `srp_amount` int(11) NOT NULL default '0',
  `srp_type` int(10) unsigned NOT NULL default '0',
  `srp_memo` text,
  `srp_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`srp_id`),
  KEY `INDEX_UID` (`srp_uid`),
  KEY `INDEX_TYPE` (`srp_type`),
  KEY `INDEX_CREATED` (`srp_created`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Babel Surprise Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_topic`
-- 

CREATE TABLE `babel_topic` (
  `tpc_id` int(10) unsigned NOT NULL auto_increment,
  `tpc_pid` int(10) unsigned NOT NULL default '5',
  `tpc_uid` int(10) unsigned NOT NULL default '0',
  `tpc_title` varchar(100) NOT NULL default 'Untitled topic',
  `tpc_description` text,
  `tpc_content` text,
  `tpc_hits` int(10) unsigned NOT NULL default '0',
  `tpc_refs` int(10) unsigned NOT NULL default '0',
  `tpc_posts` int(10) unsigned NOT NULL default '0',
  `tpc_favs` int(10) unsigned NOT NULL default '0',
  `tpc_profitable` smallint(5) unsigned NOT NULL default '0',
  `tpc_followers` text,
  `tpc_flag` int(10) unsigned NOT NULL default '0',
  `tpc_created` int(10) unsigned NOT NULL default '0',
  `tpc_lastupdated` int(10) unsigned NOT NULL default '0',
  `tpc_lasttouched` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`tpc_id`),
  KEY `INDEX_PID` (`tpc_pid`),
  KEY `INDEX_UID` (`tpc_uid`),
  KEY `INDEX_POSTS` (`tpc_posts`),
  KEY `INDEX_LASTTOUCHED` (`tpc_lasttouched`),
  KEY `INDEX_CREATED` (`tpc_created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Topic Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_user`
-- 

CREATE TABLE `babel_user` (
  `usr_id` int(10) unsigned NOT NULL auto_increment,
  `usr_gid` int(10) unsigned NOT NULL default '0',
  `usr_nick` varchar(40) NOT NULL default '',
  `usr_password` varchar(40) NOT NULL default '',
  `usr_lang` varchar(10) default NULL,
  `usr_email` varchar(100) default NULL,
  `usr_email_notify` varchar(100) default NULL,
  `usr_google_account` varchar(200) default NULL,
  `usr_geo` varchar(100) NOT NULL default 'earth',
  `usr_full` varchar(40) default NULL,
  `usr_addr` varchar(200) default NULL,
  `usr_telephone` varchar(40) default NULL,
  `usr_skype` varchar(40) default NULL,
  `usr_lastfm` varchar(40) default NULL,
  `usr_identity` varchar(18) default NULL,
  `usr_gender` smallint(6) NOT NULL default '0',
  `usr_birthday` int(10) unsigned NOT NULL default '0',
  `usr_religion` varchar(50) default NULL,
  `usr_religion_permission` int(10) unsigned NOT NULL default '0',
  `usr_religion_lastconverted` int(10) unsigned NOT NULL default '0',
  `usr_portrait` varchar(40) default NULL,
  `usr_brief` longtext,
  `usr_money` double NOT NULL default '0',
  `usr_width` int(10) unsigned NOT NULL default '1024',
  `usr_hits` int(10) unsigned NOT NULL default '0',
  `usr_logins` int(10) unsigned NOT NULL default '0',
  `usr_api` int(10) unsigned NOT NULL default '0',
  `usr_editor` varchar(20) NOT NULL default 'default',
  `usr_created` int(10) unsigned NOT NULL default '0',
  `usr_lastupdated` int(10) unsigned NOT NULL default '0',
  `usr_lastlogin` int(10) unsigned NOT NULL default '0',
  `usr_lastlogin_ua` text,
  `usr_sw_shuffle_cloud` smallint(6) NOT NULL default '1',
  `usr_sw_right_friends` int(10) unsigned NOT NULL default '0',
  `usr_sw_top_wealth` smallint(6) NOT NULL default '0',
  `usr_sw_shell` smallint(6) NOT NULL default '0',
  `usr_sw_notify_reply` smallint(6) NOT NULL default '0',
  `usr_sw_notify_reply_all` smallint(6) NOT NULL default '0',
  PRIMARY KEY  (`usr_id`),
  KEY `INDEX_GID` (`usr_gid`),
  KEY `INDEX_NICK` (`usr_nick`),
  KEY `INDEX_PASSWORD` (`usr_password`),
  KEY `INDEX_EMAIL` (`usr_email`),
  KEY `INDEX_API` (`usr_api`),
  KEY `INDEX_PORTRAIT` (`usr_portrait`),
  KEY `INDEX_HITS` (`usr_hits`),
  KEY `INDEX_LASTLOGIN` (`usr_lastlogin`),
  KEY `INDEX_GEO` (`usr_geo`),
  KEY `INDEX_GOOGLE_ACCOUNT` (`usr_google_account`),
  KEY `INDEX_CREATED` (`usr_created`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel User Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_user_portrait`
-- 

CREATE TABLE `babel_user_portrait` (
  `urp_id` int(10) unsigned NOT NULL auto_increment,
  `urp_filename` varchar(40) default NULL,
  `urp_content` blob NOT NULL,
  PRIMARY KEY  (`urp_id`),
  UNIQUE KEY `INDEX_FILENAME` (`urp_filename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_weblog`
-- 

CREATE TABLE `babel_weblog` (
  `blg_id` int(10) unsigned NOT NULL auto_increment,
  `blg_uid` int(10) unsigned NOT NULL default '0',
  `blg_title` varchar(50) NOT NULL default '',
  `blg_description` text,
  `blg_name` varchar(20) NOT NULL default '',
  `blg_portrait` varchar(20) default NULL,
  `blg_theme` varchar(20) NOT NULL default 'purple',
  `blg_license` varchar(50) default NULL,
  `blg_license_show` tinyint(3) unsigned NOT NULL default '0',
  `blg_mode` int(10) unsigned NOT NULL default '0',
  `blg_entries` int(10) unsigned NOT NULL default '0',
  `blg_comments` int(10) unsigned NOT NULL default '0',
  `blg_comment_permission` int(10) unsigned NOT NULL default '0',
  `blg_links` text,
  `blg_builds` int(10) unsigned NOT NULL default '0',
  `blg_dirty` tinyint(3) unsigned NOT NULL default '0',
  `blg_ing` tinyint(3) unsigned NOT NULL default '0',
  `blg_created` int(10) unsigned NOT NULL default '0',
  `blg_lastupdated` int(10) unsigned NOT NULL default '0',
  `blg_lastbuilt` int(10) unsigned NOT NULL default '0',
  `blg_expire` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`blg_id`),
  UNIQUE KEY `INDEX_NAME` (`blg_name`),
  KEY `INDEX_UID` (`blg_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Weblog';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_weblog_entry`
-- 

CREATE TABLE `babel_weblog_entry` (
  `bge_id` int(10) unsigned NOT NULL auto_increment,
  `bge_pid` int(10) unsigned NOT NULL default '0',
  `bge_uid` int(10) unsigned NOT NULL default '0',
  `bge_title` varchar(50) default NULL,
  `bge_body` text,
  `bge_comments` int(10) unsigned NOT NULL default '0',
  `bge_comment_permission` int(10) unsigned NOT NULL default '0',
  `bge_trackbacks` int(10) unsigned NOT NULL default '0',
  `bge_tags` varchar(200) default NULL,
  `bge_status` int(10) unsigned NOT NULL default '0',
  `bge_mode` int(10) unsigned NOT NULL default '0',
  `bge_revisions` int(10) unsigned NOT NULL default '0',
  `bge_hash` varchar(32) default NULL,
  `bge_created` int(10) unsigned NOT NULL default '0',
  `bge_lastupdated` int(10) unsigned NOT NULL default '0',
  `bge_published` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bge_id`),
  KEY `INDEX_PID` (`bge_pid`),
  KEY `INDEX_UID` (`bge_uid`),
  KEY `INDEX_HASH` (`bge_hash`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Weblog Entry';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_weblog_entry_comment`
-- 

CREATE TABLE `babel_weblog_entry_comment` (
  `bec_id` int(10) unsigned NOT NULL auto_increment,
  `bec_eid` int(10) unsigned NOT NULL default '0',
  `bec_uid` int(10) unsigned NOT NULL default '0',
  `bec_nick` varchar(20) NOT NULL default '',
  `bec_email` varchar(100) NOT NULL default '',
  `bec_url` varchar(200) default NULL,
  `bec_body` text NOT NULL,
  `bec_status` int(10) unsigned NOT NULL default '0',
  `bec_ip` varchar(15) NOT NULL default '0.0.0.0',
  `bec_created` int(10) unsigned NOT NULL default '0',
  `bec_approved` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bec_id`),
  KEY `INDEX_EID` (`bec_eid`),
  KEY `INDEX_UID` (`bec_uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Weblog Entry Comment';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_weblog_entry_tag`
-- 

CREATE TABLE `babel_weblog_entry_tag` (
  `bet_id` int(10) unsigned NOT NULL auto_increment,
  `bet_tag` varchar(20) NOT NULL default '',
  `bet_uid` int(10) unsigned NOT NULL default '0',
  `bet_eid` int(10) unsigned NOT NULL default '0',
  `bet_created` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`bet_id`),
  KEY `INDEX_TAG` (`bet_tag`),
  KEY `INDEX_UID` (`bet_uid`),
  KEY `INDEX_EID` (`bet_eid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel Weblog Entry Tag';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_weblog_portrait`
-- 

CREATE TABLE `babel_weblog_portrait` (
  `bgp_id` int(10) unsigned NOT NULL auto_increment,
  `bgp_filename` varchar(40) default NULL,
  `bgp_content` blob NOT NULL,
  PRIMARY KEY  (`bgp_id`),
  UNIQUE KEY `INDEX_FILENAME` (`bgp_filename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_zen_project`
-- 

CREATE TABLE `babel_zen_project` (
  `zpr_id` int(10) unsigned NOT NULL auto_increment,
  `zpr_uid` int(10) unsigned NOT NULL default '0',
  `zpr_private` int(10) unsigned NOT NULL default '0',
  `zpr_title` varchar(100) NOT NULL default '',
  `zpr_progress` int(10) unsigned NOT NULL default '0',
  `zpr_type` int(10) unsigned NOT NULL default '0',
  `zpr_tasks` int(10) unsigned NOT NULL default '0',
  `zpr_notes` int(10) unsigned NOT NULL default '0',
  `zpr_dbs` int(10) unsigned NOT NULL default '0',
  `zpr_created` int(10) unsigned NOT NULL default '0',
  `zpr_lastupdated` int(10) unsigned NOT NULL default '0',
  `zpr_lasttouched` int(10) unsigned NOT NULL default '0',
  `zpr_completed` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`zpr_id`),
  KEY `INDEX_UID` (`zpr_uid`),
  KEY `INDEX_PRIVATE` (`zpr_private`),
  KEY `INDEX_PROGRESS` (`zpr_progress`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel ZEN Project Table';

-- --------------------------------------------------------

-- 
-- Table structure for table `babel_zen_task`
-- 

CREATE TABLE `babel_zen_task` (
  `zta_id` int(10) unsigned NOT NULL auto_increment,
  `zta_uid` int(10) unsigned NOT NULL default '0',
  `zta_pid` int(10) unsigned NOT NULL default '0',
  `zta_title` varchar(100) NOT NULL default '',
  `zta_progress` int(10) unsigned NOT NULL default '0',
  `zta_created` int(10) unsigned NOT NULL default '0',
  `zta_lastupdated` int(10) unsigned NOT NULL default '0',
  `zta_lasttouched` int(10) unsigned NOT NULL default '0',
  `zta_completed` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`zta_id`),
  KEY `INDEX_UID` (`zta_uid`),
  KEY `INDEX_PID` (`zta_pid`),
  KEY `INDEX_PROGRESS` (`zta_progress`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Babel ZEN Task Table';

-- --------------------------------------------------------

INSERT INTO `babel_node`(`nod_pid`, `nod_level`, `nod_name`, `nod_title`) VALUES(1, 0, 'planescape', '异域');

INSERT INTO `babel_node`(`nod_pid`, `nod_level`, `nod_name`, `nod_title`) VALUES(1, 1, 'limbo', '混沌海');