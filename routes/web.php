<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
});


Route::view('videos','instructivos');
Route::view('comunidad','comunidad');
Route::view('registro-comunidad','comunidad-registro')->name('registro-comunidad');
Route::view('finalizar-registro-comunidad','inicio.registro-comunidad.finalizar');

Route::resource('comunidad-profesionales','Comunidad\ComunidadProfesionalesController');
Route::resource('comunidad-empresas','Comunidad\ComunidadEmpresasController');
Route::resource('especialidades-comunidad','Comunidad\EspecialidadesController');
Route::resource('especialidades-profesionales','Comunidad\ProfesionalesEspecialidadesController');
Route::resource('otras-especialidad-prof','Comunidad\ProfesionalesOtrasEspecialidadesController');
Route::resource('especialidades-empresas','Comunidad\EmpresasEspecialidadesController');
Route::resource('otras-especialidad-emp','Comunidad\EmpresasOtrasEspecialidadesController');
Route::resource('comunidad-invitados','Comunidad\InvitadosController');

Route::get('ver-especialidad-prof/{nuevo_miembro}','Comunidad\ProfesionalesEspecialidadesController@mostrarFrmEspecialidades')->name('ver-especialidad-prof');
Route::get('ver-especialidad-emp/{nuevo_miembro}','Comunidad\EmpresasEspecialidadesController@mostrarFrmEspecialidades')->name('ver-especialidad-emp');


Route::post('/calcular-precios/{empleados}','helpers@calcularPrecios');
Route::post('/especialidades-categoria/{categoria}','helpers@especialidadesCategoria');

Auth::routes(); //estan el archivo vendor/laravel/../Routing/Router.php



