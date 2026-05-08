import './bootstrap';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// AOS - Animations au scroll
import AOS from 'aos';
AOS.init({
    duration: 800,
    once: true,
    offset: 50,
});

// SweetAlert2 - Popups élégantes
import Swal from 'sweetalert2';
window.Swal = Swal;

// Notyf - Notifications toast
import { Notyf } from 'notyf';
window.notyf = new Notyf({
    duration: 3000,
    position: { x: 'right', y: 'top' },
    ripple: true,
});

// Flatpickr - Date picker
import flatpickr from 'flatpickr';
import { French } from 'flatpickr/dist/l10n/fr.js';
window.flatpickr = flatpickr;
flatpickr.localize(French);

// GSAP - Animations puissantes
import { gsap } from 'gsap';
window.gsap = gsap;

// Chart.js - Graphiques
import Chart from 'chart.js/auto';
window.Chart = Chart;