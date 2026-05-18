function safeJsonParse(value, fallback) {
    if (typeof value !== 'string' || value.trim() === '') return fallback;
    try {
        const parsed = JSON.parse(value);
        return parsed == null ? fallback : parsed;
    } catch (err) {
        return fallback;
    }
}

function debounce(fn, waitMs) {
    let t = null;
    return function (...args) {
        if (t) clearTimeout(t);
        t = setTimeout(() => fn.apply(this, args), waitMs);
    };
}

document.addEventListener('DOMContentLoaded', () => {
    const items = document.querySelectorAll('#areaItems button');
    const knob = document.getElementById('areaKnob');
    const track = document.getElementById('areaTrack');
    const selectedAreaInput = document.getElementById('selectedArea');
    const mainImage = document.getElementById('mainImage');
    const thumbs = document.querySelectorAll('.thumb');
    const mapPlaceholder = document.getElementById('mapPlaceholder');

    // Cache heavy JSON payloads once (avoid repeated JSON.parse on every keystroke)
    const glampingMapEl = document.getElementById('glampingMap');
    let unitPricesCache = {};
    let unitExtraChargesCache = {};
    let highRangesCache = [];
    unitPricesCache = safeJsonParse(glampingMapEl?.dataset?.unitPrices, {});
    unitExtraChargesCache = safeJsonParse(glampingMapEl?.dataset?.unitExtraCharges, {});
    highRangesCache = safeJsonParse(glampingMapEl?.dataset?.highSeasonRanges, []);

    // Accordion toggle function for amenities
    globalThis.toggleAccordion = function(id) {
        const accordion = document.getElementById('accordion-' + id);
        const icon = document.getElementById('icon-' + id);

        if (accordion && icon) {
            if (accordion.classList.contains('hidden')) {
                accordion.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                accordion.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }
    };

    function moveKnobTo(btn) {
        if (!btn || !track || !knob) return;
        const trackRect = track.getBoundingClientRect();
        const btnRect = btn.getBoundingClientRect();
        const center = (btnRect.left + btnRect.right) / 2;
        const left = Math.max(0, center - trackRect.left - (knob.offsetWidth / 2));
        knob.style.left = left + 'px';
    }

    function setArea(areaKey, label) {
        items.forEach(i => i.setAttribute('aria-pressed', 'false'));
        const btn = [...items].find(b => b.getAttribute('data-area') === areaKey);
        if (btn) {
            btn.setAttribute('aria-pressed', 'true');
            moveKnobTo(btn);
        }
        if (selectedAreaInput) {
            // Normalize label: gather visible parts and join with single space
            const display = label || (btn ? (function () {
                let parts = [];
                btn.childNodes.forEach(function (n) {
                    if (n.nodeType === Node.ELEMENT_NODE) {
                        const t = (n.textContent || '').trim();
                        if (t) parts.push(t);
                    }
                });
                if (!parts.length) {
                    const txt = (btn.textContent || '').trim();
                    parts = txt.split(/\s+/).filter(Boolean);
                }
                // detect VIP token
                let vip = false;
                if (parts.length && String(parts[parts.length-1]).toLowerCase() === 'vip') { vip = true; parts.pop(); }
                // last remaining token is usually the number or 'CABIN'
                const last = parts.length ? parts[parts.length-1] : '';
                // join name parts
                const nameParts = parts.slice(0,-1).concat([last]);
                let name = nameParts.map(function (w) { return w.charAt(0).toUpperCase() + w.slice(1).toLowerCase(); }).join(' ');
                if (vip) {
                    // if last part is numeric, append (VIP)
                    name = name + ' (VIP)';
                }
                return name;
            })() : '');
            // final normalization: collapse multiple spaces
            selectedAreaInput.value = String(display).replaceAll(/\s+/g, ' ').trim();
        }
        if (mainImage) setMainImageText((label || areaKey) + ' — galeri kosong');
    }

    function getSelectedAreaSlug() {
        const activeBtn = document.querySelector('#areaItems button[aria-pressed="true"]');
        const btn = activeBtn || (items?.length ? items[0] : null);
        return btn?.dataset?.area || '';
    }

    function formatCapacityRange(minVal, maxVal) {
        if (minVal == null && maxVal == null) return '-';
        if (minVal != null && maxVal != null && minVal !== maxVal) return `${minVal} - ${maxVal} people`;
        return `${minVal ?? maxVal} people`;
    }

    function setTextById(id, text) {
        const el = document.getElementById(id);
        if (el) el.textContent = (text == null || text === '') ? '-' : String(text);
    }

    function renderFacilityList(listEl, facilities) {
        if (!listEl) return;
        listEl.innerHTML = '';

        // Handle both array of strings (legacy) and array of objects with name/icon
        const safeFacilities = Array.isArray(facilities) ? facilities.filter(Boolean) : [];
        if (!safeFacilities.length) {
            const li = document.createElement('li');
            li.className = 'text-sm text-gray-500';
            li.textContent = 'No facilities data available.';
            listEl.appendChild(li);
            return;
        }

        safeFacilities.forEach((item) => {
            // Support both string format and {name, icon} object format
            const name = typeof item === 'string' ? item : (item.name || '-');
            const icon = typeof item === 'object' ? item.icon : null;

            const li = document.createElement('li');
            li.className = 'flex items-start gap-3 bg-white rounded-xl p-3 shadow-sm border border-gray-100';

            if (icon) {
                // Create icon image
                const img = document.createElement('img');
                img.src = icon;
                img.alt = name;
                img.className = 'w-6 h-6 flex-shrink-0 object-contain';
                img.onerror = function() {
                    // Fallback to dot if icon fails to load
                    this.replaceWith(createFallbackDot());
                };
                li.appendChild(img);
            } else {
                // Fallback to green dot
                const dot = document.createElement('span');
                dot.className = 'mt-1 w-2.5 h-2.5 rounded-full bg-[#017249] flex-shrink-0';
                li.appendChild(dot);
            }

            const txt = document.createElement('span');
            txt.className = 'text-sm text-gray-700 font-medium';
            txt.textContent = String(name);

            li.appendChild(txt);
            listEl.appendChild(li);
        });
    }

    function createFallbackDot() {
        const dot = document.createElement('span');
        dot.className = 'mt-1 w-2.5 h-2.5 rounded-full bg-[#017249] flex-shrink-0';
        return dot;
    }

    function setInfoModalVisibleState({ isLoading, isError, isContent }) {
        const loadingEl = document.getElementById('infoLoading');
        const errorEl = document.getElementById('infoError');
        const contentEl = document.getElementById('infoContent');

        if (loadingEl) loadingEl.classList.toggle('hidden', !isLoading);
        if (errorEl) errorEl.classList.toggle('hidden', !isError);
        if (contentEl) contentEl.classList.toggle('hidden', !isContent);
    }

    function resetInfoModalFields() {
        const privateList = document.getElementById('infoPrivateList');
        const publicList = document.getElementById('infoPublicList');
        const privateCount = document.getElementById('infoPrivateCount');
        const publicCount = document.getElementById('infoPublicCount');

        setTextById('infoPriceWeekday', '-');
        setTextById('infoPriceWeekend', '-');
        setTextById('infoPriceHighSeason', '-');
        setTextById('infoCapacityDefault', '-');
        setTextById('infoCapacityMax', '-');
        setTextById('infoExtraFull', '-');
        setTextById('infoExtraBreakfast', '-');
        if (privateCount) privateCount.textContent = '0 item';
        if (publicCount) publicCount.textContent = '0 item';
        if (privateList) privateList.innerHTML = '';
        if (publicList) publicList.innerHTML = '';
    }

    function buildAreaInfoUrl(areaSlug) {
        const checkin = document.getElementById('checkin')?.value || '';
        let url = '/reservasi/glamping/area-info/' + encodeURIComponent(areaSlug);
        if (checkin) url += '?checkin=' + encodeURIComponent(checkin);
        return url;
    }

    async function fetchJsonOrThrow(url) {
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        const text = await res.text();
        const body = safeJsonParse(text, null);

        if (!res.ok) {
            const message = body?.message || 'Failed to load information.';
            throw new Error(message);
        }
        return body;
    }

    async function loadInfoModalForArea(areaSlug) {
        const modalTitle = document.getElementById('infoModalTitle');
        const modalSubtitle = document.getElementById('infoModalSubtitle');
        const footerText = document.getElementById('infoFooterText');

        const errorTextEl = document.getElementById('infoErrorText');
        const privateList = document.getElementById('infoPrivateList');
        const publicList = document.getElementById('infoPublicList');
        const privateCount = document.getElementById('infoPrivateCount');
        const publicCount = document.getElementById('infoPublicCount');

        setInfoModalVisibleState({ isLoading: true, isError: false, isContent: false });

        if (modalTitle) modalTitle.textContent = 'Area Details';
        if (modalSubtitle) modalSubtitle.textContent = 'Loading data...';
        if (footerText) footerText.textContent = 'Loading information from database';

        resetInfoModalFields();

        if (!areaSlug) {
            setInfoModalVisibleState({ isLoading: false, isError: true, isContent: false });
            if (errorTextEl) errorTextEl.textContent = 'No area selected.';
            if (footerText) footerText.textContent = 'Please select an area first';
            return;
        }

        const url = buildAreaInfoUrl(areaSlug);
        try {
            const data = await fetchJsonOrThrow(url);

            const areaName = data?.area?.name || 'Area';
            if (modalTitle) modalTitle.textContent = `Details — ${areaName}`;
            if (modalSubtitle) modalSubtitle.textContent = 'Facilities, prices, capacity, and extra charges';
            if (footerText) footerText.textContent = `Showing details for ${areaName}`;

            const privateFacilities = data?.facilities?.private || [];
            const publicFacilities = data?.facilities?.public || [];
            if (privateCount) privateCount.textContent = `${Array.isArray(privateFacilities) ? privateFacilities.length : 0} item`;
            if (publicCount) publicCount.textContent = `${Array.isArray(publicFacilities) ? publicFacilities.length : 0} item`;
            renderFacilityList(privateList, privateFacilities);
            renderFacilityList(publicList, publicFacilities);

            setTextById('infoPriceWeekday', data?.prices?.weekday?.display || '-');
            setTextById('infoPriceWeekend', data?.prices?.weekend?.display || '-');
            setTextById('infoPriceHighSeason', data?.prices?.high_season?.display || '-');

            const cap = data?.capacity || {};
            setTextById('infoCapacityDefault', formatCapacityRange(cap.default_min, cap.default_max));
            setTextById('infoCapacityMax', formatCapacityRange(cap.max_min, cap.max_max));

            const extraChargeCard = document.getElementById('infoExtraChargeCard');
            const hideExtraCharge = String(areaSlug).toLowerCase() === 'pineus-tilu-cabin-vvip';
            if (extraChargeCard) {
                extraChargeCard.classList.toggle('hidden', hideExtraCharge);
            }

            if (!hideExtraCharge) {
                setTextById('infoExtraFull', data?.extra_charge?.full?.display || '-');
                setTextById('infoExtraBreakfast', data?.extra_charge?.breakfast?.display || '-');
            }

            setInfoModalVisibleState({ isLoading: false, isError: false, isContent: true });
        } catch (err) {
            setInfoModalVisibleState({ isLoading: false, isError: true, isContent: false });
            if (errorTextEl) errorTextEl.textContent = err?.message || 'Please try again.';
            if (footerText) footerText.textContent = 'An error occurred while loading data';
        }
    }

    if (items.length) {
        items.forEach((b, idx) => {
            b.addEventListener('click', () => setArea(b.getAttribute('data-area'), b.textContent.trim()));
            b.addEventListener('keydown', (e) => {
                if (e.key === 'ArrowRight') {
                    const next = items[(idx + 1) % items.length];
                    next.focus(); setArea(next.getAttribute('data-area'), next.textContent.trim());
                }
                if (e.key === 'ArrowLeft') {
                    const prev = items[(idx - 1 + items.length) % items.length];
                    prev.focus(); setArea(prev.getAttribute('data-area'), prev.textContent.trim());
                }
            });
        });
        setTimeout(() => setArea(items[0].getAttribute('data-area'), items[0].textContent.trim()), 10);
    }

    window.addEventListener('resize', () => {
        const active = [...items].find(i => i.getAttribute('aria-pressed') === 'true') || items[0];
        if (active) moveKnobTo(active);
    });

    // modal info
    const infoBtn = document.getElementById('infoBtn');
    const infoModal = document.getElementById('infoModal');
    function openInfoModal() {
        if (!infoModal) return;
        infoModal.classList.remove('hidden');
        infoModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function closeInfoModal() {
        if (!infoModal) return;
        infoModal.classList.add('hidden');
        infoModal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }
    if (infoBtn) {
        infoBtn.addEventListener('click', async () => {
            openInfoModal();
            await loadInfoModalForArea(getSelectedAreaSlug());
        });
    }

    const getCsrfToken = () => {
        const formToken = document.querySelector('#reservasiForm input[name="_token"]')?.value;
        const metaToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        return formToken || metaToken || '';
    };

    const postAsForm = async (url, payload) => {
        const body = new URLSearchParams();
        Object.entries(payload || {}).forEach(([key, value]) => {
            if (value !== undefined && value !== null) body.set(key, String(value));
        });

        const csrf = getCsrfToken();
        if (csrf && !body.has('_token')) body.set('_token', csrf);

        const res = await fetch(url, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body,
        });

        const json = await res.json().catch(() => ({}));
        if (!res.ok) {
            const message = json?.message || 'Request failed';
            throw new Error(message);
        }
        return json;
    };

    const setBookingFlowMessage = (message) => {
        const el = document.getElementById('bookingFlowMessage');
        if (!el) return;
        if (!message) {
            el.textContent = '';
            el.classList.add('hidden');
            return;
        }

        el.textContent = message;
        el.classList.remove('hidden');
    };

    // --- flatpickr for checkin/checkout
    (function initFlatpickr() {
        const checkinEl = document.getElementById('checkin');
        const checkoutHiddenEl = document.getElementById('checkout');
        const checkoutDisplayEl = document.getElementById('checkout_display');
        const mapContainer = document.getElementById('glampingMap');
        if (!checkinEl) return;

        const addDays = (date, days) => {
            const d = new Date(date);
            d.setDate(d.getDate() + days);
            return d;
        };

        const formatYmd = (date) => {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const da = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${da}`;
        };

        const formatDmy = (date) => {
            const d = String(date.getDate()).padStart(2, '0');
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const y = date.getFullYear();
            return `${d}/${m}/${y}`;
        };

        const parseYmd = (ymd) => {
            if (!ymd || typeof ymd !== 'string') return null;
            const parts = ymd.split('-');
            if (parts.length !== 3) return null;
            const y = Number.parseInt(parts[0], 10);
            const m = Number.parseInt(parts[1], 10) - 1;
            const d = Number.parseInt(parts[2], 10);
            if (!Number.isFinite(y) || !Number.isFinite(m) || !Number.isFinite(d)) return null;
            return new Date(y, m, d);
        };

        const monthCache = new Map();
        let bookedDates = new Set();
        let selectedUnitId = '';
        let checkinFp = null;
        let checkoutFp = null;
        let reservationDraftId = '';

        const ensureBookedDayStyle = () => {
            if (document.getElementById('glamping-booked-day-style')) return;
            const style = document.createElement('style');
            style.id = 'glamping-booked-day-style';
            style.textContent = `
                .flatpickr-day.booked-deck-day:not(.selected):not(.today) {
                    background: #fee2e2 !important;
                    border-color: #ef4444 !important;
                    color: #b91c1c !important;
                    font-weight: 600;
                }
                .flatpickr-day.booked-deck-day.flatpickr-disabled {
                    opacity: 0.7 !important;
                }
            `;
            document.head.appendChild(style);
        };

        const monthKey = (year, monthZeroBased) => `${year}-${String(monthZeroBased + 1).padStart(2, '0')}`;

        const fetchMonthAvailability = async (year, monthZeroBased) => {
            const key = monthKey(year, monthZeroBased);
            if (monthCache.has(key)) return monthCache.get(key);

            try {
                const url = `/availability/data?year=${encodeURIComponent(year)}&month=${encodeURIComponent(monthZeroBased)}`;
                const res = await fetch(url, { headers: { Accept: 'application/json' } });
                const json = await res.json();
                const data = (json && json.success && json.data && typeof json.data === 'object') ? json.data : {};
                monthCache.set(key, data);
                return data;
            } catch (e) {
                monthCache.set(key, {});
                return {};
            }
        };

        const collectBookedDatesForUnit = (availabilityData, unitId) => {
            const dates = [];
            if (!availabilityData || !unitId) return dates;

            Object.values(availabilityData).forEach((areaByDate) => {
                if (!areaByDate || typeof areaByDate !== 'object') return;
                Object.entries(areaByDate).forEach(([ymd, unitStatuses]) => {
                    if (!unitStatuses || typeof unitStatuses !== 'object') return;
                    const status = unitStatuses[String(unitId)] ?? unitStatuses[unitId];
                    if (status === 'booked') dates.push(ymd);
                });
            });

            return dates;
        };

        const buildUnitStatusForDate = (availabilityData, ymd) => {
            const statusByUnit = {};
            if (!availabilityData || !ymd) return statusByUnit;

            Object.values(availabilityData).forEach((areaByDate) => {
                if (!areaByDate || typeof areaByDate !== 'object') return;
                const statuses = areaByDate[ymd];
                if (!statuses || typeof statuses !== 'object') return;

                Object.entries(statuses).forEach(([unitId, status]) => {
                    statusByUnit[String(unitId)] = status;
                });
            });

            return statusByUnit;
        };

        const applyMapAvailabilityForDate = async (ymd) => {
            if (!ymd) return;
            try {
                const draftRes = await postAsForm('/api/reservasi/glamping/draft/start', { checkin: ymd });
                const draftData = draftRes?.data || {};
                reservationDraftId = String(draftData.draft_id || reservationDraftId || '');

                if (typeof globalThis.glampingMapSetAvailability === 'function' && draftData.unit_statuses && typeof draftData.unit_statuses === 'object') {
                    globalThis.glampingMapSetAvailability(draftData.unit_statuses);
                    return;
                }
            } catch (e) {
                // fallback to legacy month endpoint
            }

            const dateObj = parseYmd(ymd);
            if (!dateObj) return;
            const monthData = await fetchMonthAvailability(dateObj.getFullYear(), dateObj.getMonth());
            const statusByUnit = buildUnitStatusForDate(monthData, ymd);
            if (typeof globalThis.glampingMapSetAvailability === 'function') globalThis.glampingMapSetAvailability(statusByUnit);
        };

        const rebuildBookedDatesFromCache = () => {
            bookedDates = new Set();
            if (!selectedUnitId) return;

            monthCache.forEach((data) => {
                const dates = collectBookedDatesForUnit(data, selectedUnitId);
                dates.forEach((d) => bookedDates.add(d));
            });
        };

        const preloadVisibleMonths = async (baseDate) => {
            if (!selectedUnitId) return;

            const first = baseDate || parseYmd(checkinEl.value) || new Date();
            const next = new Date(first.getFullYear(), first.getMonth() + 1, 1);

            await Promise.all([
                fetchMonthAvailability(first.getFullYear(), first.getMonth()),
                fetchMonthAvailability(next.getFullYear(), next.getMonth()),
            ]);

            rebuildBookedDatesFromCache();

            if (checkinFp) checkinFp.redraw();
            if (checkoutFp) checkoutFp.redraw();
        };

        const isBookedDate = (date) => {
            if (!selectedUnitId) return false;
            return bookedDates.has(formatYmd(date));
        };

        const disableCheckinDate = (date) => {
            return false;
        };

        const disableCheckoutDate = (date) => {
            if (!selectedUnitId) return true;
            return isBookedDate(date);
        };

        const markBookedDay = (dateObj, dayElem) => {
            dayElem.classList.remove('booked-deck-day');
            if (!selectedUnitId) return;
            if (isBookedDate(dateObj)) {
                dayElem.classList.add('booked-deck-day');
                dayElem.title = 'Booked untuk deck yang dipilih';
            }
        };

        const applyPickerLockState = () => {
            const hasUnit = Boolean(selectedUnitId);

            if (checkinFp) {
                checkinFp.set('clickOpens', true);
                checkinFp.set('disable', [disableCheckinDate]);
                checkinFp.redraw();
            }

            if (checkoutFp) {
                checkoutFp.set('clickOpens', hasUnit);
                checkoutFp.set('disable', [disableCheckoutDate]);
                checkoutFp.redraw();
            }

            if (checkinFp?.altInput) {
                checkinFp.altInput.setAttribute('readonly', 'readonly');
            }
            checkinEl.setAttribute('readonly', 'readonly');
            if (checkoutDisplayEl) checkoutDisplayEl.setAttribute('readonly', 'readonly');
        };

        const setCheckoutDate = (checkinDate, checkoutDate, fpRef) => {
            const co = checkoutDate || addDays(checkinDate, 1);
            if (checkoutHiddenEl) checkoutHiddenEl.value = formatYmd(co);
            if (checkoutDisplayEl) {
                checkoutDisplayEl.value = fpRef
                    ? fpRef.formatDate(co, 'd/m/Y')
                    : formatDmy(co);
            }
        };

        const syncSelectedUnitState = async () => {
            const selectedUnitInput = document.getElementById('selected_unit');
            selectedUnitId = String(selectedUnitInput?.value || '').trim();
            applyPickerLockState();

            if (selectedUnitId) {
                setBookingFlowMessage('');
                let usedDraft = false;
                if (reservationDraftId) {
                    try {
                        const selectRes = await postAsForm(`/api/reservasi/glamping/draft/${encodeURIComponent(reservationDraftId)}/select-unit`, {
                            unit_id: selectedUnitId,
                        });
                        const data = selectRes?.data || {};
                        const fromApi = Array.isArray(data.booked_dates) ? data.booked_dates : [];
                        bookedDates = new Set(fromApi);
                        usedDraft = true;
                    } catch (e) {
                        usedDraft = false;
                    }
                }

                if (!usedDraft) {
                    await preloadVisibleMonths(parseYmd(checkinEl.value) || new Date());
                } else {
                    if (checkinFp) checkinFp.redraw();
                    if (checkoutFp) checkoutFp.redraw();
                }
            } else {
                bookedDates = new Set();
                if (checkinFp) checkinFp.redraw();
                if (checkoutFp) checkoutFp.redraw();
            }
        };

        const resetSelectedUnitForNewCheckin = async () => {
            selectedUnitId = '';
            bookedDates = new Set();

            const selectedUnitInput = document.getElementById('selected_unit');
            if (selectedUnitInput) {
                selectedUnitInput.value = '';
                try { selectedUnitInput.dispatchEvent(new Event('change', { bubbles: true })); } catch (_error) {}
            }

            const selectedUnitText = document.getElementById('selectedUnit');
            if (selectedUnitText) {
                selectedUnitText.value = '-';
                try { selectedUnitText.dispatchEvent(new Event('input', { bubbles: true })); } catch (_error) {}
            }

            if (typeof globalThis.glampingMapClearSelection === 'function') {
                globalThis.glampingMapClearSelection();
            }

            setBookingFlowMessage('Check-in date changed. Please select a deck again to continue.');

            applyPickerLockState();
            if (checkinFp) checkinFp.redraw();
            if (checkoutFp) checkoutFp.redraw();
        };

        const bindSelectedUnitWatcher = () => {
            const tryBind = () => {
                const selectedUnitInput = document.getElementById('selected_unit');
                if (!selectedUnitInput) {
                    setTimeout(tryBind, 120);
                    return;
                }

                if (selectedUnitInput.dataset.fpDeckBound === '1') return;
                selectedUnitInput.dataset.fpDeckBound = '1';

                selectedUnitInput.addEventListener('change', () => {
                    syncSelectedUnitState();
                });

                selectedUnitInput.addEventListener('input', () => {
                    syncSelectedUnitState();
                });

                syncSelectedUnitState();
            };

            tryBind();
        };

        ensureBookedDayStyle();

        const todayYmdInit = formatYmd(new Date());
        if (!checkinEl.value) checkinEl.value = todayYmdInit;
        checkinEl.dataset.autoDate = (checkinEl.value === todayYmdInit) ? '1' : '0';

        const checkinDateInit = parseYmd(checkinEl.value) || new Date();
        const defaultCheckoutDate = addDays(checkinDateInit, 1);
        if (checkoutHiddenEl && !checkoutHiddenEl.value) {
            checkoutHiddenEl.value = formatYmd(defaultCheckoutDate);
        }
        if (checkoutDisplayEl && !checkoutDisplayEl.value) {
            checkoutDisplayEl.value = formatDmy(defaultCheckoutDate);
            checkoutDisplayEl.dataset.autoDate = '1';
        } else if (checkoutDisplayEl) {
            checkoutDisplayEl.dataset.autoDate = '0';
        }

        if (globalThis.flatpickr) {
            let isInitializing = true;

            checkinFp = flatpickr(checkinEl, {
                altInput: true,
                altFormat: 'd/m/Y',
                dateFormat: 'Y-m-d',
                defaultDate: checkinEl.value || new Date(),
                minDate: 'today',
                clickOpens: false,
                disable: [disableCheckinDate],
                onDayCreate: function (_dObj, _dStr, _fp, dayElem) {
                    markBookedDay(dayElem.dateObj, dayElem);
                },
                onMonthChange: function (_selectedDates, _dateStr, instance) {
                    preloadVisibleMonths(new Date(instance.currentYear, instance.currentMonth, 1));
                },
                onYearChange: function (_selectedDates, _dateStr, instance) {
                    preloadVisibleMonths(new Date(instance.currentYear, instance.currentMonth, 1));
                },
                onReady: function () {
                    isInitializing = false;
                },
                onChange: function (selectedDates) {
                    if (!selectedDates?.[0]) return;
                    const ymd = formatYmd(selectedDates[0]);

                    if (!isInitializing) {
                        resetSelectedUnitForNewCheckin();
                    }

                    const nextMin = addDays(selectedDates[0], 1);
                    if (checkoutFp) checkoutFp.set('minDate', nextMin);

                    const currentCheckout = parseYmd(checkoutHiddenEl?.value || '');
                    const shouldReset = !currentCheckout || currentCheckout <= selectedDates[0] || checkoutDisplayEl?.dataset?.autoDate === '1';
                    if (shouldReset) {
                        setCheckoutDate(selectedDates[0], nextMin, checkoutFp);
                        if (checkoutDisplayEl) checkoutDisplayEl.dataset.autoDate = '1';
                    }

                    if (typeof globalThis.updatePreview === 'function') globalThis.updatePreview();
                    if (!isInitializing) {
                        checkinEl.dataset.autoDate = '0';
                    }

                    applyMapAvailabilityForDate(ymd);
                }
            });

            if (checkinFp?.altInput) checkinFp.altInput.setAttribute('readonly', 'readonly');
            checkinEl.setAttribute('readonly', 'readonly');

            if (checkoutDisplayEl) {
                checkoutFp = flatpickr(checkoutDisplayEl, {
                    dateFormat: 'd/m/Y',
                    defaultDate: checkoutDisplayEl.value || formatDmy(defaultCheckoutDate),
                    minDate: addDays(parseYmd(checkinEl.value) || new Date(), 1),
                    clickOpens: false,
                    disable: [disableCheckoutDate],
                    onDayCreate: function (_dObj, _dStr, _fp, dayElem) {
                        markBookedDay(dayElem.dateObj, dayElem);
                    },
                    onMonthChange: function (_selectedDates, _dateStr, instance) {
                        preloadVisibleMonths(new Date(instance.currentYear, instance.currentMonth, 1));
                    },
                    onYearChange: function (_selectedDates, _dateStr, instance) {
                        preloadVisibleMonths(new Date(instance.currentYear, instance.currentMonth, 1));
                    },
                    onChange: function (selectedDates) {
                        if (!selectedDates?.[0]) return;
                        setCheckoutDate(parseYmd(checkinEl.value) || new Date(), selectedDates[0], checkoutFp);
                        checkoutDisplayEl.dataset.autoDate = '0';
                        if (typeof globalThis.updatePreview === 'function') globalThis.updatePreview();
                    }
                });
                checkoutDisplayEl.setAttribute('readonly', 'readonly');
            }

            if (checkoutHiddenEl) checkoutHiddenEl.readOnly = true;

            const selected = checkinFp?.selectedDates?.[0] ? checkinFp.selectedDates[0] : new Date();
            setCheckoutDate(selected, parseYmd(checkoutHiddenEl?.value || '') || defaultCheckoutDate, checkoutFp);

            applyMapAvailabilityForDate(checkinEl.value || formatYmd(selected));

            applyPickerLockState();
            bindSelectedUnitWatcher();
            if (mapContainer) {
                mapContainer.addEventListener('click', () => {
                    // Allow glamping-map to update hidden input first, then sync picker state.
                    setTimeout(() => syncSelectedUnitState(), 0);
                });
            }
        } else {
            // fallback (without flatpickr): keep no-refresh behavior
            checkinEl.addEventListener('change', function () {
                if (!checkinEl.value) return;
                const d = parseYmd(checkinEl.value);
                if (!d) return;
                setCheckoutDate(d, addDays(d, 1), null);
                if (typeof globalThis.updatePreview === 'function') globalThis.updatePreview();
                applyMapAvailabilityForDate(checkinEl.value);
            });
            if (checkoutHiddenEl) checkoutHiddenEl.readOnly = true;
            if (checkoutDisplayEl) checkoutDisplayEl.readOnly = true;
        }
    })();

    // Universal close modal handler
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', function () {
            const modalKey = btn.dataset.closeModal;
            let modalId = modalKey;
            if (modalKey === 'jabodetabek') modalId = 'modalJabodetabek';
            if (modalKey === 'info') modalId = 'infoModal';

            const modal = modalId ? document.getElementById(modalId) : null;
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                if (modalKey === 'info' || modal.id === 'amenitiesModal') {
                    document.body.style.overflow = 'auto';
                }
            }
        });
    });
    document.addEventListener('keydown', function (e) {
        if (e.key !== 'Escape') return;
        if (infoModal && !infoModal.classList.contains('hidden')) {
            closeInfoModal();
        }
    });

    // thumbs
    thumbs.forEach((t, idx) => {
        t.addEventListener('click', () => {
            if (mainImage) setMainImageText('Thumb ' + (idx + 1) + ' - konten kosong');
        });
    });

    // Helper to safely set main image text while preserving styling
    function setMainImageText(text) {
        if (!mainImage) return;
        mainImage.innerHTML = '';
        const span = document.createElement('span');
        span.className = 'text-gray-400';
        span.textContent = text;
        mainImage.appendChild(span);
    }

    // map click effect
    if (mapPlaceholder) {
        mapPlaceholder.addEventListener('click', () => {
            mapPlaceholder.classList.add('ring-2', 'ring-[#017249]/40');
            setTimeout(() => mapPlaceholder.classList.remove('ring-2', 'ring-[#017249]/40'), 350);
        });
    }

    // guest count
    const guestDecrease = document.getElementById('guestDecrease');
    const guestIncrease = document.getElementById('guestIncrease');
    const guestCount = document.getElementById('guestCount');
    const GUEST_MIN = 1, GUEST_MAX = 20;

    function setGuestControlsEnabled(enabled) {
        if (!guestDecrease || !guestIncrease || !guestCount) return;
        guestDecrease.disabled = !enabled;
        guestIncrease.disabled = !enabled;
        guestCount.disabled = !enabled;
    }

    setGuestControlsEnabled(false);

    if (guestDecrease && guestIncrease && guestCount) {
        guestDecrease.addEventListener('click', () => {
            if (guestDecrease.disabled) return;
            let v = Number.parseInt(guestCount.value || '1', 10);
            if (isNaN(v)) v = GUEST_MIN;
            const min = Number.parseInt(guestCount.dataset.min || GUEST_MIN, 10) || GUEST_MIN;
            v = Math.max(min, v - 1);
            guestCount.value = v;
            if (typeof debouncedPreview === 'function') debouncedPreview();
        });
        guestIncrease.addEventListener('click', () => {
            if (guestIncrease.disabled) return;
            let v = Number.parseInt(guestCount.value || '1', 10);
            if (isNaN(v)) v = GUEST_MIN;
            const max = Number.parseInt(guestCount.dataset.max || GUEST_MAX, 10) || GUEST_MAX;
            v = Math.min(max, v + 1);
            guestCount.value = v;
            if (typeof debouncedPreview === 'function') debouncedPreview();
        });
        guestCount.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowUp') { e.preventDefault(); guestIncrease.click(); }
            if (e.key === 'ArrowDown') { e.preventDefault(); guestDecrease.click(); }
        });
    }

    const form = document.getElementById('reservasiForm') || document.getElementById('rescheduleForm');

    // ========== AMENITIES MODAL FUNCTIONS ==========
    globalThis.openAmenitiesModal = function() {
        const modal = document.getElementById('amenitiesModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    };

    globalThis.closeAmenitiesModal = function() {
        const modal = document.getElementById('amenitiesModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            updateAmenitiesCount();
        }
    };

    function updateAmenitiesCount() {
        refreshAmenitiesBadgeOnly();

        // Trigger preview update
        if (typeof globalThis.updatePreview === 'function') {
            globalThis.updatePreview();
        }
    }

    function refreshAmenitiesBadgeOnly() {
        const inputs = document.querySelectorAll('.amenity-qty-input');
        let count = 0;
        inputs.forEach(input => {
            const qty = Number.parseInt(input.value || '0', 10);
            count += Number.isNaN(qty) ? 0 : qty;
        });

        // Update badge in form
        const badge = document.getElementById('amenitiesCount');
        if (badge) badge.textContent = count;

        // Update modal footer
        const modalCount = document.getElementById('selectedCount');
        if (modalCount) modalCount.textContent = count;
    }

    function setExtraModeButtonState(mode, enabled) {
        const btnFull = document.getElementById('extraModeFull');
        const btnBreakfast = document.getElementById('extraModeBreakfast');

        const applyState = (btn, active) => {
            if (!btn) return;
            btn.disabled = !enabled;
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
            btn.classList.toggle('bg-[#017249]', active);
            btn.classList.toggle('bg-white', !active);
            btn.classList.toggle('text-white', active);
            btn.classList.toggle('border-[#017249]', active);
            btn.classList.toggle('opacity-60', !enabled);
            btn.classList.toggle('cursor-not-allowed', !enabled);
            btn.classList.toggle('cursor-pointer', enabled);

            const titleEl = btn.querySelector('.extra-mode-title');
            const descEl = btn.querySelector('.extra-mode-desc');

            if (titleEl) {
                titleEl.classList.toggle('text-white', active);
                titleEl.classList.toggle('text-gray-700', !active);
            }

            if (descEl) {
                descEl.classList.toggle('text-white', active);
                descEl.classList.toggle('text-gray-500', !active);
            }
        };

        applyState(btnFull, mode === 'full');
        applyState(btnBreakfast, mode === 'breakfast');
    }

    function isExtraModeEligibleArea(areaSlug) {
        return ['pineus-tilu-1', 'pineus-tilu-2', 'pineus-tilu-3-vip', 'pineus-tilu-4'].includes(String(areaSlug || ''));
    }

    function syncExtraModeDescriptions(areaSlug) {
        const cardEl = document.getElementById('extraGuestModeCard');
        if (!cardEl) return;

        const fullDescEl = document.getElementById('extraModeFullDesc');
        const breakfastDescEl = document.getElementById('extraModeBreakfastDesc');

        const regularDesc = String(cardEl.dataset.amenitiesDesc || '').trim();
        const vipDesc = String(cardEl.dataset.amenitiesVipDesc || regularDesc).trim();
        const breakfastDesc = String(cardEl.dataset.breakfastDesc || '').trim();

        const useVipDesc = String(areaSlug || '') === 'pineus-tilu-3-vip';
        if (fullDescEl) {
            fullDescEl.textContent = useVipDesc ? vipDesc : regularDesc;
        }
        if (breakfastDescEl) {
            breakfastDescEl.textContent = breakfastDesc;
        }
    }

    function syncExtraGuestAmenityMode() {
        const modeInput = document.getElementById('extraChargeMode');
        if (!modeInput) return;

        const areaSlug = getSelectedAreaSlug();
        syncExtraModeDescriptions(areaSlug);
        const isEligibleArea = isExtraModeEligibleArea(areaSlug);
        const cardEl = document.getElementById('extraGuestModeCard');
        if (cardEl) cardEl.classList.toggle('hidden', !isEligibleArea);

        if (!isEligibleArea) {
            modeInput.value = '';
            setExtraModeButtonState('', false);
            return;
        }

        const selectedUnitId = String(document.getElementById('selected_unit')?.value || '').trim();
        const hasSelectedUnit = selectedUnitId !== '';
        setGuestControlsEnabled(hasSelectedUnit);
        const guestCountNum = Number.parseInt(document.getElementById('guestCount')?.value || '1', 10) || 1;
        const unitExtra = selectedUnitId ? unitExtraChargesCache?.[selectedUnitId] : null;
        let defaultPeople = Number.parseInt(String(unitExtra?.default_people ?? 0), 10);
        if (!Number.isFinite(defaultPeople) || defaultPeople < 0) defaultPeople = 0;

        const extraGuests = hasSelectedUnit ? Math.max(0, guestCountNum - defaultPeople) : 0;
        const infoEl = document.getElementById('extraGuestInfo');
        if (infoEl) {
            if (!hasSelectedUnit) {
                infoEl.textContent = 'Select deck first';
            } else if (extraGuests > 0) {
                infoEl.textContent = `${extraGuests} extra guest${extraGuests > 1 ? 's' : ''}`;
            } else {
                infoEl.textContent = 'No extra guest';
            }
        }

        if (extraGuests <= 0) {
            modeInput.value = '';
            setExtraModeButtonState('', false);
            return;
        }

        let mode = String(modeInput.value || '').trim();
        if (mode !== 'full' && mode !== 'breakfast') {
            mode = 'breakfast';
            modeInput.value = mode;
        }

        setExtraModeButtonState(mode, true);
    }

    // Quantity button handlers for amenities
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('amenity-qty-increase')) {
            e.preventDefault();
            const itemId = e.target.dataset.itemId;
            const input = document.querySelector(`.amenity-qty-input[data-item-id="${itemId}"]`);
            const display = e.target.parentElement.querySelector('.amenity-qty-display');
            if (input && display) {
                let qty = Number.parseInt(input.value || '0', 10);
                if (Number.isNaN(qty)) qty = 0;
                qty += 1;
                input.value = qty;
                display.textContent = qty;
                updateAmenitiesCount();
            }
        } else if (e.target.classList.contains('amenity-qty-decrease')) {
            e.preventDefault();
            const itemId = e.target.dataset.itemId;
            const input = document.querySelector(`.amenity-qty-input[data-item-id="${itemId}"]`);
            const display = e.target.parentElement.querySelector('.amenity-qty-display');
            if (input && display) {
                let qty = Number.parseInt(input.value || '0', 10);
                if (Number.isNaN(qty)) qty = 0;
                qty = Math.max(0, qty - 1);
                input.value = qty;
                display.textContent = qty;
                updateAmenitiesCount();
            }
        }
    });

    const extraModeFullBtn = document.getElementById('extraModeFull');
    const extraModeBreakfastBtn = document.getElementById('extraModeBreakfast');
    const extraChargeModeInput = document.getElementById('extraChargeMode');

    if (extraModeFullBtn && extraChargeModeInput) {
        extraModeFullBtn.addEventListener('click', () => {
            if (extraModeFullBtn.disabled) return;
            extraChargeModeInput.value = 'full';
            syncExtraGuestAmenityMode();
            if (typeof globalThis.updatePreview === 'function') globalThis.updatePreview();
        });
    }

    if (extraModeBreakfastBtn && extraChargeModeInput) {
        extraModeBreakfastBtn.addEventListener('click', () => {
            if (extraModeBreakfastBtn.disabled) return;
            extraChargeModeInput.value = 'breakfast';
            syncExtraGuestAmenityMode();
            if (typeof globalThis.updatePreview === 'function') globalThis.updatePreview();
        });
    }

    // ========== PREVIEW UPDATE FUNCTION ==========
    function parseYmd(ymd) {
        if (!ymd || typeof ymd !== 'string') return null;
        const p = ymd.split('-');
        if (p.length !== 3) return null;
        return new Date(Number.parseInt(p[0], 10), Number.parseInt(p[1], 10) - 1, Number.parseInt(p[2], 10));
    }

    function formatIdr(amount) {
        const n = Number(amount);
        const safe = Number.isFinite(n) ? n : 0;
        return 'Rp ' + safe.toLocaleString('id-ID');
    }

    function resolveSeasonType(dateObj, highRanges) {
        if (!dateObj) return 'weekday';
        const ymd = dateObj.getFullYear() + '-' + String(dateObj.getMonth() + 1).padStart(2, '0') + '-' + String(dateObj.getDate()).padStart(2, '0');
        if (Array.isArray(highRanges)) {
            for (const r of highRanges) {
                if (!r?.start || !r?.end) continue;
                if (ymd >= r.start && ymd <= r.end) return 'high_season';
            }
        }

        // Friday (5) and Saturday (6)
        const day = dateObj.getDay();
        return (day === 5 || day === 6) ? 'weekend' : 'weekday';
    }

    function setTextIfEl(el, value) {
        if (el) el.textContent = value;
    }

    function renderPreviewDates(checkinDisp, checkout) {
        const previewDates = document.getElementById('previewDates');
        setTextIfEl(previewDates, `${checkinDisp} - ${checkout}`);
    }

    function renderPreviewArea(area, unit) {
        const previewArea = document.getElementById('previewArea');
        setTextIfEl(previewArea, `${area} / ${unit}`);
    }

    function renderPreviewGuest(guestCount, name) {
        const previewGuest = document.getElementById('previewGuest');
        setTextIfEl(previewGuest, `${guestCount} people (${name})`);
    }

    function renderPreviewAmenities(itemsData) {
        const amenitiesContainer = document.getElementById('previewAmenities');
        if (!amenitiesContainer) return;

        const selectedItems = itemsData.filter(it => it.qty > 0);
        amenitiesContainer.innerHTML = selectedItems.length > 0
            ? selectedItems.map(it => {
                const qtyText = it.type === 'pax' ? `${it.qty} pax` : `x${it.qty}`;
                return `<span class="text-xs bg-[#017249] text-white px-2 py-1 rounded">${it.name} (${qtyText})</span>`;
            }).join('')
            : '<span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded">No items selected</span>';
    }

    function getAmenityItemsWithQty() {
        const items = [];
        const inputs = document.querySelectorAll('.amenity-qty-input');
        inputs.forEach(input => {
            const qty = Number.parseInt(input.value || '0', 10);
            if (qty > 0) {
                const itemId = Number.parseInt(input.dataset.itemId || '0', 10);
                const price = Number.parseFloat(input.dataset.price || '0') || 0;
                const type = input.dataset.type || '';
                const nameEl = input.closest('.amenity-item')?.querySelector('.font-semibold');
                const name = nameEl?.textContent || `Item ${itemId}`;
                items.push({ id: itemId, name, qty, price, type });
            }
        });
        return items;
    }

    function calculateBaseForRange({
        selectedUnitId,
        unitPrices,
        highRanges,
        checkinDate,
        checkoutDate
    }) {
        if (!selectedUnitId || !checkinDate || !checkoutDate) return 0;
        const start = new Date(checkinDate.getFullYear(), checkinDate.getMonth(), checkinDate.getDate());
        const end = new Date(checkoutDate.getFullYear(), checkoutDate.getMonth(), checkoutDate.getDate());
        if (end <= start) return 0;

        let total = 0;
        const cursor = new Date(start.getTime());
        while (cursor < end) {
            const seasonType = resolveSeasonType(cursor, highRanges);
            const baseRaw = unitPrices?.[selectedUnitId]?.[seasonType];
            const nightly = (baseRaw == null) ? 0 : (Number.parseFloat(baseRaw) || 0);
            total += nightly;
            cursor.setDate(cursor.getDate() + 1);
        }
        return total;
    }

    function calculateEstimatedPrices({
        selectedUnitId,
        unitPrices,
        unitExtraCharges,
        isExtraModeEligible,
        extraChargeMode,
        amenityItems,
        guestCountNum,
        highRanges,
        checkinDate,
        checkoutDate
    }) {
        const basePrice = calculateBaseForRange({
            selectedUnitId,
            unitPrices,
            highRanges,
            checkinDate,
            checkoutDate
        });

        let amenitiesPrice = 0;
        for (const item of amenityItems) {
            const p = item.price || 0;
            const linePrice = p * item.qty;
            amenitiesPrice += linePrice;
        }

        const unitExtra = selectedUnitId ? unitExtraCharges?.[selectedUnitId] : null;
        let defaultPeople = (unitExtra?.default_people == null) ? 0 : Number.parseInt(unitExtra.default_people, 10);
        if (!Number.isFinite(defaultPeople) || defaultPeople < 0) defaultPeople = 0;
        let fullRate = (unitExtra?.full == null) ? 0 : Number.parseFloat(unitExtra.full);
        if (!Number.isFinite(fullRate) || fullRate < 0) fullRate = 0;
        let breakfastRate = (unitExtra?.breakfast == null) ? 0 : Number.parseFloat(unitExtra.breakfast);
        if (!Number.isFinite(breakfastRate) || breakfastRate < 0) breakfastRate = 0;
        const extraPeople = Math.max(0, guestCountNum - defaultPeople);

        let breakfastExtra = 0;
        let extraChargeLabel = 'Extra Breakfast';
        if (isExtraModeEligible && extraPeople > 0) {
            if (extraChargeMode === 'full') {
                breakfastExtra = extraPeople * fullRate;
                extraChargeLabel = 'Extra Full Amenities';
            } else {
                breakfastExtra = extraPeople * breakfastRate;
            }
        }

        const totalPrice = basePrice + amenitiesPrice + breakfastExtra;
        return { basePrice, amenitiesPrice, breakfastExtra, extraChargeLabel, totalPrice };
    }

    globalThis.updatePreview = function() {

        syncExtraGuestAmenityMode();

        const getCheckinDisplay = () => {
            const checkinInput = document.getElementById('checkin');
            const alt = checkinInput?._flatpickr?.altInput?.value;
            if (alt) return alt;
            return checkinInput?.value || '-';
        };

        // Dates preview
        const checkinDisp = getCheckinDisplay();

        const checkout = document.getElementById('checkout_display')?.value || '-';
        renderPreviewDates(checkinDisp, checkout);

        // Area and Unit preview
        const area = document.getElementById('selectedArea')?.value || '-';
        const unit = document.getElementById('selectedUnit')?.value || '-';

        renderPreviewArea(area, unit);

        // Guest and contact preview
        const guestCount = document.getElementById('guestCount')?.value || '0';
        const name = document.getElementById('name')?.value || '-';

        renderPreviewGuest(guestCount, name);

        // Amenities preview and calculation
        const amenityItems = getAmenityItemsWithQty();
        renderPreviewAmenities(amenityItems);

        // Price calculation (client-side estimate from seeded DB prices)
        // Use cached payloads for performance.
        const unitPrices = unitPricesCache || {};
        const unitExtraCharges = unitExtraChargesCache || {};
        const highRanges = highRangesCache || [];

        const selectedUnitId = document.getElementById('selected_unit')?.value || '';
        const areaSlug = getSelectedAreaSlug();
        const isExtraModeEligible = isExtraModeEligibleArea(areaSlug);
        const extraChargeMode = document.getElementById('extraChargeMode')?.value || '';
        const checkinYmd = document.getElementById('checkin')?.value || '';
        const checkoutYmd = document.getElementById('checkout')?.value || '';
        const checkinDate = parseYmd(checkinYmd);
        const checkoutDate = parseYmd(checkoutYmd);

        let guestCountNum = Number.parseInt(document.getElementById('guestCount')?.value || '1', 10);
        if (!Number.isFinite(guestCountNum) || guestCountNum < 1) guestCountNum = 1;

        const { basePrice, amenitiesPrice, breakfastExtra, extraChargeLabel, totalPrice } = calculateEstimatedPrices({
            selectedUnitId,
            unitPrices,
            unitExtraCharges,
            isExtraModeEligible,
            extraChargeMode,
            amenityItems,
            guestCountNum,
            highRanges,
            checkinDate,
            checkoutDate
        });

        const basePriceEl = document.getElementById('previewBasePrice');
        const amenitiesPriceEl = document.getElementById('previewAmenitiesPrice');
        const breakfastRowEl = document.getElementById('previewBreakfastRow');
        const breakfastExtraEl = document.getElementById('previewBreakfastExtra');
        const extraChargeLabelEl = document.getElementById('previewExtraChargeLabel');
        const totalPriceEl = document.getElementById('previewPrice');

        if (basePriceEl) basePriceEl.textContent = formatIdr(basePrice);
        if (amenitiesPriceEl) amenitiesPriceEl.textContent = formatIdr(amenitiesPrice);
        if (breakfastExtraEl) breakfastExtraEl.textContent = formatIdr(breakfastExtra);
        if (extraChargeLabelEl) extraChargeLabelEl.textContent = `${extraChargeLabel}:`;
        if (breakfastRowEl) {
            if (breakfastExtra > 0) {
                breakfastRowEl.classList.remove('hidden');
                breakfastRowEl.classList.add('flex');
            } else {
                breakfastRowEl.classList.add('hidden');
                breakfastRowEl.classList.remove('flex');
            }
        }
        if (totalPriceEl) totalPriceEl.textContent = formatIdr(totalPrice);
    };

    // Listen to form changes for preview update (debounced to avoid heavy work per keystroke)
    const debouncedPreview = debounce(function () {
        if (typeof globalThis.updatePreview === 'function') globalThis.updatePreview();
    }, 120);

    const formInputs = document.querySelectorAll(
        '#reservasiForm input, #reservasiForm select, #reservasiForm textarea, #rescheduleForm input, #rescheduleForm select, #rescheduleForm textarea'
    );
    formInputs.forEach(input => {
        input.addEventListener('change', debouncedPreview);
        input.addEventListener('input', debouncedPreview);
    });

    ['agree', 'checkin', 'checkout_display', 'selectedUnit'].forEach((id) => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('change', () => setBookingFlowMessage(''));
        el.addEventListener('input', () => setBookingFlowMessage(''));
    });

    // Initial preview update
    if (typeof globalThis.updatePreview === 'function') globalThis.updatePreview();

    // Ensure required reservation fields are present before submit (client-side hint only)
    if (form) {
        form.addEventListener('submit', function (e) {
            const unitId = document.getElementById('selected_unit')?.value;
            const checkinVal = document.getElementById('checkin')?.value;
            const checkoutVal = document.getElementById('checkout')?.value;
            const countryCodeEl = document.getElementById('country_code');
            const phoneEl = document.getElementById('phone');
            const agree = form?.querySelector('input[name="agree"]');

            if (phoneEl && countryCodeEl) {
                const rawPhone = String(phoneEl.value || '').trim();
                const rawCode = String(countryCodeEl.value || '+62').trim();

                if (rawPhone && !rawPhone.startsWith('+')) {
                    const phoneDigits = rawPhone.replaceAll(/\D+/g, '').replaceAll(/^0+/, '');
                    const codeDigits = rawCode.replaceAll(/\D+/g, '') || '62';
                    phoneEl.value = phoneDigits ? (`+${codeDigits}${phoneDigits}`) : rawPhone;
                }
            }

            if (!checkinVal || !checkoutVal) {
                e.preventDefault();
                setBookingFlowMessage('Please select check-in date, deck, and check-out date before booking.');
                return;
            }

            if (checkoutVal && checkinVal && (checkoutVal < checkinVal)) {
                e.preventDefault();
                setBookingFlowMessage('Check-out date must be after check-in date.');
                return;
            }

            if (!unitId) {
                e.preventDefault();
                setBookingFlowMessage('Please select a deck on the map before booking.');
                document.getElementById('selectedUnit')?.focus();
                return;
            }

            if (agree && !agree.checked) {
                e.preventDefault();
                setBookingFlowMessage('Please agree to the terms & conditions before proceeding.');
                agree.focus();
                return;
            }

            setBookingFlowMessage('');
        });
    }
});
