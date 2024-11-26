(async function() {
    "use strict";

    var libcryptoutil= Object.create(null);
    var bi = window.BigInt;

    libcryptoutil.powmod = function(a, b, n) {
        a = a % n;
        var r = bi("1");
        var x = a;
        while(b > 0) {
            var lsb = b % bi("2");
            b = b / bi("2");
            if(lsb == bi("1")) {
                r = r * x;
                r = r % n;
            }
            x = x * x;
            x = x % n;
        }
        return r;
    };

    libcryptoutil.str_to_long = function(s) {
        var b = (new window.TextEncoder()).encode(s);
        var r = bi("0");

        for(var i = 0; i < b.length; i++) {
            r = (r << bi("8")) | bi(b[i].toString());
        }

        return r;
    };

    libcryptoutil.long_to_str=function(l) {
        if(typeof l === "string") {
            l = bi(l);
        }

        if(typeof l === "number") {
            l = bi(l);
        }

        if(typeof l !== "bigint") {
            console.error("libcryptoutil.long_to_str: input must be a String, Number, or BigInt");
            return false;
        }
        var b = [];
        while(l > 0) {
            b.unshift(Number(l & bi("255")));
            l >>= bi("8");
        }

        try {
            return (new window.TextDecoder("utf-8", {"fatal":true})).decode(new window.Uint8Array(b));
        } catch(e) {
            window.console.error(e);
            return false;
        }
    };

    window.libcryptoutil=libcryptoutil;

})();