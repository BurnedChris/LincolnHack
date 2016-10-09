(function () {
    var KssStateGenerator;

    KssStateGenerator = (function () {
        var pseudo_selectors;

        pseudo_selectors = ['hover', 'enabled', 'disabled', 'active', 'visited', 'focus', 'target', 'checked', 'empty', 'first-of-type', 'last-of-type', 'first-child', 'last-child'];

        function KssStateGenerator() {
            var idx, idxs, pseudos, replaceRule, rule, stylesheet, _i, _len, _len2, _ref, _ref2;
            pseudos = new RegExp("(\\:" + (pseudo_selectors.join('|\\:')) + ")", "g");
            try {
                _ref = document.styleSheets;
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                    stylesheet = _ref[_i];
                    if (stylesheet.href && stylesheet.href.indexOf(document.domain) >= 0) {
                        idxs = [];
                        _ref2 = stylesheet.cssRules;
                        for (idx = 0, _len2 = _ref2.length; idx < _len2; idx++) {
                            rule = _ref2[idx];
                            if ((rule.type === CSSRule.STYLE_RULE) && pseudos.test(rule.selectorText)) {
                                replaceRule = function (matched, stuff) {
                                    return matched.replace(/\:/g, '.pseudo-class-');
                                };
                                this.insertRule(rule.cssText.replace(pseudos, replaceRule));
                            }
                            pseudos.lastIndex = 0;
                        }
                    }
                }
            } catch (_error) {
            }
        }

        KssStateGenerator.prototype.insertRule = function (rule) {
            var headEl, styleEl;
            headEl = document.getElementsByTagName('head')[0];
            styleEl = document.createElement('style');
            styleEl.type = 'text/css';
            if (styleEl.styleSheet) {
                styleEl.styleSheet.cssText = rule;
            } else {
                styleEl.appendChild(document.createTextNode(rule));
            }
            return headEl.appendChild(styleEl);
        };

        return KssStateGenerator;

    })();


    // colors.
    (function () {
        var _$pram = $('.kss-parameters');
        var _$pram_item = $('.kss-parameters__item');
        var _$pram_des = $('.kss-parameters__description');
        var _$pram_name = $('.kss-parameters__name');

        if (_$pram) {
            _$pram_item.each(function (index) {
                var description = $(this).find(_$pram_des).text().trim().replace(/, /g, ',');
                var colorName = description.split(',')[1] ? description.split(',')[1] : '';
                var colorVar = $(this).find(_$pram_name).text().trim();
                var color = description.split(',')[0];
                var isColor = /(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)/i.test(color);
                var colorContent = '<span class="kss-color__name">' + colorName + '</span>' +
                    '<span class="kss-color__code">' + color + '</span>' +
                    '<span class="kss-color__var">' + colorVar + '</span>';
                if (isColor) {
                    $(this).parent().addClass('kss-colors-container');
                    $(this).addClass('kss-color').css('background', color);
                    $(this).find(_$pram_des).html(colorContent);
                }
            });
        }
    })();

    new KssStateGenerator;

}).call(this);
