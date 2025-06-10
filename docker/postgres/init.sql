DO $$
BEGIN
   IF NOT EXISTS (SELECT FROM pg_database WHERE datname = 'paynet_db') THEN
      CREATE DATABASE paynet_db;
   END IF;
END
$$;

DO $$
BEGIN
   IF NOT EXISTS (SELECT FROM pg_database WHERE datname = 'paynet_tests_db') THEN
      CREATE DATABASE paynet_tests_db;
   END IF;
END
$$;
