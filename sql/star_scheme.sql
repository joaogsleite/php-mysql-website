# CREATE SCHEMA tentativas_login;
DROP TABLE IF EXISTS d_utilizador;
DROP TABLE IF EXISTS d_tempo;
DROP TABLE IF EXISTS tentativas_login;

CREATE TABLE d_utilizador (
    email VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    pais VARCHAR(45) NOT NULL,
    categoria VARCHAR(45) NOT NULL,
PRIMARY KEY (email)
);

CREATE TABLE d_tempo (
    dia INT NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
PRIMARY KEY (dia,mes,ano)
);

CREATE TABLE tentativas_login (
    email VARCHAR(255) NOT NULL,
    dia INT NOT NULL,
    mes INT NOT NULL,
    ano INT NOT NULL,
    tentativas INT NOT NULL,
PRIMARY KEY (email,dia,mes,ano),
FOREIGN KEY (email) REFERENCES d_utilizador (email)
);



# inserir dados na tabela d_tempo
INSERT IGNORE INTO d_tempo (dia, mes, ano)
SELECT DAY(L.moment), MONTH(L.moment), YEAR(L.moment)
FROM login L;
INSERT INTO d_tempo (NULL,NULL,NULL);

# inserir dados na tabela d_utilizador
INSERT INTO d_utilizador (email,nome,pais,categoria)
SELECT U.email, U.nome, U.pais, U.categoria
FROM utilizador U;

# inserir dados na tabela tentativas_login
INSERT INTO tentativas_login (tentativas,email,dia,mes,ano)
SELECT COUNT(contador_login), U.email, DAY(L.moment), MONTH(L.moment), YEAR(L.moment)
FROM utilizador U LEFT OUTER JOIN login L ON U.userid=L.userid
GROUP BY U.userid, DAY(L.moment), MONTH(L.moment), YEAR(L.moment);




# b) media de tentativas de login para todos os utilizadores de Portugal, em cada categoria, com rollup por ano e mes
SELECT * FROM (
    SELECT AVG(tentativas), T.email, T.ano, T.mes
    FROM tentativas_login T, d_utilizador U WHERE T.email=U.email
    AND U.pais="Portugal"
    GROUP BY U.categoria
) a
GROUP BY ano,mes WITH ROLLUP;

