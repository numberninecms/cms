(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[14],{ff63:function(e,t,n){"use strict";n.r(t);var a,s=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("q-page",[n("div",{staticClass:"q-ma-md row q-col-gutter-md",staticStyle:{"padding-top":"42px"}},[n("div",{staticClass:"col-md-2 col-sm-6 col-xs"},[n("q-select",{attrs:{label:"Select a menu to edit",filled:"",options:e.menus,"option-label":"name","option-value":"id",value:e.selectedMenu},on:{input:function(t){return e.selectCurrentMenu(t)}}})],1),n("div",{staticClass:"col-md-2 col-sm-6 col-xs"},[n("q-input",{attrs:{label:"Add new menu",filled:""},scopedSlots:e._u([{key:"append",fn:function(){return[n("q-btn",{attrs:{round:"",dense:"",flat:"",icon:"add"},on:{click:function(t){return e.addMenu(e.newMenu)}}})]},proxy:!0}]),model:{value:e.newMenu,callback:function(t){e.newMenu=t},expression:"newMenu"}})],1)]),e.selectedMenu?n("div",{staticClass:"q-ma-md row q-col-gutter-md"},[n("div",{staticClass:"col-md-2 col-sm-4 col-xs-12 col-xs order-sm-none order-xs-last"},e._l(e.contentTypes,(function(t){return n("q-expansion-item",{key:t.name,staticClass:"q-mb-xs",attrs:{label:e.getLabel(t),"header-class":"bg-secondary text-white","expand-icon-class":"text-white"},on:{click:function(n){return e.loadEntities(t,1)}}},[e.getEntitiesOfType(t,e.page[t.name]).length>0?n("div",[n("q-scroll-area",{staticStyle:{height:"300px"}},[n("div",{staticClass:"q-pa-sm"},[n("q-pagination",{attrs:{value:e.page[t.name],max:e.getMaxPages(t),"max-pages":5,color:"secondary","boundary-links":e.getMaxPages(t)>5},on:{input:function(n){return e.loadEntities(t,n)}}})],1),n("q-list",{attrs:{dense:""}},e._l(e.getEntitiesOfType(t,e.page[t.name]),(function(t){return n("q-item",{directives:[{name:"ripple",rawName:"v-ripple"}],key:t.id,attrs:{tag:"label"}},[n("q-item-section",{attrs:{side:""}},[n("q-checkbox",{attrs:{color:"secondary","false-value":null,"indeterminate-value":"indeterminate",dense:""},model:{value:e.checked[t.id],callback:function(n){e.$set(e.checked,t.id,n)},expression:"checked[entity.id]"}})],1),n("q-item-section",[n("q-item-label",[e._v(e._s(t.title))])],1)],1)})),1),n("div",{staticClass:"q-pa-sm q-pr-lg flex justify-end"},[n("q-btn",{attrs:{label:"Add to menu",dense:"",color:"secondary"},on:{click:function(t){return e.addSelectedToMenu()}}})],1)],1)],1):e._e()])})),1),n("div",{staticClass:"col-md-8 col-sm-6 col-xs"},[n("div",{staticClass:"text-h4"},[e._v(e._s(e.selectedMenu.name))]),n("q-sortable-tree",{attrs:{nodes:e.menuTrees[e.selectedMenu.id],"label-key":"title","node-key":"uid","default-expand-all":!0}}),n("q-btn",{staticClass:"q-mt-lg",attrs:{label:"Save menu",color:"primary"},on:{click:e.saveMenu}})],1)]):e._e(),n("q-page-sticky",{attrs:{expand:"",position:"top"}},[n("q-toolbar",{staticClass:"bg-primary text-white"},[n("q-toolbar-title",[e._v("\n                Menus\n            ")])],1)],1)],1)},i=[],o=n("3043"),c=n("1732"),l=n("3890"),d=n("1b40"),r=n("4bb5"),p=n("2ef0"),u=function(e,t,n,a){var s,i=arguments.length,o=i<3?t:null===a?a=Object.getOwnPropertyDescriptor(t,n):a;if("object"===typeof Reflect&&"function"===typeof Reflect.decorate)o=Reflect.decorate(e,t,n,a);else for(var c=e.length-1;c>=0;c--)(s=e[c])&&(o=(i<3?s(o):i>3?s(t,n,o):s(t,n))||o);return i>3&&o&&Object.defineProperty(t,n,o),o},y=function(e,t){if("object"===typeof Reflect&&"function"===typeof Reflect.metadata)return Reflect.metadata(e,t)};const m=Object(r["a"])("ContentType"),f=Object(r["a"])("Menu");let g=class extends d["d"]{constructor(){super(...arguments),this.newMenu="",this.page={},this.checked={},this.menuTrees={}}created(){this.queryMenus()}selectCurrentMenu(e){this.selectMenu(e),this.menuTrees.hasOwnProperty(e.id)||this.$set(this.menuTrees,e.id,[...Object(p["defaultsDeep"])({},e).menuItems])}getLabel(e){return o["a"](e.labels.pluralName)}async loadEntities(e,t){this.page[e.name]=t,0===this.getEntitiesOfType(e,t).length&&await this.queryEntitiesForMenu({contentType:e.name,page:t}),this.$forceUpdate()}getEntitiesOfType(e,t){return this.getContentEntitiesOfType({contentType:e.name,page:t})}getMaxPages(e){return this.getMaxPagesOfType(e.name)}addSelectedToMenu(){const e=Object.keys(this.checked).filter((e=>this.checked[e])).map((e=>parseInt(e))),t=e.map((e=>this.getContentEntityById(e))).filter((e=>null!==e));this.menuTrees[this.selectedMenu.id].push(...t.map((e=>({uid:Object(c["a"])(),entityId:e.id,title:e.title,children:[]})))),this.checked={}}saveMenu(){this.updateSelectedMenu(this.menuTrees[this.selectedMenu.id])}};u([m.State,y("design:type",Array)],g.prototype,"contentTypes",void 0),u([f.State,y("design:type",Array)],g.prototype,"menus",void 0),u([f.State,y("design:type","function"===typeof(a="undefined"!==typeof l["default"]&&l["default"])?a:Object)],g.prototype,"selectedMenu",void 0),u([f.Action,y("design:type",Object)],g.prototype,"queryMenus",void 0),u([f.Action,y("design:type",Object)],g.prototype,"addMenu",void 0),u([f.Action,y("design:type",Object)],g.prototype,"selectMenu",void 0),u([f.Action,y("design:type",Object)],g.prototype,"queryEntitiesForMenu",void 0),u([f.Action,y("design:type",Object)],g.prototype,"updateSelectedMenu",void 0),u([f.Getter,y("design:type",Object)],g.prototype,"getContentEntitiesOfType",void 0),u([f.Getter,y("design:type",Object)],g.prototype,"getContentEntityById",void 0),u([f.Getter,y("design:type",Object)],g.prototype,"getMaxPagesOfType",void 0),g=u([d["a"]],g);var b=g,h=b,v=n("2877"),M=n("9989"),x=n("ddd8"),q=n("27f9"),O=n("9c40"),k=n("3b73"),T=n("4983"),w=n("3b16"),j=n("1c1c"),C=n("66e5"),S=n("4074"),E=n("8f8e"),Q=n("0170"),_=n("de5e"),P=n("65c6"),A=n("6ac5"),I=n("714f"),R=n("eebe"),L=n.n(R),$=Object(v["a"])(h,s,i,!1,null,null,null);t["default"]=$.exports;L()($,"components",{QPage:M["a"],QSelect:x["a"],QInput:q["a"],QBtn:O["a"],QExpansionItem:k["a"],QScrollArea:T["a"],QPagination:w["a"],QList:j["a"],QItem:C["a"],QItemSection:S["a"],QCheckbox:E["a"],QItemLabel:Q["a"],QPageSticky:_["a"],QToolbar:P["a"],QToolbarTitle:A["a"]}),L()($,"directives",{Ripple:I["a"]})}}]);