<?php
// Dynamic URL for logo
$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/pbcms";
$logo_url = $base_url . "/assets/logo.JPG";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>PBCMS</title>
    <style>
        /* Header styling */
        header {
            background-color: #05581e;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        /* Logo + system title */
        .logo-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo-wrapper {
            background: white;
            border-radius: 50%;
            padding: 5px;
            box-shadow: 0 3px 8px rgba(0,0,0,0.3);
        }

        .logo-wrapper img {
            height: 60px;
            width: 60px;
            border-radius: 50%;
            display: block;
        }

        .system-title {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.4);
        }

        .motto {
            font-style: italic;
            font-size: 14px;
            max-width: 600px;
            text-align: right;
        }

        nav {
            margin-top: 5px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
            font-weight: bold;
        }

        nav a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<header>
    <div class="logo-title">
        <div class="logo-wrapper">
            <img src="<?= $logo_url ?>" alt="PBC Logo">
        </div>
        <div class="system-title">
            Pentecostal Bible College Management System
        </div>
    </div>
    <div class="motto">
        "Study to shew thyself approved unto God, a workman that needeth not to be ashamed." – 2 Timothy 2:15
    </div>
</header>
