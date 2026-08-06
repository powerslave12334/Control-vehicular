import Swal from 'sweetalert2';
window.Swal = Swal;

import Chart from 'chart.js/auto';
window.Chart = Chart;

import { loadGoogleMaps } from './google-maps-loader';
window.loadGoogleMaps = loadGoogleMaps;
