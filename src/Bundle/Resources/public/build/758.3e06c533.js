"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[758],{9758:(e,t,n)=>{n.r(t),n.d(t,{default:()=>L});n(5666),n(4812),n(8304),n(489),n(6992),n(1539),n(3948),n(9653),n(2419),n(8011),n(9070),n(8674),n(2526),n(1817),n(2165),n(8783);var r=n(7931),o=n(5166),i={key:0,class:"flex flex-col"},u={class:"flex items-center gap-5 px-3 mt-12"},c=["disabled"],l={class:"mt-10"},a=(0,o.createTextVNode)(" Viewing revision "),s=(0,o.createTextVNode)(" from ");n(9826),n(3710),n(9714);var f=n(9454),v=n.n(f),d=n(4737),p=n(8116),y=n.n(p),m=n(9669),h=n.n(m),b={key:0},g={class:"font-semibold mt-3 mb-2"},V={key:0,class:"bg-green-500 text-white rounded text-xs px-1"},w=["innerHTML"];n(7042),n(4553),n(8309),n(1038),n(9753);function x(e,t){var n="undefined"!=typeof Symbol&&e[Symbol.iterator]||e["@@iterator"];if(!n){if(Array.isArray(e)||(n=function(e,t){if(!e)return;if("string"==typeof e)return k(e,t);var n=Object.prototype.toString.call(e).slice(8,-1);"Object"===n&&e.constructor&&(n=e.constructor.name);if("Map"===n||"Set"===n)return Array.from(e);if("Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n))return k(e,t)}(e))||t&&e&&"number"==typeof e.length){n&&(e=n);var r=0,o=function(){};return{s:o,n:function(){return r>=e.length?{done:!0}:{done:!1,value:e[r++]}},e:function(e){throw e},f:o}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,u=!0,c=!1;return{s:function(){n=n.call(e)},n:function(){var e=n.next();return u=e.done,e},e:function(e){c=!0,i=e},f:function(){try{u||null==n.return||n.return()}finally{if(c)throw i}}}}function k(e,t){(null==t||t>e.length)&&(t=e.length);for(var n=0,r=new Array(t);n<t;n++)r[n]=e[n];return r}const S=(0,o.defineComponent)({name:"ContentEntityHistoryFieldDiff",props:{title:{type:String,required:!0},field:{type:String,required:!0},version:{type:Number,required:!0},revisions:{type:Array,required:!0}},setup:function(e){function t(t,n){var r;if(n<=0)return"";var o=e.revisions.slice(e.revisions.findIndex((function(e){return e.version===n}))),i=null!==(r=o[0][t])&&void 0!==r?r:null;if(i)return"".concat(i,"\n");for(var u=1;u<o.length;u++)if(o[u][t])return"".concat(o[u][t],"\n");return""}return{previous:(0,o.computed)((function(){return t(e.field,e.version-1)})),current:(0,o.computed)((function(){return t(e.field,e.version)})),hasFieldValueSinceVersion:function(t,n){var r,o=x(e.revisions.slice(e.revisions.findIndex((function(e){return e.version===n}))));try{for(o.s();!(r=o.n()).done;){if(r.value[t])return!0}}catch(e){o.e(e)}finally{o.f()}return!1}}}});var C=n(3744);const R=(0,C.Z)(S,[["render",function(e,t,n,r,i,u){var c=(0,o.resolveComponent)("Diff");return e.hasFieldValueSinceVersion(e.field,e.version)?((0,o.openBlock)(),(0,o.createElementBlock)("div",b,[(0,o.createElementVNode)("p",g,[(0,o.createTextVNode)((0,o.toDisplayString)(e.title)+" ",1),e.previous===e.current?((0,o.openBlock)(),(0,o.createElementBlock)("span",V,"no change")):(0,o.createCommentVNode)("",!0)]),e.previous!==e.current?((0,o.openBlock)(),(0,o.createBlock)(c,{key:0,class:"border border-gray-300",mode:"unified",theme:"light",language:"plaintext",prev:e.previous,current:e.current},null,8,["prev","current"])):((0,o.openBlock)(),(0,o.createElementBlock)("div",{key:1,innerHTML:e.current},null,8,w))])):(0,o.createCommentVNode)("",!0)}]]);var T=n(7560),E=n(2853);function N(e,t,n,r,o,i,u){try{var c=e[i](u),l=c.value}catch(e){return void n(e)}c.done?t(l):Promise.resolve(l).then(r,o)}function _(e){return function(){var t=this,n=arguments;return new Promise((function(r,o){var i=e.apply(t,n);function u(e){N(i,r,o,u,c,"next",e)}function c(e){N(i,r,o,u,c,"throw",e)}u(void 0)}))}}const B=(0,o.defineComponent)({name:"ContentEntityHistory",components:{ContentEntityHistoryFieldDiff:R,VueSlider:v()},props:{entityId:{type:Number,required:!0},contentType:{type:String,required:!0}},setup:function(e){var t=(0,o.ref)(0),n=(0,o.ref)([]);(0,o.onMounted)(_(regeneratorRuntime.mark((function r(){var o;return regeneratorRuntime.wrap((function(r){for(;;)switch(r.prev=r.next){case 0:return r.next=2,h().get(d.Z.generate("numbernine_admin_contententity_revisions_get_collection",{type:e.contentType,id:e.entityId}));case 2:o=r.sent,n.value=o.data,t.value=n.value.length-1;case 5:case"end":return r.stop()}}),r)}))));var r=(0,o.computed)((function(){return t.value>n.value.length-1?0:n.value[n.value.length-t.value-1].version})),i=function(){var e,t=null===(e=n.value.find((function(e){return e.version===r.value})))||void 0===e?void 0:e.date;return t?y()(t,"mmmm d, yyyy HH:MM"):r.value.toString()},u=(0,o.computed)((function(){return i()}));return{currentRevisionStep:t,currentRevisionVersion:r,currentRevisionDate:u,dateFormatter:i,revisions:n,revert:function(){E.Y.emit(T.Mm,{contentType:e.contentType,entityId:e.entityId,version:r.value,date:u.value})}}}}),O=(0,C.Z)(B,[["render",function(e,t,n,r,f,v){var d=(0,o.resolveComponent)("vue-slider"),p=(0,o.resolveComponent)("ContentEntityHistoryFieldDiff");return e.revisions.length>0?((0,o.openBlock)(),(0,o.createElementBlock)("div",i,[(0,o.createElementVNode)("div",u,[(0,o.createVNode)(d,{modelValue:e.currentRevisionStep,"onUpdate:modelValue":t[0]||(t[0]=function(t){return e.currentRevisionStep=t}),class:"flex-grow",min:0,max:e.revisions.length-1,interval:1,adsorb:"",contained:"","drag-on-click":"",marks:"","hide-label":"","tooltip-formatter":e.dateFormatter},null,8,["modelValue","max","tooltip-formatter"]),(0,o.createElementVNode)("button",{type:"button",class:"btn btn-color-red",disabled:e.currentRevisionStep===e.revisions.length-1,onClick:t[1]||(t[1]=function(){return e.revert&&e.revert.apply(e,arguments)})}," Revert ",8,c)]),(0,o.createElementVNode)("div",l,[a,(0,o.createElementVNode)("strong",null,(0,o.toDisplayString)(e.currentRevisionVersion),1),s,(0,o.createElementVNode)("strong",null,(0,o.toDisplayString)(e.currentRevisionDate),1)]),(0,o.createVNode)(p,{title:"Title",field:"title",version:e.currentRevisionVersion,revisions:e.revisions},null,8,["version","revisions"]),(0,o.createVNode)(p,{title:"Slug",field:"slug",version:e.currentRevisionVersion,revisions:e.revisions},null,8,["version","revisions"]),(0,o.createVNode)(p,{title:"Excerpt",field:"excerpt",version:e.currentRevisionVersion,revisions:e.revisions},null,8,["version","revisions"]),(0,o.createVNode)(p,{title:"Content",field:"content",version:e.currentRevisionVersion,revisions:e.revisions},null,8,["version","revisions"])])):(0,o.createCommentVNode)("",!0)}]]);var j=n(4055),A=n(6455),I=n.n(A);function D(e){return D="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},D(e)}function P(e,t,n,r,o,i,u){try{var c=e[i](u),l=c.value}catch(e){return void n(e)}c.done?t(l):Promise.resolve(l).then(r,o)}function H(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function M(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function F(e,t){return F=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},F(e,t)}function q(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,r=Y(e);if(t){var o=Y(this).constructor;n=Reflect.construct(r,arguments,o)}else n=r.apply(this,arguments);return Z(this,n)}}function Z(e,t){if(t&&("object"===D(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function Y(e){return Y=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},Y(e)}var L=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&F(e,t)}(u,e);var t,n,r,i=q(u);function u(){return H(this,u),i.apply(this,arguments)}return t=u,n=[{key:"connect",value:function(){(0,o.createApp)(O,{entityId:this.idValue,contentType:this.contentTypeValue}).use(j.Z).mount(this.element),E.Y.on(T.Mm,this.revertToVersion.bind(this))}},{key:"revertToVersion",value:function(e){I().fire({title:"Are you sure?",text:"All revisions past ".concat(e.date," will be permanently deleted."),icon:"warning",heightAuto:!1,showCancelButton:!0,confirmButtonColor:"var(--color-primary)",cancelButtonColor:"#d33",confirmButtonText:"Yes, revert to this version!"}).then(function(){var t,n=(t=regeneratorRuntime.mark((function t(n){return regeneratorRuntime.wrap((function(t){for(;;)switch(t.prev=t.next){case 0:if(!n.isConfirmed){t.next=4;break}return t.next=3,h().post(d.Z.generate("numbernine_admin_contententity_revert_item",{type:e.contentType,id:e.entityId,version:e.version}));case 3:window.location.reload();case 4:case"end":return t.stop()}}),t)})),function(){var e=this,n=arguments;return new Promise((function(r,o){var i=t.apply(e,n);function u(e){P(i,r,o,u,c,"next",e)}function c(e){P(i,r,o,u,c,"throw",e)}u(void 0)}))});return function(e){return n.apply(this,arguments)}}())}}],n&&M(t.prototype,n),r&&M(t,r),u}(r.Controller);L.values={id:Number,contentType:String}}}]);