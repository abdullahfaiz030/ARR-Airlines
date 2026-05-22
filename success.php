<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Payment Successful - ARR Airlines</title>
    <style>
        /* RESET & BASE */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: url('airplane-background.jpg') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* HEADER STYLES */
        header {
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 2px solid #00c6ff;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #00c6ff;
            letter-spacing: 2px;
        }

        .nav-links {
            display: flex;
            list-style: none;
        }

        .nav-links li {
            margin-left: 2rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #00c6ff;
            border-bottom: 2px solid #00c6ff;
            padding-bottom: 5px;
        }

        /* AUTH SECTION */
        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 80vh;
            padding: 2rem;
            background-color: rgba(0, 0, 0, 0.85);
        }

        .auth-form {
            background-color: #000;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.1);
            width: 100%;
            max-width: 600px;
            border: 1px solid #00c6ff;
        }

        .auth-form h1 {
            color: #00c6ff;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .auth-form p {
            color: white;
            text-align: center;
            font-size: 1.1rem;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
        }

        .btn {
            background-color: transparent;
            color: white;
            padding: 1rem 3rem;
            border-radius: 30px;
            text-decoration: none;
            font-weight: bold;
            transition: all 0.3s;
            border: 2px solid #00c6ff;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 2rem;
            display: inline-block;
        }

        .btn:hover {
            background-color: #00c6ff;
            color: #000;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        /* FOOTER */
        footer {
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 1.5rem;
            text-align: center;
            margin-top: auto;
            border-top: 1px solid #00c6ff;
        }

        footer p {
            color: #00c6ff;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* UTILITIES */
        .hidden {
            display: none;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .auth-form {
                padding: 2rem;
            }

            .auth-form h1 {
                font-size: 1.8rem;
            }

            .nav-links li {
                margin-left: 1rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <div class="logo">ARR AIRLINES</div>
            <ul class="nav-links">
                <li><a href="index.php" class="home-link active">HOME</a></li>
                <li><a href="#">ABOUT</a></li>
                <li><a href="#">CONTACT</a></li>
                <li><a href="#" class="login-link">LOGIN</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="success-content">
            <div class="auth-container">
                <div class="auth-form">
                    <h1>✅ Payment Successful!</h1>
                    <p>Thank you for booking with us.<br>Your tickets have been confirmed.</p>
                    <a href="step1.php" class="btn">Book Another Ticket</a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 ARR AIRLINES | ALL RIGHTS RESERVED</p>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const home = document.getElementById("home-content");
            const login = document.getElementById("login-content");
            const signup = document.getElementById("signup-content");
            const about = document.getElementById("about-content");
            const contact = document.getElementById("contact-content");

            function hideAllSections() {
                if (home) home.classList.add("hidden");
                if (login) login.classList.add("hidden");
                if (signup) signup.classList.add("hidden");
                if (about) about.classList.add("hidden");
                if (contact) contact.classList.add("hidden");
            }

            document.querySelectorAll(".login-link").forEach(link => {
                link.addEventListener("click", e => {
                    e.preventDefault();
                    hideAllSections();
                    if (login) login.classList.remove("hidden");
                });
            });

            document.querySelectorAll(".home-link").forEach(link => {
                link.addEventListener("click", e => {
                    e.preventDefault();
                    hideAllSections();
                    if (home) home.classList.remove("hidden");
                });
            });

            document.querySelectorAll("a").forEach(link => {
                link.addEventListener("click", e => {
                    if (link.textContent === "ABOUT") {
                        e.preventDefault();
                        hideAllSections();
                        if (about) about.classList.remove("hidden");
                    } else if (link.textContent === "CONTACT") {
                        e.preventDefault();
                        hideAllSections();
                        if (contact) contact.classList.remove("hidden");
                    }
                });
            });
        });
    </script>
</body>
</html>
