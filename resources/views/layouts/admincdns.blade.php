<link rel="icon" href="{{ asset('assets/img/favicon.png') }}">
<!-- Apple Icon -->
<link rel="apple-touch-icon" href="{{ asset('assets/img/apple-icon.png') }}">
<!-- Theme Config Js -->
<script src="{{ asset('assets/js/theme-script.js') }}" type="e56d8e3ed6c4bef649884303-text/javascript"></script>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
<link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<!-- Select2 CSS -->
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- jQuery (load before Bootstrap to prevent conflicts) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- CRITICAL: Fix for Bootstrap selector-engine "Illegal invocation" error - MUST run BEFORE Bootstrap -->
<script>
    // Fix Bootstrap selector-engine "Illegal invocation" error
    // This MUST run before Bootstrap loads to prevent it from caching broken method references
    (function() {
        'use strict';
        
        // Patch Element.prototype methods FIRST (before document methods)
        // Bootstrap's selector engine uses Element.querySelector which loses context
        // When Bootstrap does: const qs = element.querySelector; qs(selector), 'this' is lost
        if (typeof Element !== 'undefined' && Element.prototype) {
            const origElQS = Element.prototype.querySelector;
            const origElQSA = Element.prototype.querySelectorAll;
            
            // CRITICAL: Use defineProperty to create methods that maintain context
            // This prevents Bootstrap from extracting methods and losing 'this'
            Object.defineProperty(Element.prototype, 'querySelector', {
                value: function(selector) {
                    // Always use call to ensure 'this' context is maintained
                    // Handle case where 'this' is undefined/null (when method is extracted)
                    try {
                        if (!this || this.nodeType === undefined) {
                            // Fallback to document if context is invalid
                            return document.querySelector(selector);
                        }
                        return origElQS.call(this, selector);
                    } catch (e) {
                        // If call fails, fallback to document
                        if (e.message && e.message.includes('Illegal invocation')) {
                            return document.querySelector(selector);
                        }
                        throw e;
                    }
                },
                writable: true,
                configurable: true,
                enumerable: false
            });
            
            Object.defineProperty(Element.prototype, 'querySelectorAll', {
                value: function(selector) {
                    try {
                        if (!this || this.nodeType === undefined) {
                            return document.querySelectorAll(selector);
                        }
                        return origElQSA.call(this, selector);
                    } catch (e) {
                        if (e.message && e.message.includes('Illegal invocation')) {
                            return document.querySelectorAll(selector);
                        }
                        throw e;
                    }
                },
                writable: true,
                configurable: true,
                enumerable: false
            });
            
            // Also patch getElementsByClassName
            if (Element.prototype.getElementsByClassName) {
                const origGetElementsByClassName = Element.prototype.getElementsByClassName;
                Object.defineProperty(Element.prototype, 'getElementsByClassName', {
                    value: function(className) {
                        if (!this || this.nodeType === undefined) {
                            return document.getElementsByClassName(className);
                        }
                        return origGetElementsByClassName.call(this, className);
                    },
                    writable: true,
                    configurable: true,
                    enumerable: false
                });
            }
        }
        
        // Patch document methods
        const doc = document;
        const originalQuerySelector = doc.querySelector;
        const originalQuerySelectorAll = doc.querySelectorAll;
        const originalGetElementById = doc.getElementById;
        
        doc.querySelector = function(selector) {
            return originalQuerySelector.call(doc, selector);
        };
        
        doc.querySelectorAll = function(selector) {
            return originalQuerySelectorAll.call(doc, selector);
        };
        
        doc.getElementById = function(id) {
            return originalGetElementById.call(doc, id);
        };
        
        // Also patch Node.prototype if it exists (some browsers)
        if (typeof Node !== 'undefined' && Node.prototype) {
            if (Node.prototype.querySelector && Node.prototype !== Element.prototype) {
                const origNodeQS = Node.prototype.querySelector;
                const origNodeQSA = Node.prototype.querySelectorAll;
                
                Node.prototype.querySelector = function(selector) {
                    if (!this || !this.nodeType) {
                        return document.querySelector(selector);
                    }
                    return origNodeQS.call(this, selector);
                };
                
                Node.prototype.querySelectorAll = function(selector) {
                    if (!this || !this.nodeType) {
                        return document.querySelectorAll(selector);
                    }
                    return origNodeQSA.call(this, selector);
                };
            }
        }
    })();
