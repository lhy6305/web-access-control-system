(async function() {
    "use strict";

    var libutil= Object.create(null);

    libutil.is_dom_node=function(o) {
        return (typeof Node === "object" ? o instanceof Node : o && typeof o === "object" && typeof o.nodeType === "number" && typeof o.nodeName === "string");
    };

    libutil.is_dom_element=function(o) {
        return (typeof HTMLElement === "object" ? o instanceof HTMLElement : o && typeof o === "object" && o !== null && o.nodeType === 1 && typeof o.nodeName === "string");
    };

    window.libutil=libutil;

})();