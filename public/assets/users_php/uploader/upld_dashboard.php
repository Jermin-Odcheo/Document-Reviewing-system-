<html>
<head>
   <title>
      TMDD - Reviewer Dashboard
   </title>
   <link crossorigin="anonymous" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" rel="stylesheet" />
   <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
   <link href="../../styles/admin.css" rel="stylesheet">
   <link rel="icon" type="png" href="../../img/SLU Logo.png">
</head>

<body>
   <header>
      <?php include "./upld_header.php" ?>
   </header>
   <div class="d-flex">
      <div class="flex-grow-1">
         <div class="content">
            <h2>
               Dashboard
            </h2>
            <div class="d-flex justify-content-around">
               <div class="card">
                  <h3>
                     Pending Reviews
                  </h3>
                  <h1>
                     8
                  </h1>
               </div>
               <div class="card">
                  <h3>
                     Completed Reviews
                  </h3>
                  <h1>
                     20
                  </h1>
               </div>
               <div class="card">
                  <h3>
                     Unreviewed Documents
                  </h3>
                  <h1>
                     3
                  </h1>
               </div>
            </div>
         </div>
      </div>
   </div>
   <footer class="bottom">
        <?php include "../general/footer.php"?>
    </footer>
</body>

</html>