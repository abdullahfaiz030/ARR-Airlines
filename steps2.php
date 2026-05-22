<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $from = $_POST["country1"];
    $to = $_POST["country2"];
    $ps = $_POST["passengers"];
} else {
    header("Location: step1.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARR AIRLINES - Select Your Flight</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        }

        /* Header Styles */
        header {
            background-color: rgba(0, 0, 0, 0.8);
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
            text-decoration: none;
            border-bottom: 2px solid #00c6ff;
            padding-bottom: 5px;
        }

        /* Flight Selection Styles */
        .flight-container {
            flex: 1;
            padding: 2rem;
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
        }

        .flight-title {
            text-align: center;
            color: #00c6ff;
            margin-bottom: 2rem;
            font-size: 2rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .flight-card {
            border: 2px solid #00c6ff;
            border-radius: 8px;
            background-color: rgba(0, 0, 0, 0.7);
            padding: 1.5rem;
            margin: 1.5rem auto;
            max-width: 800px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s;
        }

        .flight-card:hover {
            transform: translateY(-5px);
        }

        .flight-header {
            font-weight: bold;
            font-size: 1.2rem;
            color: #00c6ff;
            margin-bottom: 0.5rem;
        }

        .flight-info {
            font-size: 1rem;
            margin: 0.5rem 0;
            color: white;
        }

        .flight-price {
            color: #00c6ff;
            font-weight: bold;
            margin-top: 1rem;
            font-size: 1.2rem;
        }

        .radio-select {
            margin-right: 1rem;
            transform: scale(1.2);
            accent-color: #00c6ff;
        }

        .submit-btn {
            background-color: #00c6ff;
            color: #000;
            padding: 1rem 2.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            margin: 2rem auto;
            display: block;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-btn:hover {
            background-color: #000;
            color: #00c6ff;
            border: 1px solid #00c6ff;
            transform: scale(1.05);
        }

        .no-flights {
            text-align: center;
            color: #00c6ff;
            font-size: 1.2rem;
            margin-top: 2rem;
        }

        /* Footer Styles */
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
            margin-bottom: 0.5rem;
            letter-spacing: 1px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .flight-card {
                padding: 1rem;
            }
            
            .nav-links li {
                margin-left: 1rem;
            }
            
            .flight-title {
                font-size: 1.5rem;
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

    <main class="flight-container">
        <h2 class="flight-title">Select Your Flight: <?= $from ?> → <?= $to ?></h2>

        <form method="post" action="steps3.php">
            <?php
            function showCard($flightNo, $dep, $arr, $duration, $class, $refundable, $baggage, $price) {
                echo "
                <div class='flight-card'>
                    <label>
                        <input type='radio' name='flight' value='$flightNo|$dep|$arr|$class|$price' class='radio-select' required>
                        <span class='flight-header'>SriLankan Airlines ($flightNo)</span><br>
                    </label>
                    <div class='flight-info'>$dep → $arr | $duration | $class | $refundable</div>
                    <div class='flight-info'>Baggage: $baggage</div>
                    <div class='flight-price'>Rs. $price (Incl. Taxes)</div>
                </div>";
            }

            // --- Route-based logic ---
            if ($from == "Sri Lanka" && $to == "USA") {
                showCard("UL101", "07:00 AM", "07:00 PM", "20h (1 Stop)", "Economy", "Refundable", "30kg + 7kg", "145000");
                showCard("UL202", "10:00 PM", "11:00 AM", "23h (Non-stop)", "Business", "Refundable", "40kg + 10kg", "190000");

            } elseif ($from == "USA" && $to == "Sri Lanka") {
                showCard("UL303", "06:00 AM", "02:30 AM", "18h 30m (1 Stop)", "Economy", "Refundable", "25kg + 7kg", "138000");
                showCard("UL404", "02:00 PM", "05:00 AM", "21h (Non-stop)", "Business", "Refundable", "40kg + 10kg", "180000");

            } else {
                echo "<p class='no-flights'>No flights found for this route.</p>";
            }
            ?>
            <input type="hidden" name="from" value="<?= $from ?>">
            <input type="hidden" name="to" value="<?= $to ?>">
            <input type="hidden" name="ps" value="<?= $ps ?>">
            <input type="submit" value="Continue" class="submit-btn">
        </form>
    </main>

    <footer>
        <p>© 2025 ARR AIRLINES</p>
        <p>ALL RIGHTS RESERVED</p>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set active nav link based on current page
            const currentPage = window.location.pathname.split('/').pop();
            const navLinks = document.querySelectorAll('.nav-links a');
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === currentPage) {
                    link.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
