<?php
include("include/connect_db.php");
include("functions/functions.php");
session_start();
//include("include/auth_cookie.php");

$id = clear_string($_GET["id"]);
$action = clear_string($_GET["action"]);

switch ($action) {

   case 'clear':
      $clear = mysql_query("DELETE FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'", $link);
      break;

   case 'delete':
      $delete = mysql_query("DELETE FROM cart WHERE cart_id = '$id' AND cart_ip = '{$_SERVER['REMOTE_ADDR']}'", $link);
      break;
}

if (isset($_POST["submitdata"])) {
   if ($_SESSION['auth'] == 'yes_auth') {

      mysql_query("INSERT INTO orders(order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
						VALUES(	
                             NOW(),
                            '" . $_POST["order_delivery"] . "',					
							'" . $_SESSION['auth_surname'] . ' ' . $_SESSION['auth_name'] . ' ' . $_SESSION['auth_patronymic'] . "',
                            '" . $_SESSION['auth_address'] . "',
                            '" . $_SESSION['auth_phone'] . "',
                            '" . $_POST['order_note'] . "',
                            '" . $_SESSION['auth_email'] . "'                              
						    )", $link);
   } else {
      $_SESSION["order_delivery"] = $_POST["order_delivery"];
      $_SESSION["order_fio"] = $_POST["order_fio"];
      $_SESSION["order_email"] = $_POST["order_email"];
      $_SESSION["order_phone"] = $_POST["order_phone"];
      $_SESSION["order_address"] = $_POST["order_address"];
      $_SESSION["order_note"] = $_POST["order_note"];

      mysql_query("INSERT INTO orders(order_datetime,order_dostavka,order_fio,order_address,order_phone,order_note,order_email)
						VALUES(	
                             NOW(),
                            '" . clear_string($_POST["order_delivery"]) . "',					
							'" . clear_string($_POST["order_fio"]) . "',
                            '" . clear_string($_POST["order_address"]) . "',
                            '" . clear_string($_POST["order_phone"]) . "',
                            '" . clear_string($_POST["order_note"]) . "',
                            '" . clear_string($_POST["order_email"]) . "'                   
						    )", $link);
   }


   $_SESSION["order_id"] = mysql_insert_id();

   $result = mysql_query("SELECT * FROM cart WHERE cart_ip = '{$_SERVER['REMOTE_ADDR']}'", $link);
   if (mysql_num_rows($result) > 0) {
      $row = mysql_fetch_array($result);

      do {

         mysql_query("INSERT INTO buy_products(buy_id_order,buy_id_product,buy_count_product)
						VALUES(	
                            '" . $_SESSION["order_id"] . "',					
							'" . $row["cart_id_product"] . "',
                            '" . $row["cart_count"] . "'                   
						    )", $link);
      } while ($row = mysql_fetch_array($result));
   }

   header("Location: cart.php?action=completion");
}


$result = mysql_query("SELECT * FROM cart,table_products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND table_products.products_id = cart.cart_id_product", $link);
if (mysql_num_rows($result) > 0) {
   $row = mysql_fetch_array($result);

   do {
      $int = $int + ($row["price"] * $row["cart_count"]);
   } while ($row = mysql_fetch_array($result));


   $itogpricecart = $int;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
  <link href="../style/style.css" rel="stylesheet" type="text/css" />
  <link href="../trackbar/trackbar.css" rel="stylesheet" type="text/css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans:wght@100;300;400;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
  <script type="text/javascript" src="../js/jquery-1.8.2.min.js"></script>
  <script type="text/javascript" src="../js/jcarousellite_1.0.1.js"></script>
  <script type="text/javascript" src="../js/shop-script.js"></script>
  <script type="text/javascript" src="../js/jquery.cookie.min.js"></script>
  <script type="text/javascript" src="../trackbar/jquery.trackbar.js"></script>
  <script type="text/javascript" src="../js/TextChange.js"></script>

  <title>Интернет-магазин постельного белья</title>
</head>

<body>
   <!-- Модальное окно  -->
   <div id="openModal" class="modal">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <h3 class="modal-title">Спасибо, что выбрали нас!</h3>
               <a href="#close" title="Close" class="close">×</a>
            </div>
            <div class="modal-body">
               <p class="info-modal">Ваш заказ успешно оформлен. В ближайшее время по указанному телефону с Вами свяжется наш менеджер.</p>
            </div>
         </div>
      </div>
   </div>
   <!-- Модальное окно. конец -->


   <div id="block-body">
      <?php
      include("include/block-header.php");
      ?>
      <div class="container">

         <?php

         $action = clear_string($_GET["action"]);
         switch ($action) {

            case 'oneclick':

               echo ' 
               <div class="block__step">  
               <div class="name__step">  
               <ul class="list__step">
               <li class="item__step"><a class="active__link">Корзина товаров</a></li>
               <li class="item__step"><span>&rarr;</span></li>
               <li class="item__step"><a> Контактная информация</a></li>
               <li class="item__step"><span>&rarr;</span></li>
               <li class="item__step"><a>Завершение заказа</a></li> 
               </ul>  
               </div>  
               <div>
               <p class="count__step">шаг 1 из 3</p>
               <a class="clear__cart" href="cart.php?action=clear">Очистить корзину</a> </div>
               </div>
';


               $result = mysql_query("SELECT * FROM cart,table_products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND table_products.products_id = cart.cart_id_product", $link);

               if (mysql_num_rows($result) > 0) {
                  $row = mysql_fetch_array($result);

                  echo '  
   
   ';

                  do {

                     $int = $row["cart_price"] * $row["cart_count"];
                     $all_price = $all_price + $int;

                     if (strlen($row["image"]) > 0 && file_exists("./uploads_images/" . $row["image"])) {
                        $img_path = './uploads_images/' . $row["image"];
                        $max_width = 150;
                        $max_height = 150;
                        list($width, $height) = getimagesize($img_path);
                        $ratioh = $max_height / $height;
                        $ratiow = $max_width / $width;
                        $ratio = min($ratioh, $ratiow);

                        $width = intval($ratio * $width);
                        $height = intval($ratio * $height);
                     } else {
                        $img_path = "/images/noimages.jpeg";
                        $width = 120;
                        $height = 105;
                     }

                     echo '

                     <div class="block-list__cart">
                     <div class="img-cart">
                       <img src="' . $img_path . '" width="' . $width . '" height="' . $height . '" />
                     </div>
                  
                     <div class="title-cart">
                       <a class="head__product-name" href="">' . $row["title"] . '</a>
                       <p class="head__product-desc">' . $row["brand"] . '</p>
                       <p class="head__product-desc">' . $row["name_face"] . '</p>
                     </div>
                  
                     <div class="count-cart">
                        <ul class="input-count-style">
                        <li>
                          <p iid="' . $row["cart_id"] . '" class="count-minus">Удалить</p>
                        </li>
                        
                        <li>
                        <p><input class="input-cart" id="input-id' . $row["cart_id"] . '" iid="' . $row["cart_id"] . '" class="count-input" maxlength="3" type="text" value="' . $row["cart_count"] . '" /></p>
                        </li>
                  
                        <li>
                        <p iid="' . $row["cart_id"] . '" class="count-plus">Добавить</p>
                  </li>
                  
                  </ul>
                  </div>
                  
                  <div id="tovar' . $row["cart_id"] . '" class="price-product"><span class="span-count" >' . $row["cart_count"] . '</span> x <span>' . $row["cart_price"] . '</span><p class="price__product" price="' . $row["cart_price"] . '" >' . group_numerals($int) . ' ₽</p></div>
                  <div class="delete-cart"><a href="cart.php?id=' . $row["cart_id"] . '&action=delete" ><img src="/img/del.svg" /></a></div>
                  </div>


';
                  } while ($row = mysql_fetch_array($result));

                  echo '
 <h2 class="itog-price" align="right">Сумма заказа: <strong>' . group_numerals($all_price) . '</strong> руб</h2>
 <p align="right" class="button-next" ><a href="cart.php?action=confirm" >Продолжить оформление</a></p> 
 ';
               } else {
                  echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
               }



               break;

            case 'confirm':

               echo ' 
               <div class="block__step">  
               <div class="name__step">  
               <ul class="list__step">
               <li class="item__step"><a>Корзина товаров</a></li>
               <li class="item__step"><span>&rarr;</span></li>
               <li class="item__step"><a a class="active__link"> Контактная информация</a></li>
               <li class="item__step"><span>&rarr;</span></li>
               <li class="item__step"><a>Завершение заказа</a></li> 
               </ul>  
               </div>  
               <div>
               <p class="count__step">шаг 2 из 3</p>
               <a class="clear__cart" href="cart.php?action=clear">Очистить корзину</a> </div>
               </div>

 

   ';


               if ($_SESSION['order_delivery'] == "По почте") $chck1 = "checked";
               if ($_SESSION['order_delivery'] == "Курьером") $chck2 = "checked";
               if ($_SESSION['order_delivery'] == "Самовывоз") $chck3 = "checked";

               echo '

               <div class="delivery-block">
               <form  class="form-delivery" method="post">
               <ul id="info-radio">
               <h3 class="title-h3" >Способы доставки:</h3>
               <li class="radio-order">
               <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery1" value="По почте" ' . $chck1 . '  />
               <label class="label_delivery" for="order_delivery1">По почте</label>
               </li>
               <li class="radio-order">
               <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery2" value="Курьером" ' . $chck2 . ' />
               <label class="label_delivery" for="order_delivery2">Курьером</label>
               </li>
               <li class="radio-order">
               <input type="radio" name="order_delivery" class="order_delivery" id="order_delivery3" value="Самовывоз" ' . $chck3 . ' />
               <label class="label_delivery" for="order_delivery3">Самовывоз</label>
               </li>
               </ul>
               
               <ul id="info-order">
<h3 class="title-h3" >Информация для доставки:</h3>
';
               if ($_SESSION['auth'] != 'yes_auth') {
                  echo '
<li class="order-item"><label class="order-label" for="order_fio">ФИО</label><input class="order-input" type="text" name="order_fio" id="order_fio" value="' . $_SESSION["order_fio"] . '" /><span class="order_span_style" >Пример: Иванов Иван Иванович</span></li>
<li class="order-item"><label class="order-label" for="order_email">E-mail</label><input class="order-input" type="text" name="order_email" id="order_email" value="' . $_SESSION["order_email"] . '" /><span class="order_span_style" >Пример: ivanov@mail.ru</span></li>
<li class="order-item"><label class="order-label" for="order_phone">Телефон</label><input class="order-input" type="text" name="order_phone" id="order_phone" value="' . $_SESSION["order_phone"] . '" /><span class="order_span_style" >Пример: 8 950 100 12 34</span></li>
<li class="order-item"><label class="order-label" for="order_address">Адрес доставки</label><input class="order-input" type="text" name="order_address" id="order_address" value="' . $_SESSION["order_address"] . '" /></li>
';
               }
               echo '
<li class="order-item"><label class="order-label" for="order_note">Примечание</label><textarea class="order-text" name="order_note"  >' . $_SESSION["order_note"] . '</textarea><span class="order_span_style">Уточните информацию о заказе.<br /></span></li>
</ul>
<input class="next-step" type="submit" name="submitdata" id="confirm-button-next" value="Продолжить оформление" />
</form>

</div>

 ';

               break;

            case 'completion':

               echo ' 
               <div class="block__step">  
               <div class="name__step">  
               <ul class="list__step">
               <li class="item__step"><a class="active__link">Корзина товаров</a></li>
               <li class="item__step"><span>&rarr;</span></li>
               <li class="item__step"><a> Контактная информация</a></li>
               <li class="item__step"><span>&rarr;</span></li>
               <li class="item__step"><a a class="active__link">Завершение заказа</a></li> 
               </ul>  
               </div>  
               <div>
               <p class="count__step">шаг 3 из 3</p>
               <a class="clear__cart" href="cart.php?action=clear">Очистить корзину</a> </div>
               </div>

   <h3 class="title-end">Конечная информация:</h3>
   ';

               if ($_SESSION['auth'] == 'yes_auth') {
                  echo '
<ul id="list-info" >
<li class="info-item"><strong>Способ доставки:</strong>' . $_SESSION['order_delivery'] . '</li>
<li class="info-item"><strong>Email:</strong>' . $_SESSION['auth_email'] . '</li>
<li class="info-item"><strong>ФИО:</strong>' . $_SESSION['auth_surname'] . ' ' . $_SESSION['auth_name'] . ' ' . $_SESSION['auth_patronymic'] . '</li>
<li class="info-item"><strong>Адрес доставки:</strong>' . $_SESSION['auth_address'] . '</li>
<li class="info-item"><strong>Телефон:</strong>' . $_SESSION['auth_phone'] . '</li>
<li class="info-item"><strong>Примечание: </strong>' . $_SESSION['order_note'] . '</li>
</ul>

';
               } else {
                  echo '
<ul id="list-info" >
<li><strong>Способ доставки:</strong>' . $_SESSION['order_delivery'] . '</li>
<li>Email:' . $_SESSION['order_email'] . '</li>
<li><strong>ФИО:</strong>' . $_SESSION['order_fio'] . '</li>
<li><strong>Адрес доставки:</strong>' . $_SESSION['order_address'] . '</li>
<li><strong>Телефон:</strong>' . $_SESSION['order_phone'] . '</li>
<li><strong>Примечание: </strong>' . $_SESSION['order_note'] . '</li>
</ul>

';
               }
               echo '
<h2 class="itog-price">Итого: <strong>' . $itogpricecart . '</strong> руб</h2>
  <p  class="button-next" ><a href="#openModal"  >Оформить заказ</a></p>
  
  
 
 ';



               break;

            default:

               echo ' 
               <div class="block__step">  
               <div class="name__step">  
               <ul class="list__step">
               <li class="item__step"><a class="active__link">Корзина товаров</a></li>
               <li class="item__step"><span>&rarr;</span></li>
               <li class="item__step"><a> Контактная информация</a></li>
               <li class="item__step"><span>&rarr;</span></li>
               <li class="item__step"><a>Завершение заказа</a></li> 
               </ul>  
               </div>  
               <div>
               <p class="count__step">шаг 1 из 3</p>
               <a class="clear__cart" href="cart.php?action=clear">Очистить корзину</a> </div>
               </div>
  
';


               $result = mysql_query("SELECT * FROM cart,table_products WHERE cart.cart_ip = '{$_SERVER['REMOTE_ADDR']}' AND table_products.products_id = cart.cart_id_product", $link);

               if (mysql_num_rows($result) > 0) {
                  $row = mysql_fetch_array($result);

                  echo '  
  
   ';

                  do {

                     $int = $row["cart_price"] * $row["cart_count"];
                     $all_price = $all_price + $int;

                     if (strlen($row["image"]) > 0 && file_exists("./uploads_images/" . $row["image"])) {
                        $img_path = './uploads_images/' . $row["image"];
                        $max_width = 100;
                        $max_height = 100;
                        list($width, $height) = getimagesize($img_path);
                        $ratioh = $max_height / $height;
                        $ratiow = $max_width / $width;
                        $ratio = min($ratioh, $ratiow);

                        $width = intval($ratio * $width);
                        $height = intval($ratio * $height);
                     } else {
                        $img_path = "/images/noimages.jpeg";
                        $width = 120;
                        $height = 105;
                     }

                     echo '

                     <div class="block-list__cart">
                     <div class="img-cart">
                       <img src="' . $img_path . '" width="' . $width . '" height="' . $height . '" />
                     </div>
                  
                     <div class="title-cart">
                       <a class="head__product-name" href="">' . $row["title"] . '</a>
                       <p class="head__product-desc">' . $row["brand"] . '</p>
                       <p class="head__product-desc">' . $row["name_face"] . '</p>
                     </div>
                  
                     <div class="count-cart">
                        <ul class="input-count-style">
                        <li>
                          <p iid="' . $row["cart_id"] . '" class="count-minus">Удалить</p>
                        </li>
                        
                        <li>
                        <p><input class="input-cart" id="input-id' . $row["cart_id"] . '" iid="' . $row["cart_id"] . '" class="count-input" maxlength="3" type="text" value="' . $row["cart_count"] . '" /></p>
                        </li>
                  
                        <li>
                        <p iid="' . $row["cart_id"] . '" class="count-plus">Добавить</p>
                  </li>
                  
                  </ul>
                  </div>
                  
                  <div id="tovar' . $row["cart_id"] . '" class="price-product"><span class="span-count" >' . $row["cart_count"] . '</span> x <span>' . $row["cart_price"] . '</span><p class="price__product" price="' . $row["cart_price"] . '" >' . group_numerals($int) . ' ₽</p></div>
                  <div class="delete-cart"><a href="cart.php?id=' . $row["cart_id"] . '&action=delete" ><img src="/img/del.svg" /></a></div>
                  </div>
                  
                  <div id="bottom-cart-line"></div>
                 


';
                  } while ($row = mysql_fetch_array($result));

                  echo '
 <h2 class="itog-price" align="right">Сумма заказа: <strong>' . group_numerals($all_price) . '</strong> руб</h2>
 <p align="right" class="button-next" ><a href="cart.php?action=confirm" >Далее</a></p> 
 ';
               } else {
                  echo '<h3 id="clear-cart" align="center">Корзина пуста</h3>';
               }
               break;
         }

         ?>

      </div>


   </div>
   <script>
      document.addEventListener("DOMContentLoaded", function() {
         var scrollbar = document.body.clientWidth - window.innerWidth + 'px';
         console.log(scrollbar);
         document.querySelector('[href="#openModal"]').addEventListener('click', function() {
            document.body.style.overflow = 'hidden';
            document.querySelector('#openModal').style.marginLeft = scrollbar;
         });
         document.querySelector('[href="#close"]').addEventListener('click', function() {
            document.body.style.overflow = 'visible';
            document.querySelector('#openModal').style.marginLeft = '0px';
         });
      });
   </script>

</body>

</html>