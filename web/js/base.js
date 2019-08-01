/*
 * @Author: ln 
 * @Date: 2018-07-09 16:20:30 
 * @Last Modified by: ln
 * @Last Modified time: 2018-10-26 19:13:17
 */
"use strict";

$(document).ready(function () {
    // overEllipsis($('.tf-news-list .tt'), 155);
    template({
        cityName: '成都',
        airDom: '.sc_tool .atm',
        weatherDom: '.sc_tool .weather',
        temperatureDom: '.sc_tool .temp',
        order: false
    });

    

    $('.sc_tabBtns>.btn').on('click', function(e) {
        var e = e || event;
        $(this).addClass('cur').siblings().removeClass('cur');
        $('.sc_tabItems>.item').eq($(this).index()).show().siblings().hide();
        e.stopPropagation();
    });
    
    tabItem({
        tab: '.m-tab',
        tabHead: '.btn',
        tabCont: '.item',
        event: 'click'
    });
    // $('.sc_news_tabBtns>.btn').on('click', function(e) {
    //     var e = e || event;
    //     $(this).addClass('cur').siblings().removeClass('cur');
    //     $('.sc_news_tab>.tabItems>.item').eq($(this).index()).show().siblings().hide();
    //     e.stopPropagation();
    // });
    // jQuery(".sc_news_tabBtns .btn").tabPanelFun({
    //     cur: 'cur',
    //     tabContent: '.sc_news_tab .tabItems',
    //     tabItem: '.sc_news_tab .tabItems .item',
    //     evnets: 'click'
    // })

    dropDownImgText({
        cont: '.sc_drop_down',
        tag: '.tag',
        text: '.tt',
        arrow: '.arrow',
        drop: '.sc_drop_lists',
        label: '.a'
    });
    
    jQuery(".sc-lb-img .lbItems .a").simpleSwitch({
        num: '.sc-lb-img .lbNums span',
		playTime:3000,
    });
});
