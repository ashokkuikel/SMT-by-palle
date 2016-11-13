<?php

  if(end($url) == 'save') {
    $user->createUser($_POST);
    Base::setRoute('user', 'admin_user_liste');
  }

?>

