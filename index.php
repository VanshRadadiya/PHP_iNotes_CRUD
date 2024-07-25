<?php
$insert_query = false;
$delete_query = false;
$update_query = false;
$srno = 0;
$con = mysqli_connect('localhost', 'root', '', 'inotes_php');

if (isset($_GET['d_id'])) {
  $id = $_GET['d_id'];
  $delete_query = "DELETE FROM notes WHERE srno = $id";
  mysqli_query($con, $delete_query);
  header('location:index.php?delete_query=true');
}

if (isset($_POST['save'])) {

  if (isset($_POST['srnoEdit'])) {
    $srnoEdit = $_POST['srnoEdit'];
    $editTitle = $_POST['editTitle'];
    $editDescription = $_POST['editDescription'];
    $update_query = "UPDATE notes SET title = '$editTitle', description = '$editDescription' WHERE srno = '$srnoEdit'";
    mysqli_query($con, $update_query);
  } else {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $insert_query = "INSERT INTO notes (title, description) VALUES ('$title', '$description')";
    mysqli_query($con, $insert_query);
  }
}

$select_query = "SELECT * FROM notes";
$notes_data = mysqli_query($con, $select_query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>iNotes - Notes taking make easy</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />

  <!-- DataTable CSS -->
  <link rel="stylesheet" href="//cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css" />
</head>



<body>

  <!-- Modal Edit-->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="editModalLabel">Edit a Note</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form class="" method="post" action="/PHP/CRUD/index.php">
          <div class="modal-body">
            <input type="hidden" name="srnoEdit" id="srnoEdit">
            <div class="mb-3">
              <label for="title" class="form-label">Note Title</label>
              <input type="text" class="form-control" name="editTitle" id="editTitle" aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
              <label for="description" class="form-label">Note Description</label>
              <textarea class="form-control" name="editDescription" id="editDescription" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" name="save" class="btn btn-primary">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg bg-dark">
    <div class="container-fluid">
      <a class="navbar-brand text-secondary fw-bold" href="#">iNotes</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active text-light" aria-current="page" href="#">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="#">About</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-light" href="#">Contact Us</a>
          </li>
        </ul>
        <form class="d-flex" role="search">
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Search</button>
        </form>
      </div>
    </div>
  </nav>

  <!-- Alert For Insert Data / Update Data / Delete Data -->

  <?php if ($insert_query) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> Your note has been <b>Inserted</b> successfully.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php } ?>

  <?php if ($update_query) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> Your note has been <b>Updated</b> successfully.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php } ?>

  <?php if (isset($_GET['delete_query'])) { ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>Success!</strong> Your note has been <b>Deleted</b> successfully.
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    
  <?php } ?>

  <!-- iNotes Form -->
  <div class="container">
    <form class="my-5" method="post" action="/PHP/CRUD/index.php">
      <h2>Add a Note</h2>
      <div class="mb-3">
        <label for="title" class="form-label">Note Title</label>
        <input type="text" class="form-control" name="title" id="title" aria-describedby="emailHelp">
      </div>
      <div class="mb-3">
        <label for="description" class="form-label">Note Description</label>
        <textarea class="form-control" name="description" id="description" rows="3"></textarea>
      </div>
      <button type="submit" name="save" class="btn btn-primary">Add Note</button>
    </form>
  </div>

  <!-- iNotes View -->

  <div class="container">
    <table class="table" id="myTable">
      <thead>
        <tr>
          <th scope="col" class="text-start">#</th>
          <th scope="col">Title</th>
          <th scope="col">Description</th>
          <th scope="col">Action</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_array($notes_data)) {
          $srno = $srno + 1;
          ?>
          <tr>
            <td class="text-start"><?php echo $srno; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['description']; ?></td>
            <td>
              <button type="button" class='edit btn btn-outline-primary' id="<?php echo $row['srno']; ?>">Edit</button>
              <a href="index.php?d_id=<?php echo $row['srno']; ?>"><button type="button"
                  class='delete btn btn-outline-danger'>Delete</button></a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

  <!-- Bootstrap Js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>

  <!-- DataTable Js -->\
  <script src="//cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>

  <script>
    let table = new DataTable('#myTable');
  </script>

  <script>
    edits = document.getElementsByClassName('edit');
    Array.from(edits).forEach((element) => {
      element.addEventListener("click", (e) => {
        tr = e.target.parentNode.parentNode;
        title = tr.getElementsByTagName("td")[1].innerText;
        description = tr.getElementsByTagName("td")[2].innerText;
        // console.log(title, description);   
        editTitle.value = title;
        editDescription.value = description;
        srnoEdit.value = e.target.id;
        console.log(e.target.id);
        $('#editModal').modal('toggle');
      })
    })
  </script>



</body>

</html>