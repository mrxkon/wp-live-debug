!function(e){var t={};function n(r){if(t[r])return t[r].exports;var c=t[r]={i:r,l:!1,exports:{}};return e[r].call(c.exports,c,c.exports,n),c.l=!0,c.exports}n.m=e,n.c=t,n.d=function(e,t,r){n.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},n.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},n.t=function(e,t){if(1&t&&(e=n(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(n.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var c in e)n.d(r,c,function(t){return e[t]}.bind(null,c));return r},n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,"a",t),t},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},n.p="",n(n.s=2)}([function(e,t){!function(){e.exports=this.wp.element}()},function(e,t){!function(){e.exports=this.wp.i18n}()},function(e,t,n){"use strict";n.r(t);var r=n(0),c=n(1),a=function(){return Object(r.createElement)("div",{className:"header",role:"region","aria-label":Object(c.__)("WP Live Debug Top Bar","wp-live-debug"),tabIndex:"-1"},Object(r.createElement)("h1",{className:"header-title"},Object(c.__)("WP Live Debug","wp-live-debug")))},l=function(){return Object(r.createElement)("textarea",{className:"logviewer"},"aefaeafaef aefae aef ae")},i=function(){return Object(r.createElement)("div",{className:"content",role:"region","aria-label":Object(c.__)("Log view","wp-live-debug"),tabIndex:"-1"},Object(r.createElement)(l,null))},o=function(){return Object(r.createElement)("div",{className:"sidebar",role:"region","aria-label":Object(c.__)("WP Live Debug Settings","wp-live-debug"),tabIndex:"-1"},Object(r.createElement)("div",{className:"section"},Object(r.createElement)("div",{className:"section-header"},Object(r.createElement)("h2",null,Object(c.__)("Settings","wp-live-debug"))),Object(r.createElement)("div",{className:"section-content"},"test")),Object(r.createElement)("div",{className:"section"},Object(r.createElement)("div",{className:"section-header"},Object(r.createElement)("h2",null,Object(c.__)("Information","wp-live-debug"))),Object(r.createElement)("div",{className:"section-content"},"test")))},u=function(){return Object(r.createElement)(r.Fragment,null,Object(r.createElement)(a,null),Object(r.createElement)(i,null),Object(r.createElement)(o,null))};Object(r.render)(Object(r.createElement)(u,null),document.getElementById("wpld-page"))}]);