!function(e, t, n) {
    function o() {
        function o(e) {
            var t = p;
            return "undefined" != typeof t[e] ? t[e] : h[e]
        }
        function l(t) {
            var o = t || e.event, r = o.keyCode || o.which;
            if (-1 !== [9, 13, 32, 27].indexOf(r)) {
                for (var a = o.target || o.srcElement, i = -1, l = 0; l < M.length; l++)
                    if (a === M[l]) {
                        i = l;
                        break
                    }
                9 === r ? (a = -1 === i ? B : i === M.length - 1 ? M[0] : M[i + 1], z(o), a.focus(), s(a, g.confirmButtonColor)) : (a = 13 === r || 32 === r ? -1 === i ? B : n : 27 === r && g.allowEscapeKey === !0 ? I : n, a !== n && L(a, o))
            }
        }
        function f(t) {
            var n = t || e.event, o = n.target || n.srcElement, r = n.relatedTarget, a = k(A, "visible");
            if (a) {
                var i = -1;
                if (null !== r) {
                    for (var l = 0; l < M.length; l++)
                        if (r === M[l]) {
                            i = l;
                            break
                        }
                    -1 === i && o.focus()
                } else
                    v = o
            }
        }
        var p = arguments[0];
        if (arguments[0] === n)
            return m("SweetAlert expects at least 1 attribute!"), !1;
        var g = i({}, h);
        switch (typeof arguments[0]) {
            case "string":
                g.title = arguments[0], g.text = arguments[1] || "", g.type = arguments[2] || "";
                break;
            case "object":
                if (arguments[0].title === n)
                    return m('Missing "title" argument!'), !1;
                g.title = arguments[0].title;
                for (var b = ["text", "type", "customClass", "allowOutsideClick", "showCancelButton", "closeOnConfirm", "closeOnCancel", "timer", "confirmButtonColor", "cancelButtonText", "imageUrl", "imageSize", "html", "animation", "allowEscapeKey"], w = b.length, x = 0; w > x; x++) {
                    var C = b[x];
                    g[C] = o(C)
                }
                g.confirmButtonText = g.showCancelButton ? "Confirm" : h.confirmButtonText, g.confirmButtonText = o("confirmButtonText"), g.doneFunction = arguments[1] || null;
                break;
            default:
                return m('Unexpected type of argument! Expected "string" or "object", got ' + typeof arguments[0]), !1
        }
        r(g), u(), c();
        for (var A = S(), T = function(t) {
            var n = t || e.event, o = n.target || n.srcElement, r = -1 !== o.className.indexOf("confirm"), i = k(A, "visible"), l = g.doneFunction && "true" === A.getAttribute("data-has-done-function");
            switch (n.type) {
                case "mouseover":
                    r && (o.style.backgroundColor = a(g.confirmButtonColor, -.04));
                    break;
                case "mouseout":
                    r && (o.style.backgroundColor = g.confirmButtonColor);
                    break;
                case "mousedown":
                    r && (o.style.backgroundColor = a(g.confirmButtonColor, -.14));
                    break;
                case "mouseup":
                    r && (o.style.backgroundColor = a(g.confirmButtonColor, -.04));
                    break;
                case "focus":
                    var s = A.querySelector("button.confirm"), c = A.querySelector("button.cancel");
                    r ? c.style.boxShadow = "none" : s.style.boxShadow = "none";
                    break;
                case "click":
                    if (r && l && i)
                        g.doneFunction(!0), g.closeOnConfirm && e.sweetAlert.close();
                    else if (l && i) {
                        var u = String(g.doneFunction).replace(/\s/g, ""), f = "function(" === u.substring(0, 9) && ")" !== u.substring(9, 10);
                        f && g.doneFunction(!1), g.closeOnCancel && e.sweetAlert.close()
                    } else
                        e.sweetAlert.close()
            }
        }, E = A.querySelectorAll("button"), q = 0; q < E.length; q++)
            E[q].onclick = T, E[q].onmouseover = T, E[q].onmouseout = T, E[q].onmousedown = T, E[q].onfocus = T;
        d = t.onclick, t.onclick = function(t) {
            var n = t || e.event, o = n.target || n.srcElement, r = A === o, a = O(A, o), i = k(A, "visible"), l = "true" === A.getAttribute("data-allow-ouside-click");
            !r && !a && i && l && e.sweetAlert.close()
        };
        var B = A.querySelector("button.confirm"), I = A.querySelector("button.cancel"), M = A.querySelectorAll("button[tabindex]");
        y = e.onkeydown, e.onkeydown = l, B.onblur = f, I.onblur = f, e.onfocus = function() {
            e.setTimeout(function() {
                v !== n && (v.focus(), v = n)
            }, 0)
        }
    }
    function r(e) {
        var t = S(), n = t.querySelector("h2"), o = t.querySelector("p"), r = t.querySelector("button.cancel"), a = t.querySelector("button.confirm");
        if (n.innerHTML = e.html ? e.title : T(e.title).split("\n").join("<br>"), o.innerHTML = e.html ? e.text : T(e.text || "").split("\n").join("<br>"), e.text && q(o), e.customClass)
            C(t, e.customClass), t.setAttribute("data-custom-class", e.customClass);
        else {
            var i = t.getAttribute("data-custom-class");
            A(t, i), t.setAttribute("data-custom-class", "")
        }
        if (I(t.querySelectorAll(".icon")), e.type && !f()) {
            for (var l = !1, c = 0; c < w.length; c++)
                if (e.type === w[c]) {
                    l = !0;
                    break
                }
            if (!l)
                return m("Unknown alert type: " + e.type), !1;
            var u = t.querySelector(".icon." + e.type);
            switch (q(u), e.type) {
                case "success":
                    C(u, "animate"), C(u.querySelector(".tip"), "animateSuccessTip"), C(u.querySelector(".long"), "animateSuccessLong");
                    break;
                case "error":
                    C(u, "animateErrorIcon"), C(u.querySelector(".x-mark"), "animateXMark");
                    break;
                case "warning":
                    C(u, "pulseWarning"), C(u.querySelector(".body"), "pulseWarningIns"), C(u.querySelector(".dot"), "pulseWarningIns")
            }
        }
        if (e.imageUrl) {
            var p = t.querySelector(".icon.custom");
            p.style.backgroundImage = "url(" + e.imageUrl + ")", q(p);
            var d = 80, y = 80;
            if (e.imageSize) {
                var v = e.imageSize.toString().split("x"), g = v[0], b = v[1];
                g && b ? (d = g, y = b) : m("Parameter imageSize expects value with format WIDTHxHEIGHT, got " + e.imageSize)
            }
            p.setAttribute("style", p.getAttribute("style") + "width:" + d + "px; height:" + y + "px")
        }
        t.setAttribute("data-has-cancel-button", e.showCancelButton), e.showCancelButton ? r.style.display = "inline-block" : I(r), e.cancelButtonText && (r.innerHTML = T(e.cancelButtonText)), e.confirmButtonText && (a.innerHTML = T(e.confirmButtonText)), a.style.backgroundColor = e.confirmButtonColor, s(a, e.confirmButtonColor), t.setAttribute("data-allow-ouside-click", e.allowOutsideClick);
        var h = e.doneFunction ? !0 : !1;
        t.setAttribute("data-has-done-function", h), e.animation ? t.setAttribute("data-animation", "pop") : t.setAttribute("data-animation", "none"), t.setAttribute("data-timer", e.timer)
    }
    function a(e, t) {
        e = String(e).replace(/[^0-9a-f]/gi, ""), e.length < 6 && (e = e[0] + e[0] + e[1] + e[1] + e[2] + e[2]), t = t || 0;
        var n, o, r = "#";
        for (o = 0; 3 > o; o++)
            n = parseInt(e.substr(2 * o, 2), 16), n = Math.round(Math.min(Math.max(0, n + n * t), 255)).toString(16), r += ("00" + n).substr(n.length);
        return r
    }
    function i(e, t) {
        for (var n in t)
            t.hasOwnProperty(n) && (e[n] = t[n]);
        return e
    }
    function l(e) {
        var t = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(e);
        return t ? parseInt(t[1], 16) + ", " + parseInt(t[2], 16) + ", " + parseInt(t[3], 16) : null
    }
    function s(e, t) {
        var n = l(t);
        e.style.boxShadow = "0 0 2px rgba(" + n + ", 0.8), inset 0 0 0 1px rgba(0, 0, 0, 0.05)"
    }
    function c() {
        var n = S();
        D(x(), 10), q(n), C(n, "showSweetAlert"), A(n, "hideSweetAlert"), p = t.activeElement;
        var o = n.querySelector("button.confirm");
        o.focus(), setTimeout(function() {
            C(n, "visible")
        }, 500);
        var r = n.getAttribute("data-timer");
        "null" !== r && "" !== r && (n.timeout = setTimeout(function() {
            e.sweetAlert.close()
        }, r))
    }
    function u() {
        var e = S();
        e.style.marginTop = M(S())
    }
    function f() {
        return e.attachEvent && !e.addEventListener ? !0 : !1
    }
    function m(t) {
        e.console && e.console.log("SweetAlert: " + t)
    }
    var p, d, y, v, g = ".sweet-alert", b = ".sweet-overlay", w = ["error", "warning", "info", "success"], h = {title: "",text: "",type: null,allowOutsideClick: !1,showCancelButton: !1,closeOnConfirm: !0,closeOnCancel: !0,confirmButtonText: "OK",confirmButtonColor: "#AEDEF4",cancelButtonText: "Cancel",imageUrl: null,imageSize: null,timer: null,customClass: "",html: !1,animation: !0,allowEscapeKey: !0}, S = function() {
        var e = t.querySelector(g);
        return e || (sweetAlertInitialize(), e = S()), e
    }, x = function() {
        return t.querySelector(b)
    }, k = function(e, t) {
        return new RegExp(" " + t + " ").test(" " + e.className + " ")
    }, C = function(e, t) {
        k(e, t) || (e.className += " " + t)
    }, A = function(e, t) {
        var n = " " + e.className.replace(/[\t\r\n]/g, " ") + " ";
        if (k(e, t)) {
            for (; n.indexOf(" " + t + " ") >= 0; )
                n = n.replace(" " + t + " ", " ");
            e.className = n.replace(/^\s+|\s+$/g, "")
        }
    }, T = function(e) {
        var n = t.createElement("div");
        return n.appendChild(t.createTextNode(e)), n.innerHTML
    }, E = function(e) {
        e.style.opacity = "", e.style.display = "block"
    }, q = function(e) {
        if (e && !e.length)
            return E(e);
        for (var t = 0; t < e.length; ++t)
            E(e[t])
    }, B = function(e) {
        e.style.opacity = "", e.style.display = "none"
    }, I = function(e) {
        if (e && !e.length)
            return B(e);
        for (var t = 0; t < e.length; ++t)
            B(e[t])
    }, O = function(e, t) {
        for (var n = t.parentNode; null !== n; ) {
            if (n === e)
                return !0;
            n = n.parentNode
        }
        return !1
    }, M = function(e) {
        e.style.left = "-9999px", e.style.display = "block";
        var t, n = e.clientHeight;
        return t = "undefined" != typeof getComputedStyle ? parseInt(getComputedStyle(e).getPropertyValue("padding"), 10) : parseInt(e.currentStyle.padding), e.style.left = "", e.style.display = "none", "-" + parseInt(n / 2 + t) + "px"
    }, D = function(e, t) {
        if (+e.style.opacity < 1) {
            t = t || 16, e.style.opacity = 0, e.style.display = "block";
            var n = +new Date, o = function() {
                e.style.opacity = +e.style.opacity + (new Date - n) / 100, n = +new Date, +e.style.opacity < 1 && setTimeout(o, t)
            };
            o()
        }
        e.style.display = "block"
    }, H = function(e, t) {
        t = t || 16, e.style.opacity = 1;
        var n = +new Date, o = function() {
            e.style.opacity = +e.style.opacity - (new Date - n) / 100, n = +new Date, +e.style.opacity > 0 ? setTimeout(o, t) : e.style.display = "none"
        };
        o()
    }, L = function(n) {
        if ("function" == typeof MouseEvent) {
            var o = new MouseEvent("click", {view: e,bubbles: !1,cancelable: !0});
            n.dispatchEvent(o)
        } else if (t.createEvent) {
            var r = t.createEvent("MouseEvents");
            r.initEvent("click", !1, !1), n.dispatchEvent(r)
        } else
            t.createEventObject ? n.fireEvent("onclick") : "function" == typeof n.onclick && n.onclick()
    }, z = function(t) {
        "function" == typeof t.stopPropagation ? (t.stopPropagation(), t.preventDefault()) : e.event && e.event.hasOwnProperty("cancelBubble") && (e.event.cancelBubble = !0)
    };
    e.sweetAlertInitialize = function() {
        var e = '<div class="sweet-overlay" tabIndex="-1"></div><div class="sweet-alert" tabIndex="-1"><div class="icon error"><span class="x-mark"><span class="line left"></span><span class="line right"></span></span></div><div class="icon warning"> <span class="body"></span> <span class="dot"></span> </div> <div class="icon info"></div> <div class="icon success"> <span class="line tip"></span> <span class="line long"></span> <div class="placeholder"></div> <div class="fix"></div> </div> <div class="icon custom"></div> <h2>Title</h2><p>Text</p><button class="cancel" tabIndex="2">Cancel</button><button class="confirm" tabIndex="1">OK</button></div>', n = t.createElement("div");
        for (n.innerHTML = e; n.firstChild; )
            t.body.appendChild(n.firstChild)
    }, e.sweetAlert = e.swal = function() {
        var e = arguments;
        if (null !== S())
            o.apply(this, e);
        else
            var t = setInterval(function() {
                null !== S() && (clearInterval(t), o.apply(this, e))
            }, 100)
    }, e.sweetAlert.setDefaults = e.swal.setDefaults = function(e) {
        if (!e)
            throw new Error("userParams is required");
        if ("object" != typeof e)
            throw new Error("userParams has to be a object");
        i(h, e)
    }, e.sweetAlert.close = e.swal.close = function() {
        var o = S();
        H(x(), 5), H(o, 5), A(o, "showSweetAlert"), C(o, "hideSweetAlert"), A(o, "visible");
        var r = o.querySelector(".icon.success");
        A(r, "animate"), A(r.querySelector(".tip"), "animateSuccessTip"), A(r.querySelector(".long"), "animateSuccessLong");
        var a = o.querySelector(".icon.error");
        A(a, "animateErrorIcon"), A(a.querySelector(".x-mark"), "animateXMark");
        var i = o.querySelector(".icon.warning");
        A(i, "pulseWarning"), A(i.querySelector(".body"), "pulseWarningIns"), A(i.querySelector(".dot"), "pulseWarningIns"), e.onkeydown = y, t.onclick = d, p && p.focus(), v = n, clearTimeout(o.timeout)
    }
}(window, document);
