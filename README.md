# 🛡️ Cybersecurity Awareness and Learning Portal
**Web Technology Laboratory (WTL) Project**

A complete, beginner-friendly cybersecurity awareness portal paired with a college Web Technology Laboratory implementation structured strictly around the **15 official syllabus practicals**.

---

## 🌐 Public Portal Pages
The portal is designed as a modern cybersecurity educational hub for beginners with a dark navy (`#090e1a`), cyan (`#06b6d4`), and emerald (`#10b981`) theme:
1. **Home (`index.html`)**: "Stay Safe in a Digital World" hero section, core pillars of security (CIA Triad), quick safety tips, featured threat overview cards, and quiz CTA.
2. **Cyber Threats (`threats.html`)**: In-depth explanations of Phishing, Malware/Spyware, Ransomware, Password Attacks, Social Engineering, and DDoS, complete with warning signs and defense tips.
3. **Learn (`learn.html`)**: Interactive "Spot the Phishing Red Flags" email simulator and the Digital Citizen Cyber Safety Pledge.
4. **Safety Tips (`safety-tips.html`)**: Actionable security checklists for Accounts, Browsing/Wi-Fi, and Devices, plus asynchronous daily advisories loaded from `data/tips.json`.
5. **Resources (`resources.html`)**: Real-time Password Strength & Entropy meter, interactive FAQ accordions, external emergency response hotlines, and security glossary.
6. **Quiz (`quiz.html`)**: Interactive multiple-choice knowledge assessment with immediate feedback, detailed explanations, final scoring, and restart option.
7. **Examiner Hub (`practicals.html`)**: Discreetly linked via `🧪 WTL Practicals` in the site footer for practical examiners and viva assessment, containing full syllabus mapping and viva questions.

---

## 📋 Exact 15 Syllabus Practicals Mapping

| # | Official WTL Syllabus Practical | Implementation File(s) | Key Concepts Demonstrated |
| :-: | :--- | :--- | :--- |
| **01** | **HTML headings, lists and images.** | `index.html` | Heading tags (`<h1>`-`<h6>`), Ordered (`<ol>`), Unordered (`<ul>`), Definition (`<dl>`), Vector image (`<img>` with attributes). |
| **02** | **Semantic HTML.** | `learn.html`, `pledge.html` | Semantic landmarks: `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<figure>`, `<figcaption>`, `<footer>`. |
| **03** | **Inline, internal and external CSS.** | `css-types.html`, `css/style.css` | External CSS via `<link>`, Internal CSS via `<style>`, Inline CSS via `style="..."`, and cascading precedence hierarchy. |
| **04** | **Responsive design using Flexbox, Grid and media queries.** | `threats.html`, `css/responsive.css` | 1D Flexbox navigation, 2D CSS Grid auto-fitting cards (`repeat(auto-fit, minmax(300px, 1fr))`), and media query breakpoints. |
| **05** | **CSS positioning and properties.** | `css-positioning.html` | Visual demonstrations of `static`, `relative`, `absolute`, `fixed`, `sticky`, `z-index` layering, `float`, `clear`, and `overflow`. |
| **06** | **JavaScript file linking, events and array functions.** | `js-arrays.html`, `js/array-methods.js` | `<script src="...">`, DOM events (`click`), functional methods: `.map()`, `.filter()`, `.reduce()`, `.find()`, `.some()`, `.every()`, `.forEach()`. |
| **07** | **JavaScript frontend functionality and form validation.** | `validation-demo.html`, `js/validation.js` | Regex email (`/^[^\s@]+@[^\s@]+\.[^\s@]+$/`), 10-digit mobile, `event.preventDefault()`, inline error rendering, token generation. |
| **08** | **React SPA using functional components and JSX.** | `react-app/index.html` | Single Page Application (SPA) architecture, functional components (`Header`, `ScoreMeter`, `ChecklistItem`), JSX in-browser via Babel. |
| **09** | **React props, state, hooks, event handling and conditional rendering.** | `react-app/index.html` | `useState` state management, `useEffect` lifecycle hook with side-effects & cleanup, synthetic events, conditional rendering. |
| **10** | **Fetch API and JSON data.** | `safety-tips.html`, `fetch-api.html`, `data/tips.json` | `fetch('data/tips.json')`, Promises, `async/await`, JSON parsing, dynamic DOM injection, random tip selector, error handling. |
| **11** | **DOM manipulation and event handling.** | `resources.html`, `dom-manipulation.html`, `js/dom-events.js` | Password strength checker (`input` event), accordion toggle (`click` event), dynamic element creation (`createElement`, `appendChild`, `remove`). |
| **12** | **PHP form handling, validation, string manipulation and session management.** | `backend-php/practical12_session_strings.php` | `session_start()`, `$_SESSION`, `session_destroy()`, form validation, `strlen()`, `strtoupper()`, `strtolower()`, `substr()`, `str_replace()`, `hash()`. |
| **13** | **PHP-MySQL connectivity and complete CRUD operations.** | `backend-php/crud_dashboard.php`, `crud_update.php`, `crud_delete.php` | Complete CRUD: **C**REATE (INSERT), **R**EAD (SELECT), **U**PDATE (UPDATE), **D**ELETE (DELETE) using PHP PDO prepared statements. |
| **14** | **Node.js and Express server, routing and static resources.** | `backend-node/server.js`, `server-basic.js` | Express app initialization, static file serving via `express.static()`, route handlers (`/`, `/api-routes`), custom logging middleware (`next()`). |
| **15** | **REST API using Node.js, Express and database connectivity.** | `integration.html`, `backend-node/routes/api.js`, `backend-node/db.js` | Live database-connected REST API (MySQL): `GET /api/incidents`, `POST /api/incidents` (201), `PUT /api/incidents/:id`, `DELETE /api/incidents/:id`. |

