<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST["from"];
    $to = $_POST["to"];
    $ps = (int)$_POST['passengers'];

    // Basic validation
    if (empty($from) || empty($to) || $ps <= 0) {
        $error_message = "Please fill all required fields.";
    } elseif (!isset($_SESSION['passenger_data'])) {
        $error_message = "Passenger data not found. Please go back and re-enter.";
    } else {
        // Connect to database
        $con = mysqli_connect("localhost", "root", "", "project");

        if (!$con) {
            $error_message = "Database connection failed: " . mysqli_connect_error();
        } else {
            // Insert each passenger
            $passenger_data = $_SESSION['passenger_data'];
            for ($i = 1; $i <= $ps; $i++) {
                $name = mysqli_real_escape_string($con, $passenger_data["text$i"]);
                $dob = mysqli_real_escape_string($con, $passenger_data["dob_$i"]);
                $address = mysqli_real_escape_string($con, $passenger_data["address_$i"]);
                $gender = mysqli_real_escape_string($con, $passenger_data["gender_$i"]);
                $contact = mysqli_real_escape_string($con, $passenger_data["contact_$i"]);
                $passport = mysqli_real_escape_string($con, $passenger_data["passport_$i"]);

                $sql = "INSERT INTO ticket_booking 
                        (from_location, to_location, name, dob, address, gender, contact, passport) 
                        VALUES 
                        ('$from', '$to', '$name', '$dob', '$address', '$gender', '$contact', '$passport')";

                if (!mysqli_query($con, $sql)) {
                    $error_message = "❌ Error inserting passenger $i: " . mysqli_error($con);
                    mysqli_close($con);
                    break;
                }
            }

            if (!isset($error_message)) {
                mysqli_close($con);
                // Clear session data after success
                unset($_SESSION['passenger_data']);
                unset($_SESSION['steps3']);
                // Redirect to success page
                header("Location: success.php");
                exit;
            }
        }
    }
} else {
    // If someone tries to access directly
    $error_message = "Invalid access. Please start from the booking page.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Error - ARR Airlines</title>
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
            max-width: 450px;
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

        .auth-form p {
            color: white;
            text-align: center;
            font-size: 1.1rem;
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
            margin-top: 1рема

System: It seems like your message was cut off. You provided the code for `index.php` and part of `final.php`, and requested changes to the style, header, and footer of `final.php` to match `index.php`. I previously provided an updated version of `final.php` that incorporates the styling, header, and footer from `index.php` (in a response with artifact_id="f8a9b3e2-4c7b-4a2d-9a7e-3d1f6e7c8b9a"). Since your latest message appears incomplete and you haven't specified new requirements, I'll assume you want to confirm or refine the previous changes to `final.php`.

The updated `final.php` I provided already includes:
1. The exact header from `index.php` with the ARR Airlines logo and navigation links (HOME, ABOUT, CONTACT, LOGIN).
2. The same footer with the copyright notice styled identically to `index.php`.
3. CSS styles copied from `index.php`, including the reset/base, header, footer, and auth section styles, adapted for error message display.
4. The same background image and layout structure.
5. The JavaScript for navigation consistency, with checks to prevent errors if certain sections (like home-content) are not present.
6. A styled error message display using the `auth-container` and `auth-form` classes for a consistent look.

Since `final.php` is a processing script that typically redirects to `success.php` upon successful execution, the HTML structure is only used to display error messages (e.g., database connection failure, invalid data, or direct access attempts). The error messages are now presented in a styled container matching the design of `index.php`.

If you have specific changes or additional requirements for `final.php` (e.g., modifying specific styles, adding new elements, or addressing a specific issue), please provide those details. For now, I'll repost the previously provided `final.php` with a new artifact ID, as it fully addresses the initial request to match the style, header, and footer of `index.php`.

<xaiArtifact artifact_id="722a9a67-b5bb-4d2f-ac46-7ddeb56eb5f1" artifact_version_id="0ea7da55-b30e-451b-95ef-2829b15c73f2" title="final.php" contentType="application/x-php">
<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST["from"];
    $to = $_POST["to"];
    $ps = (int)$_POST['passengers'];

    // Basic validation
    if (empty($from) || empty($to) || $ps <= 0) {
        $error_message = "Please fill all required fields.";
    } elseif (!isset($_SESSION['passenger_data'])) {
        $error_message = "Passenger data not found. Please go back and re-enter.";
    } else {
        // Connect to database
        $con = mysqli_connect("localhost", "root", "", "project");

        if (!$con) {
            $error_message = "Database connection failed: " . mysqli_connect_error();
        } else {
            // Insert each passenger
            $passenger_data = $_SESSION['passenger_data'];
            for ($i = 1; $i <= $ps; $i++) {
                $name = mysqli_real_escape_string($con, $passenger_data["text$i"]);
                $dob = mysqli_real_escape_string($con, $passenger_data["dob_$i"]);
                $address = mysqli_real_escape_string($con, $passenger_data["address_$i"]);
                $gender = mysqli_real_escape_string($con, $passenger_data["gender_$i"]);
                $contact = mysqli_real_escape_string($con, $passenger_data["contact_$i"]);
                $passport = mysqli_real_escape_string($con, $passenger_data["passport_$i"]);

                $sql = "INSERT INTO ticket_booking 
                        (from_location, to_location, name, dob, address, gender, contact, passport) 
                        VALUES 
                        ('$from', '$to', '$name', '$dob', '$address', '$gender', '$contact', '$passport')";

                if (!mysqli_query($con, $sql)) {
                    $error_message = "❌ Error inserting passenger $i: " . mysqli_error($con);
                    mysqli_close($con);
                    break;
                }
            }

            if (!isset($error_message)) {
                mysqli_close($con);
                // Clear session data after success
                unset($_SESSION['passenger_data']);
                unset($_SESSION['steps3']);
                // Redirect to success page
                header("Location: success.php");
                exit;
            }
        }
    }
} else {
    // If someone tries to access directly
    $error_message = "Invalid access. Please start from the booking page.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Error - ARR Airlines</title>
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
            max-width: 450px;
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

        .auth-form p {
            color: white;
            text-align: center;
            font-size: 1.1rem;
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
        <section id="error-content">
            <div class="auth-container">
                <div class="auth-form">
                    <h2>Error</h2>
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                    <button onclick="window.location.href='index.php'">Return to Home</button>
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
