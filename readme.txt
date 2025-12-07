
MATRIZ DE COMPATIBILIDAD
https://docs.microsoft.com/en-us/sql/connect/php/microsoft-php-drivers-for-sql-server-support-matrix?view=sql-server-ver15#see-also
Esta andando con SQL 2008 R2
php-7.1.29-nts-Win32-VC14-x64
Driver SQLSRV 5.6            


BASE DE DATOS
192.168.0.53  sa / einstein1.  --> windows (DESKTOP-8EH5718) SQL 2019 (migracion britanico)
              sa / sql2008     --> windows (DESKTOP-8EH5718\SQL2008) SQL 2008 (britanico legacy)
              sa / desarrollo  --> VM CCO (VM Ware Player) (CCO / #Dani.2021)
192.168.0.6   sa / RoxyAmor13! --> ubuntu docker (sql 2017)
192.168.0.77  sa / luchito     --> VM SQL2008 (Virtual Box)   
172.27.4.38   sa / peperina    --> windows SF (172.27.4.38\SQLEXPRESS2008) SQL 2008 (britanico legacy SF)
172.27.4.35   sa / desarrollo  --> windows SF (172.27.4.35\SQLEXPRESS2019) SQL 2019 (migracion britanico SF)           
-----------------------------------------------------
SVN
https://www.connolly.com.ar/neodata/
luciano
Linux123
-----------------------------------------------------
VM Britanico Viejo Legacy
cco / #Dani.2021
DB sa / desarrollo
-----------------------------------------------------

Britanico PHP
-------------------
SF
cd /d C:\luciano\SVN\neodata\Britanico\trunk
C:\php-7.1.29-nts-Win32-VC14-x64\php.exe -S 0.0.0.0:4001
-------------------
luciano@luciano-HP:~/svncco/neodata/Britanico/trunk$ php -S 0.0.0.0:4001
http://localhost:4001
neodata / 1 ( c4ca4238a0b923820dcc509a6f75849b   select * from neo_britanico.dbo.mod_backend_users)
anamaria / anamaria

Britanico Legacy
-------------------
http://localhost:8008 -> en windows
neoadmin / 55AraucariA (select * from neodata.dbo.usuarios)
mfselva / Pampuchi20 (select * from neodata.dbo.usuarios)

Disidentes
-------------
/home/luciano/cementerio/DisidentesPHP/Disidentes/trunk$ php -S 0.0.0.0:8000 
F:\Lu\Repositorios\cementerio\DisidentesNEW\DisidentesSVN\trunk> php -S 0.0.0.0:8000 
localhost:8000  ------> Disidentes
neodata / 1

Windows:
PS F:\Lu\SvnRepoCCO\neodata\Britanico\trunk> php -version
PHP 7.4.15 (cli) (built: Feb  2 2021 20:47:45) ( ZTS Visual C++ 2017 x64 )
Copyright (c) The PHP Group
Zend Engine v3.4.0, Copyright (c) Zend Technologies
    with Xdebug v3.0.3, Copyright (c) 2002-2021, by Derick Rethans
C:\php-7.4.15-Win32-vc15-x64





https://tutorialforlinux.com/2020/03/24/step-by-step-pecl-ubuntu-20-04-installation-guide/2/
sudo apt install php-pear php-dev

https://docs.microsoft.com/en-us/sql/connect/php/installation-tutorial-linux-mac?view=sql-server-ver15
sudo pecl install sqlsrv
sudo pecl install pdo_sqlsrv
sudo su
printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/8.0/mods-available/sqlsrv.ini
printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/8.0/mods-available/pdo_sqlsrv.ini
exit
sudo phpenmod -v 8.0 sqlsrv pdo_sqlsrv


https://fioriticarlos.wordpress.com/2019/09/23/como-instalar-drivers-de-sql-server-para-php-oficiales-de-microsoft/
sudo pecl install sqlsrv
sudo pecl install pdo_sqlsrv
sudo su
printf "; priority=20\nextension=sqlsrv.so\n" > /etc/php/7.2/mods-available/sqlsrv.ini
printf "; priority=30\nextension=pdo_sqlsrv.so\n" > /etc/php/7.2/mods-available/pdo_sqlsrv.ini
exit
sudo phpenmod -v 7.2 sqlsrv pdo_sqlsrv



Configuracion XDebug:
https://www.seedem.co/es/blog/como-configurar-xdebug-vscode
https://www.espai.es/blog/2018/09/como-depurar-codigo-php-con-visual-studio-code/
https://medium.com/@jose.gonzaleza1/php-xdebug-vscode-%EF%B8%8F-6d45033bb1df
https://www.cloudways.com/blog/php-debug/


Routers:
https://stackoverflow.com/questions/14783666/codeigniter-htaccess-and-url-rewrite-issues
https://gist.github.com/aenglander/8e2f83c4526fccdcdead
https://stackoverflow.com/questions/27381520/php-built-in-server-and-htaccess-mod-rewrites
https://stackoverflow.com/questions/27381520/php-built-in-server-and-htaccess-mod-rewrites
php -S localhost:8080 server2.php  <--------  Este !!
https://stackoverflow.com/questions/27381520/php-built-in-server-and-htaccess-mod-rewrites
php -S localhost:8888 server1.php



docker container ls -a
docker ps -l
docker start serene_yalow
docker stop serene_yalow

docker exec -it 54c4d8843b0a /opt/mssql-tools/bin/sqlcmd -S localhost -U sa -P RoxyAmor13!



Britanico
--------------------------------------------------

Por aca arranca el menu -> Parcelas

SELECT TOP (1000) [id]
      --,[code]
      ,[description]
      --,[created]
      --,[verified]
      --,[offline]
      ,[icon]
      ,[id_parent]
      ,[data_module]
      ,[data_model]
      ,[data_table]
      --,[fum]
      --,[priority]
      ,[data_action]
      --,[running]
      ,[brief]
      --,[show_brief]
      --,[alert_build]
  FROM [neo_britanico].[dbo].[mod_backend_functions]
  where code like '%Parcela%'
  
  id	description	      icon	      id_parent	data_module	  data_model	data_table	data_action	brief
1046	Menú parcelas	    view_module	NULL					                                              NULL
1055	Submenú parcelas	tab	        1046	    mod_britanico	Parcela	    Parcela	    brow	      NULL

select top 100 * from neo_britanico.dbo.mod_backend_log_general order by id desc

SELECT *
FROM neo_britanico.dbo."mod_backend_Files_attached"
WHERE "table_rel" = 'mod_britanico_Parcela' AND "id_rel" IS NULL
ORDER BY "description" ASC
 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY


SELECT count(*) as total
FROM neo_britanico.dbo."Parcela"
WHERE "id" IS NULL

select *
FROM neo_britanico.dbo."Parcela"
WHERE "id" IS NULL

--------> id_Parcela
ERROR - 2021-11-29 20:47:21 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Invalid column 
name 'id'. - Invalid query: SELECT count(*) as total
FROM "Parcela"
WHERE "id" IS NULL
ERROR - 2021-11-29 20:47:21 --> INNER ERROR Británico [MY_Model::get] 0 Call to a member function result_array() on bool
ERROR - 2021-11-29 20:47:21 --> cco-> pasando x edit  de mymodel!.

sys.cols
mod_backend_rel_groups_functions
mod_backend_rel_users_groups
control_lotes_tarjetas


Ahora modifique vw_parcela
ALTER view [dbo].[vw_parcela] as 
select 
   p.id_Parcela as id, --id_Parcela
   
   para ver si toma bien la PK...pero no...
   
 sigue intentando con is null en lugar de con un valor...
 ERROR - 2021-12-01 01:59:56 --> sql2 SELECT *
FROM "Parcela"
WHERE "id_Parcela" IS NULL
 ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY
ERROR - 2021-12-01 02:00:23 --> INNER ERROR Británico [MY_Model::get] 0 

tampoco logre que tome la vista en lugar de la tabla fisica.

    public function edit($values){
        try {

            $location=explode("::",strtolower(__METHOD__));
            $values["interface"]=(MOD_BRITANICO."/".$location[0]."/abm");
            $values["page"]=1;
            
            $values["table"]="vw_parcela"; // CCOO          <---------------
            $values["view"]="Parcela";

segun chris el mejor ejemplo para tomar de Disidentes algo mas complejo es Sac_Lotes. Donde para algunas cosas 
usa la vista y para las DDL usa la tabla fisica.




cual es la diferencia entre:
view
table
?


ojo con el if( $records2 === false )  en lugar de if( !$records2 )


probar tambien haciendo backup de la tabla y renombrando la PK a id
select into ParcelaBCK * from Parcela
exec dbo.sp_rename 'Parcela.id_Parcela', 'id', 'COLUMN';

<tr data-table="Parcela" data-module="mod_britanico" data-model="Parcela" data-id="26" class="record-dbl-click 
record-26"><td style="width:50px;"><button type="button" class="btn btn-raised btn-record-edit btn-info btn-sm" 
data-id="26" data-module="mod_britanico" data-model="Parcela" data-table="Parcela"><i class="material-icons">edit
</i></button></td> <td class=""><p class="text-monospace text-break" style="display:block;">1</p></td> 
<td class=""><p class="text-monospace text-break" style="display:block;">R </p></td> <td class=""><p 
class="text-monospace text-break" style="display:block;">42</p></td> <td class=""><p class="text-monospace 
text-break" style="display:block;">N</p></td><td align="right" style="width:50px;"><button type="button" 
class="btn btn-raised btn-record-remove btn-danger btn-sm" data-id="26" data-module="mod_britanico" 
data-model="Parcela" data-table="Parcela"><i class="material-icons">delete_forever</i></button></td><td></td></tr>

Parece que anda:
FUNCTIONS.js onRecordEdit _json ..
VM26:709 * {"new-files":[],"new-links":[],"del-files":[],"del-links":[],"new-messages":[],"new-folder-items":[],
"id":"2","module":"mod_britanico","model":"Parcela","table":"Parcela","page":"1"} *
:4001/index.php/api.backend/neocommand:1 Failed to load resource: the server responded with a status of 
500 (Internal Server Error)
:4001/#:1 Uncaught (in promise) Internal Server Error


My_Model
Edit
            $data["parameters"] = $values;
            $data["title"] = ucfirst(lang("m_".strtolower($values["model"])));
            log_message('error', 'cco-> antes de load view en  edit  de mymodel!.');
            $html=$this->load->view($values["interface"],$data,true);

volver a debugear


ERROR - 2021-12-03 09:48:52 --> sql2 SELECT *
FROM "Parcela"
WHERE "id" = 26
 ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY
ERROR - 2021-12-03 09:49:37 --> cco-> pasando x edit  de mymodel!.
ERROR - 2021-12-03 09:49:57 --> sql1 SELECT count(*) as total
FROM "mod_backend_Files_attached"
WHERE "table_rel" = 'mod_britanico_vw_parcela' AND "id_rel" = 26
ERROR - 2021-12-03 09:50:25 --> sql2 SELECT *
FROM "mod_backend_Files_attached"
WHERE "table_rel" = 'mod_britanico_vw_parcela' AND "id_rel" = 26
ORDER BY "description" ASC
 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY
ERROR - 2021-12-03 09:51:05 --> sql1 SELECT count(*) as total
FROM "mod_backend_Messages_attached"
WHERE "table_rel" = 'Parcela' AND "id_rel" = 26
ERROR - 2021-12-03 09:51:32 --> sql2 SELECT *
FROM "mod_backend_Messages_attached"
WHERE "table_rel" = 'Parcela' AND "id_rel" = 26
 ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY
ERROR - 2021-12-03 09:52:15 --> cco-> antes de load view en  edit  de mymodel!.





SELECT *
FROM "Parcela"
WHERE "id" = 26
 ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY


SELECT count(*) as total
FROM "mod_backend_Files_attached"
WHERE "table_rel" = 'mod_britanico_vw_parcela' AND "id_rel" = 26

SELECT *
FROM "mod_backend_Files_attached"
WHERE "table_rel" = 'mod_britanico_vw_parcela' AND "id_rel" = 26
ORDER BY "description" ASC
 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY

ESELECT count(*) as total
FROM "mod_backend_Messages_attached"
WHERE "table_rel" = 'Parcela' AND "id_rel" = 26


SELECT *
FROM "mod_backend_Messages_attached"
WHERE "table_rel" = 'Parcela' AND "id_rel" = 26
 ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY


"mod_britanico/parcela/abm" ---> vista/modelo


ERROR - 2021-12-04 17:20:50 --> sql2 SELECT *
FROM "mod_backend_Users"
WHERE "id" = '2' AND "token_authentication" = '6a36e01c064845d336a54ea336828c8ae079f5e9ec68e8f728cdf0d85db92c9bef29aa2f2dbc014a0c6485613b3bc589b581c6af6ff629f70c8d0942b09d5de6c330db3774fb80c5ec676840d917f85c2962537cdbefc9e7509304405883112d0e05988ca3ed524efb1374235b7d9bb7db66179e3cfcc583188b60b745d300de' AND "offline" IS null
 ORDER BY 1 OFFSET 0 ROWS FETCH NEXT 25 ROWS ONLY
ERROR - 2021-12-04 17:20:50 --> Query error: [Microsoft][ODBC Driver 17 for SQL Server][SQL Server]Invalid column 
name 'code'. - Invalid query: UPDATE "Parcela" SET "code" = NULL, "description" = NULL, "fum" = 
'2021-12-04 17:20:50'
WHERE "id" = 2
ERROR - 2021-12-04 17:20:50 --> INNER ERROR Británico [MY_Model::save] 42 [Microsoft][ODBC Driver 17 for SQL 
Server][SQL Server]Invalid column name 'code'.

Hay que seguir viendo como hacer para que lleguen especificados los fields en $values asi no usa los campos por 
default, que por ej la tabla Parcela ni siquiera tiene.

--------------------------> para guardar correctaemnte una edicion, debe estar implementado y con los $fields 
enumerados, el metodo save del modelo. (Parcela en este caso)
Seguir probando para hacer un insert. 
Luego, ver de volver a poner la PK como Id_Parcela en vez de id (como era originalemente.)




https://stackoverflow.com/questions/137487/null-vs-false-vs-0-in-php
https://itqna.net/questions/1476/difference-between-null-empty-0-and-false





* Britanico Legacy
-----------------------------------------------------------------------------------
Corre en SQL2008 - SQL2005 - SQL2000 (compatibility level) por un tema de outer join

Para configurar conexion remota desde linux hay problemas de SSL

A connection was successfully established with the server, but then an error occurred during the pre-login handshake. (provider: SSL Provider, error: 31 - Encryption(ssl/tls) handshake failed)

https://docs.microsoft.com/en-us/dotnet/core/compatibility/cryptography/5.0/default-cipher-suites-for-tls-on-linux
https://docs.microsoft.com/en-us/sql/connect/ado-net/sqlclient-troubleshooting-guide?view=sql-server-ver15#login-phase-errors
https://github.com/microsoft/azuredatastudio/issues/11249
https://discourse.ubuntu.com/t/default-to-tls-v1-2-in-all-tls-libraries-in-20-04-lts/12464/8



Buscando Cuentas:
SELECT TOP (1000) [id]
      ,[code]
      ,[description]
      --,[created]
      --,[verified]
      --,[offline]
      ,[icon]
      ,[id_parent]
      ,[data_module]
      ,[data_model]
      ,[data_table]
      --,[fum]
      --,[priority]
      ,[data_action]
      --,[running]
      --,[brief]
      --,[show_brief]
      --,[alert_build]
  FROM [neo_britanico].[dbo].[mod_backend_functions]
  where code like '%cuenta%' or id = 1050 or id_parent = 1050

CUENTAS:
El menu     Cuentas -> ABCM Titular    referencia:

   class="list-group-item bg-light btn-menu-click btn-m_abcm" data-alert="0" data-module="mod_britanico" data-model="Con_cuentas" data-table="Con_cuentas" data-action="brow"  <td valign="middle" class="label-menu">ABCM - Titular</td> 

  id    description                          icon         id_parent  data_module    data_model        data_table        data_action     brief
1050    Menú cuentas                         view_module  NULL                                                                          NULL
1064    Submenú movimiento de cuenta corrien tab          1048       mod_britanico  CuentaCorriente   CuentaCorriente   brow            NULL
1071    Submenú plan de cuentas              tab          1050       mod_britanico  Con_cuentas                         plan_de_cuentas NULL   <--- (1)
1077    Submenú cuentas                      tab          1052       mod_britanico  Historico                           cuentas         NULL
1078    Submenú plan de cuentas              tab          1052       mod_britanico  Historico                           plan_de_cuentas NULL


id	code	           description                icon	  id_parent	data_module	data_model	data_table	data_action
1050	m_cuentas          Menú cuentas               view_module NULL				
1069	m_abcm             Submenú ABCM               tab         1050          mod_britanico	Con_cuentas	Con_cuentas	brow
1070	m_listados	   Submenú listados           tab	  1050          mod_britanico	Con_cuentas	                listados
1071	m_plan_de_cuentas  Submenú plan de cuentas    tab	  1050          mod_britanico	Con_cuentas	                plan_de_cuentas



va a haber que armar abm.php
vista
directorio
models -> mod_britanico -> Cuentas.php (1) sera Con_cuentas ??  (/models/mod_britanico/Con_cuentas.php)
views  -> mod_britanico -> cuentas o Con_cuentas? -> abm.php  (/views/mod_britanico/con_cuenta/abm.php)
hay que continuar con el edit
revisar la busqueda con el search, que no hace nada
revisar la busqueda con los filtros que no trae nada.

CON_Cuentas:
Column_name  Type      Computed Length Prec Scale Nullable TrimTrailingBlanks FixedLenNullInSource Collation
ID           int       no       4      10   0     no       (n/a)              (n/a)                NULL
NUMERO       varchar   no       10                yes      no                 yes                  Modern_Spanish_CI_AS
NOMBRE       varchar   no       40                yes      no                 yes                  Modern_Spanish_CI_AS
TIPOAJUSTE   nvarchar  no       2                 yes      (n/a)              (n/a)                SQL_Latin1_General_CP1_CI_AS
SALDONOMIN   money     no       8      19   4     yes      (n/a)              (n/a)                NULL
SALDOAJUST   money     no       8      19   4     yes      (n/a)              (n/a)                NULL

constraint_type	constraint_name	delete_action	update_action	status_enabled	status_for_replication	constraint_keys
PRIMARY KEY (non-clustered)	aaaaaCON_Cuentas1_PK	(n/a)	(n/a)	(n/a)	(n/a)	ID

Table is referenced by foreign key
neo_britanico.dbo.Caja_Tesoreria: FK_CajaTesoreria_PlanDeCuentas
neo_britanico.dbo.Cliente: FK_Cliente_CuentaContable
neo_britanico.dbo.Cuenta_Bancaria: FK_CuentaBancaria_CON_Cuentas
neo_britanico.dbo.EmpresaSucursal: FK_Empresa_CuentaContable_Cobranza
neo_britanico.dbo.EmpresaSucursal: FK_Empresa_CuentaContable_Deudores
neo_britanico.dbo.ListaPrecio: FK_ListaPrecio_CuentaContable
neo_britanico.dbo.Movcaja_imputaciones: FK_MovCajaImp_CuentaContable
neo_britanico.dbo.Movcaja_valores: FK_MovCajaVal_CtaContable
neo_britanico.dbo.Proveedor: FK_Proveedor_CtaContableImputacion
neo_britanico.dbo.Proveedor: FK_Proveedor_CtaContableProveedor
neo_britanico.dbo.RelOPImputacionContable: FP_RelOPImputacion_CtaContable
neo_britanico.dbo.RelReciboValor: FK_RelRCValor_CuentaContable
neo_britanico.dbo.RetencionCobranza: FK_RetencionCobranza_CuentaContable
neo_britanico.dbo.RetencionEnPago: FK_RetencionEnPago_CuentaContable
neo_britanico.dbo.SAC_Operaciones: FK_Operaciones_PlanDecuentas
neo_britanico.dbo.Valor: FK_Valor_CuentaContable
neo_britanico.dbo.Valor: FK_Valor_CuentaContableEgreso


CON_Cuentas_Historico:
Column_name  Type      Computed Length Prec Scale Nullable TrimTrailingBlanks FixedLenNullInSource Collation
ID           int       no       4      10   0     no       (n/a)              (n/a)                NULL
DESDE        datetime  no       8                 yes      (n/a)              (n/a)                NULL
HASTA        datetime  no       8                 yes      (n/a)              (n/a)                NULL
NUMERO       nvarchar  no       20                yes      (n/a)              (n/a)                SQL_Latin1_General_CP1_CI_AS
NOMBRE       nvarchar  no       62                yes      (n/a)              (n/a)                SQL_Latin1_General_CP1_CI_AS
TIPOAJUSTE   nvarchar  no       2                 yes      (n/a)              (n/a)                SQL_Latin1_General_CP1_CI_AS
SALDONOMIN   money     no       8      19   4     yes      (n/a)              (n/a)                NULL
SALDOAJUST   money     no       8      19   4     yes      (n/a)              (n/a)                NULL

--- Recordar que parcela fue una prueba. Esta mal ubicado el menu.
Tabla: neo_britanico.dbo."Parcela" ?? PK?
Hay que ver de pasar en el model del Edit de application/models/mod_britanico/Parcela.php
$values["where"]=("id_Parcela=".$values["id"]); // aca esta llegando un vacio y provoca el null en el where. Hay que pasar un cero?????


Probando en Cuentas -> ABCM Cuentas el boton search (Nombre: CAJA)
No trae nada, da  No se han obtenido datos

FUNCTIONS.js onRecordEdit _json ..
VM26:709 * {"new-files":[],"new-links":[],"del-files":[],"del-links":[],"new-messages":[],"new-folder-items":[],"id":"0","module":"mod_britanico","model":"Con_cuentas","table":"Con_cuentas","page":"1"} *
:4001/index.php/api.backend/neocommand:1 Failed to load resource: the server responded with a status of 500 (Internal Server Error)
:4001/#:1 Uncaught (in promise) Internal Server Error

{"code":5100,"status":"ERROR","message":"Falta el par\u00e1metro 'function'","function":"production"}

-------> Para resolver esto, en la funcion brow me tengo que asegurear los operadores. Aca estaba 
buscando por Nombre, y eso conviene hacer por por operador like

Esto fue con los FILTROS. Y se disparan tocando la lupa del Search.

Filtro:
where: "((nombre like '%CAJA%'))"  -> buscando por un valor
where: "((numero = '11')) AND ((nombre like '%CAJA%'))"  -> buscando por dos.

Search:
No llega nada en el where (where: "")

En el model, en la parte de filter poner el browser_serch y listo


Listados de Cuentas
-------------------
SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, 
	CASE 
		WHEN TIPOAJUSTE='A' THEN 'Automático' 
		ELSE (CASE 
			        WHEN TIPOAJUSTE='D' THEN 'Directo' 
				ELSE 'Sin Ajuste'   -- Son todos tipo S, es correcto el CASE?
			END) 
	END As qryTA,
        TIPOAJUSTE 
FROM neo_britanico.dbo.[CON_Cuentas]
WHERE (NUMERO BETWEEN '0' AND '999999999')
ORDER BY NUMERO



Plan de Cuentas
---------------
No hay distincion entre RUBRO y CUENTA, entonces no aporta nada.

SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, qryTA, ROC,
		REPLICATE('&nbsp;', (   SELECT Count(NRO)
                                        FROM ((SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Rubros]  
                                                        WHERE NUMERO BETWEEN '0' AND '9999999999') 
                                                        UNION
                                                        (SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Cuentas] 
                                                        WHERE NUMERO BETWEEN '0' AND '9999999999')) As S
                                        WHERE Left(NUMERO,Len(S.NRO))=S.NRO 
                                        AND S.NRO<>NUMERO -- muestro lo que sea distinto, esto arma estructura jerarquica cuando rubo tiene una codificacion difrente a cuentas (por ej 1  11   111  etc)
                                        )
		         As qryIndent,
		(SELECT Count(NRO)
		FROM ((SELECT NUMERO As NRO, NOMBRE As NOM, 'R' as ROC 
                        FROM [CON_Rubros]  
			WHERE NUMERO BETWEEN '0' AND '9999999999') 
			UNION
			(SELECT NUMERO As NRO, NOMBRE As NOM, 'C' as ROC 
                        FROM [CON_Cuentas] 
			WHERE NUMERO BETWEEN '0' AND '9999999999')) As S
		WHERE Left(NUMERO,Len(S.NRO))=S.NRO 
		AND S.NRO<>NUMERO
		) as cntTST,
                U1.TIPOAJUSTE
FROM ((SELECT NUMERO, NOMBRE, NULL As SALDONOMIN, NULL As SALDOAJUST, NULL As qryTA, 
                'R'  as ROC, -- Agregado
                TIPOAJUSTE
	FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0' AND '9999999999') UNION
      (SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, 
				CASE 
					WHEN TIPOAJUSTE='A' THEN 'Automático' 
					ELSE (CASE 
							WHEN TIPOAJUSTE='D' THEN 'Directo' 
							ELSE 'Sin Ajuste'     -- Son todos tipo S, es correcto el CASE?
						END) 
				END As qryTA, 
                'C'  as ROC, -- Agregado
                TIPOAJUSTE
        FROM [CON_Cuentas] 
        WHERE NUMERO BETWEEN '0' AND '9999999999')) As U1
WHERE (U1.NUMERO BETWEEN '0' AND '9999999999')
ORDER BY --7 desc
U1.NUMERO, U1.ROC desc;

ID	NUMERO	NOMBRE
95	100000	Diferencia Patrimonial
96	100001	Activo
97	110000	DISPONIBILIDADES
98	110050	CAJAS
202	200000	PASIVO
203	210000	DEUDAS COMERCIALES
204	210100	PROVEEDORES
205	210110	NOREQUIS XEROX


Plan de Cuentas de Disidentes:
----------------------------------
use disidentes_new
go
SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, isnull(qryTA,'') as qryTA,
        REPLICATE('&nbsp;', (
                SELECT Count(NRO) * 2
                FROM ((SELECT NUMERO As NRO, NOMBRE As NOM 
                        FROM [CON_Rubros_Historico]  
                        WHERE NUMERO BETWEEN '0' AND '999999999999' AND DESDE='2010-01-01') UNION
                        (SELECT NUMERO As NRO, NOMBRE As NOM 
                        FROM [CON_Cuentas_Historico] 
                        WHERE NUMERO BETWEEN '0' AND '999999999999' AND DESDE='2010-01-01')) As S
                WHERE Left(NUMERO,Len(S.NRO))=S.NRO AND S.NRO<>NUMERO
                )) As qryIndent
FROM ((SELECT NUMERO, NOMBRE, NULL As SALDONOMIN, NULL As SALDOAJUST, NULL As qryTA 
        FROM [CON_Rubros_Historico]  
        WHERE NUMERO BETWEEN '0' AND '99999999999' AND DESDE='2010-01-01') UNION
        (SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, 
                CASE 
                        WHEN TIPOAJUSTE='A' THEN 'Automático' 
                        ELSE (CASE 
                                WHEN TIPOAJUSTE='D' THEN 'Directo' 
                                ELSE 'Sin Ajuste' 
                                END) 
                END As qryTA 
        FROM [CON_Cuentas_Historico] 
        WHERE NUMERO BETWEEN '0' AND '99999999999' AND DESDE='2010-01-01')) As U1
WHERE (U1.NUMERO BETWEEN '0' AND '999999999')

Rubros
NRO	NOM
1	ACTIVO
11	ACTIVO CORRIENTE
111	CAJA Y BANCOS
1111	CAJA
111101	CAJA CEMENTERIO

NUMERO	NOMBRE	SALDONOMIN	SALDOAJUST	qryTA	qryIndent
1	ACTIVO	NULL	NULL		
11	ACTIVO CORRIENTE	NULL	NULL		&nbsp;&nbsp;
111	CAJA Y BANCOS	NULL	NULL		&nbsp;&nbsp;&nbsp;&nbsp;
1111	CAJA	NULL	NULL		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
111101	CAJA CEMENTERIO	0.0000	0.0000	Sin Ajuste	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
111102	FONDO FIJO	0.0000	0.0000	Sin Ajuste	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

select * from dbo.CON_Cuentas
select * from dbo.CON_Cuentas_Historico

select * from dbo.CON_Rubros
select * from dbo.CON_Rubros_Historico




SELECT DISTINCT NUMERO, NOMBRE, ROC, 
                    (SELECT count(CASE 
                                    WHEN FECHA<cast('2010-01-01 00:00:00' as datetime) THEN 1 
                                    ELSE 0 
                                  END) 
                    FROM [CON_Renglones] WHERE U1.NUMERO=Left(CUENTA,Len(U1.NUMERO))) As qryHasMovs,
                    (SELECT sum(CASE  ------> SUM para lo mas nuevo 
                                    WHEN FECHA>cast('2011-01-01 00:00:00' as datetime) THEN 1 
                                    ELSE 0 
                                 END) 
                    FROM [CON_Renglones] WHERE U1.NUMERO=Left(CUENTA,Len(U1.NUMERO))) As qryHasMovs2,                    
                    (SELECT Sum(CASE 
                                    WHEN 
                                        FECHA<cast('2010-01-01 00:00:00' as datetime) THEN IMPORTE 
                                    ELSE 0 
                                END) 
                    FROM [CON_Renglones] WHERE U1.NUMERO=Left(CUENTA,Len(U1.NUMERO))) As qrySdoAnt,
                    (SELECT Sum(CASE 
                                    WHEN 
                                        FECHA>=cast('2011-01-01 00:00:00' as datetime) THEN IMPORTE 
                                    ELSE 0 
                                END) 
                    FROM [CON_Renglones] WHERE U1.NUMERO=Left(CUENTA,Len(U1.NUMERO))) As qrySdoPost,
                    (SELECT Sum(CASE 
                                    WHEN FECHA>=cast('2010-01-01 00:00:00' as datetime) 
                                        AND FECHA <= cast('2010-12-31 23:59:59' as datetime) 
                                                THEN (CASE WHEN IMPORTE>0 THEN  IMPORTE ELSE 0 END) 
                                    ELSE 0 
                                END) 
                    FROM [CON_Renglones] WHERE U1.NUMERO=Left(CUENTA,Len(U1.NUMERO))) As qryTotDeb,
                    (SELECT Sum(CASE 
                                    WHEN FECHA>=cast('2010-01-01 00:00:00' as datetime) 
                                    AND FECHA <= cast('2010-12-31 23:59:59' as datetime) 
                                        THEN (CASE 
                                                WHEN IMPORTE<0 THEN -IMPORTE 
                                                ELSE 0 
                                            END) 
                                        ELSE 0 
                                END) 
                    FROM [CON_Renglones] WHERE U1.NUMERO=Left(CUENTA,Len(U1.NUMERO))) As qryTotCre,
                    (SELECT Sum(CASE WHEN FECHA<=cast('2010-12-31 23:59:59' as datetime) THEN IMPORTE ELSE 0 END) FROM [CON_Renglones] WHERE U1.NUMERO=Left(CUENTA,Len(U1.NUMERO))) As qrySdoAct,
                    REPLICATE('&nbsp;', ( 
                                    SELECT Count(NRO) 
                                    FROM ((SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0000000000' AND '999999999') UNION 
                                            (SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Cuentas] WHERE NUMERO BETWEEN '0000000000' AND '999999999')) As S 
                                    WHERE Left(NUMERO,Len(S.NRO))=S.NRO AND S.NRO<>NUMERO 
                            )) As qryIndent 
FROM ((SELECT NUMERO, NOMBRE, 'R' As ROC FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0000000000' AND '999999999') UNION 
        (SELECT NUMERO, NOMBRE, 'C' As ROC FROM [CON_Cuentas] WHERE NUMERO BETWEEN '0000000000' AND '999999999')) As U1 
WHERE (U1.NUMERO BETWEEN '0000000000' AND '999999999') 
GROUP BY NUMERO, NOMBRE, ROC 
ORDER BY U1.NUMERO





SELECT INICIO, FINAL FROM [CON_Configuracion_Historico] WHERE ID=" + Page.Request.QueryString["EJ"], ref 


SELECT INICIO, FINAL FROM [CON_Configuracion_Historico]

SELECT DISTINCT NUMERO, NOMBRE, ROC,
        (SELECT Count(CASE WHEN FECHA<cast('2010-01-01 00:00:00' as datetime) THEN 1 ELSE 0 END) 
        FROM [CON_Renglones_Historico] 
        WHERE U1.NUMERO=Left(CUENTA collate SQL_Latin1_General_CP1_CI_AS,Len(U1.NUMERO)) 
        AND DESDE='2010-01-01 00:00:00') As qryHasMovs,
        (SELECT   Sum(CASE WHEN FECHA<cast('2010-01-01 00:00:00' as datetime) THEN IMPORTE ELSE 0 END) 
        FROM [CON_Renglones_Historico] WHERE U1.NUMERO=Left(CUENTA collate SQL_Latin1_General_CP1_CI_AS,Len(U1.NUMERO)) 
        AND DESDE='2010-01-01 00:00:00') As qrySdoAnt,
        (SELECT   Sum(CASE 
                        WHEN FECHA>=cast('2010-01-01 00:00:00' as datetime) 
                                AND FECHA <=cast('2010-12-31 23:59:59' as datetime) 
                                        THEN (CASE 
                                                WHEN IMPORTE>0 THEN  IMPORTE 
                                                ELSE 0 
                                             END) 
                        ELSE 0 
                        END) 
        FROM [CON_Renglones_Historico] 
        WHERE U1.NUMERO=Left(CUENTA collate SQL_Latin1_General_CP1_CI_AS,Len(U1.NUMERO)) 
        AND DESDE='2010-01-01 00:00:00') As qryTotDeb,
        (SELECT   Sum(CASE 
                        WHEN FECHA>=cast('2010-01-01 00:00:00' as datetime) 
                                AND FECHA <=cast('2010-12-31 23:59:59' as datetime) 
                                THEN (CASE 
                                        WHEN IMPORTE<0 
                                                THEN -IMPORTE 
                                        ELSE 0 
                                     END) 
                        ELSE 0 
                        END) 
        FROM [CON_Renglones_Historico] 
        WHERE U1.NUMERO=Left(CUENTA collate SQL_Latin1_General_CP1_CI_AS,Len(U1.NUMERO)) 
        AND DESDE='2010-01-01 00:00:00') As qryTotCre,
        (SELECT   Sum(CASE 
                        WHEN FECHA<=cast('2010-12-31 23:59:59' as datetime) THEN IMPORTE 
                        ELSE 0 
                     END) 
        FROM [CON_Renglones_Historico] 
        WHERE U1.NUMERO=Left(CUENTA collate SQL_Latin1_General_CP1_CI_AS,Len(U1.NUMERO)) 
        AND DESDE='2010-01-01 00:00:00') As qrySdoAct,
        REPLICATE('&nbsp;', (
                SELECT Count(NRO)
                FROM ((SELECT NUMERO As NRO, NOMBRE As NOM 
                        FROM [CON_Rubros_Historico] 
                        WHERE NUMERO BETWEEN '0000000000' AND '999999999' AND DESDE='2010-01-01 00:00:00') 
                        UNION
                        (SELECT NUMERO As NRO, NOMBRE As NOM 
                        FROM [CON_Cuentas_Historico] 
                        WHERE NUMERO BETWEEN '0000000000' AND '999999999' AND DESDE='2010-01-01 00:00:00')) As S
                WHERE Left(NUMERO,Len(S.NRO))=S.NRO AND S.NRO<>NUMERO
                )) As qryIndent
FROM ((SELECT NUMERO, NOMBRE, 'R' As ROC FROM [CON_Rubros_Historico]  
        WHERE NUMERO BETWEEN '0000000000' AND '999999999' 
        AND DESDE='2010-01-01 00:00:00') 
        UNION
      (SELECT NUMERO, NOMBRE, 'C' As ROC FROM [CON_Cuentas_Historico] 
      WHERE NUMERO BETWEEN '0000000000' AND '999999999' 
      AND DESDE='2010-01-01 00:00:00')) As U1
WHERE (U1.NUMERO BETWEEN '0000000000' AND '999999999')
GROUP BY NUMERO, NOMBRE, ROC
ORDER BY 1;





Disidentes
------------
select NUMERO,NOMBRE,SALDONOMIN,SALDOAJUST,TipoAjuste,IndentHtml
FROM [vw_SacCuentas];
 SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, isnull(qryTA,'') as TipoAjuste,  
                    REPLICATE(' ', ( 
                    SELECT Count(NRO) * 3
                    FROM ((SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0' AND '9999999999') UNION 
                          (SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Cuentas] WHERE NUMERO BETWEEN '0' AND '9999999999')) As S 
                    WHERE Left(NUMERO,Len(S.NRO))=S.NRO AND S.NRO<>NUMERO 
                    )) As IndentBlank,
					REPLICATE('&nbsp;', ( 
                    SELECT Count(NRO) * 3
                    FROM ((SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0' AND '9999999999') UNION 
                          (SELECT NUMERO As NRO, NOMBRE As NOM FROM [CON_Cuentas] WHERE NUMERO BETWEEN '0' AND '9999999999')) As S 
                    WHERE Left(NUMERO,Len(S.NRO))=S.NRO AND S.NRO<>NUMERO 
                    )) As IndentHtml 
                    FROM ((SELECT NUMERO, NOMBRE, NULL As SALDONOMIN, NULL As SALDOAJUST, NULL As qryTA FROM [CON_Rubros]  WHERE NUMERO BETWEEN '0' AND '9999999999') UNION 
                          (SELECT NUMERO, NOMBRE, SALDONOMIN, SALDOAJUST, CASE WHEN TIPOAJUSTE='A' THEN 'Automático' ELSE (CASE WHEN TIPOAJUSTE='D' THEN 'Directo' ELSE 'Sin Ajuste' END) END As qryTA 
FROM [CON_Cuentas] WHERE NUMERO BETWEEN '0' AND '9999999999')) As U1 
WHERE (U1.NUMERO BETWEEN '0' AND '9999999999') 


ABM Fallecidos
--------------
use neo_britanico
go
SELECT id_inhumado,historico,msg_no_innovar,NumeroInhumado,nombre,edad,nacionalidad,estado_civil,CausaDeceso,LugarDeceso,detalle_parcela,Nombre_cocheria,FechaDeceso,sector,manzana,parcela,NumeroCliente FROM dbo.vw_inhumado ORDER BY 2
o con filtros:
SELECT id_inhumado,historico,msg_no_innovar,NumeroInhumado,nombre,edad,nacionalidad,estado_civil,CausaDeceso,LugarDeceso,detalle_parcela,Nombre_cocheria,FechaDeceso,sector,manzana,parcela,NumeroCliente FROM dbo.vw_inhumado 
where FechaDeceso>=convert(datetime, '30/8/2021',103 ) 
ORDER BY 2
Tocando link de Historico
SELECT * FROM dbo.vw_MovimientoInhumado WHERE id_inhumado=" + _id + " ORDER BY FechaMovimiento

alta
Session["entidad"] = "inhumado";
                    //_in.class_database.Begin_Transaction(_local_database, ref _connection, ref _transaction);
                    if (formOperation != "edit") { _in.Custom_Persist_SQL_Command = "dbo.coop_Inhumado_Insert_Custom"; }
                    int _id_record = _in.class_database.PersistFormAdHoc(ref _transaction, _local_database, formOperation, Session["entidad"].ToString(), ref _in, this.Request.Form, "id_inhumado");
                    if (_id_record == -9999) { bCommit = false; }
                    string _numero_inhumado = _in.class_general.DLookup(_local_database, "NumeroInhumado", "dbo.inhumado", "id_inhumado=" + _id_record);

select * from dbo.Inhumado
select * from dbo.vw_Inhumado

select * from dbo.EstadoCivil
select * from dbo.Cocheria
Nacionalidad -> id_pais -> select * from dbo.Pais
tipo Inhumado -> id_tipo_servicio -> select * from dbo.TipoServicio where ClaseMovimiento = 'O' 
select * from dbo.Parcela
select * from dbo.TipoDocumento
Metalica -> lata -> S N null

select * from dbo.Inhumado where id_inhumado = 54449
select * from dbo.Inhumado where NumeroInhumado = 54449
54449	ALEJANDRO PLUME  --> Legacy
12036	PLUME ALEJANDRO  --> Migrado



select id, code, description, id_parent, data_module, data_model, data_table 
from neo_britanico.dbo.mod_backend_functions where description like '%fallecidos%'

select id, code, description, id_parent, data_module, data_model, data_table 
from neo_britanico.dbo.mod_backend_functions where id = 1047 or id_parent = 1047

id	code	        description	id_parent  data_module	  data_model	  data_table
1047	m_fallecidos	Menú fallecidos	NULL			
1056	m_abcm	        Submenú ABCM	1047	   mod_britanico  Sac_fallecidos  Sac_fallecidos

select * from dbo.SAC_Fallecidos where NOMBRE like '%PLUME%' ????

Hay que hacer update en nfunciones para que apunte a inhumado:
id	code	description	id_parent	data_module	data_model	data_table
1056	m_abcm	Submenú ABCM	1047	mod_britanico	Inhumado	Inhumado

**Cambiar sac_fallecidos por inhumados
**Ver de pasar fecha datetime deceso a date (dd/mm/aaaa)
Ver en PHP como armar las distintas relaciones con el brow. 
        El brow parte de una view, hay otras dependencias
        Hay combos para traducir al id.

**No esta trayendo todos los campos en el edit
No esta cargando todos datos para la modificacion.

OjO:
        No andaba el edit porque la clave de inhumados no es id sino id_inhumados
        Como el brow estaba basada en vw_inhumados, agregue columna renombrandola con alias id. Con eso anduvo ok
        Ver como hacer para que funcione con PK de otro nombre. (My_Model.setRegistersPK)




exec sp_help 'inhumado'
Column_name	Type	Computed	Length	Prec	Scale	Nullable	TrimTrailingBlanks	FixedLenNullInSource	Collation
id_inhumado	numeric	no	9	10   	0    	no	(n/a)	(n/a)	NULL
Nombre	varchar	no	40	     	     	no	no	no	Modern_Spanish_CI_AS
id_TipoDocumento	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
NumeroDocumento	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
FechaDeceso	datetime	no	8	     	     	yes	(n/a)	(n/a)	NULL
id_paisNacionalidad	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
Profesion	varchar	no	40	     	     	yes	no	yes	Modern_Spanish_CI_AS
UltimoDomicilio	varchar	no	40	     	     	yes	no	yes	Modern_Spanish_CI_AS
Edad	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
id_estadoCivil	tinyint	no	1	3    	0    	yes	(n/a)	(n/a)	NULL
CausaDeceso	varchar	no	60	     	     	yes	no	yes	Modern_Spanish_CI_AS
LugarDeceso	varchar	no	60	     	     	yes	no	yes	Modern_Spanish_CI_AS
CorteControl	varchar	no	40	     	     	yes	no	yes	Modern_Spanish_CI_AS
id_Parcela_Actual	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
NumeroCertificado	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
NumeroRegistro	numeric	no	9	18   	0    	yes	(n/a)	(n/a)	NULL
id_tipo_servicio	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
NumeroInhumado	numeric	no	9	18   	0    	yes	(n/a)	(n/a)	NULL
lata	char	no	1	     	     	yes	no	yes	Modern_Spanish_CI_AS
id_cocheria	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
no_innovar	char	no	1	     	     	yes	no	yes	Modern_Spanish_CI_AS


exec sp_help 'vw_inhumado'
Column_name	Type	Computed	Length	Prec	Scale	Nullable	TrimTrailingBlanks	FixedLenNullInSource	Collation
id	        numeric	no	        9	10   	0    	no	        (n/a)	                (n/a)	                NULL
id_inhumado	numeric	no	        9	10   	0    	no	        (n/a)	                (n/a)	                NULL
Nombre	varchar	no	40	     	     	no	no	no	Modern_Spanish_CI_AS
id_TipoDocumento	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
NumeroDocumento	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
FechaDeceso	datetime	no	8	     	     	yes	(n/a)	(n/a)	NULL
id_paisNacionalidad	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
Profesion	varchar	no	40	     	     	yes	no	yes	Modern_Spanish_CI_AS
UltimoDomicilio	varchar	no	40	     	     	yes	no	yes	Modern_Spanish_CI_AS
Edad	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
id_estadoCivil	tinyint	no	1	3    	0    	yes	(n/a)	(n/a)	NULL
CausaDeceso	varchar	no	60	     	     	yes	no	yes	Modern_Spanish_CI_AS
LugarDeceso	varchar	no	60	     	     	yes	no	yes	Modern_Spanish_CI_AS
CorteControl	varchar	no	40	     	     	yes	no	yes	Modern_Spanish_CI_AS
id_Parcela_Actual	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
NumeroCertificado	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
NumeroRegistro	numeric	no	9	18   	0    	yes	(n/a)	(n/a)	NULL
id_tipo_servicio	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
NumeroInhumado	numeric	no	9	18   	0    	yes	(n/a)	(n/a)	NULL
lata	char	no	1	     	     	yes	no	yes	Modern_Spanish_CI_AS
id_cocheria	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
no_innovar	char	no	1	     	     	yes	no	yes	Modern_Spanish_CI_AS --- Hasta aca llega la tabla
msg_no_innovar	varchar	no	58	     	     	no	no	no	Modern_Spanish_CI_AS --- depende de no_innovar
id_Parcela	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL                 --- Desde aca vienen campos de la vista
Sector	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
Manzana	char	no	2	     	     	yes	no	yes	SQL_Latin1_General_CP1_CI_AS
parcela	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
Secuencia	char	no	2	     	     	yes	no	yes	SQL_Latin1_General_CP1_CI_AS
detalle_parcela	varchar	no	207	     	     	yes	no	yes	SQL_Latin1_General_CP1_CI_AS
tipo_documento	varchar	no	20	     	     	yes	no	yes	Modern_Spanish_CI_AS
estado_civil	varchar	no	30	     	     	yes	no	yes	Modern_Spanish_CI_AS
nacionalidad	varchar	no	30	     	     	yes	no	yes	SQL_Latin1_General_CP1_CI_AS
historico	varchar	no	321	     	     	yes	no	yes	Modern_Spanish_CI_AS
Nombre_cocheria	varchar	no	40	     	     	yes	no	yes	Modern_Spanish_CI_AS
NumeroCliente	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
CertificadoTitularidad	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
contrato	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
periodo	int	no	4	10   	0    	yes	(n/a)	(n/a)	NULL
ult_arr_vence	datetime	no	8	     	     	yes	(n/a)	(n/a)	NULL
historica	varchar	no	20	     	     	yes	no	yes	SQL_Latin1_General_CP1_CI_AS

Anda edit
Anda save
Andan combos
Andan checkbox

Hay que ver si se puede empezar a sacar las vistas en en modelo para otra cosa que no se el brow. Para no hacer 
insert o update sobre una vista. Para eso hay que pasar correctamente el where con la pk por la que hacer 
el update o delete.

Hay que re-hacer el ABM de inhumados con

[dbo].[coop_Inhumado_Insert]  <------
[dbo].[coop_Inhumado_Insert_Custom]
[dbo].[coop_Inhumado_Update]



Listado Precios
----------------

select * from dbo.mod_backend_functions where description like '%facturac%'
--1048	m_facturacion

select * from dbo.mod_backend_functions where id_parent = 1048 or id=1048
--1062	m_listaprecio	Submenú lista de precios

La tabla ListaPrecios tiene una Id Operacion a que lleva? Hay una tabla SAC_Operaciones ?

Ver si corresponde usar los SP:
coop_ListaPrecio_Insert
coop_ListaPrecio_Update

Los sp de insert, devuelven el id insertado por parametro de OUT. Eso esta complicado con codeigniter:
https://docs.microsoft.com/en-us/sql/connect/php/how-to-retrieve-output-parameters-using-the-sqlsrv-driver?view=sql-server-ver15
https://stackoverflow.com/questions/31659208/how-to-call-a-stored-procedure-in-codeigniter-and-show-the-output-parameter

Ver si no es mejor ponerlo tambien como result set



codeigniter tiene problemas con:
- stored procedures con parametros de output
- stored procedures con multiples result set

---> por ahora hago un wrapper del SP para tomar el output y devolverlo como resultset

Revisar como resolver multiples result set y parametros de output:
https://consolewikicode.blogspot.com/2015/08/php-stored-procedure-with-multiple.html
https://stackoverflow.com/questions/23007335/how-to-fetch-multiple-results-returned-from-stored-procedure-codeigniter
https://stackoverflow.com/questions/9046075/trying-to-call-stored-procedures-with-codeigniter
https://medium.com/@lyoneel/codeigniter-3-el-desaf%C3%ADo-de-utilizar-procedimientos-96983fd8386c
https://stackify.dev/706516-codeigniter-multiple-results-from-stored-procedurehttps://forum.codeigniter.com/thread-80685-post-391976.html#pid391976
https://forum.codeigniter.com/thread-80685-post-391976.html#pid391976
https://www.php.net/manual/en/function.sqlsrv-query.php
https://forum.codeigniter.com/thread-46158.html


select id, id_parent, code, description, data_module, data_model, data_table 
from dbo.mod_backend_functions where id = 1046 or id_parent = 1046

id	id_parent	code	        description	        data_module	data_model	data_table
1046	NULL	        m_parcela	Menú parcelas			
1053	1046	        m_cliente	Submenú clientes	mod_britanico	Cliente	        Cliente
1054	1046	        m_pagador	Submenú pagadores	mod_britanico	Pagador	        Pagador
1055	1046	        m_parcela	Submenú parcelas	mod_britanico	Parcela	        Parcela

Hay que modificar coop cliente insert

hay que volver a poner vw_cliente con el distintas

hay que implementar metodo extedodp para hacer insert sobre la tabla sin SP.





Fallecidos / inhumados
----------------------
Todos
SELECT id_inhumado,historico,msg_no_innovar,NumeroInhumado,nombre,edad,nacionalidad,estado_civil,CausaDeceso,LugarDeceso,detalle_parcela,Nombre_cocheria,FechaDeceso,sector,manzana,parcela,NumeroCliente FROM dbo.vw_inhumado ORDER BY 2

Sin Parcela asociada
SELECT id_inhumado,historico,msg_no_innovar,NumeroInhumado,nombre,edad,nacionalidad,estado_civil,CausaDeceso,LugarDeceso,detalle_parcela,Nombre_cocheria,FechaDeceso,sector,manzana,parcela,NumeroCliente FROM dbo.vw_inhumado where id_parcela_actual is null  ORDER BY 2

javascript:HistoricoInhumado_CEM()
    "SELECT * FROM dbo.vw_MovimientoInhumado WHERE id_inhumado=" + _id + " ORDER BY FechaMovimiento";
javascript:BorrarMI_CEM()
                    case "borrar_mi":
                    parms = new SqlParameter[1];
                    parms[0] = new SqlParameter("@NumeroMovimiento", SqlDbType.Int);
                    parms[0].Value = int.Parse(_id_mi);
                    try
                    {
                        _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "spDelMI", parms);

Registros de Sepulturas N
SELECT parcelaReducido,cliente,convert(varchar,Fecha_Alta,103) as Fecha_Alta,convert(varchar,Fecha_Vencimiento,103) as Fecha_Vencimiento,Meses,clase,contrato 
FROM dbo.vw_cuentacorriente where clase ='A' AND id_Parcela IS NOT null ";
if (_itemin6_hidden != "Null" && _itemin7_hidden != "Null")
{
	_ssql += " AND fecha_alta>=convert(datetime,'" + _itemin6_hidden + "',103 ) AND fecha_alta<=convert(datetime,'" + _itemin7_hidden + "',103 )";
}


Clientes
-----------
Impresion Libro
Impresion Libro Pares
Impresion Libro Impares

SELECT * FROM dbo.vw_listado_impresion_libro 

        Dim _ssql As String = "SELECT * FROM dbo.vw_listado_impresion_libro "
        _in.class_database.GetDataReader(_local_database, _ssql, _readerfree)
        _sb.Append("<table class='tbl-custom' width='100%' cellspacing='0' cellpadding='4'><tr bgcolor='silver'>")
        Select Case _mode
            Case "0"
                _sb.Append("<tr>")
                _sb.Append("<td><b>Fecha</b></td>")
                _sb.Append("<td><b>NºC.</b></td>")
                _sb.Append("<td><b>Nombre</b></td>")
                _sb.Append("<td align='right'><b>Parcelas</b></td>")
                _sb.Append("<td><b>Vence Arr.</b></td>")
                _sb.Append("<td><b>Vence Con.</b></td>")
                _sb.Append("<td><b>Deuda</b></td>")
                _sb.Append("</tr>")
            Case "1"
                _sb.Append("<tr>")
                _sb.Append("<td><b>Fecha</b></td>")
                _sb.Append("<td><b>NºCliente</b></td>")
                _sb.Append("<td><b>Nombre</b></td>")
                _sb.Append("</tr>")
            Case "2"
                _sb.Append("<tr>")
                _sb.Append("<td align='right'><b>Parcelas</b></td>")
                _sb.Append("<td><b>Vence Arr.</b></td>")
                _sb.Append("<td><b>Vence Cons.</b></td>")
                _sb.Append("<td align='center'><b>Con deuda</b></td>")
                _sb.Append("</tr>")
        End Select


        Do While _readerfree.Read()
            Dim _deuda As String = "No"
            If (_readerfree("conDeuda").ToString() > 1) Then
                _deuda = "Si"
            End If

            Select Case _mode
                Case "0"
                    _sb.Append("<tr>")
                    _sb.Append("<td>" + _readerfree("fecha").ToString() + "</td>")
                    _sb.Append("<td>" + _readerfree("numerocliente").ToString() + "</td>")
                    _sb.Append("<td>" + _readerfree("razonsocial").ToString() + "</td>")
                    _sb.Append("<td align='right'>" + _readerfree("parcelas").ToString() + "</td>")
                    _sb.Append("<td>" + _readerfree("fechaVENCEARR").ToString() + "</td>")
                    _sb.Append("<td>" + _readerfree("fechaVENCECON").ToString() + "</td>")
                    _sb.Append("<td align='center'>" + _deuda + "</td>")
                    _sb.Append("</tr>")
                Case "1"
                    _sb.Append("<tr>")
                    _sb.Append("<td>" + _readerfree("fecha").ToString() + "</td>")
                    _sb.Append("<td>" + _readerfree("numerocliente").ToString() + "</td>")
                    _sb.Append("<td>" + _readerfree("razonsocial").ToString() + "</td>")
                    _sb.Append("</tr>")
                Case "2"
                    _sb.Append("<tr>")
                    _sb.Append("<td align='right'>" + _readerfree("parcelas").ToString() + "</td>")
                    _sb.Append("<td>" + _readerfree("fechaVENCEARR").ToString() + "</td>")
                    _sb.Append("<td>" + _readerfree("fechaVENCECON").ToString() + "</td>")
                    _sb.Append("<td align='center'>" + _deuda + "</td>")
                    _sb.Append("</tr>")
            End Select
        Loop
        _sb.Append("</table>")

Otros 3 Reportes de Clientes (Menu Parcelas -> Cliente...):
Sin cons. a vencer (ABM o reporte?)
SELECT c.* 
FROM dbo.vw_cliente as c 
WHERE c.letra_parcela NOT IN ('L','R') 
AND c.parcela IS NOT null 
AND c.id_cliente NOT IN (SELECT vcc.id_cliente 
                        FROM dbo.vw_CuentaCorriente as vcc 
                        WHERE vcc.clase='C' 
                        AND vcc.Fecha_Vencimiento>GETDATE() 
                        AND vcc.id_Parcela=c.id_Parcela)  
ORDER BY 2,1

Sin arr. a vencer  (ABM o reporte?)
SELECT c.* 
FROM dbo.vw_cliente as c 
WHERE c.parcela IS NOT null 
AND c.id_cliente NOT IN (SELECT vcc.id_cliente 
                        FROM dbo.vw_CuentaCorriente as vcc 
                        WHERE vcc.clase='A' AND vcc.Fecha_Vencimiento>GETDATE() 
                        AND vcc.id_Parcela=c.id_Parcela)  ORDER BY 2,1

Cocherias          (ABM o reporte?)
SELECT * FROM dbo.vw_cocherias_resuelto


En Disidentes, ejemplo de un action que apunta a un metodo que no es brow.
id	id_parent	code	        description	        data_module	data_model              data_table	data_action
1046	NULL	        m_parcela	Menú parcelas				
1053	1046	        m_cliente	Submenú clientes	mod_britanico	Cliente	                vw_Cliente	brow
1054	1046	        m_pagador	Submenú pagadores	mod_britanico	Pagador         	Pagador	        brow
1055	1046	        m_parcela	Submenú parcelas	mod_britanico	Parcela	                Parcela	        brow
2093	1046	        m_cliente_libro	Submenú clientes Libro	mod_britanico	Cliente	                vw_Cliente	libro


Falta acomodar que libro en Parcelas -> Libro permita generar PDF y excel correctamente, ahora queda apuntando a la table del modelo original en lugar de la table con uso por el action.
Esto es, se iria al CLiente en lugar de la vw_libro

ABCM Titular Sin Parcela...desde funciones se llama a los mismo que tituales....ver como cambiar el query / y exportaciones
Cliente.php  ver que este correcto el llamado del sp .....


Inhumados:
-- Porque si falla el sp no salta una excepcion en MY_Model??? Como meter una transaccion?
-- Porque MovimentoInhumado para id_inhumado = 53870 queda con cliente nulo??

Tomar por ej.:
exec [dbo].[coop_Inhumado_Update]       53870,
                                        "Otroo",
                                        "3",
                                        '1',
                                        "2022-02-15",
                                        "54",
                                        "PROF",
                                        "D",
                                        "88",
                                        "1",
                                        "X",
                                        "CABA",
                                        null,
                                        "18",
                                        "11",
                                        "11",
                                        "1002",
                                        'S',
                                        null,
                                        "46"




Donde se esta usando el modelo Cliente.php ??????? 
Solo estaria apareciendo como submenu de Parcelas lo siguiente:
Pagadores
Parcelas Libro de Clientes
pero no veo Clientes.....

select id, id_parent, code, description, data_module, data_model, data_table, data_action, priority 
from dbo.mod_backend_functions 
where data_model like '%client%' or data_table like '%client%' or id in (1046) or id_parent in (1046)

id	id_parent code	          description	         data_module	data_model data_table	              data_action
1046	NULL	  m_parcela	  Menú parcelas				
1053	1046	  m_cliente	  Submenú clientes       mod_britanico	Cliente	   vw_Cliente	              brow
1054	1046	  m_pagador	  Submenú pagadores      mod_britanico	Pagador	   Pagador	              brow
1055	1046	  m_parcela	  Submenú parcelas       mod_britanico	Parcela	   Parcela	              brow
2093	1046	  m_cliente_libro Submenú clientes Libro mod_britanico	Cliente	   vw_listado_impresion_libro libro





Pagador
SELECT id_pagador,Nombre,NumeroPagador,domicilio,TipoDocumento,NumeroDocumento FROM vw_pagador ORDER BY 3

CashFlow se basa en CON_Renglones. Como se mantiene esta tabla?
select distinct object_name(id) from sys.syscomments where text like '%ON_Rengl%' order by 1
coop_CON_Renglones_Historico_Insert
coop_CON_Renglones_Historico_Update
coop_CON_Renglones_Insert
coop_CON_Renglones_Update
coop_CuentaCorriente_Insert_Custom
sp_helptext coop_Parcela_Update
sp_TransferenciaMensual_Nogues_a_Chacarita
spDesengarcharParcela
spGENERAR_DEUDA_MENSUAL
spGenerar_Plan_Pago
spInsRecibo
spRenumeraAsientos



Esto para para inserar en Deposito Cheque.
case "deposito_cheque":
        _id_empresa_sucursal = _in.class_general.RequestSecure(HttpContext.Current.Request["id_empresa_sucursal"], false).ToString();
        _ID_Caja_Tesoreria = _in.class_general.RequestSecure(HttpContext.Current.Request["ID_Caja_Tesoreria"], false).ToString();
        string _id_cuenta_bancaria = _in.class_general.RequestSecure(HttpContext.Current.Request["id_cuenta_bancaria"], false).ToString();
        string _fecha_deposito = _in.class_general.RequestSecure(HttpContext.Current.Request["fecha_deposito"], false).ToString();
        string _valores = _in.class_general.RequestSecure(HttpContext.Current.Request["valores"], false).ToString();

        parms = new SqlParameter[5];
        parms[0] = new SqlParameter("@id_empresaSucursal", SqlDbType.Int);
        parms[0].Value = int.Parse(_id_empresa_sucursal);
        parms[1] = new SqlParameter("@id_CajaTesoreria", SqlDbType.Int);
        parms[1].Value = int.Parse(_ID_Caja_Tesoreria);
        parms[2] = new SqlParameter("@id_Cuenta_Bancaria", SqlDbType.Int);
        parms[2].Value = int.Parse(_id_cuenta_bancaria);
        parms[3] = new SqlParameter("@fechaDeposito", SqlDbType.DateTime);
        string[] temp = _fecha_deposito.Split('/');
        DateTime fechaDeposito = new DateTime(int.Parse(temp[2]), int.Parse(temp[1]), int.Parse(temp[0]));
        parms[3].Value = fechaDeposito;
        parms[4] = new SqlParameter("@idsValoresEnCartera", SqlDbType.VarChar, -1);
        parms[4].Value = _valores;
        try
        {
        _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "spInsDepositoCheques", parms);
        }





              case "deposito_cheque":
                    _id_empresa_sucursal = _in.class_general.RequestSecure(HttpContext.Current.Request["id_empresa_sucursal"], false).ToString();
                    _ID_Caja_Tesoreria = _in.class_general.RequestSecure(HttpContext.Current.Request["ID_Caja_Tesoreria"], false).ToString();
                    string _id_cuenta_bancaria = _in.class_general.RequestSecure(HttpContext.Current.Request["id_cuenta_bancaria"], false).ToString();
                    string _fecha_deposito = _in.class_general.RequestSecure(HttpContext.Current.Request["fecha_deposito"], false).ToString();
                    string _valores = _in.class_general.RequestSecure(HttpContext.Current.Request["valores"], false).ToString();

                    parms = new SqlParameter[5];
                    parms[0] = new SqlParameter("@id_empresaSucursal", SqlDbType.Int);
                    parms[0].Value = int.Parse(_id_empresa_sucursal);
                    parms[1] = new SqlParameter("@id_CajaTesoreria", SqlDbType.Int);
                    parms[1].Value = int.Parse(_ID_Caja_Tesoreria);
                    parms[2] = new SqlParameter("@id_Cuenta_Bancaria", SqlDbType.Int);
                    parms[2].Value = int.Parse(_id_cuenta_bancaria);
                    parms[3] = new SqlParameter("@fechaDeposito", SqlDbType.DateTime);
                    string[] temp = _fecha_deposito.Split('/');
                    DateTime fechaDeposito = new DateTime(int.Parse(temp[2]), int.Parse(temp[1]), int.Parse(temp[0]));
                    parms[3].Value = fechaDeposito;
                    parms[4] = new SqlParameter("@idsValoresEnCartera", SqlDbType.VarChar, -1);
                    parms[4].Value = _valores;
                    try
                    {
                        _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "spInsDepositoCheques", parms);
                    }
                    catch (Exception ex)
                    {
                        _sb.Append("Se ha producido un error, imposible grabar el depósito - " + ex.Message);
                        return;
                    }
                    break;

                case "ver_valores_a_depositar":
                    string filtroTipoValor = string.Empty;
                    _id_empresa_sucursal = _in.class_general.RequestSecure(HttpContext.Current.Request["id_empresa_sucursal"], false).ToString();
                    _ID_Caja_Tesoreria = _in.class_general.RequestSecure(HttpContext.Current.Request["ID_Caja_Tesoreria"], false).ToString();
                    string _chk_noorden = _in.class_general.RequestSecure(HttpContext.Current.Request["chk_noorden"], false).ToString();
                    string _chk_cheques = _in.class_general.RequestSecure(HttpContext.Current.Request["chk_cheques"], false).ToString();
                    string _chk_efectivos = _in.class_general.RequestSecure(HttpContext.Current.Request["chk_efectivos"], false).ToString();
                    string _chk_tipos = _in.class_general.RequestSecure(HttpContext.Current.Request["chk_tipos"], false).ToString();
                    if (_chk_tipos.IndexOf(",") > -1) { _chk_tipos = _chk_tipos.Substring(0, _chk_tipos.Length - 1); }
                    string _fecha_vencimiento_desde = _in.class_general.RequestSecure(HttpContext.Current.Request["fecha_vencimiento_desde"], false).ToString();
                    string _fecha_vencimiento_hasta = _in.class_general.RequestSecure(HttpContext.Current.Request["fecha_vencimiento_hasta"], false).ToString();

                    // Esto es para grabar!
                    _CommandText = "SELECT * FROM dbo.vw_valorEnCartera ";
                    _CommandText += " WHERE (";
                    _CommandText += " id_Salida_Cheques is null AND id_empresaSucursal=" + _id_empresa_sucursal + " AND ID_Caja_Tesoreria=" + _ID_Caja_Tesoreria;
                    if (_chk_noorden != "S") { _CommandText += " AND NoALaOrden!='S'"; }
                    if (_chk_tipos != "") { _CommandText += " AND tipo_cheque IN (" + _chk_tipos + ")"; }
                    if (_chk_cheques == "S") { filtroTipoValor += "EsCheque='S'"; }
                    if (_chk_efectivos == "S")
                    {
                        if (!string.IsNullOrEmpty(filtroTipoValor)) { filtroTipoValor += " OR "; }
                        filtroTipoValor += "EsEfectivo='S'";
                    }
                    if (!string.IsNullOrEmpty(filtroTipoValor)) { _CommandText += " AND (" + filtroTipoValor + ")"; }
                    if (_fecha_vencimiento_desde != "" && _fecha_vencimiento_hasta != "") { _CommandText += " AND (Fecha_Vencimiento >= CONVERT(datetime,'" + _fecha_vencimiento_desde + "',103) AND Fecha_Vencimiento <= CONVERT(datetime,'" + _fecha_vencimiento_hasta + "',103) OR Fecha_Vencimiento is null)"; }
                    _CommandText += ") OR (";
                    filtroTipoValor = "";
                    _CommandText += " id_Salida_Cheques is not null AND id_empresaSucursal=" + _id_empresa_sucursal + " AND ID_Caja_Tesoreria=" + _ID_Caja_Tesoreria;
                    if (_chk_noorden != "S") { _CommandText += " AND NoALaOrden!='S'"; }
                    if (_chk_tipos != "") { _CommandText += " AND tipo_cheque IN (" + _chk_tipos + ")"; }
                    if (_chk_cheques == "S") { filtroTipoValor += "EsCheque='S'"; }
                    if (_chk_efectivos == "S")
                    {
                        if (!string.IsNullOrEmpty(filtroTipoValor)) { filtroTipoValor += " OR "; }
                        filtroTipoValor += "EsEfectivo='S'";
                    }
                    if (!string.IsNullOrEmpty(filtroTipoValor)) { _CommandText += " AND (" + filtroTipoValor + ")"; }
                    if (_fecha_vencimiento_desde != "" && _fecha_vencimiento_hasta != "") { _CommandText += " AND (Fecha_Vencimiento >= CONVERT(datetime,'" + _fecha_vencimiento_desde + "',103) AND Fecha_Vencimiento <= CONVERT(datetime,'" + _fecha_vencimiento_hasta + "',103))"; }
                    _CommandText += ")";

                    _CommandText += " ORDER BY Fecha_Vencimiento ASC";

                    _ds = _in.class_database.GetDataSet(HttpContext.Current.Session["database"].ToString(), _CommandText);
                    _dtbl = _ds.Tables["data"];
                    _sb.Append("<table id='tblValores' cellpadding='2' style='border:solid 1px silver;'>");
                    foreach (System.Data.DataRow row in _dtbl.Rows)
                    {
                        string _color = "black";
                        string _visiblecheck = "";
                        string _html_revertir = "";
                        _id_valorencartera = row[_dtbl.Columns["ID_ValorEnCartera"]].ToString();
                        string _nro_cheque = row[_dtbl.Columns["Nro_Cheque"]].ToString();
                        string _banco = row[_dtbl.Columns["Desc_Bancos"]].ToString();
                        string _cliente = row[_dtbl.Columns["RazonSocial"]].ToString();
                        string _valor = row[_dtbl.Columns["Codigo_Valor"]].ToString();
                        string _nro_recibo = row[_dtbl.Columns["nro_recibo"]].ToString();
                        string _tipo_cheque = row[_dtbl.Columns["tipo_cheque"]].ToString();
                        _id_salida_cheques = row[_dtbl.Columns["id_salida_cheques"]].ToString();
                        string _dias_pasados = row[_dtbl.Columns["dias_pasados"]].ToString();
                        DateTime? _fecha_vto = null;
                        if (!string.IsNullOrEmpty(row[_dtbl.Columns["Fecha_Vencimiento"]].ToString()))
                        {
                            _fecha_vto = DateTime.Parse(row[_dtbl.Columns["Fecha_Vencimiento"]].ToString());
                        }

                        Double _importe = Double.Parse(row[_dtbl.Columns["importe_consolidado"]].ToString());
                        if (ic == 0)
                        {
                            _sb.Append("<tr valign='top' bgcolor='silver'>");
                            _sb.Append("<td align='center'></td>");
                            _sb.Append("<td><b>Valor</b></td>");
                            _sb.Append("<td><b>Fecha Vto.</b></td>");
                            _sb.Append("<td><b>Nºcheque</b></td>");
                            _sb.Append("<td><b>Banco</b></td>");
                            _sb.Append("<td><b>Cliente</b></td>");
                            _sb.Append("<td><b>Importe</b></td>");
                            _sb.Append("<td><b>Recibo</b></td>");
                            _sb.Append("<td><b>Físicamente en...</b></td>");
                            _sb.Append("</tr>");
                        }
                        ic += 1;
                        if (_fecha_vto > DateTime.Today) { _color = "red"; }
                        _sb.Append("<tr id='tr_" + _id_valorencartera + "' class='" + _valor + "' style='color:" + _color + ";'>");
                        if (_fecha_vto > DateTime.Today)
                        {
                            _sb.Append("<td></td>");
                        }
                        else
                        {
                            if (_id_salida_cheques == "")
                            {
                                _visiblecheck = "display:block;";
                                _html_revertir = "";
                            }
                            else
                            {
                                _visiblecheck = "display:none;";
                                if (_dias_pasados == "0") { _html_revertir = ("<input type='button' id='revertir_" + _id_valorencartera + "' value='Revertir' onclick=javascript:RevertirMovBan_CEM('" + _id_valorencartera + "','" + _id_salida_cheques + "') />"); } else { _html_revertir = ""; }
                            }
                            switch (_valor)
                            {
                                case "EF":
                                    _sb.Append("<td align='center'><input style='" + _visiblecheck + "' type='checkbox' id='importe_efectivo_" + _id_valorencartera + "' value='" + _importe.ToString("0.00") + "' onclick='javascript:TotalizarMovBan_CEM();'/>" + _html_revertir + "</td>");
                                    break;
                                case "CH":
                                case "CX":
                                    _sb.Append("<td align='center'><input style='" + _visiblecheck + "' type='checkbox' id='importe_cheque_" + _id_valorencartera + "' value='" + _importe.ToString("0.00") + "' onclick='javascript:TotalizarMovBan_CEM();'/>" + _html_revertir + "</td>");
                                    break;
                                default:
                                    _sb.Append("<td></td>");
                                    break;
                            }
                        }
                        _sb.Append("<td>" + _valor + "</td>");
                        if (_fecha_vto.HasValue)
                        {
                            _sb.Append("<td>" + ((DateTime)_fecha_vto).ToString("dd/MM/yyyy") + "</td>");
                        }
                        else
                        {
                            _sb.Append("<td></td>");
                        }
                        _sb.Append("<td>" + _nro_cheque + "</td>");
                        _sb.Append("<td>" + _banco + "</td>");
                        _sb.Append("<td>" + _cliente + "</td>");
                        _sb.Append("<td class='importe_" + _valor + "'>" + _importe.ToString("0.00") + "</td>");
                        _sb.Append("<td>" + _nro_recibo + "</td>");
                        switch (_tipo_cheque)
                        {
                            case "P":
                                _sb.Append("<td>CHACARITA</td>");
                                break;
                            case "T":
                                _sb.Append("<td>NOGUÉS</td>");
                                break;
                            default:
                                switch (_id_empresa_sucursal)
                                {
                                    case "1":
                                        _sb.Append("<td>CHACARITA</td>");
                                        break;
                                    case "2":
                                        _sb.Append("<td>NOGUÉS</td>");
                                        break;
                                }
                                break;
                        }
                        _sb.Append("</tr>");
                    }
                    _sb.Append("</table>");

                    _sb.Append("<table cellpadding='2' style='border:solid 0px silver;'>");
                    _sb.Append("<tr>");
                    _sb.Append("<td><b>Total efectivo</b></td><td id='total_efectivo'></td>");
                    _sb.Append("<td><b>Total cheques</b></td><td id='total_cheques'></td>");
                    _sb.Append("<td><b>Total general</b></td><td id='total_general'></td>");
                    _sb.Append("</tr>");

                    _sb.Append("<tr>");
                    _sb.Append("<td><b>Cuenta bancaria</b></td>");
                    _sb.Append("<td>");
                    _sb.Append(_in.class_general.GetComboFromDatabase_Plain(HttpContext.Current.Session["database"].ToString(), "id_cuenta_bancaria", "SELECT * FROM dbo.vw_Cuenta_Bancaria ORDER BY detalle", "id_Cuenta_Bancaria", "detalle", "0", true, false, ""));
                    _sb.Append("</td><td><b>Fecha depósito</b></td><td>");
                    _sb.Append(_in.class_html.html_getDate("", "fecha_deposito", "", false, false, "", "", "", ""));
                    _sb.Append("<td></td><td><input id='btnGrabarDeposito' type='button' class='button' value='Grabar depósito' onclick=javascript:GrabarDeposito_CEM('div_valores_en_cartera')></td>");
                    _sb.Append("</tr>");

                    _sb.Append("</table>");
                    _dtbl.Clear();
                    _ds.Clear();

                    string _cuantos = "";
                    switch (_id_empresa_sucursal)
                    {
                        case "1":
                            //consulta contra la base de nogues, si hay cosas fisicamente en chacarita
                            _cuantos = _in.class_database.DLookupCount(HttpContext.Current.Session["database"].ToString(), "ID_ValorEnCartera", "neo_nogues.dbo.vw_valorEnCartera", "id_Salida_Cheques is null AND id_empresaSucursal=2 AND tipo_cheque IN ('P') AND ((Fecha_Vencimiento >= CONVERT(datetime,'" + _fecha_vencimiento_desde + "',103) AND Fecha_Vencimiento <= CONVERT(datetime,'" + _fecha_vencimiento_hasta + "',103)) OR Fecha_Vencimiento is null)");
                            if (_cuantos != "0") { _sb.Append("<h1 style='color:red;'>Existen valores en la caja de Nogués que deben ser revisados</h1>"); }
                            break;
                        case "2":
                            //consulta contra la base de chacarita, si hay cosas fisicamente en nogues
                            _cuantos = _in.class_database.DLookupCount(HttpContext.Current.Session["database"].ToString(), "ID_ValorEnCartera", "neo_britanico.dbo.vw_valorEnCartera", "id_Salida_Cheques is null AND id_empresaSucursal=1 AND tipo_cheque IN ('T') AND ((Fecha_Vencimiento >= CONVERT(datetime,'" + _fecha_vencimiento_desde + "',103) AND Fecha_Vencimiento <= CONVERT(datetime,'" + _fecha_vencimiento_hasta + "',103)) OR Fecha_Vencimiento is null)");
                            if (_cuantos != "0") { _sb.Append("<h1 style='color:red;'>Existen valores en la caja de Chacarita que deben ser revisados</h1>"); }
                            break;
                    }

                    break;


Acabo de armar el Brow de Deposito bancario. Faltan los edit sobre las cuentas, sobre los depositos, y el resto de reportes asociados.
Depósitos bancarios		Aparece el menu con el brow (OjO ). Faltan ver los reportes asociados. Aparece clase Deposito apuntando a m_depositos_bancarios (2098) relacionada con vw_Salida_cheques, cuando por la tabla funciones hubiera correspondido que sea m_movbanco (1065) relacionado con tabla y clase MovBanco
  
Luego hay que quitar SetInterfaceBrowserBORRAR.cs que es un ayudamemoria de como trabaja el reporte en el legacy

Armar otro listado de valores en cartera, por ahi adentro de depositos
SELECT * FROM dbo.vw_valorEnCartera
Si ID_Salida_Cheques is null --> checkbox para ABM, y va a la lista csv de spInsDepositoCheques:
@id_empresaSucursal INT, ---> confirmar si es fijo 1. O si sale del propio listado, pero en ese caso podria haber mas de una ejecucion del SP
@id_CajaTesoreria INT, -> confirmar si es fijo 1. O si sale del propio listado, pero en ese caso podria haber mas de una ejecucion del SP
 @id_Cuenta_Bancaria INT, --> dato ABM
 @fechaDeposito DATETIME, --> dato ABM
 @idsValoresEnCartera VARCHAR(MAX)  --> lista 
Los a vencer  se ponen en rojo (vto < hoy), lo vencido va en negro (vto > hoy.)

                       switch (_tipo_cheque)
                        {
                            case "P":
                                _sb.Append("<td>CHACARITA</td>");
                                break;
                            case "T":
                                _sb.Append("<td>NOGUÉS</td>");
                                break;
                            default:
                                switch (_id_empresa_sucursal)
                                {
                                    case "1":
                                        _sb.Append("<td>CHACARITA</td>");
                                        break;
                                    case "2":
                                        _sb.Append("<td>NOGUÉS</td>");
                                        break;
                                }
                                break;

Valor	Codigo_Valor
Fecha Vto.	Fecha_Vencimiento
Nºcheque	Nrp_Cheque
Banco	Desc_Bancos
Cliente	 RazonSocial
Importe	  Importe
Recibo	       Nro_Recibo
Físicamente en..   _id_empresa_sucursal ->1  Chacarita
											2 Nogues





select distinct object_name(m.object_id),o.type_desc 
from sys.sql_modules m 
inner join sys.objects o on o.object_id = m.object_id
where m.definition like '%banc%'
order by 2, 1



----------------------------------------------------
Hacer andar lo que no anda, lo de vencimientos
Terminar lo chico
Meter los filtros y exports
Ver asi los pseudo brow conviene separarlos a un PHP totalmente separado.
Sacar andarndo todo lo de parcelas
----------------------------------------------------

El brow envia en el json, un atributo message que tiene el html de la pagina de respuesta.
Se intento hacer un agregado posterior al brow poniendo el dibujo de la pagina con controles para procesar algo similar a un abm..pero codeigniter no tiene como descomprimir


Asi que volvemos a intentar haciendo que desde el menu se llame directamente al ABM. Es algo similar lo que en 
disidentes se hace en 

Facturacion -> Notificación de conservaciones
$lang['m_notificacion_conservaciones'] = "Notificación de conservaciones";

select id, id_parent, code, description, data_module, data_model, data_table, data_action 
from dbo.mod_backend_functions where code = 'm_notificacion_conservaciones'

id	  id_parent	code  description	                data_module	                        data_model	    data_table	    data_action
1060  1048	          m_notificacion_conservaciones	Submenú notificación conservaciones	mod_disidentes	Facturacion		notificacion_conservaciones
Facturacion.php  --> notificacion_conservaciones()


Depositos / Valores en Cartera. Ver si es necesario tener lo de Caja/Tesoreria.









ESTADOS PARCELA - DESENGANCHES
javascript:ToolbarDo('search','../_britanico/___browser_parcela.aspx?action=search%27,%27_self%27);

Todos
------
SELECT id_parcela,historico,contratos,tipo_parcela,Disponible,inhumados,estado_parcela,parcela_formateada,cliente,pagador,desenganche,estado_desenganche,parcela_historica,Sector,Manzana,Parcela,Secuencia,parcela_formateada3,ClienteCategoria,id_TipoParcela 
FROM vw_parcela ORDER BY sector ASC,manzana ASC, parcela ASC, secuencia ASC

<input type="button" class="button" value="Histórico" style="cursor:hand;" onclick="javascript:HistoricoParcela_CEM(3)"/>
javascript:HistoricoParcela_CEM(3)  -- Id Parcela.
historico_plano&modo=P
------> Actual
_CommandText = "SELECT * FROM dbo.vw_MovimientoInhumado  WHERE id_Parcela=" + _id + " AND detalle NOT LIKE '%CAMBIO%' ";
_CommandText += " and id_inhumado NOT IN (SELECT m.id_inhumado FROM MovimientoInhumado as m WHERE m.id_Parcela=" + _id + " AND m.detalle like '%SALIDA%') ";
_CommandText += " and FechaMovimiento IN (SELECT MAX(f.FechaMovimiento) FROM MovimientoInhumado as f WHERE f.id_Parcela=" + _id + "  AND f.id_inhumado = vw_MovimientoInhumado.id_inhumado AND (f.detalle like '%ENTRADA%' or f.detalle is null) ) ";
_CommandText += " order by FechaMovimiento";
-------> Completo
_CommandText = "SELECT * FROM dbo.vw_MovimientoInhumado WHERE id_parcela=" + _id + " ORDER BY FechaMovimiento";

<input type="button" class="button" value="Contratos" style="cursor:hand;" onclick="javascript:ContratosArrendamiento_CEM(3)"/>
javascript:ContratosArrendamiento_CEM(3)  -- Id Parcela.
___windows_loader_file_contents.aspx?tipo=contratos_arrendamiento_britanico&recordPK="+_id);
_ssql = "SELECT * FROM dbo.vw_rel_cuentacorriente_numeros WHERE id_parcela=" + _id + " ORDER BY Fecha_Vencimiento DESC"

En Proceso de desenganche
SELECT max(estado) as x,parcela_formateada,id_parcela,id_cliente,cliente,camino 
FROM dbo.vw_desenganche_parcela GROUP BY parcela_formateada,id_parcela,id_cliente,cliente,camino ORDER BY 2

case "DESENGANCHADAS-D": -- DEVOLUCION
    _ssql = "SELECT max(fecha_cambio) as x,parcela_formateada,id_parcela,id_cliente,cliente,camino,desc_estado 
    FROM dbo.vw_desenganche_parcela WHERE estado='B5' 
    GROUP BY parcela_formateada,id_parcela,id_cliente,cliente,camino,desc_estado ORDER BY 2";
    break;
case "DESENGANCHADAS-A":  -- ABANDONO
    _ssql = "SELECT max(fecha_cambio) as x,parcela_formateada,id_parcela,id_cliente,cliente,camino,desc_estado 
    FROM dbo.vw_desenganche_parcela WHERE estado='A5' 
    GROUP BY parcela_formateada,id_parcela,id_cliente,cliente,camino,desc_estado ORDER BY 2";
    break;
    
DESENGANCHADAS viejas
ResolveSQLByColumns(ref _in, "parcela_formateada", "dbo.vw_parcela_simplificada WHERE id_cliente is null

ACTA COMITE
SELECT max(fecha_cambio) as x,parcela_formateada,id_parcela,id_cliente,cliente,camino,desc_estado
 FROM dbo.vw_desenganche_parcela as d 
 WHERE d.estado='A4' AND d.id_parcela NOT IN (SELECT p.id_parcela from dbo.vw_desenganche_parcela as p 
                                            where p.estado='A5' and p.id_parcela=d.id_parcela) 
GROUP BY parcela_formateada,id_parcela,id_cliente,cliente,camino,desc_estado ORDER BY 2

ANALISIS DE GESTION -> (Full)
"SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE disponible='S' AND id_TipoParcela=" + _id_tipo
"SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE disponible!='S' AND id_TipoParcela=" + _id_tipo
"SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE isnull(ClienteCategoria,'N')='N' AND id_TipoParcela=" + _id_tipo
"SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE ClienteCategoria='C' AND id_TipoParcela=" + _id_tipo
SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE ClienteCategoria='J' AND id_TipoParcela=" + _id_tipo
SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE id_TipoParcela=" + _id_tipo
SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE disponible='S'
SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE disponible!='S'
SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE isnull(ClienteCategoria,'N')='N'
SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE ClienteCategoria='C'
SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela WHERE ClienteCategoria='J'
SELECT count(id_parcela) as parcelas FROM dbo.vw_parcela"
SELECT * FROM dbo.TipoParcela ORDER BY nombre ASC
SELECT SUM(t.saldo) AS importe FROM (SELECT DISTINCT isnull(saldo,0) as saldo , id_cliente FROM dbo.vw_deuda_segmento_1 WHERE id_tipoParcela=" + _id_tipo + ") as t"
"SELECT isnull(count(distinct id_cliente),0) as cliente FROM dbo.vw_deuda_segmento_1 WHERE id_tipoParcela=" + _id_tipo
SELECT SUM(t.saldo) AS importe FROM (SELECT DISTINCT isnull(saldo,0) as saldo , id_cliente FROM dbo.vw_deuda_segmento_2 WHERE id_tipoParcela=" + _id_tipo + ") as t
SELECT isnull(count(distinct id_cliente),0) as cliente FROM dbo.vw_deuda_segmento_2 WHERE id_tipoParcela=" + _id_tipo
SELECT SUM(t.saldo) AS importe FROM (SELECT DISTINCT isnull(saldo,0) as saldo , id_cliente FROM dbo.vw_deuda_segmento_3 WHERE id_tipoParcela=" + _id_tipo + ") as t
SELECT isnull(count(distinct id_cliente),0) as cliente FROM dbo.vw_deuda_segmento_3 WHERE id_tipoParcela=" + _id_tipo
SELECT SUM(t.saldo) AS importe FROM (SELECT DISTINCT isnull(saldo,0) as saldo , id_cliente FROM dbo.vw_deuda_segmento_4 WHERE id_tipoParcela=" + _id_tipo + ") as t
SELECT isnull(count(distinct id_cliente),0) as cliente FROM dbo.vw_deuda_segmento_4 WHERE id_tipoParcela=" + _id_tipo


HISTORICAS
_in.class_general.ResolveSQLByColumns(ref _in, "parcela_formateada", "dbo.vw_parcela AS p WHERE parcela_historica!=''"

SIN DETALLE
ResolveSQLByColumns(ref _in, "parcela_formateada", "dbo.vw_parcela AS p WHERE RevisadoGerencia='S'");

A1 Inicio Abandono/Devolucion   btnA
A2 Envio Carta                  btnA
A3 Recepcion Carta              btnA
A4 Acta Comite                  btnA
A5 Terminado por Devolucion     btnB
B1 Terminado por Abandono       btnA
B5 Devolucion Parcela           btnB
--> Suspender y Revertir proceso de Abandono btnX

A1 -> A2
A2 -> A3
A3 -> A4
A4 -> A5
B1 -> B5
case Default    A1
                B1
                btnX



___abm_parcela
javascript:Accept_ABM_Parcela_CEM()

numerar_CEM -> _ax_1_tools.ashx', 'action=numerar&modo=' + _data + "&id_parcela="
"numerar":
 _CommandText = "UPDATE dbo.parcela SET numero_pagina_mapa='" + _modo + "' WHERE id_Parcela=" + _id_parcela;


 CEM_NoInnovar(_id, _val) {
case "no_innovar":
_CommandText = "UPDATE dbo.inhumado SET no_innovar='" + _modo + "' WHERE id_inhumado=" + _id;


              case "desenganche_parcela":
                    if (_modo != "X")
                    {
                        _CommandText = "INSERT INTO dbo.desenganche_parcela (id_parcela,id_cliente,fecha_cambio,estado) VALUES (" + _id_parcela + "," + _id_cliente + ",GETDATE(),'" + _modo + "')";
                    }
                    else
                    {
                        _CommandText = "DELETE dbo.desenganche_parcela WHERE id_parcela=" + _id_parcela + " AND id_cliente=" + _id_cliente;
                    }
                    _CommandText = System.Uri.UnescapeDataString(_CommandText);
                    _in.class_database.Execute(ref _transaction, HttpContext.Current.Session["database"].ToString(), _CommandText, CommandBehavior.Default);
                    if (_modo == "A5" || _modo == "B5")
                    {
                        parms = new SqlParameter[3];
                        parms[0] = new SqlParameter("@id_parcela", SqlDbType.Float);
                        parms[0].Value = double.Parse(_id_parcela.Replace(".", ","));
                        parms[1] = new SqlParameter("@id_cliente", SqlDbType.Float);
                        parms[1].Value = double.Parse(_id_cliente.Replace(".", ","));
                        parms[2] = new SqlParameter("@estado", SqlDbType.VarChar, -1);
                        parms[2].Value = _modo;
                        try
                        {
                            _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "spDesengarcharParcela", parms);
                        }
                        catch (Exception ex) { }




                                   {
                _html += " <input type='button' class='button btnA' value='Inicio devolución' id='A_1' name='A_1' onclick='javascript:Abandono_CEM(1," + recordPK + "," + _id_cliente + ");'/>";
                _html += " <input type='button' class='button btnA' value='Envío carta' id='A_2' name='A_2' onclick='javascript:Abandono_CEM(2," + recordPK + "," + _id_cliente + ");'/>";
                _html += " <input type='button' class='button btnA' value='Recepción carta' id='A_3' name='A_3' onclick='javascript:Abandono_CEM(3," + recordPK + "," + _id_cliente + ");'/>";
                _html += " <input type='button' class='button btnA' value='Acta comité' id='A_4' name='A_4' onclick='javascript:Abandono_CEM(4," + recordPK + "," + _id_cliente + ");'/>";
                _html += " <input type='button' class='button btnA' value='Terminado por abandono' id='A_5' name='A_5' onclick='javascript:Abandono_CEM(5," + recordPK + "," + _id_cliente + ");'/>";
                _html += " <input type='button' class='button btnB' value='Devolución parcela' id='B_1' name='B_1' onclick='javascript:PresentacionPersonal_CEM(1," + recordPK + "," + _id_cliente + ");'/>";
                _html += " <input type='button' class='button btnB' value='Terminado por devolución' id='B_5' name='B_5' onclick='javascript:PresentacionPersonal_CEM(5," + recordPK + "," + _id_cliente + ");'/>";
                _html += " <input type='button' style='border:solid 2px red;' class='button btnX' value='Suspender y revertir el proceso de abandono' id='X_0' name='X_0' onclick='javascript:RevertirDesenganche_CEM(" + recordPK + "," + _id_cliente + ");'/>";



            sSql = "SELECT * FROM dbo.vw_Rel_Cliente_Pagador_Parcela WHERE id_cliente=" + _id_cliente + " AND id_parcela=" + recordPK;
            _ds = _in.class_database.GetDataSet(_local_database, sSql);
            _dtbl = _ds.Tables["data"];
            foreach (System.Data.DataRow row in _dtbl.Rows)
            {
                _id_cliente = row[_dtbl.Columns["id_cliente"]].ToString();
                _id_pagador = row[_dtbl.Columns["id_pagador"]].ToString();
                _cliente_relacionado += row[_dtbl.Columns["cliente"]].ToString() + ", PAGADOR: " + row[_dtbl.Columns["pagador"]].ToString();
            }
            _dtbl.Clear();
            _ds.Clear();
            _html += "<script>BuildAutoComplete('cliente_pagador','id_cliente|id_pagador','msg_cliente_pagador','tbl_rel',true,'');</script>";
            _html += "<script>";


                                _html = "<table cellpadding='5' id='tblFallecidos'>";
                _html += "<tr><td colspan=''><B>Contratos de arrendamiento de la parcela</B></td></tr>";
                _html += "<tr bgcolor='silver'>";
                _html += "<td align='right'><b>Vence</b></td>";
                _html += "<td align='right'><b>Emitido</b></td>";
                _html += "<td><b>Nºcontrato</b></td>";
                _html += "<td><b>Parcela</b></td>";
                _html += "<td><b>Cliente</b></td>";
                _html += "</tr>";
                sSql = "SELECT * FROM dbo.vw_rel_cuentacorriente_numeros WHERE id_parcela=" + recordPK + " ORDER BY Fecha_Vencimiento DESC";

                                _html = "<table cellpadding='5' id='tblFallecidos'>";
                _html += "<tr><td colspan=''><B>Listado de inhumados en la parcela</B></td></tr>";
                _html += "<tr bgcolor='silver'>";
                _html += "<td align='right'><b>Nº</b></td>";
                _html += "<td><b>Nombre</b></td>";
                _html += "<td align='center'><b>Deceso</b></td>";
                _html += "</tr>";
                sSql = "SELECT * FROM dbo.vw_inhumados_by_parcela_simple WHERE id_Parcela_Actual=" + recordPK + " ORDER BY NumeroInhumado ASC";
                _ds = _in.class_database.GetDataSet(_local_database, sSql);
                DeleteClienteParcela_CEM


fecha_limite_conservacion
TypeItem.textFree, "c1", false, "Tipo", "tipo_parcela", false, false);
TypeItem.textFree, "c2", false, "Disponible", "Disponible", false, false);  --> Disponible S/N
TypeItem.textFree, "c211", false, "Inhumados", "inhumados", false, false);
TypeItem.textHTML, "c777", false, "Estado", "estado_parcela", false, false);

TypeItem.textFree, "c4", false, "Parcela", "parcela_formateada", false, false);

TypeItem.textFree, "c6", false, "Cliente", "cliente", false, false);
TypeItem.textFree, "c7", false, "Pagador", "pagador", false, false);
TypeItem.textFree, "c81", false, "", "desenganche", false, false);
TypeItem.textFree, "c8", false, "Desenganche", "estado_desenganche", false, false);
TypeItem.textHTML, "c666", false, "Histórica", "parcela_historica", false, false); -> CodigoAnterior
"ClienteCategoria"  N J C
ImporteArrendamiento            
Datos Parcela -> Observaciones






