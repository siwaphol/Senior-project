try {
/* module-key = 'com.atlassian.confluence.plugins.editor-loader:background-loading-editor', location = 'jscripts/editor-loader.js' */
AJS.Confluence.EditorLoader = (function($) {
    var _loadingTimeout = 12000;
    
    var editorLoadingStatus = {
        loaded: false,
        inProgress: false,
        errorMessage: null,
        
        started: function() {
            this.inProgress = true;
        },
        
        successful: function() {
            this.inProgress = false;
            this.loaded = true;
        },
        
        error: function(message) {
            this.inProgress = false;
            this.errorMessage = message;
        },
        
        /**
         * Should we attempt to load or is there a load already in progress, already completed 
         * or previously failed?
         * 
         * @return true if loading should be attempted.
         */
        allowLoad : function() {
            return !this.loaded
                && !this.inProgress
                && this.errorMessage == null;
        }
    };
    
    /**
     * An object that tracks events that should change the state of the editor which occur
     * when the editor is deactivated. These changes will apply to the editor when it is
     * next activated.
     */
    var stateChangeEventListener = {
        _listening: false,
            
        _queuedHandlers: [],
            
        _watchHandler: function() {
            Confluence.Editor.UI.toggleWatchPage(false);            
        },
        
        _unwatchHandler: function() {
            Confluence.Editor.UI.toggleWatchPage(true);
        },
        
        _createQueueAdder: function(handler) {
            return function() {
                if (stateChangeEventListener._listening) {
                    stateChangeEventListener._queuedHandlers.push(handler);
                }
            }  
        },
            
        /**
         * bind to all the relevant events. 
         */
        bind: function() {
            AJS.bind("watchpage.pageoperation", this._createQueueAdder(this._watchHandler));
            AJS.bind("unwatchpage.pageoperation", this._createQueueAdder(this._unwatchHandler));
        },
        
        /**
         * @param listening if true then listen and queue handlers (i.e. the editor is deactivated). If false
         * then ignore any events. The editor is active and will handle them itself.
         */
        setListening: function(listening) {
            this._listening = listening;
        },
        
        /**
         * Apply and then clear all the queued handlers. 
         */
        applyHandlers: function() {
            var handler = this._queuedHandlers.pop();
            while (handler) {
                handler();
                handler = this._queuedHandlers.pop();
            }
        }
    };
    
    stateChangeEventListener.setListening(true);
    stateChangeEventListener.bind();
    
    /** An array of functions to be called when an Editor load completes successfully. */
    var onLoadSuccess = [];

    /** An array of functions to be called when an Editor load fails. */
    var onLoadError = [];
            
    /**
     * Check if a _load should be allowed and make the appropriate callback if it shouldn't.
     * 
     * @return true if load is guarded (shouldn't be allowed); false if a load should be permitted.
     */
    var loadGuard = function(successCallback, errorCallback) {
        if (!editorLoadingStatus.allowLoad()) {
            if (editorLoadingStatus.errorMessage) {
                if (errorCallback) {
                    errorCallback(editorLoadingStatus.errorMessage);
                } else {
                    AJS.log("EditorLoader: loadGuard - previous load failed.");
                }
            } else if (editorLoadingStatus.inProgress) {
                // record the other callbacks for later (if they were supplied)
                if (successCallback) {
                    onLoadSuccess.push(successCallback);                        
                }
                
                if (errorCallback) {
                    onLoadError.push(errorCallback);
                }
            } else if (editorLoadingStatus.loaded) {
                if (successCallback) {
                    //So that the promise has time to have callbacks registered.
                    setTimeout(successCallback, 0);
                } else {
                    AJS.log("EditorLoader: loadGuard - editor is already loaded.");
                }
            }
            
            return true;
        }            
    };
    
    /**
     * Note, this methods doesn't do any encoding so don't add anything here you shouldn't.
     */
    var appendUrlParameter = function(url, name, value) {
        if (url.indexOf("?") == -1) {
            url += "?";
        } else {
            url += "&";
        }
        
        return url + name + "=" + value;
    };
    
    /**
     * @return an object with two properties; jsUrls is an array of Javascript URLs and cssTags is an array of HTML formatted CSS
     * Link elements (including conditional comments)
     */
    var getResources = function() {
        // TODO conditionals are basically comments - is inserting comment nodes into the DOM 
        // a valid way to load them. Test on IE, etc
        var resourceTags = AJS.Meta.get("editor.loader.resources");
        var $resources = $(resourceTags);
        var jsUrls = [];
        var cssTags = []; // link elements and comments containing conditional CSS
        
        // Separate the resources into CSS tags and JS urls.
        for (var i = 0; i < $resources.length; i++) {
            var resource = $resources[i];
            // nodeType 8 is Node.COMMENT_NODE
            if (resource.nodeType == 8 && resource.nodeValue.indexOf("text/css") != -1) {
                cssTags.push(resource);
            } else if (resource.nodeType == 1) {
                if (resource.nodeName == "LINK") {
                    cssTags.push(resource);
                } else if (resource.nodeName == "SCRIPT" && resource.getAttribute("type") == "text/javascript") {
                    jsUrls.push(resource.src);
                }
            }
        }
        
        return {
            "jsUrls": jsUrls,
            "cssTags" : cssTags
        };
    };
    
    /**
     * Derive the base url for the Editor based on the array of urls provided.
     * 
     * @param urls an array of Javascript urls for the Editor
     * @return the baseUrl.
     */
    var getBaseUrl = function(urls) {
        var baseUrl = null;
        for (var i = 0; i < urls.length && baseUrl == null; i++) {
            var url = urls[i];
            // try to use part of a URL that would indicate this is definitely the editor scripts (but which couldn't be
            // confused with context part, etc)
            if (/editor,/.test(url)) {
                baseUrl = url;
            }
        }
        
        return baseUrl;
    };
    
    /**
     * Load the HTML and resources required by the Editor. (Note that the Editor is not necessarily initialised unless
     * the provided callback does it.)
     * 
     * @param successCallback a function called if the Editor is successfully loaded.
     * @param errorCallback a function called if there is a failure while loading the Editor (takes a message string parameter).
     */
    var loadEditor = function(successCallback, errorCallback) {
        if (loadGuard(successCallback, errorCallback)) {
            return;
        }
        
        editorLoadingStatus.started();
        
        if (successCallback) {
            onLoadSuccess.push(successCallback);                        
        }
        
        if (errorCallback) {
            onLoadError.push(errorCallback);
        }            
        
        var resources = getResources();
            
        AJS.Meta.set("rte.src.url", getBaseUrl(resources.jsUrls));

        // An object providing tracking of the various AJAX requests involved in loading
        var loadTracker = {
            countDown: resources.jsUrls.length + 1, // the number of scripts to be loaded plus the template
            errorMessages: [],
            success: function() {
                this.loaded();
            },
            
            error: function(message) {
                this.errorMessages.push(message);
                this.loaded();
            },
            
            loaded: function() {
                this.countDown--;
                if (this.countDown == 0) {
                    if (this.errorMessages.length == 0) {
                        editorLoadingStatus.successful();
                        AJS.log("EditorLoader: Finished loading the editor.");
                        
                        $.each(onLoadSuccess, function(i, func) {
                            func();
                        });                                            
                    } else {
                        var joinedMessages = this.errorMessages.join();
                        editorLoadingStatus.error(joinedMessages);   
                        AJS.log("EditorLoader: Error while loading the editor: " + joinedMessages);
                        
                        $.each(onLoadError, function(i, func) {
                            func(joinedMessages);
                        });                                        
                    }

                    AJS.log("EditorLoader: all 'after load' callbacks have been called.");

                    // can clear callback arrays - we don't need them any more
                    onLoadSuccess = null;
                    onLoadError = null;                        
                }
            } 
        };
        
        var loadCallback = function() {
            loadTracker.success();
        };
        
        var loadErrorCallback = function(jqXHR, textStatus, errorThrown) {
            var message = "";
            if (textStatus) {
                message = textStatus;
            }
            
            if (errorThrown) {
                message = message + ": " + errorThrown;                    
            }
            
            loadTracker.error(message);
        };
        
        var originalTimeout = $.ajaxSetup().timeout;
        $.ajaxSetup({ timeout: _loadingTimeout });
        
        // Create a hidden container to load the Editor DOM into
        var $preloadContainer = getPreloadContainer(); 
        $("body").append($preloadContainer);

        // Load the Editor template if on a page
        if (AJS.Meta.get("page-id"))
        {
            $preloadContainer.load(Confluence.getContextPath() + "/plugins/editor-loader/editor.action",
                {
                    pageId: AJS.Data.get("page-id"),
                    spaceKey: AJS.Data.get("space-key"),
                    atl_after_login_redirect: window.location.pathname // the URL that an anonymous user will be redirect to after logging in
                },
                function(response, status, xhr) {
                    if (status == "success" || status == "notmodified") {
                        // move any metadata into the head (which is the only legal place for meta tags).
                        var metadataTemplate = AJS.renderTemplate("dynamic-editor-metadata");
                        $("head").append(metadataTemplate);
                        AJS.populateParameters();
                        AJS.log("EditorLoader: Finished loading the editor template.");
                        loadCallback();
                    } else {
                        loadErrorCallback(xhr, "Error loading the Editor template: " + xhr.status + " - " + xhr.statusText, null);
                    }
                });
        }
        else
        {
            loadCallback();
        }
        
        AJS.log("EditorLoader: " + resources.jsUrls.length + " scripts to be loaded.");

        var originalCacheSetting = $.ajaxSetup().cache;
        $.ajaxSetup({ cache: true });

        dispatchScriptLoader(loadCallback, loadErrorCallback, resources);

        $.ajaxSetup({
            cache: originalCacheSetting,
            timeout: originalTimeout 
        });
        
        // TODO don't append the CSS until the Editor is actually activated to avoid risk of style clashes
        $.each(resources.cssTags, function(index, tag) {
            $("head").append(tag);
        });
        
        AJS.log("EditorLoader: Finished inserting the editor CSS tags.");
    };

    /**
     * Load the scripts for the editor. If the dark feature is enabled it uses a new approach where we fetch the scripts
     * via ajax and then eval them in order when they all returned. Otherwise we fall back to the old approach where we
     * would fetch the scripts via ajax when there is only 1, otherwise append them via scripts tags to the DOM.
     *
     * @param loadCallback
     * @param loadErrorCallback
     * @param resources
     */
    var dispatchScriptLoader = function(loadCallback, loadErrorCallback, resources) {
        if (AJS.DarkFeatures.isEnabled("editor.async-script-loading")) {
            loadScripts(resources.jsUrls, loadCallback, loadErrorCallback);
        } else {
            var ajaxSettings = {
                dataType: "script",
                error: loadErrorCallback,
                success: loadCallback
            };

            if ($.browser.msie) {
                // without this setting window.execScript would be used which makes debugging
                // very difficult.
                ajaxSettings.crossDomain = true;
            }

            if (resources.jsUrls.length > 1) {
                var $head = $("head");
                $.each(resources.jsUrls, function(index, jsUrl) {
                    var $script = $("<script></script>");
                    $script.attr("src", jsUrl);
                    $head.append($script);
                    setTimeout(loadCallback); // calling loadCallback in the same 'event' seems to cause weird problems in Firefox on Linux.
                });
            } else {
                // To avoid CONFDEV-8038 prefer this mechanism for the normal (single batched resource) case.
                $.each(resources.jsUrls, function(index, jsUrl) {
                    ajaxSettings.url = jsUrl;
                    $.ajax(ajaxSettings);
                });
            }
        }
    };

    /**
     * Loads a list of scripts via XHR asynchronously but evaluates them in the order passed into the function.
     * Solves CONFDEV-7632 and CONF-27796 at the same time.
     *
     */
    var loadScripts = function(urls, loadCallback, loadErrorCallback) {
        AJS.log('EditorLoader: Loading scripts via dark feature "editor.async-script-loading"', urls, urls.length);
        var countdown = urls.length;
        var scripts = [];
        var ajaxSettings = {
            dataType: "text",
            crossDomain: $.browser.msie
        };

        $.each(urls, function(index, url) {
            $.ajax(url, ajaxSettings)
                .done(function (data) {
                    scripts[index] = data;
                })
                .always(function () {
                    countdown--;

                    if (countdown === 0) {
                        evalScripts(scripts);
                    }
                })
                .done(loadCallback)
                .error(loadErrorCallback);
        });

        function evalScripts(scripts) {
            $.each(scripts, function(i, script) {
                try {
                    $.globalEval(script);
                } catch (e) {
                    AJS.log("Error evaling script in editor-loader", urls[i], e);
                }
            });
        }
    };
    
    /**
     * Create the callback used to transfer the editor DOM and initialise the editor.
     * This callback will only be used the first time the editor is activated. Subsequent
     * activations will make use of _createReactivationCallback.
     * 
     * @param $container jQuery wrapped Element which the Editor HTML will be appended to.
     * @param loader
     * @returns {Function}
     */
    var createFirstActivationCallback = function($container, loader) {
        return function() {
            loader.notify('dom loaded');
            var $preloadContainer = getPreloadContainer();
            $container.append($preloadContainer.children());
            $preloadContainer.show();

            AJS.bind("init.rte", function() {
                loader.resolve();
                setTimeout(function(){
                    stateChangeEventListener.setListening(false);
                    stateChangeEventListener.applyHandlers();
                    AJS.trigger("add-bindings.keyboardshortcuts");
                    AJS.trigger("active.dynamic.rte");
                }, 0);
            });
            AJS.Rte.BootstrapManager.initialise();
        };            
    };
    
    /**
     * Create the callback used to activate the editor when it has been active previously.
     * 
     * @param $container
     * @param loader
     * @returns {Function}
     */
    var createReactivationCallback = function($container, loader) {
        return function() {
            var $preloadContainer = getPreloadContainer();
            if ($preloadContainer.parent()[0] != $container[0]) {
                $container.append($preloadContainer);                    
            }
            
            $preloadContainer.show();
            AJS.Rte.getEditor().focus();
            loader.resolve();
            stateChangeEventListener.setListening(false);
            stateChangeEventListener.applyHandlers();            
            AJS.trigger("add-bindings.keyboardshortcuts");
            AJS.trigger("active.dynamic.rte");
        };
    };
    
    /**
     * @returns the jQuery wrapped Element that contains the editor DOM, or create and return a new hidden div
     * if there is none found
     */
    var getPreloadContainer = function() {
        var $container = $("#editor-preload-container");
        if (!$container.length) {
            $container = $("<div id=\"editor-preload-container\" style=\"display: none;\"></div>");
        }
        
        return $container;
    };
    
    return {
        /** The maximum wait in milliseconds for the Editor to load */
        loadingTimeout: _loadingTimeout,
        
        /**
         * @returns true if there is already an active editor; otherwise false
         */
        isEditorActive: function() {
           var $container = $("#editor-preload-container");
           return $container.length && $container.is(":visible");
        },
        
        /**
         * Load the Editor into a hidden Element on the page if it hasn't already been loaded. 
         * The Editor is not initialised, its HTML, CSS and Javascript is simply loaded ready for
         * later activation.
         */
        load: loadEditor,
        
        /**
         * Activate the editor upon the given container.
         * <p/>
         * If the editor has not finished loading when this method is called then the activation
         * will occur asynchronously (when the load completes). If the editor is already loaded
         * then the success callback will occur immediately.
         * <p/>
         * If the editor has failed to load then the errorCallback will be called.
         * <p/>
         * Note that the Editor DOM is simply transferred from a current hidden location to the supplied
         * container. If you want to perform any kind of transition then you should ensure the container is
         * hidden and perform the reveal in the successCallback you provide.
         * 
         * @param container jQuery wrapped Element to display the Editor inside.
         */
        activate: function($container) {
            var loader = new $.Deferred(),
                loadCallback = null;
            //successCallback, errorCallback
            if (this.isEditorActive()) {
                //done or fail callbacks should do nothing, return an empty promise
                return new $.Deferred().promise();
            }
            
            if (editorLoadingStatus.errorMessage) {
                loader.reject(editorLoadingStatus.errorMessage)
                return loader.promise();
            }
            
            AJS.trigger("remove-bindings.keyboardshortcuts");
            // shortcuts will be added again in the callback below

            if (editorLoadingStatus.loaded && AJS.Rte && AJS.Rte.BootstrapManager && AJS.Rte.BootstrapManager.isInitComplete()) {
                loadCallback = createReactivationCallback($container, loader);
            } else {
                loadCallback = createFirstActivationCallback($container, loader);
            }

            loadEditor(loadCallback, function(){
                loader.rejectWith(this, arguments);
            });
            return loader.promise();
        },
  
        /**
         * Remove the editor from the container it was previously activated upon. deactivate must be called before an Editor can be activated
         * on another container.
         */
        deactivate: function() {
            if (this.isEditorActive()) {
                stateChangeEventListener.setListening(false);
                
                // remove all keyboard bindings and then re-bind so that we don't have editor bindings 
                // (our context enablement calculation will differ once the editor is no longer visible)
                AJS.trigger("remove-bindings.keyboardshortcuts");
                
                // This interferes with the key buffering during launch and since ajax submission is disabled
                // it is not necessary at the moment.
                // AJS.Rte.Content.setHtml("");
                // AJS.Rte.Content.editorResetContentChanged();
                var $preloadContainer = getPreloadContainer();
                $preloadContainer.hide();
                
                // Move the preload container to the body of the document in case the client plans to do 
                // anything untoward with the container now.
                // TODO You can't actually move the editor's iframe around: http://stackoverflow.com/a/2542043 so I need a new plan
                // $("body").append($preloadContainer);
                
                AJS.trigger("add-bindings.keyboardshortcuts");
            }
        },
        
        /**
         * @return the immediate parent of the currently active editor as a jQuery wrapped Element. If an editor is not
         * currently active then null will be returned.
         */
        getEditorForm: function() {
            if (this.isEditorActive()) {
                return $(tinymce.activeEditor.getContainer()).closest('form');
            } else {
                return null;
            }
        }        
    };
})(AJS.$);


} catch (err) {
    if (console && console.log && console.error) {
        console.log("Error running batched script.");
        console.error(err);
    }
}


