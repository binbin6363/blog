<?php

/*
FRecvName, FRecvAddr, FMessage, FSendName, FSendPhone, FCreateTime, FUpdateTime
CREATE TABLE `t_card_list` (
  `FId` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增id',
  `FRecvName` varchar(64) NOT NULL DEFAULT ''COMMENT '收件人名字',
  `FRecvAddr` varchar(255) NOT NULL DEFAULT ''COMMENT '收件人地址',
  `FMessage` varchar(255) NOT NULL DEFAULT ''COMMENT '留言',
  `FSendName` varchar(64) NOT NULL DEFAULT '' COMMENT '发送人名字',
  `FSendPhone` varchar(64) NOT NULL DEFAULT '' COMMENT '发送人手机',
  `FCreateTime` timestamp DEFAULT 0 COMMENT '创建时间',
  `FUpdateTime` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE COMMENT '更新时间',
  PRIMARY KEY (`FId`),
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
*/

$app = array(
    'db_host'            => 'imdis.cn',
    'db_user_name'       => 'root',
    'db_user_passwd'     => '123456',
    'db_name'            => 'db_post_card',
    'table_name'         => 't_card_list',
    'table_card_info_name' => 't_card_num',
    'db_port'            => 3306,
    'db_prefix'          => '',
    'create_table'       => 'CREATE TABLE `t_card_list` (
		`FId` int(20) unsigned NOT NULL AUTO_INCREMENT COMMENT \'自增id\',
		`FRecvName` varchar(64) NOT NULL DEFAULT \'\' COMMENT \'收件人名字\',
		`FRecvAddr` varchar(255) NOT NULL DEFAULT \'\' COMMENT \'收件人地址\',
		`FMessage` varchar(255) NOT NULL DEFAULT \'\' COMMENT \'留言\',
		`FSendName` varchar(64) NOT NULL DEFAULT \'\' COMMENT \'发送人名字\',
		`FSendPhone` varchar(64) NOT NULL DEFAULT \'\' COMMENT \'发送人手机\',
		`FCreateTime` timestamp DEFAULT 0 COMMENT \'创建时间\',
		`FUpdateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT \'更新时间\',
    `FCardStatus` int(10) DEFAULT 0 COMMENT \'投递状态\',
		PRIMARY KEY (`FId`),
    CONSTRAINT unique_cons UNIQUE (FRecvName ,FSendName)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8;',
);




?>
