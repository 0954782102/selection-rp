const { createJsonResponse, getUserByNickname, updateUser, getItems, logDonation, hashPassword, sanitizeUser, ROULETTE_COST } = require('./db');

exports.handler = async (event) => {
  if (event.httpMethod === 'OPTIONS') {
    return createJsonResponse({ message: 'OK' }, 200);
  }

  let body = {};
  try {
    body = event.body ? JSON.parse(event.body) : {};
  } catch (error) {
    return createJsonResponse({ error: 'Invalid JSON body' }, 400);
  }

  const action = (event.queryStringParameters && event.queryStringParameters.action) || body.action;
  if (!action) return createJsonResponse({ error: 'Missing action' }, 400);

  try {
    switch (action) {
      case 'login':
        return await handleLogin(body);
      case 'profile':
        return await handleProfile(body);
      case 'donate':
        return await handleDonate(body);
      case 'roulette':
        return await handleRoulette(body);
      case 'items':
        return await handleItems();
      default:
        return createJsonResponse({ error: `Unknown action ${action}` }, 404);
    }
  } catch (error) {
    console.error(error);
    return createJsonResponse({ error: error.message || 'Server error' }, 500);
  }
};

async function handleLogin(body) {
  const nickname = String(body.nickname || '').trim();
  const password = String(body.password || '').trim();
  if (!nickname || !password) {
    return createJsonResponse({ error: 'Нікнейм і пароль обов’язкові' }, 400);
  }

  const user = await getUserByNickname(nickname);
  if (!user) {
    return createJsonResponse({ error: 'Користувача не знайдено' }, 404);
  }

  if (user.password !== hashPassword(password)) {
    return createJsonResponse({ error: 'Невірний пароль' }, 401);
  }

  return createJsonResponse({ user: sanitizeUser(user) }, 200);
}

async function handleProfile(body) {
  const nickname = String(body.nickname || '').trim();
  const password = String(body.password || '').trim();
  const user = await getUserByNickname(nickname);
  if (!user || user.password !== hashPassword(password)) {
    return createJsonResponse({ error: 'Неавторизовано' }, 401);
  }
  return createJsonResponse({ user: sanitizeUser(user) }, 200);
}

async function handleDonate(body) {
  const nickname = String(body.nickname || '').trim();
  const password = String(body.password || '').trim();
  const amount = Number(body.amount);
  if (!nickname || !password) return createJsonResponse({ error: 'Неавторизовано' }, 401);
  if (!amount || amount <= 0) return createJsonResponse({ error: 'Вкажіть коректну суму' }, 400);

  const user = await getUserByNickname(nickname);
  if (!user || user.password !== hashPassword(password)) {
    return createJsonResponse({ error: 'Неавторизовано' }, 401);
  }

  const newBalance = (Number(user.balance) || 0) + amount;
  const donateBalance = (Number(user.donate_balance) || 0) + amount;

  const updated = await updateUser(nickname, { balance: newBalance, donate_balance: donateBalance });
  await logDonation(nickname, amount);

  return createJsonResponse({ message: 'Пожертвування успішне', user: sanitizeUser(updated) }, 200);
}

async function handleRoulette(body) {
  const nickname = String(body.nickname || '').trim();
  const password = String(body.password || '').trim();
  if (!nickname || !password) return createJsonResponse({ error: 'Неавторизовано' }, 401);

  const user = await getUserByNickname(nickname);
  if (!user || user.password !== hashPassword(password)) {
    return createJsonResponse({ error: 'Неавторизовано' }, 401);
  }

  if (Number(user.balance) < ROULETTE_COST) {
    return createJsonResponse({ error: 'Недостатньо балансу для рулетки' }, 402);
  }

  const items = await getItems();
  if (!items.length) {
    return createJsonResponse({ error: 'Немає доступних предметів рулетки' }, 404);
  }

  const totalWeight = items.reduce((sum, item) => sum + Number(item.weight || 1), 0);
  let random = Math.random() * totalWeight;
  let selected = items[0];
  for (const item of items) {
    random -= Number(item.weight || 1);
    if (random <= 0) {
      selected = item;
      break;
    }
  }

  const prizeValue = Number(selected.min_value || 0) + Math.floor(Math.random() * ((Number(selected.max_value || selected.min_value || 0) - Number(selected.min_value || 0)) + 1));
  const updatedBalance = Number(user.balance) - ROULETTE_COST;
  const updated = await updateUser(nickname, { balance: updatedBalance });

  return createJsonResponse({ prize: selected, prizeValue, balance: updatedBalance, cost: ROULETTE_COST }, 200);
}

async function handleItems() {
  const items = await getItems();
  return createJsonResponse({ items }, 200);
}
