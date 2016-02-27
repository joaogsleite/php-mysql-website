CREATE TABLE IF NOT EXISTS utilizador (
    userid INT NOT NULL  AUTO_INCREMENT,
    email VARCHAR(255) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    questao1 VARCHAR(255) NOT NULL,
    resposta1 VARCHAR(255) NOT NULL,
    questao2 VARCHAR(255),
    resposta2 VARCHAR(255),
    pais VARCHAR(45) NOT NULL,
    categoria VARCHAR(45) NOT NULL,
PRIMARY KEY (userid),
UNIQUE INDEX email_UNIQUE (email) 
);

CREATE TABLE IF NOT EXISTS  login (
    contador_login INT NOT NULL AUTO_INCREMENT,
    userid INT NOT NULL,
    sucesso TINYINT(1) NOT NULL,
    moment TIMESTAMP NOT NULL,
PRIMARY KEY (contador_login),
FOREIGN KEY (userid) REFERENCES utilizador (userid) ON DELETE CASCADE 
ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS sequencia (
    contador_sequencia INT NOT NULL AUTO_INCREMENT,
    moment TIMESTAMP NOT NULL,
    userid INT NOT NULL,
PRIMARY KEY (contador_sequencia),
FOREIGN KEY (userid) REFERENCES utilizador (userid) ON DELETE CASCADE 
ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS  tipo_registo (
    userid INT NOT NULL,
    typecnt INT NOT NULL,
    nome MEDIUMTEXT NOT NULL,
    ativo TINYINT(1) NOT NULL,
    idseq INT NULL,
    ptypecnt INT,
PRIMARY KEY (userid, typecnt),
FOREIGN KEY (userid) REFERENCES utilizador (userid) ON DELETE CASCADE  
ON UPDATE CASCADE,
FOREIGN KEY (userid, ptypecnt) REFERENCES tipo_registo (userid, typecnt),
FOREIGN KEY (idseq) REFERENCES  sequencia (contador_sequencia)
);

CREATE TABLE IF NOT EXISTS registo (
    userid INT NOT NULL,
    typecounter INT NOT NULL,
    regcounter INT NOT NULL,
    nome VARCHAR(1024),
    ativo TINYINT(1),
    idseq INT NULL,
    pregcounter INT,
PRIMARY KEY (userid, regcounter, typecounter),
FOREIGN KEY (idseq) REFERENCES  sequencia (contador_sequencia),
FOREIGN KEY (userid , typecounter) REFERENCES tipo_registo (userid ,  typecnt),
FOREIGN KEY (userid, pregcounter, typecounter) 
REFERENCES registo (userid, regcounter, typecounter)
);

CREATE TABLE IF NOT EXISTS pagina (
    userid INT NOT NULL,
    pagecounter INT NOT NULL,
    nome VARCHAR(1024) NOT NULL,
    idseq INT NOT NULL,
    ativa TINYINT(1) NOT NULL,
    ppagecounter INT NULL,
PRIMARY KEY (userid, pagecounter) ,
FOREIGN KEY (userid) REFERENCES utilizador (userid) ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (idseq) REFERENCES  sequencia (contador_sequencia),
FOREIGN KEY (userid, ppagecounter) REFERENCES pagina (userid, pagecounter) 
);

CREATE TABLE IF NOT EXISTS campo (
    userid INT NOT NULL,
    typecnt INT NOT NULL,
    campocnt INT NOT NULL,
    idseq INT NOT NULL,
    ativo TINYINT(1) NOT NULL,
    nome VARCHAR(1024) NOT NULL,
    pcampocnt INT,
PRIMARY KEY (userid, typecnt, campocnt) ,
FOREIGN KEY (userid , typecnt) REFERENCES tipo_registo (userid , typecnt)
ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (idseq) REFERENCES  sequencia (contador_sequencia),
FOREIGN KEY (userid, typecnt, pcampocnt) REFERENCES  campo  (userid, typecnt, campocnt)
);

CREATE TABLE IF NOT EXISTS valor (
    userid INT NOT NULL,
    typeid INT NOT NULL,
    regid INT NOT NULL,
    campoid INT NOT NULL,
    valor LONGTEXT NULL,
    idseq INT NOT NULL,
    ativo TINYINT(1) NOT NULL,
    pcampoid INT,
PRIMARY KEY (userid, regid, typeid, campoid) ,
FOREIGN KEY (userid,typeid, campoid) REFERENCES campo (userid, typecnt , campocnt)
ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (userid, regid, typeid) REFERENCES registo (userid , regcounter,typecounter)
ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (idseq) REFERENCES  sequencia (contador_sequencia),
FOREIGN KEY (userid, regid, typeid, pcampoid) REFERENCES valor (userid, regid, typeid, campoid) 
 );
 
CREATE TABLE IF NOT EXISTS reg_pag(
    idregpag INT NOT NULL  AUTO_INCREMENT,
    userid INT NOT NULL,
    pageid INT NOT NULL,
    typeid INT NOT NULL,
    regid INT NOT NULL,
    idseq INT NOT NULL,
    ativa TINYINT(1) NOT NULL,
    pidregpag INT,
PRIMARY KEY (idregpag),
FOREIGN KEY (userid ,pageid) REFERENCES pagina (userid,pagecounter)
ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (userid, regid, typeid) REFERENCES registo (userid, regcounter, typecounter)
ON DELETE CASCADE  ON UPDATE CASCADE,
FOREIGN KEY (idseq) REFERENCES  sequencia (contador_sequencia),
FOREIGN KEY (pidregpag) REFERENCES  reg_pag (idregpag)
 );



 

