<?php

use CodeIgniter\HTTP\Response;

function _constructJSONAPI($data = null, $error = null, $meta = null) {
    $resp = array();

    if ($data === null && $error !== null) {
        $resp['error'] = $error;
    } else if ($data !== null && $error === null) {
        $resp['data'] = $data;
    }

    if (null !== $meta) {
        $resp['meta'] = $meta;
    }

    return $resp;
}

function _defaultNotFoundMessage()
{
    return array( 'message' => 'not found' );
}

function _defaultErrorMessage()
{
    return array( 'message' => 'unknown error' );
}

function _defaultNotAllowedMessage()
{
    return array( 'message' => 'method not allowed' );
}

function _defaultUnsupportedMessage()
{
    return array( 'message' => 'unsupported media type' );
}

function ResponseOK($data = array( 'ping' => 'ok' ), $meta = null)
{
    $response = service('response');
    $response->setStatusCode(200);
    $response->setJSON($data);
    $response->send();
}

function ResponseError($code = 500, $error = null)
{
    $response = service('response');
    $response->setStatusCode($code);
    $response->setJSON(_constructJSONAPI(null, ($error!==null ? $error : _defaultErrorMessage()), null));
    $response->send();
}

function ResponseCreated($data = array( 'message' => 'data created'))
{
    $response = service('response');
    $response->setStatusCode(201);
    $response->setJSON(_constructJSONAPI($data, null, null));
    $response->send();
}

function ResponseNotAllowed()
{
    ResponseError(405, _defaultNotAllowedMessage());
}

function ResponseNotFound()
{
    ResponseError(404, _defaultNotFoundMessage());
}

function ResponseUnsupportedMediaType()
{
    ResponseError(415, _defaultUnsupportedMessage());
}

function ResponseConflict($message = array('message' => 'record already exist'))
{
    ResponseError(409, $message);
}
