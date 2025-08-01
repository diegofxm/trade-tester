const express = require('express');
const sqlite3 = require('sqlite3').verbose();
const path = require('path');

const fs = require('fs');
const path = require('path');


const app = express();
const PORT = 4000;

app.use(express.json());

const dbPath = path.join(__dirname, 'data', 'database.sqlite');
const db = new sqlite3.Database(dbPath, (err) => {
  if (err) console.error(err.message);
  else console.log('Conectado a SQLite');
});

// Crear tablas (puedes hacer esto en un script aparte)
db.serialize(() => {
  db.run(`CREATE TABLE IF NOT EXISTS accounts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    broker TEXT,
    platform TEXT,
    currency TEXT,
    leverage TEXT,
    initial_balance REAL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS trades (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id INTEGER NOT NULL,
    ticket INTEGER NOT NULL UNIQUE,
    symbol TEXT NOT NULL,
    type TEXT NOT NULL,
    volume REAL NOT NULL,
    open_price REAL,
    close_price REAL,
    sl REAL,
    tp REAL,
    open_time DATETIME,
    close_time DATETIME,
    profit REAL,
    status TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(account_id) REFERENCES accounts(id)
  )`);

  db.run(`CREATE TABLE IF NOT EXISTS daily_summaries (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    account_id INTEGER NOT NULL,
    date DATE NOT NULL,
    balance REAL,
    equity REAL,
    drawdown REAL,
    deposits REAL,
    withdrawals REAL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY(account_id) REFERENCES accounts(id)
  )`);
});

// ----------------------------
// Rutas

// 1. Cuentas
app.get('/api/accounts', (req, res) => {
  db.all('SELECT * FROM accounts', [], (err, rows) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(rows);
  });
});

app.post('/api/accounts', (req, res) => {
  const { name, broker, platform, currency, leverage, initial_balance } = req.body;
  const sql = `INSERT INTO accounts (name, broker, platform, currency, leverage, initial_balance) VALUES (?, ?, ?, ?, ?, ?)`;
  db.run(sql, [name, broker, platform, currency, leverage, initial_balance], function (err) {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ account_id: this.lastID });
  });
});

// 2. Trades
app.get('/api/trades', (req, res) => {
  db.all('SELECT * FROM trades', [], (err, rows) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(rows);
  });
});

app.post('/api/trades', (req, res) => {
  const { account_id, ticket, symbol, type, volume, open_price, close_price, sl, tp, open_time, close_time, profit, status } = req.body;
  const sql = `
    INSERT INTO trades 
    (account_id, ticket, symbol, type, volume, open_price, close_price, sl, tp, open_time, close_time, profit, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`;
  db.run(sql, [account_id, ticket, symbol, type, volume, open_price, close_price, sl, tp, open_time, close_time, profit, status], function (err) {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ trade_id: this.lastID });
  });
});

// 3. Resumen diario
app.get('/api/summaries', (req, res) => {
  db.all('SELECT * FROM daily_summaries', [], (err, rows) => {
    if (err) return res.status(500).json({ error: err.message });
    res.json(rows);
  });
});

app.post('/api/summaries', (req, res) => {
  const { account_id, date, balance, equity, drawdown, deposits, withdrawals } = req.body;
  const sql = `
    INSERT INTO daily_summaries (account_id, date, balance, equity, drawdown, deposits, withdrawals)
    VALUES (?, ?, ?, ?, ?, ?, ?)`;
  db.run(sql, [account_id, date, balance, equity, drawdown, deposits, withdrawals], function (err) {
    if (err) return res.status(500).json({ error: err.message });
    res.json({ summary_id: this.lastID });
  });
});

app.get('/delete-db', (req, res) => {
  try {
    if (fs.existsSync(dbPath)) {
      fs.unlinkSync(dbPath);
      res.send('Base de datos eliminada');
    } else {
      res.send('No existe la base de datos');
    }
  } catch (error) {
    res.status(500).send('Error eliminando la base de datos: ' + error.message);
  }
});

// ----------------------------

app.listen(PORT, () => {
  console.log(`API corriendo en http://localhost:${PORT}`);
});
