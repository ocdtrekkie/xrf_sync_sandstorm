ALTER TABLE `y_nodes`
  ADD `last_ip_local` VARCHAR(32) NOT NULL DEFAULT '' COMMENT
  'Local IP address at last check in' AFTER `last_ip_addr`;