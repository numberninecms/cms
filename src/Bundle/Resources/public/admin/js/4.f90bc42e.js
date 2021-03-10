(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[4],{9166:function(e,t,s){"use strict";s.r(t);var i,a=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("q-page",[s("div",{staticStyle:{"padding-top":"42px"}},[s("div",{staticClass:"row q-pa-md q-gutter-md"},[s("div",{staticClass:"col col-sm-8 col-md-6 col-lg-4"},[e.user?s("UserForm",{attrs:{user:e.user},on:{"update:user":function(t){e.user=t}}}):e._e()],1)])]),s("q-page-sticky",{attrs:{expand:"",position:"top"}},[s("q-toolbar",{staticClass:"bg-primary text-white"},[e.id?s("q-toolbar-title",[e._v("Editing user "+e._s(e.usernameBackup?e.usernameBackup:e.id))]):s("q-toolbar-title",[e._v("Add new user")])],1)],1)],1)},r=[],n=function(){var e=this,t=e.$createElement,s=e._self._c||t;return s("div",[s("form",{staticClass:"q-gutter-md",on:{submit:function(t){return t.preventDefault(),t.stopPropagation(),e.onSubmit(t)}}},[s("q-input",{ref:"username",attrs:{label:"Username",filled:"",readonly:e.user.id>0,hint:"Username can't be changed once user is created",debounce:300,rules:[e.isUsernameValid],"error-message":this.user.username.trim()?"This username is already in use":"Required"},model:{value:e.user.username,callback:function(t){e.$set(e.user,"username",t)},expression:"user.username"}}),s("q-input",{ref:"email",attrs:{label:"Email",filled:"",type:"email",debounce:300,hint:"Make sure email is valid to receive notifications",rules:[e.isEmailValid],"error-message":this.user.email.trim()?"This email is already in use":"Required"},model:{value:e.user.email,callback:function(t){e.$set(e.user,"email",t)},expression:"user.email"}}),e.user.id?e._e():s("q-input",{attrs:{label:"Password",filled:"",hint:e.passwordStrength,rules:[function(e){return!!e||"Required"}],type:e.isPassword?"password":"text"},scopedSlots:e._u([{key:"append",fn:function(){return[s("q-icon",{staticClass:"cursor-pointer",attrs:{name:e.isPassword?"visibility_off":"visibility"},on:{click:function(t){e.isPassword=!e.isPassword}}})]},proxy:!0}],null,!1,1009450914),model:{value:e.user.password,callback:function(t){e.$set(e.user,"password",t)},expression:"user.password"}}),s("q-input",{attrs:{label:"First name",filled:""},model:{value:e.user.firstName,callback:function(t){e.$set(e.user,"firstName",t)},expression:"user.firstName"}}),s("q-input",{attrs:{label:"Last name",filled:""},model:{value:e.user.lastName,callback:function(t){e.$set(e.user,"lastName",t)},expression:"user.lastName"}}),s("q-select",{attrs:{label:"Display name publicly as",filled:"","map-options":"","emit-value":"",options:e.options},model:{value:e.user.displayNameFormat,callback:function(t){e.$set(e.user,"displayNameFormat",t)},expression:"user.displayNameFormat"}}),s("q-select",{attrs:{label:"Roles",filled:"",multiple:"","option-label":"name","option-value":"id",options:e.roles},model:{value:e.user.userRoles,callback:function(t){e.$set(e.user,"userRoles",t)},expression:"user.userRoles"}}),s("q-btn",{attrs:{label:"Save",type:"submit",loading:e.isLoading,color:"primary"}}),e.user.id?s("q-btn",{attrs:{label:"Delete",loading:e.isDeleting,color:"negative"},on:{click:function(t){e.confirmDelete=!0}}}):e._e()],1),s("DeleteUserConfirm",{attrs:{selected:[e.user]},on:{done:e.onDeleteDone,close:function(t){e.confirmDelete=!1}},model:{value:e.confirmDelete,callback:function(t){e.confirmDelete=t},expression:"confirmDelete"}})],1)},l=[],o=s("a4f8"),u=s("8cfb"),c=s("2a3e"),d=s("1b40"),m=s("4bb5"),p=function(e,t,s,i){var a,r=arguments.length,n=r<3?t:null===i?i=Object.getOwnPropertyDescriptor(t,s):i;if("object"===typeof Reflect&&"function"===typeof Reflect.decorate)n=Reflect.decorate(e,t,s,i);else for(var l=e.length-1;l>=0;l--)(a=e[l])&&(n=(r<3?a(n):r>3?a(t,s,n):a(t,s))||n);return r>3&&n&&Object.defineProperty(t,s,n),n},f=function(e,t){if("object"===typeof Reflect&&"function"===typeof Reflect.metadata)return Reflect.metadata(e,t)};const h=Object(m["a"])("User");let b=class extends d["d"]{constructor(){super(...arguments),this.isLoading=!1,this.isDeleting=!1,this.confirmDelete=!1,this.isPassword=!0}created(){this.queryRoles()}onUserChange(){this.initialEmail=this.user.email}get options(){const e=[];return e.push({label:this.user.username,value:"username"}),this.user.firstName&&e.push({label:`${this.user.firstName}`,value:"first_only"}),this.user.lastName&&e.push({label:`${this.user.lastName}`,value:"last_only"}),this.user.firstName&&this.user.lastName&&e.push({label:`${this.user.firstName} ${this.user.lastName}`,value:"first_last"},{label:`${this.user.lastName} ${this.user.firstName}`,value:"last_first"}),e}onSubmit(){this.username.validate(),this.email.validate(),this.username.hasError||this.email.hasError||this.saveUserAction()}async saveUserAction(){var e;this.isLoading=!0;const t=await this.saveUser(this.user);this.isLoading=!1,this.$router.push({name:"user_edit",params:{id:(null!==(e=t.id)&&void 0!==e?e:"").toString()}}).catch((()=>{}))}onDeleteDone(){this.confirmDelete=!1,this.$router.push("/users/").catch((()=>{}))}async isUsernameFreeToUse(){return!!this.user.id||!!this.user.username.trim()&&await this.checkUsernameAvailability(this.user.username)}async isUsernameValid(){return""!==this.user.username.trim()&&await this.isUsernameFreeToUse()}async isEmailFreeToUse(){var e;return this.user.email===this.initialEmail||!!(null===(e=this.user.email)||void 0===e?void 0:e.trim())&&await this.checkEmailAvailability(this.user.email)}async isEmailValid(){var e;return""!==(null===(e=this.user.email)||void 0===e?void 0:e.trim())&&await this.isEmailFreeToUse()}get passwordStrength(){return this.user.password?o(this.user.password).value:""}};p([h.State,f("design:type",Object)],b.prototype,"roles",void 0),p([h.Action,f("design:type",Object)],b.prototype,"queryRoles",void 0),p([h.Action,f("design:type",Object)],b.prototype,"deleteUsers",void 0),p([h.Action,f("design:type",Object)],b.prototype,"saveUser",void 0),p([h.Action,f("design:type",Object)],b.prototype,"checkUsernameAvailability",void 0),p([h.Action,f("design:type",Object)],b.prototype,"checkEmailAvailability",void 0),p([Object(d["b"])(),f("design:type","function"===typeof(i="undefined"!==typeof c["default"]&&c["default"])?i:Object)],b.prototype,"user",void 0),p([Object(d["c"])("username"),f("design:type",Object)],b.prototype,"username",void 0),p([Object(d["c"])("email"),f("design:type",Object)],b.prototype,"email",void 0),p([Object(d["e"])("user.id"),f("design:type",Function),f("design:paramtypes",[]),f("design:returntype",void 0)],b.prototype,"onUserChange",null),b=p([Object(d["a"])({components:{DeleteUserConfirm:u["a"]}})],b);var y=b,v=y,g=s("2877"),w=s("27f9"),j=s("0016"),U=s("ddd8"),O=s("9c40"),N=s("eebe"),k=s.n(N),q=Object(g["a"])(v,n,l,!1,null,null,null),R=q.exports;k()(q,"components",{QInput:w["a"],QIcon:j["a"],QSelect:U["a"],QBtn:O["a"]});var D=function(e,t,s,i){var a,r=arguments.length,n=r<3?t:null===i?i=Object.getOwnPropertyDescriptor(t,s):i;if("object"===typeof Reflect&&"function"===typeof Reflect.decorate)n=Reflect.decorate(e,t,s,i);else for(var l=e.length-1;l>=0;l--)(a=e[l])&&(n=(r<3?a(n):r>3?a(t,s,n):a(t,s))||n);return r>3&&n&&Object.defineProperty(t,s,n),n},x=function(e,t){if("object"===typeof Reflect&&"function"===typeof Reflect.metadata)return Reflect.metadata(e,t)};const $=Object(m["a"])("User");let _=class extends d["d"]{constructor(){super(...arguments),this.user=this.getNewUser(),this.usernameBackup=""}async created(){this.id&&(this.user=await this.fetchUserById(this.id),this.usernameBackup=this.user.username)}getNewUser(){return new class{constructor(){this.username="",this.firstName="",this.lastName="",this.displayNameFormat="username",this.email="",this.userRoles=[]}}}};D([$.Action,x("design:type",Object)],_.prototype,"fetchUserById",void 0),D([Object(d["b"])(),x("design:type",Number)],_.prototype,"id",void 0),_=D([Object(d["a"])({components:{UserForm:R}})],_);var E=_,A=E,P=s("9989"),F=s("de5e"),S=s("65c6"),C=s("6ac5"),T=Object(g["a"])(A,a,r,!1,null,null,null);t["default"]=T.exports;k()(T,"components",{QPage:P["a"],QPageSticky:F["a"],QToolbar:S["a"],QToolbarTitle:C["a"]})}}]);