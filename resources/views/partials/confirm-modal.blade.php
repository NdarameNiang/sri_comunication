{{-- ── Modal de confirmation global ──────────────────────────────── --}}
<div id="confirm-overlay"
     style="display:none;position:fixed;inset:0;z-index:9998;background:rgba(0,0,0,.5);
            backdrop-filter:blur(3px);align-items:center;justify-content:center;">
    <div id="confirm-box"
         style="background:#fff;border-radius:1rem;width:100%;max-width:400px;margin:1rem;
                box-shadow:0 20px 50px rgba(0,0,0,.25);overflow:hidden;
                transform:scale(.95);opacity:0;transition:transform .2s ease,opacity .2s ease;">
        <div style="padding:1.5rem 1.5rem 1rem;">
            <div style="display:flex;align-items:flex-start;gap:.75rem;">
                <div id="confirm-icon-wrap"
                     style="flex-shrink:0;width:2.5rem;height:2.5rem;border-radius:50%;
                            display:flex;align-items:center;justify-content:center;margin-top:.1rem;">
                </div>
                <div>
                    <p id="confirm-title" style="font-weight:700;color:#111827;font-size:.95rem;margin:0 0 .35rem;"></p>
                    <p id="confirm-message" style="color:#6b7280;font-size:.85rem;line-height:1.55;margin:0;"></p>
                </div>
            </div>
        </div>
        <div style="display:flex;gap:.75rem;padding:.75rem 1.5rem 1.25rem;justify-content:flex-end;">
            <button id="confirm-cancel"
                    style="padding:.55rem 1.2rem;border-radius:.6rem;border:1px solid #e5e7eb;
                           background:#fff;color:#374151;font-size:.875rem;font-weight:500;cursor:pointer;">
                Annuler
            </button>
            <button id="confirm-ok"
                    style="padding:.55rem 1.2rem;border-radius:.6rem;border:none;
                           font-size:.875rem;font-weight:600;cursor:pointer;color:#fff;">
                Confirmer
            </button>
        </div>
    </div>
</div>
<script>
(function () {
    const overlay  = document.getElementById('confirm-overlay');
    const box      = document.getElementById('confirm-box');
    const titleEl  = document.getElementById('confirm-title');
    const msgEl    = document.getElementById('confirm-message');
    const okBtn    = document.getElementById('confirm-ok');
    const cancelBtn= document.getElementById('confirm-cancel');
    const iconWrap = document.getElementById('confirm-icon-wrap');

    let _resolve = null;

    const ICONS = {
        danger: { bg:'#fee2e2', color:'#dc2626', svg:'<svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>' },
        warning:{ bg:'#e0e7ff', color:'#4f46e5', svg:'<svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>' },
        info:   { bg:'#dbeafe', color:'#2563eb', svg:'<svg xmlns="http://www.w3.org/2000/svg" style="width:1.1rem;height:1.1rem;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>' },
    };

    function openModal(title, message, type) {
        const icon = ICONS[type] || ICONS.warning;
        iconWrap.style.background = icon.bg;
        iconWrap.style.color      = icon.color;
        iconWrap.innerHTML        = icon.svg;
        okBtn.style.background    = icon.color;
        titleEl.textContent       = title;
        msgEl.textContent         = message;

        overlay.style.display = 'flex';
        requestAnimationFrame(() => {
            box.style.transform = 'scale(1)';
            box.style.opacity   = '1';
        });

        return new Promise(resolve => { _resolve = resolve; });
    }

    function closeModal(result) {
        box.style.transform = 'scale(.95)';
        box.style.opacity   = '0';
        setTimeout(() => { overlay.style.display = 'none'; }, 180);
        if (_resolve) { _resolve(result); _resolve = null; }
    }

    okBtn.addEventListener('click',     () => closeModal(true));
    cancelBtn.addEventListener('click', () => closeModal(false));
    overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(false); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(false); });

    // Intercepte les formulaires avec data-confirm
    document.addEventListener('submit', async function (e) {
        const form = e.target;
        const msg  = form.dataset.confirm;
        const title= form.dataset.confirmTitle || 'Confirmation';
        const type = form.dataset.confirmType  || 'warning';
        if (!msg) return;
        e.preventDefault();
        const ok = await openModal(title, msg, type);
        if (ok) { form.removeAttribute('data-confirm'); form.submit(); }
    }, true);

    // Boutons standalone avec data-confirm (non-form)
    document.addEventListener('click', async function (e) {
        const btn = e.target.closest('[data-confirm-btn]');
        if (!btn) return;
        e.preventDefault();
        const ok = await openModal(
            btn.dataset.confirmTitle || 'Confirmation',
            btn.dataset.confirmBtn,
            btn.dataset.confirmType || 'warning'
        );
        if (ok && btn.form) { btn.form.removeAttribute('data-confirm'); btn.form.submit(); }
    }, true);
})();
</script>
