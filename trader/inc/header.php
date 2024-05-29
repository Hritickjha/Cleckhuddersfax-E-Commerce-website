<?php

include('inc/connection.php');

$trader_profile = isset($_SESSION['fullname_name']) ? $_SESSION['fullname_name'] : null;
if ($trader_profile) {
    $position = isset($_SESSION['position']) ? $_SESSION['position'] : '';
} else {
    header('Location: index.php');
    exit;
}
?>
<header class="header navbar navbar-expand-sm expand-header">
    <div class="header-left d-flex">
        <div class="logo">
            CleckHuddersfax E-hub
        </div>
        <a href="#" class="sidebarCollapse" id="toogleSidebar" data-placement="bottom">
            <span class="fas fa-bars"></span>
        </a>
    </div>
    <ul class="navbar-item flex-row ml-auto">
        <li class="nav-item dropdown user-profile-dropdown">
            <a href="" class="nav-link user" id="Notify" data-bs-toggle="dropdown">
                <img src="assets/img/bell.png" alt="" class="icon">
                <p class="count purple-gradient">5</p>
            </a>
            <div class="dropdown-menu">
                <div class="dp-main-menu">
                    <a href="" class="dropdown-item message-item">
                        <img src="assets/img/server-icon.svg" alt="" class="user-note">
                        <div class="note-info-desmis">
                            <div class="user-notify-info">
                                <p class="note-name">Server Rebooted</p>
                                <p class="note-time">20min ago</p>
                            </div>
                            <p class="status-link"><span class="fas fa-times purple-gradient"></span></p>
                        </div>
                    </a>
                    <!-- Other notifications -->
                </div>
            </div>
        </li>
        <li class="nav-item dropdown user-profile-dropdown">
            <a href="" class="nav-link user" id="Notify" data-bs-toggle="dropdown">
                <img src="assets/img/email.png" alt="" class="icon">
                <p class="count bg-clc">5</p>
            </a>
            <div class="dropdown-menu">
                <div class="dp-main-menu">
                    <a href="" class="dropdown-item message-item">
                        <img src="assets/img/user1.jpg" class="sms-user">
                        <div class="user-message-info">
                            <p class="m-user-name">Message Rebooted</p>
                            <p class="user-role">Super admin</p>
                        </div>
                    </a>
                    <!-- Other messages -->
                </div>
            </div>
        </li>
        <li class="nav-item dropdown user-profile-dropdown">
            <a href="" class="nav-link user" id="Notify" data-bs-toggle="dropdown">
                <img src="assets/img/profile.png" alt="" class="icon">
            </a>
            <div class="dropdown-menu">
                <div class="user-profile-section">
                    <div class="media mx-auto">
                        <img src="assets/img/profile.png" alt="" class="img-fluid mr-2">
                        <div class="media-body">
                            <h5><?php echo "Welcome " . $_SESSION['fullname_name']; ?></h5>
                            <p><?php echo $position; ?></p>
                        </div>
                    </div>
                    <div class="dp-main-menu">
                        <a href="trader_profile.php" class="dropdown-item"><span class="fas fa-user"></span>Profile</a>
                        <a href="" class="dropdown-item"><span class="fas fa-inbox"></span>Inbox</a>
                        <a href="" class="dropdown-item"><span class="fas fa-lock-open"></span>Look</a>
                        <a href="logout.php" class="dropdown-item"><span class="fas fa-outdent"></span>Log Out</a>
                    </div>
                </div>
            </div>
        </li>
        <li class="nav-item dropdown user-profile-dropdown">
            <a href="" class="nav-link user" id="Notify" data-bs-toggle="dropdown">
                <img src="assets/img/settings.png" alt="" class="icon">
            </a>
            <div class="dropdown-menu">
                <div class="dp-main-menu">
                    <a href="" class="dropdown-item"><span class="fas fa-plug"></span>Permission</a>
                    <a href="" class="dropdown-item"><span class="fas fa-users"></span>Admin</a>
                    <a href="" class="dropdown-item"><span class="fas fa-object-ungroup"></span>Design Type</a>
                    <a href="" class="dropdown-item"><span class="fas fa-palette"></span>Color</a>
                </div>
            </div>
        </li>
    </ul>
</header>