Busqueda de cliente/pagador para la parcela en la que estoy

               case "cliente_pagador_parcela":
                    _field_search = "pagador";
                    _field2 = "";
                    _begin = "%";
                    _CommandText = "SELECT cast(id_cliente as varchar(200))+'|'+cast(id_pagador as varchar(200)) as id," + _field_search + " as " + _field_search + "," + _field_search + " as detalle ";
                    _CommandText += " FROM dbo.vw_Rel_Cliente_Pagador_Parcela ";
                    if (_id_search == 0){
                        _CommandText += " WHERE pagador Like '%" + _term + "%' ORDER BY 2";
                    }
                    else {
                        _CommandText += " WHERE NumeroPagador="+ _term + " ORDER BY 2";
                    }
                    break;
                case "cliente_pagador":
                    _field_search = "pagador";
                    _field2 = "";
                    _begin = "%";
                    _CommandText = "SELECT cast(id_cliente as varchar(200))+'|'+cast(id_pagador as varchar(200)) as id," + _field_search + " as " + _field_search + "," + _field_search + " as detalle ";
                    _CommandText += " FROM dbo.vw_Rel_Cliente_Pagador WHERE pagador Like '" + _begin + _term + "%' ORDER BY 2";
                    break;

En Parcela, estoy pasando el save al SP.
Hay que ver de pasar todo el resto de los parametros. O bien completar la lista para el save, o hacer el select y los que 
no fueron modificados pasarlos de ahi.

