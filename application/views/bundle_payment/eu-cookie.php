<link rel="stylesheet" type="text/css" href="<?php echo site_url(); ?>assets/frontend/eu-cookie/purecookie.css" async />

<div class="cookieConsentContainer" id="cookieConsentContainer">
  <div class="cookieDesc">
    <p>
      <?php echo get_frontend_settings('cookie_note');?>
      <a class="link-cookie-policy" href="<?php echo site_url('home/cookie_policy'); ?>"><?php echo get_phrase('cookie_policy'); ?></a>
    </p>
  </div>
  <div class="cookieButton">
    <a onclick="cookieAccept();"><?php echo get_phrase('accept'); ?></a>
  </div>
</div>