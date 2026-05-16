const crypto = require('crypto');

const SUPABASE_URL = process.env.SUPABASE_URL;
const SUPABASE_KEY = process.env.SUPABASE_KEY;
const USERS_TABLE = process.env.SUPABASE_TABLE_USERS || 'profiles';
const ITEMS_TABLE = process.env.SUPABASE_TABLE_ITEMS || 'roulette_items';
const DONATIONS_TABLE = process.env.SUPABASE_TABLE_DONATIONS || 'donations';
const PASSWORD_HASH = process.env.PASSWORD_HASH || 'plain';
const ROULETTE_COST = Number(process.env.ROULETTE_COST || '25');

const hasExternalDb = Boolean(SUPABASE_URL && SUPABASE_KEY);

const defaultHeaders = {
  apikey: SUPABASE_KEY,
  Authorization: `Bearer ${SUPABASE_KEY}`,
  'Content-Type': 'application/json',
  Prefer: 'return=representation',
};

const fallbackUsers = [
  {
    nickname: 'demo',
    password: 'demo123',
    balance: 250,
    cash: 1200,
    level: 7,
    phone: '+380501234567',
    bank: 10000,
    online: false,
    last_login: '2026-05-16 12:00',
    last_ip: '127.0.0.1',
    donate_balance: 250,
    gender: 'Чоловіча',
    skills: { m4: 72, ak47: 65, pistol: 80, sdPistol: 43, deagle: 58, shotgun: 40, mp5: 51, sniper: 35 }
  }
];

const fallbackItems = [
  { id: 1, name: 'Buffalo', image_url: '/assets/images/buffalo.png', weight: 4, min_value: 1000, max_value: 4000, category: 'Авто' },
  { id: 2, name: 'EXP', image_url: '/assets/images/exp.png', weight: 8, min_value: 100, max_value: 350, category: 'Ресурси' },
  { id: 3, name: 'Донат', image_url: '/assets/images/donate.png', weight: 5, min_value: 50, max_value: 150, category: 'Баланс' },
  { id: 4, name: 'Bullet', image_url: '/assets/images/bullet.png', weight: 3, min_value: 5000, max_value: 9000, category: 'Авто' }
];

let fallbackDonations = [];

function hashPassword(password) {
  if (PASSWORD_HASH === 'sha256') {
    return crypto.createHash('sha256').update(password).digest('hex');
  }
  return password;
}

function createJsonResponse(body, status = 200) {
  return {
    statusCode: status,
    headers: {
      'Content-Type': 'application/json',
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'GET,POST,OPTIONS',
      'Access-Control-Allow-Headers': 'Content-Type',
    },
    body: JSON.stringify(body),
  };
}

async function supabaseFetch(path, init = {}) {
  const url = `${SUPABASE_URL}/rest/v1/${path}`;
  const response = await fetch(url, {
    ...init,
    headers: { ...defaultHeaders, ...(init.headers || {}) },
  });
  const text = await response.text();
  let data = null;
  try {
    data = JSON.parse(text);
  } catch {
    data = text;
  }
  if (!response.ok) {
    throw new Error(`Supabase error ${response.status}: ${JSON.stringify(data)}`);
  }
  return data;
}

async function getUserByNickname(nickname) {
  if (!nickname) return null;
  if (hasExternalDb) {
    const rows = await supabaseFetch(`${USERS_TABLE}?nickname=eq.${encodeURIComponent(nickname)}&select=*`);
    return Array.isArray(rows) ? rows[0] : null;
  }
  return fallbackUsers.find((user) => user.nickname.toLowerCase() === nickname.toLowerCase()) || null;
}

async function updateUser(nickname, data) {
  if (!nickname || !data) return null;
  if (hasExternalDb) {
    const body = JSON.stringify(data);
    const rows = await supabaseFetch(`${USERS_TABLE}?nickname=eq.${encodeURIComponent(nickname)}`, {
      method: 'PATCH',
      body,
    });
    return Array.isArray(rows) ? rows[0] : null;
  }
  const user = await getUserByNickname(nickname);
  if (!user) return null;
  Object.assign(user, data);
  return user;
}

async function getItems() {
  if (hasExternalDb) {
    const rows = await supabaseFetch(`${ITEMS_TABLE}?select=*`);
    return Array.isArray(rows) ? rows : [];
  }
  return fallbackItems;
}

async function logDonation(nickname, amount) {
  if (!nickname || !amount) return;
  if (hasExternalDb) {
    await supabaseFetch(DONATIONS_TABLE, {
      method: 'POST',
      body: JSON.stringify({ nickname, amount, created_at: new Date().toISOString() }),
    });
    return;
  }
  fallbackDonations.push({ id: fallbackDonations.length + 1, nickname, amount, created_at: new Date().toISOString() });
}

function sanitizeUser(user) {
  if (!user) return null;
  const { password, ...rest } = user;
  return rest;
}

module.exports = {
  createJsonResponse,
  getUserByNickname,
  updateUser,
  getItems,
  logDonation,
  hashPassword,
  sanitizeUser,
  ROULETTE_COST,
};
