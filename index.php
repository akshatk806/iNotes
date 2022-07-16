<!-- Making a notes Web using PHP and performing CRUD operations (Lecture 32) -->

<?php

$insert = false;
$update = false;
$delete = false;


// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$database = "notes";
$conn = mysqli_connect($servername, $username, $password, $database);
if (!$conn) {
  die("Sorry!!! we failed to connect " . mysqli_connect_error());
}

// Delete the note
if (isset($_GET['delete'])) {
  $sno = $_GET['delete'];
  $delete = true;
  $sql = "DELETE FROM `notes` WHERE `sno` = $sno";
  $result = mysqli_query($conn, $sql);
  header("Location:index.php");
}



if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['snoEdit'])) {
    // Update the record
    $sno = $_POST["snoEdit"];
    $title = $_POST["titleEdit"];
    $description = $_POST["descriptionEdit"];

    // Sql query to be executed
    $sql = "UPDATE `notes` SET `title` = '$title' , `description` = '$description' WHERE `notes`.`sno` = $sno";
    $result = mysqli_query($conn, $sql);
    if ($result) {
      $update = true;
      header("Location:index.php");
    } else {
      echo "We could not update the record successfully";
    }
  } else {
    if (isset($_POST)) {

      $title = $_POST["title"];
      $description = $_POST["description"];

      // Sql query to be executed
      $sql = "INSERT INTO `notes` (`title`, `description`) VALUES ('$title', '$description')";
      $result = mysqli_query($conn, $sql);


      if ($result) {
        $insert = true;
        echo "<script>alert('Note added Successfully')</script>";
        header("Location:/php/CRUD/index.php"); // Data is storing automatically in database when i refresh the page, so to avoid this we use header()
      } else {
        echo "The record was not inserted successfully because of this error ---> " . mysqli_error($conn);
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" type="image/x-icon" href="fav.png">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous">
  </script>
  <link rel="shortcut icon" href="favicon.png" type="image/x-icon">

  <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <title>PHP CRUD - iNotes</title>
</head>

<body>

  <!-- Edit -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Update Note</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="my-3 form" action="/php/CRUD/index.php" method="post">
          <div class="modal-body">
            <input type="hidden" name="snoEdit" id="snoEdit">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" name="titleEdit" class=" form-control" id="titleEdit">
            </div>
            <div class=" mb-3">
              <label for="description" class="form-label">Note Description</label>
              <textarea class="form-control" name="descriptionEdit" id="descriptionEdit" rows=" 3"></textarea>
            </div>
            <div class="modal-footer d-block mr-auto">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
        </form>
      </div>
    </div>
  </div>
  </div>



  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">iNotes</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="#">Home</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>



  <!-- Paste that here -->



  <div class="container">
    <h2 class="my-1">Add a Note</h2>
    <form class="my-3 form" action="/php/CRUD/index.php" method="post">
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" name="title" class="form-control" id="title">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Note Description</label>
        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Add Note</button>
    </form>

  </div>



  <div class="container">
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col">S No.</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $sql = "SELECT * FROM `notes`";
        $result = mysqli_query($conn, $sql);
        $sno = 0;
        while ($row = mysqli_fetch_assoc($result)) {
          $sno = $sno + 1;
          echo "<tr>
                <th scope='row'>" . $sno . "</th>
                <td>" . $row['title'] . "</td>
                <td>" . $row['description'] . "</td>
                <td><button class='edit btn btn-sm btn-primary' id=" . $row['sno'] . ">Edit</button> <button class='delete btn btn-sm btn-primary' id=d" . $row['sno'] . ">Delete</button>
                </tr>";
        }
        ?>
      </tbody>
    </table>
  </div>
  <hr>

</body>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous">
</script>
<!-- Data Tables -->
<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

<script>
  $(document).ready(function() {
    $('#myTable').DataTable();
  });
</script>

<script>
  edits = document.getElementsByClassName('edit');
  Array.from(edits).forEach((element) => {
    element.addEventListener('click', (event) => {
      tr = event.target.parentNode.parentNode;
      title = tr.getElementsByTagName("td")[0].innerText;
      description = tr.getElementsByTagName("td")[1].innerText;
      titleEdit.value = title;
      descriptionEdit.value = description;
      snoEdit.value = event.target.id;
      $('#editModal').modal('toggle');
    })
  })

  deletes = document.getElementsByClassName('delete');
  Array.from(deletes).forEach((element) => {
    element.addEventListener("click", (event) => {
      sno = event.target.id.substr(1);

      if (confirm("Are you sure you want to delete this note!")) {
        console.log("yes");
        window.location = `/php/CRUD/index.php?delete=${sno}`;
        // TODO: Create a form and use post request to submit a form
      } else {
        console.log("no");
      }
    })
  })
</script>

</html>