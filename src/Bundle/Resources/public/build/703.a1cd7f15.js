(self.webpackChunk=self.webpackChunk||[]).push([[703],{703:(t,e,n)=>{"use strict";n.r(e),n.d(e,{default:()=>c});n(3210),n(2222),n(8304),n(489),n(2419),n(8011),n(9070),n(2526),n(1817),n(1539),n(2165),n(6992),n(8783),n(3948);function r(t){return(r="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function a(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}function o(t,e){for(var n=0;n<e.length;n++){var r=e[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(t,r.key,r)}}function i(t,e){return(i=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function u(t){var e=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}();return function(){var n,r=l(t);if(e){var a=l(this).constructor;n=Reflect.construct(r,arguments,a)}else n=r.apply(this,arguments);return s(this,n)}}function s(t,e){return!e||"object"!==r(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function l(t){return(l=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}var c=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&i(t,e)}(l,t);var e,n,r,s=u(l);function l(){return a(this,l),s.apply(this,arguments)}return e=l,(n=[{key:"updateUsername",value:function(){this.getOptionByValue("username").text=this.usernameTarget.value}},{key:"updateFirstName",value:function(){var t=this.firstNameTarget.value.trim().length>0?this.firstNameTarget.value:"First name",e=this.lastNameTarget.value.trim().length>0?this.lastNameTarget.value:"Last name";this.getOptionByValue("first_only").text=t,this.getOptionByValue("first_last").text="".concat(t," ").concat(e),this.getOptionByValue("last_first").text="".concat(e," ").concat(t)}},{key:"updateLastName",value:function(){var t=this.firstNameTarget.value.trim().length>0?this.firstNameTarget.value:"First name",e=this.lastNameTarget.value.trim().length>0?this.lastNameTarget.value:"Last name";this.getOptionByValue("last_only").text=e,this.getOptionByValue("first_last").text="".concat(t," ").concat(e),this.getOptionByValue("last_first").text="".concat(e," ").concat(t)}},{key:"getOptionByValue",value:function(t){for(var e=0;e<this.displayNameFormatTarget.options.length;e++)if(this.displayNameFormatTarget.options[e].getAttribute("value")===t)return this.displayNameFormatTarget.options[e];return null}}])&&o(e.prototype,n),r&&o(e,r),l}(n(7931).Controller);c.targets=["username","firstName","lastName","displayNameFormat"]}}]);