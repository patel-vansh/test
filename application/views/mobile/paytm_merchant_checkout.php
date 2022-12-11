<html>

<head>
  <title>Merchant Check Out Page</title>
</head>

<body>
  <center>
    <h1>Please do not refresh this page...</h1>
  </center>
  <form method='post' action='<?php echo PAYTM_TXN_URL; ?>' name='f1'>
    <table border='f1'>
      <tbody>
        <?php foreach ($paramList as $name => $value) : ?>
          <input type="hidden" name="<?php echo htmlspecialchars($name); ?>" value="<?php echo htmlspecialchars($value); ?>">
        <?php endforeach; ?>
        <input type='hidden' name='CHECKSUMHASH' value='<?php echo htmlspecialchars($checkSum); ?>'>
      </tbody>
    </table>
    <script type='text/javascript'>
      document.f1.submit();
    </script>
  </form>
</body>

</html>
