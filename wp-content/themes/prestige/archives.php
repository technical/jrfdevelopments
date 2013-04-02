<?php
/*
Template Name: Archives
*/
?>
<?php get_header(); ?>
<div class="columns_bg">
<div id="columns">
  <div id="centercol">
    <div class="box post">
      <div class="content">
        <h2>Archives by Month:</h2>
        <ul>
          <?php wp_get_archives('type=monthly'); ?>
        </ul>
        <h2>Archives by Subject:</h2>
        <ul>
          <?php wp_list_categories(); ?>
        </ul>
      </div>
      <!--/content -->
    </div>
    <!--/box -->
  </div>
  <!--/centercol -->
  <?php get_sidebar(); ?>
  <div class="clr"></div>
</div>
<!--/columns --></div>
<?php get_footer(); ?>
