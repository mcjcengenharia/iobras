<?php
class DB {
  private static ?PDO \ = null;

  public static function conn(): PDO {
    if (self::\) return self::\;

    \System.Management.Automation.Internal.Host.InternalHost = Env::get('DB_HOST','127.0.0.1');
    \ = Env::get('DB_PORT','3306');
    \   = Env::get('DB_DATABASE','prd_system');
    \ = Env::get('DB_USERNAME','root');
    \ = Env::get('DB_PASSWORD','');

    \ = \"mysql:host=\System.Management.Automation.Internal.Host.InternalHost;port=\;dbname=\;charset=utf8mb4\";
    self::\ = new PDO(\, \, \, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return self::\;
  }
}
