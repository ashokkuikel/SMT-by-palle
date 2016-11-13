<?php

  if (isset($_POST ['username']) && isset($_POST ['passwort'])) {
    $login = $user->loginUser($_POST ['username'], $_POST ['passwort'], Base::get('Handler')->config);

    if ($login) {
      if($session->get('redirect') == '/') {
        Base::setRoute('user', 'mypage');
      } else {
        header("Location: ".$session->get('redirect'));
      }
    } 
  }
?>
