"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[897],{9897:(e,t,n)=>{n.r(t),n.d(t,{default:()=>fe});n(8304),n(489),n(6992),n(1539),n(3948),n(2419),n(8011),n(9070),n(2526),n(1817),n(2165),n(8783);var o=n(7931),r=n(5166),i=["src"];n(9554),n(4747),n(1038),n(6699),n(2023),n(8674),n(5666);var l=n(253);n(8309);var a=n(7764),u=n(2853),c=n(7560),s=n(6544);const d=(0,r.defineComponent)({name:"PageBuilderComponent",props:{component:{type:Object,required:!0}},setup:function(e){var t=(0,l.b)(),n=(0,s.Z)().preloadComponent,o=(0,r.computed)((function(){return"".concat((0,a.Ho)(e.component.name),"PageBuilderComponent")})),i=(0,r.ref)(null),d=(0,r.reactive)({down:!1,move:!1});function p(n){n.stopPropagation(),t.highlightedId=e.component.id}function f(n){n.preventDefault(),n.stopPropagation(),t.selectedId=e.component.id,u.Y.emit(c.wp,{component:t.selectedComponent})}function m(e){e.preventDefault(),e.stopPropagation(),u.Y.emit(c.LK,{component:t.selectedComponent})}function v(e){e.stopPropagation(),d.down=!0,t.isContextMenuVisible=!1}function g(){d.move=!!d.down}function h(){d.down=!1,d.move=!1}(0,r.onMounted)((function(){n(e.component),i.value.$el.addEventListener("mouseover",p),i.value.$el.addEventListener("mousedown",v),i.value.$el.addEventListener("mouseup",h),i.value.$el.addEventListener("mousemove",g),i.value.$el.addEventListener("click",f),i.value.$el.addEventListener("dblclick",m)})),(0,r.onBeforeUnmount)((function(){i.value.$el.removeEventListener("mouseover",p),i.value.$el.removeEventListener("mousedown",v),i.value.$el.removeEventListener("mouseup",h),i.value.$el.removeEventListener("mousemove",g),i.value.$el.removeEventListener("click",f),i.value.$el.removeEventListener("dblclick",m)}));var y=(0,r.computed)((function(){return d.down&&d.move}));return(0,r.watch)(y,(function(){return t.dragId=y.value?e.component.id:void 0})),{elementRef:i,componentName:o,viewSize:"lg",isVisible:!0}}});var p=n(3744);const f=(0,p.Z)(d,[["render",function(e,t,n,o,i,l){return e.isVisible?((0,r.openBlock)(),(0,r.createBlock)((0,r.resolveDynamicComponent)(e.componentName),{key:0,ref:"elementRef","self-instance":e.component,parameters:e.component.parameters,responsive:e.component.responsive,computed:e.component.computed,"view-size":e.viewSize,children:e.component.children,"data-component-id":e.component.id,class:"n9-page-builder-component"},null,8,["self-instance","parameters","responsive","computed","view-size","children","data-component-id"])):(0,r.createCommentVNode)("",!0)}]]);var m=n(8875),v=(0,m.Q_)({id:"mouse",state:function(){return{x:0,y:0,over:!1}},actions:{isWithinBoundingBox:function(e){return this.x>=e.left&&this.x<=e.right&&this.y>=e.top&&this.y<=e.bottom}}}),g={id:"n9-page-builder-toolbox"};var h={class:"n9-wrapper"};n(2222);var y=n(4586);function b(){var e=(0,r.ref)((0,y.Z)());return{uuid:e,generate:function(){(0,r.nextTick)((function(){e.value=(0,y.Z)()}))}}}const E=(0,r.defineComponent)({name:"PageBuilderToolOutline",setup:function(){var e=v(),t=(0,l.b)(),n=b(),o=n.generate,i=n.uuid;return(0,r.onMounted)((function(){u.Y.on(c.OG,o),u.Y.on(c.mv,o)})),{styles:function(){var e={};if(!t.highlightedId)return e;var n=t.document.querySelector("[data-component-id='".concat(t.highlightedId,"']")).getBoundingClientRect(),o=t.document.documentElement.scrollLeft,r=t.document.documentElement.scrollTop;return e.width="".concat(n.right-n.left,"px"),e.height="".concat(n.bottom-n.top,"px"),e.transform="translateX(".concat(n.left+o,"px) translateY(").concat(n.top+r,"px)"),e.id=i.value,delete e.id,e},active:(0,r.computed)((function(){return t.highlightedId&&e.over&&t.highlightedId!==t.selectedId})),label:(0,r.computed)((function(){return t.highlightedComponentLabel}))}}}),C=(0,p.Z)(E,[["render",function(e,t,n,o,i,l){return e.active?((0,r.openBlock)(),(0,r.createElementBlock)("div",{key:0,id:"n9-page-builder-tool-outline",style:(0,r.normalizeStyle)(e.styles())},[(0,r.createElementVNode)("div",h,[(0,r.createElementVNode)("h3",null,(0,r.toDisplayString)(e.label),1)])],4)):(0,r.createCommentVNode)("",!0)}]]);var B={key:0,class:"n9-wrapper"},x={key:0,ref:"ancestorsRef",class:"n9-ancestors"},w=(0,r.createElementVNode)("i",{class:"fas fa-chevron-down n9-text-white"},null,-1),k={key:0,ref:"ancestorsListRef"},L={ref:"optionsRef",class:"n9-options n9-text-white"},S=[(0,r.createElementVNode)("i",{class:"fas fa-cog"},null,-1)];n(5069),n(1532);var P=n(8624);const N=(0,r.defineComponent)({name:"PageBuilderToolSelect",setup:function(){var e=(0,l.b)(),t=(0,r.ref)(!1),n=(0,r.ref)(null),o=(0,r.ref)(null),i=(0,r.ref)([]),a=(0,r.ref)(null),s=(0,r.ref)(null),d=(0,r.computed)((function(){return e.getComponentAncestors(e.selectedComponent).reverse()})),p=new Map,f=b(),m=f.generate,v=f.uuid;function g(){var e,t,r,l,u;null===(e=n.value)||void 0===e||e.removeEventListener("mouseover",h),null===(t=n.value)||void 0===t||t.removeEventListener("mouseleave",y),null===(r=o.value)||void 0===r||r.removeEventListener("mouseleave",y),null===(l=a.value)||void 0===l||l.removeEventListener("mousedown",C),null===(u=s.value)||void 0===u||u.removeEventListener("click",B),p.forEach((function(e,t){e.forEach((function(e,n){var o;null===(o=i.value[t])||void 0===o||o.removeEventListener(n,e)}))})),p.clear()}function h(){t.value=!0}function y(){t.value=!1}(0,r.onMounted)((function(){u.Y.on(c.OG,m),u.Y.on(c.mv,m)})),(0,r.onBeforeUpdate)((function(){g(),i.value=[]})),(0,r.onUpdated)((function(){var t,r,l,u,c;null===(t=n.value)||void 0===t||t.addEventListener("mouseover",h),null===(r=n.value)||void 0===r||r.addEventListener("mouseleave",y),null===(l=o.value)||void 0===l||l.addEventListener("mouseleave",y),null===(u=a.value)||void 0===u||u.addEventListener("mousedown",C),null===(c=s.value)||void 0===c||c.addEventListener("click",B),i.value.forEach((function(t,n){if(t){var o=function(t){var o;t.stopPropagation(),o=d.value[n].id,e.selectedId=o,y()},r=function(t){var o;t.stopPropagation(),o=d.value[n].id,e.highlightedId=o},i=function(e){return e.stopPropagation()},l=new Map;l.set("click",o),l.set("mouseover",r),l.set("mousemove",i),p.set(n,l),t.addEventListener("click",o),t.addEventListener("mouseover",r),t.addEventListener("mousemove",i)}}))})),(0,r.onBeforeUnmount)((function(){g()}));var E=(0,r.computed)((function(){var t={};if(!e.selectedId)return t;var n=e.document.querySelector("[data-component-id='".concat(e.selectedId,"']")).getBoundingClientRect(),o=e.document.documentElement.scrollLeft,r=e.document.documentElement.scrollTop;return t.width="".concat(n.right-n.left,"px"),t.height="".concat(n.bottom-n.top,"px"),t.transform="translateX(".concat(n.left+o,"px) translateY(").concat(n.top+r,"px)"),t.id=v.value,delete t.id,t}));function C(){e.dragId=e.selectedId}function B(){e.isContextMenuVisible=!e.isContextMenuVisible}return{ancestorsRef:n,ancestorsListRef:o,ancestorsButtonRefs:i,componentLabelRef:a,optionsRef:s,styles:E,active:(0,r.computed)((function(){return!!e.selectedId})),label:(0,r.computed)((function(){return e.selectedComponentLabel})),ancestors:d,isBeingDragged:(0,r.computed)((function(){return void 0!==e.dragId})),areAncestorsVisible:t,drag:C,toggleContextMenu:e.toggleContextMenu,capitalCase:P.I}}}),V=(0,p.Z)(N,[["render",function(e,t,n,o,i,l){return e.active?((0,r.openBlock)(),(0,r.createElementBlock)("div",{key:0,id:"n9-page-builder-tool-select",style:(0,r.normalizeStyle)(e.styles)},[e.isBeingDragged?(0,r.createCommentVNode)("",!0):((0,r.openBlock)(),(0,r.createElementBlock)("div",B,[e.ancestors.length>0?((0,r.openBlock)(),(0,r.createElementBlock)("div",x,[w,e.areAncestorsVisible?((0,r.openBlock)(),(0,r.createElementBlock)("ul",k,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(e.ancestors,(function(t,n){return(0,r.openBlock)(),(0,r.createElementBlock)("li",{key:"ancestor-"+t.id},[(0,r.createElementVNode)("button",{ref:function(t){e.ancestorsButtonRefs[n]=t},type:"button"},(0,r.toDisplayString)(e.capitalCase(t.name)),513)])})),128))],512)):(0,r.createCommentVNode)("",!0)],512)):(0,r.createCommentVNode)("",!0),(0,r.createElementVNode)("h3",{ref:"componentLabelRef"},(0,r.toDisplayString)(e.label),513),(0,r.createElementVNode)("button",L,S,512)]))],4)):(0,r.createCommentVNode)("",!0)}]]);const R=(0,r.defineComponent)({name:"PageBuilderToolDragHandle",setup:function(){var e=v(),t=(0,l.b)(),n=(0,r.computed)((function(){return t.draggedComponent}));return{styles:(0,r.computed)((function(){return{transform:"translate3d(".concat(e.x,"px, ").concat(e.y,"px, 0px)")}})),active:(0,r.computed)((function(){return void 0!==t.dragId})),label:(0,r.computed)((function(){var e,t;return null!==(e=null===(t=n.value)||void 0===t?void 0:t.label)&&void 0!==e?e:""}))}}}),I=(0,p.Z)(R,[["render",function(e,t,n,o,i,l){return(0,r.withDirectives)(((0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-tool-drag-handle",style:(0,r.normalizeStyle)(e.styles)},[(0,r.createElementVNode)("h3",null,[(0,r.createElementVNode)("span",null,(0,r.toDisplayString)(e.label),1)])],4)),[[r.vShow,e.active]])}]]);const T=(0,r.defineComponent)({name:"PageBuilderToolInsertLine",setup:function(){var e=v(),t=(0,l.b)(),n=(0,r.reactive)({x:0,y:0,width:0,height:0}),o=(0,r.computed)((function(){var e={},n=t.highlightedComponentElement;if(!n)return e;var o=n.getBoundingClientRect(),r=t.document.documentElement.scrollLeft,i=t.document.documentElement.scrollTop;return e.width="".concat(o.right-o.left,"px"),e.height="".concat(o.bottom-o.top,"px"),e.transform="translateX(".concat(o.left+r,"px) translateY(").concat(o.top+i,"px)"),e})),i=(0,r.computed)((function(){return Math.min(n.width/4,100)})),a=(0,r.computed)((function(){return Math.min(n.height/4,100)})),u=(0,r.computed)((function(){var o=void 0;return t.draggedComponent&&!c.value||(Math.abs(n.x-e.x)<i.value&&s("left")&&(o="left"),Math.abs(n.x+n.width-e.x)<i.value&&s("right")&&(o="right"),Math.abs(n.y-e.y)<a.value&&s("top")&&(o="top"),Math.abs(n.y+n.height-e.y)<a.value&&s("bottom")&&(o="bottom")),o})),c=(0,r.computed)((function(){return t.dragId!==t.highlightedId&&(!(!t.draggedComponent||!t.highlightedComponent)&&(0===t.highlightedComponent.siblingsShortcodes.length?0===t.draggedComponent.siblingsShortcodes.length||t.draggedComponent.siblingsShortcodes.includes(t.highlightedComponent.name):t.highlightedComponent.siblingsShortcodes.includes(t.draggedComponent.name)))}));function s(e){return!!t.highlightedComponent&&t.highlightedComponent.siblingsPosition.includes(e)}return(0,r.watch)(u,(function(){return t.dropPosition=u.value})),(0,r.watch)((function(){return t.$state}),(function(){var e=t.highlightedComponentElement;if(e){var o=e.getBoundingClientRect();n.width=o.right-o.left,n.height=o.bottom-o.top,n.x=o.left,n.y=o.top}}),{deep:!0}),{styles:o,position:u,active:(0,r.computed)((function(){return e.over&&!!u.value}))}}}),Y=(0,p.Z)(T,[["render",function(e,t,n,o,i,l){return(0,r.withDirectives)(((0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-tool-insert-line",style:(0,r.normalizeStyle)(e.styles)},[(0,r.createElementVNode)("div",{class:(0,r.normalizeClass)(["n9-line",e.position?"n9-"+e.position:null])},null,2)],4)),[[r.vShow,e.active]])}]]);var O=[(0,r.createElementVNode)("i",{class:"fas fa-plus fa-xs"},null,-1)];const z=(0,r.defineComponent)({name:"PageBuilderToolInsertButton",setup:function(){var e=v(),t=(0,l.b)(),n=(0,r.ref)(null),o=(0,r.ref)(null),i=(0,r.computed)((function(){return e.over&&!!t.dropPosition}));(0,r.onMounted)((function(){var e;null===(e=o.value)||void 0===e||e.addEventListener("click",d)})),(0,r.onBeforeUnmount)((function(){var e;null===(e=o.value)||void 0===e||e.removeEventListener("click",d)}));var a=(0,r.computed)((function(){var e={},n=t.highlightedComponentElement;if(!n)return e;var o=n.getBoundingClientRect(),r=t.document.documentElement.scrollLeft,i=t.document.documentElement.scrollTop;return e.width="".concat(o.right-o.left,"px"),e.height="".concat(o.bottom-o.top,"px"),e.transform="translateX(".concat(o.left+r,"px) translateY(").concat(o.top+i,"px)"),e})),s=(0,r.computed)((function(){var n={},o=t.highlightedComponentElement;if(!o)return n;var r=o.getBoundingClientRect();return n.left="".concat(e.x-r.left,"px"),n}));function d(){u.Y.emit(c.Yn,{tree:t.pageComponents,target:t.highlightedComponent,position:t.dropPosition})}return(0,r.watch)((function(){return e.$state}),(function(){var n,o=null===(n=t.highlightedComponentElement)||void 0===n?void 0:n.getBoundingClientRect();i.value&&o&&!e.isWithinBoundingBox(o)&&(t.highlightedId=void 0)}),{deep:!0}),{wrapperRef:n,buttonRef:o,styles:a,buttonStyles:s,dropPosition:(0,r.computed)((function(){return t.dropPosition})),active:i}}}),M=(0,p.Z)(z,[["render",function(e,t,n,o,i,l){return(0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-tool-insert-button",ref:"wrapperRef",style:(0,r.normalizeStyle)(e.styles)},[(0,r.withDirectives)((0,r.createElementVNode)("button",{ref:"buttonRef",class:(0,r.normalizeClass)(e.dropPosition?"n9-"+e.dropPosition:null),style:(0,r.normalizeStyle)(e.buttonStyles)},O,6),[[r.vShow,e.active]])],4)}]]);var D={class:"n9-menu-buttons-list"},A={ref:"editRef"},U=[(0,r.createElementVNode)("i",{class:"far fa-edit"},null,-1),(0,r.createTextVNode)(" Edit")],Z={ref:"duplicateRef"},$=[(0,r.createElementVNode)("i",{class:"far fa-clone"},null,-1),(0,r.createTextVNode)(" Duplicate")],j={ref:"savePresetRef"},J=[(0,r.createElementVNode)("i",{class:"far fa-save"},null,-1),(0,r.createTextVNode)(" Save as preset")],_={ref:"showShortcodeRef"},H=[(0,r.createElementVNode)("i",{class:"far fa-eye"},null,-1),(0,r.createTextVNode)(" Show shortcode")],F={ref:"deleteRef",type:"button"},W=[(0,r.createElementVNode)("i",{class:"far fa-trash-alt"},null,-1),(0,r.createTextVNode)(" Delete")];const q=(0,r.defineComponent)({name:"PageBuilderToolContextMenu",setup:function(){var e=(0,l.b)(),t=(0,r.ref)(null),n=(0,r.ref)(null),o=(0,r.ref)(null),i=(0,r.ref)(null),a=(0,r.ref)(null);function s(){v(),u.Y.emit(c.LK,{component:e.selectedComponent})}function d(){v(),e.duplicateComponent(e.selectedId)}function p(){v(),e.selectedComponent&&u.Y.emit(c.qJ,{component:e.selectedComponent})}function f(){v(),e.selectedComponent&&u.Y.emit(c.sp,{component:e.selectedComponent})}function m(){v(),e.selectedComponent&&u.Y.emit(c.IU,{tree:e.pageComponents,componentToDelete:e.selectedComponent})}function v(){e.isContextMenuVisible=!1}return(0,r.onMounted)((function(){var e,r,l,u,c;null===(e=t.value)||void 0===e||e.addEventListener("click",s),null===(r=n.value)||void 0===r||r.addEventListener("click",d),null===(l=o.value)||void 0===l||l.addEventListener("click",p),null===(u=i.value)||void 0===u||u.addEventListener("click",f),null===(c=a.value)||void 0===c||c.addEventListener("click",m)})),(0,r.onBeforeUnmount)((function(){var e,r,l,u,c;null===(e=t.value)||void 0===e||e.removeEventListener("click",s),null===(r=n.value)||void 0===r||r.removeEventListener("click",d),null===(l=o.value)||void 0===l||l.removeEventListener("click",p),null===(u=i.value)||void 0===u||u.removeEventListener("click",f),null===(c=a.value)||void 0===c||c.removeEventListener("click",m)})),(0,r.watch)((function(){return e.selectedId}),(function(){v()})),{editRef:t,duplicateRef:n,savePresetRef:o,showShortcodeRef:i,deleteRef:a,styles:function(){var t={},n=e.document.querySelector("#n9-page-builder-tool-select .n9-options");if(n){var o=n.getBoundingClientRect(),r=e.document.documentElement.scrollLeft,i=e.document.documentElement.scrollTop;t.transform="translateX(".concat(o.left+r,"px) translateY(").concat(o.top+i+20,"px)")}return t},isContextMenuVisible:(0,r.computed)((function(){return e.isContextMenuVisible}))}}}),X=(0,p.Z)(q,[["render",function(e,t,n,o,i,l){return(0,r.withDirectives)(((0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-tool-context-menu",style:(0,r.normalizeStyle)(e.styles())},[(0,r.createElementVNode)("div",D,[(0,r.createElementVNode)("button",A,U,512),(0,r.createElementVNode)("button",Z,$,512),(0,r.createElementVNode)("button",j,J,512),(0,r.createElementVNode)("button",_,H,512),(0,r.createElementVNode)("button",F,W,512)])],4)),[[r.vShow,e.isContextMenuVisible]])}]]),G=(0,r.defineComponent)({name:"PageBuilderToolbox",components:{PageBuilderToolContextMenu:X,PageBuilderToolInsertButton:M,PageBuilderToolInsertLine:Y,PageBuilderToolDragHandle:I,PageBuilderToolOutline:C,PageBuilderToolSelect:V}}),K=(0,p.Z)(G,[["render",function(e,t,n,o,i,l){var a=(0,r.resolveComponent)("PageBuilderToolOutline"),u=(0,r.resolveComponent)("PageBuilderToolSelect"),c=(0,r.resolveComponent)("PageBuilderToolDragHandle"),s=(0,r.resolveComponent)("PageBuilderToolInsertLine"),d=(0,r.resolveComponent)("PageBuilderToolInsertButton"),p=(0,r.resolveComponent)("PageBuilderToolContextMenu");return(0,r.openBlock)(),(0,r.createElementBlock)("div",g,[(0,r.createVNode)(a),(0,r.createVNode)(u),(0,r.createVNode)(c),(0,r.createVNode)(s),(0,r.createVNode)(d),(0,r.createVNode)(p)])}]]),Q=(0,r.defineComponent)({name:"PageBuilder",components:{PageBuilderToolbox:K,PageBuilderComponent:f},setup:function(){var e=(0,s.Z)().replaceComponentInTree,t=(0,r.ref)(null),n=v(),o=(0,l.b)(),i=(0,r.ref)(!1);function a(){i.value=!0}function d(){i.value=!1}return(0,r.onMounted)((function(){u.Y.on(c.xx,(function(e){n.x=e.x,n.y=e.y})),u.Y.on(c.O0,(function(e){e.deletedComponent.id===o.selectedId&&(o.selectedId=void 0)})),u.Y.on(c.OG,(function(t){o.pageComponents=e(o.pageComponents,t.component)})),u.Y.on(c.Yv,(function(e){return o.viewportSize=e})),u.Y.on(c.n2,(function(e){var t;o.selectedId=null===(t=e.component)||void 0===t?void 0:t.id})),u.Y.on(c.p7,(function(e){var t;o.highlightedId=null===(t=e.component)||void 0===t?void 0:t.id,n.over=!!e.component})),u.Y.on(c.ni,(function(e){var t;o.pageComponents=null!==(t=null==e?void 0:e.tree)&&void 0!==t?t:[],u.Y.emit(c.Zc,{tree:JSON.parse(JSON.stringify(o.pageComponents))})})),u.Y.on(c.cA,(function(){o.saveComponents(),u.Y.emit(c.mK,{tree:JSON.parse(JSON.stringify(o.pageComponents))})})),o.document.addEventListener("mousedown",(function(){n.over||(o.selectedId=void 0)})),o.document.addEventListener("mouseup",(function(){o.dragId=void 0})),t.value.addEventListener("mouseenter",a),t.value.addEventListener("mouseleave",d)})),(0,r.onBeforeUnmount)((function(){t.value.removeEventListener("mouseenter",a),t.value.removeEventListener("mouseleave",d)})),(0,r.watch)(i,(function(){n.over=i.value})),{builder:t,components:(0,r.computed)((function(){return o.pageComponents})),isDragging:(0,r.computed)((function(){return void 0!==o.dragId}))}}}),ee=(0,p.Z)(Q,[["render",function(e,t,n,o,i,l){var a=(0,r.resolveComponent)("PageBuilderComponent"),u=(0,r.resolveComponent)("PageBuilderToolbox");return(0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"n9-page-builder-wrapper",ref:"builder",class:(0,r.normalizeClass)({dragging:e.isDragging})},[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(e.components,(function(e){return(0,r.openBlock)(),(0,r.createBlock)(a,{key:e.id,component:e},null,8,["component"])})),128)),(0,r.createVNode)(u)],2)}]]);function te(e,t,n,o,r,i,l){try{var a=e[i](l),u=a.value}catch(e){return void n(e)}a.done?t(u):Promise.resolve(u).then(o,r)}function ne(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}var oe=function(){function e(t,n){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),this.createApp(t),this.registerBuiltInComponents(),this.setupStoreAndFetchData(n)}var t,n,o,i,s;return t=e,n=[{key:"createApp",value:function(e){var t=document.createElement("div");e.parentNode.insertBefore(t,e);var n=(0,r.compile)(e.outerHTML);this.app=(0,r.createApp)({components:{PageBuilder:ee},render:n}),this.app.use((0,m.WB)()),this.app.mount(t),e.parentNode.removeChild(e)}},{key:"registerBuiltInComponents",value:function(){this.app.component("PageBuilderStyle",{render:function(){return(0,r.h)("style",{},this.$slots.default())}})}},{key:"setupStoreAndFetchData",value:(i=regeneratorRuntime.mark((function e(t){var n,o;return regeneratorRuntime.wrap((function(e){for(;;)switch(e.prev=e.next){case 0:return(n=(0,l.b)()).setup({app:this,componentsApiUrl:t}),e.next=4,n.fetchComponents();case 4:o=JSON.parse(JSON.stringify(n.pageComponents)),u.Y.emit(c.Zc,{tree:o}),u.Y.emit(c.h4,{tree:o,availableComponents:JSON.parse(JSON.stringify(n.availablePageComponents)),forms:JSON.parse(JSON.stringify(n.pageComponentForms))});case 7:case"end":return e.stop()}}),e,this)})),s=function(){var e=this,t=arguments;return new Promise((function(n,o){var r=i.apply(e,t);function l(e){te(r,n,o,l,a,"next",e)}function a(e){te(r,n,o,l,a,"throw",e)}l(void 0)}))},function(e){return s.apply(this,arguments)})},{key:"compileComponent",value:function(e,t){var n=(0,a.Ho)(e)+"PageBuilderComponent";this.app.component(n,{components:{PageBuilderComponent:f,PageBuilderStyle:this.app.component("PageBuilderStyle")},props:["parameters","responsive","computed","children","selfInstance","viewSize"],setup:function(e){function t(t){return e.responsive.includes(t)}return{isResponsive:t,getResponsiveValue:function(n){return t(n)?Object.prototype.hasOwnProperty.call(e.parameters[n],e.viewSize)?e.parameters[n][e.viewSize]:"":e.parameters[n]}}},template:t})}}],n&&ne(t.prototype,n),o&&ne(t,o),e}();const re=(0,r.defineComponent)({name:"PageBuilderFrame",props:{frontendUrl:{type:String,required:!0},componentsApiUrl:{type:String,required:!0},disableLinks:{type:Boolean,required:!1,default:!1}},setup:function(e){var t=(0,r.ref)(null),n=(0,r.ref)(0),o=(0,r.ref)("100%");(0,r.onMounted)((function(){u.Y.on(c.Yv,(function(e){switch(e){case"md":o.value="768px";break;case"xs":o.value="425px";break;default:o.value="100%"}}))}));function i(e){u.Y.emit(c.xx,{x:e.clientX,y:e.clientY})}return{iframe:t,width:o,height:(0,r.computed)((function(){return"".concat(n.value-48,"px")})),onLoad:function(){var o=t.value.contentDocument.body.getElementsByTagName("page-builder");if(0===o.length)throw new Error("Page without <page-builder> tag. Aborting.");var r,l=new oe(o[0],e.componentsApiUrl);u.Y.emit(c.GB,{app:l}),e.disableLinks&&(r=t.value.contentDocument.body.getElementsByTagName("a"),Array.from(r).forEach((function(e){e.addEventListener("click",(function(e){return e.preventDefault()}))}))),t.value.contentWindow.addEventListener("mousemove",i),u.Y.on(c.kf,(function(e){n.value=e,t.value.height="".concat(e-48)})),u.Y.emit(c.DE)}}}}),ie=(0,p.Z)(re,[["render",function(e,t,n,o,l,a){return(0,r.openBlock)(),(0,r.createElementBlock)("div",{id:"page-builder-content-frame",class:"w-full min-h-full flex",style:(0,r.normalizeStyle)({height:e.height,width:e.width})},[(0,r.createElementVNode)("iframe",{ref:"iframe",src:e.frontendUrl,class:"flex-1",onLoad:t[0]||(t[0]=function(){return e.onLoad&&e.onLoad.apply(e,arguments)})},null,40,i)],4)}]]);function le(e){return le="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},le(e)}function ae(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function ue(e,t){for(var n=0;n<t.length;n++){var o=t[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(e,o.key,o)}}function ce(e,t){return ce=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e},ce(e,t)}function se(e){var t=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(e){return!1}}();return function(){var n,o=pe(e);if(t){var r=pe(this).constructor;n=Reflect.construct(o,arguments,r)}else n=o.apply(this,arguments);return de(this,n)}}function de(e,t){if(t&&("object"===le(t)||"function"==typeof t))return t;if(void 0!==t)throw new TypeError("Derived constructors may only return object or undefined");return function(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}(e)}function pe(e){return pe=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)},pe(e)}var fe=function(e){!function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&ce(e,t)}(l,e);var t,n,o,i=se(l);function l(){return ae(this,l),i.apply(this,arguments)}return t=l,(n=[{key:"connect",value:function(){var e=this;window.addEventListener("resize",(function(){return e.emitWindowHeight()})),this.emitWindowHeight(),(0,r.createApp)(ie,{frontendUrl:this.frontendUrlValue,componentsApiUrl:this.componentsApiUrlValue,disableLinks:!0}).use((0,m.WB)()).mount(this.element)}},{key:"emitWindowHeight",value:function(){u.Y.emit(c.kf,document.documentElement.clientHeight)}}])&&ue(t.prototype,n),o&&ue(t,o),l}(o.Controller);fe.values={frontendUrl:String,componentsApiUrl:String}},5631:(e,t,n)=>{var o=n(3070).f,r=n(30),i=n(2248),l=n(9974),a=n(5787),u=n(408),c=n(654),s=n(6340),d=n(9781),p=n(2423).fastKey,f=n(9909),m=f.set,v=f.getterFor;e.exports={getConstructor:function(e,t,n,c){var s=e((function(e,o){a(e,f),m(e,{type:t,index:r(null),first:void 0,last:void 0,size:0}),d||(e.size=0),null!=o&&u(o,e[c],{that:e,AS_ENTRIES:n})})),f=s.prototype,g=v(t),h=function(e,t,n){var o,r,i=g(e),l=y(e,t);return l?l.value=n:(i.last=l={index:r=p(t,!0),key:t,value:n,previous:o=i.last,next:void 0,removed:!1},i.first||(i.first=l),o&&(o.next=l),d?i.size++:e.size++,"F"!==r&&(i.index[r]=l)),e},y=function(e,t){var n,o=g(e),r=p(t);if("F"!==r)return o.index[r];for(n=o.first;n;n=n.next)if(n.key==t)return n};return i(f,{clear:function(){for(var e=g(this),t=e.index,n=e.first;n;)n.removed=!0,n.previous&&(n.previous=n.previous.next=void 0),delete t[n.index],n=n.next;e.first=e.last=void 0,d?e.size=0:this.size=0},delete:function(e){var t=this,n=g(t),o=y(t,e);if(o){var r=o.next,i=o.previous;delete n.index[o.index],o.removed=!0,i&&(i.next=r),r&&(r.previous=i),n.first==o&&(n.first=r),n.last==o&&(n.last=i),d?n.size--:t.size--}return!!o},forEach:function(e){for(var t,n=g(this),o=l(e,arguments.length>1?arguments[1]:void 0);t=t?t.next:n.first;)for(o(t.value,t.key,this);t&&t.removed;)t=t.previous},has:function(e){return!!y(this,e)}}),i(f,n?{get:function(e){var t=y(this,e);return t&&t.value},set:function(e,t){return h(this,0===e?0:e,t)}}:{add:function(e){return h(this,e=0===e?0:e,e)}}),d&&o(f,"size",{get:function(){return g(this).size}}),s},setStrong:function(e,t,n){var o=t+" Iterator",r=v(t),i=v(o);c(e,t,(function(e,t){m(this,{type:o,target:e,state:r(e),kind:t,last:void 0})}),(function(){for(var e=i(this),t=e.kind,n=e.last;n&&n.removed;)n=n.previous;return e.target&&(e.last=n=n?n.next:e.state.first)?"keys"==t?{value:n.key,done:!1}:"values"==t?{value:n.value,done:!1}:{value:[n.key,n.value],done:!1}:(e.target=void 0,{value:void 0,done:!0})}),n?"entries":"values",!n,!0),s(t)}}},7710:(e,t,n)=>{var o=n(2109),r=n(7854),i=n(1702),l=n(4705),a=n(1320),u=n(2423),c=n(408),s=n(5787),d=n(614),p=n(111),f=n(7293),m=n(7072),v=n(8003),g=n(9587);e.exports=function(e,t,n){var h=-1!==e.indexOf("Map"),y=-1!==e.indexOf("Weak"),b=h?"set":"add",E=r[e],C=E&&E.prototype,B=E,x={},w=function(e){var t=i(C[e]);a(C,e,"add"==e?function(e){return t(this,0===e?0:e),this}:"delete"==e?function(e){return!(y&&!p(e))&&t(this,0===e?0:e)}:"get"==e?function(e){return y&&!p(e)?void 0:t(this,0===e?0:e)}:"has"==e?function(e){return!(y&&!p(e))&&t(this,0===e?0:e)}:function(e,n){return t(this,0===e?0:e,n),this})};if(l(e,!d(E)||!(y||C.forEach&&!f((function(){(new E).entries().next()})))))B=n.getConstructor(t,e,h,b),u.enable();else if(l(e,!0)){var k=new B,L=k[b](y?{}:-0,1)!=k,S=f((function(){k.has(1)})),P=m((function(e){new E(e)})),N=!y&&f((function(){for(var e=new E,t=5;t--;)e[b](t,t);return!e.has(-0)}));P||((B=t((function(e,t){s(e,C);var n=g(new E,e,B);return null!=t&&c(t,n[b],{that:n,AS_ENTRIES:h}),n}))).prototype=C,C.constructor=B),(S||N)&&(w("delete"),w("has"),h&&w("get")),(N||L)&&w(b),y&&C.clear&&delete C.clear}return x[e]=B,o({global:!0,forced:B!=E},x),v(B,e),y||n.setStrong(B,e,h),B}},5069:(e,t,n)=>{var o=n(2109),r=n(1702),i=n(3157),l=r([].reverse),a=[1,2];o({target:"Array",proto:!0,forced:String(a)===String(a.reverse())},{reverse:function(){return i(this)&&(this.length=this.length),l(this)}})},1532:(e,t,n)=>{n(7710)("Map",(function(e){return function(){return e(this,arguments.length?arguments[0]:void 0)}}),n(5631))}}]);