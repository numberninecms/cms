(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["adminbar"],{

/***/ "./assets/images/NumberNineWithoutText.png":
/*!*************************************************!*\
  !*** ./assets/images/NumberNineWithoutText.png ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = ("/bundles/numbernine/build/images/NumberNineWithoutText.c117dd5c.png");

/***/ }),

/***/ "./assets/scss/adminbar.scss":
/*!***********************************!*\
  !*** ./assets/scss/adminbar.scss ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

// extracted by mini-css-extract-plugin

/***/ }),

/***/ "./assets/ts/adminbar.ts":
/*!*******************************!*\
  !*** ./assets/ts/adminbar.ts ***!
  \*******************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var core_js_modules_es_object_define_property__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! core-js/modules/es.object.define-property */ "./node_modules/core-js/modules/es.object.define-property.js");
/* harmony import */ var core_js_modules_es_object_define_property__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(core_js_modules_es_object_define_property__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _scss_adminbar_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../scss/adminbar.scss */ "./assets/scss/adminbar.scss");
/* harmony import */ var _scss_adminbar_scss__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_scss_adminbar_scss__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _images_NumberNineWithoutText_png__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../images/NumberNineWithoutText.png */ "./assets/images/NumberNineWithoutText.png");


function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } }

function _createClass(Constructor, protoProps, staticProps) { if (protoProps) _defineProperties(Constructor.prototype, protoProps); if (staticProps) _defineProperties(Constructor, staticProps); return Constructor; }

/*
 * This file is part of the NumberNine package.
 *
 * (c) William Arin <williamarin.dev@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



var MenuBurgerButton = /*#__PURE__*/function () {
  function MenuBurgerButton(selector) {
    _classCallCheck(this, MenuBurgerButton);

    this.showMenu = false;
    this.DOMElement = document.querySelector(selector);

    if (this.DOMElement) {
      this.handle();
    }
  }

  _createClass(MenuBurgerButton, [{
    key: "handle",
    value: function handle() {
      var _this = this;

      this.DOMElement.addEventListener('click', function (e) {
        e.preventDefault();
        _this.showMenu = !_this.showMenu;
        var menu = document.querySelector('.n9-topbar-menu');

        if (menu) {
          menu.style.display = _this.showMenu ? 'block' : 'none';
        }
      });
    }
  }]);

  return MenuBurgerButton;
}();

new MenuBurgerButton('.n9-topbar-burger');

