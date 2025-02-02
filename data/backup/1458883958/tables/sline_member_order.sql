-- 表的结构：sline_member_order --
CREATE TABLE `sline_member_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ordersn` varchar(255) DEFAULT NULL COMMENT '订单号',
  `memberid` int(11) unsigned DEFAULT NULL COMMENT '会员id',
  `typeid` int(3) unsigned DEFAULT NULL COMMENT '订单类型',
  `supplierlist` varchar(50) DEFAULT NULL COMMENT '定单所属供应商',
  `webid` int(3) unsigned DEFAULT NULL COMMENT '所属站点',
  `productaid` int(11) unsigned DEFAULT NULL COMMENT '产品aid',
  `productname` varchar(255) DEFAULT NULL COMMENT '产品名称',
  `productautoid` int(11) unsigned DEFAULT NULL COMMENT '对应产品表自增id(第三方后台用)',
  `litpic` varchar(255) DEFAULT NULL COMMENT '产品图片',
  `price` float(10,2) unsigned DEFAULT NULL COMMENT '价格(单价)',
  `marketprice` float DEFAULT NULL COMMENT '市场价',
  `spotprice` float DEFAULT NULL COMMENT '景区结算价',
  `supplierprice` float DEFAULT NULL COMMENT '商户结算价',
  `childprice` float(10,2) unsigned DEFAULT NULL COMMENT '小孩报价',
  `usedate` varchar(255) DEFAULT NULL COMMENT '使用日期',
  `dingnum` int(3) unsigned DEFAULT NULL COMMENT '数量',
  `childnum` int(11) unsigned DEFAULT '0' COMMENT '儿童数量',
  `ispay` int(10) unsigned DEFAULT '0' COMMENT '是否已经付款',
  `status` int(1) unsigned DEFAULT '0' COMMENT '订单状态',
  `linkman` varchar(255) DEFAULT NULL COMMENT '订单联系人',
  `linktel` varchar(255) DEFAULT NULL COMMENT '订单联系电话',
  `linkemail` varchar(100) DEFAULT NULL COMMENT '联系人邮件',
  `linkqq` varchar(16) DEFAULT NULL COMMENT '联系人QQ',
  `linkidcard` varchar(20) DEFAULT NULL COMMENT '联系人身份证',
  `isneedpiao` int(1) unsigned DEFAULT '0' COMMENT '是否需要发票',
  `addtime` int(10) unsigned DEFAULT NULL COMMENT '预订时间',
  `finishtime` int(10) unsigned DEFAULT NULL COMMENT '成交时间',
  `ispinlun` int(1) unsigned DEFAULT '0' COMMENT '是否已经评论,1:已评论,0:未评论',
  `jifencomment` int(11) DEFAULT '0' COMMENT '评论送积分',
  `jifentprice` int(11) DEFAULT '0' COMMENT '积分抵现金',
  `jifenbook` int(11) DEFAULT '0' COMMENT '预订送积分',
  `dingjin` float(2,0) DEFAULT '0' COMMENT '是否支持定金',
  `suitid` int(11) DEFAULT '0' COMMENT '用于预订产品有子分类时',
  `paytype` int(1) DEFAULT '1' COMMENT '支付方式： 1-全款 2-定金 3-线下',
  `oldnum` int(11) DEFAULT '0' COMMENT '老人数量',
  `oldprice` float(10,2) DEFAULT '0.00' COMMENT '老人价格',
  `usejifen` int(1) unsigned DEFAULT '0' COMMENT '是否使用积分',
  `needjifen` int(11) unsigned DEFAULT NULL COMMENT '需要的积分',
  `pid` int(11) DEFAULT '0' COMMENT '父级订单id',
  `haschild` int(1) unsigned DEFAULT '0' COMMENT '是否有子级订单',
  `remark` mediumtext COMMENT '备注',
  `kindlist` varchar(255) DEFAULT '' COMMENT '所属目的地',
  `roombalance` int(11) DEFAULT '0' COMMENT '单房差价格',
  `roombalancenum` int(11) DEFAULT '0' COMMENT '单房差数量',
  `viewstatus` tinyint(1) DEFAULT '0' COMMENT '查看状态',
  `roombalance_paytype` tinyint(1) DEFAULT '1' COMMENT '单房差支付方式 1,预订 2,到店付',
  `paysource` char(50) DEFAULT NULL COMMENT '支付来源',
  `departdate` varchar(255) DEFAULT NULL COMMENT '离店日期(酒店用)',
  `eticketno` varchar(255) DEFAULT NULL COMMENT '消费码',
  `isconsume` int(10) unsigned DEFAULT '0' COMMENT '定单是否已消费',
  `consumetime` int(10) DEFAULT NULL COMMENT '消费时间',
  `consumeverifyuser` int(10) DEFAULT NULL COMMENT '消费验证用户id',
  `consumeverifymemo` text COMMENT '消费验证备注',
  `supplierorderexdata` longtext COMMENT '站外商户订单扩展数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8 COMMENT='会员订单表.';-- <xjx> --

