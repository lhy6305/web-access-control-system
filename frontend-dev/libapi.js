(async function() {
    "use strict";

    var api=Object.create(null);

    api.temp_vars=Object.create(null);

    api.client_fingerprint=null;

    api.libui=null;
    api.FingerprintJS=null;
    api.CryptoJS=null;

    api.libcryptoutil_init=async function() {
        await new window.Promise(function(resolve, reject) {
            var interval=window.setInterval(function() {
                try {
                    if(window.libcryptoutil) {
                        window.clearInterval(interval);
                        interval=null;
                        resolve();
                    }
                } catch {}
            }, 100);
        });
        api.libcryptoutil=window.libcryptoutil;
        delete window.libcryptoutil;

        delete api.libcryptoutil_init;
        window.console.log("libcryptoutil_init() done");
    };

    api.libutil_init=async function() {
        await new window.Promise(function(resolve, reject) {
            var interval=window.setInterval(function() {
                try {
                    if(window.libutil) {
                        window.clearInterval(interval);
                        interval=null;
                        resolve();
                    }
                } catch {}
            }, 100);
        });
        api.libutil=window.libutil;
        delete window.libutil;

        delete api.libutil_init;
        window.console.log("libutil_init() done");
    };

    api.libui_init=async function() {
        await new window.Promise(function(resolve, reject) {
            var interval=window.setInterval(function() {
                try {
                    if(window.libui) {
                        window.clearInterval(interval);
                        interval=null;
                        resolve();
                    }
                } catch {}
            }, 100);
        });
        api.libui=window.libui;
        delete window.libui;

        delete api.libui_init;
        window.console.log("libui_init() done");
    };

    api.cryptojs_init=async function() {
        await new window.Promise(function(resolve, reject) {
            var interval=window.setInterval(function() {
                try {
                    if(window.CryptoJS) {
                        window.clearInterval(interval);
                        interval=null;
                        resolve();
                    }
                } catch {}
            }, 100);
        });
        api.CryptoJS=window.CryptoJS;
        delete window.CryptoJS;

        delete api.cryptojs_init;
        window.console.log("cryptojs_init() done");
    };

    api.fingerprintjs_init=async function() {
        await new window.Promise(function(resolve, reject) {
            var interval=window.setInterval(function() {
                try {
                    if(window.FingerprintJS) {
                        window.clearInterval(interval);
                        interval=null;
                        resolve();
                    }
                } catch {}
            }, 100);
        });

        api.FingerprintJS=window.FingerprintJS;
        delete window.FingerprintJS;
        api.temp_vars.fac=api.FingerprintJS.loadSources(api.FingerprintJS.sources, {cache:{}, debug:false}, []);
        api.client_fingerprint=await api.temp_vars.fac();
        delete api.temp_vars.fac;

        api.temp_vars.fp_long_time_stable_key_list=["architecture", "colorDepth", "colorGamut", "cpuClass", "deviceMemory", "hardwareConcurrency", "osCpu", "platform"];
        api.temp_vars.fp_short_time_stable_key_list=["applePay", "audio", "audioBaseLatency", "canvas.winding", "canvas.geometry", "hdr", "indexedDB", "invertedColors", "languages", "localStorage", "math", "monochrome", "openDatabase", "screenResolution", "sessionStorage", "timezone", "touchSupport", "vendor", "vendorFlavors", "webGlBasics", "webGlExtensions"];
        api.temp_vars.fp_unstable_key_list=["canvas.text", "fontPreferences", "fonts", "forcedColors", "pdfViewerEnabled", "plugins", "privateClickMeasurement", "reducedMotion", "reducedTransparency", "screenFrame"];

        api.temp_vars.fp_list_maker=function(key_list) {
            var fp_list= Object.create(null);
            key_list.sort(function(a, b) {
                return a.localeCompare(b);
            });
            for(api.temp_vars.a=0; api.temp_vars.a<key_list.length; api.temp_vars.a++) {
                api.temp_vars.b=key_list[api.temp_vars.a].split(".");
                api.temp_vars.d=api.client_fingerprint;
                api.temp_vars.e=true;
                for(api.temp_vars.c=0; api.temp_vars.c<api.temp_vars.b.length; api.temp_vars.c++) {
                    api.temp_vars.d=api.temp_vars.d[api.temp_vars.b[api.temp_vars.c]];
                    if(api.temp_vars.c==0) {
                        if(typeof api.temp_vars.d.value == "undefined") {
                            api.temp_vars.e=false;
                            break;
                        }
                        api.temp_vars.d=api.temp_vars.d.value;
                    }
                    if(typeof api.temp_vars.d == "undefined") {
                        window.window.console.error("api.temp_vars.d ("+key_list[api.temp_vars.a]+") is undefined");
                        api.temp_vars.e=false;
                        break;
                    }
                    if(Number.isInteger(api.temp_vars.d.value)&&api.temp_vars.d.value<0) {
                        api.temp_vars.e=false;
                        break;
                    }
                }
                if(!api.temp_vars.e) {
                    key_list[api.temp_vars.a]=null;
                    continue;
                }
                fp_list[key_list[api.temp_vars.a]]= Object.create(null);
                fp_list[key_list[api.temp_vars.a]].value=api.temp_vars.d;
                continue;
            }
            delete api.temp_vars.a;
            delete api.temp_vars.b;
            delete api.temp_vars.c;
            delete api.temp_vars.d;
            delete api.temp_vars.e;
            key_list=key_list.filter(function(a) {
                return (typeof a === "string" || a instanceof String);
            });
            key_list.sort(function(a, b) {
                return a.localeCompare(b);
            });
            return [key_list, fp_list];
        };

        [api.temp_vars.fp_long_time_stable_key_list, api.temp_vars.fp_long_time_stable_list]=api.temp_vars.fp_list_maker(api.temp_vars.fp_long_time_stable_key_list);
        [api.temp_vars.fp_short_time_stable_key_list, api.temp_vars.fp_short_time_stable_list]=api.temp_vars.fp_list_maker(api.temp_vars.fp_short_time_stable_key_list);
        [api.temp_vars.fp_unstable_key_list, api.temp_vars.fp_unstable_list]=api.temp_vars.fp_list_maker(api.temp_vars.fp_unstable_key_list);

        delete api.temp_vars.fp_list_maker;

        api.client_fingerprint.vhash_lt_cl=api.temp_vars.fp_long_time_stable_key_list;
        api.client_fingerprint.vhash_st_cl=api.temp_vars.fp_short_time_stable_key_list;
        api.client_fingerprint.vhash_us_cl=api.temp_vars.fp_unstable_key_list;

        delete api.temp_vars.fp_long_time_stable_key_list;
        delete api.temp_vars.fp_short_time_stable_key_list;
        delete api.temp_vars.fp_unstable_key_list;

        api.client_fingerprint.vhash_all=api.FingerprintJS.hashComponents(api.client_fingerprint);
        api.client_fingerprint.vhash_lt=api.FingerprintJS.hashComponents(api.temp_vars.fp_long_time_stable_list);
        api.client_fingerprint.vhash_st=api.FingerprintJS.hashComponents(api.temp_vars.fp_short_time_stable_list);
        api.client_fingerprint.vhash_us=api.FingerprintJS.hashComponents(api.temp_vars.fp_unstable_list);

        delete api.temp_vars.fp_long_time_stable_list;
        delete api.temp_vars.fp_short_time_stable_list;
        delete api.temp_vars.fp_unstable_list;

        //document.write("vhash_lt: "+api.client_fingerprint.vhash_lt+"<br>\n<br>\n");
        //document.write("vhash_st: "+api.client_fingerprint.vhash_st+"<br>\n<br>\n");
        //document.write("vhash_us: "+api.client_fingerprint.vhash_us+"<br>\n<br>\n");
        //document.write("vhash_all: "+api.client_fingerprint.vhash_all+"<br>\n<br>\n");

        delete api.fingerprintjs_init;
        window.console.log("fingerprintjs_init() done");
    };

    api.on_load=async function(event) {
        await api.fingerprintjs_init();
        await api.cryptojs_init();
        await api.libutil_init();
        await api.libcryptoutil_init();
        await api.libui_init();

        api.libui.clear_document_content();

        window.removeEventListener("load", api.on_load);
        delete api.on_load;
        window.console.log("on_load() done");
    };

    window.addEventListener("load", api.on_load);

    window.libapi=api;

})();
