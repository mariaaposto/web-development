# Web Development 💻

Web applications I built during my Web Application Technologies course, using HTML, PHP, MySQL and a little JavaScript.

I'm heading into marketing and business rather than development, but knowing how a web app and its data actually fit together is a real advantage — so here's the proof I can hold my own on the technical side.

*The interface text and code comments are in Greek; the explanations below are in English.*

---

### semester-project — login + database app

The most complete one: a small multi-page app over a MySQL "books" database (Authors and Books).

- **Login** with hashed passwords (`password_verify`) and brute-force protection — three wrong tries and you're locked out for 30 seconds.
- **Session-based access**, so protected pages send you back to login if you're not signed in.
- A **menu** to pick an action and a table, a **search** page using `LIKE` with prepared statements, and an **insert** page with parameterized queries, plus a clean **logout**.

Built with PHP, MySQL (mysqli), HTML and client-side JavaScript validation.
📂 Code in [`semester-project/src/`](semester-project/src) · full report in `semester-project/report.pdf`

> A note I'd rather be honest about: most queries use prepared statements, but one `UPDATE` in `login.php` still drops the username straight into the SQL. In a real app I'd parameterize that too and not connect as a privileged database user. I'm leaving it visible on purpose — I know the difference, and that matters more than hiding it.

---

### php-login-assignment — dynamic login page

A single, self-contained `login.php` that handles all its logic server-side and renders cleanly.

It offers three selectable password-checking methods, limits you to three attempts with a live "attempts remaining" counter, shows a different message for each outcome (empty fields, wrong password, final failure, success), and has a little "Start again" reset and a show/hide-password toggle.

Built with PHP sessions, HTML and JavaScript.
📂 Code in [`php-login-assignment/src/`](php-login-assignment/src) · full report in `php-login-assignment/report.pdf`

---

### html-form — HTML5 form

A hand-built "Photo Details" form that uses a good spread of HTML5 inputs: text, textarea, select, datalist, radios, checkboxes, number, colour, date and time, all tidied up with `fieldset` and `legend`.

📂 [`html-form/photo-details-form.html`](html-form/photo-details-form.html) · brief in `html-form/report.pdf`

---

**Running the PHP projects locally**

1. Install a PHP + MySQL stack like XAMPP or MAMP.
2. Create the database and tables described in the project report.
3. Drop the `src/` files into your web root (e.g. `htdocs/`).
4. Open `login.html` (or `login.php`) in the browser.
