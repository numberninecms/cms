"use strict";(self.webpackChunkcms=self.webpackChunkcms||[]).push([[578],{6578:(e,t,n)=>{n.r(t),n.d(t,{default:()=>d});n(4812),n(8304),n(489),n(1539),n(1299),n(2419),n(1703),n(6647),n(8011),n(9070),n(6649),n(6078),n(2526),n(1817),n(9653),n(2165),n(6992),n(8783),n(3948);var o=n(6599),r=n(5358),i=n(1520),c=n(6455),u=n.n(c),f=n(1386);function l(e){return l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},l(e)}function a(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function s(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,(r=o.key,i=void 0,i=function(e,t){if("object"!==l(e)||null===e)return e;var n=e[Symbol.toPrimitive];if(void 0!==n){var o=n.call(e,t||"default");if("object"!==l(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(r,"string"),"symbol"===l(i)?i:String(i)),o)}var r,i}function p(e,t){return p=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,t){return e.__proto__=t,e},p(e,t)}function y(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,o=m(e);if(t){var r=m(this).constructor;n=Reflect.construct(o,arguments,r)}else n=o.apply(this,arguments);return b(this,n)}}function b(e,t){if(t&&("object"===l(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function m(e){return m=Object.setPrototypeOf?Object.getPrototypeOf.bind():function(e){return e.__proto__||Object.getPrototypeOf(e)},m(e)}var d=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),Object.defineProperty(e,"prototype",{writable:!1}),t&&p(e,t)}(l,e);var t,n,o,c=y(l);function l(){return a(this,l),c.apply(this,arguments)}return t=l,(n=[{key:"connect",value:function(){r.Y.on(i.mK,this.displaySaveSuccessFlash.bind(this)),r.Y.on(i.IU,this.deleteComponent.bind(this))}},{key:"displaySaveSuccessFlash",value:function(){r.Y.emit(i.nY,{label:"success",message:"Page successfully saved!"})}},{key:"deleteComponent",value:function(e){u().fire({title:"Are you sure?",icon:"warning",heightAuto:!1,showCancelButton:!0,confirmButtonColor:"var(--color-primary)",cancelButtonColor:"#d33",confirmButtonText:"Yes, delete it!"}).then((function(t){if(t.isConfirmed){var n=(0,f.Z)(),o=n.findComponentInTree,c=n.removeComponentInTree;o(e.componentToDelete.id,e.tree)&&(c(e.tree,e.componentToDelete.id),r.Y.emit(i.O0,{tree:e.tree,deletedComponent:e.componentToDelete}))}}))}}])&&s(t.prototype,n),o&&s(t,o),Object.defineProperty(t,"prototype",{writable:!1}),l}(o.Qr)}}]);