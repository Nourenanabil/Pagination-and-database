<?
require"pagination.php"
?>

<html>
<head>
<title>COURSES</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <script defer src="https://use.fontawesome.com/releases/v5.13.0/js/all.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script type="text/javascript">
    $(document).ready(function(){
    load_data(1);
      function load_data(page,query=''){
        $.ajax({
          url  : "pagination.php",
          type : "POST",
          cache: false,
          data : {page:page,query:query},
          success:function(data)
        {
          $('#dynamic').html(data);
        }
      });
    }
    $(document).on('click', '.page-link', function(){
    var page = $(this).data('page_number');
    var query = $('#search_box').val();
    load_data(page, query);
  });

  $('#search').keyup(function(){
    var query = $('#search').val();
    load_data(1, query);
  });


});
</script>
</head>
<body>

<div class="container">

      <div class="card">
        <div class="card-body">
          <nav class="navbar navbar-inverse">
            <div class="container-fluid">
              <div class="navbar-header">
                <li class="navbar-brand"><i class="fas fa-chalkboard-teacher"></i>   COURSES <i class="fas fa-search"></i>   </li>

              </div>

              <form class="navbar-form navbar-left" >
                <div class="form-group">
                  <input type="text" id="search"class="form-control" rows="4" placeholder="Search">

                </div>

              </form>
            </div>
          </nav>

        </div>
      </div>
    </div>
    <div class="table-responsive" id="dynamic">

    </div>



</body>



</html>
