<?php
	//header("Content-Type: text/xml; charset=UTF-8\r\n");
    ini_set("log_errors", 1);
    ini_set("error_log", "reportes/php-error-producto.log");

	require_once '../vendor/autoload.php';
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


    //Creación de tipos complejos
    $server->wsdl->addComplexType(
        'RespuestaGetProd',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'code' => ['name' => 'code', 'type' => 'xsd:string'],
            'message' => ['name' => 'message', 'type' => 'xsd:string'],
            'data' => ['name' => 'data', 'type' => 'xsd:string'],
            'status' => ['name' => 'status', 'type' => 'xsd:string']
        )
    );
    $server->wsdl->addComplexType(
        'RespuestaGetDetails',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'code' => ['name' => 'code', 'type' => 'xsd:string'],
            'message' => ['name' => 'message', 'type' => 'xsd:string'],
            'data' => ['name' => 'data', 'type' => 'xsd:string'],
            'status' => ['name' => 'status', 'type' => 'xsd:string'],
            'oferta' => ['name' => 'oferta', 'type' => 'xsd:boolean']
        )
    );
    /**
        REGISTRO DE LA OPERACIÓN getProd EN LA INTERFAZ DEL SERVICIO (WSDL)
    */
    $server->register( 'getProd',                               // Nombre de la operación (método)
                        array( 'user' => 'xsd:string',
                               'pass' => 'xsd:string' ,
                               'categoria' => 'xsd:string'),     // Lista de parámetros; varios de tipo simple o un tipo complejo
                        array('return' => 'tns:RespuestaGetProd'),        // Respuesta; de tipo simple o de tipo complejo
                        'urn:producto',                         // Namespace para entradas (input) y salidas (output)
                        'urn:producto#getProd',                 // Nombre de la acción (soapAction)
                        'rpc',                                  // Estilo de comunicación (rpc|document)
                        'encoded',                              // Tipo de uso (encoded|literal)
                        'Nos da una lista de productos de cada categoría.'  // Documentación o descripción del método
                     );

    $server->register( 'getDetails',                               // Nombre de la operación (método)
                        array( 'user' => 'xsd:string',
                               'pass' => 'xsd:string',
                               'isbn' => 'xsd:string'),     // Lista de parámetros; varios de tipo simple o un tipo complejo
                        array('return' => 'tns:RespuestaGetDetails'),        // Respuesta; de tipo simple o de tipo complejo
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

    //Arreglo de los diferentes tipos de respuesta
    $respuestas = array(
        200 => 'Categoría encontrada exitosamente.',
        201 => 'ISBN encontrado exitosamente.',
        300 => 'Categoría no encontrada.',
        301 => 'ISBN no encontrado.',
        500 => 'Usuario no reconocido.',
        501 => 'Password no reconocido.',
        999 => 'Error no identificado'
    );
    // Array de la respuesta para el metodo getProduct
    $RespuestaGetProd = array(
        'code' => 999,
        'message' => $respuestas[999],
        'data' => '',
        'status' => 'error'
    );

    //Array de la respuesta para el metodo getDetails
    $RespuestaGetDetails = array(
        'code' => 999,
        'message' => $respuestas[999],
        'data' => '',
        'status' => 'error',
        'oferta' => 'false'
    );
    //arreglo que gestiona a los usuarios y sus contraseñas
    $usuarios = array(
        'pruebas1' => 'de88e3e4ab202d87754078cbb2df6063',
        'pruebas2' => '6796ebbb111298d042de1a20a7b9b6eb',
        'pruebas3' => 'f7e999012e3700d47e6cb8700ee9cf19',
    );

    function getProd($user, $pass, $categoria) {
        $respuesta = '';
        $res = '';
        global $productos;
        global $usuarios;
        $categoria = strtolower($categoria);
        if(isset($usuarios[$user])){
            if($usuarios[$user] == md5($pass)){   
                if (isset($GLOBALS['productos'][$categoria])) {
                    $res = implode(" | ",$GLOBALS['productos'][$categoria]);
                    $RespuestaGetProd = array(
                        'code' => 200,
                        'message' => $GLOBALS['respuestas'][200],
                        'data' => $res,
                        'status' => 'exito'
                    );
                    return $RespuestaGetProd;
                } 
                else {
                    $resp = array(
                        'code' => 300,
                        'message' => $GLOBALS['respuestas'][300],
                        'data' => '',
                        'status' => 'error'
                    );
                    return $resp;
                }       
            }
            else {
                $resp = array(
                    'code' => 501,
                    'message' => $GLOBALS['respuestas'][501],
                    'data' => '',
                    'status' => 'error'
                );
                return $resp;
            }
        } else {
            $resp = array(
                'code' => 500,
                'message' => $GLOBALS['respuestas'][500],
                'data' => '',
                'status' => 'error'
            );
            return $resp;

        }  
        return $RespuestaGetProd;  
    };

    function getDetails($user, $pass, $isbn) {
        global $detalles;
        global $usuarios;
        $respuesta = "";
        $res = '';
        if(array_key_exists($user, $usuarios)){
            if($usuarios[$user] == md5($pass)){
                if ( array_key_exists($isbn, $detalles) ) {
                $res = implode("",$detalles[$isbn]);
                    $RespuestaGetDetails = array (
                        'code' => 201,
                                'message' => $GLOBALS['respuestas'][201],
                                'data' => $res,
                                'status' => 'exito',
                                'oferta' => 1
                    );
                    return $RespuestaGetDetails;
                }
                else {
                    $RespuestaGetDetails = array(
                        'code' => 301,
                        'message' => $GLOBALS['respuestas'][301],
                        'data' => '',
                        'status' => 'error',
                        'oferta' => 0 
                    );
                    return $RespuestaGetDetails;  
                }
            }
            else {
                $RespuestaGetDetails = array(
                    'code' => 501,
                    'message' => $GLOBALS['respuestas'][501],
                    'data' => '',
                    'status' => 'error',
                    'oferta' => 0 
                );
                return $RespuestaGetDetails;
            }
        } else {
            $RespuestaGetDetails = array(
                'code' => 500,
                'message' => $GLOBALS['respuestas'][500],
                'data' => '',
                'status' => 'error',
                'oferta' => 0 
            );
            return $RespuestaGetDetails;
        }
        return $RespuestaGetDetails;
    };

    
  
    // Exposición del servicio (WSDL)
    //$data = !empty($HTTP_RAW_POST_DATA)?$HTTP_RAW_POST_DATA:'';
    //@$server->service($data);

    // Exposición del servicio (WSDL) PHP v7.2 o superior
    @$server->service( file_get_contents("php://input") );
?>