try {
/* module-key = 'com.atlassian.confluence.plugins.editor-loader:background-loading-editor', location = 'jscripts/block-and-buffer-keys.js' */
// TODO Move this to core Confluence and modify it to also take a TinyMCE Editor as a block object
// This could then also be used by the find and replace plugin (which is currently duplicating
// most of this).
AJS.Confluence.BlockAndBuffer = {
        
        _cancelKeyboardFunction: function(e) {
            e.preventDefault();
            e.stopPropagation();
        },
        
        /* Handle UTF-16 surrogate pair character codes */
        _surrogatePairFixedFromCharCode: function(code) {  
            if (code > 0xFFFF) {  
                code -= 0x10000;  
                return String.fromCharCode(0xD800 + (code >> 10), 0xDC00 +  (code & 0x3FF));  
            }  
            else {  
                return String.fromCharCode(code);  
            }  
        },
        
        /*
         * Buffer any character codes being typed and also prevent
         * the event that is instigating them. 
         */
        _bufferTextFunction: function(e, buffer) {
            AJS.Confluence.BlockAndBuffer._cancelKeyboardFunction(e);
            
            var keyCode = e.which;
            // IE8 doesn't have charCode for keypress event
            if (!keyCode) {
                keyCode = e.charCode ? e.charCode : e.keyCode;
            }

            // Firefox and Opera wrongly raise keypress for control characters
            if (keyCode !== 13 && keyCode < 48) {
                return;
            }

            buffer.push(keyCode);
        },
        
        
        /**
         * @return the buffered text the user may have entered
         */
        _unblock: function($jq, keycodeBuffer, blockFunc) {
            
            $jq.unbind("keypress", blockFunc);
            
            var bufferedText = "";
            for (var i = 0; i < keycodeBuffer.length; i++) {
                bufferedText += AJS.Confluence.BlockAndBuffer._surrogatePairFixedFromCharCode(keycodeBuffer[i]);
            }

            return bufferedText;
        },
        
        /* -------------------------- Public API -------------------------- */
        
        /**
         * Block keys on the supplied jQuery DOM object. 
         * 
         * @param $jq jQuery wrapped Element.
         * @return the zero argument unblock function you should run when you want to cancel the block. This will return
         * the buffer as a String.
         */
        block: function($jq) {
            var keycodeBuffer = [];

            var bufferText = function(e) {
                AJS.Confluence.BlockAndBuffer._bufferTextFunction(e, keycodeBuffer);
                e.preventDefault();
            };
            
            $jq.keypress(bufferText);
            
            return function() {
                return AJS.Confluence.BlockAndBuffer._unblock($jq, keycodeBuffer, bufferText);
            };
        }
};
} catch (err) {
    if (console && console.log && console.error) {
        console.log("Error running batched script.");
        console.error(err);
    }
}


