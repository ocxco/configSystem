/*
 * @Author: ln 
 * @Date: 2018-08-20 16:46:59 
 * @Last Modified by: ln
 * @Last Modified time: 2018-10-25 17:01:52
 */
"use strict";

/*根元素字体设置
* 以1920设计稿为基准，计算时，将设计稿上的尺寸除以100即可
*/

(function (doc, win) {
    var htmlFont = function () {
        var docEl = doc.documentElement,
            view = docEl.clientWidth,
            height = docEl.clientHeight,
            font;
        docEl.style.minHeight = height + "px";
        font = view / 10.8;
        view > 1080 ? docEl.style.fontSize = 100 + "px" : docEl.style.fontSize = font + "px";
    };
    htmlFont();
    win.addEventListener("resize", htmlFont, false)
})(document, window);