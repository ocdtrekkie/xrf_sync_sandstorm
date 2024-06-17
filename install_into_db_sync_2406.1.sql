UPDATE `g_config` SET `auth_version` = '0S.2';
ALTER TABLE `y_nodes`
  ADD `last_winver` VARCHAR(20) NOT NULL DEFAULT '' COMMENT
  'Windows version of node OS' AFTER `last_ip_addr`;