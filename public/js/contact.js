// Contact Page Logic: Form submission via Fetch
async function submitContact(e) {
    e.preventDefault();

    const name = document.getElementById('ctName').value.trim();
    const email = document.getElementById('ctEmail').value.trim();
    const phone = document.getElementById('ctPhone').value.trim();
    const subject = document.getElementById('ctSubject').value.trim();
    const message = document.getElementById('ctMessage').value.trim();
    const err = document.getElementById('contactError');
    const btn = document.getElementById('contactBtn');

    err.classList.add('hidden');
    btn.disabled = true;
    btn.innerText = 'Sending...';

    try {
        const res = await fetch('/api/contact', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                customerName: name,
                email: email,
                phoneNumber: phone,
                specialRequest: `${subject ? '[' + subject + '] ' : ''}${message}`
            })
        });

        if (res.ok) {
            document.getElementById('contactForm').classList.add('hidden');
            document.getElementById('contactSuccess').classList.remove('hidden');
            document.getElementById('contactForm').reset();
        } else {
            const data = await res.json();
            err.innerText = data.error || 'Failed to send message. Please try again.';
            err.classList.remove('hidden');
        }
    } catch(errObj) {
        err.innerText = 'Network error. Please try again.';
        err.classList.remove('hidden');
    } finally {
        btn.disabled = false;
        btn.innerText = '✉️ Send Message';
    }

    return false;
}
