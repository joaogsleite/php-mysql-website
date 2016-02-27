
# query alinea a)

SELECT AVG(quantos) q, userid FROM (
	SELECT COUNT(X.regid) quantos, P.pagecounter, P.userid FROM 
		(SELECT * FROM pagina P2 WHERE P2.ativa=1) P LEFT OUTER JOIN ( SELECT * FROM 
			(SELECT typeid as rptypeid,pageid,regid,ativa as rpativa FROM reg_pag RP2 
WHERE RP2.ativa=1) RP,
			(SELECT regcounter,userid,ativo as rativo FROM registo R2 WHERE R2.ativo=1) R,
			(SELECT typecnt,ativo as tativo FROM tipo_registo T2 WHERE T2.ativo=1) T 
			WHERE RP.regid=R.regcounter AND RP.rptypeid=T.typecnt
		) X ON P.pagecounter=X.pageid
	GROUP BY P.pagecounter
) a GROUP BY userid;

# indices

CREATE INDEX RegIndex ON
registo(regcounter,ativo)
USING BTREE;

CREATE INDEX RegPagIndex ON
reg_pag(regid,pageid,ativa)
USING BTREE;

CREATE INDEX PagIndex ON
pagina(pagecounter,ativa)
USING BTREE;

CREATE INDEX TipoIndex ON
tipo_registo(typecnt,ativo)
USING BTREE;






# query alinea b)

SELECT P.userid, P.nome AS pagina, R.nome AS registo FROM registo R, reg_pag RP, pagina P, tipo_registo T 
WHERE RP.pageid=P.pagecounter AND RP.userid=P.userid AND R.regcounter=RP.regid AND R.userid=RP.userid AND R.typecounter=T.typecnt AND R.userid=T.userid AND R.ativo=1 AND RP.ativa=1 AND T.ativo=1 AND P.ativa=1
#AND P.userid= AND P.pagecounter=;
GROUP BY P.userid, P.pagecounter;

# indices

CREATE INDEX RegIndex ON
registo(regcounter,ativo)
USING BTREE;

CREATE INDEX RegNomeIndex ON
registo(regcounter,nome)
USING BTREE;

CREATE INDEX RegTypeIndex ON
registo(regcounter,typecounter)
USING BTREE;

CREATE INDEX RegPagIndex ON
reg_pag(regid,pageid,ativa)
USING BTREE;

CREATE INDEX PagIndex ON
pagina(pagecounter,ativa)
USING BTREE;

CREATE INDEX TipoIndex ON
tipo_registo(typecnt,ativo)
USING BTREE;
