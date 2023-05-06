<?php
include("/include/connect_db.php");
include("/functions/functions.php");
session_start();
//include("include/auth_cookie.php");

$sorting = $_GET["sort"];

switch ($sorting) {

    case 'news';
        $sorting = 'datetime DESC';
        $sort_name = 'Сначала новые';
        break;

    case 'popular';
        $sorting = 'count DESC';
        $sort_name = 'Сначала популярные';
        break;


    case 'price-asc';
        $sorting = 'price ASC';
        $sort_name = 'От дешевых к дорогим';
        break;

    case 'price-desc';
        $sorting = 'price DESC';
        $sort_name = 'От дорогих к дешевым';
        break;

    default:
        $sorting = 'products_id DESC';
        $sort_name = 'Нет сортировки';
        break;
}


?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
  <link href="/style/style.css" rel="stylesheet" type="text/css" />
  <link href="/trackbar/trackbar.css" rel="stylesheet" type="text/css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@100;300;400;700;800&display=swap" rel="stylesheet">
  <script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script>
  <script type="text/javascript" src="/js/jcarousellite_1.0.1.js"></script>
  <script type="text/javascript" src="/js/shop-script.js"></script>
  <script type="text/javascript" src="/js/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="/trackbar/jquery.trackbar.js"></script>
  <script type="text/javascript" src="/js/TextChange.js"></script>

  <title>Интернет-магазин постельного белья | Покрывала</title>
</head>
<header class="header">
         <?php
           include "include/block-header.php";
        ?>
</header>

<body>

    <!-- Сортировка 
    <section class="sort-block">
        <div class="container">
            <div class="sort-list">
                <ul class="sort-item">

                    <li class="sort"><a id="select-sort"><?php echo $sort_name; ?></a>
                        <ul id="sorting-list">
                            <li class="sort-name"><a href="linen-page.php?sort=news">Сначала новые</a></li>
                            <li class="sort-name"><a href="linen-page.php?sort=popular">Сначала популярное</a></li>
                            <li class="sort-name"><a href="linen-page.php?sort=price-asc">От дешевых к дорогим</a></li>
                            <li class="sort-name"><a href="linen-page.php?sort=price-desc">От дорогих к дешевым</a></li>


                        </ul>
                    </li>
                </ul>
            </div>
        </div>
 <!-- Сортировка -->
    <section class="category-product">

        <div class="container">
        <h1 class="section__title product__list-title">Покрывала</h1>
            <div class="product__wrapper-block">


            <div class="block-category">
                <div id="block-parameter">
                    <!-- Сортировка по стоимости -->
                    <script type="text/javascript">
                        $(document).ready(function() {
                            $('#blocktrackbar').trackbar({
                                onMove: function() {
                                    document.getElementById("start-price").value = this.leftValue;
                                    document.getElementById("end-price").value = this.rightValue;
                                },
                                width: 160,
                                leftLimit: 500,
                                leftValue: <?php

                                            if ((int)$_GET["start_price"] >= 500 and (int)$_GET["start_price"] <= 50000) {
                                                echo (int)$_GET["start_price"];
                                            } else {
                                                echo "500";
                                            }

                                            ?>,
                                rightLimit: 50000,
                                rightValue: <?php

                                            if ((int)$_GET["end_price"] >= 500 and (int)$_GET["end_price"] <= 50000) {
                                                echo (int)$_GET["end_price"];
                                            } else {
                                                echo "30000";
                                            }

                                            ?>,
                                roundUp: 100
                            });
                        });
                    </script>


                    <form class="filter-form" method="GET" action="search_filter-cover.php">


                        <h3 class="category-title"> Бренды </h3>


                        <ul class="checkbox-brand">

                            <?php

                            $result = mysql_query("SELECT * FROM category WHERE type='cover'", $link);

                            if (mysql_num_rows($result) > 0) {
                                $row = mysql_fetch_array($result);
                                do {
                                    $checked_brand = "";
                                    if ($_GET["brand"]) {
                                        if (in_array($row["id"], $_GET["brand"])) {
                                            $checked_brand = "checked";
                                        }
                                    }


                                    echo '

<li><input ' . $checked_brand . ' type="checkbox"name="brand[]" class="checkbox__input" value="' . $row["id"] . '" id="checkbrend' . $row["id"] . '" /><label for="checkbrend' . $row["id"] . '">' . $row["brand"] . '</label></li>
  
  
  ';
                                } while ($row = mysql_fetch_array($result));
                            }


                            ?>

                        </ul>

                        <h3 class="category-title">Стоимость</h3>
                        <div id="block-input-price">
                            <ul>
                                <li>
                                    <p>от</p>
                                </li>
                                <li><input type="text" id="start-price" name="start_price" value="1000" /></li>
                                <li>
                                    <p>до</p>
                                </li>
                                <li><input type="text" id="end-price" name="end_price" value="30000" /></li>
                                <li>
                                    <p>₽</p>
                                </li>
                            </ul>
                        </div>

                        <div id="blocktrackbar"></div>


                        <h3 class="category-title"> Ткань </h3>
                        <ul class="checkbox-type">

                            <?php

                            $result = mysql_query("SELECT * FROM type_cloth WHERE type_cloth='cover'", $link);

                            if (mysql_num_rows($result) > 0) {
                                $row = mysql_fetch_array($result);
                                do {
                                    $checked_name_face = "";
                                    if ($_GET["name_face"]) {
                                        if (in_array($row["id"], $_GET["name_face"])) {
                                            $checked_name_face = "checked";
                                        }
                                    }


                                    echo '

<li><input ' . $checked_name_face . ' type="checkbox"name="name_face[]" class="checkbox__input" value="' . $row["id"] . '" id="checktype' . $row["id"] . '" /><label for="checktype' . $row["id"] . '">' . $row["name_face"] . '</label></li>
  
  
  ';
                                } while ($row = mysql_fetch_array($result));
                            }


                            ?>

                        </ul>

                        <h3 class="category-title"> Размер </h3>
                        <ul class="checkbox-type">

                            <?php

                            $result = mysql_query("SELECT * FROM type_size WHERE type_size='cover'", $link);

                            if (mysql_num_rows($result) > 0) {
                                $row = mysql_fetch_array($result);
                                do {
                                    $checked_name_size = "";
                                    if ($_GET["name_size"]) {
                                        if (in_array($row["id"], $_GET["name_size"])) {
                                            $checked_name_size = "checked";
                                        }
                                    }


                                    echo '

<li><input ' . $checked_name_size . ' type="checkbox"name="name_size[]" class="checkbox__input" value="' . $row["id"] . '" id="checksize' . $row["id"] . '" /><label for="checksize' . $row["id"] . '">' . $row["name_size"] . '</label></li>
  
  
  ';
                                } while ($row = mysql_fetch_array($result));
                            }


                            ?>

                        </ul>

                        <input class="button__filter" type="submit" name="submit" id="button-param-search" value="Найти " />
                    </form>


                </div>


                <!--
    <ul class="brand-block">
   <?php

    $result = mysql_query("SELECT * FROM category WHERE type='cover'", $link);

    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        do {
            echo '
  
<li><a href="view_cat.php?cat=' . strtolower($row["brand"]) . '&type=' . $row["type"] . '">' . $row["brand"] . '</a></li>
  
  ';
        } while ($row = mysql_fetch_array($result));
    }

    ?>
    </ul>
