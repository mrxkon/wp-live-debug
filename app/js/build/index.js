/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./app/js/src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./app/js/src/app.js":
/*!***************************!*\
  !*** ./app/js/src/app.js ***!
  \***************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/helpers/slicedToArray */ "./node_modules/@babel/runtime/helpers/slicedToArray.js");
/* harmony import */ var _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _components_Header__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./components/Header */ "./app/js/src/components/Header.js");
/* harmony import */ var _components_Content__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./components/Content */ "./app/js/src/components/Content.js");



/**
 * WordPress dependencies.
 */

/**
 * Internal Dependencies.
 */



/**
 * Main.
 */

var App = function App() {
  /**
   * Since we're in an arrow function and using useState(),
   * altering the state will force a re-render making all of the
   * initial functions needed to re-run. To avoid this
   * we add an extra state keeping a "firstRun" to avoid unwanted
   * looping & re-runs of functions.
   */
  var _useState = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(true),
      _useState2 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState, 2),
      firstRun = _useState2[0],
      setfirstRun = _useState2[1]; // Initialize the debug states.


  var _useState3 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState4 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState3, 2),
      hasWPDebug = _useState4[0],
      setWPDebug = _useState4[1];

  var _useState5 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState6 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState5, 2),
      hasWPDebugLog = _useState6[0],
      setWPDebugLog = _useState6[1];

  var _useState7 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState8 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState7, 2),
      hasWPDebugDisplay = _useState8[0],
      setWPDebugDisplay = _useState8[1];

  var _useState9 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState10 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState9, 2),
      hasScriptDebug = _useState10[0],
      setScriptDebug = _useState10[1];

  var _useState11 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState12 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState11, 2),
      hasSaveQueries = _useState12[0],
      setSaveQueries = _useState12[1]; // Initialize the backups state.


  var _useState13 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState14 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState13, 2),
      hasManualBackup = _useState14[0],
      setHasManualBackup = _useState14[1];

  var _useState15 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState16 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState15, 2),
      hasAutoBackup = _useState16[0],
      setHasAutoBackup = _useState16[1]; // Initialize the auto refresh state.


  var _useState17 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(false),
      _useState18 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState17, 2),
      hasAutoRefresh = _useState18[0],
      setAutoRefresh = _useState18[1]; // Initialize a state for the loading spinner.


  var _useState19 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])('show-spinner'),
      _useState20 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState19, 2),
      loading = _useState20[0],
      setLoading = _useState20[1]; // Initialize the debug.log location state.


  var _useState21 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(''),
      _useState22 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState21, 2),
      debugLogLocation = _useState22[0],
      setDebugLogLocation = _useState22[1]; // Initialize the debug.log content state.


  var _useState23 = Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useState"])(''),
      _useState24 = _babel_runtime_helpers_slicedToArray__WEBPACK_IMPORTED_MODULE_0___default()(_useState23, 2),
      deubgLogContent = _useState24[0],
      setDebugLogContent = _useState24[1];
  /**
   * Check if wp-config.WPLD-auto.php exists.
   */


  var autoBackupExists = function autoBackupExists() {
    var request = new XMLHttpRequest();
    var url = wp_live_debug_globals.ajax_url;
    var nonce = wp_live_debug_globals.nonce;
    var action = 'wp-live-debug-check-auto-backup-json';
    request.open('POST', url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    request.onload = function () {
      if (this.status >= 200 && this.status < 400) {
        setHasAutoBackup(true);
      }
    };

    request.send('action=' + action + '&_ajax_nonce=' + nonce);
  };
  /**
   * Check if wp-config.WPLD-manual.php exists.
   */


  var manualBackupExists = function manualBackupExists() {
    var request = new XMLHttpRequest();
    var url = wp_live_debug_globals.ajax_url;
    var nonce = wp_live_debug_globals.nonce;
    var action = 'wp-live-debug-check-manual-backup-json';
    request.open('POST', url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    request.onload = function () {
      if (this.status >= 200 && this.status < 400) {
        var resp = JSON.parse(this.response);

        if (true === resp.success) {
          setHasManualBackup(true);
        }
      }
    };

    request.send('action=' + action + '&_ajax_nonce=' + nonce);
  };
  /**
   * See any of the constants are true and alter their state.
   */


  var isConstantTrue = function isConstantTrue() {
    var request = new XMLHttpRequest();
    var url = wp_live_debug_globals.ajax_url;
    var nonce = wp_live_debug_globals.nonce;
    var action = 'wp-live-debug-is-constant-true';
    request.open('POST', url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    request.onload = function () {
      if (this.status >= 200 && this.status < 400) {
        var resp = JSON.parse(this.response);

        if (true === resp.success) {
          resp.data.forEach(function (constant) {
            switch (constant) {
              case 'WP_DEBUG':
                setWPDebug(true);
                break;

              case 'WP_DEBUG_LOG':
                setWPDebugLog(true);
                break;

              case 'WP_DEBUG_DISPLAY':
                setWPDebugDisplay(true);
                break;

              case 'SCRIPT_DEBUG':
                setScriptDebug(true);
                break;

              case 'SAVEQUERIES':
                setSaveQueries(true);
                break;
            }
          });
          setLoading('hide-spinner');
        }
      }
    };

    request.send('action=' + action + '&_ajax_nonce=' + nonce);
  };
  /**
   * Scroll the LogViewer.
   */


  var scrollLogViewer = function scrollLogViewer() {
    var debugArea = document.getElementById('wp-live-debug-area');

    if (null !== debugArea) {
      debugArea.scrollTop = debugArea.scrollHeight;
    }
  };
  /**
   * Find debug.log location.
   */


  var findDebugLog = function findDebugLog() {
    var request = new XMLHttpRequest();
    var url = wp_live_debug_globals.ajax_url;
    var nonce = wp_live_debug_globals.nonce;
    var action = 'wp-live-debug-find-debug-log-json';
    request.open('POST', url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    request.onload = function () {
      if (this.status >= 200 && this.status < 400) {
        var resp = JSON.parse(this.response);
        setDebugLogLocation(resp.data.debuglog_path);
      }
    };

    request.send('action=' + action + '&_ajax_nonce=' + nonce);
  };
  /**
   * Read the debug.log.
   */


  var readDebugLog = function readDebugLog() {
    var request = new XMLHttpRequest();
    var url = wp_live_debug_globals.ajax_url;
    var nonce = wp_live_debug_globals.nonce;
    var action = 'wp-live-debug-read-debug-log';
    request.open('POST', url, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

    request.onload = function () {
      if (this.status >= 200 && this.status < 400) {
        setDebugLogContent(this.response);

        if (firstRun) {
          scrollLogViewer();
        }
      }
    };

    request.send('action=' + action + '&_ajax_nonce=' + nonce);
  };
  /**
   * Scroll the LogViewer on interval.
   */


  Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["useEffect"])(function () {
    var interval = setInterval(function () {
      if (true === hasAutoRefresh) {
        readDebugLog();
        scrollLogViewer();
      }
    }, 3000);
    return function () {
      return clearInterval(interval);
    };
  });
  /**
   * Backup Button Actions.
   *
   * @param {Object} e string Event handler.
   */

  var BackupActions = function BackupActions(e) {
    // Show the spinner.
    setLoading('show-spinner'); // If we're getting a backup.

    if (e.target.id === 'wp-live-debug-backup') {
      var request = new XMLHttpRequest();
      var url = wp_live_debug_globals.ajax_url;
      var nonce = wp_live_debug_globals.nonce;
      var action = 'wp-live-debug-create-backup';
      request.open('POST', url, true);
      request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

      request.onload = function () {
        if (this.status >= 200 && this.status < 400) {
          var resp = JSON.parse(this.response);

          if (true === resp.success) {
            setHasManualBackup(true);
            setLoading('hide-spinner');
          }
        }
      };

      request.send('action=' + action + '&_ajax_nonce=' + nonce); // Else restore the backup.
    } else {
      var _request = new XMLHttpRequest();

      var _url = wp_live_debug_globals.ajax_url;
      var _nonce = wp_live_debug_globals.nonce;
      var _action = 'wp-live-debug-restore-backup';

      _request.open('POST', _url, true);

      _request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;');

      _request.onload = function () {
        if (this.status >= 200 && this.status < 400) {
          var resp = JSON.parse(this.response);

          if (true === resp.success) {
            setHasManualBackup(false);
            setLoading('hide-spinner');
          }
        }
      };

      _request.send('action=' + _action + '&_ajax_nonce=' + _nonce);
    }
  };
  /**
   * Alter WP_DEBUG
   */


  var alterWPDebug = function alterWPDebug() {
    console.log('alterWPDebug');
  };
  /**
   * Alter WP_DEBUG_LOG
   */


  var alterWPDebugLog = function alterWPDebugLog() {
    console.log('alterWPDebugLog');
  };
  /**
   * Alter WP_DEBUG_DISPLAY
   */


  var alterWPDebugDisplay = function alterWPDebugDisplay() {
    console.log('alterWPDebugDisplay');
  };
  /**
   * Alter SCRIPT_DEBUG
   */


  var alterScriptDebug = function alterScriptDebug() {
    console.log('alterScriptDebug');
  };
  /**
   * Alter SAVEQUERIES
   */


  var alterSaveQueries = function alterSaveQueries() {
    console.log('alterSaveQueries');
  };
  /**
   * Alter Auto Refresh
   */


  var alterAutoRefresh = function alterAutoRefresh() {
    setLoading('show-spinner');

    if (false === hasAutoRefresh) {
      setAutoRefresh(true);
    } else {
      setAutoRefresh(false);
    }

    setLoading('hide-spinner');
  };
  /**
   * Now we utilize the "firstRun" state so we
   * can run our 1time functions and then set it
   * to false so this won't run again until a page refresh.
   */


  if (firstRun) {
    autoBackupExists();
    manualBackupExists();
    findDebugLog();
    isConstantTrue();
    readDebugLog();
    setfirstRun(false);
  }
  /**
   * Render the UI.
   */


  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_components_Header__WEBPACK_IMPORTED_MODULE_2__["default"], {
    BackupActions: BackupActions,
    hasManualBackup: hasManualBackup
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__["createElement"])(_components_Content__WEBPACK_IMPORTED_MODULE_3__["default"], {
    loading: loading,
    alterWPDebug: alterWPDebug,
    alterWPDebugLog: alterWPDebugLog,
    alterWPDebugDisplay: alterWPDebugDisplay,
    alterScriptDebug: alterScriptDebug,
    alterSaveQueries: alterSaveQueries,
    alterAutoRefresh: alterAutoRefresh,
    hasManualBackup: hasManualBackup,
    hasAutoBackup: hasAutoBackup,
    debugEnabled: hasWPDebug,
    debugLogLocation: debugLogLocation,
    debugLogEnabled: hasWPDebugLog,
    deubgLogContent: deubgLogContent,
    debugDisplayEnabled: hasWPDebugDisplay,
    scriptDebugEnabled: hasScriptDebug,
    saveQueriesEnabled: hasSaveQueries,
    autoRefreshEnabled: hasAutoRefresh
  }));
};

/* harmony default export */ __webpack_exports__["default"] = (App);

/***/ }),

