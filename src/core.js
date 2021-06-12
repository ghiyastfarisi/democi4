import './core.scss';
import 'babel-polyfill';

document.addEventListener('DOMContentLoaded', () => {
    const $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
    if ($navbarBurgers.length > 0) {
        $navbarBurgers.forEach( el => {
            el.addEventListener('click', () => {
                const target = el.dataset.target;
                const $target = document.getElementById(target);

                el.classList.toggle('is-active');
                $target.classList.toggle('sidebar-shown');
            });
        });
    }
});

const cookie = document.cookie
const splitted = decodeURIComponent(cookie).split('=')
const COOKIE_OBJECT = (splitted.length > 0 && splitted[0] === 'auth') ? Object.freeze(JSON.parse(splitted[1])) : {}