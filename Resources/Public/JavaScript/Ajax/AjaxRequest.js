/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
var __importDefault = this && this.__importDefault || function (e) {
    return e && e.__esModule ? e : {default: e}
};

/**
 * Module: TYPO3/CMS/Core/Ajax/AjaxRequest
 * @internal
 */
define(["require", "exports", "../BackwardCompat/JQueryNativePromises", "./AjaxResponse", "./InputTransformer"], (function (e, t, s, n, r) {
    "use strict";
    s = __importDefault(s);

    class o {
        constructor(e) {
            this.queryArguments = "", this.url = e, this.abortController = new AbortController, s.default.support()
        }

        withQueryArguments(e) {
            const t = this.clone();
            return t.queryArguments = ("" !== t.queryArguments ? "&" : "") + r.InputTransformer.toSearchParams(e), t
        }

        async get(e = {}) {
            const t = await this.send(Object.assign(Object.assign({}, {method: "GET"}), e));
            return new n.AjaxResponse(t)
        }

        async post(e, t = {}) {
            const s = {
                body: "string" == typeof e ? e : r.InputTransformer.byHeader(e, null == t ? void 0 : t.headers),
                cache: "no-cache",
                method: "POST"
            }, o = await this.send(Object.assign(Object.assign({}, s), t));
            return new n.AjaxResponse(o)
        }

        async put(e, t = {}) {
            const s = {
                body: "string" == typeof e ? e : r.InputTransformer.byHeader(e, null == t ? void 0 : t.headers),
                cache: "no-cache",
                method: "PUT"
            }, o = await this.send(Object.assign(Object.assign({}, s), t));
            return new n.AjaxResponse(o)
        }

        async delete(e = {}, t = {}) {
            const s = {cache: "no-cache", method: "DELETE"};
            "object" == typeof e && Object.keys(e).length > 0 ? s.body = r.InputTransformer.byHeader(e, null == t ? void 0 : t.headers) : "string" == typeof e && e.length > 0 && (s.body = e);
            const o = await this.send(Object.assign(Object.assign({}, s), t));
            return new n.AjaxResponse(o)
        }

        abort() {
            this.abortController.abort()
        }

        clone() {
            return Object.assign(Object.create(this), this)
        }

        async send(e = {}) {
            const t = await fetch(this.composeRequestUrl(), this.getMergedOptions(e));
            if (!t.ok) throw new n.AjaxResponse(t);
            return t
        }

        composeRequestUrl() {
            let e = this.url;
            if ("?" === e.charAt(0) && (e = window.location.origin + window.location.pathname + e), e = new URL(e, window.location.origin).toString(), "" !== this.queryArguments) {
                e += (this.url.includes("?") ? "&" : "?") + this.queryArguments
            }
            return e
        }

        getMergedOptions(e) {
            return Object.assign(Object.assign(Object.assign({}, o.defaultOptions), e), {signal: this.abortController.signal})
        }
    }

    return o.defaultOptions = {credentials: "same-origin"}, o
}));