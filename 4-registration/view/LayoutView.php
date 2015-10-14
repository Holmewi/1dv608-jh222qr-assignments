<?php

namespace view;

class LayoutView {
  
  public function render($isLoggedIn, LoginView $v_lv, RegisterView $v_rv, DateTimeView $dtv, NavigationView $v_nv) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 4</h1>
          ' . $this->renderIsLoggedIn($isLoggedIn, $v_nv) . '
          
          <div class="container">
              ' . $this->renderViewResponse($v_lv, $v_rv, $v_nv) . '
              ' . $dtv->show() . '
          </div>
          <div>
            <em>This site uses cookies to improve user experience. By continuing to browse the site you are agreeing to our use of cookies.</em>
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn($isLoggedIn, NavigationView $v_nv) {
    if ($isLoggedIn) {
      if($v_nv->inRegistration()) {
        return '<h2>Register new user</h2>';
      } else {
        return '<h2>Logged in</h2>';
      } 
    }
    else {
      return '<h2>Not logged in</h2>';
    }
  }

  private function renderViewResponse(LoginView $v_lv, RegisterView $v_rv, NavigationView $v_nv) {
    if($v_nv->inRegistration()) {
      return $v_rv->response();
    } else {
      return $v_lv->response();
    }
  }
}