<?php

class PDOConnection
{
    private static ?\PDO $pg_pdo = null;

    
    public static function pg_get(): PDO
    {
        if(is_null(self::$pg_pdo)){
            self::$pg_pdo = self::pg_createPdoInstance();
        }
        return self::$pg_pdo;
    }


    private static function pg_createPdoInstance(): PDO
    {
        try{
             $pg_dsn = "pgsql:host=".PG_HOST.
                        ";port=".PG_PORT.
                        ";dbname=".PG_BASE; 

            $pgPDO= new PDO( $pg_dsn, PG_USER, PG_PASS);

            $pgPDO->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            if(defined('DB_SQL_DEBUG')){
                $pgPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            return $pgPDO;
        } catch(PDOException $exception){
            throw new Exception($exception->getMessage(), 99);
        }
        return $pgPDO;
    }

    
    
}
