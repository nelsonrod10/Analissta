<script>
    $(document).ready(function(){
        $(document).foundation();
        
        $("#frm-nuevoPeligroDataGral").on("submit",function(e){
            var flagCheck=0;
            $(".fuentes").each(function(){
                 if($(this).is("input:checked")){
                    flagCheck=1;
                 }
              });

              if(flagCheck === 0){
                  alert("Seleccione por lo menos una fuente");
                  e.preventDefault();
              }
            
         });
         
         
        $("#frm-nuevoPeligroValoracion").on("submit",function(e){
            var flag=0;
           $(".input-nd-valoracion") .each(function(){
               if($(this).val() === ""){
                   flag=1;
               }
           });
           if(flag === 1){
               alert("Por favor Seleccione un nivel de Deficiencia");
               e.preventDefault();
           }
        });
        
        $(".selectRecLegal").each(function(){
            if($(this).val() === "Si" && $(this).is(":checked")){
                var numeroSelect = $(this).attr("data-numeroSelect");
                $("#row-descLegalCriterio-"+numeroSelect).removeClass("hide");
            }
        });
        
        
        if($(".input-nd-valoracion").val() !== ""){
            if($("#txt-efectoPersona").val() === "Corto y Largo Plazo"){
                calculosValoracion("2","Corto Plazo");
                calculosValoracion("3","Largo Plazo");
            }else{
                calculosValoracion("1",$("#txt-efectoPersona").val());
            }
        }
        
        $("#clasifNuevoPeligro").change(function(e){
           $("#div-descripcionesPeligros").html("<div><br/><i style='color:#ff4d4d;'>Debe Seleccionar una Clasificación</i></div>");
            $("#div-fuentesPeligros").html("<div><br/><i style='color:#ff4d4d;'>Debe Seleccionar una Descripción</i></div>");
           mostrarDescripciones($("#clasifNuevoPeligro").val(),null,null);
           e.preventDefault();
        });
        
        
        
        if($("#clasifNuevoPeligro").val() !== ""){
            $("#div-descripcionesPeligros").html("<div><br/><i style='color:#ff4d4d;'>Debe Seleccionar una Clasificación</i></div>");
            $("#div-fuentesPeligros").html("<div><br/><i style='color:#ff4d4d;'>Debe Seleccionar una Descripción</i></div>");
            //quiere decir que se dio click al boton anterior en el paso2 de creacion de un peligro
            mostrarDescripciones($("#clasifNuevoPeligro").val(),$("#vrDescripcion").val(),$("#vrSubDescripcion").val());
        }
        
        
        
        $('.mostrar-fuentes').on('change',function(){
                
                /*if ($(this).is(':checked')) {
                    
                }*/
        });
    
        
         
         
    });
    
    function mostrarDescripciones(idPeligro,idDesc,idSubDesc){
        
        if(idPeligro === ""){$("#div-descripcionesPeligros").html("<div><br/><i style='color:#ff4d4d;'>Debe Seleccionar una Clasificación</i></div>");}
        var form = $('#form-buscar-descripcion');
        var url = form.attr('action').replace(':id',idPeligro).replace(':idDesc',idDesc).replace(':idSubDesc',idSubDesc); 
        var data = form.serialize();
        $.post(url,data,function(result){
            $("#div-descripcionesPeligros").html(result.descripciones);
            var idInput ="";
            if(idSubDesc === "0"){
                idInput = "desc-"+idDesc;
            }else{
                idInput = "subdesc-"+idDesc+"-"+idSubDesc;
            }
            mostrarSubDesc_o_Fuentes(idInput);
        });
                           
        return;
    }
    
    function mostrarSubDesc_o_Fuentes(idInput){
        
        var idClasificacion = "";
        var idCategoria = "";
        $("#div-fuentesPeligros").html("<div><br/><i style='color:#ff4d4d;'>Debe Seleccionar una Descripción</i></div>");
        $('.div-subCategoria').each(function(){
            $(this).addClass('hide');
        });
        
        $('.input-subCategoria').each(function(){
            $(this).removeAttr('required');
        });
            
        var accion = $("#"+idInput).attr('data-accion');
        if(accion === "mostrar-fuentes-categoria"){
            idClasificacion = $("#"+idInput).attr("data-clasificacion");
            idCategoria = $("#"+idInput).val();
            mostrarFuentes(idClasificacion,idCategoria, 0);
        }
        if(accion === "mostrar-fuentes-subcategoria"){
            idClasificacion = $("#"+idInput).attr("data-clasificacion");
            idCategoria = $("#"+idInput).attr("data-descripcion");
            var idSubCategoria = $("#"+idInput).val();
            $("#subCategoria-"+idCategoria).removeClass('hide');
            mostrarFuentes(idClasificacion,idCategoria, idSubCategoria);
        }
        if(accion === "mostrar-subcategoria"){
            var id = $("#"+idInput).val();
                $('.input-subCategoria-descripcion-'+id).each(function(){
                    $(this).attr('required','');
                });
            $("#subCategoria-"+id).removeClass('hide');
        }
    }
    
    function mostrarFuentes(idClasificacion,idCategoria,idSubCategoria){
        
        if(idCategoria === ""){$("#div-fuentesPeligros").html("<div><br/><i style='color:#ff4d4d;'>Debe Seleccionar una Descripción</i></div>");}
        var form = $('#form-buscar-fuentes');
        var url = form.attr('action').replace(':idClasificacion',idClasificacion).replace(':idCategoria',idCategoria).replace(':idSubCategoria',idSubCategoria).replace(':fuentes',$("#vrFuentes").val()); 
        var data = form.serialize();
        
        $.post(url,data,function(result){
            $("#div-fuentesPeligros").html(result.fuentes);
        });
    }
    
    function calculosValoracion(numInput,tipoValoracion){
        
        var texto="";
        if(tipoValoracion === "Corto Plazo"){texto = "Plan de Gestión del Riesgo (PGRP)";}
        if(tipoValoracion === "Largo Plazo"){texto = "Plan de Vigilacia Epidemiologica (PVE)";}
        $("#div-alerta-plan-gestion-"+numInput).html("");
        var np=0;
        var nd = $("#nd-"+numInput).val();
        var ne = $("#ne-"+numInput).val();
        var nc = $("#nc-"+numInput).val();
        if(nd === "0" || nd === "" ){np=ne;}else{np= ne*nd;}
        var nri = np*nc;
        $("#npPeligro-"+numInput).html(np);
        $("#nriPeligro-"+numInput).html(nri);
        if(parseInt(np) >=2 && parseInt(np) <= 4 ){$("#npIntPeligro-"+numInput).html("Bajo");}
        if(parseInt(np) >=6 && parseInt(np) <= 8 ){$("#npIntPeligro-"+numInput).html("Medio");}
        if(parseInt(np) >=10 && parseInt(np) <= 20 ){$("#npIntPeligro-"+numInput).html("Alto");}
        if(parseInt(np) >=24 && parseInt(np) <= 40 ){$("#npIntPeligro-"+numInput).html("Muy Alto");}

        if(parseInt(nri) <= 20 ){$("#nriIntPeligro-"+numInput).html("IV - Mantener Controles Actuales");}
        if(parseInt(nri) >=40 && parseInt(nri) <= 120 ){$("#nriIntPeligro-"+numInput).html("III - Mejorar si es posible");}
        if(parseInt(nri) >=150 && parseInt(nri) <= 500 ){
            $("#nriIntPeligro-"+numInput).html("II - Corregir y adoptar medidas operacionales");
            $("#div-alerta-plan-gestion-"+numInput).html("Se incluirá en el "+texto);
        }
        if(parseInt(nri) >=600 && parseInt(nri) <= 4000 ){
            $("#nriIntPeligro-"+numInput).html("I - Situación Crítica, Corrección urgente");
            $("#div-alerta-plan-gestion-"+numInput).html("Este peligro se incluirá en el "+texto);
        }
    }
    
    function disabledMedidasIntervencion(){
        
        $(".check-medidas").each(function(){
           if($(this).attr("disabled") === "disabled"){
               $(this).removeAttr("disabled");
           }
           $(".label-check").each(function(){
                $(this).css("color","gray");
            });
        });
        $(".check-medidas").each(function(){
           if($(this).is("input:checked") && $(this).val() === "eliminar"){
               $(".disable-check").each(function(){
                   $(this).attr("disabled","true");
                   $(this).removeAttr("checked");
                   $(".label-check").each(function(){
                       $(this).css("color","lightgray");
                   });
               });
           }
        });
    }
</script>    

