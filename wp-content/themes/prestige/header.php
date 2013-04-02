<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
<title>
<?php if (is_home()) { ?>
<?php bloginfo('name'); ?>
-
<?php bloginfo('description'); ?>
<?php } else { ?>
<?php wp_title($sep = ''); ?>
-
<?php bloginfo('name'); ?>
<?php } ?>
</title>
<meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
<meta name="description" content="<?php bloginfo('description') ?>" />
<?php if(is_search()) { ?>
<meta name="robots" content="noindex, nofollow" />
<?php }?>
<link rel="stylesheet" type="text/css" href="<?php bloginfo('stylesheet_url'); ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/superfish.css" media="screen" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/hoverintent.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.cycle.all.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/script.js"></script>
<?php wp_head(); ?>
</head>
<body>
<?php if (is_front_page()) { ?>
<div id="page2">
<?php } else { ?>
<div id="page">
<?php } ?>
<div id="header">
  <div class="logo">
    <h1><a href="<?php echo get_option('home'); ?>/" title="<?php bloginfo('name'); ?> - <?php bloginfo('description'); ?>">
      <?php bloginfo('name'); ?>
      -
      <?php bloginfo('description'); ?>
      </a></h1>
    <div class="clr"></div>
  </div>
  <!--/logo -->
  <div class="clr"></div>
  <div class="topnav">
    <ul>
      <?php wp_list_pages('title_li='); ?>
    </ul>
    <div class="clr"></div>
  </div>
  <!--/topnav -->
  <div class="search">
    <?php include (TEMPLATEPATH . '/searchform.php'); ?>
  </div>
  <!--/search -->
  <div class="clr"></div>
  <div class="header_resize">
    <?php if (is_front_page()) { ?>
    <?php include (TEMPLATEPATH . '/slide_block.php'); ?>
    <?php } ?>
  </div>
  <!--/header_resize -->
  <div class="clr"></div>
</div>
<!--/header -->
