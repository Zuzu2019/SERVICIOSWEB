<?php
	//header("Content-Type: text/xml; charset=UTF-8\r\n");
    ini_set("log_errors", 1);
    ini_set("error_log", "reportes/php-error-producto.log");

	require_once 'vendor/autoload.php';
    //require_once 'nusoap/lib/nusoap.php';		//PHP v7.4.x o inferior
    
    /**
        CREACIÓN Y CONFIGURACIÓN DEL OBJETO QUE DEFINIRÁ EL SERVICIO WEB TIPO SOAP
    */
    $server = new soap_server();
    /*
        configureWSDL('Nombre del Servicio', 'targetNamespace');
        
        targetNamespace: Hacemos que el esquema que estamos creando tenga asociado un espacio 
                         de nombres propio (target namespace). Para ello se puede utilizar el 
                         atributo targetNamespace del elemento "schema":
    */
    $server->configureWSDL('WebServicesBUAP', 'urn:buap_api');
 	$server->soap_defencoding = 'UTF-8';
	$server->decode_utf8 = false;
	$server->encode_utf8 = true;

    /**
        REGISTRO DE LA OPERACIÓN getProd EN LA INTERFAZ DEL SERVICIO (WSDL)
    */
    $server->register( 'getProd',                             // Nombre de la operación (método)
                        array('categoria' => 'xsd:string'),     // Lista de parámetros; varios de tipo simple o un tipo complejo
                        array('return' => 'xsd:string'),        // Respuesta; de tipo simple o de tipo complejo
                        'urn:producto',                         // Namespace para entradas (input) y salidas (output)
                        'urn:producto#getProd',                 // Nombre de la acción (soapAction)
                        'rpc',                                  // Estilo de comunicación (rpc|document)
                        'encoded',                              // Tipo de uso (encoded|literal)
                        'Nos da una lista de productos de cada categoría.'  // Documentación o descripción del método
                     );
    $server->register( 'getDetails' ,                            // Nombre de la operación (método)
                        array('ISBN' => 'xsd:string'),     // Lista de parámetros; varios de tipo simple o un tipo complejo
                        array('return' => 'xsd:string'),        // Respuesta; de tipo simple o de tipo complejo
                        'urn:detalles',                         // Namespace para entradas (input) y salidas (output)
                        'urn:detalles#getDetails',                 // Nombre de la acción (soapAction)
                        'rpc',                                  // Estilo de comunicación (rpc|document)
                        'encoded',                              // Tipo de uso (encoded|literal)
                        'Nos da una lista de productos de cada categoría.'  // Documentación o descripción del método
                );
    $detalles = array( //ARREGLO DE DETALLES
        'LIB001' => [
        'Autor' => 'J.R.R. Tolkien',
        'Titulo' => 'El señor de los anillos',
        'Editorial' => 'Debolsillo',
        'Fecha' => 2019,
        'Precio' => 450.00,
        'Oferta' => false
        ],
        'LIB002' => [
        'Autor' => 'Asimov',
        'Titulo' => 'Los limites de la fundación',
        'Editorial' => 'Planeta',
        'Fecha' => 2022,
        'Precio' => 350.00,
        'Oferta' => false
        ],
        'LIB003' => [
        'Autor' => 'Obi Fernandez',
        'Titulo' => 'The Rails Way',
        'Editorial' => 'Addison-Wesley',
        'Fecha' => 2007,
        'Precio' => 250.00,
        'Oferta' => false
        ],

        'COM001' => [
        'Autor' => 'Artur Laperla',
        'Titulo' => 'Super Patata',
        'Editorial' => 'Bang Ediciones',
        'Fecha' => 2011,
        'Precio' => 229.00,
        'Oferta' => true
        ],
        'COM002' => [
        'Autor' => 'Eiichiro Oda',
        'Titulo' => 'One Piece',
        'Editorial' => 'Shueisha',
        'Fecha' => 1997,
        'Precio' => 529.00,
        'Oferta' => true
        ],
        'COM003' => [
        'Autor' => 'Bob Kane y Bill Finger',
        'Titulo' => 'Batman',
        'Editorial' => 'Bang Ediciones',
        'Fecha' => 1940,
        'Precio' => 229.00,
        'Oferta' => true
        ],

        'MAN001' => [
        'Autor' => 'Tite Kubo',
        'Titulo' => 'Bleach',
        'Editorial' => 'Viz Media',
        'Fecha' => 1997,
        'Precio' => 500.00,
        'Oferta' => false
        ],
        'MAN002' => [
        'Autor' => 'Takehiko Inoue',
        'Titulo' => 'Slam Dunk',
        'Editorial' => 'Panini',
        'Fecha' => 1997,
        'Precio' => 500.00,
        'Oferta' => false
        ],
        'MAN003' => [
        'Autor' => 'Gosho Aoyama',
        'Titulo' => 'Detective Conan',
        'Editorial' => 'Planeta Deagostini',
        'Fecha' => 2018,
        'Precio' => 800.00,
        'Oferta' => false
        ]
    );

    $productos = array(
        'libros' => [
            'LIB001' => 'El señor de los anillo',
            'LIB002' => 'Los limites de la Fundacion',
            'LIB003' => 'The Rails Way'
        ],
        'comics' => [
            'COM001' => 'Super Patata',
            'COM002' => 'One Piece',
            'COM003' => 'Bastman'
        ],
        'mangas' => [
            'MAN001' => 'Bleach',
            'MAN002' => 'Slam Dunk',
            'MAN003' => 'Detective Conan'
        ]
        );

    function getDetails($ISBN){
        $respuesta = '';

        $res = $ISBN;
        if(isset($GLOBALS["detalles"][$res])){
            $respuesta = $GLOBALS["detalles"][$res];
        }
        else {
            $respuesta = "No se encuentra el producto";
            error_log('categoria: '.$categoria);
            error_log('error: '.$respuesta);
        }
        return json_encode($respuesta,JSON_PRETTY_PRINT);
    };

    function getProd($categoria) {
    	$respuesta = ''; 

        $cat = strtolower($categoria); 

        if (isset($GLOBALS["productos"][$cat]) ) {
            $respuesta = $GLOBALS["productos"][$cat];
        } 
        else {
            $respuesta = "No se encuentra el producto";
            error_log('categoria: '.$categoria);
            error_log('error: '.$respuesta);
        }
        return json_encode($respuesta, JSON_PRETTY_PRINT);
    };
    

    /**
        IMPLEMENTACIÓN DE LA OPERACIÓN getProd
    */
    // function getProd($categoria) {
    // 	$respuesta = ''; 

    //     $cat = strtolower($categoria); 

    //     if ($cat == "libros") {
            
    //         $respuesta = join("|", array("El señor de los anillos",
    //                               "Los límites de la Fundación",
    //                              "The Rails Way"));
    //     } 
    //     elseif($cat == "comics"){
    //         $respuesta = join("|", array("Super Patata",
    //                                       "One Piece",
    //                                       "Bastman"));
    //     }
    //     elseif($cat == "mangas"){
    //         $respuesta = join("|", array("Bleach",
    //                                       "Slam Dunk",
    //                                       "Detective Conan"));
    //     }
    //     else {
    //         $respuesta = "No hay productos de esta categoria";
    //         error_log('categoria: '.$categoria);
    //         error_log('error: '.$respuesta);
    //     }

    //     return json_encode($respuesta);
    // }
    
       
        #var_dump($productos, json_encode($productos));
   


    /**
        EXPOSICIÓN DEL SERVICIO (WSDL)
    */
    // Exposición del servicio (WSDL)
    //$data = !empty($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:'';
    //@$server->service($data);

    // Exposición del servicio (WSDL) PHP v7.2 o superior
    @$server->service(file_get_contents("php://input"));
?>