$env:PGPASSWORD='Azerty@21!'
& 'C:\Program Files\PostgreSQL\16\bin\psql.exe' -U postgres -h 127.0.0.1 -p 5432 -d postgres -c "SELECT version();"
