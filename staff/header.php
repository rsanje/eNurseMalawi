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
        <li class="item"><a href="dashboard.php">Dashboard</a></li>
        <li class="item"><a href="staff.php">staff</a></li>
        <li class="item"><a href="user.php">users</a></li>
        <li class="item has-submenu">
            <a tabindex="0">Add</a>
            <ul class="submenu">
                <li class="subitem"><a href="add-nurse.php">Register Nurse</a></li>
                <li class="subitem"><a href="add-user.php">Add User</a></li>
                <li class="subitem"><a href="add-document.php">Add user Document</a></li>
                <li class="subitem"><a href="add-qualification.php">Add user Qualification</a></li>
                <li class="subitem"><a href="add-employment.php">Add User Employment</a></li>
            </ul>
        </li>
        <li class="item bg-red-500 hover:bg-red-700 text-white font-bold"><a href="logout.php">Log out</a></li>
        <li class="item button secondary"><a href="#">Sign Up</a></li>
        <li class="toggle"><a href="#"><i class="fas fa-bars"></i></a></li>
    </ul>
</nav>

<script src="script.js"></script>
