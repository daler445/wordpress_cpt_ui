<?php
  // в файле шаблона
  // in template file
?>

<?php

  $current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $per_page = '10';

  $documents = new WP_Query(array(
    'post_type' => 'documents',
    'posts_per_page' => $per_page,
    'paged' => $current_page
  ));
  $post_count = $documents->found_posts;
?>

<?php while($documents->have_posts()) : $documents->the_post(); ?>

<?php the_title(); ?>

<?php wp_reset_postdata(); ?>
<?php endwhile; ?>


<?php    

  $total_pages = (int)($post_count / $per_page);
  $ostatok = $post_count % $per_page;
  if ($ostatok > 0) {
    $total_pages = $total_pages + 1;
  }
  echo "<br />";
  echo "current page: " . $paged;
  echo "<br />";
  echo "total pages: " . $total_pages;
  echo "<br />";

  if (($total_pages > 1) && ($paged > 1)) {
    // существует пред. страница
    // have prev. page
    
    $raw_url = get_previous_posts_page_link();
    echo "<a href='".$raw_url."'>prev</a> ";
  }
  if (($paged == $total_pages) && ($paged == 1)) {
    // одна и единственная страница
    // first and only page 
  }
  if ($paged < $total_pages) {
    // существует след. страница
    // have next page
    
    $raw_url = get_next_posts_page_link();	
    echo "<a href='".$raw_url."'>next</a> ";
  }

?>
