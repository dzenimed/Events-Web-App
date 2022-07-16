<?php
class Config {
  const DATE_FORMAT = "Y-m-d H:i:s";

  // const DB_HOST = "localhost";
  // const DB_USERNAME = "events";
  // const DB_PASSWORD = "events123";
  // const DB_SCHEME = "events_db";

  // const DB_HOST = "eu-cdbr-west-02.cleardb.net";
  // const DB_USERNAME = "b80b15d1d7ad17";
  // const DB_PASSWORD = "4b91e993";
  // const DB_SCHEME = "heroku_df1469867643cb1";

//new db
  const DB_HOST = "eu-cdbr-west-03.cleardb.net";
  const DB_USERNAME = "b922072bbc67c9";
  const DB_PASSWORD = "7cbaabfe";
  const DB_SCHEME = "heroku_2b4de0c466ea2ca";

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

?>