</script>
<!-- Bootstrap 5 JS (load after jQuery and fix) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
</script>
<!-- Additional Bootstrap fixes after load -->
<script>
    // Patch Bootstrap's selector engine directly to fix "Illegal invocation" error
    (function() {
        'use strict';
        
        function patchBootstrapSelectorEngine() {
            if (typeof window.bootstrap === 'undefined') {
                setTimeout(patchBootstrapSelectorEngine, 50);
                return;
            }
            
            try {
                // Try to access Bootstrap's internal SelectorEngine
                // Bootstrap 5 stores it in different places depending on build
                let SelectorEngine = null;
                
                // Try to find SelectorEngine in bootstrap object
                if (window.bootstrap.SelectorEngine) {
                    SelectorEngine = window.bootstrap.SelectorEngine;
                } else if (window.bootstrap.Modal && window.bootstrap.Modal.constructor) {
                    SelectorEngine = window.bootstrap.Modal.constructor.SelectorEngine;
                }
                
                // If we found SelectorEngine, patch its findOne method
                if (SelectorEngine && SelectorEngine.findOne) {
                    const originalFindOne = SelectorEngine.findOne;
                    SelectorEngine.findOne = function(selector, element) {
                        try {
                            return originalFindOne.call(this, selector, element);
                        } catch (e) {
                            if (e.message && e.message.includes('Illegal invocation')) {
                                // Fallback: use document.querySelector if element context is lost
                                if (element && typeof element.querySelector === 'function') {
                                    try {
                                        return element.querySelector(selector);
                                    } catch (e2) {
                                        // If element.querySelector fails, try document
                                        return document.querySelector(selector);
                                    }
                                } else {
                                    return document.querySelector(selector);
                                }
                            }
                            throw e;
                        }
                    };
                }
                
                // Also patch querySelector methods again after Bootstrap loads
                // Bootstrap might have cached references before our initial patch
                if (Element.prototype.querySelector) {
                    const origElQS = Element.prototype.querySelector;
                    const origElQSA = Element.prototype.querySelectorAll;
                    
                    Element.prototype.querySelector = function(selector) {
                        if (!this) {
                            return document.querySelector(selector);
                        }
                        try {
                            return origElQS.call(this, selector);
                        } catch (e) {
                            if (e.message && e.message.includes('Illegal invocation')) {
                                return document.querySelector(selector);
                            }
                            throw e;
                        }
                    };
                    
                    Element.prototype.querySelectorAll = function(selector) {
                        if (!this) {
                            return document.querySelectorAll(selector);
                        }
                        try {
                            return origElQSA.call(this, selector);
                        } catch (e) {
                            if (e.message && e.message.includes('Illegal invocation')) {
                                return document.querySelectorAll(selector);
                            }
                            throw e;
                        }
                    };
                }
                
            } catch (e) {
                console.warn('Bootstrap selector engine patch error:', e);
            }
        }
        
        // Patch immediately and retry
        setTimeout(patchBootstrapSelectorEngine, 100);
        setTimeout(patchBootstrapSelectorEngine, 300);
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', patchBootstrapSelectorEngine);
        }
        
        // Direct workaround: Intercept modal triggers and use jQuery fallback if Bootstrap fails
        function setupModalFallback() {
            // Intercept clicks on modal triggers BEFORE Bootstrap handles them
            document.addEventListener('click', function(e) {
                const trigger = e.target.closest('[data-bs-toggle="modal"]');
                if (trigger) {
                    const targetId = trigger.getAttribute('data-bs-target');
                    if (targetId && targetId.startsWith('#')) {
                        // Check if modal element exists
                        const modalEl = document.querySelector(targetId);
                        if (!modalEl) {
                            console.warn('Modal element not found:', targetId);
                            return; // Let Bootstrap handle the error
                        }
                        
                        // Set up error handler for Bootstrap modal
                        const showModal = function() {
                            try {
                                if (window.bootstrap && window.bootstrap.Modal) {
                                    const modal = new window.bootstrap.Modal(modalEl);
                                    modal.show();
                                } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
                                    // Fallback to jQuery modal
                                    jQuery(targetId).modal('show');
                                }
                            } catch (err) {
                                console.error('Bootstrap modal error, using jQuery fallback:', err);
                                // Use jQuery modal as fallback
                                if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    jQuery(targetId).modal('show');
                                }
                            }
                        };
                        
                        // Delay to let Bootstrap try first, then fallback if needed
                        setTimeout(function() {
                            // Check if modal was shown (has 'show' class)
                            if (!modalEl.classList.contains('show')) {
                                // Bootstrap didn't show it, use jQuery
                                if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
                                    jQuery(targetId).modal('show');
                                }
                            }
                        }, 100);
                    }
                }
            }, true); // Use capture phase
        }
        
        // Setup fallback after DOM is ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupModalFallback);
        } else {
            setupModalFallback();
        }
    })();
