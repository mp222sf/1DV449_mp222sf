<?php
// Start sessions.
session_start();

class LayoutView {
  
  public function render($bookResponse) {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Webbteknik II - Laboration 1</title>
        </head>
        <body>

          <h1>Laboration 1</h1>
          <a href="index.php">Startsida</a>

          <div class="container">
            ' . $bookResponse . '
          </div>
         </body>
      </html>
    ';
  }
}
