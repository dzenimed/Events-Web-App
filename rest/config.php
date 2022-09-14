<?php
class Config {
  const DATE_FORMAT = "Y-m-d H:i:s";
  public const JWT_TOKEN_TIME = 604800;
  public const JWT_SECRET = "y4KvQcZVqn3F7uxQvcFk";

  const DB_HOST = "eu-cdbr-west-03.cleardb.net";
  const DB_USERNAME = "b922072bbc67c9";
  const DB_PASSWORD = "7cbaabfe";
  const DB_SCHEME = "heroku_2b4de0c466ea2ca";

  public static function JWT_SECRET()
  {
      return Config::get_env("JWT_SECRET", "y4KvQcZVqn3F7uxQvcFk");
  }

  public static function SMTP_HOST(){
  return Config::get_env("SMTP_HOST", "smtp.sendgrid.net");
}

public static function SMTP_PORT(){
  return Config::get_env("SMTP_PORT", "587");
}

  public static function SMTP_USER(){
  return Config::get_env("SMTP_USER", NULL);
}

public static function SMTP_PASSWORD(){
  return Config::get_env("SMTP_PASSWORD", NULL);
}

  // environment servers setup
  public static function ENVIRONMENT_SERVER()
  {
      return Config::get_env("ENVIRONMENT_SERVER", "localhost/Events-Web-App/");
  }
  public static function PROTOCOL()
  {
      return strtolower(substr($_SERVER["SERVER_PROTOCOL"], 0, strpos($_SERVER["SERVER_PROTOCOL"], '/'))).'://';
  }

  public static function get_env($name, $default)
  {
      return isset($_ENV[$name]) && trim($_ENV[$name]) != '' ? $_ENV[$name] : $default;
  }
}

?>
