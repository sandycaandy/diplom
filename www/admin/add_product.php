<?php
session_start();
if ($_SESSION['auth_admin'] == "yes_auth") {

   if (isset($_GET["logout"])) {
      unset($_SESSION['auth_admin']);
      header("Location: login.php");
   }

   $_SESSION['urlpage'] = "<a href='index.php' >Главная</a> \ <a href='tovar.php' >Товары</a> \ <a>Добавление товара</a>";

   include("include/db_connect.php");
   include("include/functions.php");

   if ($_POST["submit_add"]) {
      if ($_SESSION['add_tovar'] == '1') {

         $error = array();

         // Проверка полей

         if (!$_POST["form_title"]) {
            $error[] = "Укажите название товара";
         }

         if (!$_POST["form_price"]) {
            $error[] = "Укажите цену";
         }

         if (!$_POST["form_category"]) {
            $error[] = "Укажите категорию";
         } else {
            $result = mysql_query("SELECT * FROM category WHERE id='{$_POST["form_category"]}'", $link);
            $row = mysql_fetch_array($result);
            $selectbrand = $row["brand"];
         }

         if (!$_POST["form_cloth"]) {
            $error[] = "Укажите ткань изделия";
         } else {
            $result1 = mysql_query("SELECT * FROM type_cloth WHERE id='{$_POST["form_cloth"]}'", $link);
            $row1 = mysql_fetch_array($result1);
            $selectbrand1 = $row1["name_face"];
         }

         if (!$_POST["form_size"]) {
            $error[] = "Укажите размер изделия";
         } else {
            $result2 = mysql_query("SELECT * FROM type_size WHERE id='{$_POST["form_size"]}'", $link);
            $row2 = mysql_fetch_array($result2);
            $selectbrand2 = $row2["name_size"];
         }

         // Проверка чекбоксов

         if ($_POST["chk_visible"]) {
            $chk_visible = "1";
         } else {
            $chk_visible = "0";
         }

         if ($_POST["chk_new"]) {
            $chk_new = "1";
         } else {
            $chk_new = "0";
         }

         if ($_POST["chk_leader"]) {
            $chk_leader = "1";
         } else {
            $chk_leader = "0";
         }

         if ($_POST["chk_sale"]) {
            $chk_sale = "1";
         } else {
            $chk_sale = "0";
         }


         if (count($error)) {
            $_SESSION['message'] = "<p id='form-error'>" . implode('<br />', $error) . "</p>";
         } else {

            mysql_query("INSERT INTO table_products(title,price,brand,seo_words,seo_description,mini_description,description,old_price,new,leader,sale,visible,type_tovara,brand_id,skin_id, name_face, size_id, name_size)
						VALUES(						
                            '" . $_POST["form_title"] . "',
                            '" . $_POST["form_price"] . "',
                            '" . $selectbrand . "',
                            '" . $_POST["form_seo_words"] . "',
                            '" . $_POST["form_seo_description"] . "',
                            '" . $_POST["txt1"] . "',
                            '" . $_POST["txt2"] . "',
                            '" . $_POST["txt3"] . "',
                            '" . $chk_new . "',
                            '" . $chk_leader . "',
                            '" . $chk_sale . "',
                            '" . $chk_visible . "',
                            '" . $_POST["form_type"] . "',
                            '" . $_POST["form_category"] . "',
                            '" . $_POST["form_cloth"] . "',
                            '" . $selectbrand1 . "',
                            '" . $_POST["form_size"] . "'  ,
                            '" . $selectbrand2 . "'                        
						)", $link);

            $_SESSION['message'] = "<p id='form-success'>Товар успешно добавлен!</p>";
            $id = mysql_insert_id();

            if (empty($_POST["upload_image"])) {
               include("actions/upload-image.php");
               unset($_POST["upload_image"]);
            }

            if (empty($_POST["galleryimg"])) {
               include("actions/upload-gallery.php");
               unset($_POST["galleryimg"]);
            }
         }
      } else {
         $msgerror = 'У вас нет прав на добавление товаров!';
      }
   }

?>
   <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
   <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

   <head>
      <meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
      <link href="css/reset.css" rel="stylesheet" type="text/css" />
      <link href="css/style.css" rel="stylesheet" type="text/css" />
      <link href="jquery_confirm/jquery_confirm.css" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>
      <script type="text/javascript" src="js/script.js"></script>
      <script type="text/javascript" src="./ckeditor/ckeditor.js"></script>
      <title>Панель Управления</title>
   </head>

   <body>
      <div id="block-body">
         <?php
         include("include/block-header.php");
         ?>
         <div id="block-content">
            <div id="block-parameters">
               <p id="title-page">Добавление товара</p>
            </div>
            <?php
            if (isset($msgerror)) echo '<p id="form-error" align="center">' . $msgerror . '</p>';

            if (isset($_SESSION['message'])) {
               echo $_SESSION['message'];
               unset($_SESSION['message']);
            }

            if (isset($_SESSION['answer'])) {
               echo $_SESSION['answer'];
               unset($_SESSION['answer']);
            }
            ?>

            <form enctype="multipart/form-data" method="post">
               <ul id="edit-tovar">

                  <li>
                     <label>Название товара</label>
                     <input type="text" name="form_title" />
                  </li>

                  <li>
                     <label>Цена</label>
                     <input type="text" name="form_price" />
                  </li>

                  <li>
                     <label>Ключевые слова</label>
                     <input type="text" name="form_seo_words" />
                  </li>

                  <li>
                     <label>Краткое описание</label>
                     <textarea name="form_seo_description"></textarea>
                  </li>
                  <li>
                     <label>Тип товара</label>
                     <select name="form_type" id="type" size="1">

                        <option value="linen">Постельное белье</option>
                        <option value="cover">Покрывала</option>
                        <option value="plaids">Пледы</option>

                     </select>
                  </li>

                  <li>
                     <label>Категория</label>
                     <select name="form_category" size="7">

                        <?php
                        $category = mysql_query("SELECT * FROM category", $link);

                        if (mysql_num_rows($category) > 0) {
                           $result_category = mysql_fetch_array($category);
                           do {

                              echo '
  
  <option value="' . $result_category["id"] . '" >' . $result_category["brand"] . ' -- ' . $result_category["type"] . '</option>
  
  ';
                           } while ($result_category = mysql_fetch_array($category));
                        }
                        ?>

                     </select>
                  </li>

                  <li>
                     <label>Размер</label>
                     <select name="form_size" size="7">

                        <?php
                        $type_size = mysql_query("SELECT * FROM type_size", $link);

                        if (mysql_num_rows($type_size) > 0) {
                           $result_type_size = mysql_fetch_array($type_size);
                           do {

                              echo '
  
  <option value="' . $result_type_size["id"] . '" >' . $result_type_size["name_size"] . ' -- ' . $result_type_size["type_size"] . '</option>
  
  ';
                           } while ($result_type_size = mysql_fetch_array($type_size));
                        }
                        ?>

                     </select>
                  </li>

                  <li>
                     <label>Ткань</label>
                     <select name="form_cloth" size="7">

                        <?php
                        $type_cloth = mysql_query("SELECT * FROM type_cloth", $link);

                        if (mysql_num_rows($type_cloth) > 0) {
                           $result_type_cloth = mysql_fetch_array($type_cloth);
                           do {

                              echo '
  
  <option value="' . $result_type_cloth["id"] . '" >' . $result_type_cloth["name_face"] . ' -- ' . $result_type_cloth["type_cloth"] . ' </option>
  
  ';
                           } while ($result_type_cloth = mysql_fetch_array($type_cloth));
                        }
                        ?>

                     </select>
                  </li>
               </ul>
               <label class="stylelabel">Основная картинка</label>

               <div id="baseimg-upload">
                  <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
                  <input type="file" name="upload_image" />

               </div>

               <h3 class="h3click">Краткое описание товара</h3>
               <div class="div-editor1">
                  <textarea id="editor1" name="txt1" cols="100" rows="20"></textarea>
                  <script type="text/javascript">
                     var ckeditor1 = CKEDITOR.replace("editor1");
                     AjexFileManager.init({
                        returnTo: "ckeditor",
                        editor: ckeditor1
                     });
                  </script>
               </div>
               <div>
                  <h3 class="">Объем товара</h3>
                  <input type="text" name="txt2" />
               </div>

               <div>
                  <h3 class="">Старая цена</h3>
                  <input type="text" name="txt3" />
               </div>

               <label class="stylelabel">Галерея картинок</label>

               <div id="objects">

                  <div id="addimage1" class="addimage">
                     <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
                     <input type="file" name="galleryimg[]" />
                  </div>

               </div>

               <p id="add-input">Добавить</p>

               <h3 class="h3title">Настройки товара</h3>
               <ul id="chkbox">
                  <li><input type="checkbox" name="chk_visible" id="chk_visible" /><label for="chk_visible">Показывать товар</label></li>
                  <li><input type="checkbox" name="chk_new" id="chk_new" /><label for="chk_new">Новый товар</label></li>
                  <li><input type="checkbox" name="chk_leader" id="chk_leader" /><label for="chk_leader">Популярный товар</label></li>
                  <li><input type="checkbox" name="chk_sale" id="chk_sale" /><label for="chk_sale">Товар со скидкой</label></li>
               </ul>


               <p align="right"><input type="submit" id="submit_form" name="submit_add" value="Добавить товар" /></p>
            </form>


         </div>
      </div>
   </body>

   </html>
<?php
} else {
   header("Location: login.php");
}
?>