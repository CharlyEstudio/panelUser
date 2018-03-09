<?php
// NOTE send params json_encode on button and send all params, check on class.file.php construct some params
// and at this point add: page and limit ($limit from class.file.php) but for clean code set here.
// Calculate page and add to json to be process on php. Js is the intermediary to send params, backend process and show response.

$pagination = "<div class='text-center'>";
$pagination = "<nav aria-label='Page navigation example'>";
$pagination .= "<ul class='pagination justify-content-center'>";
$paramsPagination["limit"] = $limitPages;

  if($totalPages > 1) {
    // First page. If user is on different page to main (major)
    if($currentPage != 1) {

      $paramsPagination["page"] = $currentPage - 1;
      $paramsSendPagination = json_encode($paramsPagination);

      $pagination .= "<li class='page-item'>";
      $pagination .=    "<a href='#' class='page-link' onclick='pagination($paramsSendPagination)'>";
      $pagination .=      "<span class='fa fa-caret-left' aria-hidden='true'> </span>";
      $pagination .=     "</a>";
      $pagination .=   "</li>";
    }

    for($i = $initLimitPages ; $i <= $limitPages; $i++) {
      if ($currentPage == $i) {
        // if I show the index of the current page, not post link
        $pagination .= "<li class='page-item'><a class='page-link' style='color: #F89D97 !important'>$currentPage</a></li>";
      } else {
        // if the index does not correspond to the currently displayed page,
        // put the link to go to that page

        $paramsPagination["page"] = $i;
        $paramsSendPagination = json_encode($paramsPagination);

        $pagination .= "<li class='page-item'>";
        $pagination .=    "<a class='page-link' href='#' onclick='pagination($paramsSendPagination)'>" .$i. "</a>";
        $pagination .=  "</li>";
      }
    }
    // if user is not on last page
    if($currentPage != $totalPages) {

      $paramsPagination["page"] = $currentPage + 1;
      $paramsSendPagination = json_encode($paramsPagination);

      $pagination .= "<li class='page-item'>";
      $pagination .=    "<a class='page-link' href='#' onclick='pagination($paramsSendPagination)'>";
      $pagination .=      "<span class='fa fa-caret-right' aria-hidden='true'></span>";
      $pagination .=      "</a>";
      $pagination .=  "</li>";
    }
  }
$pagination .=    "</ul>";
$pagination .=    "</nav>";
$pagination .= "</div>";

$pagination .= "</div>"; // close div information that will be refres by response Ajax
echo $pagination;

?>
