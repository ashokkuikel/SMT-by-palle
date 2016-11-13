<?php

/**
 * Klasse zur Verarbeitung von emails
 * 
 * @author    Werner Pallentin <werner.pallentin@outlook.de>
 * @package   System
 */
define('CLRF', "\n");

class Mail {
  public function sendMail($to, $from, $subject, $utf8Html, $additionalHeaders = '', $boundary = '') {
    $bccMode = is_array($to);

    $mailHeader = 'From: ' . $from . CLRF;
    $mailHeader .= 'Reply-To: ' . $from . CLRF;

    if ($bccMode) {
      $mailHeader .= 'Bcc: ' . implode($to, ",\n\t") . CLRF;
      $recipient = '';
    } else {
      $recipient = $to;
    }

    if ($additionalHeaders) {
      $mailHeader .= $additionalHeaders;
    }

    $mailHeader .= 'MIME-Version: 1.0' . CLRF;

    if ($boundary) {
      $mailHeader .= 'Content-Type: multipart/mixed; boundary="' . $boundary . '"' . CLRF;
    } else {
      $mailHeader .= 'Content-Type: text/html; charset="UTF-8"' . CLRF;
    }

    $mailHeader .= 'Content-Transfer-Encoding: 8bit' . CLRF;
  
    return mail($recipient, "=?utf-8?b?" . base64_encode($subject) . "?=", $utf8Html, $mailHeader);

  }

  /**
   * Sendet eine HTML Email mit Attachment(s)
   *
   * @param mixed $to
   *        	Ein Empf�nger als String oder mehrere Empf�nger als Array (Array = BCC Mode)
   * @param string $from
   *        	Absender Email-Adresse
   * @param string $subject
   *        	Email Betreff
   * @param string $utf8Html
   *        	HTML Inhalt UTF8-kodiert
   * @param mixed $attachments
   *        	Ein Dateipfad als String oder mehrere als Array
   * @access public
   * @return boolean
   */
  public function sendAttachments($to, $from, $subject, $utf8Html, $attachments) {
    $boundary = '-----=' . md5(uniqid(rand()));

    $message = '--' . $boundary . CLRF;
    $message .= 'Content-Type: text/html; charset=UTF-8' . CLRF;
    $message .= 'Content-Transfer-Encoding: base64' . CLRF . CLRF;
    $message .= chunk_split(base64_encode($utf8Html)) . CLRF . CLRF;

    if (!is_array($attachments))
      $attachments = array(
          $attachments
      );

    foreach ($attachments as $file) {
      $file = realpath($file);
      if ($file === false)
        continue;

      $message .= '--' . $boundary . CLRF;
      $message .= 'Content-Type: ' . miniMail::_getContentType($file) . '; name="' . basename($file) . '"' . CLRF;
      $message .= 'Content-Transfer-Encoding: base64' . CLRF;
      $message .= 'Content-Disposition: attachment; filename="' . basename($file) . '"' . CLRF . CLRF;
      $message .= chunk_split(base64_encode(file_get_contents($file))) . CLRF;
    }

    $message .= '--' . $boundary . CLRF . CLRF;
    return self::sendMail($to, $from, $subject, $message, '', $boundary);
  }

  public function sendImportant($to, $from, $subject, $utf8Html, $priority = 3, $checkReadMail = true, $checkGetMail = true) {
    $mailHeader = '';
    $priorities = array(
        1 => '1 (Highest)',
        2 => '2 (High)',
        3 => '3 (Normal)',
        4 => '4 (Low)',
        5 => '5 (Lowest)'
    );

    if ($priority != 3 && isset($priorities [$priority])) {
      $mailHeader .= 'X-Priority: ' . $priorities [$priority] . CLRF;
    }

    if ($checkReadMail) {
      $mailHeader .= 'X-Confirm-Reading-To: ' . $from . CLRF;
    }

    if ($checkGetMail) {
      $mailHeader .= 'Disposition-Notification-To: ' . $from . CLRF;
    }

    return self::sendMail($to, $from, $subject, $utf8Html, $mailHeader);
  }

  public function _getContentType($fileName) {
    $contentType = '';
    $contentTypes = array(
        'xls' => 'application/msexcel',
        'ppt' => 'application/mspowerpoint',
        'pps' => 'application/mspowerpoint',
        'doc' => 'application/msword',
        'exe' => 'application/octet-stream',
        'pdf' => 'application/pdf',
        'zip' => 'application/zip',
        'gif' => 'image/gif',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'csv' => 'text/comma-separated-values',
        'txt' => 'text/plain',
        'xml' => 'text/xml',
        'mpg' => 'video/mpeg',
        'mpeg' => 'video/mpeg'
    );

    $fileInfo = pathinfo($fileName);
    $extension = strtolower($fileInfo ['extension']);

    if (isset($contentTypes [$extension])) {
      $contentType = $contentTypes [$extension];
    } else {
      $contentType = 'application/unknown';
    }

    return $contentType;
  }

}

?>