import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-app.js';
import { getAuth, OAuthProvider, signInWithPopup } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-auth.js';

const app = initializeApp(window.firebaseConfig);
const auth = getAuth(app);
const errorDiv = document.getElementById('error');

function showError(msg) {
    errorDiv.textContent = msg;
    errorDiv.classList.remove('hidden');
}

document.getElementById('microsoftLogin').addEventListener('click', async () => {
    try {
        const provider = new OAuthProvider('microsoft.com');
        const result = await signInWithPopup(auth, provider);
        const idToken = await result.user.getIdToken();

        const res = await fetch('/auth/verify', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ idToken }),
        });

        if (!res.ok) {
            showError('Verificatie mislukt. Probeer opnieuw.');
            return;
        }

        window.location.href = '/';
    } catch (err) {
        console.error('Microsoft login error:', err.code, err.message);
        showError('Microsoft login mislukt. Probeer opnieuw.');
    }
});
