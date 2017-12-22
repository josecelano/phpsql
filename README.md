# PHP multi database console client

I needed a SQL Server console client on a Linux system and I found this one:

https://github.com/hasankhan/sql-cli

and I decided to do the same for PHP.

## Install

```
git clone git@github.com:josecelano/phpsql.git
cd phpsql
```

You can use it in 3 different ways:

## SQL as argument

```
bin/phpsql --user=root --password= --database=homestead --host=127.0.0.1 --port=3306 "show tables"
```

## Standard input (pipe)

```
echo "show tables" | bin/phpsql  --user=root --password= --database=homestead --host=127.0.0.1 --port=3306
```

## Interactive shell

```
bin/phpsql --interactive --user=root --password= --database=homestead --host=127.0.0.1 --port=3306
```

## Test

```
composer test
```

## TODO

* I had to add the `--interactive` option. Ideally interactive mode should be automatically executed if there are no data from the standard input.

## Output

Output should be something like:

```
C:\Projects\phpsql (master)
λ bin\phpsql.bat --interactive --user=root --password= --database=homestead --host=127.0.0.1 --port=3306
Welcome to interactive php SQL shell. Type 'quit' or 'exit' to exit.
>show tables
+-------------------------+
| Tables_in_homestead     |
+-------------------------+
| appliances              |
| history                 |
| history_types           |
| migrations              |
| password_resets         |
| permission_role         |
| permissions             |
| role_user               |
| roles                   |
| sessions                |
| social_logins           |
| users                   |
| wishlist_item_feedbacks |
| wishlists               |
+-------------------------+
>
```