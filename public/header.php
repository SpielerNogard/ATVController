<?php
require('config.php');
require('sections.php');
require('functions.php');
require('footer.php');

function Menu() { ?>
	
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="css/style.css">
</head>

<header>
<div class="topnav" id="TopnavMenu">
  <a href="#" class="logo">ATV Controller</a>
  <a href="index.php" class="active">Home</a>
  <a href="javascript:void(0);" class="icon" onclick="menu()">
    <i class="fa fa-bars"></i>
  </a>
</div>
</header>

<?php } ?>

<body>
