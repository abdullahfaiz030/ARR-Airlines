<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARR AIRLINES - Luxury Class Travel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Gold Theme Styles */
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
            justify-content: space-between;
            position: relative;
        }

        /* Background Overlay */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.3);
            z-index: 1;
        }

        /* Header Styles */
        header {
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 100;
            border-bottom: 4px solid #00c6ff;
            width: 100%;
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
            text-decoration: none;
            border-bottom: 2px solid #00c6ff;
            padding-bottom: 5px;
        }

        /* Form Section - Only font changes here */
        main {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 2;
            width: 100%;
            padding: 0;
        }

        .form-box {
            background-color: transparent;
            padding: 40px 30px;
            border-radius: 0;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            text-align: left;
            width: 100%;
            max-width: 100%;
            color: #fff;
            border: none;
            border-top: 2px solid #00c6ff;
            border-bottom: 2px solid #00c6ff;
            backdrop-filter: blur(5px);
            min-height: calc(100vh - 150px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Montserrat', sans-serif; /* Form font changed */
        }

        .form-box form {
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
            font-weight: 500; /* Medium weight instead of bold */
        }

        .form-box label {
            margin-right: 50px;
            font-weight: 600; /* Semi-bold */
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
            font-size: 1.1em;
        }

        .form-box select, 
        .form-box input[type="date"], 
        .form-box input[type="number"] {
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #00c6ff;
            border-radius: 5px;
            width: 100%;
            box-sizing: border-box;
            background-color: rgba(255, 255, 255, 0.9);
            color: #333;
            font-weight: 500; /* Medium weight */
            font-size: 1em;
            font-family: 'Montserrat', sans-serif; /* Input font changed */
        }

        .form-box input[name="passengers"] {
            width: 100px;
        }

        .form-box input[type="submit"] {
            background-color: #00c6ff;
            color: #000;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            display: block;
            margin: 15px auto 0;
            cursor: pointer;
            font-weight: 600; /* Semi-bold */
            font-size: 1.1em;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            font-family: 'Montserrat', sans-serif; /* Button font changed */
        }

        .form-box input[type="submit"]:hover {
            background-color: #DAA520;
            transform: scale(1.05);
        }

        /* Footer Styles */
        footer {
            background-color: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 1.5rem;
            text-align: center;
            border-top: 1px solid #00c6ff;
            width: 100%;
            z-index: 2;
        }

        footer p {
            color: #00c6ff;
            font-weight: bold;
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .form-box {
                padding: 20px 15px;
            }
            .nav-links li {
                margin-left: 1rem;
            }
            .form-box label {
                margin-right: 20px;
                font-size: 1em;
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
        <section id="home-content">
            <div class="form-box">
                <form method="post" action="steps2.php">
                    <label for="country1">From: </label>
                    <select name="country1" required>
                        <option value="">--- Select Country ---</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="USA">USA</option>
                    </select><br><br>

                    <label for="country2">To: </label>
                    <select name="country2" required>
                        <option value="">--- Select Country ---</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="USA">USA</option>
                    </select><br><br>

                    Date: <input type="date" name="dob"><br><br>

                    <label>No Passengers:</label>
                    <input type="number" name="passengers" min="1" required><br><br>

                    <input type="submit" value="Next">
                </form>
            </div>
        </section>
    </main>

    <footer>
        <p>© 2025 ARR AIRLINES</p>
        <p>ALL RIGHTS RESERVED</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.nav-links a');

            function setActiveNavLink() {
                const currentPage = window.location.pathname.split('/').pop();
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if ((currentPage === 'index.php' && link.classList.contains('home-link')) {
                        link.classList.add('active');
                    }
                });
            }
            
            setActiveNavLink();
        });
    </script>
</body>
</html>
