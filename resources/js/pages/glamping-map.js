(function () {
    'use strict';

    // Toggle verbose logs without changing behavior
    var DEBUG = false;
    if (DEBUG && typeof console !== 'undefined' && console.log) {
        console.log('[glamping-map] script loaded');
    }

    // --- DOM helpers
    function qsa(selector, root) { return Array.prototype.slice.call((root || document).querySelectorAll(selector)); }

    var container = document.getElementById('glampingMap');
    if (!container) return;

    var availability = {};
    try { availability = JSON.parse(container.getAttribute('data-availability') || '{}'); } catch (e) { availability = {}; }

    var maps = qsa('[data-map]', container);
    var areaButtons = qsa('#areaItems [data-area]');
    var selectedEl = null;
    var selectedUnitId = null;


    // --- Styles
    function ensureGlobalUnitStyles() {
        if (document.getElementById('glamping-unit-styles')) return;
        var css = '\n.glamping-unit { transition: fill 150ms ease, stroke 150ms ease, opacity 150ms ease; }\n' +
                  '.glamping-unit.available { cursor: pointer; }\n' +
                  '.glamping-unit.booked { fill: #e74c3c !important; opacity: 0.75 !important; pointer-events: none !important; cursor: not-allowed !important; }\n' +
                  '.glamping-unit.selected { fill: #14532d !important; stroke: #14532d !important; }\n';
        var s = document.createElement('style'); s.id = 'glamping-unit-styles'; s.appendChild(document.createTextNode(css)); document.head.appendChild(s);
    }
    ensureGlobalUnitStyles();

    // --- SVG sizing
    function normalizeSvg(svg) {
        if (!svg) return;
        svg.removeAttribute('width');
        svg.removeAttribute('height');
        svg.setAttribute('preserveAspectRatio', 'xMidYMid meet');
        svg.style.width = '100%';
        svg.style.height = '100%';
        svg.style.maxWidth = '100%';
        svg.style.maxHeight = '100%';
        svg.style.display = 'block';
    }

    // --- Pan/Zoom for map (non-modal)
    function clamp(num, min, max) { return Math.max(min, Math.min(max, num)); }

    function getPanZoomState(wrap) {
        var scale = parseFloat((wrap && wrap.dataset && wrap.dataset.pzScale) || '1');
        var tx = parseFloat((wrap && wrap.dataset && wrap.dataset.pzTx) || '0');
        var ty = parseFloat((wrap && wrap.dataset && wrap.dataset.pzTy) || '0');
        if (!isFinite(scale) || scale <= 0) scale = 1;
        if (!isFinite(tx)) tx = 0;
        if (!isFinite(ty)) ty = 0;
        return { scale: scale, tx: tx, ty: ty };
    }

    function setPanZoomState(wrap, next) {
        if (!wrap || !wrap.dataset) return;
        wrap.dataset.pzScale = String(next.scale);
        wrap.dataset.pzTx = String(next.tx);
        wrap.dataset.pzTy = String(next.ty);
    }

    function applyPanZoom(wrap) {
        if (!wrap) return;
        var svg = wrap.querySelector && wrap.querySelector('svg');
        if (!svg) return;

        var st = getPanZoomState(wrap);
        // Translate first, then scale.
        svg.style.transformOrigin = '0 0';
        svg.style.transform = 'translate(' + st.tx + 'px,' + st.ty + 'px) scale(' + st.scale + ')';
    }

    function resetPanZoom(wrap) {
        if (!wrap) return;
        setPanZoomState(wrap, { scale: 1, tx: 0, ty: 0 });
        applyPanZoom(wrap);
    }

    function zoomBy(wrap, delta) {
        if (!wrap) return;
        var st = getPanZoomState(wrap);
        var nextScale = clamp(st.scale + delta, 1, 2.8);
        // Keep translation as-is (simple + predictable).
        setPanZoomState(wrap, { scale: nextScale, tx: st.tx, ty: st.ty });
        applyPanZoom(wrap);
    }

    function installPanZoom(wrap) {
        if (!wrap || wrap.dataset && wrap.dataset.panzoomInstalled === '1') return;
        if (wrap.dataset) wrap.dataset.panzoomInstalled = '1';

        // Make panning feel natural
        try { wrap.style.cursor = 'grab'; } catch (e) {}
        try { wrap.style.touchAction = 'none'; } catch (e) {}

        resetPanZoom(wrap);

        var isDown = false;
        var moved = false;
        var startX = 0, startY = 0;
        var baseTx = 0, baseTy = 0;
        var threshold = 6;
        var captured = false;
        var activePointerId = null;

        function onPointerDown(ev) {
            // left click / primary touch
            if (ev && ev.button != null && ev.button !== 0) return;
            isDown = true;
            moved = false;
            captured = false;
            activePointerId = ev.pointerId;

            var st = getPanZoomState(wrap);
            baseTx = st.tx;
            baseTy = st.ty;
            startX = ev.clientX;
            startY = ev.clientY;

            try { wrap.style.cursor = 'grabbing'; } catch (e) {}
        }

        function onPointerMove(ev) {
            if (!isDown) return;
            var dx = ev.clientX - startX;
            var dy = ev.clientY - startY;

            if (!moved && (Math.abs(dx) >= threshold || Math.abs(dy) >= threshold)) {
                moved = true;
                // Only capture pointer after intent to drag is clear.
                try { wrap.setPointerCapture(activePointerId); captured = true; } catch (e) { captured = false; }
            }
            if (!moved) return;

            var st = getPanZoomState(wrap);
            setPanZoomState(wrap, { scale: st.scale, tx: baseTx + dx, ty: baseTy + dy });
            applyPanZoom(wrap);
            try { ev.preventDefault(); } catch (e) {}
        }

        function endDrag(ev) {
            if (!isDown) return;
            isDown = false;
            if (captured) {
                try { wrap.releasePointerCapture(activePointerId); } catch (e) {}
            }
            try { wrap.style.cursor = 'grab'; } catch (e) {}
            if (moved) {
                // Suppress the click that usually follows a drag
                wrap.__pz_suppress_click_until = Date.now() + 250;
            }
            captured = false;
            activePointerId = null;
        }

        wrap.addEventListener('pointerdown', onPointerDown);
        wrap.addEventListener('pointermove', onPointerMove);
        wrap.addEventListener('pointerup', endDrag);
        wrap.addEventListener('pointercancel', endDrag);

        // Capture click and suppress if it was a drag
        wrap.addEventListener('click', function (e) {
            if (wrap.__pz_suppress_click_until && Date.now() < wrap.__pz_suppress_click_until) {
                try { e.preventDefault(); } catch (err) {}
                try { e.stopPropagation(); } catch (err2) {}
            }
        }, true);
    }

    // --- color parsing / green detection
    function parseRgbValues(str) {
        if (!str) return null;
        str = String(str).trim();
        if (!str) return null;
        if (str.charAt(0) === '#') {
            var hex = str.slice(1);
            if (hex.length === 3) hex = hex.split('').map(function (c) { return c + c; }).join('');
            if (hex.length !== 6) return null;
            return [parseInt(hex.slice(0,2),16), parseInt(hex.slice(2,4),16), parseInt(hex.slice(4,6),16)];
        }
        var m = str.match(/rgb\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/i);
        if (m) return [parseInt(m[1],10), parseInt(m[2],10), parseInt(m[3],10)];
        return null;
    }
    function isGreenColor(colorStr) {
        var vals = parseRgbValues(colorStr);
        if (!vals) return false;
        var r = vals[0], g = vals[1], b = vals[2];
        return (g > r + 20 && g > b + 20 && g > 80);
    }

    // --- Inline SVGs
    function isMapVisibleForWrap(wrap) {
        try {
            var mw = wrap && wrap.closest && wrap.closest('[data-map]');
            if (!mw) return true;
            return !mw.classList.contains('hidden');
        } catch (e) {
            return true;
        }
    }

    function getVisibleSvgWraps() {
        var wrappers = qsa('.svg-wrap', container);
        return wrappers.filter(function (w) { return isMapVisibleForWrap(w); });
    }

    function processInlinedWrap(wrap) {
        if (!wrap) return;
        // Always (re)apply marking + mapping when a map is shown.
        // This avoids timing issues where SVG loads but interactive units aren't ready.
        installPanZoom(wrap);
        applyPanZoom(wrap);
        markGreenObjects(wrap);
        try { bindDomUnitsToAreaUnits(wrap); } catch (e) { try { if (DEBUG) console.error('[glamping-map] bindDomUnitsToAreaUnits error', e); } catch (error_) {} }
        attachUnitHandlers();
    }

    function inlineSVGs(opts) {
        opts = opts || {};
        var wrappers = Array.isArray(opts.wrappers) ? opts.wrappers : qsa('.svg-wrap', container);
        if (opts.onlyVisible) {
            wrappers = wrappers.filter(function (w) { return isMapVisibleForWrap(w); });
        }
        try { if (DEBUG) console.log('[glamping-map] inlineSVGs wrappers=', wrappers.length); } catch (e) {}
        wrappers.forEach(function (wrap) {
            var src = wrap.getAttribute('data-src');
            if (!src || wrap.getAttribute('data-inlined') === '1') return;
            try { if (DEBUG) console.log('[glamping-map] fetching svg', src); } catch (e) {}
            fetch(src).then(function (r) { return r.text(); }).then(function (txt) {
                wrap.innerHTML = txt;
                wrap.setAttribute('data-inlined', '1');
                var svg = wrap.querySelector('svg');
                if (svg) normalizeSvg(svg);
                try { if (DEBUG) console.log('[glamping-map] inlined svg for', src); } catch (e) {}
                processInlinedWrap(wrap);
            }).catch(function (err) { try { console.error('[glamping-map] failed to fetch svg', src, err); } catch (e) {} });
        });
    }

    // Map detected interactive DOM units in an inlined SVG to server-provided areaUnits
    function bindDomUnitsToAreaUnits(wrap) {
        if (!wrap) return;
        var mapWrap = wrap.closest('[data-map]');
        if (!mapWrap) return;
        var areaKey = mapWrap.getAttribute('data-map');
        var areaUnitsMap = {};
        try { areaUnitsMap = JSON.parse(container.getAttribute('data-area-units') || '{}'); } catch (e) { areaUnitsMap = {}; }
        var list = areaUnitsMap[areaKey] || [];
        if (!list || !list.length) return;

        function getElementCenter(el) {
            if (!el) return { x: 0, y: 0 };
            // Prefer SVG space coordinates
            try {
                if (typeof el.getBBox === 'function') {
                    var bb = el.getBBox();
                    return { x: bb.x + bb.width / 2, y: bb.y + bb.height / 2 };
                }
            } catch (e) {}
            // Fallback to screen space
            try {
                var r = el.getBoundingClientRect();
                return { x: r.left + r.width / 2, y: r.top + r.height / 2 };
            } catch (e2) {}
            return { x: 0, y: 0 };
        }

        function unitNumber(u) {
            try {
                var m = String((u && u.name) || '').match(/(\d{1,3})/);
                if (m) return parseInt(m[1], 10);
            } catch (e) {}
            return null;
        }

        function sortUnitsByNumber(units) {
            return (units || []).slice().sort(function (a, b) {
                var na = unitNumber(a);
                var nb = unitNumber(b);
                if (na !== null && nb !== null) return na - nb;
                if (na !== null) return -1;
                if (nb !== null) return 1;
                return String(a.name || '').localeCompare(String(b.name || ''));
            });
        }

        function sortDomByPosition(domEls) {
            var mode = 'yx';
            // Allow per-area overrides if needed later
            // mode: 'yx' (top->bottom then left->right) or 'xy' (left->right then top->bottom)
            if (areaKey === 'pineus-tilu-2') mode = 'yx';
            if (areaKey === 'pineus-tilu-1') mode = 'yx';
            if (areaKey === 'pineus-tilu-3-vip') mode = 'yx';
            if (areaKey === 'pineus-tilu-4') mode = 'yx';
            if (areaKey === 'pineus-tilu-cabin') mode = 'yx';

            return (domEls || []).slice().sort(function (a, b) {
                var pa = getElementCenter(a);
                var pb = getElementCenter(b);
                if (mode === 'xy') {
                    if (pa.x !== pb.x) return pa.x - pb.x;
                    return pa.y - pb.y;
                }
                if (pa.y !== pb.y) return pa.y - pb.y;
                return pa.x - pb.x;
            });
        }

        // collect unique interactive ancestor elements in document order
        // treat auto-generated data-unit values like "areaKey-gen-N" as "generated"
        var seen = new Set();
        var interactiveList = [];
        var all = wrap.querySelectorAll('[data-unit]');
        try { if (DEBUG) console.log('[glamping-map] bindDomUnitsToAreaUnits areaKey=', areaKey, 'domCandidates=', all.length, 'serverUnits=', list.length); } catch (e) {}
        all.forEach(function (el) {
            var top = el.closest('[data-unit]');
            if (!top) return;
            var key = top.getAttribute('data-unit');
            if (!key) return;
            if (seen.has(key)) return;
            seen.add(key);
            // mark whether this element currently carries an auto-generated key
            var generated = (String(key).indexOf(areaKey + '-gen-') === 0);
            interactiveList.push({ el: top, key: key, generated: generated });
        });

        // Build a numeric lookup from unit name -> unit object (try to extract deck/plot number)
        var unitNumberMap = {}; // number -> unit
        var unitNameMap = {}; // normalized name -> unit
        list.forEach(function (u) {
            var n = null;
            try {
                var m = (u.name || '').match(/(\d{1,3})/);
                if (m) n = m[1];
            } catch (e) {}
            if (n) unitNumberMap[String(n)] = u;
            var norm = String(u.name || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ').trim();
            if (norm) unitNameMap[norm] = u;
        });

        function detectNumberFromElement(el) {
            if (!el) return null;
            // check explicit attributes first
            var a = (el.getAttribute && el.getAttribute('data-deck')) || (el.getAttribute && el.getAttribute('data-unit-number')) || el.id || '';
            var m = String(a || '').match(/(\d{1,3})/);
            if (m) return m[1];
            // check title/aria-label
            a = (el.getAttribute && el.getAttribute('aria-label')) || (el.getAttribute && el.getAttribute('title')) || '';
            m = String(a || '').match(/(\d{1,3})/);
            if (m) return m[1];
            // check descendant text nodes (text, tspan)
            var txtEls = el.querySelectorAll && el.querySelectorAll('text,tspan');
            if (txtEls && txtEls.length) {
                for (var i = 0; i < txtEls.length; i++) {
                    var txt = (txtEls[i].textContent || '').trim();
                    var mm = txt.match(/(\d{1,3})/);
                    if (mm) return mm[1];
                }
            }
            // try plain text content of element
            var content = (el.textContent || '').trim();
            m = content.match(/(\d{1,3})/);
            if (m) return m[1];
            return null;
        }

        // First pass: map DOM units by explicit number found in SVG text/attributes
        var assigned = {};
        interactiveList.forEach(function (obj) {
            var domEl = obj.el;
            var number = detectNumberFromElement(domEl);
            var unit = null;
            if (number && unitNumberMap[String(number)]) unit = unitNumberMap[String(number)];
            if (!unit) {
                // try matching by normalized name text inside domEl
                var txt = (domEl.textContent || '').toLowerCase().replace(/[^a-z0-9]+/g, ' ').trim();
                if (txt && unitNameMap[txt]) unit = unitNameMap[txt];
            }
            if (unit) {
                var unitId = String(unit.id);
                domEl.setAttribute('data-unit', unitId);
                var descendants = domEl.querySelectorAll('*');
                descendants.forEach(function (d) { try { d.setAttribute('data-unit', unitId); } catch (e) {} });
                assigned[unitId] = true;
                try { if (DEBUG) console.log('[glamping-map] mapped dom element to unit', unitId, 'number=', number, 'name=', unit.name); } catch (e) {}
            }
        });

        // Second pass: spatial mapping based on nearest numeric label in the SVG.
        // This handles SVGs where the deck number text is a sibling (not a descendant) of the green shape.
        try {
            var svgRoot2 = wrap.querySelector && wrap.querySelector('svg');
            if (svgRoot2) {
                var svgBox2 = null;
                try { svgBox2 = svgRoot2.getBBox(); } catch (e) { svgBox2 = null; }
                var maxDist = 140;
                if (svgBox2 && svgBox2.width && svgBox2.height) {
                    maxDist = Math.max(60, Math.min(220, Math.min(svgBox2.width, svgBox2.height) * 0.25));
                }

                var labels = [];
                var textNodes = svgRoot2.querySelectorAll('text');
                textNodes.forEach(function (te) {
                    try {
                        var raw = (te.textContent || '').trim();
                        var mm = raw.match(/^\s*(\d{1,3})\s*$/);
                        if (!mm) return;
                        var num = String(mm[1]);
                        if (!unitNumberMap[num]) return;
                        var bb = te.getBBox();
                        labels.push({ num: num, x: bb.x + bb.width / 2, y: bb.y + bb.height / 2 });
                    } catch (e) {}
                });

                if (labels.length) {
                    var usedNums = {};
                    Object.keys(assigned).forEach(function (uid) {
                        try {
                            var u = list.find(function (it) { return String(it.id) === String(uid); });
                            if (!u) return;
                            var m2 = (u.name || '').match(/(\d{1,3})/);
                            if (m2) usedNums[String(m2[1])] = true;
                        } catch (e) {}
                    });

                    // Map only elements that are still generated/unmapped
                    interactiveList.forEach(function (obj) {
                        var domEl = obj.el;
                        var cur = domEl.getAttribute && domEl.getAttribute('data-unit');
                        if (!cur || String(cur).indexOf(areaKey + '-gen-') !== 0) return;

                        var bb = null;
                        try { bb = domEl.getBBox(); } catch (e) { bb = null; }
                        if (!bb) return;
                        var cx = bb.x + bb.width / 2;
                        var cy = bb.y + bb.height / 2;

                        // nearest label first
                        var candidates = labels
                            .map(function (l) {
                                var dx = l.x - cx;
                                var dy = l.y - cy;
                                return { num: l.num, dist: Math.sqrt(dx * dx + dy * dy) };
                            })
                            .sort(function (a, b) { return a.dist - b.dist; });

                        for (var i = 0; i < candidates.length; i++) {
                            var c = candidates[i];
                            if (c.dist > maxDist) break;
                            if (usedNums[c.num]) continue;
                            var unit = unitNumberMap[c.num];
                            if (!unit) continue;
                            var unitId = String(unit.id);
                            if (assigned[unitId]) { usedNums[c.num] = true; continue; }
                            domEl.setAttribute('data-unit', unitId);
                            var descendants = domEl.querySelectorAll('*');
                            descendants.forEach(function (d) { try { d.setAttribute('data-unit', unitId); } catch (e) {} });
                            assigned[unitId] = true;
                            usedNums[c.num] = true;
                            try { domEl.setAttribute('data-unit-number', c.num); } catch (e) {}
                            try { if (DEBUG) console.log('[glamping-map] spatial-mapped dom element to unit', unitId, 'number=', c.num, 'name=', unit.name); } catch (e) {}
                            break;
                        }
                    });
                }
            }
        } catch (e) {}

        // Fallback: if some DOM elements remain unmapped, assign remaining units
        // by DOM document order matched to unit numbering (1..N).
        // This works because SVG paths are authored in deck order (Deck 1, Deck 2, ...),
        // and the unit list is sorted numerically by deck number.
        var remainingUnits = sortUnitsByNumber(list.filter(function (u) { return !assigned[String(u.id)]; }));
        // Preserve SVG document order for DOM elements - do NOT use spatial sorting.
        // SVG paths are created in deck order, so document order is the most reliable.
        var remainingDom = interactiveList.filter(function (obj) {
            var d = obj.el;
            var cur = d.getAttribute && d.getAttribute('data-unit');
            if (!cur) return true;
            return String(cur).indexOf(areaKey + '-gen-') === 0;
        }).map(function (obj) { return obj.el; });

        var count = Math.min(remainingUnits.length, remainingDom.length);
        try { if (DEBUG) console.log('[glamping-map] fallback mapping: remainingUnits=', remainingUnits.length, 'remainingDom=', remainingDom.length); } catch (e) {}
        for (var i = 0; i < count; i++) {
            var domEl2 = remainingDom[i];
            var unit2 = remainingUnits[i];
            if (!domEl2 || !unit2) continue;
            var unitId2 = String(unit2.id);
            try { if (DEBUG) console.log('[glamping-map] fallback mapping index=', i, 'domEl key=', domEl2.getAttribute('data-unit'), 'unit=', unit2.name, 'unitId=', unitId2); } catch (e) {}
            domEl2.setAttribute('data-unit', unitId2);
            var descendants2 = domEl2.querySelectorAll('*');
            descendants2.forEach(function (d) { try { d.setAttribute('data-unit', unitId2); } catch (e) {} });
            try { if (DEBUG) console.log('[glamping-map] fallback mapped dom element to unit', unitId2, 'name=', unit2.name); } catch (e) {}
        }
    }

    // --- Mark green shapes as interactive units (group-aware)
    function markGreenObjects(root) {
        if (!root) return;
        var svg = root.querySelector('svg');
        if (!svg) return;
        var areaKey = (svg.closest && svg.closest('[data-map]') && svg.closest('[data-map]').getAttribute('data-map')) || 'area';
        var idx = 0;

        function findInteractiveAncestor(el) {
            var cur = el, lastGroup = null;
            while (cur && cur !== svg) {
                if (cur.tagName && cur.tagName.toLowerCase() === 'g') lastGroup = cur;
                cur = cur.parentNode;
            }
            if (lastGroup) {
                try { var bb = lastGroup.getBBox(); if (bb.width >= 6 && bb.height >= 6) return lastGroup; } catch (e) { return lastGroup; }
            }
            return el;
        }

        var shapes = svg.querySelectorAll('path,rect,polygon,circle,ellipse,g');
        shapes.forEach(function (el) {
            try {
                var fillAttr = (el.getAttribute && el.getAttribute('fill')) || '';
                var styleFill = (window.getComputedStyle && window.getComputedStyle(el).fill) || '';
                var fillToTest = fillAttr || styleFill || '';

                if (!isGreenColor(fillToTest)) return;
                var interactive = findInteractiveAncestor(el) || el;
                try { var b = interactive.getBBox(); if (b.width < 6 && b.height < 6) return; } catch (e) {}
                if (!interactive.hasAttribute('data-unit')) { idx += 1; interactive.setAttribute('data-unit', areaKey + '-gen-' + idx); }
                var unitId = interactive.getAttribute('data-unit');
                interactive.setAttribute('data-status', 'available');
                interactive.classList.add('glamping-unit','available');
                interactive.setAttribute('tabindex', '0');
                try { interactive.style.cursor = 'pointer'; interactive.style.pointerEvents = 'auto'; } catch (e) {}
                var descendants = interactive.querySelectorAll('*');
                descendants.forEach(function (d) { try { d.setAttribute('data-unit', unitId); d.setAttribute('data-status', 'available'); d.classList.add('glamping-unit','available'); try { d.style.cursor = 'pointer'; d.style.pointerEvents = 'auto'; } catch (e) {} } catch (e) {} });
            } catch (e) {}
        });
    }

    // NOTE: No dummy availability overrides. Booked/available is driven by backend.

    // --- Selection visuals: save/restore original attrs and apply selected style
    function applySelectedStyle(unitId) {
        if (!unitId) return;
        var members = qsa('[data-unit="' + unitId + '"]', container);
        members.forEach(function (m) {
            try {
                // store original values if not already stored
                if (m.dataset && m.dataset.origFill === undefined) {
                    var origFill = (m.getAttribute && m.getAttribute('fill')) || (m.style && m.style.fill) || ((window.getComputedStyle && window.getComputedStyle(m).fill) || '');
                    var origStroke = (m.getAttribute && m.getAttribute('stroke')) || (m.style && m.style.stroke) || ((window.getComputedStyle && window.getComputedStyle(m).stroke) || '');
                    m.dataset.origFill = origFill;
                    m.dataset.origStroke = origStroke;
                }
                try { m.setAttribute('fill', '#14532d'); } catch (e) {}
                try { m.style.fill = '#14532d'; } catch (e) {}
                try { m.setAttribute('stroke', '#14532d'); m.style.stroke = '#14532d'; } catch (e) {}

                // if group, apply to children as well
                if (m.tagName && m.tagName.toLowerCase() === 'g') {
                    var ch = m.querySelectorAll('*');
                    ch.forEach(function (c) {
                        try {
                            if (c.dataset && c.dataset.origFill === undefined) {
                                var of = (c.getAttribute && c.getAttribute('fill')) || (c.style && c.style.fill) || ((window.getComputedStyle && window.getComputedStyle(c).fill) || '');
                                var os = (c.getAttribute && c.getAttribute('stroke')) || (c.style && c.style.stroke) || ((window.getComputedStyle && window.getComputedStyle(c).stroke) || '');
                                c.dataset.origFill = of;
                                c.dataset.origStroke = os;
                            }
                            try { c.setAttribute('fill', '#14532d'); } catch (e) {}
                            try { c.style.fill = '#14532d'; } catch (e) {}
                            try { c.setAttribute('stroke', '#14532d'); c.style.stroke = '#14532d'; } catch (e) {}
                        } catch (e) {}
                    });
                }
            } catch (e) {}
        });
    }

    function clearSelectedStyle(unitId) {
        if (!unitId) return;
        var members = qsa('[data-unit="' + unitId + '"]', container);
        members.forEach(function (m) {
            try {
                if (m.dataset && (m.dataset.origFill !== undefined || m.dataset.origStroke !== undefined)) {
                    try { if (m.dataset.origFill) m.setAttribute('fill', m.dataset.origFill); else m.removeAttribute('fill'); } catch (e) { try { m.style.fill = m.dataset.origFill || ''; } catch (e) {} }
                    try { if (m.dataset.origStroke) m.setAttribute('stroke', m.dataset.origStroke); else m.removeAttribute('stroke'); } catch (e) { try { m.style.stroke = m.dataset.origStroke || ''; } catch (e) {} }
                    try { m.style.fill = m.dataset.origFill || ''; } catch (e) {}
                    try { m.style.stroke = m.dataset.origStroke || ''; } catch (e) {}
                    try { delete m.dataset.origFill; delete m.dataset.origStroke; } catch (e) {}
                } else {
                    try { m.removeAttribute('fill'); } catch (e) {}
                    try { m.removeAttribute('stroke'); } catch (e) {}
                    try { m.style.fill = ''; } catch (e) {}
                    try { m.style.stroke = ''; } catch (e) {}
                }

                if (m.tagName && m.tagName.toLowerCase() === 'g') {
                    var ch = m.querySelectorAll('*');
                    ch.forEach(function (c) {
                        try {
                            if (c.dataset && (c.dataset.origFill !== undefined || c.dataset.origStroke !== undefined)) {
                                try { if (c.dataset.origFill) c.setAttribute('fill', c.dataset.origFill); else c.removeAttribute('fill'); } catch (e) {}
                                try { if (c.dataset.origStroke) c.setAttribute('stroke', c.dataset.origStroke); else c.removeAttribute('stroke'); } catch (e) {}
                                try { c.style.fill = c.dataset.origFill || ''; } catch (e) {}
                                try { c.style.stroke = c.dataset.origStroke || ''; } catch (e) {}
                                try { delete c.dataset.origFill; delete c.dataset.origStroke; } catch (e) {}
                            } else {
                                try { c.removeAttribute('fill'); } catch (e) {}
                                try { c.removeAttribute('stroke'); } catch (e) {}
                                try { c.style.fill = ''; } catch (e) {}
                                try { c.style.stroke = ''; } catch (e) {}
                            }
                        } catch (e) {}
                    });
                }
            } catch (e) {}
        });
    }

    function clearSelectionState(opts) {
        opts = opts || {};
        var hiddenInput = opts.hiddenInput || document.getElementById('selected_unit');
        var prev = (hiddenInput && hiddenInput.value) ? String(hiddenInput.value) : (selectedUnitId ? String(selectedUnitId) : null);

        if (prev) {
            var prevMembers = qsa('[data-unit="' + prev + '"]', container);
            prevMembers.forEach(function (pm) { try { pm.classList.remove('selected'); } catch (e) {} });
            clearSelectedStyle(prev);
        }

        if (selectedEl) {
            try { selectedEl.classList.remove('ring-4','ring-green-600','ring-offset-2'); } catch (e) {}
        }

        selectedEl = null;
        selectedUnitId = null;

        if (hiddenInput) {
            try { hiddenInput.value = ''; } catch (e) {}
            try { hiddenInput.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) {}
        }

        if (opts.resetFormFields) {
            var selectedUnitText = document.getElementById('selectedUnit');
            if (selectedUnitText) {
                try { selectedUnitText.value = '-'; } catch (e) {}
                try { selectedUnitText.dispatchEvent(new Event('input', { bubbles: true })); } catch (e) {}
            }
        }
    }

    // --- Delegated handlers + normalize statuses
    function attachUnitHandlers() {
        var allUnitEls = qsa('[data-unit]', container);
        var hiddenInput = document.getElementById('selected_unit');
        if (!hiddenInput) { hiddenInput = document.createElement('input'); hiddenInput.type = 'hidden'; hiddenInput.name = 'unit_id'; hiddenInput.id = 'selected_unit'; container.appendChild(hiddenInput); }

        try { if (DEBUG) console.log('[glamping-map] attachUnitHandlers found allUnitEls=', allUnitEls.length); } catch (e) {}

        allUnitEls.forEach(function (el) {
            var unitId = el.getAttribute('data-unit');
            if (!unitId) return;
            // availability keys may be numeric (unit id) or legacy strings; prefer numeric
            var rawStatus = availability[unitId] || availability[String(unitId)] || el.getAttribute('data-status') || ((String(unitId).indexOf('-gen-') !== -1) ? 'available' : 'booked');
            var status = (rawStatus === 'booked') ? 'booked' : 'available';

            try {
                el.classList.remove('available', 'booked', 'opacity-70', 'cursor-not-allowed');
                el.classList.remove('glamping-unit');
            } catch (e) {}

            el.setAttribute('data-status', status);
            el.classList.add('transition-all','duration-200','ease-in-out');
            if (status === 'available') {
                el.classList.add('glamping-unit','available');
                el.setAttribute('tabindex', '0');
                try { el.style.cursor = 'pointer'; el.style.pointerEvents = 'auto'; } catch (e) {}
            } else {
                el.classList.add('glamping-unit','booked','opacity-70','cursor-not-allowed');
                try { el.style.cursor = 'not-allowed'; el.style.pointerEvents = 'none'; } catch (e) {}
            }
        });

        try {
            if (selectedUnitId) {
                var selectedStatus = availability[selectedUnitId] || availability[String(selectedUnitId)] || null;
                if (selectedStatus && selectedStatus !== 'available') {
                    clearSelectionState({ hiddenInput: hiddenInput, resetFormFields: true });
                }
            }
        } catch (e) {}

        if (container.__glamping_delegation_installed) return;

        container.addEventListener('click', function (ev) {
            try { if (DEBUG) console.log('[glamping-map] container click', ev.target && ev.target.tagName); } catch (e) {}
            var el = ev.target.closest && ev.target.closest('[data-unit]');
            try { if (DEBUG) console.log('[glamping-map] click resolved el=', !!el, el && el.getAttribute && el.getAttribute('data-unit')); } catch (e) {}
            if (!el || el.getAttribute('data-status') !== 'available') return;
            var mapWrapper = el.closest('[data-map]');
            if (!mapWrapper || mapWrapper.classList.contains('hidden')) return;

            var prev = hiddenInput ? hiddenInput.value : null;
            if (prev && prev === el.getAttribute('data-unit')) {
                var prevMembers = qsa('[data-unit="' + prev + '"]', container); prevMembers.forEach(function (pm) { pm.classList.remove('selected'); });
                clearSelectionState({ hiddenInput: hiddenInput, resetFormFields: true });
                return;
            }

            if (prev) { var prevMembers2 = qsa('[data-unit="' + prev + '"]', container); prevMembers2.forEach(function (pm) { pm.classList.remove('selected'); }); clearSelectedStyle(prev); }

            el.classList.add('ring-4','ring-green-600','ring-offset-2'); selectedEl = el; var unitVal = el.getAttribute('data-unit') || el.id || ''; hiddenInput.value = unitVal; selectedUnitId = unitVal;
            var members = qsa('[data-unit="' + unitVal + '"]', container); members.forEach(function (m) { m.classList.add('selected'); });
            applySelectedStyle(unitVal);
                // update reservation form fields (selected unit name, guest defaults/limits)
                try {
                var areaUnitsMap = {};
                try { areaUnitsMap = JSON.parse(container.getAttribute('data-area-units') || '{}'); } catch (e) { areaUnitsMap = {}; }
                var found = null;
                Object.keys(areaUnitsMap).some(function (slug) {
                    var arr = areaUnitsMap[slug] || [];
                    for (var ii = 0; ii < arr.length; ii++) {
                        if (String(arr[ii].id) === String(unitVal)) { found = Object.assign({}, arr[ii]); found._area = slug; return true; }
                    }
                    return false;
                });
                    if (found) {
                        try { if (DEBUG) console.log('[glamping-map] click -> unitVal=', unitVal, 'found=', found); } catch (e) {}
                        // commit selection in a single place to avoid partial updates
                        (function commitSelection(foundUnit, unitId) {
                            // mark that user actively selected a unit
                            try { container.setAttribute('data-user-selected', '1'); } catch (e) {}

                            var guestCountEl = document.getElementById('guestCount');
                            if (guestCountEl) {
                                try { guestCountEl.value = foundUnit.default_people || guestCountEl.value || 1; } catch (e) {}
                                try {
                                    guestCountEl.dataset.min = 1;
                                    guestCountEl.dataset.max = foundUnit.max_people || 20;
                                    guestCountEl.dataset.defaultPeople = foundUnit.default_people || 0;
                                } catch (e) {}
                                try { guestCountEl.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) {}
                            }

                            var selectedUnitText = document.getElementById('selectedUnit');
                            if (selectedUnitText) {
                                try {
                                    // ensure we always overwrite dummy when user selects
                                    selectedUnitText.value = foundUnit.name || ('Deck ' + (foundUnit.name && foundUnit.name.match(/(\d+)/) ? foundUnit.name.match(/(\d+)/)[1] : unitId));
                                } catch (e) {}
                                try { selectedUnitText.dispatchEvent(new Event('input', { bubbles: true })); } catch (e) {}
                            }

                            var selectedAreaText = document.getElementById('selectedArea');
                            if (selectedAreaText) {
                                var areaNames = {};
                                try { areaNames = JSON.parse(container.getAttribute('data-area-names') || '{}'); } catch (e) { areaNames = {}; }
                                var display = (foundUnit._area && (areaNames[foundUnit._area] || null)) || null;
                                if (!display && foundUnit._area) {
                                    try {
                                        display = String(foundUnit._area).split('-').map(function (s) { return s.charAt(0).toUpperCase() + s.slice(1); }).join(' ');
                                        if (display.match(/\bVIP\b/i) || display.match(/-vip$/i)) {
                                            display = display.replace(/\s*VIP\s*$/i, ' (VIP)');
                                        }
                                    } catch (e) { display = foundUnit._area; }
                                }
                                try { selectedAreaText.value = display || selectedAreaText.value; } catch (e) {}
                                try { selectedAreaText.dispatchEvent(new Event('input', { bubbles: true })); } catch (e) {}
                            }

                            // update hidden input value (single source of truth)
                            try { if (hiddenInput) hiddenInput.value = unitId; } catch (e) {}
                            try { if (hiddenInput) hiddenInput.dispatchEvent(new Event('change', { bubbles: true })); } catch (e) {}
                            selectedUnitId = unitId;
                        })(found, unitVal);
                        try { if (DEBUG) console.log('[glamping-map] selection committed -> hiddenInput=', hiddenInput ? hiddenInput.value : null, 'selectedUnit=', (document.getElementById('selectedUnit') || {}).value); } catch (e) {}
                    }
            } catch (e) {}
        });

        container.addEventListener('keydown', function (ev) { if (ev.key === 'Enter' || ev.key === ' ') { var el = ev.target.closest && ev.target.closest('[data-unit]'); if (el) { ev.preventDefault(); el.click(); } } });

        container.__glamping_delegation_installed = true;
    }

    // --- Initialization and area controls
    function showMap(areaKey) {
        // Switching areas should clear prior unit selection (including styles)
        clearSelectionState({ resetFormFields: true });

        // Update selected area field immediately
        try {
            var selectedAreaText = document.getElementById('selectedArea');
            if (selectedAreaText) {
                var areaNames = {};
                try { areaNames = JSON.parse(container.getAttribute('data-area-names') || '{}'); } catch (e) { areaNames = {}; }
                var display = (areaNames && areaNames[areaKey]) ? areaNames[areaKey] : null;
                if (!display) {
                    try {
                        display = String(areaKey).split('-').map(function (s) { return s.charAt(0).toUpperCase() + s.slice(1); }).join(' ');
                        if (display.match(/\bVIP\b/i) || display.match(/-vip$/i)) {
                            display = display.replace(/\s*VIP\s*$/i, ' (VIP)');
                        }
                    } catch (e) { display = areaKey; }
                }
                try { selectedAreaText.value = display || selectedAreaText.value; } catch (e) {}
                try { selectedAreaText.dispatchEvent(new Event('input', { bubbles: true })); } catch (e) {}
            }
        } catch (e) {}

        var found = false;
        maps.forEach(function (m) {
            if (m.getAttribute('data-map') === areaKey) {
                found = true;
                m.classList.remove('hidden'); m.classList.add('flex');
                setTimeout(function () {
                    var wrap = m.querySelector('.svg-wrap');
                    if (!wrap) return;
                    if (wrap.getAttribute('data-inlined') === '1') {
                        var svg = wrap.querySelector('svg');
                        if (svg) normalizeSvg(svg);
                        processInlinedWrap(wrap);
                    } else {
                        inlineSVGs({ wrappers: [wrap] });
                    }
                }, 60);
            } else { m.classList.remove('flex'); m.classList.add('hidden'); }
        });

        // If nothing matched the requested area, show the first map as a safe fallback
        if (!found && maps && maps.length) {
            var first = maps[0];
            first.classList.remove('hidden'); first.classList.add('flex');
            setTimeout(function () {
                var wrap = first.querySelector('.svg-wrap');
                if (!wrap) return;
                if (wrap.getAttribute('data-inlined') === '1') {
                    var svg = wrap.querySelector('svg');
                    if (svg) normalizeSvg(svg);
                    processInlinedWrap(wrap);
                } else {
                    inlineSVGs({ wrappers: [wrap] });
                }
            }, 60);
        }
    }

    function setAriaForArea(areaKey) { areaButtons.forEach(function (b) { try { b.setAttribute('aria-pressed', b.getAttribute('data-area') === areaKey ? 'true' : 'false'); } catch (e) {} }); }
    areaButtons.forEach(function (btn) { btn.addEventListener('click', function () { var area = btn.getAttribute('data-area'); if (!area) return; showMap(area); setAriaForArea(area); }); });

    var defaultArea = container.getAttribute('data-default-area') || (maps && maps[0] && maps[0].getAttribute('data-map')) || '';
    setTimeout(function () { showMap(defaultArea); setAriaForArea(defaultArea); }, 10);

    // Zoom controls (buttons live in each map wrapper)
    container.addEventListener('click', function (ev) {
        var btn = ev.target && ev.target.closest && ev.target.closest('[data-zoom-in],[data-zoom-out],[data-zoom-reset]');
        if (!btn) return;
        var mapWrap = btn.closest && btn.closest('[data-map]');
        if (!mapWrap) return;
        var wrap = mapWrap.querySelector && mapWrap.querySelector('.svg-wrap');
        if (!wrap) return;

        // Ensure pan/zoom installed even if SVG isn't inlined yet
        installPanZoom(wrap);

        if (btn.hasAttribute('data-zoom-in')) zoomBy(wrap, 0.2);
        else if (btn.hasAttribute('data-zoom-out')) zoomBy(wrap, -0.2);
        else if (btn.hasAttribute('data-zoom-reset')) resetPanZoom(wrap);

        try { ev.preventDefault(); } catch (e) {}
        try { ev.stopPropagation(); } catch (e2) {}
    });

    // initial inline (only currently visible map to reduce initial network/CPU)
    setTimeout(function () {
        inlineSVGs({ wrappers: getVisibleSvgWraps(), onlyVisible: true });
    }, 20);

    // Allow other modules (e.g. datepicker flow) to refresh deck availability without page reload.
    globalThis.glampingMapSetAvailability = function (nextAvailability) {
        availability = (nextAvailability && typeof nextAvailability === 'object') ? nextAvailability : {};
        attachUnitHandlers();
    };

    // Allow other modules to clear selected deck when check-in changes.
    globalThis.glampingMapClearSelection = function () {
        clearSelectionState({ resetFormFields: true });
    };

    // observe aria changes on area buttons (compat)
    try {
        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (m) {
                if (m.type === 'attributes' && m.attributeName === 'aria-pressed') {
                    var t = m.target;
                    if (t && t.getAttribute && t.getAttribute('aria-pressed') === 'true') {
                        var area = t.getAttribute('data-area');
                        if (area) {
                            showMap(area);
                            clearSelectionState({ resetFormFields: true });
                        }
                    }
                }
            });
        });
        areaButtons.forEach(function (b) { observer.observe(b, { attributes: true }); });
    } catch (e) {}

})();
