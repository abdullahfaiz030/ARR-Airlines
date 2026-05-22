<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: steps2.php");
    exit;
}

// Store passenger data in session
$_SESSION['passenger_data'] = $_POST;

// Retrieve route and passenger count from session
$from = $_SESSION['steps3']['from'] ?? '';
$to = $_SESSION['steps3']['to'] ?? '';
$ps = $_SESSION['steps3']['ps'] ?? 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Secure Payment - ARR Airlines</title>
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

        /* FORM STYLES */
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
            max-width: 500px;
            border: 1px solid #00c6ff;
        }

        .auth-form h2 {
            color: #00c6ff;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .auth-form label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #00c6ff;
            letter-spacing: 1px;
        }

        .auth-form input,
        .auth-form select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #00c6ff;
            border-radius: 4px;
            font-size: 1rem;
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            margin-bottom: 1.5rem;
        }

        .auth-form button {
            width: 100%;
            padding: 1rem;
            background-color: #00c6ff;
            color: #000;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin-top: 1rem;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .auth-form button:hover {
            background-color: #000;
            color: #00c6ff;
            border: 1px solid #00c6ff;
        }

        .card-logos {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .card-icon {
            width: 200px;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(255, 215, 0, 0.2);
        }

        .note {
            font-size: 0.9rem;
            color: #00c6ff;
            margin-top: 1.5rem;
            text-align: center;
            letter-spacing: 1px;
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

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .auth-form {
                padding: 2rem;
            }

            .auth-form h2 {
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
                <li><a href="index.php" class="home-link">HOME</a></li>
                <li><a href="#">ABOUT</a></li>
                <li><a href="#">CONTACT</a></li>
                <li><a href="#" class="login-link">LOGIN</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="payment-content">
            <div class="auth-container">
                <div class="auth-form">
                    <h2>Secure Payment</h2>
                    <form method="post" action="final.php">
                        <div class="card-logos">
                            <img src="logo.png" alt="Accepted Cards" class="card-icon">
                        </div>
                        <label>Cardholder Name</label>
                        <input type="text" name="card_name" required>
                        <label>Card Number</label>
                        <input type="text" name="card_number" maxlength="19" placeholder="0000-0000-0000-0000"
                               oninput="formatCardNumber(this)" required>
                        <label>Expiry Date</label>
                        <input type="month" name="expiry" required>
                        <label>CVV</label>
                        <input type="text" name="cvv" maxlength="4" required pattern="\d{3,4}" placeholder="3 or 4 digits">
                        <input type="hidden" name="from" value="<?php echo htmlspecialchars($from); ?>">
                        <input type="hidden" name="to" value="<?php echo htmlspecialchars($to); ?>">
                        <input type="hidden" name="passengers" value="<?php echo htmlspecialchars($ps); ?>">
                        <button type="submit">Pay & Confirm Booking</button>
                        <div class="note">
                            Your payment is secure and encrypted.
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 ARR AIRLINES | ALL RIGHTS RESERVED</p>
    </footer>

    <script>
        function formatCardNumber(input) {
            input.value = input.value
                .replace(/\D/g, '')
                .replace(/(.{4})/g, '$1-')
                .replace(/-$/, '')
                .substring(0, 19);
        }

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