Lo que esta llegando es 
ClienteCategoriaNumero de paginaIdTipoParcela
CodigoAnterior
Observaciones
key -> id cliente y id pagador
id parcela

que onda el resto, que onda que no aparece Fecha Conservacion Hasta y Fecha Limite Conservacion

cd /d F:\Lu\SvnRepoCCO\neodata\Britanico\trunk
F:\Lu\SvnRepoCCO\neodata\Britanico\trunk> c:\php-7.1.29-nts-Win32-VC14-x64\php.exe -S localhost:4001


Clientes.
A la tabla cliente van Datos del Cliente que es el campo osbservacionnes. Hacerlo multiline
Y en la ventnana van las notas, tipo de nota y usuario que la asento.



dbo.coop_Cliente_Insert ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?

[dbo].[coop_Cliente_Insert]
    @id_cliente int output,
    @NumeroCliente int,
    @RazonSocial varchar(40),
    @DomicilioCalle varchar(40),
    @DomicilioNumero varchar(8),
    @DomicilioPiso varchar(3),
    @DomicilioDepartamento varchar(3),
    @DomicilioEntreCalles varchar(50),
    @Domicilio_id_provincia int,
    @Localidad varchar(30),
    @CodigoPostal varchar(15),
    @Telefono1 varchar(30),
    @Telefono2 varchar(30),
    @Cuit varchar(13),
    @id_CategoriaIva smallint,
    @ID_CuentaContable int,
    @id_TipoDocumento int,
    @NumeroDocumento numeric(8,0),
    @id_PaisNacionalidad int,
    @RecibeAviso char(1),
    @CeroDevengamiento char(1),
    @RevisadoGerencia char(1),
    @observaciones varchar(max)=''


