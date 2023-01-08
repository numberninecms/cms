/*! For license information please see 569.5f32cc32.js.LICENSE.txt */
(self.webpackChunkcms=self.webpackChunkcms||[]).push([[569],{2569:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>me});n(8304),n(4812),n(489),n(6992),n(1539),n(3948),n(1299),n(2419),n(1703),n(6647),n(8011),n(9070),n(6649),n(6078),n(2526),n(1817),n(9653),n(2165),n(8783);var o=n(6599),r=n(5166),i=["src"];n(9554),n(4747),n(1038),n(8674),n(8862),n(6699),n(2023),n(2443),n(9341),n(3706),n(2703),n(7658),n(8309),n(5069),n(7042);var a=n(8965);var l=n(7764),u=n(5358),c=n(1520),s=n(1386);const d=(0,r.defineComponent)({name:"PageBuilderComponent",props:{component:{type:Object,required:!0}},setup:function(e){var t=(0,a.b)(),n=(0,s.Z)().preloadComponent,o=(0,r.computed)((function(){return"".concat((0,l.Ho)(e.component.name),"PageBuilderComponent")})),i=(0,r.ref)(null),d=(0,r.reactive)({down:!1,move:!1});function f(n){n.stopPropagation(),t.highlightedId=e.component.id}function p(n){n.preventDefault(),n.stopPropagation(),t.selectedId=e.component.id,u.Y.emit(c.wp,{component:t.selectedComponent})}function v(e){e.preventDefault(),e.stopPropagation(),u.Y.emit(c.LK,{component:t.selectedComponent})}function m(e){e.stopPropagation(),d.down=!0,t.isContextMenuVisible=!1}function h(){d.move=!!d.down}function g(){d.down=!1,d.move=!1}(0,r.onMounted)((function(){n(e.component),i.value.$el.addEventListener("mouseover",f),i.value.$el.addEventListener("mousedown",m),i.value.$el.addEventListener("mouseup",g),i.value.$el.addEventListener("mousemove",h),i.value.$el.addEventListener("click",p),i.value.$el.addEventListener("dblclick",v)})),(0,r.onBeforeUnmount)((function(){i.value.$el.removeEventListener("mouseover",f),i.value.$el.removeEventListener("mousedown",m),i.value.$el.removeEventListener("mouseup",g),i.value.$el.removeEventListener("mousemove",h),i.value.$el.removeEventListener("click",p),i.value.$el.removeEventListener("dblclick",v)}));var y=(0,r.computed)((function(){return d.down&&d.move}));return(0,r.watch)(y,(function(){return t.dragId=y.value?e.component.id:void 0})),{elementRef:i,componentName:o,viewSize:"lg",isVisible:!0}}});var f=n(3744);const p=(0,f.Z)(d,[["render",function(e,t,n,o,i,a){return e.isVisible?((0,r.openBlock)(),(0,r.createBlock)((0,r.resolveDynamicComponent)(e.componentName),{key:0,ref:"elementRef","self-instance":e.component,parameters:e.component.parameters,responsive:e.component.responsive,computed:e.component.computed,"view-size":e.viewSize,children:e.component.children,"data-component-id":e.component.id,class:"n9-page-builder-component"},null,8,["self-instance","parameters","responsive","computed","view-size","children","data-component-id"])):(0,r.createCommentVNode)("",!0)}]]);var v=n(830),m=(0,v.Q_)({id:"mouse",state:function(){return{x:0,y:0,over:!1}},actions:{isWithinBoundingBox:function(e){return this.x>=e.left&&this.x<=e.right&&this.y>=e.top&&this.y<=e.bottom}}}),h={id:"n9-page-builder-toolbox"};var g={class:"n9-wrapper"};n(2222);var y=n(8721);function b(){var e=(0,r.ref)((0,y.Z)());return{uuid:e,generate:function(){(0,r.nextTick)((function(){e.value=(0,y.Z)()}))}}}const E=(0,r.defineComponent)({name:"PageBuilderToolOutline",setup:function(){var e=m(),t=(0,a.b)(),n=b(),o=n.generate,i=n.uuid;return(0,r.onMounted)((function(){u.Y.on(c.OG,o),u.Y.on(c.mv,o)})),{styles:function(){var e={};if(!t.highlightedId)return e;var n=t.document.querySelector("[data-component-id='".concat(t.highlightedId,"']")).getBoundingClientRect(),o=t.document.documentElement.scrollLeft,r=t.document.documentElement.scrollTop;return e.width="".concat(n.right-n.left,"px"),e.height="".concat(n.bottom-n.top,"px"),e.transform="translateX(".concat(n.left+o,"px) translateY(").concat(n.top+r,"px)"),e.id=i.value,delete e.id,e},active:(0,r.computed)((function(){return t.highlightedId&&e.over&&t.highlightedId!==t.selectedId})),label:(0,r.computed)((function(){return t.highlightedComponentLabel}))}}}),w=(0,f.Z)(E,[["render",function(e,t,n,o,i,a){return e.active?((0,r.openBlock)(),(0,r.createElementBlock)("div",{key:0,id:"n9-page-builder-tool-outline",style:(0,r.normalizeStyle)(e.styles())},[(0,r.createElementVNode)("div",g,[(0,r.createElementVNode)("h3",null,(0,r.toDisplayString)(e.label),1)])],4)):(0,r.createCommentVNode)("",!0)}]]);var x={key:0,class:"n9-wrapper"},C={key:0,ref:"ancestorsRef",class:"n9-ancestors"},B=(0,r.createElementVNode)("i",{class:"fas fa-chevron-down n9-text-white"},null,-1),k={key:0,ref:"ancestorsListRef"},L={ref:"optionsRef",class:"n9-options n9-text-white"},S=[(0,r.createElementVNode)("i",{class:"fas fa-cog"},null,-1)];n(1532);var P=n(8624);const N=(0,r.defineComponent)({name:"PageBuilderToolSelect",setup:function(){var e=(0,a.b)(),t=(0,r.ref)(!1),n=(0,r.ref)(null),o=(0,r.ref)(null),i=(0,r.ref)([]),l=(0,r.ref)(null),s=(0,r.ref)(null),d=(0,r.computed)((function(){return e.getComponentAncestors(e.selectedComponent).reverse()})),f=new Map,p=b(),v=p.generate,m=p.uuid;function h(){var e,t,r,a,u;null===(e=n.value)||void 0===e||e.removeEventListener("mouseover",g),null===(t=n.value)||void 0===t||t.removeEventListener("mouseleave",y),null===(r=o.value)||void 0===r||r.removeEventListener("mouseleave",y),null===(a=l.value)||void 0===a||a.removeEventListener("mousedown",w),null===(u=s.value)||void 0===u||u.removeEventListener("click",x),f.forEach((function(e,t){e.forEach((function(e,n){var o;null===(o=i.value[t])||void 0===o||o.removeEventListener(n,e)}))})),f.clear()}function g(){t.value=!0}function y(){t.value=!1}(0,r.onMounted)((function(){u.Y.on(c.OG,v),u.Y.on(c.mv,v)})),(0,r.onBeforeUpdate)((function(){h(),i.value=[]})),(0,r.onUpdated)((function(){var t,r,a,u,c;null===(t=n.value)||void 0===t||t.addEventListener("mouseover",g),null===(r=n.value)||void 0===r||r.addEventListener("mouseleave",y),null===(a=o.value)||void 0===a||a.addEventListener("mouseleave",y),null===(u=l.value)||void 0===u||u.addEventListener("mousedown",w),null===(c=s.value)||void 0===c||c.addEventListener("click",x),i.value.forEach((function(t,n){if(t){var o=function(t){var o;t.stopPropagation(),o=d.value[n].id,e.selectedId=o,y()},r=function(t){var o;t.stopPropagation(),o=d.value[n].id,e.highlightedId=o},i=function(e){return e.stopPropagation()},a=new Map;a.set("click",o),a.set("mouseover",r),a.set("mousemove",i),f.set(n,a),t.addEventListener("click",o),t.addEventListener("mouseover",r),t.addEventListener("mousemove",i)}}))})),(0,r.onBeforeUnmount)((function(){h()}));var E=(0,r.computed)((function(){var t={};if(!e.selectedId)return t;var n=e.document.querySelector("[data-component-id='".concat(e.selectedId,"']")).getBoundingClientRect(),o=e.document.documentElement.scrollLeft,r=e.document.documentElement.scrollTop;return t.width="".concat(n.right-n.left,"px"),t.height="".concat(n.bottom-n.top,"px"),t.transform="translateX(".concat(n.left+o,"px) translateY(").concat(n.top+r,"px)"),t.id=m.value,delete t.id,t}));function w(){e.dragId=e.selectedId}function x(){e.isContextMenuVisible=!e.isContextMenuVisible}return{ancestorsRef:n,ancestorsListRef:o,ancestorsButtonRefs:i,componentLabelRef:l,optionsRef:s,styles:E,active:(0,r.computed)((function(){return!!e.selectedId})),label:(0,r.computed)((function(){return e.selectedComponentLabel})),ancestors:d,isBeingDragged:(0,r.computed)((function(){return void 0!==e.dragId})),areAncestorsVisible:t,drag:w,toggleContextMenu:e.toggleContextMenu,capitalCase:P.I}}}),V=(0,f.Z)(N,[["render",function(e,t,n,o,i,a){return e.active?((0,r.openBlock)(),(0,r.createElementBlock)("div",{key:0,id:"n9-page-builder-tool-select",style:(0,r.normalizeStyle)(e.styles)},[e.isBeingDragged?(0,r.createCommentVNode)("",!0):((0,r.openBlock)(),(0,r.createElementBlock)("div",x,[e.ancestors.length>0?((0,r.openBlock)(),(0,r.createElementBlock)("div",C,[B,e.areAncestorsVisible?((0,r.openBlock)(),(0,r.createElementBlock)("ul",k,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(e.ancestors,(function(t,n){return(0,r.openBlock)(),(0,r.createElementBlock)("li",{key:"ancestor-"+t.id},[(0,r.createElementVNode)("button",{ref_for:!0,ref:function(t){e.ancestorsButtonRefs[n]=t},type:"button"},(0,r.toDisplayString)(e.capitalCase(t.name)),513)])})),128))],512)):(0,r.createCommentVNode)("",!0)],512)):(0,r.createCommentVNode)("",!0),(0,r.createElementVNode)("h3",{ref:"componentLabelRef"},(0,r.toDisplayString)(e.label),513),(0,r.createElementVNode)("button",L,S,512)]))],4)):(0,r.createCommentVNode)("",!0)}]]);const O=(0,r.defineComponent)({name:"PageBuilderToolDragHandle",setup:function(){var e=m(),t=(0,a.b)(),n=(0,r.computed)((function(){return t.draggedComponent}));return{styles:(0,r.computed)((function(){return{transform:"translate3d(".concat(e.x,"px, ").concat(e.y,"px, 0px)")}})),active:(0,r.computed)((function(){return void 0!==t.dragId})),label:(0,r.computed)((function(){var e,t;return null!==(t=null===(e=n.value)||void 0===e?void 0:e.label)&&void 0!==t?t:""}))}}}),T=(0,f.Z)(O,[["render",function(e,t,n,o,i,a){return(0,r.withDirectives)(((0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-tool-drag-handle",style:(0,r.normalizeStyle)(e.styles)},[(0,r.createElementVNode)("h3",null,[(0,r.createElementVNode)("span",null,(0,r.toDisplayString)(e.label),1)])],4)),[[r.vShow,e.active]])}]]);const I=(0,r.defineComponent)({name:"PageBuilderToolInsertLine",setup:function(){var e=m(),t=(0,a.b)(),n=(0,r.reactive)({x:0,y:0,width:0,height:0}),o=(0,r.computed)((function(){var e={},n=t.highlightedComponentElement;if(!n)return e;var o=n.getBoundingClientRect(),r=t.document.documentElement.scrollLeft,i=t.document.documentElement.scrollTop;return e.width="".concat(o.right-o.left,"px"),e.height="".concat(o.bottom-o.top,"px"),e.transform="translateX(".concat(o.left+r,"px) translateY(").concat(o.top+i,"px)"),e})),i=(0,r.computed)((function(){return Math.min(n.width/4,100)})),l=(0,r.computed)((function(){return Math.min(n.height/4,100)})),u=(0,r.computed)((function(){var o=void 0;return t.draggedComponent&&!c.value||(Math.abs(n.x-e.x)<i.value&&s("left")&&(o="left"),Math.abs(n.x+n.width-e.x)<i.value&&s("right")&&(o="right"),Math.abs(n.y-e.y)<l.value&&s("top")&&(o="top"),Math.abs(n.y+n.height-e.y)<l.value&&s("bottom")&&(o="bottom")),o})),c=(0,r.computed)((function(){return t.dragId!==t.highlightedId&&(!(!t.draggedComponent||!t.highlightedComponent)&&(0===t.highlightedComponent.siblingsShortcodes.length?0===t.draggedComponent.siblingsShortcodes.length||t.draggedComponent.siblingsShortcodes.includes(t.highlightedComponent.name):t.highlightedComponent.siblingsShortcodes.includes(t.draggedComponent.name)))}));function s(e){return!!t.highlightedComponent&&t.highlightedComponent.siblingsPosition.includes(e)}return(0,r.watch)(u,(function(){return t.dropPosition=u.value})),(0,r.watch)((function(){return t.$state}),(function(){var e=t.highlightedComponentElement;if(e){var o=e.getBoundingClientRect();n.width=o.right-o.left,n.height=o.bottom-o.top,n.x=o.left,n.y=o.top}}),{deep:!0}),{styles:o,position:u,active:(0,r.computed)((function(){return e.over&&!!u.value}))}}}),R=(0,f.Z)(I,[["render",function(e,t,n,o,i,a){return(0,r.withDirectives)(((0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-tool-insert-line",style:(0,r.normalizeStyle)(e.styles)},[(0,r.createElementVNode)("div",{class:(0,r.normalizeClass)(["n9-line",e.position?"n9-"+e.position:null])},null,2)],4)),[[r.vShow,e.active]])}]]);var Y=[(0,r.createElementVNode)("i",{class:"fas fa-plus fa-xs"},null,-1)];const j=(0,r.defineComponent)({name:"PageBuilderToolInsertButton",setup:function(){var e=m(),t=(0,a.b)(),n=(0,r.ref)(null),o=(0,r.ref)(null),i=(0,r.computed)((function(){return e.over&&!!t.dropPosition}));(0,r.onMounted)((function(){var e;null===(e=o.value)||void 0===e||e.addEventListener("click",d)})),(0,r.onBeforeUnmount)((function(){var e;null===(e=o.value)||void 0===e||e.removeEventListener("click",d)}));var l=(0,r.computed)((function(){var e={},n=t.highlightedComponentElement;if(!n)return e;var o=n.getBoundingClientRect(),r=t.document.documentElement.scrollLeft,i=t.document.documentElement.scrollTop;return e.width="".concat(o.right-o.left,"px"),e.height="".concat(o.bottom-o.top,"px"),e.transform="translateX(".concat(o.left+r,"px) translateY(").concat(o.top+i,"px)"),e})),s=(0,r.computed)((function(){var n={},o=t.highlightedComponentElement;if(!o)return n;var r=o.getBoundingClientRect();return n.left="".concat(e.x-r.left,"px"),n}));function d(){u.Y.emit(c.Yn,{tree:t.pageComponents,target:t.highlightedComponent,position:t.dropPosition})}return(0,r.watch)((function(){return e.$state}),(function(){var n,o=null===(n=t.highlightedComponentElement)||void 0===n?void 0:n.getBoundingClientRect();i.value&&o&&!e.isWithinBoundingBox(o)&&(t.highlightedId=void 0)}),{deep:!0}),{wrapperRef:n,buttonRef:o,styles:l,buttonStyles:s,dropPosition:(0,r.computed)((function(){return t.dropPosition})),active:i}}}),z=(0,f.Z)(j,[["render",function(e,t,n,o,i,a){return(0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-tool-insert-button",ref:"wrapperRef",style:(0,r.normalizeStyle)(e.styles)},[(0,r.withDirectives)((0,r.createElementVNode)("button",{ref:"buttonRef",class:(0,r.normalizeClass)(e.dropPosition?"n9-"+e.dropPosition:null),style:(0,r.normalizeStyle)(e.buttonStyles)},Y,6),[[r.vShow,e.active]])],4)}]]);var M={class:"n9-menu-buttons-list"},D={ref:"editRef"},_=(0,r.createElementVNode)("i",{class:"far fa-edit"},null,-1),A={ref:"duplicateRef"},U=(0,r.createElementVNode)("i",{class:"far fa-clone"},null,-1),Z={ref:"savePresetRef"},$=(0,r.createElementVNode)("i",{class:"far fa-save"},null,-1),F={ref:"showShortcodeRef"},G=(0,r.createElementVNode)("i",{class:"far fa-eye"},null,-1),J={ref:"deleteRef",type:"button"},H=(0,r.createElementVNode)("i",{class:"far fa-trash-alt"},null,-1);const W=(0,r.defineComponent)({name:"PageBuilderToolContextMenu",setup:function(){var e=(0,a.b)(),t=(0,r.ref)(null),n=(0,r.ref)(null),o=(0,r.ref)(null),i=(0,r.ref)(null),l=(0,r.ref)(null);function s(){m(),u.Y.emit(c.LK,{component:e.selectedComponent})}function d(){m(),e.duplicateComponent(e.selectedId)}function f(){m(),e.selectedComponent&&u.Y.emit(c.qJ,{component:e.selectedComponent})}function p(){m(),e.selectedComponent&&u.Y.emit(c.sp,{component:e.selectedComponent})}function v(){m(),e.selectedComponent&&u.Y.emit(c.IU,{tree:e.pageComponents,componentToDelete:e.selectedComponent})}function m(){e.isContextMenuVisible=!1}return(0,r.onMounted)((function(){var e,r,a,u,c;null===(e=t.value)||void 0===e||e.addEventListener("click",s),null===(r=n.value)||void 0===r||r.addEventListener("click",d),null===(a=o.value)||void 0===a||a.addEventListener("click",f),null===(u=i.value)||void 0===u||u.addEventListener("click",p),null===(c=l.value)||void 0===c||c.addEventListener("click",v)})),(0,r.onBeforeUnmount)((function(){var e,r,a,u,c;null===(e=t.value)||void 0===e||e.removeEventListener("click",s),null===(r=n.value)||void 0===r||r.removeEventListener("click",d),null===(a=o.value)||void 0===a||a.removeEventListener("click",f),null===(u=i.value)||void 0===u||u.removeEventListener("click",p),null===(c=l.value)||void 0===c||c.removeEventListener("click",v)})),(0,r.watch)((function(){return e.selectedId}),(function(){m()})),{editRef:t,duplicateRef:n,savePresetRef:o,showShortcodeRef:i,deleteRef:l,styles:function(){var t={},n=e.document.querySelector("#n9-page-builder-tool-select .n9-options");if(n){var o=n.getBoundingClientRect(),r=e.document.documentElement.scrollLeft,i=e.document.documentElement.scrollTop;t.transform="translateX(".concat(o.left+r,"px) translateY(").concat(o.top+i+20,"px)")}return t},isContextMenuVisible:(0,r.computed)((function(){return e.isContextMenuVisible}))}}}),q=(0,f.Z)(W,[["render",function(e,t,n,o,i,a){return(0,r.withDirectives)(((0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-tool-context-menu",style:(0,r.normalizeStyle)(e.styles())},[(0,r.createElementVNode)("div",M,[(0,r.createElementVNode)("button",D,[_,(0,r.createTextVNode)(" Edit")],512),(0,r.createElementVNode)("button",A,[U,(0,r.createTextVNode)(" Duplicate")],512),(0,r.createElementVNode)("button",Z,[$,(0,r.createTextVNode)(" Save as preset")],512),(0,r.createElementVNode)("button",F,[G,(0,r.createTextVNode)(" Show shortcode")],512),(0,r.createElementVNode)("button",J,[H,(0,r.createTextVNode)(" Delete")],512)])],4)),[[r.vShow,e.isContextMenuVisible]])}]]),X=(0,r.defineComponent)({name:"PageBuilderToolbox",components:{PageBuilderToolContextMenu:q,PageBuilderToolInsertButton:z,PageBuilderToolInsertLine:R,PageBuilderToolDragHandle:T,PageBuilderToolOutline:w,PageBuilderToolSelect:V}}),K=(0,f.Z)(X,[["render",function(e,t,n,o,i,a){var l=(0,r.resolveComponent)("PageBuilderToolOutline"),u=(0,r.resolveComponent)("PageBuilderToolSelect"),c=(0,r.resolveComponent)("PageBuilderToolDragHandle"),s=(0,r.resolveComponent)("PageBuilderToolInsertLine"),d=(0,r.resolveComponent)("PageBuilderToolInsertButton"),f=(0,r.resolveComponent)("PageBuilderToolContextMenu");return(0,r.openBlock)(),(0,r.createElementBlock)("div",h,[(0,r.createVNode)(l),(0,r.createVNode)(u),(0,r.createVNode)(c),(0,r.createVNode)(s),(0,r.createVNode)(d),(0,r.createVNode)(f)])}]]),Q=(0,r.defineComponent)({name:"PageBuilder",components:{PageBuilderToolbox:K,PageBuilderComponent:p},setup:function(){var e=(0,s.Z)().replaceComponentInTree,t=(0,r.ref)(null),n=m(),o=(0,a.b)(),i=(0,r.ref)(!1);function l(){i.value=!0}function d(){i.value=!1}return(0,r.onMounted)((function(){u.Y.on(c.xx,(function(e){n.x=e.x,n.y=e.y})),u.Y.on(c.O0,(function(e){e.deletedComponent.id===o.selectedId&&(o.selectedId=void 0)})),u.Y.on(c.OG,(function(t){o.pageComponents=e(o.pageComponents,t.component)})),u.Y.on(c.Yv,(function(e){return o.viewportSize=e})),u.Y.on(c.n2,(function(e){var t;o.selectedId=null===(t=e.component)||void 0===t?void 0:t.id})),u.Y.on(c.p7,(function(e){var t;o.highlightedId=null===(t=e.component)||void 0===t?void 0:t.id,n.over=!!e.component})),u.Y.on(c.ni,(function(e){var t;o.pageComponents=null!==(t=null==e?void 0:e.tree)&&void 0!==t?t:[],u.Y.emit(c.Zc,{tree:JSON.parse(JSON.stringify(o.pageComponents))})})),u.Y.on(c.cA,(function(){o.saveComponents(),u.Y.emit(c.mK,{tree:JSON.parse(JSON.stringify(o.pageComponents))})})),o.document.addEventListener("mousedown",(function(){n.over||(o.selectedId=void 0)})),o.document.addEventListener("mouseup",(function(){o.dragId=void 0})),t.value.addEventListener("mouseenter",l),t.value.addEventListener("mouseleave",d)})),(0,r.onBeforeUnmount)((function(){t.value.removeEventListener("mouseenter",l),t.value.removeEventListener("mouseleave",d)})),(0,r.watch)(i,(function(){n.over=i.value})),{builder:t,components:(0,r.computed)((function(){return o.pageComponents})),isDragging:(0,r.computed)((function(){return void 0!==o.dragId}))}}}),ee=(0,f.Z)(Q,[["render",function(e,t,n,o,i,a){var l=(0,r.resolveComponent)("PageBuilderComponent"),u=(0,r.resolveComponent)("PageBuilderToolbox");return(0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-wrapper",ref:"builder",class:(0,r.normalizeClass)({dragging:e.isDragging})},[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(e.components,(function(e){return(0,r.openBlock)(),(0,r.createBlock)(l,{key:e.id,component:e},null,8,["component"])})),128)),(0,r.createVNode)(u)],2)}]]);function te(e){return te="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},te(e)}function ne(){ne=function(){return e};var e={},t=Object.prototype,n=t.hasOwnProperty,o=Object.defineProperty||function(e,t,n){e[t]=n.value},r="function"==typeof Symbol?Symbol:{},i=r.iterator||"@@iterator",a=r.asyncIterator||"@@asyncIterator",l=r.toStringTag||"@@toStringTag";function u(e,t,n){return Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}),e[t]}try{u({},"")}catch(e){u=function(e,t,n){return e[t]=n}}function c(e,t,n,r){var i=t&&t.prototype instanceof f?t:f,a=Object.create(i.prototype),l=new k(r||[]);return o(a,"_invoke",{value:w(e,n,l)}),a}function s(e,t,n){try{return{type:"normal",arg:e.call(t,n)}}catch(e){return{type:"throw",arg:e}}}e.wrap=c;var d={};function f(){}function p(){}function v(){}var m={};u(m,i,(function(){return this}));var h=Object.getPrototypeOf,g=h&&h(h(L([])));g&&g!==t&&n.call(g,i)&&(m=g);var y=v.prototype=f.prototype=Object.create(m);function b(e){["next","throw","return"].forEach((function(t){u(e,t,(function(e){return this._invoke(t,e)}))}))}function E(e,t){function r(o,i,a,l){var u=s(e[o],e,i);if("throw"!==u.type){var c=u.arg,d=c.value;return d&&"object"==te(d)&&n.call(d,"__await")?t.resolve(d.__await).then((function(e){r("next",e,a,l)}),(function(e){r("throw",e,a,l)})):t.resolve(d).then((function(e){c.value=e,a(c)}),(function(e){return r("throw",e,a,l)}))}l(u.arg)}var i;o(this,"_invoke",{value:function(e,n){function o(){return new t((function(t,o){r(e,n,t,o)}))}return i=i?i.then(o,o):o()}})}function w(e,t,n){var o="suspendedStart";return function(r,i){if("executing"===o)throw new Error("Generator is already running");if("completed"===o){if("throw"===r)throw i;return S()}for(n.method=r,n.arg=i;;){var a=n.delegate;if(a){var l=x(a,n);if(l){if(l===d)continue;return l}}if("next"===n.method)n.sent=n._sent=n.arg;else if("throw"===n.method){if("suspendedStart"===o)throw o="completed",n.arg;n.dispatchException(n.arg)}else"return"===n.method&&n.abrupt("return",n.arg);o="executing";var u=s(e,t,n);if("normal"===u.type){if(o=n.done?"completed":"suspendedYield",u.arg===d)continue;return{value:u.arg,done:n.done}}"throw"===u.type&&(o="completed",n.method="throw",n.arg=u.arg)}}}function x(e,t){var n=t.method,o=e.iterator[n];if(void 0===o)return t.delegate=null,"throw"===n&&e.iterator.return&&(t.method="return",t.arg=void 0,x(e,t),"throw"===t.method)||"return"!==n&&(t.method="throw",t.arg=new TypeError("The iterator does not provide a '"+n+"' method")),d;var r=s(o,e.iterator,t.arg);if("throw"===r.type)return t.method="throw",t.arg=r.arg,t.delegate=null,d;var i=r.arg;return i?i.done?(t[e.resultName]=i.value,t.next=e.nextLoc,"return"!==t.method&&(t.method="next",t.arg=void 0),t.delegate=null,d):i:(t.method="throw",t.arg=new TypeError("iterator result is not an object"),t.delegate=null,d)}function C(e){var t={tryLoc:e[0]};1 in e&&(t.catchLoc=e[1]),2 in e&&(t.finallyLoc=e[2],t.afterLoc=e[3]),this.tryEntries.push(t)}function B(e){var t=e.completion||{};t.type="normal",delete t.arg,e.completion=t}function k(e){this.tryEntries=[{tryLoc:"root"}],e.forEach(C,this),this.reset(!0)}function L(e){if(e){var t=e[i];if(t)return t.call(e);if("function"==typeof e.next)return e;if(!isNaN(e.length)){var o=-1,r=function t(){for(;++o<e.length;)if(n.call(e,o))return t.value=e[o],t.done=!1,t;return t.value=void 0,t.done=!0,t};return r.next=r}}return{next:S}}function S(){return{value:void 0,done:!0}}return p.prototype=v,o(y,"constructor",{value:v,configurable:!0}),o(v,"constructor",{value:p,configurable:!0}),p.displayName=u(v,l,"GeneratorFunction"),e.isGeneratorFunction=function(e){var t="function"==typeof e&&e.constructor;return!!t&&(t===p||"GeneratorFunction"===(t.displayName||t.name))},e.mark=function(e){return Object.setPrototypeOf?Object.setPrototypeOf(e,v):(e.__proto__=v,u(e,l,"GeneratorFunction")),e.prototype=Object.create(y),e},e.awrap=function(e){return{__await:e}},b(E.prototype),u(E.prototype,a,(function(){return this})),e.AsyncIterator=E,e.async=function(t,n,o,r,i){void 0===i&&(i=Promise);var a=new E(c(t,n,o,r),i);return e.isGeneratorFunction(n)?a:a.next().then((function(e){return e.done?e.value:a.next()}))},b(y),u(y,l,"Generator"),u(y,i,(function(){return this})),u(y,"toString",(function(){return"[object Generator]"})),e.keys=function(e){var t=Object(e),n=[];for(var o in t)n.push(o);return n.reverse(),function e(){for(;n.length;){var o=n.pop();if(o in t)return e.value=o,e.done=!1,e}return e.done=!0,e}},e.values=L,k.prototype={constructor:k,reset:function(e){if(this.prev=0,this.next=0,this.sent=this._sent=void 0,this.done=!1,this.delegate=null,this.method="next",this.arg=void 0,this.tryEntries.forEach(B),!e)for(var t in this)"t"===t.charAt(0)&&n.call(this,t)&&!isNaN(+t.slice(1))&&(this[t]=void 0)},stop:function(){this.done=!0;var e=this.tryEntries[0].completion;if("throw"===e.type)throw e.arg;return this.rval},dispatchException:function(e){if(this.done)throw e;var t=this;function o(n,o){return a.type="throw",a.arg=e,t.next=n,o&&(t.method="next",t.arg=void 0),!!o}for(var r=this.tryEntries.length-1;r>=0;--r){var i=this.tryEntries[r],a=i.completion;if("root"===i.tryLoc)return o("end");if(i.tryLoc<=this.prev){var l=n.call(i,"catchLoc"),u=n.call(i,"finallyLoc");if(l&&u){if(this.prev<i.catchLoc)return o(i.catchLoc,!0);if(this.prev<i.finallyLoc)return o(i.finallyLoc)}else if(l){if(this.prev<i.catchLoc)return o(i.catchLoc,!0)}else{if(!u)throw new Error("try statement without catch or finally");if(this.prev<i.finallyLoc)return o(i.finallyLoc)}}}},abrupt:function(e,t){for(var o=this.tryEntries.length-1;o>=0;--o){var r=this.tryEntries[o];if(r.tryLoc<=this.prev&&n.call(r,"finallyLoc")&&this.prev<r.finallyLoc){var i=r;break}}i&&("break"===e||"continue"===e)&&i.tryLoc<=t&&t<=i.finallyLoc&&(i=null);var a=i?i.completion:{};return a.type=e,a.arg=t,i?(this.method="next",this.next=i.finallyLoc,d):this.complete(a)},complete:function(e,t){if("throw"===e.type)throw e.arg;return"break"===e.type||"continue"===e.type?this.next=e.arg:"return"===e.type?(this.rval=this.arg=e.arg,this.method="return",this.next="end"):"normal"===e.type&&t&&(this.next=t),d},finish:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.finallyLoc===e)return this.complete(n.completion,n.afterLoc),B(n),d}},catch:function(e){for(var t=this.tryEntries.length-1;t>=0;--t){var n=this.tryEntries[t];if(n.tryLoc===e){var o=n.completion;if("throw"===o.type){var r=o.arg;B(n)}return r}}throw new Error("illegal catch attempt")},delegateYield:function(e,t,n){return this.delegate={iterator:L(e),resultName:t,nextLoc:n},"next"===this.method&&(this.arg=void 0),d}},e}function oe(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,(r=o.key,i=void 0,i=function(e,t){if("object"!==te(e)||null===e)return e;var n=e[Symbol.toPrimitive];if(void 0!==n){var o=n.call(e,t||"default");if("object"!==te(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(r,"string"),"symbol"===te(i)?i:String(i)),o)}var r,i}var re=function(e,t,n,o){return new(n||(n=Promise))((function(r,i){function a(e){try{u(o.next(e))}catch(e){i(e)}}function l(e){try{u(o.throw(e))}catch(e){i(e)}}function u(e){var t;e.done?r(e.value):(t=e.value,t instanceof n?t:new n((function(e){e(t)}))).then(a,l)}u((o=o.apply(e,t||[])).next())}))},ie=function(){function e(t,n){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.createApp(t),this.registerBuiltInComponents(),this.setupStoreAndFetchData(n)}var t,n,o;return t=e,(n=[{key:"createApp",value:function(e){var t=document.createElement("div");e.parentNode.insertBefore(t,e);var n=(0,r.compile)(e.outerHTML);this.app=(0,r.createApp)({components:{PageBuilder:ee},render:n}),this.app.use((0,v.WB)()),this.app.mount(t),e.parentNode.removeChild(e)}},{key:"registerBuiltInComponents",value:function(){this.app.component("PageBuilderStyle",{render:function(){return(0,r.h)("style",{},this.$slots.default())}})}},{key:"setupStoreAndFetchData",value:function(e){return re(this,void 0,void 0,ne().mark((function t(){var n,o;return ne().wrap((function(t){for(;;)switch(t.prev=t.next){case 0:return(n=(0,a.b)()).setup({app:this,componentsApiUrl:e}),t.next=4,n.fetchComponents();case 4:o=JSON.parse(JSON.stringify(n.pageComponents)),u.Y.emit(c.Zc,{tree:o}),u.Y.emit(c.h4,{tree:o,availableComponents:JSON.parse(JSON.stringify(n.availablePageComponents)),forms:JSON.parse(JSON.stringify(n.pageComponentForms))});case 7:case"end":return t.stop()}}),t,this)})))}},{key:"compileComponent",value:function(e,t){var n=(0,l.Ho)(e)+"PageBuilderComponent";this.app.component(n,{components:{PageBuilderComponent:p,PageBuilderStyle:this.app.component("PageBuilderStyle")},props:["parameters","responsive","computed","children","selfInstance","viewSize"],setup:function(e){function t(t){return e.responsive.includes(t)}return{isResponsive:t,getResponsiveValue:function(n){return t(n)?Object.prototype.hasOwnProperty.call(e.parameters[n],e.viewSize)?e.parameters[n][e.viewSize]:"":e.parameters[n]}}},template:t})}}])&&oe(t.prototype,n),o&&oe(t,o),Object.defineProperty(t,"prototype",{writable:!1}),e}();const ae=(0,r.defineComponent)({name:"PageBuilderFrame",props:{frontendUrl:{type:String,required:!0},componentsApiUrl:{type:String,required:!0},disableLinks:{type:Boolean,required:!1,default:!1}},setup:function(e){var t=(0,r.ref)(null),n=(0,r.ref)(0),o=(0,r.ref)("100%");(0,r.onMounted)((function(){u.Y.on(c.Yv,(function(e){switch(e){case"md":o.value="768px";break;case"xs":o.value="425px";break;default:o.value="100%"}}))}));function i(e){u.Y.emit(c.xx,{x:e.clientX,y:e.clientY})}return{iframe:t,width:o,height:(0,r.computed)((function(){return"".concat(n.value-48,"px")})),onLoad:function(){var o=t.value.contentDocument.body.getElementsByTagName("page-builder");if(0===o.length)throw new Error("Page without <page-builder> tag. Aborting.");var r,a=new ie(o[0],e.componentsApiUrl);u.Y.emit(c.GB,{app:a}),e.disableLinks&&(r=t.value.contentDocument.body.getElementsByTagName("a"),Array.from(r).forEach((function(e){e.addEventListener("click",(function(e){return e.preventDefault()}))}))),t.value.contentWindow.addEventListener("mousemove",i),u.Y.on(c.kf,(function(e){n.value=e,t.value.height="".concat(e-48)})),u.Y.emit(c.DE)}}}}),le=(0,f.Z)(ae,[["render",function(e,t,n,o,a,l){return(0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"page-builder-content-frame",class:"w-full min-h-full flex",style:(0,r.normalizeStyle)({height:e.height,width:e.width})},[(0,r.createElementVNode)("iframe",{ref:"iframe",src:e.frontendUrl,class:"flex-1",onLoad:t[0]||(t[0]=function(){return e.onLoad&&e.onLoad.apply(e,arguments)})},null,40,i)],4)}]]);function ue(e){return ue="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},ue(e)}function ce(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function se(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,(r=o.key,i=void 0,i=function(e,t){if("object"!==ue(e)||null===e)return e;var n=e[Symbol.toPrimitive];if(void 0!==n){var o=n.call(e,t||"default");if("object"!==ue(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===t?String:Number)(e)}(r,"string"),"symbol"===ue(i)?i:String(i)),o)}var r,i}function de(e,t){return de=Object.setPrototypeOf?Object.setPrototypeOf.bind():function(e,t){return e.__proto__=t,e},de(e,t)}function fe(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,o=ve(e);if(t){var r=ve(this).constructor;n=Reflect.construct(o,arguments,r)}else n=o.apply(this,arguments);return pe(this,n)}}function pe(e,t){if(t&&("object"===ue(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function ve(e){return ve=Object.setPrototypeOf?Object.getPrototypeOf.bind():function(e){return e.__proto__||Object.getPrototypeOf(e)},ve(e)}var me=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),Object.defineProperty(e,"prototype",{writable:!1}),t&&de(e,t)}(a,e);var t,n,o,i=fe(a);function a(){return ce(this,a),i.apply(this,arguments)}return t=a,(n=[{key:"connect",value:function(){var e=this;window.addEventListener("resize",(function(){return e.emitWindowHeight()})),this.emitWindowHeight(),(0,r.createApp)(le,{frontendUrl:this.frontendUrlValue,componentsApiUrl:this.componentsApiUrlValue,disableLinks:!0}).use((0,v.WB)()).mount(this.element)}},{key:"emitWindowHeight",value:function(){u.Y.emit(c.kf,document.documentElement.clientHeight)}}])&&se(t.prototype,n),o&&se(t,o),Object.defineProperty(t,"prototype",{writable:!1}),a}(o.Qr);me.values={frontendUrl:String,componentsApiUrl:String}},5631:(e,t,n)=>{"use strict";var o=n(3070).f,r=n(30),i=n(9190),a=n(9974),l=n(5787),u=n(8554),c=n(408),s=n(1656),d=n(6178),f=n(6340),p=n(9781),v=n(2423).fastKey,m=n(9909),h=m.set,g=m.getterFor;e.exports={getConstructor:function(e,t,n,s){var d=e((function(e,o){l(e,f),h(e,{type:t,index:r(null),first:void 0,last:void 0,size:0}),p||(e.size=0),u(o)||c(o,e[s],{that:e,AS_ENTRIES:n})})),f=d.prototype,m=g(t),y=function(e,t,n){var o,r,i=m(e),a=b(e,t);return a?a.value=n:(i.last=a={index:r=v(t,!0),key:t,value:n,previous:o=i.last,next:void 0,removed:!1},i.first||(i.first=a),o&&(o.next=a),p?i.size++:e.size++,"F"!==r&&(i.index[r]=a)),e},b=function(e,t){var n,o=m(e),r=v(t);if("F"!==r)return o.index[r];for(n=o.first;n;n=n.next)if(n.key==t)return n};return i(f,{clear:function(){for(var e=m(this),t=e.index,n=e.first;n;)n.removed=!0,n.previous&&(n.previous=n.previous.next=void 0),delete t[n.index],n=n.next;e.first=e.last=void 0,p?e.size=0:this.size=0},delete:function(e){var t=this,n=m(t),o=b(t,e);if(o){var r=o.next,i=o.previous;delete n.index[o.index],o.removed=!0,i&&(i.next=r),r&&(r.previous=i),n.first==o&&(n.first=r),n.last==o&&(n.last=i),p?n.size--:t.size--}return!!o},forEach:function(e){for(var t,n=m(this),o=a(e,arguments.length>1?arguments[1]:void 0);t=t?t.next:n.first;)for(o(t.value,t.key,this);t&&t.removed;)t=t.previous},has:function(e){return!!b(this,e)}}),i(f,n?{get:function(e){var t=b(this,e);return t&&t.value},set:function(e,t){return y(this,0===e?0:e,t)}}:{add:function(e){return y(this,e=0===e?0:e,e)}}),p&&o(f,"size",{get:function(){return m(this).size}}),d},setStrong:function(e,t,n){var o=t+" Iterator",r=g(t),i=g(o);s(e,t,(function(e,t){h(this,{type:o,target:e,state:r(e),kind:t,last:void 0})}),(function(){for(var e=i(this),t=e.kind,n=e.last;n&&n.removed;)n=n.previous;return e.target&&(e.last=n=n?n.next:e.state.first)?d("keys"==t?n.key:"values"==t?n.value:[n.key,n.value],!1):(e.target=void 0,d(void 0,!0))}),n?"entries":"values",!n,!0),f(t)}}},7710:(e,t,n)=>{"use strict";var o=n(2109),r=n(7854),i=n(1702),a=n(4705),l=n(8052),u=n(2423),c=n(408),s=n(5787),d=n(614),f=n(8554),p=n(111),v=n(7293),m=n(7072),h=n(8003),g=n(9587);e.exports=function(e,t,n){var y=-1!==e.indexOf("Map"),b=-1!==e.indexOf("Weak"),E=y?"set":"add",w=r[e],x=w&&w.prototype,C=w,B={},k=function(e){var t=i(x[e]);l(x,e,"add"==e?function(e){return t(this,0===e?0:e),this}:"delete"==e?function(e){return!(b&&!p(e))&&t(this,0===e?0:e)}:"get"==e?function(e){return b&&!p(e)?void 0:t(this,0===e?0:e)}:"has"==e?function(e){return!(b&&!p(e))&&t(this,0===e?0:e)}:function(e,n){return t(this,0===e?0:e,n),this})};if(a(e,!d(w)||!(b||x.forEach&&!v((function(){(new w).entries().next()})))))C=n.getConstructor(t,e,y,E),u.enable();else if(a(e,!0)){var L=new C,S=L[E](b?{}:-0,1)!=L,P=v((function(){L.has(1)})),N=m((function(e){new w(e)})),V=!b&&v((function(){for(var e=new w,t=5;t--;)e[E](t,t);return!e.has(-0)}));N||((C=t((function(e,t){s(e,x);var n=g(new w,e,C);return f(t)||c(t,n[E],{that:n,AS_ENTRIES:y}),n}))).prototype=x,x.constructor=C),(P||V)&&(k("delete"),k("has"),y&&k("get")),(V||S)&&k(E),b&&x.clear&&delete x.clear}return B[e]=C,o({global:!0,constructor:!0,forced:C!=w},B),h(C,e),b||n.setStrong(C,e,y),C}},9098:(e,t,n)=>{"use strict";n(7710)("Map",(function(e){return function(){return e(this,arguments.length?arguments[0]:void 0)}}),n(5631))},1532:(e,t,n)=>{n(9098)}}]);