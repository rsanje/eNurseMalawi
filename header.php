<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Your Website Title</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
  <link rel="stylesheet" href="main.css">
   <style>
        .table-auto th, .table-auto td {
            text-align: left;
            padding: 0.25rem 0.5rem; /* Adjust padding as needed */
            margin: 0; /* Remove unwanted margin */
        }
    </style>
  
</head>
<body class="bg-gray-100">
  <nav>
    <ul class="menu">
        <li class="logo"><a href="home.php">Nurses & Midwives Council of Malawi</a></li>
        <li class="item"><a href="home.php">Home</a></li>
        <li class="item"><a href="#">About</a></li>
        <li class="item has-submenu">
            <a tabindex="0">Services</a>
            <ul class="submenu">
                <li class="subitem"><a href="#">Registration</a></li>
                <li class="subitem"><a href="#">Membership Renewal</a></li>
                <li class="subitem"><a href="#">Education & Training</a></li>
                <li class="subitem"><a href="#">Continuing Professional Development</a></li>
            </ul>
        </li>
        <li class="item has-submenu">
            <a tabindex="0">Departments</a>
            <ul class="submenu">
                <li class="subitem"><a href="#">The Registrar</a></li>
                <li class="subitem"><a href="#">Finance & Administration</a></li>
            </ul>
        </li>
        <li class="item"><a href="#">Blog</a></li>
        <li class="item"><a href="#">Contact</a></li>
        <?php if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
          <!-- User is logged in, show logout button -->
          <li class="item bg-red-500 hover:bg-red-700 text-white font-bold"><a href="logout.php">Log out</a></li>
        <?php else: ?>
            <!-- User is not logged in, show login button -->
          <li class="item button"><a href="login.php">Log In</a></li>
          <li class="item button secondary"><a href="register.php">Sign Up</a></li>
        <?php endif; ?>
        
        <li class="toggle"><a href="#"><i class="fas fa-bars"></i></a></li>
    </ul>
</nav>
