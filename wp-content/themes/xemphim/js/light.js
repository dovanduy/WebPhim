var Light = {};
jQuery.extend(!0, {
    context: function (e, t) {
        if ("string" == typeof t) {
            var n = e;
            e = e[t], t = n
        }
        return function () {
            return e.apply(t, arguments)
        }
    }
}), Light.ajax = function (e) {
    var t = e.success || null,
        n = e.error || null;
    e.success = function (i) {
        try {
            i = $.parseJSON(i) || {}
        } catch (r) {
            return "function" == typeof n ? n.call(this, i) : "undefined" != typeof e.errorMessage ? alert(e.errorMessage) : alert(Light.ajax.errorMessage), void 0
        }
        if ("" != $.trim(i.page_title) && (document.title = i.page_title), i.message && i.alert) {
            if ($.isArray(i.message) || $.isPlainObject(i.message)) {
                var a = "";
                for (var o in i.message) a += i.message[o] + "\n";
                i.message = a
            }
            alert(i.message)
        }
        "function" == typeof t && t.call(this, i), "" != $.trim(i.redirect) && setTimeout(function () {
            window.location = i.redirect
        }, 3e3)
    }, $.ajax(e)
}, Light.ajax.errorMessage = "An error occured. Please try again.", Light.Overlay = function (e) {
    return Light.Overlay.id = "light-overlay", this.setting = $.extend({
        zIndex: 3,
        background: null,
        opacity: .9,
        useEffect: !1,
        onClickCallback: null
    }, e), this.$overlay = $("#" + Light.Overlay.id), 0 == this.$overlay.length && (this.$overlay = $("<div />").attr("id", Light.Overlay.id).appendTo(document.body)), this.$overlay.css({
        position: "fixed",
        zIndex: this.setting.zIndex,
        background: this.setting.background,
        opacity: this.setting.opacity,
        top: 0,
        left: 0,
        display: "none",
        width: "100%",
        height: "100%"
    }).click($.context(this, "destroy")).data("effect", this.setting.useEffect), this.setting.useEffect ? this.$overlay.fadeIn(300) : this.$overlay.show(), this.$overlay
}, Light.Overlay.prototype.destroy = function () {
    "function" == typeof this.setting.onClickCallback && this.setting.onClickCallback.call(), Light.Overlay.hide()
}, Light.Overlay.create = function (e) {
    new Light.Overlay(e)
}, Light.Overlay.hide = function () {
    var e = $("#" + Light.Overlay.id);
    e.length && (e.data("effect") ? e.fadeOut(null, null, function () {
        e.remove()
    }) : e.remove())
}, Light.scrollTop = function (e, t, n, i) {
    $("html,body").animate({
        scrollTop: $(e).offset().top
    }, t || "slow", n, i)
}, Light.AutoComplete = function (e) {
    this.__construct(e)
}, Light.AutoComplete.prototype = {
    __construct: function (e) {
        this.$input = e;
        var t = {
            multiple: !1,
            minLength: 2,
            queryKey: "q",
            extraParams: {}
        };
        this.url || (this.url = "ajax/common"), this.multiple = t.multiple, this.minLength = t.minLength, this.queryKey = t.queryKey, this.extraParams = t.extraParams, this.$results = null, this.selectedValue = 0, this.resultVisible = !1, this.timer = null, this.setup()
    },
    setup: function () {
        this.$input.keyup($.context(this, "keyup"))
    },
    keyup: function (e) {
        switch (e.keyCode) {
        case 27:
            return this.hideResults();
        case 13:
            return this.resultVisible && (this.addValue(this.$results.find("li:eq(" + this.selectedValue + ")").text()), this.hideResults()), void 0;
        case 38:
            return this.selectedValue > 0 && --this.selectedValue, this.resultHover(), void 0;
        case 40:
            return this.selectedValue < this.$results.children().length - 1 && ++this.selectedValue, this.resultHover(), void 0
        }
        return "" == this.val() ? (this.hideResults(), void 0) : (this.timer && clearTimeout(this.timer), this.timer = setTimeout($.context(this, "load"), 250), void 0)
    },
    load: function () {
        this.timer && clearTimeout(this.timer);
        var e = this.getPartialValue();
        return e.length < this.minLength ? (clearTimeout(this.timer), void 0) : (this.extraParams[this.queryKey] = e, $.ajax({
            url: this.url,
            type: "GET",
            data: this.extraParams,
            success: $.context(this, "showResults")
        }), void 0)
    },
    showResults: function (e) {
        var e = $.parseJSON(e).json || {};
        this.$results || (this.$results = $("<ul>").css({
            position: "absolute",
            "z-index": 100,
            top: this.$input.offset().top + this.$input.height() + parseInt(this.$input.css("padding-top")) + parseInt(this.$input.css("padding-bottom")),
            left: this.$input.offset().left
        }).addClass("autocomplete-list").appendTo(document.body)), this.hideResults();
        var t = 0;
        for (var n in e) $("<li>").css("cursor", "pointer").data("autocomplete-id", t++).addClass("item-", t).attr("data-key", n).click($.context(this, "resultClick")).hover($.context(this, "resultHover")).html(e[n].replace(new RegExp("(" + this.getPartialValue() + ")", "ig"), "<strong>$1</strong>")).appendTo(this.$results);
        t && (Light.Overlay.create({
            onClickCallback: $.context(this, "hideResults")
        }), this.resultVisible = !0, this.$results.show(), this.resultHover())
    },
    resultHover: function (e) {
        this.resultVisible && ("undefined" != typeof e && (this.selectedValue = $(e.currentTarget).data("autocomplete-id")), this.$results.find("li").removeClass("hover"), this.$results.find("li:eq(" + this.selectedValue + ")").addClass("hover"))
    },
    resultClick: function (e) {
        this.addValue($(e.currentTarget).text()), this.hideResults(), this.$input.focus()
    },
    hideResults: function () {
        this.selectedValue = 0, this.resultVisible = !1, this.$results && this.$results.empty().hide(), Light.Overlay.hide()
    },
    addValue: function (e) {
        var t = this.getFullValues();
        t.pop(), t.push(e), this.val(t.join(this.multiple + " "))
    },
    val: function (e) {
        return "undefined" == typeof e ? this.getFullValues().join(this.multiple + " ") : (this.$input.val(e), void 0)
    },
    getPartialValue: function () {
        var e = this.$input.val();
        if (!this.multiple) return e;
        var t = e.lastIndexOf(this.multiple);
        return -1 != t ? $.trim(e.substr(t + this.multiple.length)) : e
    },
    getFullValues: function () {
        var e = $.trim(this.$input.val());
        if ("" == e) return [];
        if (this.multiple) {
            var t = e.split(this.multiple);
            if (1 == t.length) return [e];
            var n = [];
            for (var i in t) $.trim(t[i]) && n.push($.trim(t[i]));
            return n
        }
        return [e]
    }
}, Light.Popup = {
    popName: "Chip-LightPopup",
    alwaysPop: !1,
    onNewTab: !0,
    eventType: 1,
    defaults: {
        width: window.screen.width,
        height: window.screen.height,
        left: 0,
        top: 0,
        location: 1,
        tollbar: 1,
        status: 1,
        menubar: 1,
        scrollbars: 1,
        resizable: 1
    },
    newWindowDefaults: {
        width: window.screen.width - 20,
        height: window.screen.height - 20
    },
    __newWindow: {
        scrollbars: 0
    },
    __counter: 0,
    create: function (link, options) {
        var optionsOriginal = options = options || {}, me = this,
            popName = me.popName + "_" + me.__counter++,
            keys = ["onNewTab", "eventType", "cookieExpires", "alwaysPop"];
        for (var i in keys) {
            var key = keys[i];
            "undefined" != typeof options[key] ? (eval("var " + key + " = options." + key), delete options[key]) : eval("var " + key + " = me." + key)
        }
        alwaysPop && (cookieExpires = -1);
        for (var i in me.defaults) "undefined" == typeof options[i] && (options[i] = me.defaults[i], onNewTab || "undefined" == typeof me.newWindowDefaults[i] || (options[i] = me.newWindowDefaults[i]));
        for (var i in me.__newWindow) options[i] = me.__newWindow[i];
        var params = [];
        for (var i in options) params.push(i + "=" + options[i]);
        params = params.join(",");
        var executed = !1,
            execute = function () {
                if (null === me.cookie(popName) && !executed) {
                    if ("undefined" != typeof window.chrome && -1 != navigator.userAgent.indexOf("Windows") && "undefined" != typeof ___lastPopTime && ___lastPopTime + 5 > (new Date).getTime()) return;
                    if (executed = !0, onNewTab) var e = window.open(link, popName);
                    else var e = window.open(link, "_blank", params);
                    e && e.blur(), window.focus(), me.cookie(popName, 1, cookieExpires), ___lastPopTime = (new Date).getTime(), -1 != navigator.userAgent.indexOf("Mac OS") && "undefined" != typeof window.chrome && setTimeout(function () {
                        e.innerWidth && e.document.documentElement.clientWidth || me.create(link, optionsOriginal)
                    }, 100)
                }
            };
        2 == eventType || navigator.userAgent.match(/msie\s+(6|7|8)/i) ? window.addEventListener ? window.addEventListener("load", function () {
            document.body.addEventListener("click", execute)
        }) : window.attachEvent("onload", function () {
            document.body.attachEvent("onclick", execute)
        }) : 1 == eventType && (window.addEventListener ? window.addEventListener("click", execute) : window.attachEvent("onclick", execute))
    },
    cookie: function (e, t, n) {
        if (1 == arguments.length) {
            var i = document.cookie.match(new RegExp(e + "=[^;]+", "i"));
            return i ? decodeURIComponent(i[0].split("=")[1]) : null
        }
        if (null == n || "undefined" == typeof n) expires = "";
        else {
            var r;
            "number" == typeof n ? (r = new Date, r.setTime(r.getTime() + 1e3 * 60 * 60 * 24 * n)) : r = n, expires = "; expires=" + r.toUTCString()
        }
        var t = escape(t) + expires + "; path=/";
        document.cookie = e + "=" + t
    }
};
var _gaq = _gaq || [];
Light.GA = {
    init: function (e) {
        _gaq.push(["_setAccount", e]), _gaq.push(["_trackPageview"]),
        function () {
            var e = document.createElement("script");
            e.type = "text/javascript", e.async = !0, e.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
            var t = document.getElementsByTagName("script")[0];
            t.parentNode.insertBefore(e, t)
        }()
    }
}, Light.Facebook = {
    appId: null,
    init: function (e) {
        var t = this;
        t.appId = e || t.appId,
        function (e, n, i) {
            var r, a = e.getElementsByTagName(n)[0];
            e.getElementById(i) || (r = e.createElement(n), r.id = i, r.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1&amp;appId=" + t.appId, a.parentNode.insertBefore(r, a))
        }(document, "script", "facebook-jssdk")
    }
};