import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

document.addEventListener('submit', function (e) {
    const form = e.target;
    const confirmMsg = form.getAttribute('data-confirm');
    if (confirmMsg) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi',
            text: confirmMsg,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#C9B037',
            cancelButtonColor: '#6B7280',
            confirmButtonText: 'Ya, lanjutkan!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
        }).then((result) => {
            if (result.isConfirmed) {
                form.removeAttribute('data-confirm');
                form.submit();
            }
        });
    }
});
