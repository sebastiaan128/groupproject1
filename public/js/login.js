import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-app.js';
import { getAuth, OAuthProvider, signInWithPopup, signOut } from 'https://www.gstatic.com/firebasejs/10.14.0/firebase-auth.js';

const app = initializeApp(window.firebaseConfig);
const auth = getAuth(app);
const errorDiv = document.getElementById('error');

function showError(msg) {
    errorDiv.textContent = msg;
    errorDiv.classList.remove('hidden');
}

document.getElementById('microsoftLogin').addEventListener('click', async () => {
    try {
        await signOut(auth);

        const provider = new OAuthProvider('microsoft.com');
        provider.setCustomParameters({ prompt: 'select_account' });
        provider.addScope('email');
        provider.addScope('profile');

        const result = await signInWithPopup(auth, provider);
        const idToken = await result.user.getIdToken(true);

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/auth/verify';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'idToken';
        input.value = idToken;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    } catch (err) {
        console.error('Microsoft login error:', err.code, err.message);
        showError('Microsoft login mislukt. Probeer opnieuw.');
    }
});
