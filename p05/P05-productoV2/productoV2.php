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
    $server->register( 'getProd',                               // Nombre de la operación (método)
                        array('categoria' => 'xsd:string'),     // Lista de parámetros; varios de tipo simple o un tipo complejo
                        array('return' => 'xsd:string'),        // Respuesta; de tipo simple o de tipo complejo
                        'urn:producto',                         // Namespace para entradas (input) y salidas (output)
                        'urn:producto#getProd',                 // Nombre de la acción (soapAction)
                        'rpc',                                  // Estilo de comunicación (rpc|document)
                        'encoded',                              // Tipo de uso (encoded|literal)
                        'Nos da una lista de productos de cada categoría.'  // Documentación o descripción del método
                     );

    $server->register( 'getDetails',                               // Nombre de la operación (método)
                        array('isbn' => 'xsd:string'),     // Lista de parámetros; varios de tipo simple o un tipo complejo
                        array('return' => 'xsd:string'),        // Respuesta; de tipo simple o de tipo complejo
                        'urn:producto',                         // Namespace para entradas (input) y salidas (output)
                        'urn:producto#getDetails',                 // Nombre de la acción (soapAction)
                        'rpc',                                  // Estilo de comunicación (rpc|document)
                        'encoded',                              // Tipo de uso (encoded|literal)
                        'Nos da una lista de detalles del isbn.'  // Documentación o descripción del método
                     );

    $productos = array(
        'libros' => [
            'LIB001' => 'El señor de los anillos',
            'LIB002' => 'Los límites de la Fundación',
            'LIB003' => 'The Rails Way'
        ],
        'comics' => [
            'COM001' => 'Comic 1',
            'COM002' => 'Comic 2',
            'COM003' => 'Comic 3'
        ],
        'mangas' => [
            'MAN001' => 'Manga 1',
            'MAN002' => 'Manga 2',
            'MAN003' => 'Manga 3'
        ]
    );

    $detalles = array(
        'LIB001' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'El señor de los anillos',
            'Editorial' => 'Editorial X',
            'Fecha'     => 1984,
            'Precio'    => 0.00,
            'Oferta'    => false
        ],
        'LIB002' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'Los límites de la Fundación',
            'Editorial' => 'Editorial X',
            'Fecha'     => 1995,
            'Precio'    => 0.00,
            'Oferta'    => false
        ],
        'LIB003' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'The Rails Way',
            'Editorial' => 'Editorial X',
            'Fecha'     => 2020,
            'Precio'    => 0.00,
            'Oferta'    => false
        ],
        'COM001' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'Comic 1',
            'Editorial' => 'Editorial X',
            'Fecha'     => 1985,
            'Precio'    => 0.00,
            'Oferta'    => false
        ],
        'COM002' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'Comic 2',
            'Editorial' => 'Editorial X',
            'Fecha'     => 1994,
            'Precio'    => 0.00,
            'Oferta'    => true
        ],
        'COM003' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'Comic 3',
            'Editorial' => 'Editorial X',
            'Fecha'     => 2019,
            'Precio'    => 0.00,
            'Oferta'    => false
        ],
        'MAN001' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'Manga 1',
            'Editorial' => 'Editorial X',
            'Fecha'     => 2000,
            'Precio'    => 0.00,
            'Oferta'    => false
        ],
        'MAN002' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'Manga 2',
            'Editorial' => 'Editorial X',
            'Fecha'     => 2003,
            'Precio'    => 0.00,
            'Oferta'    => true
        ],
        'MAN003' => [
            'Autor'     => 'Autor X',
            'Nombre'    => 'Manga 3',
            'Editorial' => 'Editorial X',
            'Fecha'     => 2021,
            'Precio'    => 0.00,
            'Oferta'    => false
        ]
    );

    function getProd($categoria) {
        global $productos;
        $categoria = strtolower($categoria);

        if ( array_key_exists($categoria, $productos) ) {
            $json_data = json_encode($productos[$categoria], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            //error_log( var_export($json_data, true) );    // USAR EN CASO DE RESULTADO INESPERADO
            return $json_data;
        }
        else {
            error_log($categoria);
            return "No hay productos de esta categoria";
        }
    }

    function getDetails($isbn) {
        global $detalles;

        if ( array_key_exists($isbn, $detalles) ) {
            $json_data = json_encode($detalles[$isbn], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            //error_log( var_export($json_data, true) );    // USAR EN CASO DE RESULTADO INESPERADO
            return $json_data;
        }
        else {
            error_log($isbn);
            return "No hay detalles de esta isbn";
        }
    }
  
    // Exposición del servicio (WSDL)
    //$data = !empty($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:'';
    //@$server->service($data);

    // Exposición del servicio (WSDL) PHP v7.2 o superior
    @$server->service( file_get_contents("php://input") );
?>