# 📝 Todo REST API — PHP MVC Backend

Eine modulare REST API in **PHP 8.2** mit MVC-Architektur, JWT-Authentifizierung und Soft Delete.  
Entwickelt als Lernprojekt im Rahmen der Ausbildung zum **Fachinformatiker Anwendungsentwicklung (IHK)**.

---

## 🚀 Features

- ✅ MVC-Architektur (Model, Controller, Core)
- ✅ REST API mit JSON-Responses
- ✅ JWT-Authentifizierung (HS256)
- ✅ User-Ownership — jeder User sieht nur seine eigenen Todos
- ✅ Soft Delete (`deleted_at` Pattern)
- ✅ Passwort-Hashing mit `password_hash()` (bcrypt)
- ✅ Umgebungsvariablen via `.env`
- ✅ Apache URL-Rewriting (Front Controller)

---
### Workflow

```
1. POST /auth/register  → User anlegen : body={Name ,Lastname ,user@mail.com, passowrd[klar-text]} ->DB
2. POST /auth/login     → Token erstellen : body={email,password} -> erstellt Token, private Route schutz()
3. POST /todos →    Authorization header: Bearer Token, body{title, description, status} ->DB
4. PUT /todos{id} → Authorization header: Bearer Token, body{title, description, status} ->DB
5. GET /todos →get Users Todos: per user Authorization header: Bearer Token
6. DELETE /todos{id} → Delete :per user Authorization header: Bearer Token

3. Alle anderen Requests → Token im Header setzen
```

## 🔧 Bekannte Einschränkungen / Roadmap

- [ ] User name in Respone # AuthController::Respon::json()
- [ ] `username` beim Register befüllen # automatisch erstellen
- [ ] `erstellt_am` → `created_at` in `users` vereinheitlichen
- [ ] Token-Blacklist für sofortigen Logout
- [ ] Notes-Modul (`/notes`)
- [ ] Rate Limiting
- [ ] Input-Sanitization Middleware



## 🗄️ Datenbankschema

```sql
-- todos
id          INT AUTO_INCREMENT PK
user_id     INT UNSIGNED NOT NULL (FK → users.id)
title       VARCHAR(255) NOT NULL
description TEXT
status      ENUM('open','done') DEFAULT 'open'
created_at  DATETIME
updated_at  DATETIME
deleted_at  DATETIME (Soft Delete)

-- users
id          INT UNSIGNED AUTO_INCREMENT PK
name        VARCHAR(50) NOT NULL
lastname    VARCHAR(50) NOT NULL
email       VARCHAR(100) UNIQUE NOT NULL
username    VARCHAR(50) DEFAULT NULL
password    VARCHAR(255) NOT NULL (bcrypt)
role        VARCHAR(20) DEFAULT 'user'
status      ENUM('active','block') DEFAULT 'active'
erstellt_am TIMESTAMP
updated_at  DATETIME
deleted_at  DATETIME (Soft Delete)
```

---



### HTTP Status Codes Übersicht

| Code | Bedeutung |
|---|---|
| `200` | OK — Anfrage erfolgreich |
| `201` | Created — Ressource erstellt |
| `401` | Unauthorized — Token fehlt, abgelaufen oder ungültig |
| `404` | Not Found — Ressource nicht gefunden |
| `422` | Unprocessable — Validierungsfehler |

---
**Fehler:**

| Code | Grund |
|---|---|
| `422` | Pflichtfeld fehlt |

---


| Code | Grund |
|---|---|
| `401` | E-Mail oder Passwort falsch |
| `422` | Pflichtfeld fehlt |

---


| Code | Grund |
|---|---|
| `404` | Todo nicht gefunden oder gehört anderem User |

---


| Code | Grund |
|---|---|
| `422` | `title` fehlt |

---



| Code | Grund |
|---|---|
| `404` | Todo nicht gefunden oder gehört anderem User |

---


| Code | Grund |
|---|---|
| `404` | Todo nicht gefunden oder gehört anderem User |

---

## 🧪 Testen mit Thunder Client / Postman

## 🔒 Sicherheit

