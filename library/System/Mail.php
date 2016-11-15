<?php

/**
 * Klasse zum versenden von emails
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   System
 */
define('CLRF', "\n");

class Mail {
  
  public function sendMail($to, $from, $subject, $utf8Html) {
    $mailHeader = 'From: ' . $from . CLRF;
    $mailHeader .= 'Reply-To: ' . $from . CLRF;
    $mailHeader .= 'MIME-Version: 1.0' . CLRF;
    $mailHeader .= 'Content-Type: text/html; charset="UTF-8"' . CLRF;
    $mailHeader .= 'Content-Transfer-Encoding: 8bit' . CLRF;  
    return mail($to, "=?utf-8?b?" . base64_encode($subject) . "?=", $utf8Html, $mailHeader);
  }
}

?>