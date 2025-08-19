import './bootstrap';

import Alpine from 'alpinejs';
import {
    Chart,
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    DoughnutController,
    ArcElement,
    Tooltip,
    Legend,
    PieController
} from 'chart.js';
import Swal from 'sweetalert2';

Chart.register(
    BarController,
    BarElement,
    CategoryScale,
    LinearScale,
    DoughnutController,
    ArcElement,
    Tooltip,
    Legend,
    PieController
);

window.Alpine = Alpine;
window.Swal = Swal;
window.Chart = Chart;

Alpine.start();