coop_Cliente_Update ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?

[dbo].[coop_Cliente_Update]
	@id_cliente int output,
	@NumeroCliente int,
	@RazonSocial varchar(40),
	@DomicilioCalle varchar(40),
	@DomicilioNumero varchar(8),
	@DomicilioPiso varchar(3),
	@DomicilioDepartamento varchar(3),
	@DomicilioEntreCalles varchar(50),
	@Domicilio_id_provincia int,
	@Localidad varchar(30),
	@CodigoPostal varchar(15),
	@Telefono1 varchar(30),
	@Telefono2 varchar(30),
	@Cuit varchar(13),
	@id_CategoriaIva smallint,
	@ID_CuentaContable int,
	@id_TipoDocumento int,
	@NumeroDocumento numeric(8,0),
	--@FechaNacimiento datetime=null,
	@id_PaisNacionalidad int,
	--@FechaAlta datetime=null,
	@RecibeAviso char(1),
	@CeroDevengamiento char(1),
	@RevisadoGerencia char(1),
	@observaciones varchar(max)=''

TransferirTitularidad_CEM
     case "transferir_titularidad_cem":
                    parms = new SqlParameter[1];
                    parms[0] = new SqlParameter("@id_cliente", SqlDbType.Float);
                    parms[0].Value = double.Parse(_id_cliente.Replace(".", ","));
                    try
                    {
                        _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "spTransferirTitularidad", parms);

                        
