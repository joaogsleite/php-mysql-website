/* Pergunta 1 */
SELECT nome FROM Tipos WHERE email='Manuel@notebook.pt';


/* Pergunta 2 */
SELECT DISTINCT email FROM Historico WHERE accao='login_fail';


/* Pergunta 3 */
SELECT nascimento FROM Utilizadores WHERE email IN 
(
	SELECT email FROM
	(
		SELECT * FROM Registos WHERE nome="facebook"
		INTERSECT
		SELECT * FROM Paginas WHERE nome="facebook"
	)
);