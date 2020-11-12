'use strict';

var insertionQ=function(){"use strict";function t(t,n){var e,u="insQ_"+i++,o=function(t){(t.animationName===u||t[c]===u)&&(r(t.target)||n(t.target))};e=document.createElement("style"),e.innerHTML="@"+f+"keyframes "+u+" {  from {  outline: 1px solid transparent  } to {  outline: 0px solid transparent }  }\n"+t+" { animation-duration: 0.001s; animation-name: "+u+"; "+f+"animation-duration: 0.001s; "+f+"animation-name: "+u+";  } ",document.head.appendChild(e);var a=setTimeout(function(){document.addEventListener("animationstart",o,!1),document.addEventListener("MSAnimationStart",o,!1),document.addEventListener("webkitAnimationStart",o,!1)},p.timeout);return{destroy:function(){clearTimeout(a),e&&(document.head.removeChild(e),e=null),document.removeEventListener("animationstart",o),document.removeEventListener("MSAnimationStart",o),document.removeEventListener("webkitAnimationStart",o)}}}function n(t){t.QinsQ=!0}function r(t){return p.strictlyNew&&t.QinsQ===!0}function e(t){return r(t.parentNode)?t:e(t.parentNode)}function u(t){for(n(t),t=t.firstChild;t;t=t.nextSibling)void 0!==t&&1===t.nodeType&&u(t)}function o(o,i){var a=[],c=function(){var t;return function(){clearTimeout(t),t=setTimeout(function(){a.forEach(u),i(a),a=[]},10)}}();return t(o,function(t){if(!r(t)){n(t);var u=e(t);a.indexOf(u)<0&&a.push(u),c()}})}var i=100,a=!1,c="animationName",f="",s="Webkit Moz O ms Khtml".split(" "),l="",h=document.createElement("div"),p={strictlyNew:!0,timeout:20};if(h.style.animationName&&(a=!0),a===!1)for(var v=0;v<s.length;v++)if(void 0!==h.style[s[v]+"AnimationName"]){l=s[v],c=l+"AnimationName",f="-"+l.toLowerCase()+"-",a=!0;break}var y=function(n){return a&&n.match(/[^{}]/)?(p.strictlyNew&&u(document.body),{every:function(r){return t(n,r)},summary:function(t){return o(n,t)}}):!1};return y.config=function(t){for(var n in t)t.hasOwnProperty(n)&&(p[n]=t[n])},y}();"undefined"!=typeof module&&"undefined"!=typeof module.exports&&(module.exports=insertionQ);

/*
 *
 * PayStand helpers
 * version 4.0
 * http://www.paystand.com/
 *
 */

var _ps = {
  "get": get,
  "set": set,
  "each": forEach,
  "isFunction": isFunction,
  "merge": merge,
  "clone": clone
};

var toString = Object.prototype.toString;

/**
 * clone
 * @param object
 * @returns {*}
 */
function clone(object) {
  var newObj = (object instanceof Array) ? [] : {};
  for (var key in object) {
    var value = object[key];
    if (value && typeof value === 'object') {
      newObj[key] = clone(value);
    } else newObj[key] = value
  }
  return newObj;
}
/**
 * get from path (only for objects)
 * @param object
 * @param path
 * @returns {undefined}
 */
function get(object, path) {
  var pathType = typeof path === 'string';
  if (isFunction(object) || object === null || object === undefined || !isObject(object) || !pathType) {
    return undefined;
  }

  var index = 0;
  var keys = path.split('.');
  var length = keys.length;
  var helper = Object.assign({}, object);

  while (helper !== null && helper !== undefined && index < length) {
    helper = helper[keys[index++]];
  }
  return (index && index === length) ? helper : undefined;
}

/**
 * Set values on objects
 * @param object
 * @param path
 * @param value
 * @returns {*}
 */
function set(object, path, value) {
  var pathType = typeof path === 'string';
  if (isFunction(object) || object === null || object === undefined || !isObject(object) || !pathType) {
    return object;
  }

  var index = 0;
  var keys = path.split('.');
  var length = keys.length;
  var helper = object;

  while (isObject(helper) && index < length - 1) {
    var key = keys[index++];
    helper = !helper[key] ? helper[key] = {} : helper[key];
  }
  helper[keys[length - 1]] = value;
  return object;
}

/**
 * forEach
 * @param array
 * @param callFunction
 * @returns {*}
 */
function forEach(array, callFunction) {
  var index = -1;
  var length = array.length;

  while (++index < length) {
    if (callFunction(array[index], index, array) === false) {
      break;
    }
  }
  return array;
}

/**
 * isObject
 * @param value
 * @returns {boolean}
 */
function isObject(value) {
  var type = typeof value;
  return value != null && (type == 'object' || type == 'function')
}

/**
 * is Function
 * @param value
 * @returns {boolean}
 */
function isFunction(value) {
  if (isObject(value)) {
    var tag = toString.call(value);
    return tag == '[object Function]' || tag == '[object AsyncFunction]' ||
      tag == '[object GeneratorFunction]' || tag == '[object Proxy]';
  }
  return false;
}

/**
 * Merge objects
 * @param object
 * @returns {*}
 */
function merge(object) {
  var index = 0;
  var length = arguments.length <= 1 ? 0 : arguments.length - 1;
  var newObject = object;

  if(length === 0) {
    newObject = mergeObject(newObject, arguments[0] || {});
  }
  else {
    while(index++ <= length) {
      newObject = mergeObject(newObject, arguments[index] || {});
    }
  }

  return newObject;
}

/**
 * merge 2 objects
 * @param weakObject
 * @param strongObject
 * @returns {{}}
 */
function mergeObject(weakObject, strongObject) {
  var keys = union(Object.keys(weakObject), Object.keys(strongObject));
  var newObject = {};
  forEach(keys, function (key) {
    var weakIsObject = isObject(weakObject[key]);
    var strongIsObject = isObject(strongObject[key]);
    if (weakObject[key] && strongObject[key] && weakIsObject && strongIsObject) {
      newObject[key] = mergeObject(weakObject[key], strongObject[key]);
    }
    else if (strongObject[key]) {
      newObject[key] = strongObject[key];
    }
    else {
      newObject[key] = weakObject[key];
    }
  });
  return newObject;
}

/**
 * union arrays
 * @param arrayOne
 * @param arrayTwo
 * @returns {Array}
 */
