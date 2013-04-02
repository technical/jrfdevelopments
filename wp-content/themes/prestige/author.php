<?php get_header(); ?>
<?php
	global $wp_query;
	$curauth = $wp_query->get_queried_object();
	
	$urlHome = get_bloginfo('template_directory');
?>
<div class="columns_bg">
<div id="columns">
  <div id="centercol">
    <div class="box post">
      <div class="content">
      <div class="post-block">
        <div class="post-title full">
          <div class="fl">
            <h1><?php echo $curauth->display_name; ?></h1>
          </div>
          <div class="fr">
            <h1 class="author">Author</h1>
          </div>
          <div class="clr"></div>
        </div>
        </div>
        <div class="pic fl"><?php echo get_avatar("$curauth->user_email", $size = '80', $default = $urlHome . '/images/default_avatar_author.gif' ); ?></div>
        <div class="post-author">
          <div class="author-descr">
            <p><?php echo $curauth->user_description; ?></p>
            <div class="author-details"><a href="<?php echo $curauth->user_url; ?>" target="_blank">Visit Authors Website</a></div>
          </div>
          <!--/author-descr -->
        </div>
        <!--/post-author -->
        <div class="clr"></div>
      </div>
    </div>
    <div class="clr"></div>
    <?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
    <div class="box post" id="post-<?php the_ID(); ?>">
        <div class="content">
          <div class="post-date"><span>
            <?php the_time('dS'); ?>
            </span><br />
            <?php the_time('F'); ?>
          </div>
          <!--/post-date -->
          <div class="post-title">
            <h2><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
              <?php the_title(); ?>
              </a></h2>
            <div class="post-block">In
              <?php the_category(', ') ?>
              , by
              <?php the_author_posts_link(); ?>
            </div>
            <div class="clr"></div>
          </div>
          <!--/post-title -->
          <div class="bg"></div>
          <?php $postimageurl = get_post_meta($post->ID, 'post-img', true); if ($postimageurl) { ?>
          <div class="pic fl"><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><img src="<?php echo $postimageurl; ?>" alt="<?php the_title_attribute(); ?>" /></a></div>
          <?php } ?>
          <!--/post-img -->
          <div class="post-excerpt">
            <?php the_excerpt(); ?>
            <p><a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?> Response">
              <?php _e('Read More'); ?>
              &raquo;</a></p>
          </div>
          <!--/post-excerpt -->
          <div class="bg"></div>
          <div class="social-links">
            <div class="post-commets"> Read More <a href="<?php the_permalink(); ?>" title="View <?php _e('Comments'); ?>" class="font-sm">
              <?php comments_number(0, 1, '%'); ?>
              <?php _e('Comments'); ?>
              </a></div>
            <div class="clr"></div>
          </div>
          <!--/social-links -->
        </div>
        <!--/content -->
      </div>
    <!--/box -->
    <?php endwhile; ?>
    <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
    <?php /*
        <ul>
			<li><?php next_posts_link('&laquo; Older Entries') ?></li>
			<li><?php previous_posts_link('Newer Entries &raquo;') ?></li>
		</ul>
		*/ ?>
    <?php else : ?>
    <div class="box post">
      <div class="content">
        <div class="post-title">
          <h1>No Posts Were Found</h1>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
  <!--/centercol -->
  <?php get_sidebar(); ?>
  <div class="clr"></div>
</div>
<!--/columns --></div>
<?php get_footer(); ?>
