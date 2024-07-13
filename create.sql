/**
 * 创建数据库
 */
create database chat_room;
use chat_room;
/**
 * 创建禁止ip
 */
CREATE TABLE `ban` (
  `ban_ip` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '黑名单ip'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='黑名单ip';
/**
 * 创建屏蔽词
 */
CREATE TABLE `block` (
  `block_word` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '屏蔽词',
  `replace` text COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '替换的词'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='屏蔽词列表';
/**
 * 创建聊天记录存储
 */
CREATE TABLE `chat` (
  `name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '名称',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `time` datetime NOT NULL,
  `ip` varchar(40) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'IP地址'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='聊天信息表';
/**
 * 创建管理员密码
 */
CREATE TABLE `root_password` (
  `password` int NOT NULL COMMENT '管理员密码'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='管理员密码';
/**
 * 添加管理员密码
 */
INSERT INTO root_password (password) VALUES (163895724);