function union (arrayOne, arrayTwo) {
  var obj = {};
  for (var i = arrayOne.length-1; i >= 0; -- i)
    obj[arrayOne[i]] = arrayOne[i];
  for (var j = arrayTwo.length-1; j >= 0; -- j)
    obj[arrayTwo[j]] = arrayTwo[j];
  var res = [];
  for (var k in obj) {
    if (obj.hasOwnProperty(k)) {
      res.push(obj[k]);
    }
  }
  return res;
}

/*
 *
 * PayStand Checkout
 * version 4.0
 * http://www.paystand.com/
 *
 */

if (!PayStandCheckout) {

  var PayStandCheckout = PayStandCheckout || (function () {

    /**
     *
     *
     * Initialize variables
     *
     *
     */

      // establishes the checkout object
    var checkout = {};

    // a reference to the initializing script element
    checkout.script = null;

    // note: not sure this is used anymore
    checkout.config = {};

    // at times checkout needs to temporarily save configurations. this variable is that
    // temp storage.
    checkout.savedConfig = null;

    // the 'id' attribute for the iframe element
    checkout.iFrameID = "paystand_checkout_iframe";

    // the current mode (modal/embed) of the checkout instance
    checkout.mode = "modal";

    // various references to the iframe and its wrapper elements.
    checkout.container = null;
    checkout.frame = null;
    checkout.container = null;
    checkout.containerInner = null;
    checkout.containerClose = null;
    checkout.containerContent = null;

    // flag that is set to true when the checkout client has connected with the checkout
    // instance.
    checkout.isReady = false;

    // 'l' is an array that holds the list of current listener requests.
    // requests can be one-time (once) request or repeated requests (on).
    checkout.l = [];

    // holds various checkout 'state' attributes. note: not sure this is meaningfully used
    // at the moment.
    checkout.state = {
      "cssLoaded": false
    };

    // hold a reference to the current launched popup window/tab
    // note: there can only be one per checkoutJS instance.
    checkout.launchedWindow = undefined;

    // 'debug' determines if the checkout should output extra logs to the console
    checkout.debug = false;

    // tmp variable used to store complicated scenarios regarding onclick event
    checkout._nextMerge = null;

    // has ready been initialized
    checkout.initReady = false;

    // have listeners been set up
    checkout.listening = false;

    //
    //
    // Init / load
    //
    //

    /**
     * Init
     */
    checkout.init = function () {

      // initialize from the script tag
      checkout.initScript();

      // initialize all buttons
      checkout.initButtons();

      // initialize future elements as the appear in the DOM
      // this is needed to assure that the different arrangement of elements in the DOM
      // (ie script before or after buttons) can be supported.
      checkout.liveInit();

      return checkout;
    };

    /**
     * Init script
     */
    checkout.initScript = function () {

      // exit if checkout has already been initialized
      if (checkout.initialized) {
        return checkout;
      }

      // get script element
      var v1 = document.getElementById("paystand_checkout");
      var v2 = document.getElementById("ps_checkout");

      // get version
      if (v2) {
        checkout.version = "2.0";
      }
      else if (v1) {
        checkout.version = "1.0";
      }
      else {
        return checkout;
      }

      // determine script element
      var element = v2 || v1 || null;

      // save script element reference for later
      // note: the script tag is used as common data for all other elements
      // so we use the saved script element to merge its data with config reset
      // requests
      checkout.script = element;

      // exit if no element exists
      if (!element) {
        return checkout;
      }

      // set initialized flag as early as possible
      checkout.initialized = true;

      // parse the script tag to generate a config
      var config = checkout.parseElement(element);

      // load the checkout instance
      checkout.loadInstance(config);

      // show if requested
      // note: this should probably only be supported for v1. not sure yet though
      // so leaving it for now
      if (config.show == "true") {
        checkout.showCheckout(config);
      }

      return checkout;
    };

    /**
     * Init buttons
     */
    checkout.initButtons = function () {

      // v2 buttons
      var v2 = document.getElementsByClassName("ps-button");

      // v1 buttons
      var v1 = document.getElementsByClassName("paystand-button");

      // only support one version (v2 is preferred over v1)
      // note: this should actually be locked down by version number
      checkout.buttons = v2 || v1 || [];

      // v2 buttons choosen
      if (v2.length > 0) {
        checkout.buttons = v2;
      }

      // v1 buttons choosen
      else {
        checkout.buttons = v1;
      }

      // initialize each button found
      _ps.each(checkout.buttons, checkout.initButton);

      return checkout;
    };

    /**
     * live init
     */
    checkout.liveInit = function () {

      // note: calling initButtons or initScript multiple times is not a problem
      // as they keep track of each button and each script that they attempt to load
      // to make sure they don't initialize buttons and scripts multiple times.

      // configure watcher library
      insertionQ.config({
        strictlyNew: false
      });

      //
      // v2 watchers
      //

      // v2 uses .ps-button attribute to define buttons
      insertionQ(".ps-button").every(function (element) {
        checkout.initButton(element);
      });

      // v2 uses #ps_checkout to define the init script
      insertionQ("#ps_checkout").every(function (element) {
        checkout.initScript();
      });

      // note: note sure this is supported anymore
      insertionQ("#ps_checkout_settings").every(function (element) {
        checkout.initScript();
      });

      //
      // v1 watchers
      //

      // v1 uses #paystand_checkout to define the init script
      insertionQ("#paystand_checkout").every(function (element) {
        checkout.initScript();
      });

      // v1 uses .paystand-button to define button elements
      insertionQ(".paystand-button").every(function (element) {
        checkout.initButton(element);
      });

      //
      // load watcher
      //

      // just in case, we watch for the DOM load and attempt to init buttons
      // we may have missed
      document.addEventListener("DOMContentLoaded", function () {
        checkout.initButtons();
      });

    };

    /**
     * Init button
     * @param button
     */
    checkout.initButton = function (button) {

      // only initialize once
      if (button.initialized) {
        return checkout;
      }

      // initialize element as soon as possible
      button.initialized = true;

      // handle onclick attributes on the button element
      checkout.handleOnclickAttribute(button);

      // add a click event listener to the button
      button.addEventListener("click", function () {

        // handle the button click
        checkout.buttonClick(button);

      });

      return checkout;
    };

    /**
     * handle onclick attribute
     * @param button
     */
    checkout.handleOnclickAttribute = function(button){

      // get onclick attribute
      var onclick = button.getAttribute("onclick");

      // nothing to do if no onclick found
      if (!onclick) {
        return checkout;
      }

      // save onclick value to a new attribute called "clickfn"
      // note: this will allow us to manually perform the onclick during
      // our normal click listener, allowing us more control over what happens
      button.setAttribute("clickfn", onclick);

      // remove the "onclick" attribute so it doesn't fire async to our code firing
      button.removeAttribute("onclick");

      // warn the developer that "onclick is not supported.
      console.warn("consider using 'ps-click' instead of 'onclick' to speed up checkout");

      return checkout;
    };

    /**
     * Button click
     * @param button
     */
    checkout.buttonClick = function (button) {

      // if the checkout is ready we should do the button click
      // action immediately
      if (checkout.isReady) {

        // perform the button click
        checkout.buttonClickAction(button);

      }

      // if the checkout is not ready but we are dealing with a mobile situation
      // in which we should perform a popup, then it makes sense that the checkout
      // is not ready since it wouldn't be ready until we performed the popup. So
      // we should perform the button click action immediately as it will handle
      // the window popup for us.
      else if (checkout.shouldPopup()) {

        // perform the button click
        checkout.buttonClickAction(button);

      }

      // if checkout is not ready and we don't need to popup then checkout must
      // still be trying to initialize. this can happen if a user clicks on a button
      // really quickly on page load, and checkout client hasn't been able to establish a
      // a connection to the instance.
      else {

        // set the button to loading
        checkout.buttonIsLoading(button, function () {

          // perform the button click
          checkout.buttonClickAction(button);

        });

      }

      return checkout;
    };

    /**
     * checkout button is loading
     * @param button
     * @param cb
     */
    checkout.buttonIsLoading = function (button, cb) {

      // get the current button text to save it temporarily
      var buttonText = button.innerHTML;

      // set the button text to a loading message
      button.innerHTML = "loading...";

      // set a watcher to detect when the checkout client is ready
      var handler = setInterval(function () {

        // detect when checkout is ready
        if (checkout.isReady) {

          // clear the watcher
          clearInterval(handler);

          // reset the initial button text
          button.innerHTML = buttonText;

          // call the callback function if provided
          if (cb) {
            cb();
          }

        }
      }, 10);

      return checkout;
    };

    /**
     * button click action
     * @param button
     */
    checkout.buttonClickAction = function (button) {

      // hold the config we will be generating
      var config = {};

      // get script config
      var scriptConfig = checkout.parseElement(checkout.script);

      // get button config
      var buttonConfig = checkout.parseElement(button, true);

      // handle possible ps click function
      var mergedConfig = _ps.merge({}, scriptConfig, buttonConfig);
      var clickFnHandled = checkout.clickFnHandler(button, mergedConfig);

      // exit if the click function was handled
      if (clickFnHandled) {
        return checkout;
      }

      // handle possible ps click function
      var psClickConfig = checkout.psClickHandler(button);

      // merge script, button, psClick in that order
      config = _ps.merge(config, scriptConfig, buttonConfig, psClickConfig);

      // run checkout with the computed config
      checkout.runCheckout(config);

      return checkout;
    };

    /**
     * ps click handler
     * @param button
     */
    checkout.psClickHandler = function (button) {

      // hold the possible ps-click function config
      var fnConfig = null;

      // get ps-click attribute
      var psclick = button.getAttribute("ps-click");

      // ps click function exists
      if (psclick) {
        try {
          fnConfig = eval(psclick);
        }
        catch(e){}
      }

      return fnConfig;
    };

    /**
     * click function handler
     * @param button
     */
    checkout.clickFnHandler = function (button, config) {

      // was the function handled
      var fnHandled = false;

      // get clickfn attribute
      var clickfn = button.getAttribute("clickfn");

      // clickfn exists
      if (clickfn) {
        try {

          // save to next merge variable
          // note: since the click function is almost always used to perform an update or reset, we keep track of the
          // current button config so that when the next update or reset is called, we can merge in the button
          // data. This is a dangerous assumpton of course. If a developer does not perform an update or reset
          // then this will impact whatever the next update or reset is. Hence why we prefer to not support the
          // onclick event going forward.
          checkout._nextMerge = config;

          // run the click function.
          // note: this can run arbitrary code. in practice it is almost always used to perform reset or updates
          // so we don't need to capture the return data.
          eval(clickfn);

          // we show checkout immediately. this is an aggressive move but its to make our current customer code
          // run correctly with their current implementations.
          checkout.showCheckout();

          // set another warning message.
          console.warn("The 'onclick' event handler is not supported as it can give inconsistent results. Please consider using the 'ps-click' attribute. During an 'onclick' event the checkout client will immediately display the checkout instance to avoid certain edge case problems. The 'onclick' event is known to not work as expected in all scenarios if the 'onclick' event does not perform an 'update' or 'reset' command.");

          // perform no further actions as we  are counting on the click fn to call 'update' or 'reset', thus
          // kicking off another config request cycle async to the current request. During that next cycle, the next
          // merge variable that we defined here will be merged with that request (if all goes well).
          fnHandled = true;

        }

          // silently fail on eval errors
        catch(e){}
      }

      return fnHandled;
    };

    /**
     * Load instance
     * @param config
     */
    checkout.loadInstance = function (config) {
      config = config || {};
      checkout
        .injectCss(config)
        .injectFaye(config)
        .setupListeners(config)
        .createCheckoutIframe(config)
        .hideCheckout(config)
        .injectCheckoutIframe(config)
      return checkout;
    };

    /**
     * Inject css
     */
    checkout.injectCss = function (config) {

      config = config || {};

      // get css link element
      var css_element = document.getElementById("ps_css");

      // exit if element was found since that means it has alrady been injected
      if (css_element) {
        return checkout;
      }

      // default css path
      var path = "js/paystand.checkout.css";

      // sandbox/live css path
      if (config.env == "sandbox" || config.env == "live" || !config.env) {
        path = "js/checkout.min.css";
      }

      // get full url path
      var url = checkout.getDomains(config).checkout + path;

      // create link element to inject
      var link = document.createElement('link');
      link.id = "ps_css";
      link.rel = "stylesheet";
      link.type = "text/css";
      link.href = url;

      // get head element
      var head = document.getElementsByTagName('head')[0];

      // inject link at the bottom of the head element
      head.appendChild(link);

      // verify when loaded
      checkout.checkCssLoaded(url, 1000);

      return checkout;
    };

    /**
     * Check that css has been loaded
     * @param url
     * @param tries
     */
    checkout.checkCssLoaded = function (url, tries) {

      // iterate over page stylesheets
      for (var i in document.styleSheets) {

        // get stylesheet href
        var href = document.styleSheets[i].href || "";

        // if the href is the same as the url then we found our injected css element
        if (href === url) {

          // mark tht the css has been loaded note: not sure this is actually used
          checkout.state.cssLoaded = true;

          // note: no clue what this is supposed to be doing
          // note: possibly able to be removed, but not sure
          if (_ps.get(checkout, "container.style.cssText")) {
            _ps.set(checkout, "container.style.cssText", null);
          }

          // trigger the client css loaded event
          checkout.trigger("checkoutEvent", {
            "from": "client",
            "response": {
              "event": {
                "object": "checkout",
                "type": "clientCssLoaded"
              }
            }
          });

        }
      }

      // if the css has not been loaded set a timer to retry
      if (!checkout.state.cssLoaded) {

        // retry after 50ms
        setTimeout(function () {
          checkout.checkCssLoaded(url, tries);
        }, 50);

      }

      return checkout;
    };

    /**
     * inject faye
     * @returns {{}}
     */
    checkout.injectFaye = function(config){

      config = config || {};

      // get faye element
      var faye_element = document.getElementById("ps_faye");

      // exit if faye element is found since that means its already been injected
      if (faye_element) {
        return checkout;
      }

      // set faye url
      var url = checkout.getDomains(config).api + "faye/client.js";

      // build script element
      var script = document.createElement('script');
      script.id = "ps_faye";
      script.type = "text/javascript";
      script.src = url;

      // get head element
      var head = document.getElementsByTagName('head')[0];

      // append script element to bottom of the head element
      head.appendChild(script);

      return checkout;
    }

    /**
     * Inject checkout (default to body)
     * @param elementId
     */
    checkout.injectCheckoutIframe = function (config) {

      // initialize the element
      var element = null;

      // attempt to get the container element id
      var containerId = _ps.get(config, "container.id") || _ps.get(config, "containerid");

      // if no container id was found set the element to the body
      if (!containerId) {
        element = document.body;
      }

      // we found a container id
      else {

        // get the container element
        var container = document.getElementById(containerId);

        // if the container element does not exist set the element to the body
        if (!container) {
          element = document.body;
        }

        // otherwise set the element to be the container
        else {
          element = container;
        }

      }

      // inject the container
      element.appendChild(checkout.container);

      return checkout;
    };

    /**
     * Create checkout
     */
    checkout.createCheckoutIframe = function (config) {
      checkout
        .createFrame(config)
        .createContainer(config)
        .injectIframe(config);
      return checkout;
    };

    /**
     * Create frame
     */
    checkout.createFrame = function (config) {
      var frame = document.createElement("iframe");
      frame.id = this.iFrameID;
      frame.width = "100%";
      frame.height = "100%";
      frame.frameBorder = 0;
      frame.allowTransparency = "true";
      frame.vspace = 0;
      frame.hspace = 0;
      frame.marginheight = 0;
      frame.marginwidth = 0;
      frame.src = checkout.getDomains(config).checkout + "index.html";
      checkout.frame = frame;
      return checkout;
    };

    /**
     * Create container
     */
    checkout.createContainer = function () {

      // elements
      var container = document.createElement('div');
      var containerInner = document.createElement('div');
      var containerClose = document.createElement('div');
      var containerContent = document.createElement('div');

      // classes
      container.className = "ps-checkout";
      containerInner.className = "ps-checkout-inner";
      containerClose.className = "ps-checkout-close";
      containerContent.className = "ps-checkout-content";

      // close
      containerClose.innerHTML = "&times;";
      containerClose.onclick = checkout.hideCheckout;

      // append
      containerContent.appendChild(containerClose);
      containerInner.appendChild(containerContent);
      container.appendChild(containerInner);

      // setters
      checkout.container = container;
      checkout.containerInner = containerInner;
      checkout.containerClose = containerClose;
      checkout.containerContent = containerContent;

      return checkout;
    };

    /**
     * Load container
     */
    checkout.injectIframe = function () {
      checkout.containerContent.appendChild(checkout.frame);
      return checkout;
    };

    /**
     * Launch checkout window
     */
    checkout.launchWindow = function (config) {

      // hide the checkout iframe just in case
      checkout.hideCheckout(true);

      // get the full checkout url
      var url = checkout.getDomains(config).checkout + "index.html?wsclientid=" + checkout.wsClientId + "&wscheckoutid=" + checkout.wsCheckoutId;

      // launch a new window and save its reference
      checkout.launchedWindow = window.open(url, "wsIds");

      // if the window launched make sure we focus to that window or tab
      if (checkout.launchedWindow) {
        checkout.launchedWindow.focus();
        checkout.launchedWindow.blur();
      }

      return checkout;
    };

    /**
     * Clear window
     */
    checkout.clearWindow = function () {

      // if we have a valid window instance attempt to close it
      if (checkout.launchedWindow && checkout.launchedWindow.close) {
        checkout.launchedWindow.close();
      }

      // set the window instance to null
      checkout.launchedWindow = null;

      return checkout;
    };

    /**
     * Hide checkout
     */
    checkout.hideCheckout = function (keepWindow) {

      // clear window
      if (!keepWindow) {
        checkout.clearWindow();
      }

      if(!checkout.container){
        return true;
      }

      // get classes
      var classes = _ps.get(checkout, "container.className");

      // remove visible class
      checkout.container.className = classes.replace(/ps-checkout-visible/g, "");

      // add hidden class
      var hiddenName = "ps-checkout-hidden";
      if (classes && classes.indexOf(hiddenName) == -1) {
        checkout.container.className = checkout.container.className + (checkout.container.className ? ' ' + hiddenName : hiddenName);
      }

      return checkout;
    };

    /**
     * Show checkout
     */
    checkout.showCheckout = function (config) {

      if (checkout.shouldPopup(config)) {
        return true;
      }

      // get class names
      var classes = _ps.get(checkout, "container.className");

      if(!checkout.container || !classes){
        return true;
      }

      // remove hidden class
      checkout.container.className = classes.replace(/ps-checkout-hidden/g, "");

      // add visible class
      var visibleName = "ps-checkout-visible";
      if (classes && classes.indexOf(visibleName) == -1) {
        checkout.container.className = checkout.container.className + (checkout.container.className ? ' ' + visibleName : visibleName);
      }
      return checkout;
    };

    /**
     * Get domain
     * @param config
     */
    checkout.getDomains = function (config) {
      config = config || {};
      var domains = {};
      switch (config.env) {
        case "info":
          domains.checkout = "https://checkout.paystand.info/v4/";
          domains.api = "https://api.paystand.info/v3/";
          domains.apiDomain = "https://api.paystand.info/";
          break;
        case "staging":
          domains.checkout = "https://checkout.paystand.us/v4/";
          domains.api = "https://api.paystand.us/v3/";
          domains.apiDomain = "https://api.paystand.us/";
          break;
        case "sandbox":
          domains.checkout = "https://checkout.paystand.co/v4/";
          domains.api = "https://api.paystand.co/v3/";
          domains.apiDomain = "https://api.paystand.co/";
          break;
        case "development":
          domains.checkout = "https://checkout.paystand.biz/v4/";
          domains.api = "https://api.paystand.biz/v3/";
          domains.apiDomain = "https://api.paystand.biz/";
          break;
        case "local":
          domains.checkout = "https://localhost:3002/";
          domains.api = "https://localhost:3001/api/v3/";
          domains.apiDomain = "https://localhost:3001/";
          break;
        case "local_http":
          domains.checkout = "http://localhost:3002/";
          domains.api = "https://localhost:3001/api/v3/";
          domains.apiDomain = "https://localhost:3001/";
          break;
        case "live":
        default:
          domains.checkout = "https://checkout.paystand.com/v4/";
          domains.api = "https://api.paystand.com/v3/";
          domains.apiDomain = "https://api.paystand.com/";
          break;
      }
      return domains;
    };

    /**
     * runCheckout
     * @param config
     */
    checkout.runCheckout = function (config) {

      // if we need to popup a window then initiate a mobile checkout
      if (checkout.shouldPopup(config)) {
        checkout.runMobile(config);
      }

      // other wise initiate a desktop checkout
      else {
        checkout.runDesktop(config);
      }

      return checkout;
    };

    /**
     * Run mobile
     */
    checkout.runMobile = function (config) {

      // save the current config for later
      // note: we use this to send to the faye socket listener once the window has been created
      // and we are ready to send the config
      checkout.mobileConfig = config;

      // launch a new window
      checkout.launchWindow(config);

    };

    /**
     * Run desktop
     * @param config
     * @param button
     */
    checkout.runDesktop = function (config) {

      // get the requested update type
      var updateType = config.updateType || config.updatetype || "reset";

      // modify the update type due to the _reset internal function
      // note: this was introduced as a result of backward compt. issues
      updateType = updateType == "reset" ? "_reset" : updateType;

      // call the update type method
      checkout[updateType](config)

      // show checkout once loading or loaded
      // note: not sure why we have two of these
        .onceLoading(function () {
          checkout.showCheckout(config);
        })
        .onceLoaded(function () {
          checkout.showCheckout(config);
        });

      return checkout;
    };

    /**
     * Parse elements
     * @param element
     */
    checkout.parseElement = function (element, ignoreAttributes) {

      var config = {};

      // iterate over the element attributes
      _ps.each(element.attributes, function (item) {

        // remove 'ps-' to get the attribute name
        var name = item.name.replace("ps-", "");

        // if the attribute was a 'ps-' element
        if (name != item.name) {

          // is this an abbreviation
          var abbreviationCheck = name.replace(/\./g, "");

          // if its not an abbreviation then save directly to the config
          if (name != abbreviationCheck) {
            _ps.set(config, [name], item.value);
          }

          // if it is an abbreviation then convert '.' to '-'
          else {
            _ps.set(config, name.replace(/\-/g, "."), item.value);
          }

        }

      });

      // set debug
      if (config && config.debug == "true") {
        checkout.debug = true;
      }
      else {
        checkout.debug = false;
      }

      // set loadstate
      if (checkout.loadstate == "ready") {
        config.loadstate = "ready";
      }

      return config;
    };

    /**
     * isVersion
     * @param number
     * @returns {boolean}
     */
    checkout.isVersion = function (number) {
      var isVersion = false;
      switch (number) {
        case 1:
          isVersion = checkout.version == "1.0";
          break;
        case 2:
          isVersion = checkout.version == "2.0";
          break;
      }
      return isVersion;
    };

    /**
     * shouldPopup
     * @returns {boolean}
     */
    checkout.shouldPopup = function (config) {
      var mode = config ? config.mode : checkout.mode;
      return checkout.isMobileApple() && mode != "embed";
    };

    /**
     * isMobileApple
     * @returns {boolean}
     */
    checkout.isMobileApple = function () {
      return checkout.isMobile("apple.device");
    };

    /**
     * Is mobile
     * @returns {boolean}
     */
    checkout.isMobile = function (device) {
      !function(e,i){var n=/iPhone/i,o=/iPod/i,t=/iPad/i,d=/(?=.*\bAndroid\b)(?=.*\bMobile\b)/i,s=/Android/i,b=/(?=.*\bAndroid\b)(?=.*\bSD4930UR\b)/i,r=/(?=.*\bAndroid\b)(?=.*\b(?:KFOT|KFTT|KFJWI|KFJWA|KFSOWI|KFTHWI|KFTHWA|KFAPWI|KFAPWA|KFARWI|KFASWI|KFSAWI|KFSAWA)\b)/i,h=/Windows Phone/i,p=/(?=.*\bWindows\b)(?=.*\bARM\b)/i,a=/BlackBerry/i,l=/BB10/i,f=/Opera Mini/i,u=/(CriOS|Chrome)(?=.*\bMobile\b)/i,c=/(?=.*\bFirefox\b)(?=.*\bMobile\b)/i,w=new RegExp("(?:Nexus 7|BNTV250|Kindle Fire|Silk|GT-P1000)","i"),A=function(e,i){return e.test(i)},v=function(e){var i=e||navigator.userAgent,v=i.split("[FBAN");return"undefined"!=typeof v[1]&&(i=v[0]),v=i.split("Twitter"),"undefined"!=typeof v[1]&&(i=v[0]),this.apple={phone:A(n,i),ipod:A(o,i),tablet:!A(n,i)&&A(t,i),device:A(n,i)||A(o,i)||A(t,i)},this.amazon={phone:A(b,i),tablet:!A(b,i)&&A(r,i),device:A(b,i)||A(r,i)},this.android={phone:A(b,i)||A(d,i),tablet:!A(b,i)&&!A(d,i)&&(A(r,i)||A(s,i)),device:A(b,i)||A(r,i)||A(d,i)||A(s,i)},this.windows={phone:A(h,i),tablet:A(p,i),device:A(h,i)||A(p,i)},this.other={blackberry:A(a,i),blackberry10:A(l,i),opera:A(f,i),firefox:A(c,i),chrome:A(u,i),device:A(a,i)||A(l,i)||A(f,i)||A(c,i)||A(u,i)},this.seven_inch=A(w,i),this.any=this.apple.device||this.android.device||this.windows.device||this.other.device||this.seven_inch,this.phone=this.apple.phone||this.android.phone||this.windows.phone,this.tablet=this.apple.tablet||this.android.tablet||this.windows.tablet,"undefined"==typeof window?this:void 0},F=function(){var e=new v;return e.Class=v,e};"undefined"!=typeof module&&module.exports&&"undefined"==typeof window?module.exports=v:"undefined"!=typeof module&&module.exports&&"undefined"!=typeof window?module.exports=F():"function"==typeof define&&define.amd?define("_isMobile",[],i._isMobile=F()):i._isMobile=F()}(this,checkout);
      return !!(device ? _ps.get(checkout._isMobile, device) : checkout._isMobile.any);
    };

    /**
     * Log
     * @param pre
     * @param msg
     */
    checkout.log = function (pre, msg) {
      if (checkout.debug) {
        console.log("ps_checkout >> " + pre, msg);
      }
      return checkout;
    };

    /**
     * Update dimensions
     */
    checkout.dimensions = function (width, height) {
      width = width || "400px";
      height = height || "700px";
      if (checkout.containerContent) {
        checkout.containerContent.style.height = height;
        checkout.containerContent.style.width = width;
      }
      return checkout;
    };

    /**
     * Update mode
     */
    checkout.updateMode = function (mode) {

      mode = mode || checkout.mode;

      // get classes
      var classes = _ps.get(checkout, "container.className");

      if (!checkout.container || !classes) {
        return true;
      }

      // get current mode
      var currentMode = null;
      var list = _ps.get(checkout, "container.classList");
      _ps.each(list, function (item) {
        if (item.indexOf("ps-mode-") > -1) {
          currentMode = item;
        }
      });

      // set class name
      var modeName = "ps-mode-" + mode;
      if (currentMode != modeName) {
        checkout.container.className = classes.replace(currentMode, "");
      }
      if (classes.indexOf(modeName) == -1) {
        checkout.container.className = checkout.container.className + (checkout.container.className ? ' ' + modeName : modeName);
      }

      checkout.mode = mode;

      return checkout;
    };

    /**
     * Embed
     * @returns {{}}
     */
    checkout.embed = function () {
      checkout.mode = "embed";
      checkout.updateMode();
      return checkout;
    };

    /**
     * Modal
     * @returns {{}}
     */
    checkout.modal = function () {
      checkout.mode = "modal";
      checkout.updateMode();
      return checkout;
    };

    /**
     *
     *
     * Actions API
     *
     *
     */

    /**
     * live update
     * @param config
     */
    checkout.liveUpdate = function (config) {

      // signal that we only want to update, not reboot
      // note: checkout instance uses this flag to circumvent the reboot process early on and instead
      // it uses a custom process that only updates the values sent.
      config.updateType = "updateOnly";

      // update
      checkout._update(config);

      return checkout;
    };

    /**
     * reboot checkout
     * @param config
     * @param addScript
     */
    checkout.reboot = function (config, addScript) {
      return checkout._reset(config, addScript);
    }

    /**
     * Reset
     * Start all over. Reboot. Remove everything, then update.
     * Use for a fresh start
     * @param config
     * @param addScript
     */
    checkout._reset = function (config, addScript) {

      config = config || {};

      // merge the init script by default
      if (addScript !== false) {

        // get the script config
        var scriptConfig = checkout.parseElement(checkout.script);

        // merge the script with any potential pending merges and the config
        config = _ps.merge({}, scriptConfig, checkout._nextMerge || {}, config);

        // remove any cached merges from future use
        // note: this is usually used to support deprecated 'onclick' feature
        checkout._nextMerge = null;

      }

      // set checkout settings
      config.discard = "all";
      config.merge = "all";
      config.updateType = "reset";
      config.viewClose = config.viewClose || config.viewclose || "show";

      // save as last config generated
      checkout.savedConfig = config;

      // perform the update
      checkout._update(config);

      return checkout;
    };

    /**
     * update checkout instance
     *
     * note: this is the raw-ist update call. this will post to checkout and update the checkout chrome.
     * @param config
     * @returns {{}}
     * @private
     */
    checkout._update = function (config) {
      config = config || {};
      checkout.postMessage(config);
      checkout.dimensions(config.width, config.height);
      checkout.updateMode(config.mode || "modal");
      checkout.isLoading = false;
      checkout.isLoaded = false;
      return checkout;
    };

    /**
     * deprecated actions api handler
     * @param config
     */
    checkout.deprecatedActionsApiHandler = function (config) {

      // note: honestly, most of this method is a mystery to me but it was created
      // to handle various current customer situations.

      // is there a merge cache hit
      if (checkout._nextMerge) {

        // run checkout with merged + config
        checkout._nextMerge = _ps.merge(checkout._nextMerge, config);
        return checkout.runCheckout(checkout._nextMerge);

      }

      // are we in a popup situation and not ready yet (ie. no window)
      else if (checkout.shouldPopup() && !checkout.isReady) {

        // get script config
        var scriptConfig = checkout.parseElement(checkout.script);

        // run checkout with script config + config
        scriptConfig = _ps.merge(scriptConfig, config);
        return checkout.runCheckout(scriptConfig);

      }

      // otherwise just reboot with the given config
      checkout.reboot(config);

      return checkout;
    };

    // set deprecated methods to a new handler
    checkout.update = checkout.reset = checkout.deprecatedActionsApiHandler;

    /**
     *
     *
     * Listener API
     *
     *
     */

    /**
     * On
     * @param event
     * @param type
     * @param cb
     */
    checkout.on = function (event, type, cb) {
      checkout.l.push({
        "event": event,
        "type": type,
        "cb": cb,
        "once": false
      });
      return checkout;
    };

    /**
     * Once
     * @param event
     * @param type
     * @param cb
     */
    checkout.once = function (event, type, cb) {
      if (event == "event" && type == "checkoutLoaded") {
        return checkout.onceLoaded(cb);
      }
      else {
        return checkout._once(event, type, cb);
      }
    };

    /**
     * _once
     * @param event
     * @param type
     * @param cb
     */
    checkout._once = function (event, type, cb) {
      checkout.l.push({
        "event": event,
        "type": type,
        "cb": cb,
        "once": true
      });
      return checkout;
    };

    /**
     * Is
     * @param key
     * @param success
     * @param failure
     * @returns {{}}
     */
    checkout.is = function (key, success, failure) {
      var is = !!checkout.state[key];
      if (is && success) {
        success();
      }
      if (!is && failure) {
        failure();
      }
      return checkout;
    };

    /**
     * Action
     */
    checkout.action = function (commands) {
      checkout.reboot({
        "actions": commands || []
      });
      return checkout;
    };

    /**
     * On helper
     * @param event
     * @param type
     * @param data
     */
    checkout._on = function (event, type, data) {
      var list = _ps.clone(checkout.l);
      _ps.each(list, function (item, key) {
        if (item.event == event && item.type == type) {
          if (item.cb) {
            item.cb(data)
          }
          if (item.once) {
            checkout.l.splice(key, 1);
          }
        }
      });
      return checkout;
    };

    /**
     * On load
     * @param cb
     */
    checkout.onLoaded = function (cb) {
      if (checkout.isLoaded) {
        cb();
      }
      checkout.on("event", "checkoutLoaded", cb);
      return checkout;
    };

    /**
     * On load
     * @param cb
     */
    checkout.onceLoaded = function (cb) {
      if (checkout.isLoaded || checkout.isReady) {
        cb();
      }
      else {
        checkout._once("event", "checkoutLoaded", cb);
      }
      return checkout;
    };

    /**
     * On loading
     * @param cb
     */
    checkout.onLoading = function (cb) {
      if (checkout.isLoading) {
        cb()
      }
      checkout.on("event", "checkoutLoading", cb);
      return checkout;
    };

    /**
     * Once loading
     * @param cb
     */
    checkout.onceLoading = function (cb) {
      if (checkout.isLoading) {
        cb()
      }
      checkout._once("event", "checkoutLoading", cb);
      return checkout;
    };

    /**
     * On ready
     */
    checkout.onReady = function (cb) {
      checkout.readyImplemented = cb;
      if (!checkout.isReady) {
        checkout.ready();
      }
      return checkout;
    };

    /**
     * On complete
     * @param cb
     */
    checkout.onComplete = function (cb) {
      checkout.on("flow", "flowComplete", cb);
      return checkout;
    };

    /**
     * Once complete
     * @param cb
     */
    checkout.onceComplete = function (cb) {
      checkout.once("flow", "flowComplete", cb);
      return checkout;
    };

    /**
     * On error
     * @param cb
     */
    checkout.onError = function (cb) {
      checkout.on("event", "error", cb);
      return checkout;
    }

    /**
     * On failed
     * @param cb
     */
    checkout.onFailed = function (cb) {
      // todo: have a flow failure to listen to
      // checkout.on("event", "error", cb);
      return checkout;
    }

    /**
     * Once failed
     * @param cb
     */
    checkout.onceFailed = function (cb) {
      // todo: have a flow failure to listen to
      //checkout.once("event", "error", cb);
      return checkout;
    }

    /**
     * Checkout ready default
     */
    checkout.readyDefault = function (useConfig) {

      // show the checkout early (with the loading bar)
      var loaderShow = false;

      // config to send
      var sendConfig = {};

      // handle v2 auto loading
      if (checkout.isVersion(2)) {

        // get the v2 auto-loader element
        var loader = document.getElementById("ps_checkout_load");

        // the loader element exists
        if (loader) {

          // parse the script element
          var scriptConfig = checkout.parseElement(checkout.script);

          // parse the loader element
          var loaderConfig = checkout.parseElement(loader, true);

          // merge the script and loader configs
          sendConfig = _ps.merge(sendConfig, scriptConfig, loaderConfig);

          // set a flag to remind us to show the loader
          loaderShow = true;

        }

        // no loader
        else {

          // parse the script config
          sendConfig = checkout.parseElement(checkout.script);

          // set the load state to ready
          checkout.loadstate = "ready";

        }

        // reboot checkout
        checkout.reboot(useConfig || sendConfig);

        // show checkout as early as possible
        if (loaderShow) {
          checkout.showCheckout();
        }

      }

      // handle v1
      else {

        // parse the script element
        var scriptConfig = checkout.parseElement(checkout.script);

        // reboot with the script config
        checkout.reboot(useConfig || checkout.savedConfig || scriptConfig);

      }

      return checkout;
    };

    /**
     * Ready
     */
    checkout.ready = function (useConfig) {

      checkout.isReady = true;

      // always call the default ready
      checkout.readyDefault(useConfig);

      // call user defined ready
      if (_ps.isFunction(checkout.readyImplemented)) {
        checkout.readyImplemented();
      }

      return checkout;
    };

    /**
     * Checkout event: internal
     * @param data
     */
    checkout.checkoutEventInternal = function (data) {

      // get the event type
      var eventType = _ps.get(data, "response.event.type");

      switch (eventType) {

        // set loading flags
        case "checkoutLoading":
          checkout.isLoading = true;
          checkout.isLoaded = false;
          break;

        // set loaded flags
        case "checkoutLoaded":
          checkout.isLoading = false;
          checkout.isLoaded = true;
          break;

      }

      // trigger events listeners
      checkout._on("event", eventType, data);

      return checkout;
    };

    /**
     * Checkout action: internal
     * @param data
     */
    checkout.checkoutActionInternal = function (data) {

      // get action
      var action = _ps.get(data, "response.action");

      // trigger action listeners
      checkout._on("action", action, data);

      switch (action) {

        // close checkout actions
        case "closeModal":
        case "closeDialog":
          checkout.hideCheckout();
          break;

      }
      return checkout;
    };

    /**
     * Checkout flow: internal
     * @param data
     */
    checkout.checkoutFlowInternal = function (data) {
      var eventType = _ps.get(data, "response.type");
      checkout._on("flow", eventType, data);
      return checkout;
    };

    /**
     * Checkout event: default
     * @param data
     */
    checkout.checkoutEventDefault = function (data) {
      checkout.log("checkout event:", data);
      checkout.log("checkout event >> details >> " + _ps.get(data, "response.event.type"), _ps.get(data, "response.event.data"));
      return checkout;
    };

    /**
     * Checkout action: default
     * @param data
     */
    checkout.checkoutActionDefault = function (data) {
      checkout.log("checkout action:", data);
      return checkout;
    };

    /**
     * Checkout action: default
     * @param data
     */
    checkout.checkoutUpdatedDefault = function (data) {
      checkout.log("checkout updated:", data);
      return checkout;
    };

    /**
     * Checkout action: default
     * @param data
     */
    checkout.checkoutFlowDefault = function (data) {
      checkout.log("checkout flow:", data);
      return checkout;
    };

    /**
     * Implement checkout.checkoutEvent
     */
    checkout.checkoutEvent = checkout.checkoutEvent || checkout.checkoutEventDefault;

    /**
     * Implement checkout.checkoutAction
     */
    checkout.checkoutAction = checkout.checkoutAction || checkout.checkoutActionDefault;

    /**
     * Implement checkout.checkoutUpdated
     */
    checkout.checkoutUpdated = checkout.checkoutUpdated || checkout.checkoutUpdatedDefault;

    /**
     * Implement checkout.checkoutFlow
     */
    checkout.checkoutFlow = checkout.checkoutFlow || checkout.checkoutFlowDefault;

    /**
     * Listen for
     */
    checkout.listenFor = checkout.listenFor || function (eventName, responseFunction) {
      checkout.callbackEvents = checkout.callbackEvents || {};
      checkout.callbackEvents[eventName] = checkout.callbackEvents[eventName] || [];
      checkout.callbackEvents[eventName].push(responseFunction);
    };

    /**
     * Checkout Event
     */
    checkout.listenFor("checkoutEvent", function (data) {
      checkout.checkoutEventInternal(data);
      checkout.checkoutEvent(data);
      return checkout;
    });

    /**
     * Checkout Action
     */
    checkout.listenFor("checkoutAction", function (data) {
      checkout.checkoutActionInternal(data);
      checkout.checkoutAction(data);
      return checkout;
    });

    /**
     * Checkout Action
     */
    checkout.listenFor("checkoutUpdated", function (data) {
      checkout.checkoutUpdated(data);
      return checkout;
    });

    /**
     * Checkout Action
     */
    checkout.listenFor("checkoutFlow", function (data) {
      checkout.checkoutFlowInternal(data);
      checkout.checkoutFlow(data);
      return checkout;
    });

    /**
     * Trigger
     * @param eventName
     * @param data
     */
    checkout.trigger = function (eventName, data) {
      var callbacks = checkout.callbackEvents[eventName];
      if (callbacks) {
        for (var i = 0; i < callbacks.length; i++) {
          var callback = callbacks[i];
          callback(data);
        }
      }
      return checkout;
    };

    /**
     * Receive Message
     */
    checkout.receiveMessage = function (data) {
      checkout.trigger(data.type, data);
      return checkout;
    };

    /**
     * Incoming message
     * @param msg
     */
    checkout.incomingMessage = function (msg) {

      // handle send configuration signale
      if (typeof msg !== 'undefined' && msg.data === "sendConfiguration") {
        checkout.ready();
      }

      // handle all other messages
      else {
        checkout.receiveMessage(msg.data);
      }

      return checkout;
    };

    /**
     * Post message
     * @param msg
     */
    checkout.postMessage = function (msg) {

      // add the checkout version
      msg.checkoutVersion = checkout.version;

      // setup ready flags
      if (!checkout.initReady) {
        checkout.initReady = true;
      }
      else {
        msg.loadstate = null;
      }

      // if in popup mode, send to socket
      if (checkout.shouldPopup(msg)) {
        checkout.postToSocket(msg);
      }

      // otherwise send to iframe
      else {

        // get the frame id
        var frame = document.getElementById(checkout.iFrameID);

        // found frame
        if (frame) {

          // send to frame
          frame.contentWindow.postMessage(msg, "*");

        }

      }
      return checkout;
    };

    /**
     * Setup listeners
     */
    checkout.setupListeners = function (config) {

      // exit if we already set up listeners
      if (checkout.listening) {
        return checkout;
      }

      // we are listening
      checkout.listening = true;

      // setup socket listener
      checkout.setupSocketListener(config);

      // setup iframe listener
      window.addEventListener("message", checkout.incomingMessage);

      return checkout;
    };

    /**
     * setupWebsockets
     */
    checkout.setupSocketListener = function (config) {

      // generate ids
      checkout.updateWsIds();

      // wait for Faye to be avaialble
      // note: Faye is injected early on in the init process. once it is injected
      // Faye should be available.
      var handler = setInterval(function () {
        try {

          // if Faye is available
          if (Faye) {

            clearInterval(handler);

            // begin Faye setup
            setupFaye(config);

          }
        }
        catch(e){}

      }, 50);

      return checkout;
    }

    /**
     * setupFaye
     */
    function setupFaye (config) {

      // get api domain
      var domain = checkout.getDomains(config).api;

      // create a new faye client
      checkout.socketClient = new Faye.Client(domain + "faye");

      // disable websockets
      // note: we do this since we can't currently support websockets do to an old apache version
      checkout.socketClient.disable('websocket');

      // fire the loaded event
      // note: this is to grandfather old customer code
      if (!checkout.isLoaded && !checkout.isLoading && !checkout.isReady) {
        checkout._on("event", "checkoutLoaded", {});
      }

      // subscribe to listen for our checkout messages
      var subscription = checkout.socketClient.subscribe("/checkout/" + checkout.wsCheckoutId, function (message) {

        // exit if no message
        if (!message) {
          return true;
        }

        // handle messages
        switch (message.status) {

          // on ready
          case "checkoutReady":

            // call ready
            checkout.ready(checkout.mobileConfig);

            break;

          // handle all other messages
          default:
            checkout.receiveMessage(message.data);
            break;

        }
      });

      // on subscription
      subscription.then(function () {

        // let checkout know that we are ready as well
        checkout.socketClient.publish("/checkout/" + checkout.wsClientId, {
          "status": "clientReady"
        });

      });

      return checkout;
    };

    /**
     * updateWsIds
     */
    checkout.updateWsIds = function () {

      // generate checkout id or get from cache
      checkout.wsCheckoutId = localStorage.getItem("_psWsCheckoutId") || checkout.wsCheckoutId || checkout.guid();

      // generate client id or get from cache
      checkout.wsClientId = localStorage.getItem("_psWsClientId") || checkout.wsClientId || checkout.guid();

      // save to storage
      localStorage.setItem("_psWsCheckoutId", checkout.wsCheckoutId);
      localStorage.setItem("_psWsClientId", checkout.wsClientId);

      return checkout;
    };

    /**
     * post to socket
     * @param msg
     */
    checkout.postToSocket = function (msg) {
      checkout.socketClient.publish("/checkout/" + checkout.wsClientId, {
        "data": msg,
        "status": "message"
      });
    }

    /**
     * guid
     * @returns {string}
     */
    checkout.guid = function () {
      function s4() {
        return Math.floor((1 + Math.random()) * 0x10000)
          .toString(16)
          .substring(1);
      }

      return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
        s4() + '-' + s4() + s4() + s4();
    }

    // start the initialization process immediately
    checkout.init();

    return checkout;

  })();
  var psCheckout = PayStandCheckout;
}
