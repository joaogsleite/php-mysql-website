<?php
include("session.php"); 
$page=$_GET['page'];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bloco de Notas</title>
    <meta name="description" content="">
    <meta name="author" content="77896,77907,77969">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top">     
            <div class="navbar-header">
                <a class="navbar-brand" href="./index.php">Bloco de Notas</a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li><a href="#"><i class="fa fa-gear"></i> &nbsp;Definições</a></li>
                <li><a href="#"><i class="fa fa-user"></i> &nbsp;Perfil</a></li>
                <li><a href="./logout.php"><i class="fa fa-sign-out"></i> &nbsp;Sair</a></li>
            </ul>
        </nav>

        <div id="page-wrapper">


            <div class="row">
                <div class="col-md-12"><br>

                <?php if(isset($_POST['registo'])){ ?>


                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    O registo <?php echo $_POST['registo']; ?> foi adicionado com sucesso.
                </div>
                <?php 
                    $nome_registo=$_POST['registo'];
                    $typeid=$_POST['typeid'];

                    $mysql->query("
                        INSERT INTO registo (userid,typecounter, regcounter, nome,ativo)
                        SELECT '".$userid."','".$typeid."', max(regcounter)+1, '".$nome_registo."','1'  
                        FROM registo WHERE userid='".$userid."'"); 

                    $reg = $mysql->query("SELECT max(regcounter) as regid  FROM registo WHERE userid=$userid"); 
                    $regid = $reg->fetch_assoc();
                    $regid = $regid['regid'];


                    $mysql->query("
                        INSERT INTO reg_pag (userid,pageid,typeid,regid,ativa)
                        VALUES ('".$userid."','".$page."','".$typeid."','".$regid."','1')
                    ");

                    $campos = $mysql->query("SELECT C.campocnt, C.nome FROM tipo_registo T, campo C WHERE T.typecnt=C.typecnt AND C.ativo=1 AND T.typecnt=$typeid"); 
                    while($campo = $campos->fetch_assoc()) {
                        $campo_id=$campo['campocnt'];
                        $campo_valor=$_POST[$campo_id];
                        $mysql->query("
                            INSERT INTO valor (userid,typeid,regid,campoid,valor,ativo)
                            SELECT $userid,$typeid,$regid,$campo_id,".'"'.$campo_valor.'"'.",1
                        ");     
                    }

                } ?>

                </div>
            </div>
            
            <div class="row">
                
                <!-- PAGINA -->
                <?php
                    $pagina = $mysql->query("SELECT pagecounter,nome FROM pagina WHERE userid=$userid AND pagecounter=$page AND ativa=1");
                    if($pagina->num_rows == 1){
                        $row = $pagina->fetch_assoc();
                        $pageid=$row['pagecounter'];
                ?>
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-3">
                                    <i class="fa fa-files-o fa-5x"></i>
                                </div>
                                <div class="col-xs-9 text-right">
                                    <h2><?php echo $row['nome']; ?></h2>
                                    <div><a data-toggle="modal" data-target="#modalp-edit" href="#">Editar nome</a></div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalp-edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel2">Editar página</h4>
                                    </div>
                                    <form role="form" method="post" action="./pagina.php?page=<?php echo $pageid; ?>">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Nome da página</label>
                                            <input name="pagina" type="hidden" value="<?php echo $pageid; ?>">
                                            <input name="nome" value="<?php echo $row['nome']; ?>" class="form-control" placeholder="Página">
                                        </div>   
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                        <input type="submit" class="btn btn-success" value="Editar" />
                                    </div>
                                    </form>
                                </div>
                            </div>
                        </div>


                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Registos</th>
                                        <th>Campos</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $registos = $mysql->query("
SELECT A.regcounter, A.typecounter, A.campocnt, A.rnome, A.cnome, B.valor, B.idseq FROM (
    SELECT R.regcounter, R.typecounter, C.campocnt, R.nome rnome, C.nome cnome
    FROM reg_pag RP, campo C, tipo_registo T, registo R
    WHERE RP.typeid=C.typecnt AND T.typecnt=RP.typeid AND RP.regid=R.regcounter
    AND RP.ativa=1 AND C.ativo=1 AND R.ativo=1 AND T.ativo=1 AND R.regcounter=RP.regid
    AND RP.pageid=$pageid
) A LEFT OUTER JOIN (
    SELECT * FROM valor V WHERE V.ativo=1
) B ON A.regcounter=B.regid AND A.campocnt=B.campoid
WHERE IF(B.valor IS NULL,1,B.idseq >= ALL( SELECT X.idseq FROM valor X WHERE X.regid=A.regcounter AND X.campoid=A.campocnt ))
ORDER BY regcounter, campocnt
                                    ");
                                    if($registos->num_rows > 0){
                                    $last=0;
                                    while($registo = $registos->fetch_assoc()) {
                                        $tipo_registo=$registo['typecounter'];
                                        if($registo['regcounter']!=$last){
                                            if($last!=0){ ?>  </tbody></table></td><td></td></tr> <?php }
                                            $last=$registo['regcounter'];
                                            ?>
                                            <tr>
                                            <td><?php echo $registo['regcounter']; ?></td>
                                            <td><?php echo $registo['rnome']; ?></td>
                                            <td class="campos">
                                                <table class="table"><tbody>
                                                    <tr><td><?php echo $registo['cnome']; ?></td><td>=</td><td><?php echo $registo['valor']; ?></td></tr>
                                    <?php }else{ ?>
                                        <tr><td><?php echo $registo['cnome']; ?></td><td>=</td><td><?php echo $registo['valor']; ?></td></tr>
                                    <?php }}} else{ echo "<tr><td></td><td>(não há registos associados)</td><td></td><td></td></tr>"; } ?>
                                    </tbody></table></td><td></td></tr>  
                                </tbody>
                            </table>
                        </div>
                        <a data-toggle="modal" data-target="#modalr-add" href="#">
                            <div class="panel-footer">
                                <span class="pull-left">Associar registo</span>
                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                <div class="clearfix"></div>
                            </div>
                        </a>
                        <div class="modal fade" id="modalr-add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel2">Associar registo</h4>
                                    </div>
                                    <div class="modal-body">
                                        <select id='mySelect' class="form-control">
                                            <?php $i=0;
                                            $tipos = $mysql->query("SELECT T.nome,T.typecnt FROM tipo_registo T WHERE T.userid=$userid AND T.ativo=1"); 
                                            while($tipo = $tipos->fetch_assoc()) {
                                            ?>
                                            <option value="<?php echo $i; ?>"><?php echo $tipo['nome']; ?></option>
                                            <?php $i++; } ?>
                                        </select>
                                        <ul class="nav nav-tabs" id="myTab" style="display:none;">
                                            <?php $i=0;
                                            $tipos = $mysql->query("SELECT T.nome,T.typecnt FROM tipo_registo T WHERE T.userid=$userid AND T.ativo=1"); 
                                            while($tipo = $tipos->fetch_assoc()) {
                                            ?>
                                            <li><a href="#<?php echo $i; ?>"><?php echo $tipo['nome']; ?></a></li>
                                            <?php $i++; } ?>
                                        </ul>
                                        <br>
                                        <div class="tab-content">
                                            <?php $i=0;
                                            $tipos = $mysql->query("SELECT T.nome,T.typecnt FROM tipo_registo T WHERE T.userid=$userid AND T.ativo=1"); 
                                            while($tipo = $tipos->fetch_assoc()) {
                                            ?>
                                            <div class="tab-pane" id="<?php echo $i; ?>">
                                            <form role="form" method="post" action="./pagina.php?page=<?php echo $pageid; ?>">



                                            <div class="form-group">
                                                <label>Nome do registo</label>
                                                <input name="registo" class="form-control input-lg" placeholder="Registo">
                                                <input name="typeid" type="hidden" value="<?php echo $tipo['typecnt']; ?>">
                                                <input type="hidden" name="page" value="<?php echo $pageid; ?>" />
                                            </div> 
                                                <br>
                                            <div class="form-horizontal">

                                                <?php
                                                    $campos = $mysql->query("SELECT C.campocnt, C.nome FROM tipo_registo T, campo C WHERE T.typecnt=C.typecnt AND C.ativo=1 AND T.typecnt=".$tipo['typecnt']); 
                                                    while($campo = $campos->fetch_assoc()) {
                                                ?>

                                                <div class="form-group">
                                                    <label class="col-sm-3 control-label"><?php echo $campo['nome']; ?></label>
                                                    <div class="col-sm-9">
                                                        <input name="<?php echo $campo['campocnt']; ?>" class="form-control" placeholder="Valor">
                                                    </div>
                                                </div> 
                                                <?php } ?>
                                            </div>
                                            <div class="modal-footer">
                                            
                                            <input type="submit" class="btn btn-success" value="Adicionar" />
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                                            </div>
                                            </form>
                                            </div>
                                            <?php $i++; } ?>

                                            
                                        </div>

                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }else{ echo "Página não existe!"; } ?>


            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://getbootstrap.com/2.3.2/assets/js/bootstrap-tab.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.2.0/metisMenu.min.js"></script>
    <script>
        $('#mySelect').on('change', function (e) {
            $('#myTab li a').eq($(this).val()).tab('show'); 
        });
    </script>
    <?php $mysql->close(); ?>

</body>
</html>