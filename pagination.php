<?php
  $con = new PDO("mysql:host=localhost; dbname=courses", "root", "");

	function get_total_row($con)
	{
	  $query = "SELECT c.course_name,c.course_description,d.department_name,p.professor_name FROM Course c INNER JOIN Department d on d.department_id=c.department_id INNER JOIN Professor p on p.professor_id=c.professor_id";
	  $statement = $con->prepare($query);
	  $statement->execute();
	  return $statement->rowCount();
	}

	$total_record = get_total_row($con);

$record_per_page=5;
$start_from=0;
$page=1;
$output='';
if (isset($_POST["page"]))
{
  $page=$_POST["page"];
	$start_from=(int)($page-1)*$record_per_page;
}
else
{
  $start_from=0;
}
$query = "SELECT c.course_name,c.course_description,d.department_name,p.professor_name FROM Course c INNER JOIN Department d on d.department_id=c.department_id INNER JOIN Professor p on p.professor_id=c.professor_id";
if(isset($_POST["query"])){
    // Prepare a select statement
		$query .= '
	  WHERE REPLACE(c.course_name," ","") LIKE  "%'.str_replace(' ', '%', $_POST['query']).'%"
    OR REPLACE(c.course_description," ","") LIKE  "%'.str_replace(' ', '%', $_POST['query']).'%"
		OR REPLACE(d.department_name," ","") LIKE  "%'.str_replace(' ', '%', $_POST['query']).'%"
		OR REPLACE(p.professor_name," ","") LIKE  "%'.str_replace(' ', '%', $_POST['query']).'%"
	  ';


	}
$filter_query = $query . 'LIMIT '.$start_from.', '.$record_per_page.'';
$statement = $con->prepare($query);
$statement->execute();
$total_data = $statement->rowCount();

$statement = $con->prepare($filter_query);
$statement->execute();
$result = $statement->fetchAll();

$total_filter_data = $statement->rowCount();

$output = '
<label>Total Records - '.$total_data.'</label>
<table class="table table-striped table-bordered">
		    <thead>
		        <tr>
			   <th>course_name</th>
			   <th>course_description</th>
			   <th>department_name</th>
			   <th>professor_name</th>
	                 </tr>
		    </thead>';
				if($total_data > 0)
{
  foreach($result as $row)
  {
    $output .= '
    <tr>
      <td>'.$row["course_name"].'</td>
      <td>'.$row["course_description"].'</td>
			<td>'.$row["department_name"].'</td>
			<td>'.$row["professor_name"].'</td>
    </tr>
    ';
  }
}
else
{
  $output .= '
  <tr>
    <td colspan="4" align="center">No results for " '.$_POST["query"].' ".</td>

  </tr>
  ';
}
$output .= '
</table>
<br />
<div align="center">
  <ul class="pagination">
';
$total_links = ceil($total_data/$record_per_page);
$previous_link = '';
$next_link = '';
$page_link = '';


$page_array[]=0;
if($total_links > 4)
{
  if($page < 5)
  {
    for($count = 1; $count <= 5; $count++)
    {
      $page_array[] = $count;
    }
    $page_array[] = '...';
    $page_array[] = $total_links;
  }
  else
  {
    $end_limit = $total_links - 5;
    if($page > $end_limit)
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $end_limit; $count <= $total_links; $count++)
      {
        $page_array[] = $count;
      }
    }
    else
    {
      $page_array[] = 1;
      $page_array[] = '...';
      for($count = $page - 1; $count <= $page + 1; $count++)
      {
        $page_array[] = $count;
      }
      $page_array[] = '...';
      $page_array[] = $total_links;
    }
  }
}
else
{
  for($count = 1; $count <= $total_links; $count++)
  {
    $page_array[] = $count;
  }
}

for($count = 0; $count < count($page_array); $count++)
{
  if($page == $page_array[$count])
  {
    $page_link .= '
    <li class="page-item active">
      <a class="page-link" href="#">'.$page_array[$count].' <span class="sr-only">(current)</span></a>
    </li>
    ';

    $previous_id = $page_array[$count] - 1;
    if($previous_id > 0)
    {
      $previous_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$previous_id.'">Previous</a></li>';
    }
    else
    {
      $previous_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Previous</a>
      </li>
      ';
    }
    $next_id = $page_array[$count] + 1;
    if($next_id > $total_links)
    {
      $next_link = '
      <li class="page-item disabled">
        <a class="page-link" href="#">Next</a>
      </li>
        ';
    }
    else
    {
      $next_link = '<li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$next_id.'">Next</a></li>';
    }
  }
  else
  {
    if($page_array[$count] == '...')
    {
      $page_link .= '
      <li class="page-item disabled">
          <a class="page-link" href="#">...</a>
      </li>
      ';
    }
    else
    {
      $page_link .= '
      <li class="page-item"><a class="page-link" href="javascript:void(0)" data-page_number="'.$page_array[$count].'">'.$page_array[$count].'</a></li>
      ';
    }
  }
}

$output .= $previous_link . $page_link . $next_link;
$output .= '
  </ul>

</div>
';

echo $output;

?>
