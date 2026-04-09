/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
document.addEventListener('DOMContentLoaded', () => {
    const body = document.body;
    const themeToggle = document.querySelector('[data-theme-toggle]');
    const sidebarToggles = document.querySelectorAll('[data-sidebar-toggle]');
    const deleteLinks = document.querySelectorAll('[data-confirm]');
    const storedTheme = window.localStorage.getItem('theme');
    const storedSidebarState = window.localStorage.getItem('sidebar-collapsed');

    if (storedTheme === 'night') {
        body.dataset.theme = 'night';
    }

    if (storedSidebarState === 'true') {
        body.classList.add('sidebar-collapsed');
    }

    themeToggle?.addEventListener('click', () => {
        const nextTheme = body.dataset.theme === 'night' ? 'day' : 'night';

        if (nextTheme === 'night') {
            body.dataset.theme = 'night';
        } else {
            delete body.dataset.theme;
        }

        window.localStorage.setItem('theme', nextTheme);
    });

    sidebarToggles.forEach((toggle) => {
        toggle.addEventListener('click', () => {
            body.classList.toggle('sidebar-collapsed');
            window.localStorage.setItem(
                'sidebar-collapsed',
                body.classList.contains('sidebar-collapsed') ? 'true' : 'false',
            );
        });
    });

    deleteLinks.forEach((link) => {
        link.addEventListener('click', (event) => {
            const message = link.getAttribute('data-confirm') ?? 'Czy na pewno chcesz usunąć ten wpis?';

            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});
