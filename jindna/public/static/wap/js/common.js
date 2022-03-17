'use strict';

(function () {
    function initRem() {
        var documentElement = window.document.documentElement;
        var width = Math.min(documentElement.clientWidth, 750);
        documentElement.style.fontSize = width / (750 / 100) + "px";
    }

    initRem();

    window.addEventListener('resize', initRem, false);
})();

var $win = $(window);

/**
 * tabs.js
 */
;
(function (win, doc, $, undefined) {
    var Plugin = function (elem, options) {
        this.$elem = elem;
        this.$item = this.$elem.find('.tab-item');
        this.$bar = this.$elem.find('.tab-bar');
        this.$pane = this.$elem.find('.tab-pane');
        this.defaults = {
            isFixed: false, // 婊氬姩鍒伴《閮ㄥ浐瀹�
            bar_anim: true, // bar鏄惁婊戝姩杩愬姩
            callback: null, // 鍥炶皟鍑芥暟锛屾帴鍙楀弬鏁癷tem涓嬫爣
        }
        this.settings = $.extend({}, this.defaults, options);
    }

    Plugin.prototype = {
        init: function () {
            var that = this;
            this.initBar();
            this.$item.click(function () {
                that.index = $(this).index();
                that.changeTab();

                if (that.settings.callback && typeof that.settings.callback == 'function') {
                    that.settings.callback(that.index);
                }
            });

            var offsetTop = that.$elem.offset().top;
            if (this.settings.isFixed) {
                $win.scroll(function () {
                    if ($win.scrollTop() >= offsetTop) {
                        that.$elem.addClass('fixed');
                    } else {
                        that.$elem.removeClass('fixed');
                    }
                });
            }
        },

        changeTab: function () {
            this.$item.removeClass('on');
            this.$item.eq(this.index).addClass('on');
            this.$pane.removeClass('on');
            this.$pane.eq(this.index).addClass('on');
            this.initBar();
        },

        initBar: function () {
            var barWidth = this.$elem.find('.tab-item.on span').width();
            var itemWidth = this.$item.width();
            this.$bar.css({
                'width': barWidth,
                'margin-left': (itemWidth - barWidth) / 2,
                '-webkit-transform': 'translateX(' + this.index * itemWidth + 'px)',
                'transform': 'translateX(' + this.index * itemWidth + 'px)',
            });
            if (!this.settings.bar_anim) {
                this.$bar.css({
                    '-webkit-transition': 'none',
                    'transition': 'none'
                });
            }
        }
    }

    $.fn.tab = function (options) {
        var plugin = new Plugin(this, options);
        return plugin.init();
    }
})(window, document, Zepto);

/**
 * goTop.js
 */
;
(function (win, doc, $, undefined) {
    $.fn.gotop = function (options) {
        var $elem = this;
        var timer = null;
        var scrollTop = 0;
        var speed = 0;
        var b = false;

        $elem.click(function () {
            clearInterval(timer);

            timer = setInterval(function () {
                scrollTop = $(window).scrollTop();
                speed = Math.floor((0 - scrollTop) / 8);

                if (scrollTop == 0) {
                    clearInterval(timer);
                } else {
                    $win.scrollTop(scrollTop + speed);
                }

                b = true;
            }, 30);
        });

        $win.scroll(function () {
            if (!b) {
                clearInterval(timer);
            }

            b = false;

            scrollTop = $win.scrollTop();

            scrollTop > 1000 ? $elem.addClass('on') : $elem.removeClass('on');
        });
    }
})(window, document, Zepto);

/**
 * Marquee.js
 */
var Marquee = function (a) {
    a = a || {};
    var opts = {
        el: a.el || '#marqueebox',
        speed: a.speed || 20,
        delay: a.delay || 5000,
    }

    var el = document.getElementById(opts.el.replace('#', ''));
    if (!el) return false;

    var b = false;
    var timer;
    var lineHeight = el.children[0].offsetHeight;

    el.innerHTML += el.innerHTML;
    el.style.marginTop = 0;
    el.onmouseover = function () {
        p = true;
    }
    el.onmouseout = function () {
        p = false;
    }

    var start = function () {
        timer = setInterval(scroll, opts.speed);
        if (!b) el.style.marginTop = parseInt(el.style.marginTop) - 1 + "px";
    }

    var scroll = function () {
        if (parseInt(el.style.marginTop) % lineHeight != 0) {
            el.style.marginTop = parseInt(el.style.marginTop) - 1 + "px";
            if (Math.abs(parseInt(el.style.marginTop)) >= el.scrollHeight / 2) el.style.marginTop = 0;
        } else {
            clearInterval(timer);
            setTimeout(start, opts.delay);
        }
    }

    setTimeout(start, opts.delay);
}

/**
 * drag.js
 */
;
(function (win, doc, $, undefined) {
    var Plugin = function (elem, options) {
        this.$elem = elem;
        this.x = x;
        this.y = y;
    }
    $.fn.drag = function (options) {
        var plugin = new Plugin(this, options);
        return plugin.init();
    }
})(window, document, Zepto);

$(function () {
    (function () {
        $('#container').css('padding-bottom', $('footer').height());
    })();

    /* 寮规 */
    $('[data-popup]').click(function () {
        var popId = $(this).data('popup');

        $('#' + popId).addClass('on');
    });
    $('[data-close]').click(function () {
        $(this).parents('.popup').removeClass('on');
    });

    $('.go-top').gotop();
});