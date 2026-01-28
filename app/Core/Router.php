<?php
class Router {
  private array \ = ['GET'=>[], 'POST'=>[]];

  public function get(string \, \): void { \->routes['GET'][\] = \; }
  public function post(string \, \): void { \->routes['POST'][\] = \; }

  public function dispatch(): void {
    \ = \['REQUEST_METHOD'] ?? 'GET';
    \ = parse_url(\['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    \ = rtrim(\, '/') ?: '/';

    \ = \->routes[\][\] ?? null;
    if (!\) { http_response_code(404); echo \"404\"; return; }

    if (is_array(\)) {
      \ = \[0]; \ = \[1];
      \ = new \();
      \->\();
      return;
    }
    call_user_func(\);
  }
}