Route::middleware('auth')->group(function(){
    Route::get('/home', 'HomeController@index')->name('home');
    
    Route::resource('role-verify','RoleVerifyController');
    
    Route::resource('comunidad-analissta','Comunidad\ComunidadAnalisstaController');
    Route::resource('analissta-profesionales','Comunidad\ComunidadProfesionalesController');
    Route::resource('analissta-empresas','Comunidad\ComunidadEmpresasController');
    Route::post('update-analissta','ActualizacionesController@realizarActualizacion')->name('update-analissta');
    /*Route::resource('gestion-super-admin','SuperAdmin\SuperAdminController');
    Route::resource('gestion-asesores','Asesores\AsesoresController');
    Route::resource('gestion-administradores','Clientes\Usuarios\Administradores\AdministradoresController');
    Route::resource('gestion-evaluadores','Clientes\Usuarios\Evaluadores\EvaluadoresController');*/
    //se debe crear el middleware para verificar el rol del usuario
    
    Route::get('/inicio', function(){
        //si existe se elimina la variable de session
        \App\Http\Controllers\EmpresaClienteController::unsetSessionVariables();
        $user = App\User::find(Auth::user()->id);
        if (Gate::forUser(Auth::user())->allows('verify-Asesor-role')) {
            $asesorUser = $user->asesor;
            foreach ($asesorUser as $value) {
                $idAsesor = $value->id;
            }
            $asesor = App\Asesore::find($idAsesor);
            $empresaAsesor = $asesor->empresaAsesor;
            $empresasCliente = $asesor->empresasCliente;
            return view('analissta.inicioAsesor')->with(['datosEmpresa'=>$empresaAsesor,'empresasCliente'=>$empresasCliente]);
        }

        $empresaCliente = App\Usuario::find($user->usuario()->first()->id)->empresaCliente;
        //return view('analissta.inicioCliente')->with('datosEmpresa',$empresaCliente);
        return redirect()->route('ver-empresa-cliente',['id'=>$empresaCliente->id]);
    })->name('inicio');
    
    Route::prefix('/crear-empresa')->group(function(){
        
        Route::get('/contexto-general',function(){
            //$empresaAsesor = \App\Http\Controllers\helpers::getAsesor();
            return view('analissta.Asesores.crearEmpresa.contexto-general');
        })->name('contexto-general');
        
        Route::get('/empleados-jornada',function(){
            $empresaAsesor = \App\Http\Controllers\helpers::getAsesor();
            return view('analissta.Asesores.crearEmpresa.empleados-jornada')->with('datosEmpresa',$empresaAsesor);
        })->name('empleados-jornada');
        
        Route::get('/centros-trabajo',function(){
            $empresaAsesor = \App\Http\Controllers\helpers::getAsesor();
            return view('analissta.Asesores.crearEmpresa.centros-trabajo')->with('datosEmpresa',$empresaAsesor);
        })->name('centros-trabajo');
        
        Route::get('/tipo-valoracion',function(){
            $empresaAsesor = \App\Http\Controllers\helpers::getAsesor();
            return view('analissta.Asesores.crearEmpresa.tipo-valoracion')->with('datosEmpresa',$empresaAsesor);
        })->name('tipo-valoracion');
        
        Route::get('/afiliaciones',function(){
            $empresaAsesor = \App\Http\Controllers\helpers::getAsesor();
            return view('analissta.Asesores.crearEmpresa.afiliaciones')->with('datosEmpresa',$empresaAsesor);
        })->name('afiliaciones');
        
        Route::get('/resultados-anteriores',function(){
            $empresaAsesor = \App\Http\Controllers\helpers::getAsesor();
            return view('analissta.Asesores.crearEmpresa.resultados-anteriores')->with('datosEmpresa',$empresaAsesor);
        })->name('resultados-anteriores');
        
        /*RUTAS POST PARA CREAR UNA EMPRESA CLIENTE NUEVA*/
        Route::post('/cancelar-crear-empresa','EmpresaClienteController@deleteCrearEmpresaCliente')->name('cancelar-crear-empresa');
        Route::get('/eliminar-centro/{id}','CentrosTrabajoController@eliminarCentrosTrabajo')->name('eliminar-centro');
        
        Route::post('/crear-contexto-general','EmpresaClienteController@crearContextoGeneral')->name('crear-contexto-general');
        Route::post('/crear-empleados-jornada','EmpresaClienteController@crearEmpleadosJornada')->name('crear-empleados-jornada');
        Route::post('/crear-centros-trabajo/{origen}','CentrosTrabajoController@crearCentrosTrabajo')->name('crear-centros-trabajo');
        Route::post('/crear-tipo-valoracion','EmpresaClienteController@crearTipoValoracion')->name('crear-tipo-valoracion');
        Route::post('/crear-afiliaciones','EmpresaClienteController@crearAfiliaciones')->name('crear-afiliaciones');
        Route::post('/crear-resultados-anteriores','EmpresaClienteController@finalizarCrearEmpresa')->name('crear-resultados-anteriores');
        
        /*RUTAS PARA ACTUALIZAR DATOS EMPRESA CLIENTE */
        Route::get('/update-datos-empresa','EmpresaClienteController@getUpdateDatosEmpresa')->name('update-datos-empresa');    
        
        Route::post('/update-data-empresa','EmpresaClienteController@updateDatosEmpresa')->name('update-data-empresa');    
        
        Route::get('/update-datos-centro/{id}','CentrosTrabajoController@getUpdateDatosCentro')->name('update-datos-centro');    
        Route::post('/update-data-centro/{id}','CentrosTrabajoController@updateDatosCentro')->name('update-data-centro');    
        
        Route::get('/crear-nuevo-centro','CentrosTrabajoController@getFrmNuevoCentro')->name('crear-nuevo-centro');    
        
        
        
    });
    
    /*RUTAS PARA GESTIONAR EMPLEADOS*/
    Route::prefix('/empleados')->group(function(){
        Route::get('/agregar-nuevo-usuario','UsuarioController@getfrmAgregarUsuario')->name('agregar-nuevo-usuario');    
        Route::post('/crear-nuevo-usuario','UsuarioController@crearNuevoUsuario')->name('crear-nuevo-usuario');    
        Route::get('/eliminar-usuario/{id}','UsuarioController@eliminarUsuario')->name('eliminar-usuario');    
        
        Route::get('/buscar-empleado/{q}','EmpleadoController@buscarEmpleado');
        Route::get('/agregar-nuevo-empleado/{origen}/{idOrigen?}','EmpleadoController@getfrmAgregarEmpleado')->name('agregar-nuevo-empleado');    
        Route::get('/editar-datos-empleado/{origen}','EmpleadoController@getfrmEditarDatosEmpleado')->name('editar-datos-empleado');    
        Route::get('/mostrar-empleados','EmpleadoController@cargarBaseDatosEmpleados')->name('mostrar-empleados');    
        
        Route::resource('cargue-masivo-empleados','EmpleadosCargueMasivoController');    
        Route::post('/subir-archivo-empleados','EmpleadosCargueMasivoController@subirArchivo')->name('subir-archivo-empleados');
        Route::get('/eliminar-archivo-empleados','EmpleadosCargueMasivoController@eliminarArchivo')->name('eliminar-archivo-empleados');
        Route::post('/cargar-datos-empleado/{id}','EmpleadoController@cargarDatosEmpleado');
        Route::post('/agregar-nuevo-empleado/{id}/{idOrigen?}','EmpleadoController@agregarNuevoEmpleado')->name('agregar-nuevo-empleado');
        Route::get('/eliminar-empleado/{id}','EmpleadoController@eliminarEmpleado')->name('eliminar-empleado');
        
        Route::get('/perfil-socio-demografico/{id}','EmpleadoController@cargarFrmSocioDemografico')->name('perfil-socio-demografico');    
        Route::post('/perfil-socio-demografico/{id}','EmpleadoController@guardarPerfilSocioDemografico');    
        Route::post('/cambiar-centro-trabajo/{empleado}','EmpleadoController@cambiarCentroTrabajo')->name('cambiar-centro-trabajo');
        Route::post('/actualizar-fecha-ingreso/{empleado}','EmpleadoController@actualizarFechaIngreso')->name('actualizar-fecha-ingreso');
        
        Route::resource('/evaluaciones-medicas','Empleados\EvaluacionesMedicasController');    
        Route::resource('/programar-evaluacion-medica','Empleados\ProgramarEvaluacionMedicaController');    
        Route::resource('/realizar-evaluacion-medica','Empleados\RealizarEvaluacionMedicaController');    
    });
    
    /*RUTAS PARA GESTIONAR PROCESOS Y ACTIVIDADES DEL SISTEMA*/
    Route::prefix('/sistema')->group(function(){
        Route::get('/procesos-actividades/{sistema}','Sistema\SistemaController@getProcesosActividades')->name('procesos-actividades');    
        Route::post('/crear-proceso','Sistema\ProcesoController@crearProceso')->name('crear-proceso');    
        
        Route::get('/agregar-actividad/{idProceso}','Sistema\ActividadeController@getFrmCrearActividad')->name('agregar-actividad');    
        Route::post('/crear-actividad/{idProceso}','Sistema\ActividadeController@crearActividad')->name('crear-actividad');    
        
        Route::get('/ver-actividad-proceso/{id}','Sistema\ActividadeController@verActividad')->name('ver-actividad-proceso');    
        Route::get('/frm-actualizar-actividad-proceso/{nombreProceso}/{idActividad}','Sistema\ActividadeController@getFrmActualizarActividad')->name('frm-actualizar-actividad-proceso');
        Route::post('/actualizar-actividad-proceso/{idActividad}','Sistema\ActividadeController@actualizarActividad')->name('actualizar-actividad-proceso');
        Route::get('/eliminar-actividad-proceso/{id}','Sistema\ActividadeController@eliminarActividad')->name('eliminar-actividad-proceso');
        
        Route::get('/detalles-peligro/{idActividad}/{idPeligro}','Sistema\ActividadeController@detallesPeligro')->name('detalles-peligro');
        
    });
    
    /*RUTAS PARA GESTIONAR VALORACION DE PELIGROS*/
    Route::prefix('/valoracion')->group(function(){
        Route::get('/identificacion-peligro/{idActividad}','Valoracion\ValoracionController@cargarIdentificacionPeligro')->name('identificacion-peligro');    
        Route::get('/efectos-peligro/{idActividad}','Valoracion\ValoracionController@cargarEfectosPeligro')->name('efectos-peligro');    
        Route::get('/controles-peligro/{idActividad}','Valoracion\ValoracionController@cargarControlesPeligro')->name('controles-peligro');    
        Route::get('/valoracion-peligro/{idActividad}','Valoracion\ValoracionController@cargarValoracionPeligro')->name('valoracion-peligro');    
        Route::get('/criterios-peligro/{idActividad}','Valoracion\ValoracionController@cargarCriteriosPeligro')->name('criterios-peligro');    
        Route::get('/medidas-intervencion-peligro/{idActividad}','Valoracion\ValoracionController@cargarMedidasIntervencionPeligro')->name('medidas-intervencion-peligro');    
        Route::get('/volver-a-medidas-intervencion-peligro/{idActividad}','Valoracion\PeligroController@volverMedidasIntervencionPeligro')->name('volver-a-medidas-intervencion-peligro');    
        Route::get('/configurar-medida-intervencion/{idActividad}/{conteo}','Valoracion\ValoracionController@cargarConfigurarMedidaIntervencion')->name('configurar-medida-intervencion');
        Route::get('/avanzar-medida-intervencion/{idActividad}/{medida}','Valoracion\PeligroController@modificarArrayMedidas')->name('avanzar-medida-intervencion');
        Route::get('/finalizar-valoracion/{idActividad}','Valoracion\ValoracionController@finalizarValoracion')->name('finalizar-valoracion');
        Route::get('/cancelar-valoracion/{idActividad}','Valoracion\ValoracionController@cancelarValoracion')->name('cancelar-valoracion');
        
        Route::post('/buscar-descripcion-peligro/{idClasificacion}/{idDescripcion}/{idSubDesc}','Valoracion\PeligroController@buscarDescripcionPeligro')->name('buscar-descripcion-peligro');    
        Route::post('/buscar-fuentes-peligro/{idClasificacion}/{idCategoria}/{idSubCategoria}/{strFuentes}','Valoracion\PeligroController@buscarFuentesPeligro')->name('buscar-fuentes-peligro');    
        Route::post('/guardar-identificacion-peligro/{idActividad}','Valoracion\PeligroController@guardarIdentificacionPeligro')->name('guardar-identificacion-peligro');
        Route::post('/efectos-peligro/{idActividad}','Valoracion\PeligroController@efectosPeligro')->name('efectos-peligro');
        Route::post('/controles-peligro/{idActividad}','Valoracion\PeligroController@controlesPeligro')->name('controles-peligro');
        Route::post('/valoracion-peligro/{idActividad}','Valoracion\PeligroController@valoracionPeligro')->name('valoracion-peligro');
        Route::post('/criterios-peligro/{idActividad}','Valoracion\PeligroController@criteriosPeligro')->name('criterios-peligro');
        Route::post('/medidas-intervencion-peligro/{idActividad}','Valoracion\PeligroController@medidasIntervencionPeligro')->name('medidas-intervencion-peligro');
        Route::post('/crear-medida-intervencion/{idActividad}','Valoracion\PeligroController@crearMedidaIntervencion')->name('crear-medida-intervencion');
        Route::post('/eliminar-medida-intervencion/{idActividad}','Valoracion\PeligroController@eliminarMedidaIntervencion')->name('eliminar-medida-intervencion');
        
    });
    
    /*RUTAS PARA GESTIONAR REVALORACION DE PELIGROS*/
    Route::prefix('/revaloracion')->group(function(){
        Route::get('/revaloracion-peligro/{id}','Valoracion\RevaloracionController@cargarRevaloracion')->name('revaloracion-peligro');    
        Route::get('/criterios-revaloracion/{id}','Valoracion\RevaloracionController@cargarCriterios')->name('criterios-revaloracion');    
        Route::get('/medidas-intervencion-revaloracion/{id}','Valoracion\RevaloracionController@cargarMedidasIntervencion')->name('medidas-intervencion-revaloracion');    
        Route::get('/volver-a-medidas-intervencion-revaloracion/{idPeligro}','Valoracion\RevaloracionController@volverMedidasIntervencionPeligro')->name('volver-a-medidas-intervencion-revaloracion');    
        Route::get('/configurar-medida-intervencion-revaloracion/{idPeligro}/{conteo}','Valoracion\RevaloracionController@cargarConfigurarMedidaIntervencion')->name('configurar-medida-intervencion-revaloracion');
        Route::get('/avanzar-medida-intervencion-revaloracion/{idPeligro}/{medida}','Valoracion\RevaloracionController@modificarArrayMedidas')->name('avanzar-medida-intervencion-revaloracion');
        Route::get('/finalizar-revaloracion/{idPeligro}','Valoracion\RevaloracionController@finalizarRevaloracion')->name('finalizar-revaloracion');
        Route::get('/cancelar-revaloracion/{idPeligro}','Valoracion\RevaloracionController@cancelarRevaloracion')->name('cancelar-revaloracion');
        
        Route::post('/guardar-revaloracion-peligro/{idPeligro}','Valoracion\RevaloracionController@revaloracionPeligro')->name('guardar-revaloracion-peligro');
        Route::post('/guardar-criterios-revaloracion/{idPeligro}','Valoracion\RevaloracionController@criteriosRevaloracion')->name('guardar-criterios-revaloracion');
        Route::post('/medidas-intervencion-revaloracion/{idPeligro}','Valoracion\RevaloracionController@medidasIntervencionRevaloracion')->name('medidas-intervencion-revaloracion');
        Route::post('/crear-medida-intervencion-revaloracion/{idPeligro}','Valoracion\RevaloracionController@crearMedidaIntervencion')->name('crear-medida-intervencion-revaloracion');
        Route::post('/eliminar-medida-intervencion-revaloracion/{idActividad}','Valoracion\RevaloracionController@eliminarMedidaIntervencion')->name('eliminar-medida-intervencion-revaloracion');
        
    });
    
    /*RUTAS PARA GESTIONAR Actividades, Capacitaciones, Capacitaciones*/
    Route::prefix('/medidas-intervencion')->group(function(){
        Route::get('/actividades','MedidasIntervencion\MedidasIntervencionController@calendarioActividades')->name('actividades');
        Route::get('/capacitaciones','MedidasIntervencion\MedidasIntervencionController@calendarioCapacitaciones')->name('capacitaciones');
        Route::get('/inspecciones','MedidasIntervencion\MedidasIntervencionController@calendarioInspecciones')->name('inspecciones');
        
    /*Actividades*/    
        Route::get('/actividad-obligatoria/{id}','MedidasIntervencion\ActividadesObligatoriasController@verActividad')->name('actividad-obligatoria');
        Route::get('/actividad-sugerida/{id}','MedidasIntervencion\ActividadesSugeridasController@verActividad')->name('actividad-sugerida');
        Route::get('/actividad-valoracion/{id}','MedidasIntervencion\ActividadesValoracionController@verActividad')->name('actividad-valoracion');
        Route::get('/actividad-hallazgo/{id}','MedidasIntervencion\ActividadesHallazgosController@verActividad')->name('actividad-hallazgo');
        
        Route::get('/eliminar-actividad-obligatoria/{id}','MedidasIntervencion\ActividadesObligatoriasController@eliminarActividad')->name('eliminar-actividad-obligatoria');
        Route::get('/eliminar-actividad-sugerida/{id}','MedidasIntervencion\ActividadesSugeridasController@eliminarActividad')->name('eliminar-actividad-sugerida');
        
        Route::get('/programar-actividad/{id}/{tipo}','MedidasIntervencion\ActividadesController@iniciarProgramarActividad')->name('programar-actividad');
        Route::get('/programacion-actividad/{id}/{tipo}','MedidasIntervencion\ActividadesController@programacionActividad')->name('ver-programacion-actividad');
        Route::get('/programacion-presupuesto-actividad/{id}/{tipo}','MedidasIntervencion\ActividadesController@presupuestoActividad')->name('programacion-presupuesto-actividad');
        Route::get('/finalizar-programar-actividad/{id}/{tipo}','MedidasIntervencion\ActividadesController@finalizarProgramarActividad')->name('finalizar-programar-actividad');
        
        Route::post('/datos-generales-actividad','MedidasIntervencion\ActividadesController@datosGeneralesActividad')->name('datos-generales-actividad');
        Route::post('/programacion-actividad','MedidasIntervencion\ActividadesController@guardarProgramacionActividad')->name('programacion-actividad');
        Route::post('/presupuesto-actividad','MedidasIntervencion\ActividadesController@guardarPresupuestoActividad')->name('presupuesto-actividad');
        Route::get('/cancelar-programacion-actividad/{id}/{tipo}','MedidasIntervencion\ActividadesController@cancelarProgramacionActividad')->name('cancelar-programacion-actividad');
        
        Route::get('/ejecucion-actividad/{id}/{tipo}','MedidasIntervencion\ActividadesController@verEjecucionActividad')->name('ejecucion-actividad');
        Route::post('/guardar-ejecucion-actividad','MedidasIntervencion\ActividadesController@guardarEjecucionActividad')->name('guardar-ejecucion-actividad');
        
        Route::get('/calendario-actividades-semana/{mes}/{semana}','MedidasIntervencion\ActividadesCalendarioController@calendarioSemanalActividad')->name('calendario-actividades-semana');
        Route::get('/calendario-actividades-mes/{mes}','MedidasIntervencion\ActividadesCalendarioController@calendarioMensualActividad')->name('calendario-actividades-mes');
        
        Route::get('/indicadores-actividades','MedidasIntervencion\ActividadesIndicadoresController@Indicadores')->name('indicadores-actividades');
        Route::post('/data-indicadores-actividades','MedidasIntervencion\ActividadesIndicadoresController@getDataIndicadores')->name('data-indicadores-actividades');
        
        Route::post('/crear-actividad-sugerida','MedidasIntervencion\ActividadesSugeridasController@crearActividadSugerida')->name('crear-actividad-sugerida');
        Route::post('/crear-actividad-obligatoria','MedidasIntervencion\ActividadesObligatoriasController@crearActividadObligatoria')->name('crear-actividad-obligatoria');
        
    /*Inspecciones*/                
        Route::get('/inspeccion-obligatoria/{id}','MedidasIntervencion\InspeccionesObligatoriasController@verInspeccion')->name('inspeccion-obligatoria');
        Route::get('/inspeccion-sugerida/{id}','MedidasIntervencion\InspeccionesSugeridasController@verInspeccion')->name('inspeccion-sugerida');
        Route::get('/inspeccion-valoracion/{id}','MedidasIntervencion\InspeccionesValoracionController@verInspeccion')->name('inspeccion-valoracion');
        
        Route::get('/eliminar-inspeccion-obligatoria/{id}','MedidasIntervencion\InspeccionesObligatoriasController@eliminarInspeccion')->name('eliminar-inspeccion-obligatoria');
        Route::get('/eliminar-inspeccion-sugerida/{id}','MedidasIntervencion\InspeccionesSugeridasController@eliminarInspeccion')->name('eliminar-inspeccion-sugerida');
        
        Route::get('/programar-inspeccion/{id}/{tipo}','MedidasIntervencion\InspeccionesController@iniciarProgramarInspeccion')->name('programar-inspeccion');
        Route::get('/programacion-inspeccion/{id}/{tipo}','MedidasIntervencion\InspeccionesController@programacionInspeccion')->name('ver-programacion-inspeccion');
        Route::get('/programacion-presupuesto-inspeccion/{id}/{tipo}','MedidasIntervencion\InspeccionesController@presupuestoInspeccion')->name('programacion-presupuesto-inspeccion');
        Route::get('/finalizar-programar-inspeccion/{id}/{tipo}','MedidasIntervencion\InspeccionesController@finalizarProgramarInspeccion')->name('finalizar-programar-inspeccion');
        
        Route::post('/datos-generales-inspeccion','MedidasIntervencion\InspeccionesController@datosGeneralesInspeccion')->name('datos-generales-inspeccion');
        Route::post('/programacion-inspeccion','MedidasIntervencion\InspeccionesController@guardarProgramacionInspeccion')->name('programacion-inspeccion');
        Route::post('/presupuesto-inspeccion','MedidasIntervencion\InspeccionesController@guardarPresupuestoInspeccion')->name('presupuesto-inspeccion');
        Route::get('/cancelar-programacion-inspeccion/{id}/{tipo}','MedidasIntervencion\InspeccionesController@cancelarProgramacionInspeccion')->name('cancelar-programacion-inspeccion');
        
        Route::get('/ejecucion-inspeccion/{id}/{tipo}','MedidasIntervencion\InspeccionesController@verEjecucionInspeccion')->name('ejecucion-inspeccion');
        Route::post('/guardar-ejecucion-inspeccion','MedidasIntervencion\InspeccionesController@guardarEjecucionInspeccion')->name('guardar-ejecucion-inspeccion');
        
        Route::get('/hallazgos-inspeccion/{id}/{tipo}','MedidasIntervencion\InspeccionesController@verHallazgosInspeccion')->name('hallazgos-inspeccion');
        
        Route::get('/calendario-inspecciones-semana/{mes}/{semana}','MedidasIntervencion\InspeccionesCalendarioController@calendarioSemanalInspeccion')->name('calendario-inspecciones-semana');
        Route::get('/calendario-inspecciones-mes/{mes}','MedidasIntervencion\InspeccionesCalendarioController@calendarioMensualInspeccion')->name('calendario-inspecciones-mes');

        Route::get('/indicadores-inspecciones','MedidasIntervencion\InspeccionesIndicadoresController@Indicadores')->name('indicadores-inspecciones');
        Route::post('/data-indicadores-inspecciones','MedidasIntervencion\InspeccionesIndicadoresController@getDataIndicadores')->name('data-indicadores-inspecciones');
        
        Route::post('/crear-inspeccion-sugerida','MedidasIntervencion\InspeccionesSugeridasController@crearInspeccionSugerida')->name('crear-inspeccion-sugerida');
        Route::post('/crear-inspeccion-obligatoria','MedidasIntervencion\InspeccionesObligatoriasController@crearInspeccionObligatoria')->name('crear-inspeccion-obligatoria');
        
    /*Capacitaciones*/                
        Route::get('/capacitacion-obligatoria/{id}','MedidasIntervencion\CapacitacionesObligatoriasController@verCapacitacion')->name('capacitacion-obligatoria');
        Route::get('/capacitacion-sugerida/{id}','MedidasIntervencion\CapacitacionesSugeridasController@verCapacitacion')->name('capacitacion-sugerida');
        Route::get('/capacitacion-valoracion/{id}','MedidasIntervencion\CapacitacionesValoracionController@verCapacitacion')->name('capacitacion-valoracion');
        Route::get('/capacitacion-hallazgo/{id}','MedidasIntervencion\CapacitacionesHallazgosController@verCapacitacion')->name('capacitacion-hallazgo');
        
        Route::get('/eliminar-capacitacion-obligatoria/{id}','MedidasIntervencion\CapacitacionesObligatoriasController@eliminarCapacitacion')->name('eliminar-capacitacion-obligatoria');
        Route::get('/eliminar-capacitacion-sugerida/{id}','MedidasIntervencion\CapacitacionesSugeridasController@eliminarCapacitacion')->name('eliminar-capacitacion-sugerida');
        
        Route::get('/programar-capacitacion/{id}/{tipo}','MedidasIntervencion\CapacitacionesController@iniciarProgramarCapacitacion')->name('programar-capacitacion');
        Route::get('/programacion-capacitacion/{id}/{tipo}','MedidasIntervencion\CapacitacionesController@programacionCapacitacion')->name('ver-programacion-capacitacion');
        Route::get('/programacion-jornadas-capacitacion/{id}/{tipo}/{arrDataCentros}','MedidasIntervencion\CapacitacionesController@jornadasCapacitacion')->name('programacion-jornadas-capacitacion');
        Route::get('/programacion-presupuesto-capacitacion/{id}/{tipo}/{arrDataCentros}','MedidasIntervencion\CapacitacionesController@presupuestoCapacitacion')->name('programacion-presupuesto-capacitacion');
        Route::get('/finalizar-programar-capacitacion/{id}/{tipo}','MedidasIntervencion\CapacitacionesController@finalizarProgramarCapacitacion')->name('finalizar-programar-capacitacion');
        
        Route::post('/datos-generales-capacitacion','MedidasIntervencion\CapacitacionesController@datosGeneralesCapacitacion')->name('datos-generales-capacitacion');
        Route::post('/programacion-capacitacion','MedidasIntervencion\CapacitacionesController@guardarProgramacionCapacitacion')->name('programacion-capacitacion');
        Route::post('/jornadas-capacitacion','MedidasIntervencion\CapacitacionesController@guardarJornadasCapacitacion')->name('jornadas-capacitacion');
        Route::post('/presupuesto-capacitacion','MedidasIntervencion\CapacitacionesController@guardarPresupuestoCapacitacion')->name('presupuesto-capacitacion');
        Route::get('/cancelar-programacion-capacitacion/{id}/{tipo}','MedidasIntervencion\CapacitacionesController@cancelarProgramacionCapacitacion')->name('cancelar-programacion-capacitacion');
        
        Route::get('/ejecucion-capacitacion/{id}/{tipo}','MedidasIntervencion\CapacitacionesController@verEjecucionCapacitacion')->name('ejecucion-capacitacion');
        Route::post('/guardar-ejecucion-capacitacion','MedidasIntervencion\CapacitacionesController@guardarEjecucionCapacitacion')->name('guardar-ejecucion-capacitacion');
        
        Route::get('/calendario-capacitaciones-semana/{mes}/{semana}','MedidasIntervencion\CapacitacionesCalendarioController@calendarioSemanalCapacitacion')->name('calendario-capacitaciones-semana');
        Route::get('/calendario-capacitaciones-mes/{mes}','MedidasIntervencion\CapacitacionesCalendarioController@calendarioMensualCapacitacion')->name('calendario-capacitaciones-mes');
    
        Route::get('/indicadores-capacitaciones','MedidasIntervencion\CapacitacionesIndicadoresController@Indicadores')->name('indicadores-capacitaciones');
        Route::post('/data-indicadores-capacitaciones','MedidasIntervencion\CapacitacionesIndicadoresController@getDataIndicadores')->name('data-indicadores-capacitaciones');
        
        Route::post('/crear-capacitacion-sugerida','MedidasIntervencion\CapacitacionesSugeridasController@crearCapacitacionSugerida')->name('crear-capacitacion-sugerida');
        Route::post('/crear-capacitacion-obligatoria','MedidasIntervencion\CapacitacionesObligatoriasController@crearCapacitacionObligatoria')->name('crear-capacitacion-obligatoria');
        
    });
    
    /*RUTAS PARA GESTIONAR HALLAZGOS*/
    Route::prefix('/hallazgos')->group(function(){
        Route::get('/hallazgos','Hallazgos\HallazgosController@principalHallazgos')->name('hallazgos');    
        Route::get('/hallazgo/{id}','Hallazgos\HallazgosController@mostrarHallazgo')->name('hallazgo');    
        
        Route::get('/crear-hallazgo/{id?}','Hallazgos\HallazgosController@datosGenerales')->name('crear-hallazgo');    
        Route::get('/peligro-asociado-hallazgo/{id}','Hallazgos\HallazgosController@peligroAsociado')->name('peligro-asociado-hallazgo');
        Route::get('/causas-inmediatas-hallazgo/{id}','Hallazgos\HallazgosController@causasInmediatas')->name('causas-inmediatas-hallazgo');
        Route::get('/causas-basicas-hallazgo/{id}','Hallazgos\HallazgosController@causasBasicas')->name('causas-basicas-hallazgo');
        Route::get('/acciones-hallazgo/{id}','Hallazgos\HallazgosController@acciones')->name('acciones-hallazgo');
        Route::get('/actividades-hallazgo/{id}','Hallazgos\HallazgosController@actividades')->name('actividades-hallazgo');
        Route::get('/capacitaciones-hallazgo/{id}','Hallazgos\HallazgosController@capacitaciones')->name('capacitaciones-hallazgo');
        
        Route::post('/crear-datos-generales-hallazgo/{id?}','Hallazgos\HallazgosController@crearDatosGenerales')->name('crear-datos-generales-hallazgo');    
        Route::post('/crear-peligro-asociado-hallazgo/{id}','Hallazgos\HallazgosController@crearPeligroAsociado')->name('crear-peligro-asociado-hallazgo');    
        Route::post('/crear-causas-inmediatas-hallazgo/{id}','Hallazgos\HallazgosController@crearCausasInmediatas')->name('crear-causas-inmediatas-hallazgo');
        Route::post('/crear-causas-basicas-hallazgo/{id}','Hallazgos\HallazgosController@crearCausasBasicas')->name('crear-causas-basicas-hallazgo');
        Route::post('/crear-acciones-hallazgo/{id}','Hallazgos\HallazgosController@crearAcciones')->name('crear-acciones-hallazgo');
        Route::post('/crear-actividades-hallazgo/{id}','Hallazgos\HallazgosController@crearActividades')->name('crear-actividades-hallazgo');
        Route::post('/crear-capacitaciones-hallazgo/{id}','Hallazgos\HallazgosController@crearCapacitaciones')->name('crear-capacitaciones-hallazgo');
        Route::post('/editar-datos-hallazgo/{id}','Hallazgos\HallazgosController@editarDatosHallazgo')->name('editar-datos-hallazgo');
        
        
        Route::get('/eliminar-actividad-hallazgo/{idHallazgo}/{idActividad}','Hallazgos\HallazgosController@eliminarActividad')->name('eliminar-actividad-hallazgo');
        Route::get('/eliminar-capacitacion-hallazgo/{idHallazgo}/{idCapacitacion}','Hallazgos\HallazgosController@eliminarCapacitacion')->name('eliminar-capacitacion-hallazgo');
        
        Route::get('/eliminar-causa-inmediata-hallazgo/{idHallazgo}/{idCausa}','Hallazgos\HallazgosController@eliminarCausaInmediata')->name('eliminar-causa-inmediata-hallazgo');
        Route::get('/eliminar-causa-basica-hallazgo/{idHallazgo}/{idCausa}','Hallazgos\HallazgosController@eliminarCausaBasica')->name('eliminar-causa-basica-hallazgo');
        
        Route::get('/cancelar-crear-hallazgo/{id?}','Hallazgos\HallazgosController@cancelarHallazgo')->name('cancelar-crear-hallazgo');
        
        Route::post('/cierre-hallazgo/{id}','Hallazgos\CierreHallazgosController@cierreHallazgo')->name('cierre-hallazgo');
        Route::get('/actividades-cierre-hallazgo/{id}/{idCierre}','Hallazgos\CierreHallazgosController@actividades')->name('actividades-cierre-hallazgo');
        Route::get('/capacitaciones-cierre-hallazgo/{id}/{idCierre}','Hallazgos\CierreHallazgosController@capacitaciones')->name('capacitaciones-cierre-hallazgo');
        
        Route::get('/eliminar-actividad-cierre-hallazgo/{idHallazgo}/{idCierre}/{idActividad}','Hallazgos\CierreHallazgosController@eliminarActividad')->name('eliminar-actividad-cierre-hallazgo');
        Route::get('/eliminar-capacitacion-cierre-hallazgo/{idHallazgo}/{idCierre}/{idCapacitacion}','Hallazgos\CierreHallazgosController@eliminarCapacitacion')->name('eliminar-capacitacion-cierre-hallazgo');
        
        Route::post('/crear-actividades-cierre-hallazgo/{id}/{idCierre}','Hallazgos\CierreHallazgosController@crearActividades')->name('crear-actividades-cierre-hallazgo');
        Route::post('/crear-capacitaciones-cierre-hallazgo/{id}/{idCierre}','Hallazgos\CierreHallazgosController@crearCapacitaciones')->name('crear-capacitaciones-cierre-hallazgo');
        
        Route::get('/finalizar-cierre-hallazgo/{id}','Hallazgos\CierreHallazgosController@finalizarCierre')->name('finalizar-cierre-hallazgo');
        
        Route::get('/cancelar-cierre-hallazgo/{id}/{idCierre}','Hallazgos\CierreHallazgosController@cancelarCierre')->name('cancelar-cierre-hallazgo');
        
        Route::get('/crear-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo?}','Hallazgos\HallazgosInspeccionesController@datosGenerales')->name('crear-hallazgo-inspeccion');    
        Route::get('/peligro-asociado-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@peligroAsociado')->name('peligro-asociado-hallazgo-inspeccion');
        Route::get('/causas-inmediatas-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@causasInmediatas')->name('causas-inmediatas-hallazgo-inspeccion');
        Route::get('/causas-basicas-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@causasBasicas')->name('causas-basicas-hallazgo-inspeccion');
        Route::get('/acciones-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@acciones')->name('acciones-hallazgo-inspeccion');
        Route::get('/actividades-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@actividades')->name('actividades-hallazgo-inspeccion');
        Route::get('/capacitaciones-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@capacitaciones')->name('capacitaciones-hallazgo-inspeccion');
        
        Route::post('/crear-datos-generales-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{id?}','Hallazgos\HallazgosInspeccionesController@crearDatosGenerales')->name('crear-datos-generales-hallazgo-inspeccion');
        Route::post('/crear-peligro-asociado-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@crearPeligroAsociado')->name('crear-peligro-asociado-hallazgo-inspeccion');    
        Route::post('/crear-causas-inmediatas-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@crearCausasInmediatas')->name('crear-causas-inmediatas-hallazgo-inspeccion');
        Route::post('/crear-causas-basicas-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@crearCausasBasicas')->name('crear-causas-basicas-hallazgo-inspeccion');
        Route::post('/crear-acciones-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@crearAcciones')->name('crear-acciones-hallazgo-inspeccion');
        Route::post('/crear-actividades-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@crearActividades')->name('crear-actividades-hallazgo-inspeccion');
        Route::post('/crear-capacitaciones-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}','Hallazgos\HallazgosInspeccionesController@crearCapacitaciones')->name('crear-capacitaciones-hallazgo-inspeccion');
        
        Route::get('/eliminar-causa-inmediata-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}/{idCausa}','Hallazgos\HallazgosInspeccionesController@eliminarCausaInmediata')->name('eliminar-causa-inmediata-hallazgo-inspeccion');
        Route::get('/eliminar-causa-basica-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}/{idCausa}','Hallazgos\HallazgosInspeccionesController@eliminarCausaBasica')->name('eliminar-causa-basica-hallazgo-inspeccion');
        
        Route::get('/eliminar-actividad-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}/{idActividad}','Hallazgos\HallazgosInspeccionesController@eliminarActividad')->name('eliminar-actividad-hallazgo-inspeccion');
        Route::get('/eliminar-capacitacion-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo}/{idCapacitacion}','Hallazgos\HallazgosInspeccionesController@eliminarCapacitacion')->name('eliminar-capacitacion-hallazgo-inspeccion');
        Route::get('/cancelar-crear-hallazgo-inspeccion/{idInspeccion}/{tipoInspeccion}/{idHallazgo?}','Hallazgos\HallazgosInspeccionesController@cancelarHallazgo')->name('cancelar-crear-hallazgo-inspeccion');
    });
    
    
    /*RUTAS PARA GESTIONAR ACCIDENTES*/
    Route::prefix('/accidentes')->group(function(){
        Route::get('/accidentes','Accidentes\AccidentesController@principalAccidentes')->name('accidentes');    
        Route::get('/accidente/{id}','Accidentes\AccidentesController@mostrarAccidente')->name('accidente');    
        
        Route::get('/crear-accidente/{id?}','Accidentes\AccidentesController@datosGenerales')->name('crear-accidente');    
        Route::get('/datos-accidente/{id}','Accidentes\AccidentesController@datosAccidente')->name('datos-accidente');    
        Route::get('/afectacion-cuerpo-accidente/{id}','Accidentes\AccidentesController@afectacionCuerpo')->name('afectacion-cuerpo-accidente');
        Route::get('/afectacion-lesion-accidente/{id}','Accidentes\AccidentesController@afectacionLesion')->name('afectacion-lesion-accidente');
        Route::get('/afectacion-agente-accidente/{id}','Accidentes\AccidentesController@afectacionAgente')->name('afectacion-agente-accidente');
        Route::get('/afectacion-fuente-accidente/{id}','Accidentes\AccidentesController@afectacionFuente')->name('afectacion-fuente-accidente');
        Route::get('/peligro-asociado-accidente/{id}','Accidentes\AccidentesController@peligroAsociado')->name('peligro-asociado-accidente');
        Route::get('/causas-inmediatas-accidente/{id}','Accidentes\AccidentesController@causasInmediatas')->name('causas-inmediatas-accidente');
        Route::get('/causas-basicas-accidente/{id}','Accidentes\AccidentesController@causasBasicas')->name('causas-basicas-accidente');
        Route::get('/costos-accidente/{id}','Accidentes\AccidentesController@costos')->name('costos-accidente');
        
        Route::post('/crear-datos-generales-accidente/{id?}','Accidentes\AccidentesController@crearDatosGenerales')->name('crear-datos-generales-accidente');    
        Route::post('/crear-datos-accidentado-accidente/{id}','Accidentes\AccidentesController@crearDatosAccidentado')->name('crear-datos-accidentado-accidente');
        Route::post('/crear-afectacion-cuerpo-accidente/{id}','Accidentes\AccidentesController@crearAfectacionCuerpo')->name('crear-afectacion-cuerpo-accidente');
        Route::post('/crear-afectacion-lesion-accidente/{id}','Accidentes\AccidentesController@crearAfectacionLesion')->name('crear-afectacion-lesion-accidente');
        Route::post('/crear-afectacion-agente-accidente/{id}','Accidentes\AccidentesController@crearAfectacionAgente')->name('crear-afectacion-agente-accidente');
        Route::post('/crear-afectacion-fuente-accidente/{id}','Accidentes\AccidentesController@crearAfectacionFuente')->name('crear-afectacion-fuente-accidente');
        Route::post('/crear-peligro-asociado-accidente/{id}','Accidentes\AccidentesController@crearPeligroAsociado')->name('crear-peligro-asociado-accidente');    
        Route::post('/crear-causas-inmediatas-accidente/{id}','Accidentes\AccidentesController@crearCausasInmediatas')->name('crear-causas-inmediatas-accidente');
        Route::post('/crear-causas-basicas-accidente/{id}','Accidentes\AccidentesController@crearCausasBasicas')->name('crear-causas-basicas-accidente');
        Route::post('/crear-costos-accidente/{id}','Accidentes\AccidentesController@crearCostos')->name('crear-costos-accidente');
        
        Route::get('/eliminar-causa-inmediata-accidente/{idAccidente}/{idCausa}','Accidentes\AccidentesController@eliminarCausaInmediata')->name('eliminar-causa-inmediata-accidente');
        Route::get('/eliminar-causa-basica-accidente/{idAccidente}/{idCausa}','Accidentes\AccidentesController@eliminarCausaBasica')->name('eliminar-causa-basica-accidente');
        
        Route::get('/cancelar-crear-accidente/{id?}','Accidentes\AccidentesController@cancelarAccidente')->name('cancelar-crear-accidente');
        
        Route::get('/crear-hallazgo-accidente/{idAccidente}/{idHallazgo?}','Accidentes\AccidentesHallazgoController@datosGenerales')->name('crear-hallazgo-accidente');    
        Route::get('/acciones-hallazgo-accidente/{idAccidente}','Accidentes\AccidentesHallazgoController@acciones')->name('acciones-hallazgo-accidente');
        Route::get('/actividades-hallazgo-accidente/{idAccidente}','Accidentes\AccidentesHallazgoController@actividades')->name('actividades-hallazgo-accidente');
        Route::get('/capacitaciones-hallazgo-accidente/{idAccidente}','Accidentes\AccidentesHallazgoController@capacitaciones')->name('capacitaciones-hallazgo-accidente');
        
        Route::post('/crear-acciones-hallazgo-accidente/{idAccidente}','Accidentes\AccidentesHallazgoController@crearAcciones')->name('crear-acciones-hallazgo-accidente');
        Route::post('/crear-actividades-hallazgo-accidente/{idAccidente}','Accidentes\AccidentesHallazgoController@crearActividades')->name('crear-actividades-hallazgo-accidente');
        Route::post('/crear-capacitaciones-hallazgo-accidente/{idAccidente}','Accidentes\AccidentesHallazgoController@crearCapacitaciones')->name('crear-capacitaciones-hallazgo-accidente');
        
        Route::post('/eliminar-actividad-hallazgo-accidente/{idAccidente}','Accidentes\AccidentesHallazgoController@eliminarActividad')->name('eliminar-actividad-hallazgo-accidente');
        Route::post('/eliminar-capacitacion-hallazgo-accidente/{idAccidente}','Accidentes\AccidentesHallazgoController@eliminarCapacitacion')->name('eliminar-capacitacion-hallazgo-accidente');
        
        Route::get('/cancelar-crear-hallazgo-accidente/{idAccidente}/{idHallazgo}','Accidentes\AccidentesHallazgoController@cancelarHallazgo')->name('cancelar-crear-hallazgo-accidente');
        
        Route::get('/crear-ausentismo-accidente/{idAccidente}','Accidentes\AccidentesAusentismoController@crearAusentismo')->name('crear-ausentismo-accidente');    
        Route::get('/datos-generales-ausentismo-accidente/{idAccidente}','Accidentes\AccidentesAusentismoController@datosGenerales')->name('datos-generales-ausentismo-accidente');
        Route::get('/diagnostico-ausentismo-accidente/{idAccidente}','Accidentes\AccidentesAusentismoController@diagnostico')->name('diagnostico-ausentismo-accidente');
        
        Route::post('/datos-generales-ausentismo-accidente/{idAccidente}','Accidentes\AccidentesAusentismoController@crearDatosGenerales')->name('datos-generales-ausentismo-accidente');
        Route::post('/diagnostico-ausentismo-accidente/{idAccidente}','Accidentes\AccidentesAusentismoController@crearDiagnostico')->name('diagnostico-ausentismo-accidente');
        
        Route::get('/cancelar-crear-ausentismo-accidente/{idAccidente}/{idAusencia}','Accidentes\AccidentesAusentismoController@cancelarAusentismo')->name('cancelar-crear-ausentismo-accidente');
        
    });
    
    /*RUTAS PARA GESTIONAR AUSENTISMOS*/
    Route::prefix('/ausentismos')->group(function(){
        Route::get('/ausentismos','Ausentismos\AusentismosController@principalAusentismos')->name('ausentismos');    
        Route::get('/ausentismo/{id}','Ausentismos\AusentismosController@mostrarAusentismo')->name('ausentismo');    
        
        Route::get('/crear-ausentismo/{id?}','Ausentismos\AusentismosController@datosGenerales')->name('crear-ausentismo');    
        Route::get('/datos-ausentismo/{id}','Ausentismos\AusentismosController@datosAusentismo')->name('datos-ausentismo');    
        Route::get('/diagnostico-ausentismo/{id}','Ausentismos\AusentismosController@diagnosticoAusentismo')->name('diagnostico-ausentismo');
        
        Route::post('/crear-datos-generales-ausentismo/{id?}','Ausentismos\AusentismosController@crearDatosGenerales')->name('crear-datos-generales-ausentismo');    
        Route::post('/crear-datos-ausentado/{id}','Ausentismos\AusentismosController@crearDatosAusentado')->name('crear-datos-ausentado');
        Route::post('/crear-diagnostico-ausentismo/{id}','Ausentismos\AusentismosController@crearDiagnostico')->name('crear-diagnostico-ausentismo');
        
        Route::post('/calcular-fecha-final-ausentismo/{d}/{h}/{fi}/{hi}','Ausentismos\AusentismosController@calcularFechaFinal')->name('calcular-fecha-final-ausentismo');
        Route::post('/buscar-diagnostico/{diagnostico}','Ausentismos\AusentismosController@buscarDiagnostico')->name('buscar-diagnostico');
        Route::post('/cargar-datos-diagnostico/{diagnostico}','Ausentismos\AusentismosController@datosDiagnostico')->name('cargar-datos-diagnostico');
        
        Route::get('/cancelar-crear-ausentismo/{id?}','Ausentismos\AusentismosController@cancelarAusentismo')->name('cancelar-crear-ausentismo');
        
        Route::post('/agregar-prorroga','Ausentismos\AusentismosProrrogasController@showFrmProrroga')->name('agregar-prorroga');
        Route::post('/crear-prorroga','Ausentismos\AusentismosProrrogasController@crearProrroga')->name('crear-prorroga');
        
    });
    
    /*RUTAS PARA GESTIONAR PGRP*/
    Route::prefix('/riesgos-prioritarios')->group(function(){
        Route::get('/pgrps','PlanesGestion\PgrpController@principalPgrp')->name('pgrps');    
        Route::get('/pgrp/{tipo}/{id}','PlanesGestion\PgrpController@mostrarPgrp')->name('pgrp');
        Route::get('/editar-pgrp/{clasificacion}/{id}','PlanesGestion\PgrpController@editarPgrp')->name('editar-pgrp');
        
        Route::post('/datos-pgrp','PlanesGestion\PgrpController@datosPgrp')->name('datos-pgrp');
    });
    
    /*RUTAS PARA GESTIONAR PVE*/
    Route::prefix('/vigilancia-epidemiologica')->group(function(){
        Route::get('/pves','PlanesGestion\PveController@principalPve')->name('pves');    
        Route::get('/pve/{tipo}/{id}','PlanesGestion\PveController@mostrarPve')->name('pve');
        Route::get('/editar-pve/{clasificacion}/{id}','PlanesGestion\PveController@editarPve')->name('editar-pve');
        
        Route::post('/datos-pve','PlanesGestion\PveController@datosPve')->name('datos-pve');
    });
    
    /*RUTAS PARA GESTIONAR PRESUPUESTO*/
    Route::prefix('/presupuesto')->group(function(){
        Route::post('/eliminar-item-presupuesto','PresupuestoController@eliminarItemPresupuesto')->name('eliminar-item-presupuesto');
        Route::post('/eliminar-item-presupuesto-capacitacion','PresupuestoController@eliminarItemPresupuestoCapacitacion')->name('eliminar-item-presupuesto-capacitacion');
        Route::get('/ver-medida-intervencion/{presupuesto}/{tipo}','PresupuestoController@verMedidaIntervencion')->name('ver-medida-intervencion');
        Route::resource("general","PresupuestoController");
        Route::resource("presupuesto-ejecucion","PresupuestoEjecucionController");
    });
    
    /*RUTAS PARA GESTIONAR EVIDENCIAS*/
    Route::prefix('/evidencias')->group(function(){
        /*Route::get('/ver-evidencias-actividad/{origenId}/{origenTable}/{tipo}','EvidenciasController@cargarEvidenciasActividad')->name('ver-evidencias-actividad');
        Route::get('/ver-evidencias-capacitacion/{origenId}/{origenTable}/{tipo}','EvidenciasController@cargarEvidenciasCapacitacion')->name('ver-evidencias-capacitacion');
        Route::get('/ver-evidencias/{origenId}/{origenTable}/{tipo}','EvidenciasController@cargarEvidencias')->name('ver-evidencias');
        Route::post('/agregar-evidencia','EvidenciasController@agregarEvidencia')->name('agregar-evidencia');*/
        Route::resource('gestionar-evidencia','EvidenciasController');
    });
    
    /*
     * Ruta para ver la empresa Cliente de un asesor
     */
    Route::get('/ver-empresa-cliente/{id}','EmpresaClienteController@cargarPaginaEmpresa')->name('ver-empresa-cliente');
    
    /*
     * Ruta para que un usuario cliente entre a la pagina de su empresa
     */
    //Route::get('/principal-empresa-cliente/{idUsuario}','EmpresaClienteController@paginaPrincipalEmpresa')->name('principal-empresa-cliente');
    
    Route::get('/test','EmpresaClienteController@finalizarCrearEmpresa');
    
    Route::get('/files',  'DownloadFilesController@index');

    Route::post('/file', 'DownloadFilesController@download');
    
    
});

