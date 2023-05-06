<?php
include("include/connect_db.php");
include("functions/functions.php");
$cat = clear_string($_GET["cat"]);
$type = clear_string($_GET["type"]);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta charset="UTF-8">
  <link href="style/style.css" rel="stylesheet" type="text/css" />
  <link href="trackbar/trackbar.css" rel="stylesheet" type="text/css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@100;300;400;700;800&display=swap" rel="stylesheet">
  <script type="text/javascript" src="/js/jquery-1.8.2.min.js"></script>
  <script type="text/javascript" src="/js/jcarousellite_1.0.1.js"></script>
  <script type="text/javascript" src="/js/shop-script.js"></script>
  <script type="text/javascript" src="/js/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="/trackbar/jquery.trackbar.js"></script>
  <script type="text/javascript" src="/js/TextChange.js"></script>

  <title>Интернет-магазин постельного белья</title>
</head>
<header class="header">
  <?php
  include("include/block-header.php");
  ?>

</header>

<body>
  <section class="category-product">

    <div class="container">
    <h1 class="section__title product__list-title">Постельное бельё</h1>
            <div class="product__wrapper-block">
      <div class="block-category">
        <!-- Сортировка по стоимости -->
        <script type="text/javascript">
          $(document).ready(function() {
            $('#blocktrackbar').trackbar({
              onMove: function() {
                document.getElementById("start-price").value = this.leftValue;
                document.getElementById("end-price").value = this.rightValue;
              },
              width: 160,
              leftLimit: 10,
              leftValue: <?php

                          if ((int)$_GET["start_price"] >= 10 and (int)$_GET["start_price"] <= 10000) {
                            echo (int)$_GET["start_price"];
                          } else {
                            echo "50";
                          }

                          ?>,
              rightLimit: 10000,
              rightValue: <?php

                          if ((int)$_GET["end_price"] >= 10 and (int)$_GET["end_price"] <= 10000) {
                            echo (int)$_GET["end_price"];
                          } else {
                            echo "10000";
                          }

                          ?>,
              roundUp: 100
            });
          });
        </script>


        <form class="filter-form" method="GET" action="search_filter.php">

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



          <h3 class="category-title"> Бренды </h3>


          <ul class="checkbox-brand">

            <?php

            $result = mysql_query("SELECT * FROM category WHERE type='linen'", $link);

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


          <h3 class="category-title"> Ткань </h3>
          <ul class="checkbox-type">

            <?php

            $result = mysql_query("SELECT * FROM type_cloth WHERE type_cloth='linen'", $link);

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

            $result = mysql_query("SELECT * FROM type_size WHERE type_size='linen'", $link);

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



      <div id="block-content">


        <?php


        if ($_GET["brand"]) {
          $check_brand = implode(',', $_GET["brand"]);
        }

        if ($_GET["name_face"]) {
          $check_face = implode(',', $_GET["name_face"]);
        }

        if ($_GET["name_size"]) {
          $check_size = implode(',', $_GET["name_size"]);
        }

        $start_price = (int)$_GET["start_price"];
        $end_price = (int)$_GET["end_price"];


        if (!empty($check_brand) or !empty($end_price)) {

          if (!empty($check_brand)) $query_brand = " AND brand_id IN($check_brand)";
          if (!empty($check_face)) $query_face = " AND skin_id IN($check_face)";
          if (!empty($check_size)) $query_size = " AND size_id IN($check_size)";
          if (!empty($end_price)) $query_price = " AND price BETWEEN $start_price AND $end_price";
        }



        ?>
      </div>

      <ul class="product__list">

        <?php

        $result = mysql_query("SELECT * FROM table_products WHERE type_tovara='linen' AND  visible='1'  $query_brand $query_face $query_size $query_price  ORDER BY products_id DESC", $link);

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
            <span class="price-product old-price">' . $row["old_price"] . '₽</span>
            </div>
            <div class="more-product">
            <a class="more-details" href="view_content.php?id=' . $row["products_id"] . '">Подробнее о товаре</a>
            <a class="buy-product" tid="' . $row["products_id"] . '"></a>
            </div>
            </li>



';
          } while ($row = mysql_fetch_array($result));
        } else {
          echo '<h3>Выбранной категории товаров не существует!</3>';
        }


        ?>
      </ul>


    </div>
    </div>

    </div>
      </div>
  </section>
</body>

</html>