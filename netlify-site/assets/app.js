const API_ROOT = '/.netlify/functions/api';
const STORAGE_KEY = 'selection-rp-session';

function getSession() {
  try {
    return JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
  } catch {
    return {};
  }
}

function setSession(session) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(session));
}

function clearSession() {
  localStorage.removeItem(STORAGE_KEY);
}

async function apiRequest(action, data = {}) {
  const body = JSON.stringify({ action, ...data });
  const response = await fetch(`${API_ROOT}?action=${encodeURIComponent(action)}`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body,
  });
  const result = await response.json();
  if (!response.ok || result.error) {
    throw new Error(result.error || `HTTP ${response.status}`);
  }
  return result;
}

function redirectToLogin() {
  window.location.href = 'login.html';
}

function requireAuth() {
  const session = getSession();
  if (!session.nickname || !session.password) {
    redirectToLogin();
    return null;
  }
  return session;
}

function showMessage(element, message, type = 'danger') {
  if (!element) return;
  element.innerHTML = `<div class="alert alert-${type}">${message}</div>`;
}

function attachLogoutButtons() {
  const logout = document.getElementById('logout-button');
  if (logout) {
    logout.addEventListener('click', () => {
      clearSession();
      redirectToLogin();
    });
  }
}

async function initLoginPage() {
  const form = document.getElementById('login-form');
  const message = document.getElementById('login-message');
  if (!form) return;
  
  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    showMessage(message, 'Завантаження...', 'info');
    const nickname = form.nickname.value.trim();
    const password = form.password.value.trim();
    try {
      const response = await apiRequest('login', { nickname, password });
      setSession({ nickname, password });
      window.location.href = 'profile.html';
    } catch (error) {
      showMessage(message, error.message, 'danger');
    }
  });
}

async function initProfilePage() {
  attachLogoutButtons();
  const session = requireAuth();
  if (!session) return;

  const container = document.getElementById('profile-card');
  if (!container) return;

  try {
    const response = await apiRequest('profile', session);
    const user = response.user;
    container.innerHTML = `
      <h2>Вітаємо, ${user.nickname}</h2>
      <div class="profile-row mt-3">
        <div class="profile-field"><strong>ID:</strong>${user.id || '-'}</div>
        <div class="profile-field"><strong>Баланс донату:</strong>${user.donate || 0}</div>
        <div class="profile-field"><strong>Готівка:</strong>${user.cash || 0}</div>
        <div class="profile-field"><strong>Рівень:</strong>${user.level || 0}</div>
        <div class="profile-field"><strong>Телефон:</strong>${user.phone || '-'}</div>
        <div class="profile-field"><strong>Банк:</strong>${user.bank || 0}</div>
        <div class="profile-field"><strong>Статус:</strong>${user.online ? 'У грі' : 'Не в грі'}</div>
      </div>
    `;
  } catch (error) {
    container.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
  }
}

async function initDonatePage() {
  attachLogoutButtons();
  const session = requireAuth();
  if (!session) return;

  const form = document.getElementById('donate-form');
  const message = document.getElementById('donate-message');
  if (!form) return;

  form.addEventListener('submit', async (event) => {
    event.preventDefault();
    const amount = Number(form.amount.value);
    if (!amount || amount <= 0) {
      showMessage(message, 'Введіть коректну суму', 'warning');
      return;
    }
    showMessage(message, 'Обробка...', 'info');
    try {
      const response = await apiRequest('donate', { ...session, amount });
      setSession(session);
      showMessage(message, `Донат успішно зараховано. Новий баланс: ${response.user.donate}`, 'success');
      form.reset();
    } catch (error) {
      showMessage(message, error.message, 'danger');
    }
  });
}

async function initRoulettePage() {
  attachLogoutButtons();
  const session = requireAuth();
  if (!session) return;

  const status = document.getElementById('roulette-status');
  const balanceLabel = document.getElementById('roulette-balance');
  const spinButton = document.getElementById('spin-button');
  const resultBox = document.getElementById('roulette-result');

  async function refreshProfile() {
    try {
      const response = await apiRequest('profile', session);
      if (balanceLabel) balanceLabel.textContent = `${response.user.donate || 0}`;
      if (status) {
        status.textContent = 'Готово. Натисніть «Крутити рулетку». Тариф: 25.';
        status.className = 'alert alert-info';
      }
    } catch (error) {
      if (status) {
        status.textContent = error.message;
        status.className = 'alert alert-danger';
      }
    }
  }

  if (spinButton) {
    spinButton.addEventListener('click', async () => {
      if (resultBox) resultBox.innerHTML = '<div class="alert alert-info">Крутиться...</div>';
      try {
        const response = await apiRequest('roulette', session);
        if (balanceLabel) balanceLabel.textContent = `${response.balance}`;
        if (resultBox) {
          resultBox.innerHTML = `
            <div class="alert alert-success">
              <strong>Ура!</strong> Ви отримали: ${response.prize.i_name}<br>
              Категорія: ${response.prize.i_category || '-'}<br>
              Значення призу: ${response.prizeValue}
            </div>
          `;
        }
      } catch (error) {
        if (resultBox) resultBox.innerHTML = `<div class="alert alert-danger">${error.message}</div>`;
      }
    });
  }

  await refreshProfile();
}

(function init() {
  initLoginPage();
  initProfilePage();
  initDonatePage();
  initRoulettePage();
})();
