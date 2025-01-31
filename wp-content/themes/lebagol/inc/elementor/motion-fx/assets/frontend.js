/*! elementor-pro - v3.5.1 - 10-11-2021 */
(self["webpackChunkelementor_pro"] = self["webpackChunkelementor_pro"] || []).push([["frontend"],{

    /***/ "../node_modules/@babel/runtime/helpers/defineProperty.js":
    /*!****************************************************************!*\
      !*** ../node_modules/@babel/runtime/helpers/defineProperty.js ***!
      \****************************************************************/
    /***/ ((module) => {

        function _defineProperty(obj, key, value) {
            if (key in obj) {
                Object.defineProperty(obj, key, {
                    value: value,
                    enumerable: true,
                    configurable: true,
                    writable: true
                });
            } else {
                obj[key] = value;
            }

            return obj;
        }

        module.exports = _defineProperty;
        module.exports.default = module.exports, module.exports.__esModule = true;

        /***/ }),

    /***/ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js":
    /*!***********************************************************************!*\
      !*** ../node_modules/@babel/runtime/helpers/interopRequireDefault.js ***!
      \***********************************************************************/
    /***/ ((module) => {

        function _interopRequireDefault(obj) {
            return obj && obj.__esModule ? obj : {
                "default": obj
            };
        }

        module.exports = _interopRequireDefault;
        module.exports.default = module.exports, module.exports.__esModule = true;

        /***/ }),

    /***/ "../assets/dev/js/frontend/frontend.js":
    /*!*********************************************!*\
      !*** ../assets/dev/js/frontend/frontend.js ***!
      \*********************************************/
    /***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

        "use strict";


        var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

        __webpack_require__(/*! ../public-path */ "../assets/dev/js/public-path.js");

        var _frontend = _interopRequireDefault(__webpack_require__(/*! ../../../../modules/motion-fx/assets/js/frontend/frontend */ "../modules/motion-fx/assets/js/frontend/frontend.js"));

        var _frontend2 = _interopRequireDefault(__webpack_require__(/*! ../../../../modules/sticky/assets/js/frontend/frontend */ "../modules/sticky/assets/js/frontend/frontend.js"));

        var _frontend3 = _interopRequireDefault(__webpack_require__(/*! ../../../../modules/code-highlight/assets/js/frontend/frontend */ "../modules/code-highlight/assets/js/frontend/frontend.js"));

        var _frontend4 = _interopRequireDefault(__webpack_require__(/*! ../../../../modules/video-playlist/assets/js/frontend/frontend */ "../modules/video-playlist/assets/js/frontend/frontend.js"));

        var _frontend5 = _interopRequireDefault(__webpack_require__(/*! ../../../../modules/payments/assets/js/frontend/frontend */ "../modules/payments/assets/js/frontend/frontend.js"));

        var _frontend6 = _interopRequireDefault(__webpack_require__(/*! ../../../../modules/progress-tracker/assets/js/frontend/frontend */ "../modules/progress-tracker/assets/js/frontend/frontend.js"));

        class ElementorProFrontend extends elementorModules.ViewModule {
            onInit() {
                super.onInit();
                this.config = {};
                this.modules = {};
            }

            bindEvents() {
                jQuery(window).on('elementor/frontend/init', this.onElementorFrontendInit.bind(this));
            }

            initModules() {
                // Handlers that should be available by default for sections usage.
                let handlers = {
                    motionFX: _frontend.default,
                    sticky: _frontend2.default,
                    codeHighlight: _frontend3.default,
                    videoPlaylist: _frontend4.default,
                    payments: _frontend5.default,
                    progressTracker: _frontend6.default
                }; // Keep this line before applying filter on the handlers.

                elementorProFrontend.trigger('elementor-pro/modules/init:before');
                handlers = elementorFrontend.hooks.applyFilters('elementor-pro/frontend/handlers', handlers);
                jQuery.each(handlers, (moduleName, ModuleClass) => {
                    this.modules[moduleName] = new ModuleClass();
                }); // TODO: BC Since 2.9.0

                this.modules.linkActions = {
                    addAction: (...args) => {
                        elementorFrontend.utils.urlActions.addAction(...args);
                    }
                };
            }

            onElementorFrontendInit() {
                this.initModules();
            }

        }

        window.elementorProFrontend = new ElementorProFrontend();

        /***/ }),

    /***/ "../assets/dev/js/public-path.js":
    /*!***************************************!*\
      !*** ../assets/dev/js/public-path.js ***!
      \***************************************/
    /***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

        "use strict";


        /* eslint-disable camelcase */
        __webpack_require__.p = 'js/';

        /***/ }),

    /***/ "../modules/code-highlight/assets/js/frontend/frontend.js":
    /*!****************************************************************!*\
      !*** ../modules/code-highlight/assets/js/frontend/frontend.js ***!
      \****************************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        class _default extends elementorModules.Module {
            constructor() {
                super();
                elementorFrontend.elementsHandler.attachHandler('code-highlight', () => __webpack_require__.e(/*! import() | code-highlight */ "code-highlight").then(__webpack_require__.bind(__webpack_require__, /*! ./handler */ "../modules/code-highlight/assets/js/frontend/handler.js")));
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/motion-fx/assets/js/frontend/frontend.js":
    /*!***********************************************************!*\
      !*** ../modules/motion-fx/assets/js/frontend/frontend.js ***!
      \***********************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        var _handler = _interopRequireDefault(__webpack_require__(/*! ./handler */ "../modules/motion-fx/assets/js/frontend/handler.js"));

        class _default extends elementorModules.Module {
            constructor() {
                super();
                elementorFrontend.elementsHandler.attachHandler('global', _handler.default, null);
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/motion-fx/assets/js/frontend/handler.js":
    /*!**********************************************************!*\
      !*** ../modules/motion-fx/assets/js/frontend/handler.js ***!
      \**********************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        var _motionFx = _interopRequireDefault(__webpack_require__(/*! ./motion-fx/motion-fx */ "../modules/motion-fx/assets/js/frontend/motion-fx/motion-fx.js"));

        class _default extends elementorModules.frontend.handlers.Base {
            __construct(...args) {
                super.__construct(...args);

                this.toggle = elementorFrontend.debounce(this.toggle, 200);
            }

            getDefaultSettings() {
                return {
                    selectors: {
                        container: '.elementor-widget-container'
                    }
                };
            }

            getDefaultElements() {
                const selectors = this.getSettings('selectors');
                return {
                    $container: this.$element.find(selectors.container)
                };
            }

            bindEvents() {
                elementorFrontend.elements.$window.on('resize', this.toggle);
            }

            unbindEvents() {
                elementorFrontend.elements.$window.off('resize', this.toggle);
            }

            addCSSTransformEvents() {
                // Remove CSS transition variable that assigned from scroll.js in order to allow the transition of the CSS-Transform.
                const motionFxScrolling = this.getElementSettings('motion_fx_motion_fx_scrolling');

                if (motionFxScrolling && !this.isTransitionEventAdded) {
                    this.isTransitionEventAdded = true;
                    this.elements.$container.on('mouseenter', () => {
                        this.elements.$container.css('--e-transform-transition-duration', '');
                    });
                }
            }

            initEffects() {
                this.effects = {
                    translateY: {
                        interaction: 'scroll',
                        actions: ['translateY']
                    },
                    translateX: {
                        interaction: 'scroll',
                        actions: ['translateX']
                    },
                    rotateZ: {
                        interaction: 'scroll',
                        actions: ['rotateZ']
                    },
                    scale: {
                        interaction: 'scroll',
                        actions: ['scale']
                    },
                    opacity: {
                        interaction: 'scroll',
                        actions: ['opacity']
                    },
                    blur: {
                        interaction: 'scroll',
                        actions: ['blur']
                    },
                    mouseTrack: {
                        interaction: 'mouseMove',
                        actions: ['translateXY']
                    },
                    tilt: {
                        interaction: 'mouseMove',
                        actions: ['tilt']
                    }
                };
            }

            prepareOptions(name) {
                const elementSettings = this.getElementSettings(),
                    type = 'motion_fx' === name ? 'element' : 'background',
                    interactions = {};
                jQuery.each(elementSettings, (key, value) => {
                    const keyRegex = new RegExp('^' + name + '_(.+?)_effect'),
                        keyMatches = key.match(keyRegex);

                    if (!keyMatches || !value) {
                        return;
                    }

                    const options = {},
                        effectName = keyMatches[1];
                    jQuery.each(elementSettings, (subKey, subValue) => {
                        const subKeyRegex = new RegExp(name + '_' + effectName + '_(.+)'),
                            subKeyMatches = subKey.match(subKeyRegex);

                        if (!subKeyMatches) {
                            return;
                        }

                        const subFieldName = subKeyMatches[1];

                        if ('effect' === subFieldName) {
                            return;
                        }

                        if ('object' === typeof subValue) {
                            subValue = Object.keys(subValue.sizes).length ? subValue.sizes : subValue.size;
                        }

                        options[subKeyMatches[1]] = subValue;
                    });
                    const effect = this.effects[effectName],
                        interactionName = effect.interaction;

                    if (!interactions[interactionName]) {
                        interactions[interactionName] = {};
                    }

                    effect.actions.forEach(action => interactions[interactionName][action] = options);
                });
                let $element = this.$element,
                    $dimensionsElement;
                const elementType = this.getElementType();

                if ('element' === type && 'section' !== elementType) {
                    $dimensionsElement = $element;
                    let childElementSelector;

                    if ('column' === elementType) {
                        childElementSelector = elementorFrontend.config.legacyMode.elementWrappers ? '.elementor-column-wrap' : '.elementor-widget-wrap';
                    } else {
                        childElementSelector = '.elementor-widget-container';
                    }

                    $element = $element.find('> ' + childElementSelector);
                }

                const options = {
                    type,
                    interactions,
                    elementSettings,
                    $element,
                    $dimensionsElement,
                    refreshDimensions: this.isEdit,
                    range: elementSettings[name + '_range'],
                    classes: {
                        element: 'elementor-motion-effects-element',
                        parent: 'elementor-motion-effects-parent',
                        backgroundType: 'elementor-motion-effects-element-type-background',
                        container: 'elementor-motion-effects-container',
                        layer: 'elementor-motion-effects-layer',
                        perspective: 'elementor-motion-effects-perspective'
                    }
                };

                if (!options.range && 'fixed' === this.getCurrentDeviceSetting('_position')) {
                    options.range = 'page';
                }

                if ('fixed' === this.getCurrentDeviceSetting('_position')) {
                    options.isFixedPosition = true;
                }

                if ('background' === type && 'column' === this.getElementType()) {
                    options.addBackgroundLayerTo = ' > .elementor-element-populated';
                }

                return options;
            }

            activate(name) {
                const options = this.prepareOptions(name);

                if (jQuery.isEmptyObject(options.interactions)) {
                    return;
                }

                this[name] = new _motionFx.default(options);
            }

            deactivate(name) {
                if (this[name]) {
                    this[name].destroy();
                    delete this[name];
                }
            }

            toggle() {
                const currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
                    elementSettings = this.getElementSettings();
                ['motion_fx', 'background_motion_fx'].forEach(name => {
                    const devices = elementSettings[name + '_devices'],
                        isCurrentModeActive = !devices || -1 !== devices.indexOf(currentDeviceMode);

                    if (isCurrentModeActive && (elementSettings[name + '_motion_fx_scrolling'] || elementSettings[name + '_motion_fx_mouse'])) {
                        if (this[name]) {
                            this.refreshInstance(name);
                        } else {
                            this.activate(name);
                        }
                    } else {
                        this.deactivate(name);
                    }
                });
            }

            refreshInstance(instanceName) {
                const instance = this[instanceName];

                if (!instance) {
                    return;
                }

                const preparedOptions = this.prepareOptions(instanceName);
                instance.setSettings(preparedOptions);
                instance.refresh();
            }

            onInit() {
                super.onInit();
                this.initEffects();
                this.addCSSTransformEvents();
                this.toggle();
            }

            onElementChange(propertyName) {
                if (/motion_fx_((scrolling)|(mouse)|(devices))$/.test(propertyName)) {
                    if ('motion_fx_motion_fx_scrolling' === propertyName) {
                        this.addCSSTransformEvents();
                    }

                    this.toggle();
                    return;
                }

                const propertyMatches = propertyName.match('.*?(motion_fx|_transform)');

                if (propertyMatches) {
                    const instanceName = propertyMatches[0].match('(_transform)') ? 'motion_fx' : propertyMatches[0];
                    this.refreshInstance(instanceName);

                    if (!this[instanceName]) {
                        this.activate(instanceName);
                    }
                }

                if (/^_position/.test(propertyName)) {
                    ['motion_fx', 'background_motion_fx'].forEach(instanceName => {
                        this.refreshInstance(instanceName);
                    });
                }
            }

            onDestroy() {
                super.onDestroy();
                ['motion_fx', 'background_motion_fx'].forEach(name => {
                    this.deactivate(name);
                });
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/motion-fx/assets/js/frontend/motion-fx/actions.js":
    /*!********************************************************************!*\
      !*** ../modules/motion-fx/assets/js/frontend/motion-fx/actions.js ***!
      \********************************************************************/
    /***/ ((__unused_webpack_module, exports) => {

        "use strict";


        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        class _default extends elementorModules.Module {
            getMovePointFromPassedPercents(movableRange, passedPercents) {
                const movePoint = passedPercents / movableRange * 100;
                return +movePoint.toFixed(2);
            }

            getEffectValueFromMovePoint(range, movePoint) {
                return range * movePoint / 100;
            }

            getStep(passedPercents, options) {
                if ('element' === this.getSettings('type')) {
                    return this.getElementStep(passedPercents, options);
                }

                return this.getBackgroundStep(passedPercents, options);
            }

            getElementStep(passedPercents, options) {
                return -(passedPercents - 50) * options.speed;
            }

            getBackgroundStep(passedPercents, options) {
                const movableRange = this.getSettings('dimensions.movable' + options.axis.toUpperCase());
                return -this.getEffectValueFromMovePoint(movableRange, passedPercents);
            }

            getDirectionMovePoint(passedPercents, direction, range) {
                let movePoint;

                if (passedPercents < range.start) {
                    if ('out-in' === direction) {
                        movePoint = 0;
                    } else if ('in-out' === direction) {
                        movePoint = 100;
                    } else {
                        movePoint = this.getMovePointFromPassedPercents(range.start, passedPercents);

                        if ('in-out-in' === direction) {
                            movePoint = 100 - movePoint;
                        }
                    }
                } else if (passedPercents < range.end) {
                    if ('in-out-in' === direction) {
                        movePoint = 0;
                    } else if ('out-in-out' === direction) {
                        movePoint = 100;
                    } else {
                        movePoint = this.getMovePointFromPassedPercents(range.end - range.start, passedPercents - range.start);

                        if ('in-out' === direction) {
                            movePoint = 100 - movePoint;
                        }
                    }
                } else if ('in-out' === direction) {
                    movePoint = 0;
                } else if ('out-in' === direction) {
                    movePoint = 100;
                } else {
                    movePoint = this.getMovePointFromPassedPercents(100 - range.end, 100 - passedPercents);

                    if ('in-out-in' === direction) {
                        movePoint = 100 - movePoint;
                    }
                }

                return movePoint;
            }

            translateX(actionData, passedPercents) {
                actionData.axis = 'x';
                actionData.unit = 'px';
                this.transform('translateX', passedPercents, actionData);
            }

            translateY(actionData, passedPercents) {
                actionData.axis = 'y';
                actionData.unit = 'px';
                this.transform('translateY', passedPercents, actionData);
            }

            translateXY(actionData, passedPercentsX, passedPercentsY) {
                this.translateX(actionData, passedPercentsX);
                this.translateY(actionData, passedPercentsY);
            }

            tilt(actionData, passedPercentsX, passedPercentsY) {
                const options = {
                    speed: actionData.speed / 10,
                    direction: actionData.direction
                };
                this.rotateX(options, passedPercentsY);
                this.rotateY(options, 100 - passedPercentsX);
            }

            rotateX(actionData, passedPercents) {
                actionData.axis = 'x';
                actionData.unit = 'deg';
                this.transform('rotateX', passedPercents, actionData);
            }

            rotateY(actionData, passedPercents) {
                actionData.axis = 'y';
                actionData.unit = 'deg';
                this.transform('rotateY', passedPercents, actionData);
            }

            rotateZ(actionData, passedPercents) {
                actionData.unit = 'deg';
                this.transform('rotateZ', passedPercents, actionData);
            }

            scale(actionData, passedPercents) {
                const movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range);
                this.updateRulePart('transform', 'scale', 1 + actionData.speed * movePoint / 1000);
            }

            transform(action, passedPercents, actionData) {
                if (actionData.direction) {
                    passedPercents = 100 - passedPercents;
                }

                this.updateRulePart('transform', action, this.getStep(passedPercents, actionData) + actionData.unit);
            }

            setCSSTransformVariables(elementSettings) {
                this.CSSTransformVariables = [];
                jQuery.each(elementSettings, (settingKey, settingValue) => {
                    const transformKeyMatches = settingKey.match(/_transform_(.+?)_effect/m);

                    if (transformKeyMatches && settingValue) {
                        if ('perspective' === transformKeyMatches[1]) {
                            this.CSSTransformVariables.unshift(transformKeyMatches[1]);
                            return;
                        }

                        if (this.CSSTransformVariables.includes(transformKeyMatches[1])) {
                            return;
                        }

                        this.CSSTransformVariables.push(transformKeyMatches[1]);
                    }
                });
            }

            opacity(actionData, passedPercents) {
                const movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range),
                    level = actionData.level / 10,
                    opacity = 1 - level + this.getEffectValueFromMovePoint(level, movePoint);
                this.$element.css({
                    opacity,
                    'will-change': 'opacity'
                });
            }

            blur(actionData, passedPercents) {
                const movePoint = this.getDirectionMovePoint(passedPercents, actionData.direction, actionData.range),
                    blur = actionData.level - this.getEffectValueFromMovePoint(actionData.level, movePoint);
                this.updateRulePart('filter', 'blur', blur + 'px');
            }

            updateRulePart(ruleName, key, value) {
                if (!this.rulesVariables[ruleName]) {
                    this.rulesVariables[ruleName] = {};
                }

                if (!this.rulesVariables[ruleName][key]) {
                    this.rulesVariables[ruleName][key] = true;
                    this.updateRule(ruleName);
                }

                const cssVarKey = `--${key}`;
                this.$element[0].style.setProperty(cssVarKey, value);
            }

            updateRule(ruleName) {
                let value = '';
                value += this.concatTransformCSSProperties(ruleName);
                value += this.concatTransformMotionEffectCSSProperties(ruleName);
                this.$element.css(ruleName, value);
            }

            concatTransformCSSProperties(ruleName) {
                let value = '';

                if ('transform' === ruleName) {
                    jQuery.each(this.CSSTransformVariables, (index, variableKey) => {
                        const variableName = variableKey;

                        if (variableKey.startsWith('flip')) {
                            variableKey = variableKey.replace('flip', 'scale');
                        } // Adding default value because of the hover state. if there is no default the transform will break.


                        const defaultUnit = variableKey.startsWith('rotate') || variableKey.startsWith('skew') ? 'deg' : 'px',
                            defaultValue = variableKey.startsWith('scale') ? 1 : 0 + defaultUnit;
                        value += `${variableKey}(var(--e-transform-${variableName}, ${defaultValue}))`;
                    });
                }

                return value;
            }

            concatTransformMotionEffectCSSProperties(ruleName) {
                let value = '';
                jQuery.each(this.rulesVariables[ruleName], variableKey => {
                    value += `${variableKey}(var(--${variableKey}))`;
                });
                return value;
            }

            runAction(actionName, actionData, passedPercents, ...args) {
                if (actionData.affectedRange) {
                    if (actionData.affectedRange.start > passedPercents) {
                        passedPercents = actionData.affectedRange.start;
                    }

                    if (actionData.affectedRange.end < passedPercents) {
                        passedPercents = actionData.affectedRange.end;
                    }
                }

                this[actionName](actionData, passedPercents, ...args);
            }

            refresh() {
                this.rulesVariables = {};
                this.CSSTransformVariables = [];
                this.$element.css({
                    transform: '',
                    filter: '',
                    opacity: '',
                    'will-change': ''
                });
            }

            onInit() {
                this.$element = this.getSettings('$targetElement');
                this.refresh();
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/motion-fx/assets/js/frontend/motion-fx/interactions/base.js":
    /*!******************************************************************************!*\
      !*** ../modules/motion-fx/assets/js/frontend/motion-fx/interactions/base.js ***!
      \******************************************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        var _defineProperty2 = _interopRequireDefault(__webpack_require__(/*! @babel/runtime/helpers/defineProperty */ "../node_modules/@babel/runtime/helpers/defineProperty.js"));

        class _default extends elementorModules.ViewModule {
            constructor(...args) {
                super(...args);
                (0, _defineProperty2.default)(this, "onInsideViewport", () => {
                    this.run();
                    this.animationFrameRequest = requestAnimationFrame(this.onInsideViewport);
                });
            }

            __construct(options) {
                this.motionFX = options.motionFX;

                if (!this.intersectionObservers) {
                    this.setElementInViewportObserver();
                }
            }

            setElementInViewportObserver() {
                this.intersectionObserver = elementorModules.utils.Scroll.scrollObserver({
                    callback: event => {
                        if (event.isInViewport) {
                            this.onInsideViewport();
                        } else {
                            this.removeAnimationFrameRequest();
                        }
                    }
                });
                this.intersectionObserver.observe(this.motionFX.elements.$parent[0]);
            }

            runCallback(...args) {
                const callback = this.getSettings('callback');
                callback(...args);
            }

            removeIntersectionObserver() {
                if (this.intersectionObserver) {
                    this.intersectionObserver.unobserve(this.motionFX.elements.$parent[0]);
                }
            }

            removeAnimationFrameRequest() {
                if (this.animationFrameRequest) {
                    cancelAnimationFrame(this.animationFrameRequest);
                }
            }

            destroy() {
                this.removeAnimationFrameRequest();
                this.removeIntersectionObserver();
            }

            onInit() {
                super.onInit();
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/motion-fx/assets/js/frontend/motion-fx/interactions/mouse-move.js":
    /*!************************************************************************************!*\
      !*** ../modules/motion-fx/assets/js/frontend/motion-fx/interactions/mouse-move.js ***!
      \************************************************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        var _base = _interopRequireDefault(__webpack_require__(/*! ./base */ "../modules/motion-fx/assets/js/frontend/motion-fx/interactions/base.js"));

        class MouseMoveInteraction extends _base.default {
            bindEvents() {
                if (!MouseMoveInteraction.mouseTracked) {
                    elementorFrontend.elements.$window.on('mousemove', MouseMoveInteraction.updateMousePosition);
                    MouseMoveInteraction.mouseTracked = true;
                }
            }

            run() {
                const mousePosition = MouseMoveInteraction.mousePosition,
                    oldMousePosition = this.oldMousePosition;

                if (oldMousePosition.x === mousePosition.x && oldMousePosition.y === mousePosition.y) {
                    return;
                }

                this.oldMousePosition = {
                    x: mousePosition.x,
                    y: mousePosition.y
                };
                const passedPercentsX = 100 / innerWidth * mousePosition.x,
                    passedPercentsY = 100 / innerHeight * mousePosition.y;
                this.runCallback(passedPercentsX, passedPercentsY);
            }

            onInit() {
                this.oldMousePosition = {};
                super.onInit();
            }

        }

        exports.default = MouseMoveInteraction;
        MouseMoveInteraction.mousePosition = {};

        MouseMoveInteraction.updateMousePosition = event => {
            MouseMoveInteraction.mousePosition = {
                x: event.clientX,
                y: event.clientY
            };
        };

        /***/ }),

    /***/ "../modules/motion-fx/assets/js/frontend/motion-fx/interactions/scroll.js":
    /*!********************************************************************************!*\
      !*** ../modules/motion-fx/assets/js/frontend/motion-fx/interactions/scroll.js ***!
      \********************************************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        var _base = _interopRequireDefault(__webpack_require__(/*! ./base */ "../modules/motion-fx/assets/js/frontend/motion-fx/interactions/base.js"));

        class _default extends _base.default {
            run() {
                if (pageYOffset === this.windowScrollTop) {
                    return false;
                }

                this.onScrollMovement();
                this.windowScrollTop = pageYOffset;
            }

            onScrollMovement() {
                this.updateMotionFxDimensions();
                this.updateAnimation();
                this.resetTransitionVariable();
            }

            resetTransitionVariable() {
                this.motionFX.$element.css('--e-transform-transition-duration', '100ms');
            }

            updateMotionFxDimensions() {
                const motionFXSettings = this.motionFX.getSettings();

                if (motionFXSettings.refreshDimensions) {
                    this.motionFX.defineDimensions();
                }
            }

            updateAnimation() {
                let passedRangePercents;

                if ('page' === this.motionFX.getSettings('range')) {
                    passedRangePercents = elementorModules.utils.Scroll.getPageScrollPercentage();
                } else if (this.motionFX.getSettings('isFixedPosition')) {
                    passedRangePercents = elementorModules.utils.Scroll.getPageScrollPercentage({}, window.innerHeight);
                } else {
                    passedRangePercents = elementorModules.utils.Scroll.getElementViewportPercentage(this.motionFX.elements.$parent);
                }

                this.runCallback(passedRangePercents);
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/motion-fx/assets/js/frontend/motion-fx/motion-fx.js":
    /*!**********************************************************************!*\
      !*** ../modules/motion-fx/assets/js/frontend/motion-fx/motion-fx.js ***!
      \**********************************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        var _scroll = _interopRequireDefault(__webpack_require__(/*! ./interactions/scroll */ "../modules/motion-fx/assets/js/frontend/motion-fx/interactions/scroll.js"));

        var _mouseMove = _interopRequireDefault(__webpack_require__(/*! ./interactions/mouse-move */ "../modules/motion-fx/assets/js/frontend/motion-fx/interactions/mouse-move.js"));

        var _actions = _interopRequireDefault(__webpack_require__(/*! ./actions */ "../modules/motion-fx/assets/js/frontend/motion-fx/actions.js"));

        class _default extends elementorModules.ViewModule {
            getDefaultSettings() {
                return {
                    type: 'element',
                    $element: null,
                    $dimensionsElement: null,
                    addBackgroundLayerTo: null,
                    interactions: {},
                    refreshDimensions: false,
                    range: 'viewport',
                    classes: {
                        element: 'motion-fx-element',
                        parent: 'motion-fx-parent',
                        backgroundType: 'motion-fx-element-type-background',
                        container: 'motion-fx-container',
                        layer: 'motion-fx-layer',
                        perspective: 'motion-fx-perspective'
                    }
                };
            }

            bindEvents() {
                this.onWindowResize = this.onWindowResize.bind(this);
                elementorFrontend.elements.$window.on('resize', this.onWindowResize);
            }

            unbindEvents() {
                elementorFrontend.elements.$window.off('resize', this.onWindowResize);
            }

            addBackgroundLayer() {
                const settings = this.getSettings();
                this.elements.$motionFXContainer = jQuery('<div>', {
                    class: settings.classes.container
                });
                this.elements.$motionFXLayer = jQuery('<div>', {
                    class: settings.classes.layer
                });
                this.updateBackgroundLayerSize();
                this.elements.$motionFXContainer.prepend(this.elements.$motionFXLayer);
                const $addBackgroundLayerTo = settings.addBackgroundLayerTo ? this.$element.find(settings.addBackgroundLayerTo) : this.$element;
                $addBackgroundLayerTo.prepend(this.elements.$motionFXContainer);
            }

            removeBackgroundLayer() {
                this.elements.$motionFXContainer.remove();
            }

            updateBackgroundLayerSize() {
                const settings = this.getSettings(),
                    speed = {
                        x: 0,
                        y: 0
                    },
                    mouseInteraction = settings.interactions.mouseMove,
                    scrollInteraction = settings.interactions.scroll;

                if (mouseInteraction && mouseInteraction.translateXY) {
                    speed.x = mouseInteraction.translateXY.speed * 10;
                    speed.y = mouseInteraction.translateXY.speed * 10;
                }

                if (scrollInteraction) {
                    if (scrollInteraction.translateX) {
                        speed.x = scrollInteraction.translateX.speed * 10;
                    }

                    if (scrollInteraction.translateY) {
                        speed.y = scrollInteraction.translateY.speed * 10;
                    }
                }

                this.elements.$motionFXLayer.css({
                    width: 100 + speed.x + '%',
                    height: 100 + speed.y + '%'
                });
            }

            defineDimensions() {
                const $dimensionsElement = this.getSettings('$dimensionsElement') || this.$element,
                    elementOffset = $dimensionsElement.offset();
                const dimensions = {
                    elementHeight: $dimensionsElement.outerHeight(),
                    elementWidth: $dimensionsElement.outerWidth(),
                    elementTop: elementOffset.top,
                    elementLeft: elementOffset.left
                };
                dimensions.elementRange = dimensions.elementHeight + innerHeight;
                this.setSettings('dimensions', dimensions);

                if ('background' === this.getSettings('type')) {
                    this.defineBackgroundLayerDimensions();
                }
            }

            defineBackgroundLayerDimensions() {
                const dimensions = this.getSettings('dimensions');
                dimensions.layerHeight = this.elements.$motionFXLayer.height();
                dimensions.layerWidth = this.elements.$motionFXLayer.width();
                dimensions.movableX = dimensions.layerWidth - dimensions.elementWidth;
                dimensions.movableY = dimensions.layerHeight - dimensions.elementHeight;
                this.setSettings('dimensions', dimensions);
            }

            initInteractionsTypes() {
                this.interactionsTypes = {
                    scroll: _scroll.default,
                    mouseMove: _mouseMove.default
                };
            }

            prepareSpecialActions() {
                const settings = this.getSettings(),
                    hasTiltEffect = !!(settings.interactions.mouseMove && settings.interactions.mouseMove.tilt);
                this.elements.$parent.toggleClass(settings.classes.perspective, hasTiltEffect);
            }

            cleanSpecialActions() {
                const settings = this.getSettings();
                this.elements.$parent.removeClass(settings.classes.perspective);
            }

            runInteractions() {
                const settings = this.getSettings();
                this.actions.setCSSTransformVariables(settings.elementSettings);
                this.prepareSpecialActions();
                jQuery.each(settings.interactions, (interactionName, actions) => {
                    this.interactions[interactionName] = new this.interactionsTypes[interactionName]({
                        motionFX: this,
                        callback: (...args) => {
                            jQuery.each(actions, (actionName, actionData) => this.actions.runAction(actionName, actionData, ...args));
                        }
                    });
                    this.interactions[interactionName].run();
                });
            }

            destroyInteractions() {
                this.cleanSpecialActions();
                jQuery.each(this.interactions, (interactionName, interaction) => interaction.destroy());
                this.interactions = {};
            }

            refresh() {
                this.actions.setSettings(this.getSettings());

                if ('background' === this.getSettings('type')) {
                    this.updateBackgroundLayerSize();
                    this.defineBackgroundLayerDimensions();
                }

                this.actions.refresh();
                this.destroyInteractions();
                this.runInteractions();
            }

            destroy() {
                this.destroyInteractions();
                this.actions.refresh();
                const settings = this.getSettings();
                this.$element.removeClass(settings.classes.element);
                this.elements.$parent.removeClass(settings.classes.parent);

                if ('background' === settings.type) {
                    this.$element.removeClass(settings.classes.backgroundType);
                    this.removeBackgroundLayer();
                }
            }

            onInit() {
                super.onInit();
                const settings = this.getSettings();
                this.$element = settings.$element;
                this.elements.$parent = this.$element.parent();
                this.$element.addClass(settings.classes.element);
                this.elements.$parent = this.$element.parent();
                this.elements.$parent.addClass(settings.classes.parent);

                if ('background' === settings.type) {
                    this.$element.addClass(settings.classes.backgroundType);
                    this.addBackgroundLayer();
                }

                this.defineDimensions();
                settings.$targetElement = 'element' === settings.type ? this.$element : this.elements.$motionFXLayer;
                this.interactions = {};
                this.actions = new _actions.default(settings);
                this.initInteractionsTypes();
                this.runInteractions();
            }

            onWindowResize() {
                this.defineDimensions();
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/payments/assets/js/frontend/frontend.js":
    /*!**********************************************************!*\
      !*** ../modules/payments/assets/js/frontend/frontend.js ***!
      \**********************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        class _default extends elementorModules.Module {
            constructor() {
                super();
                elementorFrontend.elementsHandler.attachHandler('paypal-button', () => __webpack_require__.e(/*! import() | paypal-button */ "paypal-button").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/paypal-button */ "../modules/payments/assets/js/frontend/handlers/paypal-button.js")));
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/progress-tracker/assets/js/frontend/frontend.js":
    /*!******************************************************************!*\
      !*** ../modules/progress-tracker/assets/js/frontend/frontend.js ***!
      \******************************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        class _default extends elementorModules.Module {
            constructor() {
                super();
                elementorFrontend.elementsHandler.attachHandler('progress-tracker', () => __webpack_require__.e(/*! import() | progress-tracker */ "progress-tracker").then(__webpack_require__.bind(__webpack_require__, /*! ./handlers/progress-tracker */ "../modules/progress-tracker/assets/js/frontend/handlers/progress-tracker.js")));
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/sticky/assets/js/frontend/frontend.js":
    /*!********************************************************!*\
      !*** ../modules/sticky/assets/js/frontend/frontend.js ***!
      \********************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        var _interopRequireDefault = __webpack_require__(/*! @babel/runtime/helpers/interopRequireDefault */ "../node_modules/@babel/runtime/helpers/interopRequireDefault.js");

        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        var _sticky = _interopRequireDefault(__webpack_require__(/*! ./handlers/sticky */ "../modules/sticky/assets/js/frontend/handlers/sticky.js"));

        class _default extends elementorModules.Module {
            constructor() {
                super();
                elementorFrontend.elementsHandler.attachHandler('section', _sticky.default, null);
                elementorFrontend.elementsHandler.attachHandler('widget', _sticky.default, null);
            }

        }

        exports.default = _default;

        /***/ }),

    /***/ "../modules/sticky/assets/js/frontend/handlers/sticky.js":
    /*!***************************************************************!*\
      !*** ../modules/sticky/assets/js/frontend/handlers/sticky.js ***!
      \***************************************************************/
    /***/ ((__unused_webpack_module, exports) => {

        "use strict";


        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        var _default = elementorModules.frontend.handlers.Base.extend({
            bindEvents() {
                elementorFrontend.addListenerOnce(this.getUniqueHandlerID() + 'sticky', 'resize', this.run);
            },

            unbindEvents() {
                elementorFrontend.removeListeners(this.getUniqueHandlerID() + 'sticky', 'resize', this.run);
            },

            isStickyInstanceActive() {
                return undefined !== this.$element.data('sticky');
            },

            /**
             * Get the current active setting value for a responsive control.
             *
             * @param {string} setting
             *
             * @return {any} - Setting value.
             */
            getResponsiveSetting(setting) {
                const elementSettings = this.getElementSettings();
                return elementorFrontend.getCurrentDeviceSetting(elementSettings, setting);
            },

            /**
             * Return an array of settings names for responsive control (e.g. `settings`, `setting_tablet`, `setting_mobile` ).
             *
             * @param {string} setting
             *
             * @return {string[]} - List of settings.
             */
            getResponsiveSettingList(setting) {
                const breakpoints = Object.keys(elementorFrontend.config.responsive.activeBreakpoints);
                return ['', ...breakpoints].map(suffix => {
                    return suffix ? `${setting}_${suffix}` : setting;
                });
            },

            activate() {
                var elementSettings = this.getElementSettings(),
                    stickyOptions = {
                        to: elementSettings.sticky,
                        offset: this.getResponsiveSetting('sticky_offset'),
                        effectsOffset: this.getResponsiveSetting('sticky_effects_offset'),
                        classes: {
                            sticky: 'elementor-sticky',
                            stickyActive: 'elementor-sticky--active elementor-section--handles-inside',
                            stickyEffects: 'elementor-sticky--effects',
                            spacer: 'elementor-sticky__spacer'
                        }
                    },
                    $wpAdminBar = elementorFrontend.elements.$wpAdminBar;

                if (elementSettings.sticky_parent) {
                    stickyOptions.parent = '.elementor-widget-wrap';
                }

                if ($wpAdminBar.length && 'top' === elementSettings.sticky && 'fixed' === $wpAdminBar.css('position')) {
                    stickyOptions.offset += $wpAdminBar.height();
                }

                this.$element.sticky(stickyOptions);
            },

            deactivate() {
                if (!this.isStickyInstanceActive()) {
                    return;
                }

                this.$element.sticky('destroy');
            },

            run(refresh) {
                if (!this.getElementSettings('sticky')) {
                    this.deactivate();
                    return;
                }

                var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
                    activeDevices = this.getElementSettings('sticky_on');

                if (-1 !== activeDevices.indexOf(currentDeviceMode)) {
                    if (true === refresh) {
                        this.reactivate();
                    } else if (!this.isStickyInstanceActive()) {
                        this.activate();
                    }
                } else {
                    this.deactivate();
                }
            },

            reactivate() {
                this.deactivate();
                this.activate();
            },

            onElementChange(settingKey) {
                if (-1 !== ['sticky', 'sticky_on'].indexOf(settingKey)) {
                    this.run(true);
                } // Settings that trigger a re-activation when changed.


                const settings = [...this.getResponsiveSettingList('sticky_offset'), ...this.getResponsiveSettingList('sticky_effects_offset'), 'sticky_parent'];

                if (-1 !== settings.indexOf(settingKey)) {
                    this.reactivate();
                }
            },

            /**
             * Listen to device mode changes and re-initialize the sticky.
             *
             * @return {void}
             */
            onDeviceModeChange() {
                // Wait for the call stack to be empty.
                // The `run` function requests the current device mode from the CSS so it's not ready immediately.
                // (need to wait for the `deviceMode` event to change the CSS).
                // See `elementorFrontend.getCurrentDeviceMode()` for reference.
                setTimeout(() => {
                    this.run(true);
                });
            },

            onInit() {
                elementorModules.frontend.handlers.Base.prototype.onInit.apply(this, arguments);

                if (elementorFrontend.isEditMode()) {
                    elementor.listenTo(elementor.channels.deviceMode, 'change', () => this.onDeviceModeChange());
                }

                this.run();
            },

            onDestroy() {
                elementorModules.frontend.handlers.Base.prototype.onDestroy.apply(this, arguments);
                this.deactivate();
            }

        });

        exports.default = _default;

        /***/ }),

    /***/ "../modules/video-playlist/assets/js/frontend/frontend.js":
    /*!****************************************************************!*\
      !*** ../modules/video-playlist/assets/js/frontend/frontend.js ***!
      \****************************************************************/
    /***/ ((__unused_webpack_module, exports, __webpack_require__) => {

        "use strict";


        Object.defineProperty(exports, "__esModule", ({
            value: true
        }));
        exports.default = void 0;

        class _default extends elementorModules.Module {
            constructor() {
                super();
                elementorFrontend.hooks.addAction('frontend/element_ready/video-playlist.default', $element => {
                    __webpack_require__.e(/*! import() | video-playlist */ "video-playlist").then(__webpack_require__.bind(__webpack_require__, /*! ./handler */ "../modules/video-playlist/assets/js/frontend/handler.js")).then(({
                                                                                                                                                                                                                                       default: dynamicHandler
                                                                                                                                                                                                                                   }) => {
                        elementorFrontend.elementsHandler.addHandler(dynamicHandler, {
                            $element,
                            toggleSelf: false
                        });
                    });
                });
            }

        }

        exports.default = _default;

        /***/ })

},
    /******/ __webpack_require__ => { // webpackRuntimeModules
        /******/ "use strict";
        /******/
        /******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
        /******/ var __webpack_exports__ = (__webpack_exec__("../assets/dev/js/frontend/frontend.js"));
        /******/ }
]);
