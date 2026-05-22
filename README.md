# ARR-Airlines

> A simple PHP/MySQL flight booking demo application built for local XAMPP development.

## Summary

ARR-Airlines is a lightweight booking demo that demonstrates a small booking flow using PHP, MySQL and Bootstrap. It includes pages for searching flights, signing up, logging in, booking, and viewing tickets.

## Features

- Flight search and booking flow
- User signup/login and session-based dashboard
- Admin panel (basic) for viewing bookings
- Simple procedural PHP codebase intended for learning and quick demos

## Requirements

- PHP 7.4+ (bundled with XAMPP)
- MySQL / MariaDB (bundled with XAMPP)
- Web browser

## Quick setup (local with XAMPP)

1. Copy the project to your XAMPP `htdocs` directory (already placed here).
2. Start Apache and MySQL from the XAMPP control panel.
3. Create the database and tables by visiting the browser: `http://localhost/ARR-Airlines/setup_db.php`.
4. Open `config.php` and confirm the database credentials (default: `root` / empty password).
5. Visit `http://localhost/ARR-Airlines` in your browser.

## Security notes

- `config.php` currently contains database credentials. For production move secrets to environment variables or a `.env` file and add it to `.gitignore`.
- Do not deploy this demo as-is to production without hardening (prepared statements, password hashing review, session security).

## Git / GitHub — commands to create the remote repo and push

If you have the GitHub CLI (`gh`) installed and authenticated, run from the project root:

```bash
git init
git add .
git commit -m "Initial commit: ARR-Airlines"
gh repo create ARR-Airlines --public --source=. --remote=origin --push
```

If you prefer to create the repository on GitHub manually, create a new public repo named `ARR-Airlines`, then run:

```bash
git init
git add .
git commit -m "Initial commit: ARR-Airlines"
git remote add origin https://github.com/<your-username>/ARR-Airlines.git
git branch -M main
git push -u origin main
```

## What I added to the repository here

- [README.md](README.md) — this file
- [.gitignore](.gitignore) — ignores common files
- [LICENSE](LICENSE) — MIT license

## Next steps I can do for you

- Initialize the local git repository and make the initial commit for you (requires terminal access).
- Use the GitHub API / `gh` to create the remote and push (you must authenticate or provide a PAT).
- Help remove credentials from `config.php` and migrate to an environment-based config.

---

If you'd like, I can run the git initialization and push for you — tell me whether you prefer using `gh` or a manual remote URL and confirm you have credentials available.