VerHistoricoTitularidad_CEM
SELECT * FROM dbo.cliente_historico WHERE id_cliente_parent=" + recordPK + " ORDER BY fecha_historico DESC





    _sb.Append("<tr id='trDatosComplementarios' bgcolor='ivory' style='display:none;'>");
        _sb.Append("<td colspan='8' align='left'>");
        _sb.Append("<b>Datos del cliente</b><br/>");
        _sb.Append(_in.class_html.html_getTextArea(_datos_complementarios, "datos_complementarios", "", false, "55", "10", "", "") + "<br/>");
        _sb.Append("<input type='button' value='Grabar datos' onclick='javascript:GrabarDatosComplementarios_CEM(" + _id_cliente + ");'>");
        _sb.Append("</td>");
        _sb.Append("</tr>");

        _sb.Append("<tr id='trMasDeuda' bgcolor='ivory' style='display:none;'>");
        _sb.Append("<td colspan='8'>");
        _sb.Append("<table cellpadding='1'>");
        _sb.Append("<tr valign='top'>");
        _sb.Append("<td><b>Tipo de comprobante</b></td><td>" + _in.class_general.GetComboFromDatabase_Plain(HttpContext.Current.Session["database"].ToString(), "id_tipo_comprobante", "SELECT id_tipo_comprobante,DescTipos_Comprobantes FROM dbo.tipo_comprobante WHERE BL_CompProv='S' AND id_tipo_comprobante!=17 ORDER BY DescTipos_Comprobantes", "id_tipo_comprobante", "DescTipos_Comprobantes", "18", false, false, "") + "</td>");
        _sb.Append("<td><b>Parcela</b></td><td>" + _in.class_general.GetComboFromDatabase_Plain(HttpContext.Current.Session["database"].ToString(), "id_Parcela", "SELECT id_Parcela,parcela_formateada FROM dbo.vw_Parcela WHERE id_cliente=" + _id_cliente + " ORDER BY parcela_formateada", "id_Parcela", "parcela_formateada", "", false, false, "javascript:SetComboInhumados_CEM(this.value,'id_inhumado_cc');") + "</td>");
        _sb.Append("</tr>");
        _sb.Append("<tr valign='top'>");
        _sb.Append("<td><b>Operación</b></td><td>" + _in.class_general.GetComboFromDatabase_Plain(HttpContext.Current.Session["database"].ToString(), "ID_Operacion", "SELECT id_ConceptoListaPrecio, codigo+' - '+operacion as operacion FROM dbo.vw_ListaPrecio WHERE activo='A' ORDER BY 2", "id_ConceptoListaPrecio", "operacion", "", false, false, "javascript:SetPrecioDefault_CEM('ID_Operacion','importe_cc')") + "</td>");
        _sb.Append("<td><b>Importe</b></td><td>" + _in.class_html.html_getNumeric("0", "importe_cc", "", false, "15", "15", "0-99999999", "allow_neg", "", "") + "</td>");
        _sb.Append("</tr>");
        _sb.Append("<tr>");

        // Datos adicionales a los MCC  
        _sb.Append("<tr valign='top'>");
        _sb.Append("<td colspan='2'>");
        _sb.Append("  <table>");
        _sb.Append("    <tr valign='top'>");
        _sb.Append("      <td>Datos del inhumado</td>");
        _sb.Append("      <td valign='top'>");
        _sb.Append("         <select id='id_inhumado_cc' name='id_inhumado_cc'></select>");
        _sb.Append("      </td>");
        _sb.Append("    </tr>");
        _sb.Append("  </table>");
        _sb.Append("</td>");
        _sb.Append("<td><b>Fecha inicio período</b></td>");
        _sb.Append("<td>" + _in.class_html.html_getDate(DateTime.Today.ToString("dd/MM/yyyy"), "fecha_alta", "", false, false, "", "", "", "") + "</td>");
        _sb.Append("</tr>");

        _sb.Append("<tr valign='top'>");
        _sb.Append("<td>Descripción</td>");
        _sb.Append("<td colspan='5'>" + _in.class_html.html_getTextArea("", "descripcion_cc", "", false, "75", "3", "", "") + "</td>");
        _sb.Append("</tr>");

        _sb.Append("<tr>");
        _sb.Append("<td colspan='2'><i>No podrá grabar el recibo hasta tanto no grabe los movimientos manuales</i></td>");

        if (_pagar == "S")
        {
            _sb.Append("<td colspan='2' align='right'><input type='button' value='Agregar deuda' onclick=javascript:AgregarDeuda_CEM()></td>");


        case "transaccion_nueva_deuda":
                    string _id_tipo_comprobante_deuda = _in.class_general.RequestSecure(HttpContext.Current.Request["id_tipo_comprobante_deuda"], false).ToString();
                    string _id_parcela_deuda = _in.class_general.RequestSecure(HttpContext.Current.Request["id_parcela_deuda"], false).ToString();
                    string _id_operacion_deuda = _in.class_general.RequestSecure(HttpContext.Current.Request["id_operacion_deuda"], false).ToString();
                    string _importe_deuda = _in.class_general.RequestSecure(HttpContext.Current.Request["importe_deuda"], false).ToString();
                    string _descripcion_deuda = _in.class_general.RequestSecure(HttpContext.Current.Request["descripcion_deuda"], false).ToString();
                    string _id_inhumado = _in.class_general.RequestSecure(HttpContext.Current.Request["id_inhumado"], false).ToString();
                    string _fecha_alta = _in.class_general.RequestSecure(HttpContext.Current.Request["fecha_alta"], false).ToString();
                    _fecha_alta = _in.class_database.Data2DB(_fecha_alta);
                    

                    string[] v_id_tipo_comprobante_deuda = new string[1];
                    string[] v_id_parcela_deuda = new string[1];
                    string[] v_id_operacion_deuda = new string[1];
                    string[] v_descripcion_deuda = new string[1];
                    string[] v_importe_deuda = new string[1];
                    string[] v_id_inhumado = new string[1];

                    v_id_tipo_comprobante_deuda = _id_tipo_comprobante_deuda.Split(new char[] { ',' });
                    v_id_parcela_deuda = _id_parcela_deuda.Split(new char[] { ',' });
                    v_id_operacion_deuda = _id_operacion_deuda.Split(new char[] { ',' });
                    v_descripcion_deuda = _descripcion_deuda.Split(new char[] { ',' });
                    v_importe_deuda = _importe_deuda.Split(new char[] { ',' });
                    v_id_inhumado = _id_inhumado.Split(new char[] { ',' });
                    ic = 0;
                    StringBuilder sbItems = new StringBuilder();

                    foreach (string s in v_id_tipo_comprobante_deuda)
                    {
                        if (sbItems.Length > 0) { sbItems.Append("~"); }
                        if (v_descripcion_deuda[ic] == "") { v_descripcion_deuda[ic] = "."; }
                        if (v_id_inhumado[ic] == "") { v_id_inhumado[ic] = "0"; }
                        if (s == "17") { v_importe_deuda[ic] = "-" + v_importe_deuda[ic]; }
                        sbItems.AppendFormat("{0}^{1}^{2}^{3}", int.Parse(v_id_operacion_deuda[ic]), v_descripcion_deuda[ic], v_importe_deuda[ic].Replace(",", "."), v_id_inhumado[ic]);
                        ic += 1;
                    }
                    //_connection = new System.Data.SqlClient.SqlConnection();
                    parms = new SqlParameter[7];
                    parms[0] = new SqlParameter("@ID_EmpresaSucursal", SqlDbType.Int);
                    parms[0].Value = int.Parse(_id_empresa);
                    parms[1] = new SqlParameter("@id_cliente", SqlDbType.Int);
                    parms[1].Value = int.Parse(_id_cliente);
                    parms[2] = new SqlParameter("@id_Parcela", SqlDbType.Int);
                    parms[2].Value = int.Parse(v_id_parcela_deuda[0]);
                    parms[3] = new SqlParameter("@id_tipo_comprobante", SqlDbType.Int);
                    parms[3].Value = int.Parse(v_id_tipo_comprobante_deuda[0]);
                    parms[4] = new SqlParameter("@items", SqlDbType.VarChar, -1);
                    parms[4].Value = sbItems.ToString();
                    parms[5] = new SqlParameter("@fecha_alta", SqlDbType.VarChar, -1);
                    parms[5].Value = _fecha_alta;
                    parms[6] = new SqlParameter("@ajuste", SqlDbType.VarChar, -1);
                    parms[6].Value = "S";

                    try
                    {
                        _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "coop_CuentaCorriente_Insert_Custom", parms);



                        l = "SELECT * FROM dbo.CuentaCorriente WHERE id_CuentaCorriente=" + recordPK




                                     case "modificar_mcc":
                    string _fecha_emision_mcc = _in.class_general.RequestSecure(HttpContext.Current.Request["fecha_emision"], false).ToString();
                    string _fecha_vencimiento_mcc = _in.class_general.RequestSecure(HttpContext.Current.Request["fecha_vencimiento"], false).ToString();
                    string _id_ConceptoListaPrecio_mcc = _in.class_general.RequestSecure(HttpContext.Current.Request["id_ConceptoListaPrecio"], false).ToString();
                    string _importe_mcc = _in.class_general.RequestSecure(HttpContext.Current.Request["importe"], false).ToString();
                    string _saldo_mcc = _in.class_general.RequestSecure(HttpContext.Current.Request["saldo"], false).ToString();

                    parms = new SqlParameter[6];
                    parms[0] = new SqlParameter("@idCuentaCorriente", SqlDbType.Int);
                    parms[0].Value = int.Parse(_id_mcc);
                    parms[1] = new SqlParameter("@fecha_emision_mcc", SqlDbType.DateTime);
                    parms[1].Value = DateTime.Parse(_fecha_emision_mcc);
                    parms[2] = new SqlParameter("@fecha_vencimiento_mcc", SqlDbType.DateTime);
                    parms[2].Value = DateTime.Parse(_fecha_vencimiento_mcc);
                    parms[3] = new SqlParameter("@id_ConceptoListaPrecio_mcc", SqlDbType.Int);
                    parms[3].Value = int.Parse(_id_ConceptoListaPrecio_mcc);
                    parms[4] = new SqlParameter("@importe_mcc", SqlDbType.Float);
                    parms[4].Value = double.Parse(_importe_mcc.Replace(".", ","));
                    parms[5] = new SqlParameter("@saldo_mcc", SqlDbType.Float);
                    parms[5].Value = double.Parse(_saldo_mcc.Replace(".", ","));
                    try
                    {
                        _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "spUpdMCC", parms);




        case "borrar_mcc":
                    parms = new SqlParameter[1];
                    parms[0] = new SqlParameter("@idCuentaCorriente", SqlDbType.Int);
                    parms[0].Value = int.Parse(_id_mcc);
                    try
                    {
                        _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "spDelMCC", parms);





{
      if(confirm("Se pasará al histórico el cliente actual.  Debe cambiar los datos de este registro por los del nuevo cliente.\nConfirma?"))
      {
         var _param=('action=transferir_titularidad_cem&id_cliente='+_id_cliente);
         var _ret=AjaxExec('_ax_1_tools.ashx',_param);
         if(_ret=="")
            {
               $('table').css("background-color","red");
               $('#btnModificarNombre').show();





GetHistorico_Pagador_CEM
     _ssql = "SELECT * FROM dbo.pagador_historico WHERE id_pagador_parent= ? ORDER BY fecha_historico DESC"
        _in.class_database.GetDataReader(_local_database, _ssql, _readerfree)
        _sb.Append("<h2>Registro histórico del pagador Nº" + _nro_pagador + " </h2>")
        _sb.Append("<table width='100%'>")
        _sb.Append("      <tr>")
        _sb.Append("        <td align='right'><b>Nºpagador original</b></td>")
        _sb.Append("        <td align='center'><b>Fecha histórico</b></td>")
        _sb.Append("        <td><b>Nombre</b></td>")
        _sb.Append("        <td><b>Teléfono</b></td>")
        _sb.Append("        <td align='right'><b>Nºdocumento</b></td>")
        _sb.Append("      </tr>")
        Do While _readerfree.Read()
            _sb.Append("      <tr>")
            _sb.Append("        <td align='right'>" + _readerfree("NumeroPagador").ToString() + "</td>")
            _sb.Append("        <td align='center'>" + _readerfree("fecha_historico").ToString() + "</td>")
            _sb.Append("        <td>" + _readerfree("nombre").ToString() + "</td>")
            _sb.Append("        <td>" + _readerfree("telefono1").ToString() + "</td>")
            _sb.Append("        <td align='right'>" + _readerfree("numerodocumento").ToString() + "</td>")
            _sb.Append("      </tr>")
        Loop
        _sb.Append("</table>")
        _readerfree.Close()



Abandono_CEM(1," + recordPK + "," + _id_cliente + ")
'_ax_1_tools.ashx','action=desenganche_parcela&modo=A'+_i+'&id_parcela='+_id_parcela+'&id_cliente='+_id_cliente);
"INSERT INTO dbo.desenganche_parcela (id_parcela,id_cliente,fecha_cambio,estado) VALUES (" + _id_parcela + "," + _id_cliente + ",GETDATE(),'" + _modo + "')";

PresentacionPersonal_CEM(1," + recordPK + "," + _id_cliente + ")
ax_1_tools.ashx','action=desenganche_parcela&modo=B'+_i+'&id_parcela='+_id_parcela+'&id_cliente='+_id_cliente);
"INSERT INTO dbo.desenganche_parcela (id_parcela,id_cliente,fecha_cambio,estado) VALUES (" + _id_parcela + "," + _id_cliente + ",GETDATE(),'" + _modo + "')";

RevertirDesenganche_CEM(" + recordPK + "," + _id_cliente + ")
    AjaxExec('_ax_1_tools.ashx','action=desenganche_parcela&modo=X&id_parcela='+_id_parcela+'&id_cliente='+_id_cliente);

    --> "DELETE dbo.desenganche_parcela WHERE id_parcela=" + _id_parcela + " AND id_cliente=" + _id_cliente;

    if (_modo == "A5" || _modo == "B5")
    {
        parms = new SqlParameter[3];
        parms[0] = new SqlParameter("@id_parcela", SqlDbType.Float);
        parms[0].Value = double.Parse(_id_parcela.Replace(".", ","));
        parms[1] = new SqlParameter("@id_cliente", SqlDbType.Float);
        parms[1].Value = double.Parse(_id_cliente.Replace(".", ","));
        parms[2] = new SqlParameter("@estado", SqlDbType.VarChar, -1);
        parms[2].Value = _modo;
        try
        {
            _in.class_database.Execute(HttpContext.Current.Session["database"].ToString(), "spDesengarcharParcela", parms);
        }
        catch (Ex




Cambiar Parcela.brow a Parcela otro metodo
Pierdo filtro y exportacion
Lo manejo por JS
Para eso ya tengo el punto de menu m_parcela2


begin tran
update dbo.mod_backend_functions 
set code = 'm_parcela2', --'m_parcela'
	data_action = 'parcelas' -- 'brow'
where id = 1055
commit



 controlar los atributos del pagina funciona.Pero hay que hacerlo luego del buscar, que es cuando se acomoda todo
 Luego del buscar debo volver a setear los controles (radiobuton y paginado)
como a la vista estoy volviendo con values, alli deberia tener accesible el valor origial. ($values["data_adicional"]
los filtros andan si toco radiobutton y atras hago search

 falta cambiar desenganche



javascript:Abandono_CEM
javascript:PresentacionPersonal_CEM
javascript:RevertirDesenganche_CEM
