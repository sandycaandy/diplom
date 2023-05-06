<div id="block-header">

    <div id="block-header1">
        <h3 class="title-block">Панель управления интернет-магазина постельного белья</h3>
        <p id="link-nav"><?php echo $_SESSION['urlpage']; ?></p>
    </div>

    <div id="block-header2">
        <p align="right"><a href="?logout">Выйти из профиля</a></p>

    </div>

</div>

<div id="left-nav">
    <ul class="menu-admin">
        <li><a href="orders.php">Заказы</a><?php echo $count_str1; ?></li>
        <li><a href="tovar.php">Товары</a></li>
        <li><a href="category.php">Категории</a></li>
        <li><a href="clients.php">Клиенты</a></li>
    </ul>
</div>