import "../css/app.css";
import htmx from "htmx.org";
import Swiper from "swiper";
import {
    Autoplay,
    EffectFade,
    EffectCoverflow,
    EffectCards,
    EffectCreative,
} from "swiper/modules";
import ApexCharts from "apexcharts";
import Alpine from "alpinejs";
import collapse from "@alpinejs/collapse";
import { createHighlighterCore } from "shiki/core";
import { createJavaScriptRegexEngine } from "shiki/engine/javascript";
import Quill from 'quill';



window.Quill = Quill;
window.htmx = htmx;
window.Alpine = Alpine;
window.ApexCharts = ApexCharts;
window.Swiper = Swiper;


Swiper.use([
    Autoplay,
    EffectFade,
    EffectCoverflow,
    EffectCards,
    EffectCreative,
]);

/**
- CODE COLORATION 
 */
let highlighter = null;

window.highlight = async (code, lang = "blade") => {
    if (!highlighter) {
        highlighter = await createHighlighterCore({
            themes: [import("@shikijs/themes/github-dark")],
            langs: [import("@shikijs/langs/blade")],
            engine: createJavaScriptRegexEngine(),
        });
    }

    return highlighter.codeToHtml(code, { lang, theme: "github-dark" });
};

Alpine.plugin(collapse);
Alpine.start();