/***/ })

},[["./assets/ts/adminbar.ts","runtime","vendors~adminbar"]]]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hc3NldHMvaW1hZ2VzL051bWJlck5pbmVXaXRob3V0VGV4dC5wbmciLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL3Njc3MvYWRtaW5iYXIuc2NzcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvdHMvYWRtaW5iYXIudHMiXSwibmFtZXMiOltdLCJtYXBwaW5ncyI6Ijs7Ozs7Ozs7OztBQUFBO0FBQWUsb0lBQXFFLEU7Ozs7Ozs7Ozs7O0FDQXBGLHVDOzs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7Ozs7OztBQ0FBOzs7Ozs7O0FBT0c7QUFFSDtBQUNBOztJQUVNLGdCO0FBSUYsNEJBQW1CLFFBQW5CLEVBQW1DO0FBQUE7O0FBRjNCLG9CQUFXLEtBQVg7QUFHSixTQUFLLFVBQUwsR0FBa0IsUUFBUSxDQUFDLGFBQVQsQ0FBdUIsUUFBdkIsQ0FBbEI7O0FBRUEsUUFBSSxLQUFLLFVBQVQsRUFBcUI7QUFDakIsV0FBSyxNQUFMO0FBQ0g7QUFDSjs7Ozs2QkFFYTtBQUFBOztBQUNWLFdBQUssVUFBTCxDQUFpQixnQkFBakIsQ0FBa0MsT0FBbEMsRUFBMkMsVUFBQyxDQUFELEVBQU07QUFDN0MsU0FBQyxDQUFDLGNBQUY7QUFDQSxhQUFJLENBQUMsUUFBTCxHQUFnQixDQUFDLEtBQUksQ0FBQyxRQUF0QjtBQUVBLFlBQU0sSUFBSSxHQUF1QixRQUFRLENBQUMsYUFBVCxDQUF1QixpQkFBdkIsQ0FBakM7O0FBRUEsWUFBSSxJQUFKLEVBQVU7QUFDTixjQUFJLENBQUMsS0FBTCxDQUFXLE9BQVgsR0FBcUIsS0FBSSxDQUFDLFFBQUwsR0FBZ0IsT0FBaEIsR0FBMEIsTUFBL0M7QUFDSDtBQUNKLE9BVEQ7QUFVSDs7Ozs7O0FBR0wsSUFBSSxnQkFBSixDQUFxQixtQkFBckIsRSIsImZpbGUiOiJhZG1pbmJhci5qcyIsInNvdXJjZXNDb250ZW50IjpbImV4cG9ydCBkZWZhdWx0IFwiL2J1bmRsZXMvbnVtYmVybmluZS9idWlsZC9pbWFnZXMvTnVtYmVyTmluZVdpdGhvdXRUZXh0LmMxMTdkZDVjLnBuZ1wiOyIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpbiIsIi8qXG4gKiBUaGlzIGZpbGUgaXMgcGFydCBvZiB0aGUgTnVtYmVyTmluZSBwYWNrYWdlLlxuICpcbiAqIChjKSBXaWxsaWFtIEFyaW4gPHdpbGxpYW1hcmluLmRldkBnbWFpbC5jb20+XG4gKlxuICogRm9yIHRoZSBmdWxsIGNvcHlyaWdodCBhbmQgbGljZW5zZSBpbmZvcm1hdGlvbiwgcGxlYXNlIHZpZXcgdGhlIExJQ0VOU0VcbiAqIGZpbGUgdGhhdCB3YXMgZGlzdHJpYnV0ZWQgd2l0aCB0aGlzIHNvdXJjZSBjb2RlLlxuICovXG5cbmltcG9ydCAnLi4vc2Nzcy9hZG1pbmJhci5zY3NzJztcbmltcG9ydCAnLi4vaW1hZ2VzL051bWJlck5pbmVXaXRob3V0VGV4dC5wbmcnO1xuXG5jbGFzcyBNZW51QnVyZ2VyQnV0dG9uIHtcbiAgICBwcml2YXRlIHJlYWRvbmx5IERPTUVsZW1lbnQ6IEhUTUxFbGVtZW50IHwgbnVsbDtcbiAgICBwcml2YXRlIHNob3dNZW51ID0gZmFsc2U7XG5cbiAgICBwdWJsaWMgY29uc3RydWN0b3Ioc2VsZWN0b3I6IHN0cmluZykge1xuICAgICAgICB0aGlzLkRPTUVsZW1lbnQgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKHNlbGVjdG9yKTtcblxuICAgICAgICBpZiAodGhpcy5ET01FbGVtZW50KSB7XG4gICAgICAgICAgICB0aGlzLmhhbmRsZSgpO1xuICAgICAgICB9XG4gICAgfVxuXG4gICAgcHJpdmF0ZSBoYW5kbGUoKSB7XG4gICAgICAgIHRoaXMuRE9NRWxlbWVudCEuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZSkgPT4ge1xuICAgICAgICAgICAgZS5wcmV2ZW50RGVmYXVsdCgpO1xuICAgICAgICAgICAgdGhpcy5zaG93TWVudSA9ICF0aGlzLnNob3dNZW51O1xuXG4gICAgICAgICAgICBjb25zdCBtZW51OiBIVE1MRWxlbWVudCB8IG51bGwgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcubjktdG9wYmFyLW1lbnUnKTtcblxuICAgICAgICAgICAgaWYgKG1lbnUpIHtcbiAgICAgICAgICAgICAgICBtZW51LnN0eWxlLmRpc3BsYXkgPSB0aGlzLnNob3dNZW51ID8gJ2Jsb2NrJyA6ICdub25lJztcbiAgICAgICAgICAgIH1cbiAgICAgICAgfSk7XG4gICAgfVxufVxuXG5uZXcgTWVudUJ1cmdlckJ1dHRvbignLm45LXRvcGJhci1idXJnZXInKTtcbiJdLCJzb3VyY2VSb290IjoiIn0=