<?php
session_start();

require_once "./config/database.php";
echo "login dans side = " . $_SESSION['loggued_on_user'];
$images_name = null;

$page_num = 1;
if (isset($_GET['page_num']))
  $page_num = $_GET['page_num'];

// Connection to database
$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);

// Getting all the images
$sql = "SELECT `img_name` FROM Photos
        WHERE display = 1
        ORDER BY id_photos DESC;";

foreach ($dbh->query($sql) as $row)
  $images_name[] = $row['img_name'];

  // Getting like count by image
  $sql = "SELECT Likes.img_name, COUNT(Likes.img_name) AS 'nb_of_likes' FROM Likes
          JOIN Photos ON Photos.img_name = Likes.img_name
          WHERE Photos.display = 1
          GROUP BY Likes.img_name";

  foreach ($dbh->query($sql) as $row)
    $images_likes[$row['img_name']] = $row['nb_of_likes'];

  // var_dump($images_likes);

if (empty($images_name))
  return ;

$num_of_img = count($images_name);
?>

<!-- Section w/ all the photos -->
<ul>
  <?php
    for ($i = $page_num * 3 - 3; $i < $page_num * 3 && $i < $num_of_img; $i++) {
// Code for image
      echo "<li>
              <img id=" . $images_name[$i] . " src='http://localhost:8080/Camagru/src/img/usr/" . $images_name[$i] . ".png'/><br>
              <div id='like_image_" . $images_name[$i] . "' onclick='like_image(\"" . $images_name[$i] . "\")'>
                Like (" . ($images_likes[$images_name[$i]]) . ")
              </div>
              Comments"; ?>
              <div class="comments">
                <form action="./comments.php" method="post" id="post_comment">
                  <input type="text" name="comment_text" value="" required><br>
                  <input type="submit" value="Comment">
                </form>
              </div>"
              <?php
    echo "</li><br>";
    }
  ?>
</ul>
<table id="navigation">
  <tr>
    <?php
    if ($num_of_img > 3)
        for ($i = 1; $i <= ($num_of_img + 3 - $num_of_img % 3) / 3; $i++)
          echo "<td><a onclick='carousel_page($i)' href='#'>" . $i . "</a></td>";
      ?>
  </tr>
</table>
