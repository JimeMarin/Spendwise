<?php
require_once 'dbConfig.php';
?>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <style>
              
        /* Barra superior fija */
        .top-bar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60px;            
            color: white; 
            background-color: #182749;  
            display: flex;     
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
        }
        
        .logo {
            font-size: 20px;
            font-weight: bold;
            width: 130px;            
            justify-content: flex-start;
        }
        
        .right-section {
            display: flex;
            margin-left: auto; 
            align-items: center;
        }
        
        .user-menu {
            color: #416847;
            position: relative;
            display: inline-block;
            justify-content: flex-end;
        }
        
        .user-name {
            justify-content: flex-end;
            display: flex;
            cursor: pointer;
            align-items: center;
        }
        
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
        }
        
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        
        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }
        
        .user-menu:hover .dropdown-content {
            display: block;
        }
        
        .notifications {
            position: relative;
            cursor: pointer;
            margin-left: 20px;  
        }
        
        .notification-bell {
            font-size: 20px;
            justify-content: flex-end;
        }
        
        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: red;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        
        
        /* Menú inferior */
        .bottom-menu {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 60px;
            background-color: white;
            display: flex;
            justify-content: space-evenly;
            align-items: center;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        
        .menu-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            cursor: pointer;
        }
                     
        .menu-text {
            font-size: 12px;
            color: #555;
        }
        
        .menu-item.active .menu-text {
            color: #4CAF50;
            font-weight: bold;
        }
    </style>
    <!-- Barra superior fija -->
    <div class="top-bar">
    
    	<img src="imgs/LogoSW.png" alt="logo" class="logo">   
    	
    	<div class="right-section">
    	    
            <div class="user-menu">
                <div class="user-name">
                    <?php require_once 'heading.php';?>
                    <span style="margin-left: 5px;"></span>
                </div>
                <div class="dropdown-content">
                    <a href="changePassword.php">Change Password</a>
                    <?php if (isset($_SESSION["ID"])): ?>
                		<a href="endSession.php">Logout</a>
                	<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="notifications">
            <span class="notification-bell">🔔</span>
            <span class="notification-count"></span>
        </div>
    </div>
</head>
<body>


    <div class="bottom-menu">
    <!-- Primer botón: Redirige a testExpenses.php -->
    <div class="menu-item">
        <a href="testExpenses.php" class="menu-icon" style="text-decoration: none; color: inherit;">📈</a>
    </div>

    <!-- Segundo botón: Budget-->
    <div class="menu-item">
        <a href="testBudget.php" class="menu-icon" style="text-decoration: none; color: inherit;">💰</a>
    </div>
    
    <!-- Segundo botón:Categories-->
    <div class="menu-item">
        <a href="testCategory.php" class="menu-icon" style="text-decoration: none; color: inherit;">📋</a>
    </div>

    <!-- Tercer botón: Goals -->
    <div class="menu-item">
        <a href="testGoals.php" class="menu-icon" style="text-decoration: none; color: inherit;">🎯</a>
    </div>

    <!-- Cuarto botón:  Savings -->
    <div class="menu-item">
        <a href="testSavings.php" class="menu-icon" style="text-decoration: none; color: inherit;">🐖</a>
    </div>
</div>

</body>