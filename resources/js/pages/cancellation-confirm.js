import { onReady } from '../utils/dom.js';

onReady(() => {
    const checkbox = document.getElementById('accept-terms');
    const btn = document.getElementById('process-refund-btn');
    const form = document.getElementById('refund-form');

    if (!checkbox || !btn || !form) return;

    checkbox.addEventListener('change', () => {
        btn.disabled = !checkbox.checked;
    });

    form.addEventListener('submit', (e) => {
        if (!checkbox.checked) {
            e.preventDefault();
            return;
        }

        const confirmed = confirm('Apakah Anda yakin ingin melakukan refund dan membatalkan booking ini? Tindakan ini tidak dapat dibatalkan.');
        if (!confirmed) {
            e.preventDefault();
            return;
        }

        // disable button to prevent double submit
        btn.disabled = true;
        btn.textContent = 'Processing...';
    });
});

export default {};
