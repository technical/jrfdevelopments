<?php
if (isset($_GET['href'])) $href = $_GET['href'];
else $href = 'http://gallery.me.com/sebastien.gillard/100278/BPL - Medium - Medium.m4v';
if (isset($_GET['width'])) $width = $_GET['width'];
else $width = '640';
if (isset($_GET['height'])) $height = $_GET['height'];
else $height = '480';

?>
<head>
<script src="video-js/video.js" type="text/javascript" charset="utf-8"></script>
<link rel="stylesheet" href="video-js/video-js.css" type="text/css" media="screen" title="Video JS" charset="utf-8">

<script type="text/javascript" charset="utf-8">
// Add VideoJS to all video tags on the page when the DOM is ready
VideoJS.setupAllWhenReady();
</script>
</head>

<body style="margin: 0">
   
    
 <!-- Begin VideoJS -->
  <div class="video-js-box">
    <!-- Using the Video for Everybody Embed Code http://camendesign.com/code/video_for_everybody -->
    <video class="video-js" width="<?php echo $width ?>" height="<?php echo $height ?>" controls preload autoplay>
      <source src="<?php echo $href ?>" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"' />
    </video>
    <!-- Download links provided for devices that can't play video in the browser. -->
    <p class="vjs-no-video"><strong>Download Video:</strong>
      <a href="<?php echo $href ?>">MP4</a>,
      <!-- Support VideoJS by keeping this link. -->
      <a href="http://videojs.com">HTML5 Video Player</a> by VideoJS
    </p>
  </div>
  <!-- End VideoJS -->
  </body>