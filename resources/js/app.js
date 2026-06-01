import './bootstrap';
import 'bootstrap';
import { createIcons, icons } from 'lucide';

document.addEventListener('DOMContentLoaded', function () {
    createIcons({
        icons: icons,
    });
});

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
