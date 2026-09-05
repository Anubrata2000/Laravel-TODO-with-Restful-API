<?php
/**
 * Formatting json result
 * @param type $httpStatusCode
 * @param type $data
 * @param type $message
 * @param type $http_response only sent when exception is thrown
 * @return type
 */
if ( !function_exists( 'renderJsonResponse' ) ) {
    function renderJsonResponse( $message, $httpStatusCode, $data = null ) {
        $response = [];

        if ( !is_null( $data ) ) {
            $response['data'] = $data;
        }

        $response['message'] = $message;
        $response['status_code'] = $httpStatusCode;

        return response()->json( $response, $httpStatusCode );
    }
}

if ( !function_exists( 'renderJSONResponse' ) ) {
    function renderJSONResponse( $message, $httpStatusCode, $data = null ) {
        return renderJsonResponse( $message, $httpStatusCode, $data );
    }
}