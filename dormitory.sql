
#
# Structure for table "building"
#

CREATE TABLE `building` (
  `building_id` int(2) NOT NULL DEFAULT '0' COMMENT '楼号',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '楼名',
  `floor_count` varchar(1) NOT NULL COMMENT '楼层数',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`building_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='宿舍楼表';

#
# Data for table "building"
#

INSERT INTO `building` VALUES (39,'39号楼',0);

#
# Structure for table "dormitory"
#

CREATE TABLE `dormitory` (
  `id` int(5) NOT NULL AUTO_INCREMENT COMMENT '宿舍id',
  `building_id` int(2) NOT NULL DEFAULT '0' COMMENT '所属楼号',
  `dormitory_number` varchar(5) NOT NULL DEFAULT '' COMMENT '宿舍门号',
  `floor` int(1) NOT NULL DEFAULT '0' COMMENT '楼层',
  `max_student_count` int(2) NOT NULL DEFAULT '0' COMMENT '宿舍标准人数',
  `real_student_count` int(2) NOT NULL DEFAULT '0' COMMENT '宿舍实际人数',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='宿舍表';

#
# Data for table "dormitory"
#

INSERT INTO `dormitory` VALUES (1,39,'101',1,4,1,0),(2,39,'102',1,4,0,0);

#
# Structure for table "dormitory_bed"
#

CREATE TABLE `dormitory_bed` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `dormitory_id` int(11) NOT NULL DEFAULT '0' COMMENT '宿舍id',
  `bed_number` int(1) NOT NULL DEFAULT '0' COMMENT '床号',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态 0:未占用 1:已占用',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='宿舍床号表';

#
# Data for table "dormitory_bed"
#

INSERT INTO `dormitory_bed` VALUES (1,1,1,1),(2,1,2,0),(3,1,3,0),(4,1,4,0);

#
# Structure for table "student"
#

CREATE TABLE `student` (
  `student_id` varchar(20) NOT NULL DEFAULT '' COMMENT '学号',
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '学生姓名',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别 0:男 1:女',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '手机',
  `class` varchar(30) NOT NULL DEFAULT '' COMMENT '专业班级',
  `birthday` int(11) NOT NULL DEFAULT '0' COMMENT '出生日期(时间戳)',
  `grade` int(4) NOT NULL DEFAULT '0' COMMENT '级数(2015,2016,2017...)',
  `building_id` int(2) NOT NULL DEFAULT '0' COMMENT '楼号',
  `dormitory_id` int(5) NOT NULL DEFAULT '0' COMMENT '宿舍id',
  `bed_id` int(11) NOT NULL DEFAULT '0' COMMENT '床id',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间(时间戳)',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`student_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='学生住宿表';

#
# Data for table "student"
#

INSERT INTO `student` VALUES ('2015014001','张三',0,'17811111111','信工151',860288400,2015,39,1,1,1551925469,0);

#
# Structure for table "user"
#

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `username` varchar(20) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码(md5加密)',
  `name` varchar(20) DEFAULT '' COMMENT '姓名',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态:0禁用;1启用',
  `building_id` tinyint(2) NOT NULL DEFAULT '0' COMMENT '所属楼号',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间(时间戳)',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间(时间戳)',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

#
# Data for table "user"
#

INSERT INTO `user` VALUES (1,'admin','e10adc3949ba59abbe56e057f20f883e','',1,0,1551944489,0),(2,'building39','e10adc3949ba59abbe56e057f20f883e','39号楼宿管',1,39,1551940371,0);
