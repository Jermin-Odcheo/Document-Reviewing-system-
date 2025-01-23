<html>
 <head>
  <title>
   TMDD - Document Reviewer
  </title>
  <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet"/>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
  <link href="../../styles/admin.css" rel="stylesheet">
  <link rel="icon" type="png" href="../../img/SLU Logo.png"> 
</head>
 <header>
    <?php include "./admin_header.php";?>
    </header>
    <div class="d-flex">
      <div class="content flex-grow-1">
    <h2>
     Logs Manager
    </h2>
    <div class="d-flex mb-3">
     <input class="form-control me-2" placeholder="Search Name" type="text"/>
     <select class="form-select me-2">
        <option>
            All Roles
        </option>
     </select>
     <select class="form-select me-2">
        <option>
            All Types
        </option>
     </select>
     <button class="btn btn-secondary me-2">
      Apply
     </button>
     <button class="btn btn-secondary">
      Clear All
     </button>
    </div>

    <div class="table-container">
     <h3>
      Logs Manager
     </h3>
     <table class="table table-bordered">
      <thead>
       <tr>
        <th>
         Username
        </th>
        <th>
         First Name
        </th>
        <th>
         Last Name
        </th>
        <th>
         Email
        </th>
        <th>
         Role
        </th>
        <th>
         Password
        </th>
        <th>
         Avatar
        </th>
        <th>
         Actions
        </th>
       </tr>
      </thead>

      <tbody>
       <tr>
        <td>
         admin
        </td>
        <td>
         ad
        </td>
        <td>
         min
        </td>
        <td>
         admin@gmail.com
        </td>
        <td>
         Admin
        </td>
        <td>
         ********
        </td>
        <td>
         <img alt="Avatar" class="rounded-circle" height="30" src="https://storage.googleapis.com/a1aa/image/vVdqGIeqK40mCKmtiPme1idBr1FVRjeWXA48LzNRZ6L7SpPoA.jpg" width="30"/>
         Avatar
        </td>
        <td>
         <button class="btn btn-primary btn-sm">
          Edit
         </button>
         <button class="btn btn-danger btn-sm">
          Delete
         </button>
        </td>
       </tr>
        <button class="btn btn-primary">
      + Add User
     </button>
    </div>
   </div>
  </div>
<footer>
<?php include "../general/footer.php";?>
</footer>
 </body>
</html>
