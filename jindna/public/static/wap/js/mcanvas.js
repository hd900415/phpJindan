(function (global, factory) {
	typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
	typeof define === 'function' && define.amd ? define(factory) :
	(global.MCanvas = factory());
}(this, (function () { 'use strict';

var _typeof = typeof Symbol === "function" && typeof Symbol.iterator === "symbol" ? function (obj) { return typeof obj; } : function (obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; };

var _ = {
    extend: function extend(obj1, obj2) {
        var _this = this;

        var type = this.isType(obj2);
        if (type == 'object') {
            this.forin(obj2, function (k, v) {
                var vType = _this.isType(v);
                if (vType !== 'object' && vType !== 'array') {
                    obj1[k] = v;
                } else {
                    if (_this.isType(obj1[k]) !== vType || obj1[k] === null) {
                        obj1[k] = vType == 'object' ? {} : [];
                    }
                    _this.extend(obj1[k], v);
                }
            });
        } else if (type == 'array') {
            for (var i = 0; i < obj2.length; i++) {
                obj1[i] = obj2[i];
            }
        } else {
            obj1 = obj2;
        }
        return obj1;
    },
    loadImage: function loadImage(image, loaded, error) {
        var img = new Image();
        if (image.indexOf('http') == 0) {
            img.crossOrigin = 'Anonymous';
        }
        img.onload = function () {
            loaded(img);
            setTimeout(function () {
                img = null;
            }, 1000);
        };
        img.onerror = function () {
            error('img load error');
        };
        img.src = image;
        console.log(img);
    },
    isObject: function isObject(tar) {
        return this.isObjFunc(tar, 'Object');
    },
    isBoolean: function isBoolean(tar) {
        return this.isObjFunc(tar, 'Boolean');
    },
    isArr: function isArr(tar) {
        return this.isObjFunc(tar, 'Array');
    },
    getImage: function getImage(image, cbk, error) {
        if (typeof image == 'string') {
            this.loadImage(image, function (img) {
                cbk(img);
            }, error);
        } else if ((typeof image === 'undefined' ? 'undefined' : _typeof(image)) == 'object') {
            cbk(image);
        } else {
            console.log('add image error');
            return;
        }
    },
    forin: function forin(obj, cbk) {
        for (var k in obj) {
            if (obj.hasOwnProperty(k)) {
                cbk(k, obj[k]);
            }
        }
    },
    isIos8: function isIos8() {
        var UA = window.navigator.userAgent.toLowerCase();
        var IOS = /(iPhone|iPad|iPod|iOS)/gi.test(UA);
        var IPAD = /(iPad)/gi.test(UA);
        if (IOS) {
            return IPAD ? UA.match(/cpu os (\d*)/)[1] < 9 : UA.match(/iphone os (\d*)/)[1] < 9;
        } else {
            return false;
        }
    },
    deepCopy: function deepCopy(obj) {
        return JSON.parse(JSON.stringify(obj));
    },
    isObjFunc: function isObjFunc(tar, name) {
        return Object.prototype.toString.call(tar) === '[object ' + name + ']';
    },
    isType: function isType(tar) {
        return Object.prototype.toString.call(tar).split(' ')[1].replace(']', '').toLowerCase();
    }
};

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _slicedToArray = function () { function sliceIterator(arr, i) { var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"]) _i["return"](); } finally { if (_d) throw _e; } } return _arr; } return function (arr, i) { if (Array.isArray(arr)) { return arr; } else if (Symbol.iterator in Object(arr)) { return sliceIterator(arr, i); } else { throw new TypeError("Invalid attempt to destructure non-iterable instance"); } }; }();

function MCanvas() {
    var options = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

    // ??????????????? new ????????????
    if (!(this instanceof MCanvas)) return new MCanvas(options);

    // ??????canvas???????????????
    // width??????????????????Number,?????????????????? 500;
    // height: ???????????????Number????????????????????????????????????
    this.ops = _.extend({
        width: 500,
        height: 500,
        backgroundColor: ''
    }, options);

    // ???????????????
    this.canvas = null;
    this.ctx = null;
    // ?????????????????????
    this.queue = [];
    // ??????????????????
    this.fn = {
        // ????????????????????????
        success: function success() {},

        // ???????????????
        error: function error() {}
    };
    this.data = {
        // ??????id???
        textId: 0,
        // ?????????????????????
        text: {},
        // ???????????????;
        bgConfig: null
    };

    // ????????????????????????
    this._init();
}

MCanvas.prototype._init = function () {
    this.canvas = document.createElement('canvas');
    this.canvas.width = this.ops.width;
    this.canvas.height = this.ops.height;
    this.ctx = this.canvas.getContext('2d');
    this.ctx.save();

    this.ops.backgroundColor && this.setBgColor(this.ops.backgroundColor);
};

// --------------------------------------------------------
// ?????????????????????
// --------------------------------------------------------

MCanvas.prototype.background = function (image) {
    var _this = this;

    var bg = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {
        type: 'origin'
    };

    if (!image && !this.data.bgConfig) {
        console.error('mcanvas error : the init background must has a image.');
        return;
    }

    // ??????bg options??? ???????????????
    if (!image) {
        bg = this.data.bgConfig;
    } else {
        bg.image = image;
        this.data.bgConfig = bg;
    }

    this.queue.push(function () {
        if (bg.color) _this.setBgColor(bg.color);
        _.getImage(bg.image, function (img) {
            _this._background(img, bg);
        }, _this.fn.error);
    });
    return this;
};

// ??????????????????;
MCanvas.prototype.setBgColor = function (color) {
    this.ctx.fillStyle = color;
    this.ctx.fillRect(0, 0, this.canvas.width, this.canvas.height);
};

MCanvas.prototype._getBgAlign = function (left, iw, cw, cropScale) {
    var rv = void 0;
    if (typeof left == 'string') {
        if (left == '50%' || left == 'center') {
            rv = Math.abs((iw - cw / cropScale) / 2);
        } else if (left == '100%') {
            rv = Math.abs(iw - cw / cropScale);
        } else if (left == '0%') {
            rv = 0;
        }
    } else if (typeof left == 'number') {
        rv = left;
    } else {
        rv = 0;
    }
    return rv;
};
MCanvas.prototype._background = function (img, bg) {
    var _getSize = this._getSize(img),
        iw = _getSize.iw,
        ih = _getSize.ih;
    // ?????????canvas???????????????


    var iRatio = iw / ih;
    var cRatio = this.canvas.width / this.canvas.height;
    // ?????????????????????
    var sx = void 0,
        sy = void 0,
        swidth = void 0,
        sheight = void 0,
        dx = void 0,
        dy = void 0,
        dwidth = void 0,
        dheight = void 0;
    var cropScale = void 0;
    switch (bg.type) {
        // ?????????????????????canvas????????????????????????????????????????????????
        case 'crop':
            if (iRatio > cRatio) {
                swidth = ih * cRatio;
                sheight = ih;
                cropScale = this.canvas.height / ih;
            } else {
                swidth = iw;
                sheight = swidth / cRatio;
                cropScale = this.canvas.width / iw;
            }

            sx = this._getBgAlign(bg.left, iw, this.canvas.width, cropScale);
            sy = this._getBgAlign(bg.top, ih, this.canvas.height, cropScale);

            dy = dx = 0;
            dheight = this.canvas.height;
            dwidth = this.canvas.width;
            break;
        // ?????????????????????canvas???????????????????????????
        case 'contain':
            sy = sx = 0;
            swidth = iw;
            sheight = ih;
            if (iRatio > cRatio) {
                dwidth = this.canvas.width;
                dheight = dwidth / iRatio;
                dx = bg.left || 0;
                dy = bg.top || bg.top == 0 ? bg.top : (this.canvas.height - dheight) / 2;
            } else {
                dheight = this.canvas.height;
                dwidth = dheight * iRatio;
                dy = bg.top || 0;
                dx = bg.left || bg.left == 0 ? bg.left : (this.canvas.width - dwidth) / 2;
            }
            break;
        // ???????????????canvas??????????????????????????????????????? ????????????????????????
        // ?????????background ????????? left/top ???????????????
        case 'origin':
            this.canvas.width = iw;
            this.canvas.height = ih;
            sx = sy = 0;
            swidth = iw;
            sheight = ih;
            dx = dy = 0;
            dwidth = this.canvas.width;
            dheight = this.canvas.height;
            break;
        default:
            console.error('mcanvas error:background type error!');
            return;
    }
    this.ctx.drawImage(img, sx, sy, swidth, sheight, dx, dy, dwidth, dheight);
    this._next();
};
// --------------------------------------------------------
// ?????????????????????
// --------------------------------------------------------

// ??????????????????
MCanvas.prototype.rect = function (ops) {
    var _this2 = this;

    this.queue.push(function () {
        var _ops$fillColor = ops.fillColor,
            fillColor = _ops$fillColor === undefined ? '#fff' : _ops$fillColor,
            _ops$strokeColor = ops.strokeColor,
            strokeColor = _ops$strokeColor === undefined ? fillColor : _ops$strokeColor,
            _ops$strokeWidth = ops.strokeWidth,
            strokeWidth = _ops$strokeWidth === undefined ? 0 : _ops$strokeWidth;

        var cw = _this2.canvas.width,
            ch = _this2.canvas.height;
        var width = _this2._get(cw, 0, ops.width || 0, 'pos') - 2 * strokeWidth,
            height = _this2._get(ch, 0, ops.height || 0, 'pos') - 2 * strokeWidth;
        var x = _this2._get(cw, width, ops.x || 0, 'pos') + strokeWidth / 2,
            y = _this2._get(ch, height, ops.y || 0, 'pos') + strokeWidth / 2;
        _this2.ctx.lineWidth = strokeWidth;
        _this2.ctx.fillStyle = fillColor;
        _this2.ctx.strokeStyle = strokeColor;

        _this2.ctx.beginPath();
        _this2.ctx.strokeRect(x, y, width, height);
        _this2.ctx.fillRect(x, y, width, height);
        _this2.ctx.closePath();

        _this2._resetCtx()._next();
    });
    return this;
};

// ??????????????????
MCanvas.prototype.circle = function (ops) {
    var _this3 = this;

    this.queue.push(function () {
        var _ops$fillColor2 = ops.fillColor,
            fillColor = _ops$fillColor2 === undefined ? '#fff' : _ops$fillColor2,
            _ops$strokeColor2 = ops.strokeColor,
            strokeColor = _ops$strokeColor2 === undefined ? fillColor : _ops$strokeColor2,
            _ops$strokeWidth2 = ops.strokeWidth,
            strokeWidth = _ops$strokeWidth2 === undefined ? 0 : _ops$strokeWidth2;

        var cw = _this3.canvas.width,
            ch = _this3.canvas.height;
        var r = _this3._get(cw, 0, ops.r || 0, 'pos') - 2 * strokeWidth;
        var x = _this3._get(cw, 2 * r, ops.x || 0, 'pos') + strokeWidth / 2 + r,
            y = _this3._get(ch, 2 * r, ops.y || 0, 'pos') + strokeWidth / 2 + r;

        _this3.ctx.beginPath();
        _this3.ctx.arc(x, y, r, 0, Math.PI * 2, false);
        _this3.ctx.fillStyle = fillColor;
        _this3.ctx.fill();
        _this3.ctx.strokeStyle = strokeColor;
        _this3.ctx.lineWidth = strokeWidth;
        _this3.ctx.stroke();
        _this3.ctx.closePath();

        _this3._resetCtx()._next();
    });
    return this;
};

// ??????ctx??????;
MCanvas.prototype._resetCtx = function () {
    this.ctx.setTransform(1, 0, 0, 1, 0, 0);
    this.ctx.restore();
    return this;
};

// ????????????????????? add ???????????????
MCanvas.prototype.watermark = function () {
    var image = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var ops = arguments[1];

    if (!image) {
        console.error('mcanvas error : there is not image of watermark.');
        return;
    }
    // ??????????????????
    var _ops$width = ops.width,
        width = _ops$width === undefined ? '40%' : _ops$width,
        _ops$pos = ops.pos,
        pos = _ops$pos === undefined ? 'rightbottom' : _ops$pos,
        _ops$margin = ops.margin,
        margin = _ops$margin === undefined ? 20 : _ops$margin;

    var position = {
        x: 0,
        y: 0,
        scale: 1,
        rotate: 0
    };
    switch (pos) {
        case 'leftTop':
            position.x = 'left:' + margin;
            position.y = 'top:' + margin;
            break;
        case 'leftBottom':
            position.x = 'left:' + margin;
            position.y = 'bottom:' + margin;
            break;
        case 'rightTop':
            position.x = 'right:' + margin;
            position.y = 'top:' + margin;
            break;
        case 'rightBottom':
            position.x = 'right:' + margin;
            position.y = 'bottom:' + margin;
            break;
        default:
    }
    this.add(image, {
        width: width,
        pos: position
    });
    return this;
};

// ???????????????????????????
// ???????????????
// ?????????: add([{image:'',options:{}},{image:'',options:{}}]);
// ?????????: add(image,options);
MCanvas.prototype.add = function () {
    var _this4 = this;

    var image = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var options = arguments[1];

    // ???????????????
    var def = {
        width: '100%',
        crop: {
            x: 0,
            y: 0,
            width: '100%',
            height: '100%'
        },
        pos: {
            x: 0,
            y: 0,
            scale: 1,
            rotate: 0
        }
    };

    if (!_.isArr(image)) image = [{ image: image, options: options }];

    image.forEach(function (v) {
        // ??????????????? add?????? ???????????????????????????
        // ???????????? _handleOps ?????????
        _this4.queue.push(function () {
            _.getImage(v.image, function (img) {
                _this4._add(img, _this4._handleOps(_.extend(def, v.options), img));
            }, _this4.fn.error);
        });
    });

    return this;
};

MCanvas.prototype._add = function (img, ops) {
    if (ops.width == 0) console.warn('mcanvas warn: the width of mc-element is zero');

    var _getSize2 = this._getSize(img),
        iw = _getSize2.iw,
        ih = _getSize2.ih;
    // let ratio = iw / ih;
    // ??????canvas?????????


    var cdx = void 0,
        cdy = void 0,
        cdw = void 0,
        cdh = void 0;
    // ??????canvas?????????
    var _ops$crop = ops.crop,
        lsx = _ops$crop.x,
        lsy = _ops$crop.y,
        lsw = _ops$crop.width,
        lsh = _ops$crop.height;


    var cratio = lsw / lsh;
    var ldx = void 0,
        ldy = void 0,
        ldw = void 0,
        ldh = void 0;
    // ??????canvas?????????;
    var lcvs = document.createElement('canvas');
    var lctx = lcvs.getContext('2d');
    // ??????????????? * 1.4 ?????????????????????????????????????????????????????????????????????
    // ??????????????????????????????????????????????????????????????????????????????????????????????????????5???
    // _ratio ??? ????????? / ????????? ????????????
    var _ratio = iw > ih ? iw / ih : ih / iw;
    var lctxScale = _ratio * 1.4 > 5 ? 5 : _ratio * 1.4;
    var spaceX = void 0,
        spaceY = void 0;

    lcvs.width = lsw * lctxScale;
    lcvs.height = lsh * lctxScale;

    // ??????canvas????????????ios8????????? 2096, ???????????????????????? 4096;
    var shrink = void 0;
    if (_.isIos8() && (lcvs.width > 2096 || lcvs.height > 2096)) {
        if (cratio > 1) {
            shrink = 2096 / lcvs.width;
        } else {
            shrink = 2096 / lcvs.height;
        }
    } else if (lcvs.width > 4096 || lcvs.height > 4096) {
        if (cratio > 1) {
            shrink = 4096 / lcvs.width;
        } else {
            shrink = 4096 / lcvs.height;
        }
    }

    // ?????????canvas???????????????????????????
    ldx = -Math.round(lsw / 2);
    ldy = -Math.round(lsh / 2);
    ldw = lsw;
    ldh = Math.round(lsw / cratio);

    // ???????????????????????????;
    if (shrink) {
        var _map = [lcvs.width, lcvs.height, ldx, ldy, ldw, ldh].map(function (v) {
            return Math.round(v * shrink);
        });

        var _map2 = _slicedToArray(_map, 6);

        lcvs.width = _map2[0];
        lcvs.height = _map2[1];
        ldx = _map2[2];
        ldy = _map2[3];
        ldw = _map2[4];
        ldh = _map2[5];
    }

    lctx.translate(lcvs.width / 2, lcvs.height / 2);
    lctx.rotate(ops.pos.rotate);

    lctx.drawImage(img, lsx, lsy, lsw, lsh, ldx, ldy, ldw, ldh);

    // lcvs.style.width = '300px';
    // document.querySelector('body').appendChild(lcvs);

    cdw = ops.width * lctxScale;
    cdh = cdw / cratio;

    spaceX = (lctxScale - 1) * ops.width / 2;
    spaceY = spaceX / cratio;

    // ????????????????????????
    //    ??????????????? - ??????????????? - ??????????????????????????????
    cdx = ops.pos.x + cdw * (1 - ops.pos.scale) / 2 - spaceX;
    cdy = ops.pos.y + cdh * (1 - ops.pos.scale) / 2 - spaceY;

    cdw *= ops.pos.scale;
    cdh *= ops.pos.scale;

    this.ctx.drawImage(lcvs, cdx, cdy, cdw, cdh);

    lcvs = lctx = null;
    this._next();
};

// ?????????????????????img???canvas
MCanvas.prototype._getSize = function (img) {
    var iw = void 0,
        ih = void 0;
    if (img.tagName === 'IMG') {
        iw = img.naturalWidth;
        ih = img.naturalHeight;
    } else if (img.tagName === 'CANVAS') {
        iw = img.width;
        ih = img.height;
    } else {
        iw = img.offsetWidth;
        ih = img.offsetHeight;
    }
    return { iw: iw, ih: ih };
};
// ?????????????????????
MCanvas.prototype._handleOps = function (ops, img) {
    var cw = this.canvas.width,
        ch = this.canvas.height;

    var _getSize3 = this._getSize(img),
        iw = _getSize3.iw,
        ih = _getSize3.ih;

    // ??????????????????


    var ratio = iw / ih;

    // ???????????????????????????????????????
    var width = this._get(cw, iw, ops.width, 'pos');

    // ????????????????????????
    var maxLsw = void 0,
        maxLsh = void 0;

    // ???????????????
    var _ops$crop2 = ops.crop,
        cropx = _ops$crop2.x,
        cropy = _ops$crop2.y,
        cropw = _ops$crop2.width,
        croph = _ops$crop2.height;

    var crop = {};
    crop.width = this._get(cw, iw, cropw, 'crop');
    crop.height = this._get(ch, ih, croph, 'crop');
    crop.x = this._get(iw, crop.width, cropx, 'crop');
    crop.y = this._get(ih, crop.height, cropy, 'crop');

    // ??????????????????
    if (crop.x > iw) crop.x = iw;
    if (crop.y > ih) crop.y = ih;
    maxLsw = iw - crop.x;
    maxLsh = ih - crop.y;
    if (crop.width > maxLsw) crop.width = maxLsw;
    if (crop.height > maxLsh) crop.height = maxLsh;

    // ???????????????
    var _ops$pos2 = ops.pos,
        px = _ops$pos2.x,
        py = _ops$pos2.y,
        pr = _ops$pos2.rotate,
        ps = _ops$pos2.scale;

    var pos = {
        x: this._get(cw, width, px, 'pos'),
        y: this._get(ch, width / ratio, py, 'pos'),
        scale: ps,
        rotate: parseFloat(pr) * Math.PI / 180
    };
    return { width: width, crop: crop, pos: pos };
};

// --------------------------------------------------------
// ?????????????????????
// --------------------------------------------------------
MCanvas.prototype.text = function () {
    var _this5 = this;

    var context = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
    var ops = arguments[1];

    // ???????????????
    var fontFamily = 'helvetica neue,hiragino sans gb,Microsoft YaHei,arial,tahoma,sans-serif';
    // ?????????????????????;
    var size = this.canvas.width / 20;

    this.queue.push(function () {
        var defaultStyle = {
            color: '#000',
            type: 'fill', // strokeText or fillText,
            lineWidth: 1,
            shadow: {
                color: null,
                blur: 0,
                offsetX: 0,
                offsetY: 0
            }
        };
        var option = {
            width: 300,
            align: 'left',
            smallStyle: _extends({
                font: size * 0.8 + 'px ' + fontFamily,
                lineHeight: size * 0.9
            }, _.deepCopy(defaultStyle)),
            normalStyle: _extends({
                font: size + 'px ' + fontFamily,
                lineHeight: size * 1.1,
                type: 'fill' }, _.deepCopy(defaultStyle)),
            largeStyle: _extends({
                font: size * 1.3 + 'px ' + fontFamily,
                lineHeight: size * 1.4
            }, _.deepCopy(defaultStyle)),
            pos: {
                x: 0,
                y: 0,
                rotate: 0
            }
        };

        option = _.extend(option, ops);

        // ??????????????????????????????????????????????????????
        var parseContext = _this5._parse(context);
        var max = 0,
            maxFont = void 0;
        parseContext.map(function (v) {
            if (v.size > max) {
                max = v.size;
                maxFont = v.type;
            }
        });
        // ???????????????????????????????????????????????????????????????????????????????????????
        var maxFontSize = parseInt(option[maxFont + 'Style'].font);
        if (maxFontSize && option.width < maxFontSize) option.width = maxFontSize;

        _this5._text(parseContext, option);
        _this5._resetCtx()._next();
    });
    return this;
};
// ???????????????????????????
// ?????? <s></s> <b></b>
MCanvas.prototype._parse = function (context) {
    var arr = context.split(/<s>|<b>/);
    var result = [];
    for (var i = 0; i < arr.length; i++) {
        var value = arr[i];
        if (/<\/s>|<\/b>/.test(value)) {
            var splitTag = /<\/s>/.test(value) ? '</s>' : '</b>',
                type = /<\/s>/.test(value) ? 'small' : 'large';
            var tmp = arr[i].split(splitTag);
            result.push({
                type: type,
                text: tmp[0],
                // ??????????????????????????????
                size: type == 'small' ? 0 : 2
            });
            tmp[1] && result.push({
                type: 'normal',
                text: tmp[1],
                size: 1
            });
            continue;
        }
        arr[i] && result.push({
            text: arr[i],
            type: 'normal',
            size: 1
        });
    }
    return result;
};

MCanvas.prototype._text = function (textArr, option) {
    var _this6 = this;

    this.data.textId++;
    this.data.text[this.data.textId] = {};

    // ?????????????????????
    option.width = this._get(this.canvas.width, 0, option.width, 'pos');

    var style = void 0,
        line = 1,
        length = 0,
        lineHeight = getLineHeight(textArr, option),
        x = this._get(this.canvas.width, option.width, 0, 'pos'),
        y = this._get(this.canvas.height, 0, 0, 'pos') + lineHeight;

    // data:???????????????
    // lineWidth:?????????
    this.data.text[this.data.textId][line] = {
        data: [],
        lineWidth: 0
    };

    // ?????????????????????
    textArr.forEach(function (v) {
        style = option[v.type + 'Style'];
        _this6.ctx.font = style.font;
        var width = _this6.ctx.measureText(v.text).width;

        // ?????? <br> ????????????????????? '|',????????????????????????????????????
        var context = v.text.replace(/<br>/g, '|');

        // ???????????????????????????????????????????????????????????????????????????
        if (length + width > option.width || context.indexOf('|') !== -1) {
            for (var i = 0, fontLength = context.length; i < fontLength; i++) {
                var font = context[i];
                width = _this6.ctx.measureText(font).width;

                // ???????????????????????? > ??????????????? || ????????????????????????,?????????????????????
                if (length + width > option.width || font == '|') {
                    length = 0;
                    x = _this6._get(_this6.canvas.width, option.width, 0, 'pos');
                    y += lineHeight;
                    line += 1;
                    _this6.data.text[_this6.data.textId][line] = {
                        data: [],
                        lineWidth: 0
                    };
                    if (font == '|') continue;
                }
                _this6.data.text[_this6.data.textId][line]['data'].push({
                    context: font, x: x, y: y, style: style, width: width
                });
                length += width;
                x += width;
                _this6.data.text[_this6.data.textId][line]['lineWidth'] = length;
            }
        } else {
            _this6.data.text[_this6.data.textId][line]['data'].push({
                context: context, x: x, y: y, style: style, width: width
            });
            length += width;
            x += width;
            _this6.data.text[_this6.data.textId][line]['lineWidth'] = length;
        }
    });

    // ?????????????????????
    var tcvs = document.createElement('canvas');
    var tctx = tcvs.getContext('2d');
    var tdx = this._get(this.canvas.width, option.width, option.pos.x, 'pos'),
        tdy = this._get(this.canvas.height, 0, option.pos.y, 'pos');
    var tdw = void 0,
        tdh = void 0;
    tdw = tcvs.width = option.width;
    tdh = tcvs.height = this._getTextRectHeight(line);

    // ??????????????????????????????????????????
    _.forin(this.data.text[this.data.textId], function (k, v) {
        // ?????? align ????????????
        var add = 0;
        if (v.lineWidth < option.width) {
            if (option.align == 'center') {
                add = (option.width - v.lineWidth) / 2;
            } else if (option.align == 'right') {
                add = option.width - v.lineWidth;
            }
        }
        v.data.forEach(function (text) {
            text.x += add;
            _this6._fillText(tctx, text);
        });
    });

    // tcvs.style.width = '300px';
    // document.querySelector('body').appendChild(tcvs);

    // ?????????????????????
    var originX = tdx + tdw / 2,
        originY = tdy + tdh / 2;
    this.ctx.translate(originX, originY);
    this.ctx.rotate(parseFloat(option.pos.rotate) * Math.PI / 180);
    this.ctx.drawImage(tcvs, -tdw / 2, -tdh / 2, tdw, tdh);

    // ???????????????
    function getLineHeight(textArr, option) {
        var lh = 0,
            vlh = void 0;
        textArr.forEach(function (v) {
            vlh = option[v.type + 'Style'].lineHeight;
            if (vlh > lh) lh = vlh;
        });
        return lh;
    }
};

MCanvas.prototype._fillText = function (ctx, text) {
    var context = text.context,
        x = text.x,
        y = text.y,
        style = text.style;
    var align = style.align,
        lineWidth = style.lineWidth,
        shadow = style.shadow;
    var color = shadow.color,
        blur = shadow.blur,
        offsetX = shadow.offsetX,
        offsetY = shadow.offsetY;

    ctx.font = style.font;
    ctx.textAlign = align;
    ctx.textBaseline = 'alphabetic';
    ctx.lineWidth = lineWidth;
    ctx.shadowColor = color;
    ctx.shadowBlur = blur;
    ctx.shadowOffsetX = offsetX;
    ctx.shadowOffsetY = offsetY;

    if (style.gradient) {
        var _style$gradient = style.gradient,
            type = _style$gradient.type,
            colorStop = _style$gradient.colorStop;

        var x1 = void 0,
            y1 = void 0,
            x2 = void 0,
            y2 = void 0;
        if (type == 1) {
            x1 = x;y1 = y;
            x2 = x + text.width;y2 = y;
        } else {
            x1 = x;y1 = y - style.lineHeight;
            x2 = x;y2 = y;
        }
        var grad = ctx.createLinearGradient(x1, y1, x2, y2);
        var colorNum = colorStop.length || 0;
        _.forin(colorStop, function (i, v) {
            grad.addColorStop(1 / colorNum * (+i + 1), v);
        });
        ctx[style.type + 'Style'] = grad;
    } else {
        ctx[style.type + 'Style'] = style.color;
    }

    ctx[style.type + 'Text'](context, x, y);
    this._resetCtx();
};

MCanvas.prototype._getTextRectHeight = function (lastLine) {
    var lastLineData = this.data.text[this.data.textId][lastLine].data[0];
    return lastLineData.y + lastLineData.style.lineHeight;
};

// --------------------------------------------------------
// ????????????????????????
// --------------------------------------------------------

// ?????????????????????
// ?????? 5 ??? value ??????
// x:250, x:'250px', x:'100%', x:'left:250',x:'center',
// width:100,width:'100px',width:'100%'
MCanvas.prototype._get = function (par, child, str, type) {
    var result = str;
    if (typeof str === 'string') {
        if (str.indexOf(':') !== -1 && type == 'pos') {
            var arr = str.split(':');
            switch (arr[0]) {
                case 'left':
                case 'top':
                    result = +arr[1].replace('px', '');
                    break;
                case 'right':
                case 'bottom':
                    result = par - +arr[1].replace('px', '') - child;
                    break;
                default:
            }
        } else if (str.indexOf('px') !== -1) {
            result = +str.replace('px', '');
        } else if (str.indexOf('%') !== -1) {
            if (type == 'crop') {
                result = child * +str.replace('%', '') / 100;
            } else {
                result = par * +str.replace('%', '') / 100;
            }
        } else if (str == 'center') {
            result = (par - child) / 2;
        } else if (str == 'origin') {
            result = child;
        } else {
            result = +str;
        }
    }
    return Math.round(result);
};

// ???????????????
MCanvas.prototype.draw = function (ops) {
    var _this7 = this;

    var _ops = {
        type: 'jpg',
        quality: .9,
        success: function success() {},
        error: function error() {}
    },
        b64 = void 0;
    if (typeof ops == 'function') {
        _ops.success = ops;
    } else {
        _ops = _.extend(_ops, ops);
        if (_ops.type == 'jpg') _ops.type = 'jpeg';
    }
    this.fn.error = _ops.error;
    this.fn.success = function () {
        setTimeout(function () {
            b64 = _this7.canvas.toDataURL('image/' + _ops.type, _ops.quality);
            _ops.success(b64);
        }, 0);
    };
    this._next();
    return this;
};

MCanvas.prototype._next = function () {
    if (this.queue.length > 0) {
        this.queue.shift()();
    } else {
        this.fn.success();
    }
};

MCanvas.prototype.clear = function () {
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
    return this;
};

return MCanvas;

})));
//# sourceMappingURL=mcanvas.js.map
