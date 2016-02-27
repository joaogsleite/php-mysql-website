
#SECCAO2

#alinea a)
SELECT userid FROM utilizador U WHERE
(SELECT COUNT(*) FROM login WHERE sucesso=0 AND userid=U.userid) 
>
(SELECT COUNT(*) FROM login WHERE sucesso=1 AND userid=U.userid)


#alinea b)
SELECT a.regid, a.userid FROM (
	SELECT COUNT(P.pagecounter) rpaginas, P.userid, P.pagecounter, RP.regid 
	FROM pagina P LEFT OUTER JOIN reg_pag RP ON P.userid=RP.userid AND P.pagecounter=RP.pageid 
	WHERE P.ativa=1 AND RP.ativa=1
	GROUP BY RP.regid
) a, (
	SELECT COUNT(P.pagecounter) as upaginas, P.userid 
	FROM pagina P WHERE P.ativa=1 GROUP BY P.userid
) b, registo R, tipo_registo T
WHERE a.userid=b.userid AND R.regcounter=a.regid AND R.typecounter=T.typecnt AND R.userid=T.userid
AND rpaginas=upaginas AND R.ativo=1 AND T.ativo=1


#alinea c)
DROP TEMPORARY TABLE IF EXISTS media1,media2;
CREATE TEMPORARY TABLE media1 AS ( 
	SELECT AVG(quantos) q, userid FROM (
		SELECT COUNT(X.regid) quantos, P.pagecounter, P.userid FROM 
			(SELECT * FROM pagina P2 WHERE P2.ativa=1) P LEFT OUTER JOIN ( SELECT * FROM 
				(SELECT typeid as rptypeid,pageid,regid,ativa as rpativa FROM reg_pag RP2 WHERE RP2.ativa=1) RP,
				(SELECT regcounter,userid,ativo as rativo FROM registo R2 WHERE R2.ativo=1) R,
				(SELECT typecnt,ativo as tativo FROM tipo_registo T2 WHERE T2.ativo=1) T 
				WHERE RP.regid=R.regcounter AND RP.rptypeid=T.typecnt
			) X ON P.pagecounter=X.pageid
		GROUP BY P.pagecounter
	) a GROUP BY userid
);
CREATE TEMPORARY TABLE media2 AS (SELECT * FROM media1);
SELECT userid FROM media1 WHERE q=(SELECT max(q) FROM media2);


#alinea d)
SELECT userid FROM (
	SELECT X.userid,X.regid,X.pageid, X.typeid FROM (
		SELECT P.userid,RP.regid,RP.pageid, RP.typeid FROM pagina P LEFT OUTER JOIN reg_pag RP 
		ON P.userid=RP.userid AND P.pagecounter=RP.pageid
		WHERE P.ativa=1 AND RP.ativa=1
	) X, registo R, tipo_registo T WHERE X.userid=R.userid AND X.regid=R.regcounter AND X.userid=T.userid AND X.typeid=T.typecnt AND R.ativo=1 AND T.ativo=1
	GROUP BY X.pageid
	HAVING COUNT(DISTINCT X.typeid) =(SELECT COUNT(DISTINCT T.typecnt) FROM tipo_registo T WHERE T.userid=X.userid AND T.ativo=1)
) a 
GROUP BY a.userid
HAVING COUNT(pageid)=(SELECT COUNT(pagecounter) FROM pagina PP WHERE PP.userid=a.userid AND PP.ativa=1)


