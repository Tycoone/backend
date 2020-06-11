<?php
function  sendwelcomemail($password)
{
    new Mailer('welcomemail');
    $welcome = new welcomemail($password);
    $welcome->send();
}
