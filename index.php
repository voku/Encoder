<?php
define('PORTABLE_UTF8__DISABLE_AUTO_FILTER', 1);
require_once __DIR__ . '/vendor/autoload.php';
// Check for form submit
if (!isset($_POST['cmdEncode']) && !isset($_POST['cmdDecode'])) {
    // User has not yet submitted
    $_POST['chkBasics'] = true;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title>Decoder - Encoder: UTF-8, UTF-16, ...</title>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <link href="css/styles.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
  <div id="main">
    <h1><u>De</u>code, <u>En</u>code or <u>Ob</u>fuscate your string</h1>
    <p>This is used to obfuscate your string or code, to encode or decode a certain value.</p>
    <hr />
    <form method="post" action="index.php">
      <label for="txtCode">Enter your string in the textarea below.</label>
      <textarea name="txtCode" id="txtCode" cols="80" rows="6"><?= $_POST['txtCode'] ?? '&#20013;&#25991;&#31354;&#30333; | FÃÂÂÂÂ©dÃÂÂÂÂ©ration | DÃ¼sseldorf | %25e1%2583%25a1%25e1%2583%2590%25e1%2583%25a5%25e1%2583%2590%25e1%2583%25a0%25e1%2583%2597%25e1%2583%2595%25e1%2583%2594%25e1%2583%259a%25e1%2583%259d' ?></textarea><br />
      <input type="checkbox" name="chkBasics" id="chkBasics" <?= isset($_POST['chkBasics']) ? 'checked' : '' ?> />
      <label for="chkBasics">Include basic encoding/decoding (HTML, UTF-8, base64, URL encode, ...)</label><br />
      <input type="checkbox" name="chkOneWay" id="chkOneWay" <?= isset($_POST['chkOneWay']) ? 'checked' : '' ?> />
      <label for="chkOneWay">Include one-way encryption (MD5, SHA1, RipeMD, Adler, Haval...)</label><br />
      <input type="checkbox" name="chkObfuscate" id="chkObfuscate" <?= isset($_POST['chkObfuscate']) ? 'checked' : '' ?> />
      <label for="chkObfuscate">Include code obfuscation (Javascript, SQL, HTML)</label><br />
      <input type="checkbox" name="chkStringBasics" id="chkStringBasics" <?= isset($_POST['chkStringBasics']) ? 'checked' : '' ?> />
      <label for="chkStringBasics">Include string function results (strtolower, strlen, ...)</label><br />
      <input type="submit" name="cmdEncode" value="Encode string" class="submit_button" />
      <input type="submit" name="cmdDecode" value="Decode string" class="submit_button" />
    </form>

      <?php
      if (isset($_POST['cmdEncode']) && strlen($_POST['txtCode']) > 0) {
          // Encode this string
          $txtCode = $_POST['txtCode'];

          $arrCharCode = [];
          $arrCharCodeSQL = [];
          $arrCharCodeHexHtml = [];
          $arrCharCodeDecHtml = [];
          $arrCharCodeHexShortHtml = [];
          foreach (\voku\helper\UTF8::chars($txtCode) as $char) {
              $arrCharCode[] = \voku\helper\UTF8::ord($char);
              $arrCharCodeSQL[] = "CHAR(" . \voku\helper\UTF8::ord($char) . ")";
              $arrCharCodeHexHtml[] = "&#x" . dechex(\voku\helper\UTF8::ord($char));
              $arrCharCodeDecHtml[] = "&#" . \voku\helper\UTF8::ord($char);
              $arrCharCodeHexShortHtml[] = "%" . dechex(\voku\helper\UTF8::ord($char));
          }

          echo "<br /><h1>Encoding results</h1>\n\n";

          if (isset($_POST['chkBasics'])) {
              echo "<h1>Basic encoding</h1>\n";

              // UTF-7
              echo "<h2>Modified UTF-7 encode (imap_utf7_encode)</h2>\n";
              echo "<xmp>" . imap_utf7_encode($txtCode) . "</xmp>\n\n";

              // UTF-7
              echo "<h2>UTF-7 encode (UTF8::encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::encode('UTF-7', $txtCode) . "</xmp>\n\n";

              // UTF-8
              echo "<h2>Encodes an ISO-8859-1 string to UTF-8 (utf8_encode)</h2>\n";
              echo "<xmp>" . utf8_encode($txtCode) . "</xmp>\n\n";

              // UTF-8
              echo "<h2>Encodes an ISO-8859-1 string to UTF-8 (UTF8::utf8_encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::utf8_encode($txtCode) . "</xmp>\n\n";

              // UTF-8
              echo "<h2>UTF-8 encode (UTF8::encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::encode('UTF-8', $txtCode) . "</xmp>\n\n";

              // UTF-16
              echo "<h2>UTF-16 encode (mb_convert_encoding)</h2>\n";
              echo "<xmp>" . mb_convert_encoding($txtCode, "UTF-16", "auto") . "</xmp>\n\n";

              // UTF-16
              echo "<h2>UTF-16 encode (UTF8::encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::encode('UTF-16', $txtCode) . "</xmp>\n\n";

              // UTF-32
              echo "<h2>UTF-32 encode (mb_convert_encoding)</h2>\n";
              echo "<xmp>" . mb_convert_encoding($txtCode, "UTF-32", "auto") . "</xmp>\n\n";

              // UTF-32
              echo "<h2>UTF-32 encode (UTF8::encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::encode('UTF-32', $txtCode) . "</xmp>\n\n";

              // rawurlencode
              echo "<h2>RAW URL encode (rawurlencode)</h2>\n";
              echo "<xmp>" . rawurlencode($txtCode) . "</xmp>\n\n";

              // urlencode
              echo "<h2>URL encode simple (urlencode)</h2>\n";
              echo "<xmp>" . urlencode($txtCode) . "</xmp>\n\n";

              // urlencode
              echo "<h2>URL encode full</h2>\n";
              echo "<xmp>" . implode("", $arrCharCodeHexShortHtml) . "</xmp>\n\n";

              // HTML
              echo "<h2>HTML encode (htmlentities)</h2>\n";
              /** @noinspection NonSecureHtmlentitiesUsageInspection */
              echo "<xmp>" . htmlentities($txtCode) . "</xmp>\n\n";

              // HTML
              echo "<h2>HTML encode (UTF8::htmlentities)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::htmlentities($txtCode) . "</xmp>\n\n";

              // HTML
              echo "<h2>HTML encode (UTF8::html_encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::html_encode($txtCode) . "</xmp>\n\n";

              // base64
              echo "<h2>Base64 encode (base64_encode)</h2>\n";
              echo "<xmp>" . base64_encode($txtCode) . "</xmp>\n\n";

              // base64
              echo "<h2>Base64 encode (UTF8::encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::encode('BASE64', $txtCode) . "</xmp>\n\n";

              // uuencode
              echo "<h2>UUencode (convert_uuencode)</h2>\n";
              echo "<xmp>" . convert_uuencode($txtCode) . "</xmp>\n\n";
          }

          if (isset($_POST['chkOneWay'])) {
              echo "<h1>One way encryption</h1>\n";
              foreach (hash_algos() as $hash_algo) {
                  echo "<h2>Hash: " . $hash_algo . "</h2>\n";
                  echo "<xmp>" . hash($hash_algo, $txtCode) . "</xmp>\n\n";
              }
          }

          if (isset($_POST['chkObfuscate'])) {
              echo "<h1>Obfuscation: JavaScript</h1>\n";
              // String.fromCharCode() in Javascript
              echo "<h2>fromCharCode()</h2>\n";
              echo "<xmp>document.write(String.fromCharCode(" . implode(",", $arrCharCode) . "));</xmp>\n\n";

              // unescape() in Javascript
              echo "<h2>unescape()</h2>\n";
              echo "<xmp>document.write(unescape(\"" . implode("", $arrCharCodeHexShortHtml) . "\"));</xmp>\n\n";

              echo "<h1>Obfuscation: SQL</h1>\n";
              // concat() char's
              echo "<h2>CONTACT of CHAR()'s</h2>\n";
              echo "<xmp>CONCAT(" . implode(",", $arrCharCodeSQL) . ")</xmp>\n\n";

              // char()
              echo "<h2>CHAR()</h2>\n";
              echo "<xmp>CHAR(" . implode(",", $arrCharCode) . ")</xmp>\n\n";

              echo "<h1>Obfuscation: HTML</h1>\n";
              // hexadecimal
              echo "<h2>HTML Hexadecimal with optional semicolons</h2>\n";
              echo "<xmp>" . implode(";", $arrCharCodeHexHtml) . ";</xmp>\n\n";

              // decimal
              echo "<h2>HTML Decimal with optional semicolons</h2>\n";
              echo "<xmp>" . implode(";", $arrCharCodeDecHtml) . ";</xmp>\n\n";
          }

          if (isset($_POST['chkStringBasics'])) {
              echo "<h1>String: Function Examples</h1>\n";

              // strlen
              echo "<h2>String length (strlen)</h2>\n";
              echo "<xmp>" . strlen($txtCode) . "</xmp>\n\n";

              // strlen
              echo "<h2>String length (UTF8::strlen)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::strlen($txtCode) . "</xmp>\n\n";

              // strtolower
              echo "<h2>String to lower (strtolower)</h2>\n";
              echo "<xmp>" . strtolower($txtCode) . "</xmp>\n\n";

              // strtolower
              echo "<h2>String to lower (UTF8::strtolower)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::strtolower($txtCode) . "</xmp>\n\n";

              // strtoupper
              echo "<h2>String to upper (strtoupper)</h2>\n";
              echo "<xmp>" . strtoupper($txtCode) . "</xmp>\n\n";

              // strtoupper
              echo "<h2>String to upper (UTF8::strtoupper)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::strtoupper($txtCode) . "</xmp>\n\n";

              // substr
              echo "<h2>Get part of a string. [e.g. 0-5] (substr)</h2>\n";
              echo "<xmp>" . substr($txtCode, 0, 5) . "</xmp>\n\n";

              // substr
              echo "<h2>Get part of a string. [e.g. 0-5] (UTF8::substr)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::substr($txtCode, 0, 5) . "</xmp>\n\n";

              // lcfirst
              echo "<h2>Make a string's first character lowercase (lcfirst)</h2>\n";
              echo "<xmp>" . lcfirst($txtCode) . "</xmp>\n\n";

              // lcfirst
              echo "<h2>Make a string's first character lowercase (UTF8::lcfirst)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::lcfirst($txtCode) . "</xmp>\n\n";

              // ucfirst
              echo "<h2>Make a string's first character uppercase (ucfirst)</h2>\n";
              echo "<xmp>" . ucfirst($txtCode) . "</xmp>\n\n";

              // ucfirst
              echo "<h2>Make a string's first character uppercase (UTF8::ucfirst)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::ucfirst($txtCode) . "</xmp>\n\n";

              // str_camelize
              echo "<h2>String to camel case (UTF8::str_camelize)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::str_camelize($txtCode) . "</xmp>\n\n";

              // str_snakeize
              echo "<h2>String to snake case (UTF8::str_snakeize)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::str_snakeize($txtCode) . "</xmp>\n\n";

              // str_dasherize
              echo "<h2>String separated by dashes (UTF8::str_dasherize)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::str_dasherize($txtCode) . "</xmp>\n\n";

              // str_underscored
              echo "<h2>String separated by underscores (UTF8::str_underscored)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::str_underscored($txtCode) . "</xmp>\n\n";

              // str_titleize
              echo "<h2>String capitalized each word (UTF8::str_titleize)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::str_titleize($txtCode) . "</xmp>\n\n";

              // str_truncate_safe
              echo "<h2>Truncates the string [e.g. 5 chars] (UTF8::str_truncate_safe)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::str_truncate_safe($txtCode, 5) . "</xmp>\n\n";

              // str_word_count
              echo "<h2>Get the number of words in a specific string. (UTF8::str_word_count)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::str_word_count($txtCode) . "</xmp>\n\n";

              // count_chars
              echo "<h2>Returns count of characters used in a string. (UTF8::count_chars)</h2>\n";
              echo "<xmp>" . print_r(\voku\helper\UTF8::count_chars($txtCode), true) . "</xmp>\n\n";

              // codepoints
              echo "<h2>Get an array of Unicode code points. (UTF8::codepoints)</h2>\n";
              echo "<xmp>" . print_r(\voku\helper\UTF8::codepoints($txtCode), true) . "</xmp>\n\n";

          }
      } elseif (isset($_POST['cmdDecode']) && strlen($_POST['txtCode']) > 0) {
          // Decode this string
          $txtCode = $_POST['txtCode'];

          if (isset($_POST['chkBasics'])) {
              echo "<h1>Basic encoding</h1>\n";

              // ASCII
              echo "<h2>Converting into ASCII (UTF8::to_ascii)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::to_ascii($txtCode) . "</xmp>\n\n";

              // UTF-7
              echo "<h2>Modified UTF-7 decoded (imap_utf7_decode)</h2>\n";
              echo "<xmp>" . imap_utf7_decode($txtCode) . "</xmp>\n\n";

              // UTF-7
              echo "<h2>UTF-7 decoded (UTF8::encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::encode('UTF-8', $txtCode, 'UTF-7') . "</xmp>\n\n";

              // UTF-8
              echo "<h2>Decodes a UTF-8 string to ISO-8859-1 (utf8_decode)</h2>\n";
              echo "<xmp>" . utf8_decode($txtCode) . "</xmp>\n\n";

              // UTF-8
              echo "<h2>Decodes a UTF-8 string to ISO-8859-1 (UTF8::utf8_decode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::utf8_decode($txtCode) . "</xmp>\n\n";

              // UTF-8
              echo "<h2>Converting almost all non-UTF-8 to UTF-8 (UTF8::to_utf8)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::to_utf8($txtCode) . "</xmp>\n\n";

              // UTF-8
              echo "<h2>Fix a double (or multiple) encoded UTF-8 string (UTF8::fix_utf8)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::fix_utf8($txtCode) . "</xmp>\n\n";

              // UTF-8
              echo "<h2>Try to fix simple broken UTF-8 strings (UTF8::fix_simple_utf8)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::fix_simple_utf8($txtCode) . "</xmp>\n\n";

              // UTF-16
              echo "<h2>UTF-16 decoded to UTF-8 (mb_convert_encoding)</h2>\n";
              echo "<xmp>" . mb_convert_encoding($txtCode, "UTF-8", ["UTF-16"]) . "</xmp>\n\n";

              // UTF-16
              echo "<h2>UTF-16 decoded to UTF-8 (UTF8::encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::encode('UTF-8', $txtCode, false, 'UTF-16') . "</xmp>\n\n";

              // UTF-32
              echo "<h2>UTF-32 decoded to UTF-8 (mb_convert_encoding)</h2>\n";
              echo "<xmp>" . mb_convert_encoding($txtCode, "UTF-8", ["UTF-32"]) . "</xmp>\n\n";

              // UTF-32
              echo "<h2>UTF-32 decoded to UTF-8 (UTF8::encode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::encode('UTF-8', $txtCode, false, 'UTF-32') . "</xmp>\n\n";

              // rawurlencode
              echo "<h2>RAW URL decoded (rawurldecode)</h2>\n";
              echo "<xmp>" . rawurldecode($txtCode) . "</xmp>\n\n";

              // rawurlencode
              echo "<h2>Multi RAW URL + HTML entity decoded + fix urlencoded-win1252-chars (UTF8::rawurldecode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::rawurldecode($txtCode) . "</xmp>\n\n";

              // urlencode
              echo "<h2>URL encode (urlencode)</h2>\n";
              echo "<xmp>" . urlencode($txtCode) . "</xmp>\n\n";

              // HTML
              echo "<h2>HTML entities decoded (html_entity_decode)</h2>\n";
              echo "<xmp>" . html_entity_decode($txtCode) . "</xmp>\n\n";

              // HTML
              echo "<h2>HTML entities decoded + decode also numeric & UTF16 two byte entities (UTF8::html_entity_decode)</h2>\n";
              echo "<xmp>" . \voku\helper\UTF8::html_entity_decode($txtCode) . "</xmp>\n\n";

              // base64
              echo "<h2>Base64 decoded (base64_decode)</h2>\n";
              echo "<xmp>" . base64_decode($txtCode) . "</xmp>\n\n";

              // uuencode
              echo "<h2>UUdecoded (convert_uudecode)</h2>\n";
              echo "<xmp>" . convert_uudecode($txtCode) . "</xmp>\n\n";
          }
      }
      ?>
  </div>

  <div id="footer">
    String decoder &amp; encoder
    |
    Demo for <a href="https://github.com/voku/portable-utf8" target="_blank">🉑 Portable UTF-8</a>
    |
    Source of this demo on <a href="https://github.com/voku/Encoder" target="_blank">Github</a>
  </div>
</div>
<script type="application/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/1.7.1/clipboard.min.js"></script>
<!--suppress CheckValidXmlInScriptTagBody -->
<script type="application/javascript">
  (function() {
    // Make copy button for all xmp tags
    var xmps = document.getElementsByTagName('xmp');

    for (var count = 0; count < xmps.length; count++) {
      var id = 'copy-' + count;

      var xmp = xmps[count];
      xmp.id  = id;

      var button = document.createElement('a');
      button.setAttribute("data-clipboard-target", "#" + id);
      button.innerText = 'Copy text';

      var title = xmp.previousElementSibling;
      title.appendChild(button);
    }

    var clipboard = new Clipboard('a[data-clipboard-target]');

    clipboard.on('success', function(e) {
      var trigger       = e.trigger;
      trigger.innerText = 'Copied!';

      setTimeout(function() {
        trigger.innerText = 'Copy text';
      }, 2000);

      e.clearSelection();
    });

    clipboard.on('error', function(e) {
      var trigger       = e.trigger;
      trigger.innerText = 'Press Ctrl+c or Cmd+c to copy.';
      setTimeout(function() {
        trigger.innerText = 'Copy text';
      }, 2000);
    });
  }());
</script>
</body>

</html>