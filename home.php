<?php
include 'connect.php';

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name);
   $artist = $_POST['artist'];
   $artist = filter_var($artist);

   if (!isset($artist)) {
      $artist = '';
   }

   $album = $_FILES['album']['name'];
   $album = filter_var($album);
   $album_size = $_FILES['album']['size'];
   $album_tmp_name = $_FILES['album']['tmp_name'];
   $album_folder = 'uploaded_album/' . $album;

   if (isset($album)) {
      if ($album_size > 2000000) {
         $message[] = 'album size is too large!';
      } else {
         move_uploaded_file($album_tmp_name, $album_folder);
      }
   } else {
      $album = '';
   }

   $music = $_FILES['music']['name'];
   $music = filter_var($music);
   $music_size = $_FILES['music']['size'];
   $music_tmp_name = $_FILES['music']['tmp_name'];
   $music_folder = 'uploaded_music/' . $music;

   if ($music_size > 100000000) {
      $message[] = 'music size is too large!';
   } else {
      $upload_music = $conn->prepare("INSERT INTO `songs`(name, artist, album, music) VALUES(?,?,?,?)");
      $upload_music->execute([$name, $artist, $album, $music]);
      move_uploaded_file($music_tmp_name, $music_folder);
      $message[] = 'new music uploaded!';
   }
}

if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message" id="messageBox">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <section class="playlist">
      <div class="heading">
         <h3>music playlist</h3>
         <button class="btn" id="openModal">upload music</button>
      </div>
      <button id="shuffleBtn" class="btn">
         <i class="fas fa-random"></i> &nbsp;Shuffle
      </button>
      <br>
      <div class="box-container">
         <?php
         $select_songs = $conn->prepare("SELECT * FROM `songs`");
         $select_songs->execute();
         if ($select_songs->rowCount() > 0) {
            while ($fetch_song = $select_songs->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="box">
                  <?php if ($fetch_song['album'] != '') { ?>
                     <img src="uploaded_album/<?= $fetch_song['album']; ?>" alt="album" class="album">
                  <?php } else { ?>
                     <img src="images/disc.png" alt="default album" class="album">
                  <?php } ?>
                  <div class="info">
                     <div class="name"><?= $fetch_song['name']; ?></div>
                     <div class="artist"><?= $fetch_song['artist']; ?></div>
                  </div>
                  <div class="controls">
                     <div class="play" data-src="uploaded_music/<?= $fetch_song['music']; ?>"><i class="fas fa-play"></i><span>Play</span></div>
                     <a href="uploaded_music/<?= $fetch_song['music']; ?>" download><i class="fas fa-download"></i><span>Download</span></a>
                  </div>
               </div>
         <?php
            }
         }
         ?>
      </div>

   </section>
   <div id="uploadModal" class="modal">
      <div class="modal-content form-container"> <span class="close">&times;</span>
         <h3 class="heading">upload music</h3>
         <form action="" method="POST" enctype="multipart/form-data">
            <p>music name <span>*</span></p> <input type="text" name="name" placeholder="enter music name" required maxlength="100" class="box">
            <p>artist name</p> <input type="text" name="artist" placeholder="enter artist name" maxlength="100" class="box">
            <p>select music <span>*</span></p> <input type="file" name="music" class="box" required accept="audio/*">
            <p>select album</p> <input type="file" name="album" class="box" accept="image/*"> <input type="submit" value="upload music" class="btn" name="submit"> <a href="home.php" class="option-btn">go to home</a>
         </form>
      </div>
   </div>

   <div class="music-player">
      <i class="fas fa-times" id="close"></i>
      <div class="box">
         <img src="" class="album" alt="">
         <div class="name"></div>
         <div class="artist"></div>
         <audio src="" controls class="music"></audio>
      </div>
   </div>

   <script src="js/script.js"></script>
   <script>
      document.getElementById('openModal').onclick = function() {
         document.getElementById('uploadModal').style.display = "block";
      }

      document.getElementsByClassName('close')[0].onclick = function() {
         document.getElementById('uploadModal').style.display = "none";
      }

      window.onclick = function(event) {
         if (event.target == document.getElementById('uploadModal')) {
            document.getElementById('uploadModal').style.display = "none";
         }
      }

      setTimeout(function() {
         var messageBox = document.getElementById('messageBox');
         if (messageBox) {
            messageBox.remove();
         }
      }, 3000);
   </script>
</body>

</html>