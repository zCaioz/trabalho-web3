Apache config:

1 - Descomentar, caso esteja: LoadModule rewrite_module modules/mod_rewrite.so
2 - Altera AllowOverride None -> AllowOverride All

Banco de Dados:

1 - Execute o script SQL contido no arquivo script.txt para criar o banco de dados e tabelas.
2 - Dado o uso do XAMPP, o usuário padrão é root e a senha está vazia ("").
Se precisar alterar, modifique as credenciais no arquivo DbService.php.

CORS:

Caso ocorra algum erro, no arquivo .htaccess, altere header("Access-Control-Allow-Origin: XXXX") para a origem da sua request

