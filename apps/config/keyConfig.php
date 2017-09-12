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
    // 淘宝 有问题 https://rate.taobao.com/feedRateList.htm?auctionNumId=536650105506&userNumId=1914800532&currentPageNum=1&pageSize=20&rateType=&orderType=sort_weight&attribute=&sku=&hasSku=false&folded=0&ua=098%23E1hvavvEC86vUvCkvvvvvjiPP25UAjimPsLhgjljPmPZsjinRLSOQjnhRLsO0ji2mphvLhPaf9mFejXvfCuYiLUpwh%2BFp%2B0xhC1TmmxBlwyzhmpXayjw1COqb6OyCW2%2B%2BfvsxeCXTWeARFxjb9TxfX9wjoC3ZfmB53rlBzDx0f0tvpvIphvvvvvvphCvpCpvvvCvYhCvHvvvvhnFphvZvvvvprtvpCpvvvCmlgyCvvX2pQ9WeiuivpvUphvhJL%2Bf14I3vpPlkmT8QpURMUsqQ44EvQqr98foTUNG%2F%2FNETgLyAQWRqOmesS0p0pP%2B6f0RSg5PAdeESOktFG2qtUzcIMK4Mpc33f7j%2BYgj%2FfeEKUegFwM8l%2F7qkfAR6Y2cqdkUKgqW6f82SO0GF%2BsbSg0%2FgfKPAMet9f0W%2BO5L6qAHdrztDnNqKgutlTJNAY813d0HdEqPsWTrii%2FtkGd8KUkgDdk7sPSuFRSJKIqYMN8x%2Fgz3Dpg8%2FGV5kPdZMpf39KPnMXQEMReVi%2BcekGyRlqzq1P0UlQbGkPkbdEqPsw5%2BKI%2FqMfMWSOMRS4NhC9Pd98foTUNG%2F%2FNETgLyQwsPKSsG0fKRsJ2qk%2FcvKgqnzK54KOF5Aabb%2BUeMkdVZsW%2FTMRSH%2BU2WAdkUKUk5D%2BFWTOSKFfAPsSzdyJ%2FwMYdWsquWqwkqF%2BzJtwmTFqKRsaTT5quPKgqW6PSJKI%2FqF%2BzJG8cMr4AJsGAYFGdSKgqWsqdgSYsm9PgBSWdRFbMbsPoTs%2F0IsfMsGnTMSIdHF9d1QKJTFqKRsns3FqF914GvsPTo1IMNTahWMtSwtPuaCK239rVEGbdeGGKnA4sIuGzbqaqYk%2B4xmazWKW8FdEqPsb0PsISX9aPLMuzK0d5Wsd%2FTFquPKMzbCJJ9KwdwyvGW6X23DrVbgabqIG7CTuUE2%2FjZ0XjOTKPpsbsd%2BGFT6rVt9JVjSNgVTqdBqgbPT%2BsPKI%2FTMnT%2BzYjjyN%2FTsuIRmYmmtXjelSPytOsXFSTyCSqiFMV9qgzkGM0zQ4Le6K59S%2BFPSPewqPqfTW0G6PcWsquPKSFI9Qh8tX8r5qAR6vsrkT0NSUMJvSAU%2Fn0qF%2BsPKMur5qARsGsRTquPKgqWgP0BTONrQQqqtgci3uNhmpJr5q0PSUq9MJJo%2FEN5Q%2BsPKI%2FTMRAUMQqi9RLCKgqWsqSY%2FgSyQwsPKI%2FqM%2FFfAGA%2FybmjSUcp6qKxSUkgFWqPKE4t94JoASA19bVvSKNMs%2F8CT%2B%2FqF%2BsPKIsrAbKRsW%2FqM%2F8nTbbWsrm1sNd%2FKJSf0TMGrWmom%2Fq31buPKgqWsqdG1TSXTaWUSPF9uKm46GqPKW0d0XSB6n01gbFednWLGuRM0GoFz3jjgduPKgqWsqdG1gqR%2FaSrGdSQKdzosvS3k%2FR%2BGXNiq%2BRd%2BNSg%2BGzaSY5WuSkYgnsjdYAMG82IzMec0%2FMfkPqkqE0MANqWCWKgYdFLg4p2SquPKI%2FqF%2Bsw%2BnRQlYReCQb%2BFwVn0TWVTPKUgOk%2B5%2BMWSO%2FYFGMg2pcMS80NSUqPsbqLMneqF%2BsPKM%2Ft5bKRsn2re40NSUMJ6b581d2mGMPaQiNbSNSE2v245KeD1E2%2FMRm5%2FwMVyaGCvpvVph9vvvvv2QhvC26wT9ytvpvhphvvv8wCvvBvpvpZ&_ksTS=1505221838584_1469&callback=jsonp_tbcrate_reviews_list
    // 问大家 https://rate.taobao.com/detailCommon.htm?auctionNumId=536650105506&userNumId=1914800532&callback=json_tbc_rate_summary
];

?>