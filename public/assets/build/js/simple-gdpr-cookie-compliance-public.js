"use strict";var cookieName="s_gdpr_c_c_cookie",bgOverlayEle=document.getElementById("s-gdpr-c-c-bg-overlay"),setCookie=function(e,t){var o=2<arguments.length&&void 0!==arguments[2]?arguments[2]:0,n=new Date,i=new Date,c="",c=0<o?(i.setTime(n.getTime()+24*o*60*60*1e3),e+"="+t+"; expires="+i.toUTCString()):e+"="+t;"1"===simpleGDPRCCJsObj.isMultisite&&"1"!==simpleGDPRCCJsObj.subdomainInstall?c+="; path="+simpleGDPRCCJsObj.path:c+="; path=/",document.cookie=c},getCookie=function(e){for(var t=decodeURIComponent(document.cookie).split(";"),o=0;o<t.length;o++){for(var n=t[o];" "===n.charAt(0);)n=n.substring(1);if(0===n.indexOf("".concat(e,"=")))return n.substring(e.length+1,n.length)}return""},closeNotice=function(){var e=document.getElementById("close-sgcc");e&&e.addEventListener("click",function(e){e.preventDefault(),document.querySelector(".sgcc-main-wrapper").classList.add("hidden"),bgOverlayEle&&(bgOverlayEle.style.display="none")})},acceptCookie=function(){var t=parseInt(simpleGDPRCCJsObj.cookieExpireTime),e=document.getElementById("sgcc-accept");e&&e.addEventListener("click",function(e){e.preventDefault(),setCookie(cookieName,"on",t);e=document.querySelector(".sgcc-main-wrapper");e&&e.classList.add("hidden"),bgOverlayEle&&(bgOverlayEle.style.display="none")})},showNotice=function(){var e=document.querySelector(".sgcc-main-wrapper"),t="on"===getCookie(cookieName);!1===navigator.cookieEnabled?(e&&e.classList.add("hidden"),bgOverlayEle&&(bgOverlayEle.style.display="none")):null===t||""===t?console.log("Simple GDPR Cookie Consent: Cookie is not set!!!"):e.classList.toggle("hidden",t)};document.addEventListener("DOMContentLoaded",function(){closeNotice(),acceptCookie(),showNotice()});