/***/ "./app/js/src/components/Content.js":
/*!******************************************!*\
  !*** ./app/js/src/components/Content.js ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _LogViewer__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./LogViewer */ "./app/js/src/components/LogViewer.js");
/* harmony import */ var _Sidebar__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./Sidebar */ "./app/js/src/components/Sidebar.js");


/**
 * WordPress dependencies.
 */

/**
 * Internal dependencies.
 */



/**
 * Main.
 *
 * @param {Object} props
 */

var Content = function Content(props) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "content",
    role: "region",
    "aria-label": Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Log view', 'wp-live-debug'),
    tabIndex: "-1"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "main"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("h2", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Viewing:', 'wp-live-debug'), " ", props.debugLogLocation), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_LogViewer__WEBPACK_IMPORTED_MODULE_2__["default"], {
    deubgLogContent: props.deubgLogContent
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "sidebar"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_Sidebar__WEBPACK_IMPORTED_MODULE_3__["default"], {
    loading: props.loading,
    alterWPDebug: props.alterWPDebug,
    alterWPDebugLog: props.alterWPDebugLog,
    alterWPDebugDisplay: props.alterWPDebugDisplay,
    alterScriptDebug: props.alterScriptDebug,
    alterSaveQueries: props.alterSaveQueries,
    alterAutoRefresh: props.alterAutoRefresh,
    hasManualBackup: props.hasManualBackup,
    hasAutoBackup: props.hasAutoBackup,
    debugEnabled: props.debugEnabled,
    debugLogEnabled: props.debugLogEnabled,
    debugDisplayEnabled: props.debugDisplayEnabled,
    scriptDebugEnabled: props.scriptDebugEnabled,
    saveQueriesEnabled: props.saveQueriesEnabled,
    autoRefreshEnabled: props.autoRefreshEnabled
  }))));
};