| Maßnahme | Implementierung |
|---|---|
| Passwort-Hashing | `password_hash()` mit bcrypt |
| JWT Signierung | HS256 mit geheimem Schlüssel aus `.env` |
| SQL Injection | PDO Prepared Statements überall |
| User-Isolation | `user_id` aus JWT Token — nie aus Request Body |
| Soft Delete | Daten bleiben für Audit-Zwecke erhalten |
| Secrets | Nie im Repository — nur in `.env` |

---
## 🗂️ Projektstruktur

```
php_mvc_rest_sql/
├── index.php                        ← Front Controller (Einstiegspunkt)
├── .htaccess                        ← URL-Rewriting + Auth-Header Fix
├── .env                             ← Secrets (nicht im Repo!)
├── .env.example                     ← Vorlage für .env
├── composer.json
│
├── app/
│   ├── controllers/
│   │   ├── AuthController.php       ← register, login
│   │   └── TodoController.php       ← CRUD für Todos
│   ├── core/
│   │   ├── Database.php             ← PDO Singleton
│   │   ├── Response.php             ← JSON-Antworten
│   │   └── Router.php               ← URL-Routing + Middleware-Aufruf
│   ├── middleware/
│   │   └── AuthMiddleware.php       ← JWT prüfen
│   ├── models/
│   │   ├── Todo.php                 ← DB-Operationen für Todos
│   │   └── User.php                 ← DB-Operationen für User
│   └── helpers/
│       └── bootstrap.php            ← BASE_PATH Konstante
│
├── config/
│   └── database.example.php         ← DB-Konfigurationsvorlage
│
└── data/SQL/
    ├── migration/
    │   ├── 000_create_tables.sql
    │   ├── 001_create_table_users.sql
    │   └── 002_add_user_id_to_todos.sql
    └── seeder/
        └── 001_users_todos_test_daten.sql


---
## 🛠️ Voraussetzungen

| Tool | Version | Download |
|---|---|---|
| **PHP** | 8.2+ | [php.net](https://www.php.net) / XAMPP |
| **MySQL** | 8.0+ | [mysql.com](https://www.mysql.com) / XAMPP |
| **Apache** | 2.4+ | XAMPP / WAMP |
| **Composer** | aktuell | [getcomposer.org](https://getcomposer.org/Composer-Setup.exe) |

### PHP-Erweiterungen (müssen aktiv sein)

In `php.ini` diese Zeilen aktivieren (`;` am Anfang entfernen):

```ini
extension=zip
extension=pdo_mysql
extension=curl
extension=mbstring
```

---

## 📦 Pakete

| Paket | Version | Zweck |
|---|---|---|
| `vlucas/phpdotenv` | ^5.6 | `.env` Datei laden |
| `firebase/php-jwt` | ^7.0 | JWT Token erstellen & prüfen |

---

## ⚙️ Installation

### 1. Repository klonen

```

`.env` ausfüllen:

JWT Secret generieren:

```

### 4. Datenbank einrichten

Migrationen **in dieser Reihenfolge** in phpMyAdmin oder MySQL CLI ausführen:

```bash
# 1. Todos-Tabelle erstellen
data/SQL/migration/000_create_tables.sql

# 2. Users-Tabelle erstellen
data/SQL/migration/001_create_table_users.sql

# 3. user_id zu todos hinzufügen
data/SQL/migration/002_add_user_id_to_todos.sql

# Optional: Testdaten einspielen
data/SQL/seeder/001_users_todos_test_daten.sql
```

### 5. Apache konfigurieren (XAMPP)

Projekt in XAMPP `htdocs` ablegen:
```
C:/xampp/htdocs/fag57/phpd/php_mvc_rest/php_mvc_rest_sql/
```

Sicherstellen dass `mod_rewrite` aktiv ist — in `httpd.conf`:
```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

Und `AllowOverride All` für das `htdocs` Verzeichnis gesetzt ist.

---


```

---

## 🌐 Frontend

PHP Frontend: [todo-frontend-php](https://github.com/Ahmadizaldeen/todo-frontend-php) *(coming soon)*  
React Frontend: geplant

---

## 👨‍💻 Autor

**Ahmad Izaldeen**  
Fachinformatiker Anwendungsentwicklung (Umschulung)  
[github.com/Ahmadizaldeen](https://github.com/Ahmadizaldeen)

---

## 📄 Lizenz

MIT