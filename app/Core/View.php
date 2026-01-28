<?php
class View {
  private static ?string \ = null;
  private static array \ = [];

  public static function extends(string \): void { self::\ = \; }
  public static function render(string \, array \=[]): void {
    extract(\);
    self::\ = null;

    \ = __DIR__ . '/../Views/' . \ . '.php';
    if (!file_exists(\)) { throw new Exception('View nÃ£o encontrada: ' . \); }

    ob_start();
    include \;
    \ = ob_get_clean();

    if (self::\) {
      \ = __DIR__ . '/../Views/' . self::\ . '.php';
      if (!file_exists(\)) { throw new Exception('Layout nÃ£o encontrado: ' . self::\); }
      \ = \;
      include \;
      return;
    }

    echo \;
  }
}