/* harmony default export */ __webpack_exports__["default"] = (Content);

/***/ }),

/***/ "./app/js/src/components/Header.js":
/*!*****************************************!*\
  !*** ./app/js/src/components/Header.js ***!
  \*****************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);


/**
 * WordPress dependencies.
 */


/**
 * Main.
 *
 * @param {Object} props
 */

var Header = function Header(props) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "header",
    role: "region",
    "aria-label": Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('WP Live Debug Top Bar', 'wp-live-debug'),
    tabIndex: "-1"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "page-title"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("h1", {
    className: "header-title"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('WP Live Debug', 'wp-live-debug'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
    className: "backup-restore"
  }, props.hasManualBackup ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["Button"], {
    id: "wp-live-debug-restore",
    isPrimary: true,
    onClick: props.BackupActions
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Restore wp-config', 'wp-live-debug')) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["Button"], {
    id: "wp-live-debug-backup",
    isPrimary: true,
    onClick: props.BackupActions
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Backup wp-config', 'wp-live-debug')))));
};

/* harmony default export */ __webpack_exports__["default"] = (Header);

/***/ }),

/***/ "./app/js/src/components/LogViewer.js":
/*!********************************************!*\
  !*** ./app/js/src/components/LogViewer.js ***!
  \********************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);


/**
 * Main.
 *
 * @param {Object} props
 */
