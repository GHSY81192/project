-- 表的结构：sline_hotel_room --
CREATE TABLE `sline_hotel_room` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `webid` int(3) NOT NULL DEFAULT '1' COMMENT '弃用',
  `hotelid` int(11) DEFAULT NULL COMMENT '酒店id',
  `roomname` text COMMENT '房型名称',
  `price` int(11) unsigned DEFAULT NULL COMMENT '报价',
  `sellprice` int(11) unsigned DEFAULT NULL COMMENT '市场价',
  `breakfirst` varchar(255) DEFAULT NULL COMMENT '早餐',
  `computer` varchar(255) DEFAULT NULL COMMENT '宽带',
  `otherprice` varchar(255) DEFAULT NULL COMMENT '其它报价(弃用)',
  `displayorder` int(11) DEFAULT NULL COMMENT '排序',
  `otherroom` varchar(255) DEFAULT NULL COMMENT '暂时不用',
  `otherroomurl` varchar(255) DEFAULT NULL COMMENT '暂时不用',
  `nightdays` varchar(255) DEFAULT NULL COMMENT '暂时不用',
  `roomids` varchar(255) DEFAULT NULL COMMENT '暂时不用',
  `roomstyle` varchar(255) DEFAULT NULL COMMENT '床型',
  `roomarea` varchar(255) DEFAULT NULL COMMENT '房间面积',
  `roomfloor` varchar(255) DEFAULT NULL COMMENT '楼层',
  `roomwindow` varchar(255) DEFAULT NULL COMMENT '窗户',
  `piclist` text COMMENT '房间图片列表',
  `number` int(11) DEFAULT '0' COMMENT '库存',
  `jifencomment` int(11) DEFAULT '0' COMMENT '评论送积分',
  `jifentprice` int(11) DEFAULT '0' COMMENT '积分抵现金',
  `jifenbook` int(11) DEFAULT '0' COMMENT '预订送积分',
  `paytype` int(1) unsigned DEFAULT '1' COMMENT '支付类型:1全额支付,2,定金支付 3,二次确认支付',
  `dingjin` varchar(255) DEFAULT NULL COMMENT '定金',
  `lastoffer` text NOT NULL COMMENT '上次报价',
  `description` text COMMENT '房型描述',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='酒店房型表';-- <xjx> --

