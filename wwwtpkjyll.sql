/*
 Navicat Premium Data Transfer

 Source Server         : 本地的数据库
 Source Server Type    : MySQL
 Source Server Version : 100136
 Source Host           : localhost:3306
 Source Schema         : wwwtpkjyll

 Target Server Type    : MySQL
 Target Server Version : 100136
 File Encoding         : 65001

 Date: 25/01/2019 09:06:47
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for snake_articles
-- ----------------------------
DROP TABLE IF EXISTS `snake_articles`;
CREATE TABLE `snake_articles`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章标题',
  `description` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章描述',
  `keywords` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章关键字',
  `thumbnail` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章缩略图',
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文章内容',
  `add_time` datetime(0) NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of snake_articles
-- ----------------------------
INSERT INTO `snake_articles` VALUES (2, '文章标题', '文章描述', '关键字1,关键字2,关键字3', '/upload/20190108/bf9dc3d37ba4a6bc512298c6f78f0ab9.png', '<p><img src=\"/upload/image/20170916/1505555254.png\" title=\"1505555254.png\" alt=\"QQ截图20170916174651.png\"/></p><p>测试文章内容</p><p>测试内容</p>', '2017-09-16 17:47:44');

-- ----------------------------
-- Table structure for snake_document
-- ----------------------------
DROP TABLE IF EXISTS `snake_document`;
CREATE TABLE `snake_document`  (
  `doc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `doc_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文档名称',
  `parent_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '父ID',
  `project_id` int(11) NOT NULL COMMENT '所属项目',
  `doc_sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `doc_content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '文档内容',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `create_at` int(11) NOT NULL,
  `modify_time` datetime(0) NULL DEFAULT NULL,
  `modify_at` int(11) NULL DEFAULT NULL,
  `version` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '当前时间戳',
  PRIMARY KEY (`doc_id`) USING BTREE,
  UNIQUE INDEX `wk_document_id_uindex`(`doc_id`) USING BTREE,
  INDEX `project_id_index`(`project_id`) USING BTREE,
  INDEX `doc_sort_index`(`doc_sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 10 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文档表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of snake_document
-- ----------------------------
INSERT INTO `snake_document` VALUES (6, '3242', 0, 1, 0, '> 变化\n\n![](http://www.tpkj.yll/upload/20190114/5c3b658290d69_5c3b6582.png)\n\n\n     public static function getDocumnetHtmlFromCache($doc_id,$update = false)\n        {\n            $key = \'document.html.\' . $doc_id;\n    \n            $html = null;//$update or Cache::get($key);\n    \n            if(empty($html)) {\n                $document = self::getDocumentFromCache($doc_id, $update);\n    \n                if (empty($document)) {\n                    return false;\n                }\n                if(empty($document->doc_content)){\n                    return \'\';\n                }\n    //            $parsedown = new \\Parsedown();\n    //\n    //            $html  = $parsedown->text($document->doc_content);\n    \n                $html = markdown_converter($document->doc_content);\n    \n                $html = str_replace(\'class=\"language-\',\'class=\"\',$html);\n                Cache::set($key,$html,3600);\n            }\n            return $html;\n        }\n', NULL, 0, NULL, NULL, '2019-01-14 13:05:35');
INSERT INTO `snake_document` VALUES (9, 'diyig12', 6, 1, 0, '3r3 ', NULL, 0, NULL, NULL, '2019-01-20 19:05:43');

-- ----------------------------
-- Table structure for snake_document_history
-- ----------------------------
DROP TABLE IF EXISTS `snake_document_history`;
CREATE TABLE `snake_document_history`  (
  `history_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `doc_id` bigint(20) NOT NULL COMMENT '文档ID',
  `doc_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文档名称',
  `parent_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '父ID',
  `doc_content` longtext CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '文档内容',
  `modify_time` datetime(0) NULL DEFAULT NULL,
  `modify_at` int(11) NULL DEFAULT NULL,
  `version` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '当前时间戳',
  `create_time` datetime(0) NULL DEFAULT NULL COMMENT '历史记录创建时间',
  `create_at` int(11) NOT NULL COMMENT '历史记录创建人',
  PRIMARY KEY (`history_id`) USING BTREE,
  UNIQUE INDEX `history_id`(`history_id`) USING BTREE,
  INDEX `doc_id_index`(`doc_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '文档编辑历史记录表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for snake_node
-- ----------------------------
DROP TABLE IF EXISTS `snake_node`;
CREATE TABLE `snake_node`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `node_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '节点名称',
  `control_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '方法名',
  `is_menu` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否是菜单项 1不是 2是',
  `type_id` int(11) NOT NULL COMMENT '父级节点id',
  `style` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '菜单样式',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 29 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of snake_node
-- ----------------------------
INSERT INTO `snake_node` VALUES (1, '用户管理', '#', '#', 2, 0, 'fa fa-users');
INSERT INTO `snake_node` VALUES (2, '管理员管理', 'user', 'index', 2, 1, '');
INSERT INTO `snake_node` VALUES (3, '添加管理员', 'user', 'useradd', 1, 2, '');
INSERT INTO `snake_node` VALUES (4, '编辑管理员', 'user', 'useredit', 1, 2, '');
INSERT INTO `snake_node` VALUES (5, '删除管理员', 'user', 'userdel', 1, 2, '');
INSERT INTO `snake_node` VALUES (6, '角色管理', 'role', 'index', 2, 1, '');
INSERT INTO `snake_node` VALUES (7, '添加角色', 'role', 'roleadd', 1, 6, '');
INSERT INTO `snake_node` VALUES (8, '编辑角色', 'role', 'roleedit', 1, 6, '');
INSERT INTO `snake_node` VALUES (9, '删除角色', 'role', 'roledel', 1, 6, '');
INSERT INTO `snake_node` VALUES (10, '分配权限', 'role', 'giveaccess', 1, 6, '');
INSERT INTO `snake_node` VALUES (11, '系统管理', '#', '#', 2, 0, 'fa fa-desktop');
INSERT INTO `snake_node` VALUES (12, '数据备份/还原', 'data', 'index', 2, 11, '');
INSERT INTO `snake_node` VALUES (13, '备份数据', 'data', 'importdata', 1, 12, '');
INSERT INTO `snake_node` VALUES (14, '还原数据', 'data', 'backdata', 1, 12, '');
INSERT INTO `snake_node` VALUES (15, '节点管理', 'node', 'index', 2, 1, '');
INSERT INTO `snake_node` VALUES (16, '添加节点', 'node', 'nodeadd', 1, 15, '');
INSERT INTO `snake_node` VALUES (17, '编辑节点', 'node', 'nodeedit', 1, 15, '');
INSERT INTO `snake_node` VALUES (18, '删除节点', 'node', 'nodedel', 1, 15, '');
INSERT INTO `snake_node` VALUES (19, '文章管理', 'articles', 'index', 2, 0, 'fa fa-book');
INSERT INTO `snake_node` VALUES (20, '文章列表', 'articles', 'index', 2, 19, '');
INSERT INTO `snake_node` VALUES (21, '添加文章', 'articles', 'articleadd', 1, 19, '');
INSERT INTO `snake_node` VALUES (22, '编辑文章', 'articles', 'articleedit', 1, 19, '');
INSERT INTO `snake_node` VALUES (23, '删除文章', 'articles', 'articledel', 1, 19, '');
INSERT INTO `snake_node` VALUES (24, '上传图片', 'articles', 'uploadImg', 1, 19, '');
INSERT INTO `snake_node` VALUES (25, '个人中心', '#', '#', 1, 0, '');
INSERT INTO `snake_node` VALUES (26, '编辑信息', 'profile', 'index', 1, 25, '');
INSERT INTO `snake_node` VALUES (27, '编辑头像', 'profile', 'headedit', 1, 25, '');
INSERT INTO `snake_node` VALUES (28, '上传头像', 'profile', 'uploadheade', 1, 25, '');

-- ----------------------------
-- Table structure for snake_project
-- ----------------------------
DROP TABLE IF EXISTS `snake_project`;
CREATE TABLE `snake_project`  (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '项目名称',
  `description` varchar(2000) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '项目描述',
  `doc_tree` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '当前项目的文档树',
  `project_open_state` tinyint(4) NULL DEFAULT 0 COMMENT '项目公开状态：0 私密，1 完全公开，2 加密公开',
  `project_password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '项目密码',
  `doc_count` int(11) NULL DEFAULT 0 COMMENT '文档数据量',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `create_at` int(11) NOT NULL,
  `modify_time` datetime(0) NULL DEFAULT NULL,
  `modify_at` int(11) NULL DEFAULT NULL,
  `version` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0.1' COMMENT '版本号',
  PRIMARY KEY (`project_id`) USING BTREE,
  UNIQUE INDEX `project_id_uindex`(`project_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '项目表' ROW_FORMAT = Compact;

-- ----------------------------
-- Records of snake_project
-- ----------------------------
INSERT INTO `snake_project` VALUES (1, 'admin', '我开始系统的研究学习', NULL, 0, NULL, 1, '2019-01-13 13:42:29', 1, '2019-01-13 13:42:29', NULL, 'laravel从入门到精通');

-- ----------------------------
-- Table structure for snake_project_types
-- ----------------------------
DROP TABLE IF EXISTS `snake_project_types`;
CREATE TABLE `snake_project_types`  (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '父ID',
  `type_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文档名称',
  `type_sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `create_time` datetime(0) NULL DEFAULT NULL,
  `create_at` int(11) NOT NULL,
  `modify_time` datetime(0) NULL DEFAULT NULL,
  `modify_at` int(11) NULL DEFAULT NULL,
  `version` timestamp(0) NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP(0) COMMENT '当前时间戳',
  PRIMARY KEY (`type_id`) USING BTREE,
  UNIQUE INDEX `wk_project_types_id_uindex`(`type_id`) USING BTREE,
  INDEX `type_id_index`(`type_id`) USING BTREE,
  INDEX `type_sort_index`(`type_sort`) USING BTREE,
  INDEX `wk_project_types_parent_id_index`(`parent_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '项目分类表' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for snake_role
-- ----------------------------
DROP TABLE IF EXISTS `snake_role`;
CREATE TABLE `snake_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(155) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '角色名称',
  `rule` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '权限节点数据',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of snake_role
-- ----------------------------
INSERT INTO `snake_role` VALUES (1, '超级管理员', '*');
INSERT INTO `snake_role` VALUES (2, '系统维护员', '1,2,3,4,5,6,7,8,9,10');

-- ----------------------------
-- Table structure for snake_user
-- ----------------------------
DROP TABLE IF EXISTS `snake_user`;
CREATE TABLE `snake_user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `head` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT '' COMMENT '头像',
  `login_times` int(11) NOT NULL DEFAULT 0 COMMENT '登陆次数',
  `last_login_ip` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT 0 COMMENT '最后登录时间',
  `real_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '真实姓名',
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '状态',
  `role_id` int(11) NOT NULL DEFAULT 1 COMMENT '用户角色id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_bin ROW_FORMAT = Compact;

-- ----------------------------
-- Records of snake_user
-- ----------------------------
INSERT INTO `snake_user` VALUES (1, 'admin', 'a9ddd2e7bdff202e3e9bca32765e9ba0', '/static/admin/images/profile_small.jpg', 45, '127.0.0.1', 1547773400, 'admin', 1, 1);

SET FOREIGN_KEY_CHECKS = 1;