var LogViewer = function LogViewer(props) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("textarea", {
    id: "wp-live-debug-area",
    name: "wp-live-debug-area",
    spellCheck: "false",
    value: props.deubgLogContent
  });
};

/* harmony default export */ __webpack_exports__["default"] = (LogViewer);

/***/ }),

/***/ "./app/js/src/components/Sidebar.js":
/*!******************************************!*\
  !*** ./app/js/src/components/Sidebar.js ***!
  \******************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);


/**
 * WordPress dependencies.
 */


/**
 * Main.
 *
 * @param {Object} props
 */

var Sidebar = function Sidebar(props) {
  return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["Panel"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelHeader"], {
    label: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Settings & Information', 'wp-live-debug')
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelBody"], {
    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Constants Settings', 'wp-live-debug'),
    initialOpen: true,
    className: props.loading,
    icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["Spinner"], null)
  }, props.hasManualBackup ? Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
    htmlFor: "alter-wp-debug",
    className: "components-toggle-control__label"
  }, "WP_DEBUG"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["FormToggle"], {
    id: "alter-wp-debug",
    checked: props.debugEnabled,
    onClick: props.alterWPDebug
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
    htmlFor: "alter-wp-debug-log",
    className: "components-toggle-control__label"
  }, "WP_DEBUG_LOG"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["FormToggle"], {
    id: "alter-wp-debug-log",
    checked: props.debugLogEnabled,
    onClick: props.alterWPDebugLog
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
    htmlFor: "alter-wp-debug-display",
    className: "components-toggle-control__label"
  }, "WP_DEBUG_DISPLAY"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["FormToggle"], {
    id: "alter-wp-debug-display",
    checked: props.debugDisplayEnabled,
    onClick: props.alterWPDebugDisplay
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
    htmlFor: "alter-wp-script-debug",
    className: "components-toggle-control__label"
  }, "SCRIPT_DEBUG"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["FormToggle"], {
    id: "alter-wp-script-debug",
    checked: props.scriptDebugEnabled,
    onClick: props.alterScriptDebug
  })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
    htmlFor: "alter-wp-savequeries",
    className: "components-toggle-control__label"
  }, "SAVEQUERIES"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["FormToggle"], {
    id: "alter-wp-savequeries",
    checked: props.saveQueriesEnabled,
    onClick: props.alterSaveQueries
  }))) : Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Backup wp-config for more settings!', 'wp-live-debug'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("label", {
    htmlFor: "alterAutoRefresh",
    className: "components-toggle-control__label"
  }, "Auto Refresh"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["FormToggle"], {
    id: "alterAutoRefresh",
    checked: props.autoRefreshEnabled,
    onClick: props.alterAutoRefresh
  })))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["Panel"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelBody"], {
    title: Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('More Information', 'wp-live-debug'),
    initialOpen: false
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('You will find extra wp-config.php backups in your WordPress root directory as:', 'wp-live-debug'))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", null, props.hasAutoBackup && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["Fragment"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("strong", null, "wp-config.WPLD-auto.php "), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("br", null)), props.hasManualBackup && Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("strong", null, "wp-config.WPLD-manual.php"))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__["PanelRow"], null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("span", null, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('For more information you can visit', 'wp-live-debug'), " ", Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("a", {
    target: "_blank",
    rel: "noopener noreferrer",
    href: "https://wordpress.org/support/article/debugging-in-wordpress/"
  }, Object(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__["__"])('Debugging in WordPress', 'wp-live-debug')), ".")))));
};

