<?php
//namespace Models;
final class PessoasModel{
    
    var $resultado;
    var $Conn;

    function __construct(){
        
        require_once("database/ConexaoClass.php");
        $oConexao = new ConexaoClass();
        $oConexao -> OpenConnect();
        $this -> Conn = $oConexao -> GetConnect();

    }

    public function CountRows($id){
        
        $sql = "SELECT COUNT(*) as total_linhas FROM pessoas";
        if( $id != null ){
            $sql .= ", responsaveis_tem_dependentes";
            $sql .= " WHERE responsaveis_tem_dependentes.idResponsavel = $id";
            $sql .= " and pessoas.idPessoa = responsaveis_tem_dependentes.idDependente";
        }
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function ListThis( $nComecarPor, $nItensPorPagina, $id ){

        $sql = "SELECT idPessoa, nomePessoa, emailPessoa, usuarioPessoa";
        if( $id != null ){
            $sql .= ", responsaveis_tem_dependentes";
            $sql .= " WHERE responsaveis_tem_dependentes.idResponsavel = $id";
            $sql .= " and pessoas.idPessoa = responsaveis_tem_dependentes.idDependente";
        }
        $sql .= " FROM pessoas";
        $sql .= " ORDER BY dataCadastroPessoa DESC";
        $sql .= " LIMIT $nComecarPor, $nItensPorPagina";
        
        $this -> resultado = $this -> Conn -> query( $sql );

    }

    public function ConsultPessoa($id){

        $sql = "SELECT * FROM pessoas WHERE idPessoa = " . $id . ";" ;
        
        $this -> resultado = $this -> Conn -> query($sql);

    }

    public function InsertPessoa($arraypessoas){

        $sql = "INSERT INTO pessoas(
                `nomePessoa`,
                `identidadePessoa`,
                `emailPessoa`,
                `tipoPessoa`,
                `senhaPessoa`,
                `usuarioPessoa`,
                `dataCadastroPessoa`) 
                VALUE(
                    '" . $arraypessoas['nomePessoa'] . "', 
                    '" . $arraypessoas['identidadePessoa'] . "', 
                    '" . $arraypessoas['emailPessoa'] . "', 
                    '" . $arraypessoas['tipoPessoa'] . "', 
                    '" . $arraypessoas['senhaPessoa'] . "', 
                    '" . $arraypessoas['usuarioPessoa'] . "', 
                    now()
                    );";

        $this -> Conn -> query($sql);
        $this -> resultado = $this -> Conn -> insert_id;

    }

    public function UpdatePessoa($arraypessoas){

        $sql = "UPDATE pessoas 
            SET nomePessoa='" . $arraypessoas['nomePessoa'] . "'
                ,emailPessoa='" . $arraypessoas['emailPessoa'] . "'
                ,identidadePessoa='" . $arraypessoas['identidadePessoa'] . "'
                ,usuarioPessoa='" . $arraypessoas['usuarioPessoa'] . "'
                ,senhaPessoa='" . $arraypessoas['senhaPessoa'] . "'
                ,tipoPessoa='" . $arraypessoas['tipoPessoa'] . "' 
            WHERE idPessoa=" . $arraypessoas['idPessoa'] . ";" ;

        $this -> resultado = $this -> Conn -> query($sql);

    }

    public function DeletePessoa($idPessoa){

        $sql = "DELETE FROM pessoas WHERE idPessoa='" . $idPessoa . "';" ;

        $this -> resultado = $this -> Conn-> query($sql);

    }

    public function GetConsult(){
        return $this -> resultado;
    }

}

?>