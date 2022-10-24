<?php
//namespace Controlllers;
final class PessoasController extends BaseController {
    
    function __construct(){
		
		require_once("models/PessoasModel.php");
        $this -> Model = new PessoasModel();
		
    }

    public function ListThis(){

        $result = $this -> ListPagination();
        $arrayPessoas = array();
        if( $result != false ){
            if( $result -> num_rows > 0 ){ 
                while( $line = $result -> fetch_assoc() ) {
                    array_push( $arrayPessoas, $line );
                }
            }
        }

        header( 'Content-Type: application/json' );
        $pagination['page'] = $this -> nPagina;
        $pagination['pagesTotal'] = $this -> nTotalPaginas;
        $pagination['itemsPerPage'] = $this -> PaginacaoItensPorPagina;
        $pagination['itemsTotal'] = $this -> nTotalItens;
        $retorno['success'] = $this -> Model -> Conn -> affected_rows > 0 ? "true": "false";
        $retorno['pagination'] = $pagination;
        $retorno['data'] = $arrayPessoas;
        echo json_encode( $retorno );
        http_response_code(200);

    }

	public function ConsultPessoa( $id ){
		
        header( 'Content-Type: application/json' );
        if( isset($id) && strval($id) > 0 ){
            $this -> Model -> ConsultPessoa( $id );
            $result = $this -> Model -> GetConsult();
            $pessoa = $result -> fetch_assoc();

            $retorno['success'] = $this -> Model -> Conn -> affected_rows > 0 ? "true": "false";
            $retorno['data'] = $pessoa;
            echo json_encode($retorno);
            http_response_code(200);
        }else{
            $data['name'] = "Requisição Mal Feita";
            $data['message'] = "Requisição mal feita! Revisar a sintaxe!";
            $data['code'] = 0;
            $data['status'] = 400;
            $retorno['success'] = "false";
            $retorno['data'] = $data;
            echo json_encode( $retorno );
            http_response_code(400);
        }
		
	}

    public function InsertPessoa(){
		
        $oPessoa = json_decode( file_get_contents("php://input") );

        $arrayPessoas["nomePessoa"] = $oPessoa -> nomePessoa;
        $arrayPessoas["identidadePessoa"] = $oPessoa -> identidadePessoa;
        $arrayPessoas["emailPessoa"]  = $oPessoa -> emailPessoa;
        $arrayPessoas["tipoPessoa"] = $oPessoa -> tipoPessoa;
        $arrayPessoas["senhaPessoa"] = $oPessoa -> senhaPessoa;
        $arrayPessoas["usuarioPessoa"] = $oPessoa -> usuarioPessoa;

        $this -> Model -> InsertPessoa($arrayPessoas);
        $idPessoa = $this -> Model -> GetConsult();

        header('Content-Type: application/json');
        $data['idPessoa'] = $idPessoa;
        $retorno['success'] = $this -> Model -> Conn -> affected_rows > 0 ? "true": "false";
        $retorno['data'] = $data;
        echo json_encode($retorno);
        http_response_code(201);

    }

    public function UpdatePessoa( $idPessoa ){

		$oPessoa = json_decode(file_get_contents("php://input"));
        
		if( empty( $idPessoa ) ){
			$idPessoa = $oPessoa -> idPessoa;
		}
        
        header('Content-Type: application/json');
        if( !empty($idPessoa)){
            $arrayPessoas["idPessoa"] = $idPessoa;
            $arrayPessoas["nomePessoa"] = $oPessoa -> nomePessoa;
            $arrayPessoas["emailPessoa"] = $oPessoa -> emailPessoa;
            $arrayPessoas["senhaPessoa"] = $oPessoa -> senhaPessoa;
            $arrayPessoas["tipoPessoa"] = $oPessoa -> tipoPessoa;
            $this -> Model -> UpdatePessoa($arrayPessoas);
            $retorno['success'] = $this -> Model -> Conn -> affected_rows > 0 ? "true": "false";
            echo json_encode($retorno);
            http_response_code(200);
        }else{
            $data['name'] = "Requisição Mal Feita";
            $data['message'] = "Requisição mal feita! Revisar a sintaxe!";
            $data['code'] = 0;
            $data['status'] = 400;
            $retorno['success'] = "false";
            $retorno['data'] = $data;
            echo json_encode( $retorno );
            http_response_code(400);
        }
        
    }

    public function DeletePessoa( $idPessoa ){

		if( empty( $idPessoa ) ){
			$idPessoa = json_decode(file_get_contents("php://input")) -> idPessoa;
		}
        header('Content-Type: application/json');
        if( !empty($idPessoa)){
            $this -> Model -> DeletePessoa( $idPessoa );
            $retorno['success'] = $this -> Model -> Conn -> affected_rows > 0 ? "true": "false";
            echo json_encode($retorno);
            http_response_code(200);
        }else{
            $data['name'] = "Requisição Mal Feita";
            $data['message'] = "Requisição mal feita! Revisar a sintaxe!";
            $data['code'] = 0;
            $data['status'] = 400;
            $retorno['success'] = "false";
            $retorno['data'] = $data;
            echo json_encode( $retorno );
            http_response_code(400);
        }    

    }

}

?>