document.addEventListener('DOMContentLoaded', () => {
    // Get data from window object (injected by Blade template)
    const outboundsData = window.outboundsData || {};
    const transportationPrice = window.transportationPrice || 200000;

    // DOM elements
    const list = document.getElementById('outCatList');
    const track = document.getElementById('outTrack');
    const knob = document.getElementById('outKnob');
    const main = document.getElementById('outMainImage');
    const thumbs = document.getElementById('outThumbs');
    const infoBtn = document.getElementById('outInfoBtn');
    const modal = document.getElementById('outInfoModal');
    const close1 = document.getElementById('outCloseModal');
    const close2 = document.getElementById('outCloseModal2');
    const variantSection = document.getElementById('variantSection');
    const variantGrid = document.getElementById('variantGrid');

    // Form elements
    const selectedOutboundId = document.getElementById('selectedOutboundId');
    const selectedVariantId = document.getElementById('selectedVariantId');
    const outGuestCount = document.getElementById('outGuestCount');
    const guestLabel = document.getElementById('guestLabel');
    const guestUnit = document.getElementById('guestUnit');
    const outGuestInfo = document.getElementById('outGuestInfo');
    const dokumentasiOption = document.getElementById('dokumentasiOption');
    const transportasiOption = document.getElementById('transportasiOption');
    const outCheckin = document.getElementById('outCheckin');
    const outName = document.getElementById('outName');

    // Preview elements (sidebar)
    const previewDate = document.getElementById('previewDate');
    const previewActivity = document.getElementById('previewActivity');
    const previewVariantSection = document.getElementById('previewVariantSection');
    const previewVariant = document.getElementById('previewVariant');
    const previewGuest = document.getElementById('previewGuest');
    const previewExtras = document.getElementById('previewExtras');
    const previewPrice = document.getElementById('previewPrice');
    const previewActivityPrice = document.getElementById('previewActivityPrice');
    const previewQty = document.getElementById('previewQty');
    const previewExtrasRow = document.getElementById('previewExtrasRow');
    const previewExtrasPrice = document.getElementById('previewExtrasPrice');
    const extrasCount = document.getElementById('extrasCount');

    // State
    let currentOutbound = null;
    let currentVariant = null;
    let currentPrice = 0;
    let guestCount = 1;

    // Utility functions
    function formatCurrency(value) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(value);
    }

    function setKnobTo(btn) {
        if (!track || !knob || !btn) return;
        const tRect = track.getBoundingClientRect();
        const bRect = btn.getBoundingClientRect();
        const center = (bRect.left + bRect.right) / 2;
        const left = Math.max(0, center - tRect.left - (knob.offsetWidth / 2));
        const maxLeft = tRect.width - knob.offsetWidth;
        knob.style.left = Math.min(maxLeft, Math.max(0, left)) + 'px';
    }

    function renderThumbs(galleries) {
        if (!thumbs) return;
        thumbs.innerHTML = '';

        if (!galleries || galleries.length === 0) {
            if (main) main.innerHTML = '<div class="w-full min-h-[220px] bg-gray-100 flex items-center justify-center rounded-lg"><span class="text-gray-400">No image</span></div>';
            return;
        }

        galleries.forEach((gallery, idx) => {
            // Support both storage path and direct public path
            const src = gallery.image_path.startsWith('images/') ? '/' + gallery.image_path : '/storage/' + gallery.image_path;
            const d = document.createElement('button');
            d.type = 'button';
            d.className = 'w-24 h-14 rounded-lg bg-white border border-[#e9ebec] overflow-hidden hover:ring-2 hover:ring-[#017249]/40 transition';
            d.innerHTML = `<img alt="${gallery.description || 'thumb ' + (idx + 1)}" class="w-full h-full object-contain" src="${src}">`;
            d.addEventListener('click', () => {
                if (main) main.innerHTML = `<img alt="${gallery.description || 'main ' + (idx + 1)}" class="w-full h-auto max-h-[500px] object-contain rounded-lg" src="${src}">`;
            });
            thumbs.appendChild(d);
        });

        if (galleries[0] && main) {
            // Support both storage path and direct public path
            const firstSrc = galleries[0].image_path.startsWith('images/') ? '/' + galleries[0].image_path : '/storage/' + galleries[0].image_path;
            main.innerHTML = `<img alt="${galleries[0].description || 'main 1'}" class="w-full h-auto max-h-[500px] object-contain rounded-lg" src="${firstSrc}">`;
        }
    }

    function renderVariants(outbound) {
        if (!variantSection || !variantGrid) return;

        if (!outbound.variants || outbound.variants.length === 0) {
            variantSection.classList.add('hidden');
            return;
        }

        variantSection.classList.remove('hidden');
        variantGrid.innerHTML = '';

        // Get the base outbound price if variants don't have their own prices
        const basePrice = outbound.prices && outbound.prices[0] ? outbound.prices[0].price : 0;

        outbound.variants.forEach((variant, idx) => {
            // Use variant price if available, otherwise use base outbound price
            const price = variant.prices && variant.prices[0] ? variant.prices[0].price : basePrice;
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'variant-btn p-4 rounded-xl border-2 border-gray-200 hover:border-[#017249]/50 transition-all text-left';
            btn.dataset.variantId = variant.id;

            // Use variant_label instead of name, and show capacity info
            const variantName = variant.variant_label || variant.name || 'Variant ' + (idx + 1);
            const capacityInfo = variant.min_pax_per_unit && variant.max_pax_per_unit
                ? `${variant.min_pax_per_unit}-${variant.max_pax_per_unit} orang`
                : (variant.max_pax_per_unit ? `Max ${variant.max_pax_per_unit} orang` : '');

            // Show documentation status
            let docuInfo = '';
            if (variant.includes_documentation === true || variant.includes_documentation === 1) {
                docuInfo = '<span class="text-green-600">📸 Termasuk Dokumentasi</span>';
            } else if (variant.includes_documentation === false || variant.includes_documentation === 0) {
                docuInfo = '<span class="text-gray-500">📷 Tidak Termasuk Dokumentasi</span>';
            }

            btn.innerHTML = `
                <div class="font-semibold text-gray-800">${variantName}</div>
                ${capacityInfo ? `<div class="text-xs text-gray-500 mt-1">${capacityInfo}</div>` : ''}
                ${docuInfo ? `<div class="text-xs mt-1">${docuInfo}</div>` : ''}
                <div class="text-sm font-bold text-[#017249] mt-2">${formatCurrency(price)}</div>
            `;
            btn.addEventListener('click', () => selectVariant(variant, btn, basePrice));
            variantGrid.appendChild(btn);

            // Auto-select first variant
            if (idx === 0) {
                setTimeout(() => selectVariant(variant, btn, basePrice), 10);
            }
        });
    }

    function selectVariant(variant, btn, basePrice = 0) {
        currentVariant = variant;

        // Update UI
        document.querySelectorAll('.variant-btn').forEach(b => {
            b.classList.remove('border-[#017249]', 'bg-[#017249]/5');
            b.classList.add('border-gray-200');
        });
        btn.classList.remove('border-gray-200');
        btn.classList.add('border-[#017249]', 'bg-[#017249]/5');

        // Update hidden input
        if (selectedVariantId) selectedVariantId.value = variant.id;

        // Update price - use variant price if available, otherwise use base outbound price
        currentPrice = variant.prices && variant.prices[0] ? parseFloat(variant.prices[0].price) : parseFloat(basePrice);

        // Update dokumentasi addon visibility based on variant's includes_documentation
        updateDokumentasiOptionForVariant(variant);

        updatePreview();
    }

    function updateDokumentasiOptionForVariant(variant) {
        if (!dokumentasiOption) return;

        // If variant already includes documentation, hide the add-on option
        if (variant && (variant.includes_documentation === true || variant.includes_documentation === 1)) {
            dokumentasiOption.classList.add('hidden');
            // Uncheck if hidden
            const checkbox = dokumentasiOption.querySelector('input[type="checkbox"]');
            if (checkbox) checkbox.checked = false;
        } else if (currentOutbound && currentOutbound.allows_documentation_addon) {
            // Only show if the outbound allows documentation addon AND variant doesn't include it
            dokumentasiOption.classList.remove('hidden');
        }
    }

    function updateGuestInfo(outbound) {
        if (!outGuestInfo) return;

        let info = [];
        if (outbound.min_participants) info.push(`Min: ${outbound.min_participants} ${outbound.pricing_type === 'per_unit' ? outbound.unit_name : 'people'}`);
        if (outbound.max_participants) info.push(`Max: ${outbound.max_participants} ${outbound.pricing_type === 'per_unit' ? outbound.unit_name : 'people'}`);
        if (outbound.min_age) info.push(`Min age: ${outbound.min_age} years`);

        outGuestInfo.textContent = info.join(' | ');
    }

    function updateExtrasOptions(outbound) {
        if (dokumentasiOption) {
            if (outbound.allows_documentation_addon) {
                dokumentasiOption.classList.remove('hidden');
            } else {
                dokumentasiOption.classList.add('hidden');
                // Uncheck if hidden
                const checkbox = dokumentasiOption.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            }
        }

        if (transportasiOption) {
            if (outbound.requires_transportation) {
                transportasiOption.classList.remove('hidden');
            } else {
                transportasiOption.classList.add('hidden');
                // Uncheck if hidden
                const checkbox = transportasiOption.querySelector('input[type="checkbox"]');
                if (checkbox) checkbox.checked = false;
            }
        }
    }

    function updatePreview() {
        // Update date preview
        if (previewDate && outCheckin) {
            if (outCheckin.value) {
                const date = new Date(outCheckin.value);
                const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };
                previewDate.textContent = date.toLocaleDateString('id-ID', options);
            } else {
                previewDate.textContent = 'Pilih tanggal';
            }
        }

        // Update activity preview
        if (previewActivity && currentOutbound) {
            previewActivity.textContent = currentOutbound.name;
        }

        // Update variant preview
        if (previewVariantSection && previewVariant) {
            if (currentVariant) {
                previewVariantSection.classList.remove('hidden');
                previewVariant.textContent = currentVariant.variant_label || currentVariant.name || '-';
            } else if (currentOutbound && currentOutbound.variants && currentOutbound.variants.length > 0) {
                previewVariantSection.classList.remove('hidden');
                previewVariant.textContent = 'Pilih variant';
            } else {
                previewVariantSection.classList.add('hidden');
            }
        }

        // Update guest preview
        const quantity = parseInt(outGuestCount?.value || 1);
        const unit = currentOutbound?.pricing_type === 'per_unit' ? currentOutbound?.unit_name : 'orang';
        if (previewGuest) {
            previewGuest.textContent = `${quantity} ${unit}`;
        }

        // Calculate extras
        let extrasTotal = 0;
        let checkedExtrasCount = 0;
        document.querySelectorAll('.extra-checkbox:checked').forEach(checkbox => {
            extrasTotal += parseInt(checkbox.dataset.price || 0);
            checkedExtrasCount++;
        });

        // Update extras badge count
        if (extrasCount) {
            extrasCount.textContent = checkedExtrasCount;
        }

        // Update extras preview
        if (previewExtras) {
            if (checkedExtrasCount > 0) {
                previewExtras.textContent = `${checkedExtrasCount} tambahan`;
            } else {
                previewExtras.textContent = 'Tidak ada';
            }
        }

        // Update price details
        if (previewActivityPrice) {
            previewActivityPrice.textContent = formatCurrency(currentPrice);
        }
        if (previewQty) {
            previewQty.textContent = `× ${quantity}`;
        }

        // Update extras row
        if (previewExtrasRow && previewExtrasPrice) {
            if (extrasTotal > 0) {
                previewExtrasRow.classList.remove('hidden');
                previewExtrasPrice.textContent = formatCurrency(extrasTotal);
            } else {
                previewExtrasRow.classList.add('hidden');
            }
        }

        // Update total
        const baseTotal = currentPrice * quantity;
        const total = baseTotal + extrasTotal;
        if (previewPrice) {
            previewPrice.textContent = formatCurrency(total);
        }
    }

    // Legacy function for compatibility
    function updatePriceSummary() {
        updatePreview();
    }

    function setOutbound(outboundId) {
        if (!list) return;

        const outbound = outboundsData[outboundId];
        if (!outbound) return;

        currentOutbound = outbound;
        currentVariant = null;

        // Update button states
        const btns = [...list.querySelectorAll('.cat-btn')];
        btns.forEach(b => b.setAttribute('aria-pressed', 'false'));
        const active = btns.find(b => b.dataset.outboundId == outboundId);
        if (active) {
            active.setAttribute('aria-pressed', 'true');
            setKnobTo(active);
        }

        // Update hidden input
        if (selectedOutboundId) selectedOutboundId.value = outboundId;
        if (selectedVariantId) selectedVariantId.value = '';

        // Get base price from outbound
        const basePrice = outbound.prices && outbound.prices[0] ? parseFloat(outbound.prices[0].price) : 0;

        // Set price based on whether has variants
        if (outbound.variants && outbound.variants.length > 0) {
            // Price will be set when variant is selected, but show base price initially
            currentPrice = basePrice;
        } else {
            currentPrice = basePrice;
        }

        // Update guest count label and constraints
        if (guestLabel) {
            guestLabel.textContent = outbound.pricing_type === 'per_unit' ? `Jumlah ${outbound.unit_name}` : 'Jumlah Peserta';
        }
        if (guestUnit) {
            guestUnit.textContent = outbound.pricing_type === 'per_unit' ? outbound.unit_name : 'orang';
        }

        // Set initial guest count
        guestCount = outbound.min_participants || 1;
        if (outGuestCount) outGuestCount.value = guestCount;

        // Render thumbnails
        renderThumbs(outbound.galleries || []);

        // Render variants
        renderVariants(outbound);

        // Update guest info text
        updateGuestInfo(outbound);

        // Update extras options
        updateExtrasOptions(outbound);

        // Update preview sidebar
        updatePreview();
    }

    // Setup outbound buttons
    list?.querySelectorAll('.cat-btn').forEach((btn, idx) => {
        btn.addEventListener('click', () => setOutbound(btn.dataset.outboundId));
        btn.addEventListener('keydown', (e) => {
            const btns = [...list.querySelectorAll('.cat-btn')];
            if (e.key === 'ArrowRight') {
                const n = btns[(idx + 1) % btns.length];
                n.focus();
                setOutbound(n.dataset.outboundId);
            }
            if (e.key === 'ArrowLeft') {
                const p = btns[(idx - 1 + btns.length) % btns.length];
                p.focus();
                setOutbound(p.dataset.outboundId);
            }
        });
    });

    // Guest count controls
    const guestDecrease = document.getElementById('outGuestDecrease');
    const guestIncrease = document.getElementById('outGuestIncrease');

    guestDecrease?.addEventListener('click', () => {
        const min = currentOutbound?.min_participants || 1;
        if (guestCount > min) {
            guestCount--;
            if (outGuestCount) outGuestCount.value = guestCount;
            updatePreview();
        }
    });

    guestIncrease?.addEventListener('click', () => {
        const max = currentOutbound?.max_participants || 999;
        if (guestCount < max) {
            guestCount++;
            if (outGuestCount) outGuestCount.value = guestCount;
            updatePreview();
        }
    });

    // Extras checkboxes
    document.querySelectorAll('.extra-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', updatePreview);
    });

    // Date change listener
    if (outCheckin) {
        outCheckin.addEventListener('change', updatePreview);
    }

    // Info modal
    function openInfoModal(outbound) {
        if (!modal) return;

        // Update modal content
        const infoTitle = document.getElementById('outInfoTitle');
        const infoSubtitle = document.getElementById('outInfoSubtitle');
        const durationInfo = document.getElementById('outDurationInfo');
        const distanceInfo = document.getElementById('outDistanceInfo');
        const participantsInfo = document.getElementById('outParticipantsInfo');
        const ageInfo = document.getElementById('outAgeInfo');
        const facilitiesList = document.getElementById('outFacilitiesList');
        const facilitiesCount = document.getElementById('outFacilitiesCount');
        const basePriceSection = document.getElementById('outBasePriceSection');
        const basePrice = document.getElementById('outBasePrice');
        const priceUnit = document.getElementById('outPriceUnit');
        const variantsPricing = document.getElementById('outVariantsPricing');
        const variantsList = document.getElementById('outVariantsList');
        const transportNotice = document.getElementById('outTransportNotice');
        const docuNotice = document.getElementById('outDocuNotice');
        const transportPriceSpan = document.getElementById('outTransportPrice');
        const minParticipants = document.getElementById('outMinParticipants');
        const maxParticipants = document.getElementById('outMaxParticipants');

        if (infoTitle) infoTitle.textContent = `DETAILS — ${outbound.name}`;
        if (infoSubtitle) infoSubtitle.textContent = 'Facilities, prices, and activity details';

        // Duration (stored in minutes in database)
        if (durationInfo) {
            if (outbound.duration) {
                const mins = parseInt(outbound.duration);
                if (mins >= 60) {
                    const hours = Math.floor(mins / 60);
                    const remainMins = mins % 60;
                    durationInfo.textContent = remainMins > 0 ? `~${hours}h ${remainMins}m` : `~${hours} hours`;
                } else {
                    durationInfo.textContent = `~${mins} mins`;
                }
            } else {
                durationInfo.textContent = '-';
            }
        }

        // Distance
        if (distanceInfo) {
            distanceInfo.textContent = outbound.distance ? `${parseFloat(outbound.distance)} km` : '-';
        }

        // Participants info grid
        if (participantsInfo) {
            const min = outbound.min_participants || 1;
            const max = outbound.max_participants || '∞';
            participantsInfo.textContent = `${min} - ${max}`;
        }

        // Capacity section
        if (minParticipants) {
            minParticipants.textContent = outbound.min_participants ? `${outbound.min_participants} person${outbound.min_participants > 1 ? 's' : ''}` : '1 person';
        }
        if (maxParticipants) {
            maxParticipants.textContent = outbound.max_participants ? `${outbound.max_participants} persons` : '∞';
        }

        // Age
        if (ageInfo) {
            ageInfo.textContent = outbound.min_age ? `${outbound.min_age}+ years` : 'All ages';
        }

        // Facilities
        if (facilitiesList && outbound.facilities) {
            facilitiesList.innerHTML = '';
            if (outbound.facilities.length > 0) {
                outbound.facilities.forEach(facility => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center gap-2 text-sm text-gray-600';
                    div.innerHTML = `
                        <svg class="w-4 h-4 text-[#017249] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>${facility.name}</span>
                    `;
                    facilitiesList.appendChild(div);
                });
            } else {
                facilitiesList.innerHTML = '<span class="text-sm text-gray-500">No facilities data available.</span>';
            }
            if (facilitiesCount) {
                facilitiesCount.textContent = `${outbound.facilities.length} item`;
            }
        }

        // Pricing
        if (outbound.variants && outbound.variants.length > 0) {
            // Get base price from outbound for variants without individual prices
            const outboundBasePrice = outbound.prices && outbound.prices[0] ? parseFloat(outbound.prices[0].price) : 0;

            if (basePriceSection) basePriceSection.classList.add('hidden');
            if (variantsPricing) {
                variantsPricing.classList.remove('hidden');
                if (variantsList) {
                    variantsList.innerHTML = '';
                    outbound.variants.forEach(variant => {
                        // Use variant price if available, otherwise use base outbound price
                        const price = variant.prices && variant.prices[0] ? parseFloat(variant.prices[0].price) : outboundBasePrice;
                        const variantName = variant.variant_label || variant.name || 'Variant';
                        const div = document.createElement('div');
                        div.className = 'flex justify-between items-center py-2 border-b border-gray-100 last:border-0';
                        div.innerHTML = `
                            <span class="text-gray-600">${variantName}</span>
                            <span class="font-bold text-[#017249]">${formatCurrency(price)}</span>
                        `;
                        variantsList.appendChild(div);
                    });
                }
            }
        } else {
            if (variantsPricing) variantsPricing.classList.add('hidden');
            if (basePriceSection) basePriceSection.classList.remove('hidden');
            const price = outbound.prices && outbound.prices[0] ? parseFloat(outbound.prices[0].price) : 0;
            if (basePrice) basePrice.textContent = formatCurrency(price);
            if (priceUnit) priceUnit.textContent = outbound.pricing_type === 'per_unit' ? `per ${outbound.unit_name}` : 'per person';
        }

        // Transportation notice
        if (transportNotice) {
            if (outbound.requires_transportation) {
                transportNotice.classList.remove('hidden');
                if (transportPriceSpan) transportPriceSpan.textContent = formatCurrency(transportationPrice);
            } else {
                transportNotice.classList.add('hidden');
            }
        }

        // Documentation notice
        if (docuNotice) {
            if (outbound.allows_documentation_addon) {
                docuNotice.classList.remove('hidden');
            } else {
                docuNotice.classList.add('hidden');
            }
        }

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    infoBtn?.addEventListener('click', () => {
        if (currentOutbound) openInfoModal(currentOutbound);
    });

    function closeModal() {
        modal?.classList.add('hidden');
        modal?.classList.remove('flex');
    }
    close1?.addEventListener('click', closeModal);
    close2?.addEventListener('click', closeModal);

    // Resize handler
    window.addEventListener('resize', () => {
        const active = list ? [...list.querySelectorAll('.cat-btn')]
            .find(b => b.getAttribute('aria-pressed') === 'true') : null;
        if (active) setKnobTo(active);
    });

    // sync date inputs with hidden form inputs
    const checkinInput = document.getElementById('outCheckin');
    const hiddenCheckin = document.getElementById('hiddenCheckin');
    const hiddenActivityDate = document.getElementById('hiddenActivityDate');

    function syncDateInputs() {
        if (hiddenCheckin && checkinInput) hiddenCheckin.value = checkinInput.value;
        if (hiddenActivityDate && checkinInput) hiddenActivityDate.value = checkinInput.value;
    }

    if (checkinInput) checkinInput.addEventListener('change', syncDateInputs);

    // form validation
    const form = document.getElementById('outReservasiForm');
    if (form) {
        form.addEventListener('submit', (e) => {
            // sync values before validation
            syncDateInputs();

            const agree = form?.querySelector('input[name="agree"]');
            const name = document.getElementById('outName');
            const email = document.getElementById('outEmail');
            const phone = document.getElementById('outPhone');
            const outboundIdInput = document.getElementById('selectedOutboundId');

            // Validate outbound_id
            if (!outboundIdInput?.value) {
                e.preventDefault();
                alert('Please select an outbound activity.');
                return;
            }

            if (!name?.value.trim()) {
                e.preventDefault();
                alert('Please enter your full name.');
                name?.focus();
                return;
            }

            if (!email?.value.trim()) {
                e.preventDefault();
                alert('Please enter your email.');
                email?.focus();
                return;
            }

            if (!phone?.value.trim()) {
                e.preventDefault();
                alert('Please enter your phone number.');
                phone?.focus();
                return;
            }

            if (!checkinInput?.value) {
                e.preventDefault();
                alert('Please select an activity date.');
                checkinInput?.focus();
                return;
            }

            if (agree && !agree.checked) {
                e.preventDefault();
                alert('Please agree to the terms & conditions before proceeding.');
                agree.focus();
                return;
            }

            // Form is valid, let it submit normally
            console.log('Form submitted with data:', {
                outbound_id: outboundIdInput?.value,
                variant_id: selectedVariantId?.value,
                activity_date: checkinInput?.value,
                guest_count: outGuestCount?.value,
                name: name?.value,
                email: email?.value,
                phone: phone?.value,
            });
        });
    }

    // Initialize with first outbound
    const firstBtn = list?.querySelector('.cat-btn');
    if (firstBtn) {
        setTimeout(() => setOutbound(firstBtn.dataset.outboundId), 10);
    }
});
