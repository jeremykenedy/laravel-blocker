(function () {
    var root = document.currentScript.closest('[data-blocker-root]');
    var select = root.querySelector('[data-blocker-theme]');
    var key = 'laravelblocker-theme';
    if (select) {
        try {
            var saved = localStorage.getItem(key);
            if (['light', 'dark', 'system'].indexOf(saved) !== -1) {
                root.dataset.theme = saved;
                select.value = saved;
            }
        } catch (error) {}
        select.addEventListener('change', function () {
            root.dataset.theme = select.value;
            try { localStorage.setItem(key, select.value); } catch (error) {}
        });
    }
    root.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });
    var type = root.querySelector('[name="typeId"]');
    var user = root.querySelector('[name="userId"]');
    var value = root.querySelector('[name="value"]');
    if (type && user && value) {
        var syncUser = function () {
            if (type.options[type.selectedIndex].dataset.type === 'user' && user.value) {
                value.value = user.options[user.selectedIndex].dataset.email || '';
            }
        };
        type.addEventListener('change', syncUser);
        user.addEventListener('change', syncUser);
    }
})();
