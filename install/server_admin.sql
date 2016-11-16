SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `db_user` (
  `id` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `password` varchar(32) NOT NULL,
  `status` enum('on','off','new') NOT NULL,
  `rechte` set('usr','mod','adm') NOT NULL DEFAULT 'usr',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `db_user_config` (
  `id` int(11) NOT NULL,
  `username` varchar(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `name` char(15) NOT NULL,
  `value` varchar(255) NOT NULL,
  `info` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `db_user_contact` (
  `id` int(11) NOT NULL,
  `username` char(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `email` varchar(60) NOT NULL,
  `pushover` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `db_user_favorite` (
  `id` int(11) NOT NULL,
  `username` char(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `server_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `db_user_private` (
  `id` int(11) NOT NULL,
  `username` char(100) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `salutation` char(12) NOT NULL DEFAULT 'Herr',
  `company` varchar(100) NOT NULL,
  `lastname` varchar(25) NOT NULL,
  `firstname` varchar(25) NOT NULL,
  `displayName` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `db_user_secure` (
  `id` int(11) NOT NULL,
  `username` char(100) NOT NULL,
  `countLogin` int(11) NOT NULL,
  `authCode` char(6) NOT NULL,
  `lastLogin` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `lastAuthCode` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `psm_last_update` (
  `last_update` datetime NOT NULL,
  `counter` int(2) NOT NULL,
  `updatet` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `psm_log` (
  `log_id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `type` enum('status','email','sms','pushover','updater') NOT NULL,
  `message` varchar(255) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `psm_servers` (
  `server_id` int(11) UNSIGNED NOT NULL,
  `ip` varchar(100) NOT NULL,
  `port` int(5) UNSIGNED NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` enum('service','website','reminder') NOT NULL DEFAULT 'service',
  `pattern` varchar(255) NOT NULL,
  `status` enum('on','off','warn') NOT NULL DEFAULT 'on',
  `rtime` float(9,7) DEFAULT NULL,
  `last_online` datetime DEFAULT NULL,
  `last_check` datetime DEFAULT NULL,
  `active` enum('yes','no') NOT NULL DEFAULT 'yes',
  `email` enum('yes','no') NOT NULL DEFAULT 'yes',
  `pushover` enum('yes','no') NOT NULL DEFAULT 'yes',
  `warning_threshold` mediumint(1) NOT NULL DEFAULT '1',
  `warning_threshold_counter` mediumint(1) NOT NULL,
  `description` text NOT NULL,
  `home_system` int(11) NOT NULL,
  `end_date` date NOT NULL,
  `warn_date` date NOT NULL,
  `isWarning` int(1) DEFAULT NULL,
  `user` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `psm_servers_history` (
  `servers_history_id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `latency_min` float(9,7) NOT NULL,
  `latency_avg` float(9,7) NOT NULL,
  `latency_max` float(9,7) NOT NULL,
  `checks_total` int(11) UNSIGNED NOT NULL,
  `checks_failed` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `psm_servers_uptime` (
  `servers_uptime_id` int(11) UNSIGNED NOT NULL,
  `server_id` int(11) UNSIGNED NOT NULL,
  `date` datetime NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL,
  `latency` float(9,7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `psm_users` (
  `user_id` int(11) UNSIGNED NOT NULL,
  `user_name` varchar(64) NOT NULL COMMENT 'user''s name, unique',
  `pushover_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_config` (
  `id` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `field` char(20) NOT NULL DEFAULT 'text'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_dns_cron` (
  `id` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `ipadresse` char(15) NOT NULL,
  `hostname` char(20) NOT NULL,
  `serverart` char(120) NOT NULL,
  `meldung` varchar(255) NOT NULL,
  `fehler` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_hardware` (
  `id` int(11) NOT NULL,
  `bezeichnung` varchar(255) NOT NULL,
  `kategorie` char(100) NOT NULL,
  `inventarnummer` char(100) NOT NULL,
  `kaufdatum` date NOT NULL,
  `hersteller` varchar(255) NOT NULL,
  `modell` varchar(255) NOT NULL,
  `seriennummer` char(100) NOT NULL,
  `zuordnung` char(100) NOT NULL,
  `beschreibung` text NOT NULL,
  `inventur` enum('ja','nein') NOT NULL DEFAULT 'ja'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_hardware_details` (
  `id` int(11) NOT NULL,
  `hardware_id` int(11) NOT NULL,
  `form_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `form_value` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_international` (
  `id` varchar(10) NOT NULL DEFAULT '',
  `aktiv` enum('ja','nein') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_inventur` (
  `id` int(11) NOT NULL,
  `barcode` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_knowledge` (
  `id` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `page_content` text NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `visible` int(1) NOT NULL DEFAULT '1',
  `version` decimal(2,1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_knowledge_history` (
  `id` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `version` decimal(2,1) NOT NULL,
  `datum` datetime NOT NULL,
  `user` varchar(100) NOT NULL,
  `content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_language_de` (
  `id` int(11) NOT NULL,
  `text_name` char(60) NOT NULL,
  `text_value` text NOT NULL,
  `art` enum('sys','usr','not') NOT NULL DEFAULT 'sys'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_license` (
  `id` int(11) NOT NULL,
  `hersteller` varchar(200) NOT NULL,
  `produkt` varchar(200) NOT NULL,
  `version` char(50) NOT NULL,
  `licensekey` varchar(255) NOT NULL,
  `anzahl` int(5) NOT NULL,
  `beschreibung` text NOT NULL,
  `vmware` int(11) NOT NULL DEFAULT '0',
  `barcode` varchar(200) NOT NULL,
  `zuordnung` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_news` (
  `id` int(11) NOT NULL,
  `datum` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` char(20) CHARACTER SET latin1 NOT NULL,
  `titel` varchar(255) CHARACTER SET tis620 COLLATE tis620_bin NOT NULL,
  `nachricht` text CHARACTER SET latin1 NOT NULL,
  `controller` char(30) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_server` (
  `id` int(11) NOT NULL,
  `bezeichnung` varchar(255) CHARACTER SET latin1 NOT NULL,
  `inventarnummer` char(30) CHARACTER SET latin1 NOT NULL,
  `hostname` char(30) CHARACTER SET latin1 NOT NULL,
  `aliase` varchar(255) CHARACTER SET latin1 NOT NULL,
  `ipadressen` char(50) CHARACTER SET latin1 NOT NULL,
  `standort` char(25) CHARACTER SET latin1 NOT NULL,
  `betriebssystem` varchar(255) CHARACTER SET latin1 NOT NULL,
  `technischedaten` varchar(255) CHARACTER SET latin1 NOT NULL,
  `verwendungszweck` varchar(255) CHARACTER SET latin1 NOT NULL,
  `beschreibung` text CHARACTER SET latin1 NOT NULL,
  `serverart` enum('server','vmware') CHARACTER SET latin1 NOT NULL,
  `service_relations` char(50) CHARACTER SET latin1 NOT NULL,
  `live_dns` enum('on','off') CHARACTER SET latin1 NOT NULL DEFAULT 'on',
  `prio` int(1) NOT NULL,
  `wartung` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_server_ports` (
  `lastcheck` datetime NOT NULL,
  `ipadresse` char(20) NOT NULL,
  `port` int(5) NOT NULL,
  `bezeichnung` varchar(255) NOT NULL,
  `beschreibung` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_submenu` (
  `id` int(11) NOT NULL,
  `controller` char(30) CHARACTER SET latin1 NOT NULL,
  `controller_ziel` char(30) CHARACTER SET latin1 NOT NULL,
  `methode` char(30) CHARACTER SET latin1 NOT NULL,
  `bezeichnung` char(30) CHARACTER SET latin1 NOT NULL,
  `lfdnr` int(2) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_system_details` (
  `id` int(11) NOT NULL,
  `systemid` int(11) NOT NULL,
  `form_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `form_value` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `wos_tcp_port` (
  `port` int(11) NOT NULL,
  `bezeichnung` varchar(100) CHARACTER SET latin1 NOT NULL,
  `beschreibung` varchar(255) CHARACTER SET latin1 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `db_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

ALTER TABLE `db_user_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

ALTER TABLE `db_user_contact`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

ALTER TABLE `db_user_favorite`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

ALTER TABLE `db_user_private`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

ALTER TABLE `db_user_secure`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`);

ALTER TABLE `psm_log`
  ADD PRIMARY KEY (`log_id`);

ALTER TABLE `psm_servers`
  ADD PRIMARY KEY (`server_id`);

ALTER TABLE `psm_servers_history`
  ADD PRIMARY KEY (`servers_history_id`),
  ADD UNIQUE KEY `server_id_date` (`server_id`,`date`);

ALTER TABLE `psm_servers_uptime`
  ADD PRIMARY KEY (`servers_uptime_id`),
  ADD KEY `server_id` (`server_id`);

ALTER TABLE `psm_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `unique_username` (`user_name`);

ALTER TABLE `wos_config`
  ADD PRIMARY KEY (`id`),
  ADD KEY `key` (`id`);

ALTER TABLE `wos_dns_cron`
  ADD PRIMARY KEY (`id`),
  ADD KEY `server_id` (`sid`),
  ADD KEY `sid` (`sid`);

ALTER TABLE `wos_hardware`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `inventarnummer` (`inventarnummer`,`seriennummer`);

ALTER TABLE `wos_hardware_details`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wos_international`
  ADD UNIQUE KEY `id` (`id`);

ALTER TABLE `wos_inventur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `id` (`id`),
  ADD KEY `barcode_2` (`barcode`);

ALTER TABLE `wos_knowledge`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

ALTER TABLE `wos_knowledge_history`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wos_language_de`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `text_name` (`text_name`);

ALTER TABLE `wos_license`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wos_news`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wos_server`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ipadressen` (`ipadressen`),
  ADD UNIQUE KEY `hostname` (`hostname`);

ALTER TABLE `wos_server_ports`
  ADD KEY `ipadresse` (`ipadresse`);

ALTER TABLE `wos_submenu`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wos_system_details`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `wos_tcp_port`
  ADD PRIMARY KEY (`port`);


ALTER TABLE `db_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `db_user_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `db_user_contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `db_user_favorite`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `db_user_private`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `db_user_secure`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `psm_log`
  MODIFY `log_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `psm_servers`
  MODIFY `server_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
ALTER TABLE `psm_servers_history`
  MODIFY `servers_history_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `psm_servers_uptime`
  MODIFY `servers_uptime_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
ALTER TABLE `psm_users`
  MODIFY `user_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `wos_dns_cron`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `wos_hardware`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wos_hardware_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wos_inventur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wos_knowledge`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wos_knowledge_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wos_language_de`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=303;
ALTER TABLE `wos_license`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `wos_news`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
ALTER TABLE `wos_server`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
ALTER TABLE `wos_submenu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
ALTER TABLE `wos_system_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;