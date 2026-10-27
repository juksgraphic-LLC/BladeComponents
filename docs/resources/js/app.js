import "../css/app.css";
import htmx from "htmx.org";
import Swiper from "swiper";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import { createHighlighterCore } from "shiki/core";
import { createJavaScriptRegexEngine } from "shiki/engine/javascript";
import {
    Autoplay,
    EffectFade,
    EffectCoverflow,
    EffectCards,
    EffectCreative,
} from "swiper/modules";

window.htmx   = htmx;
window.Alpine = Alpine;
window.Swiper = Swiper;

Swiper.use([
    Autoplay,
    EffectFade,
    EffectCoverflow,
    EffectCards,
    EffectCreative,
]);

/**
 * CODE COLORATION
 */
let highlighter;
const highlight = async (code, lang = "blade") => {

    highlighter ??= await createHighlighterCore({
        themes:  [import("@shikijs/themes/github-dark")],
        langs :  [import("@shikijs/langs/blade")],
        engine:  createJavaScriptRegexEngine(),
    });

    return highlighter.codeToHtml(code, { lang, theme: "github-dark" });
};


/**
 * CODEBLOCK + COPY CODE
 */
Alpine.data("codeBlock", (lang = "blade") => ({
    html: "",
    src: "",
    copied: false,

    init() {
        this.src = this.$el.querySelector("[data-src]").textContent;
        highlight(this.src, lang)
            .then((html) => (this.html = html))
            .catch(() => {});
    },

    async copy() {
        await navigator.clipboard.writeText(this.src);
        this.copied = true;
        setTimeout(() => (this.copied = false), 1800);
    },
}));

Alpine.plugin(collapse);
Alpine.start();
