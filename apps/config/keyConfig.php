<?php
return [
    'pid' => 'mm_29947721_14832832_57874820',
    'cache_time' => '500', // 秒
    'keyWordPageSize'=>20,//默认
    'keyWordCollectionPageSize'=>1000,//默认
    'taobaoke_keyword' => 'http://api.m.taobao.com/h5/mtop.alimama.ertao.graphql.query/1.0/?appKey=12574478&t=%s&sign=%s&api=mtop.alimama.ertao.graphql.query&v=1.0&type=jsonp&dataType=jsonp&callback=mtopjsonp1&data=',
    'taobaoke_keyword_data' => '{"query":"query auctionQuery($condition: LunaCondition) {auctionList(condition: $condition) {pageInfo {totalCount}auctions {nid title zkFinalPriceWap biz30Day couponStartFee clickUrl shareUrl pictUrl couponKey couponAmount couponSendCount couponTotalCount userType}}}","variables":"{\"condition\":{\"q\":\"%s\",\"offset\":%s,\"count\":%s,\"complement\":false,\"pid\":\"%s\",\"searchMap\":{\"price\":\"0~\",\"summaryfields\":\"npx_score,coupon,tk_mkt_activity_id,tk_mkt_rates,MAX_tk_event_eids,MAX_tk_event_creator_ids\",\"src_scene\":\"\",\"querytype\":\"couponlist\",\"distfield\":\"\"},\"serviceList\":[\"dpyhq\"]},\"bizEnvVars\":{\"pid\":\"%s\",\"st\":\"73\"}}"}'
    // 'is_open_page'=>false,
];

?>