function showError(input, message) {
    const formGroup = input.closest('.form-group');
    let error = formGroup.querySelector('.error-text');
    if (!error) {
        error = document.createElement('div');
        error.className = 'error-text';
        error.style.color = 'red';
        error.style.fontSize = '0.9em';
        error.style.marginTop = '5px';
        formGroup.appendChild(error);
    }
    error.textContent = message;
    input.style.borderColor = 'red';
}

function clearError(input) {
    const formGroup = input.closest('.form-group');
    const error = formGroup.querySelector('.error-text');
    if (error) error.remove();
    input.style.borderColor = '#ccc';
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

document.addEventListener('DOMContentLoaded', function () {
    const bukuForm = document.querySelector('form[action="tambah_buku.php"]');
    if (bukuForm) {
        const judul = bukuForm.querySelector('input[name="judul"]');
        const stok = bukuForm.querySelector('input[name="stok"]');

        if (judul) {
            judul.addEventListener('blur', () => {
                if (judul.value.trim().length < 3) {
                    showError(judul, "Judul minimal 3 karakter");
                } else {
                    clearError(judul);
                }
            });
        }

        if (stok) {
            stok.addEventListener('input', () => {
                if (parseInt(stok.value) <= 0) {
                    showError(stok, "Stok harus lebih dari 0");
                } else {
                    clearError(stok);
                }
            });
        }
    }

    const anggotaForm = document.querySelector('form[action="tambah_anggota.php"]');
    if (anggotaForm) {
        const email = anggotaForm.querySelector('input[name="email"]');
        if (email) {
            email.addEventListener('input', () => {
                if (!validateEmail(email.value)) {
                    showError(email, "Format email tidak valid");
                } else {
                    clearError(email);
                }
            });
        }
    }

    const firstInput = document.querySelector('input[type="text"], input[type="email"], input[type="number"], input[type="date"], select');
    if (firstInput) firstInput.focus();
});