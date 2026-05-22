<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>ARR Airlines - Premium Travel</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            /* Deep blue gradient overlay over the background image */
            background: linear-gradient(rgba(0, 15, 40, 0.85), rgba(0, 30, 80, 0.95)), url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?q=80&w=2000&auto=format&fit=crop') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Glassmorphism utilities */
        .glass-panel {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 215, 0, 0.2);
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        /* Navbar */
        header {
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid #00c6ff;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            color: #00c6ff !important;
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .nav-link {
            color: white !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: #00c6ff !important;
            transform: translateY(-2px);
        }

        /* Buttons */
        .btn-custom {
            background: linear-gradient(45deg, #00c6ff, #0072ff);
            color: #000;
            border: none;
            border-radius: 30px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
            padding: 0.75rem 2rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.3);
        }

        .btn-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.5);
            color: #000;
        }

        /* Form Controls */
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 215, 0, 0.3);
            color: white;
            border-radius: 8px;
        }

        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.15);
            border-color: #00c6ff;
            color: white;
            box-shadow: 0 0 0 0.25rem rgba(255, 215, 0, 0.25);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        option {
            background-color: #333;
            color: white;
        }

        main {
            flex-grow: 1;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="index.php"><i class="bi bi-airplane-engines"></i> ARR AIRLINES</a>
                <button class="navbar-toggler custom-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" style="background-color: #00c6ff;">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item"><a class="nav-link" href="index.php">Search Flights</a></li>
                        
                        <?php if(isset($_SESSION['user_id'])): ?>
                            <?php if(isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <li class="nav-item"><a class="nav-link" href="admin.php">Admin Panel</a></li>
                            <?php else: ?>
                                <li class="nav-item"><a class="nav-link" href="dashboard.php">My Bookings</a></li>
                            <?php endif; ?>
                            <li class="nav-item ms-3">
                                <?php 
                                    $hour = date('H');
                                    $greeting = ($hour < 12) ? 'Good Morning' : (($hour < 18) ? 'Good Afternoon' : 'Good Evening');
                                ?>
                                <span class="text-light me-3 fw-bold"><i class="bi bi-brightness-high"></i> <?php echo $greeting; ?>, <?php echo isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : 'Guest'; ?></span>
                                <a href="logout.php" class="btn btn-outline-light btn-sm rounded-pill border-info text-info hover-info">Logout</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                            <li class="nav-item ms-2"><a href="signup.php" class="btn btn-custom btn-sm py-2">Sign Up</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="py-5">
