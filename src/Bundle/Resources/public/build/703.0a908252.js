"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[703],{703:(t,e,r)=>{r.r(e),r.d(e,{default:()=>c});r(3210),r(2222),r(8304),r(489),r(2419),r(8011),r(9070),r(2526),r(1817),r(1539),r(2165),r(6992),r(8783),r(3948);function n(t){return n="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},n(t)}function o(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function a(t,e){for(var r=0;r<e.length;r++){var n=e[r];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function i(t,e){return i=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t},i(t,e)}function u(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}();return function(){var r,n=l(t);if(e){var o=l(this).constructor;r=Reflect.construct(n,arguments,o)}else r=n.apply(this,arguments);return s(this,r)}}function s(t,e){if(e&&("object"===n(e)||"function"==typeof e))return e;if(void 0!==e)throw new TypeError("Derived constructors may only return object or undefined");return function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t)}function l(t){return l=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)},l(t)}var c=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&i(t,e)}(l,t);var e,r,n,s=u(l);function l(){return o(this,l),s.apply(this,arguments)}return e=l,(r=[{key:"updateUsername",value:function(){this.getOptionByValue("username").text=this.usernameTarget.value}},{key:"updateFirstName",value:function(){var t=this.firstNameTarget.value.trim().length>0?this.firstNameTarget.value:"First name",e=this.lastNameTarget.value.trim().length>0?this.lastNameTarget.value:"Last name";this.getOptionByValue("first_only").text=t,this.getOptionByValue("first_last").text="".concat(t," ").concat(e),this.getOptionByValue("last_first").text="".concat(e," ").concat(t)}},{key:"updateLastName",value:function(){var t=this.firstNameTarget.value.trim().length>0?this.firstNameTarget.value:"First name",e=this.lastNameTarget.value.trim().length>0?this.lastNameTarget.value:"Last name";this.getOptionByValue("last_only").text=e,this.getOptionByValue("first_last").text="".concat(t," ").concat(e),this.getOptionByValue("last_first").text="".concat(e," ").concat(t)}},{key:"getOptionByValue",value:function(t){for(var e=0;e<this.displayNameFormatTarget.options.length;e++)if(this.displayNameFormatTarget.options[e].getAttribute("value")===t)return this.displayNameFormatTarget.options[e];return null}}])&&a(e.prototype,r),n&&a(e,n),l}(r(7931).Controller);c.targets=["username","firstName","lastName","displayNameFormat"]}}]);