---

## 💻 Step-by-Step Instructions to Run on Windows

### 1. Frontend & Client-Side Practicals (Practicals 01 to 07, 10, 11)
- Double-click `index.html` in Windows Explorer to open in Google Chrome or Microsoft Edge.
- Open `practicals.html` to navigate the master syllabus index and test any practical.

### 2. Standalone React Single Page Application (Practicals 08 & 09)
- Double-click `react-app/index.html`.
- Loads React 18 and Babel directly via CDN—no Node build process or compilation required!

### 3. PHP & MySQL Practicals via XAMPP (Practicals 12 & 13)
1. Open the **XAMPP Control Panel** on Windows and click **Start** for **Apache** and **MySQL**.
2. **Import Database**:
   - Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Click the **Import** tab at the top.
   - Choose `backend-php/db.sql` and click **Import** (creates database `cyber_portal_db` and table `incident_reports`).
3. **Run PHP**:
   - Run via PHP's built-in CLI server:
     ```powershell
     cd backend-php
     php -S localhost:8000
     ```
   - Open: [http://localhost:8000/practical12_session_strings.php](http://localhost:8000/practical12_session_strings.php) (Practical 12: Sessions & Strings)
   - Open: [http://localhost:8000/crud_dashboard.php](http://localhost:8000/crud_dashboard.php) (Practical 13: Complete CRUD)

### 4. Node.js & Express REST API with MySQL (Practicals 14 & 15)
Open PowerShell in the project directory:
```powershell
# Navigate to backend directory
cd backend-node

# To test Practical 14 & Practical 15 (Express Server & Database-Connected REST API on Port 5000):
node server.js
```
- Open [http://localhost:5000](http://localhost:5000) to access the portal served via Express static resources (Practical 14).
- Open [http://localhost:5000/integration.html](http://localhost:5000/integration.html) to interact with the database-connected REST API console (Practical 15).
- Test REST Endpoints directly in your browser or Postman:
  - `GET http://localhost:5000/api/incidents`
  - `GET http://localhost:5000/api/db-status`
  - `POST http://localhost:5000/api/incidents` (JSON body)
  - `PUT http://localhost:5000/api/incidents/1` (JSON body)
  - `DELETE http://localhost:5000/api/incidents/1`

---

## 🧪 Verification & Testing Commands

To verify Node.js and PHP syntax anytime:
```powershell
# Check Node.js files
cd backend-node
node -c server.js
node -c routes/api.js
node -c db.js

# Check PHP files
cd ..\backend-php
php -l db_connect.php
php -l practical12_session_strings.php
php -l crud_dashboard.php
php -l crud_update.php
php -l crud_delete.php
```