-->

            </div>


            <!-- Вывод товаров -->
            <div class="product__wrapper">
                <ul class="product__list">
                    <?php

                    $result = mysql_query("SELECT * FROM table_products WHERE type_tovara='cover' ORDER BY $sorting", $link);

                    if (mysql_num_rows($result) > 0) {
                        $row = mysql_fetch_array($result);

                        do {
                            if ($row["image"] != "" && file_exists("./uploads_images/" . $row["image"])) {
                                $img_path = './uploads_images/' . $row["image"];
                                $max_width = 280;
                                $max_height = 280;
                                list($width, $height) = getimagesize($img_path);
                                $ratioh = $max_height / $height;
                                $ratiow = $max_width / $width;
                                $ratio = min($ratioh, $ratiow);
                                $width = intval($ratio * $width);
                                $height = intval($ratio * $height);
                            } else {
                                $img_path = "/images/noimages80x70.png";
                                $width = 80;
                                $height = 70;
                            }

                            echo '
<li class="product-item">
<div class="product-link">
<img class="product-image" src="' . $img_path . '" width="' . $width . '" height="' . $height . '" />
</div>
<span class="type">' . $row["description"] . '</span>
<div class="description-item">
<span><a class="title-product" href="view_content.php?id=' . $row["products_id"] . '" >' . $row["title"] . '</a></span> 
</div>

<div class="item-price">
<span class="price-product new-price"> ' . group_numerals($row["price"]) . ' ₽</span>
<span class="price-product old-price">' . $row["old_price"] . '</span>
</div>
<div class="more-product">
<a class="more-details" href="view_content.php?id=' . $row["products_id"] . '">Подробнее о товаре</a>
<a class="buy-product" tid="' . $row["products_id"] . '"></a>
</div>
</li>


';
                        } while ($row = mysql_fetch_array($result));
                    }





                    ?>
                </ul>
            </div> </div>
        </div>
    </section>


</body>