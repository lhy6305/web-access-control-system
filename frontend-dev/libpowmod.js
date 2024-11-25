window.powmod = function(a, b, n) {
    a = a % n;
    var bi = window.BigInt;
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