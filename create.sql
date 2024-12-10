CREATE TABLE chat
(
    avatar  LONGTEXT     NOT NULL COMMENT '头像',
    name    VARCHAR(50)  NOT NULL COMMENT '名称',
    content LONGTEXT     NOT NULL COMMENT '内容',
    time    VARCHAR(40)  NOT NULL COMMENT '日期'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='聊天信息表';

CREATE TABLE users
(
    id       INT         AUTO_INCREMENT PRIMARY KEY COMMENT '用户唯一标识符',
    username VARCHAR(50) NOT NULL COMMENT '用户名',
    password VARCHAR(50) NOT NULL COMMENT '用户密码',
    avatar   LONGTEXT    NOT NULL COMMENT '头像'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='聊天信息表';