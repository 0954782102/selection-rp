const { query, createJsonResponse } = require('./db');

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
      case 'change_password':
        return await handleChangePassword(body);
      default:
        return createJsonResponse({ error: `Unknown action ${action}` }, 404);
    }
  } catch (error) {
    console.error('API Error:', error);
    return createJsonResponse({ error: error.message || 'Server error' }, 500);
  }
};

async function handleLogin(body) {
  const nickname = String(body.nickname || '').trim();
  const password = String(body.password || '').trim();
  if (!nickname || !password) {
    return createJsonResponse({ error: 'Нікнейм і пароль обов\'язкові' }, 400);
  }

  try {
    const rows = await query('SELECT * FROM `accounts` WHERE `nickname` = ? LIMIT 1', [nickname]);
    if (rows.length === 0) {
      return createJsonResponse({ error: 'Користувача не знайдено' }, 404);
    }

    const user = rows[0];
    if (user.password !== password) {
      return createJsonResponse({ error: 'Невірний пароль' }, 401);
    }

    return createJsonResponse({ user: sanitizeUser(user) }, 200);
  } catch (error) {
    return createJsonResponse({ error: error.message }, 500);
  }
}

async function handleProfile(body) {
  const nickname = String(body.nickname || '').trim();
  const password = String(body.password || '').trim();
  
  if (!nickname || !password) {
    return createJsonResponse({ error: 'Неавторизовано' }, 401);
  }

  try {
    const rows = await query('SELECT * FROM `accounts` WHERE `nickname` = ? LIMIT 1', [nickname]);
    if (rows.length === 0 || rows[0].password !== password) {
      return createJsonResponse({ error: 'Неавторизовано' }, 401);
    }
    return createJsonResponse({ user: sanitizeUser(rows[0]) }, 200);
  } catch (error) {
    return createJsonResponse({ error: error.message }, 500);
  }
}

async function handleChangePassword(body) {
  const nickname = String(body.nickname || '').trim();
  const oldPassword = String(body.old_password || '').trim();
  const newPassword = String(body.new_password || '').trim();

  if (!nickname || !oldPassword || !newPassword) {
    return createJsonResponse({ error: 'Всі поля обов\'язкові' }, 400);
  }

  try {
    const rows = await query('SELECT * FROM `accounts` WHERE `nickname` = ? LIMIT 1', [nickname]);
    if (rows.length === 0 || rows[0].password !== oldPassword) {
      return createJsonResponse({ error: 'Невірний пароль' }, 401);
    }

    await query('UPDATE `accounts` SET `password` = ? WHERE `nickname` = ?', [newPassword, nickname]);
    return createJsonResponse({ message: 'Пароль успішно змінено' }, 200);
  } catch (error) {
    return createJsonResponse({ error: error.message }, 500);
  }
}

async function handleDonate(body) {
  const nickname = String(body.nickname || '').trim();
  const password = String(body.password || '').trim();
  const amount = Number(body.amount);

  if (!nickname || !password) return createJsonResponse({ error: 'Неавторизовано' }, 401);
  if (!amount || amount <= 0) return createJsonResponse({ error: 'Вкажіть коректну суму' }, 400);

  try {
    const rows = await query('SELECT * FROM `accounts` WHERE `nickname` = ? LIMIT 1', [nickname]);
    if (rows.length === 0 || rows[0].password !== password) {
      return createJsonResponse({ error: 'Неавторизовано' }, 401);
    }

    const user = rows[0];
    const newBalance = (Number(user.donate) || 0) + amount;

    await query('UPDATE `accounts` SET `donate` = ? WHERE `nickname` = ?', [newBalance, nickname]);
    
    const updated = await query('SELECT * FROM `accounts` WHERE `nickname` = ? LIMIT 1', [nickname]);
    return createJsonResponse({ message: 'Пожертвування успішне', user: sanitizeUser(updated[0]) }, 200);
  } catch (error) {
    return createJsonResponse({ error: error.message }, 500);
  }
}

async function handleRoulette(body) {
  const nickname = String(body.nickname || '').trim();
  const password = String(body.password || '').trim();

  if (!nickname || !password) return createJsonResponse({ error: 'Неавторизовано' }, 401);

  try {
    const rows = await query('SELECT * FROM `accounts` WHERE `nickname` = ? LIMIT 1', [nickname]);
    if (rows.length === 0 || rows[0].password !== password) {
      return createJsonResponse({ error: 'Неавторизовано' }, 401);
    }

    const user = rows[0];
    const ROULETTE_COST = 25;

    if (Number(user.donate) < ROULETTE_COST) {
      return createJsonResponse({ error: 'Недостатньо балансу для рулетки' }, 402);
    }

    const items = await query('SELECT * FROM `ucp_item_roulette`');
    if (items.length === 0) {
      return createJsonResponse({ error: 'Немає доступних предметів рулетки' }, 404);
    }

    const totalWeight = items.reduce((sum, item) => sum + Number(item.i_change || 1), 0);
    let random = Math.random() * totalWeight;
    let selected = items[0];
    for (const item of items) {
      random -= Number(item.i_change || 1);
      if (random <= 0) {
        selected = item;
        break;
      }
    }

    const prizeValue = Number(selected.i_start_rand || 0) + Math.floor(Math.random() * ((Number(selected.i_end_rand || selected.i_start_rand || 0) - Number(selected.i_start_rand || 0)) + 1));
    const updatedBalance = Number(user.donate) - ROULETTE_COST;

    await query('UPDATE `accounts` SET `donate` = ? WHERE `nickname` = ?', [updatedBalance, nickname]);
    
    const dataNow = new Date().toISOString().slice(0, 19).replace('T', ' ');
    await query('INSERT INTO `ucp_drop_roulette` (`p_user`, `p_data`, `p_value`, `p_id`, `p_status`) VALUES (?, ?, ?, ?, ?)', 
      [nickname, dataNow, prizeValue, selected.id, 0]);

    return createJsonResponse({ 
      prize: selected, 
      prizeValue, 
      balance: updatedBalance, 
      cost: ROULETTE_COST 
    }, 200);
  } catch (error) {
    return createJsonResponse({ error: error.message }, 500);
  }
}

async function handleItems() {
  try {
    const items = await query('SELECT * FROM `ucp_item_roulette`');
    return createJsonResponse({ items }, 200);
  } catch (error) {
    return createJsonResponse({ error: error.message }, 500);
  }
}

function sanitizeUser(user) {
  if (!user) return null;
  const { password, ...rest } = user;
  return rest;
}
