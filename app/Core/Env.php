<?php
class Env {
  public static function load(string \): void {
    if (!file_exists(\)) return;
    \ = file(\, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach (\ as \) {
      \ = trim(\);
      if (\ === '' || str_starts_with(\, '#')) continue;
      \ = explode('=', \, 2);
      if (count(\) !== 2) continue;
      \ = trim(\[0]);
      \ = trim(\[1]);
      \ = trim(\, '\"');
      \[\] = \;
      putenv(\"\=\\");
    }
  }
  public static function get(string \, \=null) {
    return \[\] ?? getenv(\) ?? \;
  }
}
