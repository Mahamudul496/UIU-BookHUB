// Shared helpers: API calls, login guard, user box, logout.
(function () {
    const inSub = /\/(student|admin)\//.test(location.pathname);
    const BASE = inSub ? '../' : '';
    const area = location.pathname.includes('/admin/') ? 'admin'
               : location.pathname.includes('/student/') ? 'student' : 'public';

    const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    const imgUrl = u => !u ? BASE + 'assets/logo.png' : (/^https?:/.test(u) ? u : BASE + u);
    const initials = n => String(n || '?').trim().split(/\s+/).slice(0, 2).map(w => w[0]).join('').toUpperCase();

    async function api(path, body) {
        const opts = body ? { method: 'POST', body: body instanceof FormData ? body : new URLSearchParams(body) } : {};
        const res = await fetch(BASE + 'api/' + path, { credentials: 'same-origin', ...opts });
        let data = {};
        try { data = await res.json(); } catch (e) { data = { success: false, message: 'Server error. Is Apache/MySQL running?' }; }
        data.status = res.status;
        return data;
    }

    async function logout() {
        await api('auth/logout.php', {});
        location.href = BASE + 'index.php';
    }

    async function init() {
        const me = await api('auth/me.php');
        const user = me.success ? me.user : null;

        if (area !== 'public') {
            if (!user) { location.href = BASE + 'login.php'; return null; }
            if (area === 'admin' && user.role !== 'admin') { location.href = BASE + 'student/dashboard.php'; return null; }
            if (area === 'student' && user.role === 'admin') { location.href = BASE + 'admin/dashboard.php'; return null; }
            document.querySelectorAll('.user-mini').forEach(box => {
                const av = box.querySelector('.avatar'), nm = box.querySelector('strong');
                if (av) av.textContent = initials(user.full_name);
                if (nm) nm.textContent = user.full_name;
            });
            document.querySelectorAll('.side-bottom a').forEach(a => {
                if (/logout/i.test(a.textContent)) a.addEventListener('click', e => { e.preventDefault(); logout(); });
            });
        } else if (user) {
            const dash = user.role === 'admin' ? 'admin/dashboard.php' : 'student/dashboard.php';
            document.querySelectorAll('.login-link').forEach(a => { a.textContent = 'Dashboard'; a.href = BASE + dash; });
        }
        return user;
    }

    window.BH = { BASE, esc, imgUrl, initials, api, logout, user: null, ready: null };
    BH.ready = init().then(u => (BH.user = u));
})();
