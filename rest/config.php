<?php
class Config {
  const DATE_FORMAT = "Y-m-d H:i:s";

  const DB_HOST = "eu-cdbr-west-03.cleardb.net";
  const DB_USERNAME = "b922072bbc67c9";
  const DB_PASSWORD = "7cbaabfe";
  const DB_SCHEME = "heroku_2b4de0c466ea2ca";

  public const SMTP_HOST = "smtp.sendgrid.net";
  public const SMTP_PORT = 587;
  public const SMTP_USER = "#";
  public const SMTP_PASSWORD = "#";

  public static function JWT_SECRET()
  {
      return Config::get_env("JWT_SECRET", "y4KvQcZVqn3F7uxQvcFk");
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

  // const DB_HOST = "localhost";
  // const DB_USERNAME = "events";
  // const DB_PASSWORD = "events123";
  // const DB_SCHEME = "events_db";
?>
