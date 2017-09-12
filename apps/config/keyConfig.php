<?php
return [
    'urlEncodeKey'=>'1990',//纯数字，不能修改，否则整个网站将无法访问
    'title'=>"淘宝优惠券,天猫优惠券,淘宝内部优惠券,天猫内部优惠券,京东优惠券,好券vip",
    'subtitle'=>'淘宝优惠券,天猫优惠券,淘宝内部优惠券,天猫内部优惠券,京东优惠券',
    'description'=>"好券vip是淘宝(天猫)内部优惠券领取网站,独家优惠券直接领取,领取后可直接下单抵扣,价格超实惠。千万淘宝优惠券每天更新,上淘宝(天猫)购物先上券买买,比双11更低!",
    'keyWords'=>"淘宝优惠券,天猫优惠券,淘宝内部优惠券,天猫内部优惠券,京东优惠券,好券vip",
    'pid' => 'mm_29947721_14832832_57874820',
    'cache_time' => '5', // 秒
    'keyWordPageSize'=>20,//默认
    'keyWordCollectionPageSize'=>500,//默认
    'taobaoke_keyword' => 'http://api.m.taobao.com/h5/mtop.alimama.ertao.graphql.query/1.0/?appKey=12574478&t=%s&sign=%s&api=mtop.alimama.ertao.graphql.query&v=1.0&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data=',
    'taobaoke_keyword_data' => '{"query":"query auctionQuery($condition: LunaCondition) {auctionList(condition: $condition) {pageInfo {totalCount}auctions {nid title zkFinalPriceWap biz30Day couponEffectiveStartTime provcity nick couponEffectiveEndTime couponStartFee clickUrl shareUrl pictUrl couponKey couponAmount couponSendCount couponTotalCount userType}}}","variables":"{\"condition\":{\"q\":\"%s\",\"offset\":%s,\"count\":%s,\"complement\":false,\"pid\":\"%s\",\"searchMap\":{\"price\":\"0~\",\"summaryfields\":\"npx_score,coupon,tk_mkt_activity_id,tk_mkt_rates,MAX_tk_event_eids,MAX_tk_event_creator_ids\",\"src_scene\":\"\",\"querytype\":\"couponlist\",\"distfield\":\"\"},\"serviceList\":[\"dpyhq\"]},\"bizEnvVars\":{\"pid\":\"%s\",\"st\":\"\"}}"}',
    // 'is_open_page'=>false,
    //  新加字段 couponEffectiveStartTime provcity nick couponEffectiveEndTime 
    'baiDuWordPos'=>"http://www.xiaoqiwen.cn/api/baidukw.php?kw=%s",//百度分词获取地址
    'taoBaoKeyWords'=>"https://suggest.taobao.com/sug?code=utf-8&q=%s"//淘宝关键词生成
    // 天猫  https://rate.tmall.com/list_detail_rate.htm?itemId=554662908267&sellerId=1637289231&order=3&currentPage=2&append=0&content=1&tagId=&posi=&picture=0&itemPropertyId=&itemPropertyIndex=&userPropertyId=&userPropertyIndex=&rateQuery=&location=&needFold=0&callback=jsonp1966
        // 问大家 https://rate.taobao.com/detailCommon.htm?auctionNumId=536650105506&userNumId=1914800532&callback=json_tbc_rate_summary
];

?>