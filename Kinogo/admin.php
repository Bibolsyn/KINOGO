<?php
include('server.php');

// Добавление фильма
if (isset($_POST['add_movie'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $genre = mysqli_real_escape_string($db, $_POST['genre']);
    $description = mysqli_real_escape_string($db, $_POST['description']);

    // Загружаем изображение
    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    // Вставляем данные в базу
    $query = "INSERT INTO movies (name_movie, janyr, img, description) VALUES ('$name', '$genre', '$target', '$description')";
    mysqli_query($db, $query);

    header('Location: admin.php');
    exit();
}

// Удаление фильма
if (isset($_POST['delete_movie'])) {
    $movie_id = mysqli_real_escape_string($db, $_POST['movie_id']);

    // Удаляем запись из базы данных
    $query = "DELETE FROM movies WHERE id='$movie_id'";
    mysqli_query($db, $query);

    header('Location: admin.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Администратор - Управление Фильмами</title>
  <link rel="stylesheet" href="adminstlye.css">
</head>
<body>
  <h1>Администратор - Управление Фильмами</h1>

  <!-- Форма для добавления фильма -->
  <section>
    <h2>Добавить Фильм</h2>
    <form action="admin.php" method="POST" enctype="multipart/form-data">
      <label for="name">Название фильма:</label>
      <input type="text" id="name" name="name" required>

      <label for="genre">Жанр:</label>
      <input type="text" id="genre" name="genre" required>

      <label for="image">Изображение:</label>
      <input type="file" id="image" name="image" accept="image/*" required>

      <label for="description">Описание:</label>
      <textarea id="description" name="description" required></textarea>

      <button type="submit" name="add_movie">Добавить</button>
    </form>
  </section>

  <!-- Таблица для удаления фильмов -->
  <section>
    <h2>Список Фильмов</h2>
    <table border="1">
      <thead>
        <tr>
          <th>ID</th>
          <th>Название</th>
          <th>Жанр</th>
          <th>Изображение</th>
          <th>Действие</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Подключаем базу данных
        include('server.php');
        $result = mysqli_query($db, "SELECT * FROM movies");
        while ($movie = mysqli_fetch_assoc($result)) {
          echo "<tr>
                  <td>{$movie['id']}</td>
                  <td>{$movie['name_movie']}</td>
                  <td>{$movie['janyr']}</td>
                  <td><img src='{$movie['img']}' alt='{$movie['name_movie']}' width='100'></td>
                  <td>
                    <form action='admin.php' method='POST'>
                      <input type='hidden' name='movie_id' value='{$movie['id']}'>
                      <button type='submit' name='delete_movie'>Удалить</button>
                    </form>
                  </td>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </section>
</body>
</html>