</script>
{{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" /> --}}

{{-- <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" /> --}}
<!-- Or for RTL support -->
<!-- Datetimepicker CSS -->

<!-- Select2 JS (loaded globally so initializer can run reliably) -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Moment.js (or Luxon, Day.js, etc.) -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Tempus Dominus Bootstrap 5 CSS -->
<link rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.7.10/dist/css/tempus-dominus.min.css">

<!-- Tempus Dominus Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/@eonasdan/tempus-dominus@6.7.10/dist/js/tempus-dominus.min.js"></script>
<!-- Daterangepikcer CSS -->
{{-- <link rel="stylesheet"href="{{ asset('assets/plugins/daterangepicker/daterangepicker.css') }}"> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.js"
    integrity="sha512-W76C8qrNYavcaycIH9EijxRuswoS+LCqA1+hq+ECrmjzAbe/SHhTgrwA1uc84husS/Gz50mxOEHPzrcd3sxBqQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css"
    integrity="sha512-rBi1cGvEdd3NmSAQhPWId5Nd6QxE8To4ADjM2a6n0BrqQdisZ/RPUlm0YycDzvNL1HHAh1nKZqI0kSbif+5upQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Fontawesome CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/fontawesome.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome/css/all.min.css') }}">

<!-- Tabler Icon CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/tabler-icons/tabler-icons.min.css') }}">

<!-- Simplebar CSS -->
{{-- <link rel="stylesheet" href="{{ asset('assets/plugins/simplebar/simplebar.min.css') }}"> --}}
<!-- Include SimpleBar CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.css" />

<!-- Include SimpleBar JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/simplebar@latest/dist/simplebar.min.js"></script>

{{-- CK Editor 5 --}}
<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/47.3.0/ckeditor5.css" />
<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>



<!-- Main CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/adminstyle.css') }}">


<script src="{{ asset('assets/plugins/apexchart/apexcharts.min.js') }}" type="e56d8e3ed6c4bef649884303-text/javascript"></script>
<script src="{{ asset('assets/js/script.js') }}"></script>
{{-- <script src="/cdn-cgi/scripts/7d0fa10a/cloudflare-static/rocket-loader.min.js"
    data-cf-settings="e56d8e3ed6c4bef649884303-|49" defer></script> --}}
<script defer src="https://static.cloudflareinsights.com/beacon.min.js/vcd15cbe7772f49c399c6a5babf22c1241717689176015"
    integrity="sha512-ZpsOmlRQV6y907TI0dKBHq9Md29nnaEIPlkf84rnaERnq6zvWvPUqr2ft8M1aS28oN72PdrCzSjY4U6VaAw1EQ=="
    data-cf-beacon='{"rayId":"978472bda86b0b57","version":"2025.8.0","serverTiming":{"name":{"cfExtPri":true,"cfEdge":true,"cfOrigin":true,"cfL4":true,"cfSpeedBrain":true,"cfCacheStatus":true}},"token":"3ca157e612a14eccbb30cf6db6691c29","b":1}'
    crossorigin="anonymous"></script>
<!-- REVOLUTION JS FILES -->
<script src="{{ asset('assets/styles/js/jquery.themepunch.tools.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/jquery.themepunch.revolution.min.js') }}"></script>
<!-- SLIDER REVOLUTION EXTENSIONS -->
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.actions.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.carousel.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.kenburn.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.layeranimation.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.migration.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.navigation.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.parallax.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.slideanims.min.js') }}"></script>
<script src="{{ asset('assets/styles/js/extensions/revolution.extension.video.min.js') }}"></script>
<!-- custom script -->
{{-- <script src="{{ asset('assets/styles/js/select2.min.js') }}"></script> --}}

<script src="{{ asset('assets/styles/js/date.js') }}"></script>
<script src="{{ asset('assets/styles/js/jquery.hoverdir.js') }}"></script>
<script src="{{ asset('assets/styles/js/jquery-ui.bundle.js') }}"></script>
<script src="{{ asset('assets/styles/js/flip.js') }}"></script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB4fusEY9kSwNHgtK8KOgyoTsyP5Tb2NXo"></script>
<script src="{{ asset('assets/medical/js/map.js') }}"></script>
<script src="{{ asset('assets/styles/js/contact_us.js') }}"></script>
<script src="{{ asset('assets/medical/js/script.js') }}"></script>