/* harmony default export */ __webpack_exports__["default"] = (Sidebar);

/***/ }),

/***/ "./app/js/src/index.js":
/*!*****************************!*\
  !*** ./app/js/src/index.js ***!
  \*****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _app__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./app */ "./app/js/src/app.js");


/**
 * WordPress dependencies.
 */

/**
 * Internal dependencies.
 */


/**
 * Render the WP Live Debug screen.
 */

Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["render"])(Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(_app__WEBPACK_IMPORTED_MODULE_1__["default"], null), document.getElementById('wpld-page'));

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js":
/*!***************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/arrayWithHoles.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

module.exports = _arrayWithHoles;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js":
/*!*********************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js ***!
  \*********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _iterableToArrayLimit(arr, i) {
  if (!(Symbol.iterator in Object(arr) || Object.prototype.toString.call(arr) === "[object Arguments]")) {
    return;
  }

  var _arr = [];
  var _n = true;
  var _d = false;
  var _e = undefined;

  try {
    for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);

      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }

  return _arr;
}

module.exports = _iterableToArrayLimit;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/nonIterableRest.js":
/*!****************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/nonIterableRest.js ***!
  \****************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance");
}

module.exports = _nonIterableRest;

/***/ }),

/***/ "./node_modules/@babel/runtime/helpers/slicedToArray.js":
/*!**************************************************************!*\
  !*** ./node_modules/@babel/runtime/helpers/slicedToArray.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

var arrayWithHoles = __webpack_require__(/*! ./arrayWithHoles */ "./node_modules/@babel/runtime/helpers/arrayWithHoles.js");

var iterableToArrayLimit = __webpack_require__(/*! ./iterableToArrayLimit */ "./node_modules/@babel/runtime/helpers/iterableToArrayLimit.js");

var nonIterableRest = __webpack_require__(/*! ./nonIterableRest */ "./node_modules/@babel/runtime/helpers/nonIterableRest.js");

function _slicedToArray(arr, i) {
  return arrayWithHoles(arr) || iterableToArrayLimit(arr, i) || nonIterableRest();
}

module.exports = _slicedToArray;

/***/ }),

/***/ "@wordpress/components":
/*!*********************************************!*\
  !*** external {"this":["wp","components"]} ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["components"]; }());

/***/ }),

/***/ "@wordpress/element":
/*!******************************************!*\
  !*** external {"this":["wp","element"]} ***!
  \******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["element"]; }());

/***/ }),

/***/ "@wordpress/i18n":
/*!***************************************!*\
  !*** external {"this":["wp","i18n"]} ***!
  \***************************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = this["wp"]["i18n"]; }());

/***/ })

/******/ });
//# sourceMappingURL=index.js.map