(async function() {
    "use strict";

    await new window.Promise(function(resolve, reject) {
        var interval=window.setInterval(function() {
            try {
                if(window.libapi) {
                    window.clearInterval(interval);
                    interval=null;
                    resolve();
                }
            } catch {}
        }, 100);
    });

    var libui= Object.create(null);

    libui.clear_document_content=function() {
        if(libapi.libutil.is_dom_element(window.document.documentElement)) {
            while(window.document.documentElement.firstChild) {
                window.document.documentElement.firstChild.remove();
            }
            window.document.documentElement.remove();
            return;
        }
    };

    libui.display_page_input=function(title, footer, tip, button_text, placeholder_list) {

        if(arguments.length<5) {
            window.console.error("libui.display_page_input: arguments.length < 5");
            return;
        }

        libui.clear_document_content();

        if(!Array.isArray(placeholder_list)) {
            placeholder_list=[placeholder_list];
        }

        libapi.temp_vars.a=window.document.createElement("html");
        window.document.append(libapi.temp_vars.a);
        libapi.temp_vars.b=window.document.createElement("head");
        libapi.temp_vars.a.append(libapi.temp_vars.b);
        libapi.temp_vars.c=window.document.createElement("body");
        libapi.temp_vars.a.append(libapi.temp_vars.c);

        libapi.temp_vars.d=window.document.createElement("meta");
        libapi.temp_vars.d.setAttribute("charset", "utf-8");
        libapi.temp_vars.b.append(libapi.temp_vars.d);

        libapi.temp_vars.d=window.document.createElement("meta");
        libapi.temp_vars.d.setAttribute("name", "viewport");
        libapi.temp_vars.d.setAttribute("content", "width=device-width,initial-scale=1.0");
        libapi.temp_vars.b.append(libapi.temp_vars.d);

        libapi.temp_vars.d=window.document.createElement("meta");
        libapi.temp_vars.d.setAttribute("http-equiv", "X-UA-Compatible");
        libapi.temp_vars.d.setAttribute("content", "ie=edge");
        libapi.temp_vars.b.append(libapi.temp_vars.d);

        libapi.temp_vars.d=window.document.createElement("title");
        libapi.temp_vars.d.innerHTML=title;
        libapi.temp_vars.b.append(libapi.temp_vars.d);

        libapi.temp_vars.d=window.document.createElement("style");
        libapi.temp_vars.d.innerHTML="*{margin:0;padding:0;box-sizing:border-box;}body,html{width:100%;height:100%;display:flex;justify-content:center;align-items:center;font-family:Arial,sans-serif;background-color:#f7f7f7;overflow:hidden;}.login-container{width:100%;max-width:350px;background:#ffffff;padding:30px;border-radius:8px;box-shadow:0 4px 12px rgba(0,0,0,0.1);text-align:center;animation:fadeIn 0.5s ease-out;}h2{margin-bottom:20px;color:#333;font-size:22px;font-weight:normal;}.input-group{margin-bottom:20px;}input[type=\"text\"]{width:100%;padding:12px;font-size:16px;border:1px solid #ddd;border-radius:4px;outline:none;transition:border-color 0.3s;}input[type=\"text\"]:focus{border-color:#5c9aff;}button{width:100%;padding:14px;background-color:#5c9aff;color:white;font-size:16px;border:none;border-radius:4px;cursor:pointer;transition:background-color 0.3s;}button:hover{background-color:#4a8ee4;}@keyframes fadeIn{from{opacity:0;transform:translateY(-10px);}to{opacity:1;transform:translateY(0);}}@media (max-width:480px){.login-container{width:80%;padding:20px;}}footer{position:absolute;bottom:15px;width:100%;text-align:center;color:#999;}";
        libapi.temp_vars.b.append(libapi.temp_vars.d);

        libapi.temp_vars.d=window.document.createElement("div");
        libapi.temp_vars.d.setAttribute("class", "login-container");
        libapi.temp_vars.e=window.document.createElement("h2");
        libapi.temp_vars.e.innerHTML=tip;
        libapi.temp_vars.d.append(libapi.temp_vars.e);

        for(libapi.temp_vars.h=0; libapi.temp_vars.h<placeholder_list.length; libapi.temp_vars.h++) {
            libapi.temp_vars.f=window.document.createElement("div");
            libapi.temp_vars.f.setAttribute("class", "input-group");

            libapi.temp_vars.g=window.document.createElement("input");
            libapi.temp_vars.g.setAttribute("type", "text");
            libapi.temp_vars.g.setAttribute("placeholder", placeholder_list[libapi.temp_vars.h]);
            libapi.temp_vars.g.setAttribute("id", "auth-input-password"+libapi.temp_vars.h.toString());
            libapi.temp_vars.f.append(libapi.temp_vars.g);
            libapi.temp_vars.d.append(libapi.temp_vars.f);
        }
        delete libapi.temp_vars.h;

        libapi.temp_vars.e=window.document.createElement("button");
        libapi.temp_vars.e.setAttribute("id", "auth-button-submit");
        libapi.temp_vars.e.innerHTML=button_text;
        libapi.temp_vars.d.append(libapi.temp_vars.e);

        libapi.temp_vars.c.append(libapi.temp_vars.d);

        libapi.temp_vars.d=window.document.createElement("footer");
        libapi.temp_vars.d.innerHTML=footer;
        libapi.temp_vars.c.append(libapi.temp_vars.d);

        delete libapi.temp_vars.a;
        delete libapi.temp_vars.b;
        delete libapi.temp_vars.c;
        delete libapi.temp_vars.d;
        delete libapi.temp_vars.e;
        delete libapi.temp_vars.f;
        delete libapi.temp_vars.g;
    };

    libui.display_page_auth_usr=function() {
        libui.display_page_input("Authv1 - User Identifier System by ly65", "User Identifier System by ly65", "用户认证", "提交", ["请输入您的密码"]);
    };

    libui.display_page_auth_sys=function() {
        libui.display_page_input("Authv2 - User Identifier System by ly65", "User Identifier System by ly65", "系统认证", "提交", ["请输入管理员下发的注册码"]);
    };

    libui.display_page_user_change_password=function() {
        libui.display_page_input("chpwd - User Identifier System by ly65", "User Identifier System by ly65", "用户密码更改", "提交", ["新密码", "确认密码"]);
    };

    window.libui=libui;

})();
